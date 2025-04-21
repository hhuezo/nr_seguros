<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExtraPrimadosExcluidosExport implements FromView, ShouldAutoSize
{
    protected $poliza;

    public function __construct($poliza = null)
    {
        $this->poliza = $poliza;
    }
    public function view(): View
    {
        $poliza = $this->poliza;

        $vida = Vida::findOrFail($poliza);
        $extra_primados = $vida->extra_primados;

        foreach ($extra_primados as $extra_primado) {
            $extra_primado->Existe = VidaCarteraTemp::where('NumeroReferencia', $extra_primado->NumeroReferencia)->count();
        }
        return view('polizas.deuda.extraprimados_excluidos',compact('extra_primados'));
    }
}
