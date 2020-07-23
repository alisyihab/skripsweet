<?php

namespace App\Http\Controllers\API;

use App\Transaction;
use App\Expense;
use App\User;
use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\TransactionCollection;
use App\Http\Controllers\Controller;
use App\Exports\TransactionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Excel;

class DashboardController extends Controller
{
    /**
     * @return array
     */
    public function chart()
    {
        $filter = request()->year . '-' .request()->month;
        $parse = Carbon::parse($filter);
        $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));

        $transaction = Transaction::where('status', 2)
            ->select(DB::raw('date(created_at) as date,sum(amount) as total'))
            ->where('created_at', 'LIKE', '%' . $filter . '%')
            ->groupBy(DB::raw('date(created_at)'))
            ->get();

        $data = [];
        foreach ($array_date as $row) {
            $f_date = strlen($row) == 1 ? 0 . $row:$row;
            $date = $filter . '-' . $f_date;
            $total = $transaction->firstWhere('date', $date);
            $data[] = [
                'date' => $date,
                'total' => $total ? $total->total:0
            ];
        }

        return $data;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function exportData(Request $request)
    {
        $transaction = $this->chart();
        $total = $this->chart();
        return Excel::download(
            new TransactionExport(
                $transaction, request()->month,
                request()->year,
                $total
            ), 'transaction.xlsx'
        );
    }

    public function data()
    {
        $tot_income = Transaction::where('status', 2)->sum('amount');
        $tot_orders = Transaction::count();
        $tot_expanses = Expense::where('status', 1)->sum('price');
        $tot_cus =  User::where('role', '=', '3')->count();

        return response()->json([
            'income' => $tot_income,
            'orders' => $tot_orders,
            'expanse' => $tot_expanses,
            'customer' => $tot_cus
        ]);
    }

    public function getExpanse()
    {
        $filter = request()->year;

        $expenses = Expense::where('status', 1)
            ->select(DB::raw("(SUM(price)) as total"),DB::raw("MONTHNAME(created_at) as monthname"))
            ->whereYear('created_at', date('Y'))
            ->where('created_at', 'LIKE', '%' . $filter . '%')
            ->groupBy('monthname')
            ->get();

        $data = [];
        foreach ($expenses as $row) {
            $data[] = [
                'date' => $row['monthname'],
                'total' => $row['total']
            ];
        }

        return $data;
    }

    public function getTransaction()
    {
        $user = request()->user();

        $transaction = Transaction::with(['user', 'detail', 'customer'])
            ->orderBy('created_at', 'DESC');
        
        if ($user->role != 0 && $user->role != 1) {
            $transaction = $transaction
                ->where('customer_id', $user->id)
            ;
        }

        $transaction = $transaction->take(3)->get();

        return new TransactionCollection($transaction);
    }

    public function countTrans()
    {
        $user = request()->user();

        $transaction = Transaction::where('customer_id', $user->id)->count();

        return response()->json([
            'data' => $transaction
        ]);
    }

    public function getExpenseDashboard()
    {
        $user = request()->user();
        $expenses = Expense::with(['user'])->orderBy('created_at', 'DESC');

        if ($user->role = 1) {
            $transaction = $expenses
                ->where('user_id', $user->id)
            ;
        }

        return (new ExpenseCollection(
            $expenses->take(5)->get())
        );
    }
}
