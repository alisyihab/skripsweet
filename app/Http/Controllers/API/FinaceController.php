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
            select(
                DB::raw("SUM(CASE WHEN type = '0' THEN amount ELSE 0 END) AS debit"), 
                DB::raw("SUM(CASE WHEN type = '1' THEN amount ELSE 0 END) AS credit"),
                DB::raw("MONTHNAME(created_at) as monthname"),  
            )
            ->where('created_at', 'LIKE', '%' . $filter . '%')
            ->groupBy('monthname')
            ->get()
        ;

        $data = [];
        foreach ($finace as $row) {
            $data[] = [
                'date' => $row['monthname'],
                'debit' => $row['debit'],
                'credit' => $row['credit'],
                // 'note' => $row['note']
            ];
        }
        
        return $data;
    }
}