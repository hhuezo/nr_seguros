<?php

namespace App\Exports\vida;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class VidaCarteraExport implements FromView
{
    protected $cartera;

    public function __construct($cartera = null)
    {
        $this->cartera = $cartera;
    }
    public function view(): View
    {
        $cartera = $this->cartera;
        return view('reporte.exportar_cartera_vida', compact('cartera'));
    }
}
