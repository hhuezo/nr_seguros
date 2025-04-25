<?php

namespace App\Exports\vida;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class VidaFedeExport  implements FromView
{
    protected $cartera;

    public function __construct($cartera = null)
    {
        $this->cartera = $cartera;
    }
    public function view(): View
    {
        $cartera = $this->cartera;

       // dd($empleados);

        return view('reporte.exportar_fede', compact('cartera'));
    }
}
