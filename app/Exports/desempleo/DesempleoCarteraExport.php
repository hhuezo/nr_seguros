<?php

namespace App\Exports\desempleo;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class DesempleoCarteraExport implements FromView
{
    protected $cartera;

    public function __construct($cartera = null)
    {
        $this->cartera = $cartera;
    }
    public function view(): View
    {
        $cartera = $this->cartera;
        return view('reporte.exportar_cartera_desempleo', compact('cartera'));
    }
}
