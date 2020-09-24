<?php


namespace App\Http\Controllers\API;

use App\FinancialRecords;
use Illuminate\Support\Facades\DB;

class FinaceController
{
    public function index()
     {

        $filter = request()->year;

        $finace = FinancialRecords::
            select(DB::raw("SUM(debit) as debit"), DB::raw("SUM(credit) as credit"), DB::raw("MONTHNAME(created_at) as monthname"))
            ->where('created_at', 'LIKE', '%' . $filter . '%')
            ->groupBy('monthname')
            ->get()
        ;

        $data = [];
        foreach ($finace as $row) {
            $data[] = [
                'date' => $row['monthname'],
                'debit' => $row['debit'],
                'credit' => $row['credit']
            ];
        }
        
        return $data;
    }
}