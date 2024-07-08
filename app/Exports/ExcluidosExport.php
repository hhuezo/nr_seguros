<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExcluidosExport implements FromView,ShouldAutoSize
{
    protected $excluidos;
    protected $tipo;


    public function __construct($excluidos = null,$tipo = null)
    {
        $this->excluidos = $excluidos;
        $this->tipo = $tipo;
    }
    public function view(): View
    {
        $excluidos = $this->excluidos;
        $tipo = $this->tipo;


        //dd($excluidos);
        if($tipo == 1){
            return view('reporte.excluidos_edad', compact('excluidos'));
        }else{
            return view('reporte.excluidos_dinero', compact('excluidos'));
        }
    }
}
