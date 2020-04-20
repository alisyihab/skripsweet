<?php

namespace App\Http\Controllers\API;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
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
}
