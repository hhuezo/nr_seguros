<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaEliminados;
use App\Models\temp\PolizaDeudaTempCartera;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


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

        $deuda = Deuda::findOrFail($poliza);
        $extra_primados = $deuda->extra_primados;

        foreach ($extra_primados as $extra_primado) {
            $extra_primado->Existe = PolizaDeudaTempCartera::where('NumeroReferencia', $extra_primado->NumeroReferencia)->count();
        }
        return view('polizas.deuda.extraprimados_excluidos',compact('extra_primados'));
    }
    // public function styles(Worksheet $sheet)
    // {
    //     // Aplica el formato de texto a la columna B (NumeroReferencia)
    //     $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
    //     $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    // }
}
