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
    protected $mes;
    protected $deuda;


    public function __construct($excluidos = null,$tipo = null,$mes = null,$deuda= null)
    {
        $this->excluidos = $excluidos;
        $this->tipo = $tipo;
        $this->mes = $mes;
        $this->deuda = $deuda;
    }
    public function view(): View
    {
        $excluidos = $this->excluidos;
        $tipo = $this->tipo;
        $mes = $this->mes;
        $deuda = $this->deuda;


        //dd($excluidos);
        if($tipo == 1){
            return view('reporte.excluidos_edad', compact('excluidos','mes'));
        }else{
            return view('reporte.excluidos_dinero', compact('excluidos','deuda'));
        }
    }
}
