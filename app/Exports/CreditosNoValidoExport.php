<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class CreditosNoValidoExport implements FromView
{
    use Exportable;
    protected $poliza_cumulos;

    public function __construct($poliza_cumulos = null)
    {
        $this->poliza_cumulos = $poliza_cumulos;
    }
    public function view(): View
    {
        $poliza_cumulos = $this->poliza_cumulos;

       // dd($empleados);

        return view('polizas.deuda.credito_no_validos', compact('poliza_cumulos'));
    }
}