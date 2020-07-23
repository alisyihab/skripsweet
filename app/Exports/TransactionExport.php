<?php

namespace App\Exports;

use App\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExport implements FromView, ShouldAutoSize
{
    protected $transaction;
    protected $month;
    protected $year;
    protected $total;

    public function __construct($transaction, $month, $year, $total)
    {
        $this->transaction = $transaction;
        $this->month = $month;
        $this->year = $year;
        $filter = request()->year . '-' .request()->month;
        $this->total = $this->total = $total = $total = DB::table("transactions")
            ->select(DB::raw("SUM(amount) as count"))
            ->where('created_at', 'LIKE', '%' . $filter . '%')
            ->where('status', 2)
            ->groupBy(DB::raw("month(created_at)"))
            ->get();;
    }

    public function view(): View
    {
        return view('export.transaction', [
            'transaction' => $this->transaction,
            'month' => $this->month,
            'year' => $this->year,
            'total' => $this->total
        ]);
    }
}
