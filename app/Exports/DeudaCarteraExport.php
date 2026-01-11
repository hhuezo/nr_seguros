<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DeudaCarteraExport implements FromView
{
    protected $cartera;

    public function __construct($cartera = null)
    {
        $this->cartera = $cartera;
    }
    public function view(): View
    {
        $cartera = $this->cartera;
        return view('reporte.exportar_cartera', compact('cartera'));
    }
}
