<?php


namespace App\Http\Controllers\API;

use App\Exports\FinaceExport;
use App\FinancialRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FinaceController
{
    public function chart()
    {

        $filter = request()->year;

        $finace = FinancialRecords::
            select(
                DB::raw("SUM(CASE WHEN type = '0' THEN amount ELSE 0 END) AS debit, SUM(CASE WHEN type = '1' THEN amount ELSE 0 END) AS credit"),
                DB::raw("MONTHNAME(created_at) as monthname"),
            )
            ->where('created_at', 'LIKE', '%' . $filter . '%')
            ->groupBy(DB::raw('MONTHNAME(created_at)'))
            ->get()
        ;

        $data = [];
        foreach ($finace as $row) {
            $data[] = [
                'date' => $row['monthname'],
                'debit' => $row['debit'],
                'credit' => $row['credit'],
            ];
        }
        
        return $data;
    }

    public function exportData(Request $request)
    {
        $finace = $this->chart();

        return Excel::download(new FinaceExport($finace, request()->month, request()->year), 'keuangan.xlsx');
    }
}