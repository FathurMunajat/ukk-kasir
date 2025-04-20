<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPenjualanExport implements FromView
{
    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function view(): View
    {
        return view('exports.laporan', [
            'purchases' => $this->purchases
        ]);
    }
}
