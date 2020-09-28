<?php

namespace App\Exports;

use App\FinancialRecords;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FinaceExport implements FromView, ShouldAutoSize
{
    protected $finace;
    protected $month;
    protected $year;
    
    public function __construct($finace, $month, $year)
    {
        $this->finace = $finace;
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View 
    {
        return view('export.finace', [
            'finace' => $this->finace,
            'month' => $this->month,
            'year' => $this->year,
        ]);
    }
}
