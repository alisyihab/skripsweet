<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Exports\TransactionExport;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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

        $transaction = Transaction::select(DB::raw('date(created_at) as date,sum(amount) as total'))
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
        $total_cus = Customer::count();
        $tot_income = Transaction::sum('amount');
        $tot_orders = Transaction::count();

        return response()->json([
            'customer' => $total_cus,
            'income' => $tot_income,
            'orders' => $tot_orders
        ]);
    }
}
