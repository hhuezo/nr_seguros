<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaDetalle;
use Illuminate\Contracts\View\View;
use App\Models\polizas\DeudaHistorialRecibo;
use Maatwebsite\Excel\Concerns\FromView;

class DeudaReciboExport implements FromView
{
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function view(): View
    {

        $id = $this->id;
        $detalle = DeudaDetalle::findOrFail($id);

        //dd($detalle->Deuda);
        $deuda = Deuda::findOrFail($detalle->Deuda);

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = DeudaHistorialRecibo::where('PolizaDeudaDetalle', $id)->orderBy('id', 'desc')->first();

        return view('polizas.deuda.recibo_excel', compact('recibo_historial','detalle', 'deuda', 'meses'));
    }
}
