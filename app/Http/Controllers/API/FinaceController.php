<?php


namespace App\Http\Controllers\API;

use App\FinancialRecords;

class FinaceController
{
    public function index()
     {

        $debit = FinancialRecords::where('type', 0)->sum('amount');
        $credit = FinancialRecords::where('type', 1)->sum('amount');

        return response()->json([
            'debit' => $debit,
            'credit' => $credit
        ]);
    }
}