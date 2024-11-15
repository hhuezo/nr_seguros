<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaEliminados;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistroRequisitosExport implements FromView, ShouldAutoSize
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
        $requisitos = $deuda->requisitos;
        $poliza_eliminados = DeudaEliminados::where('Poliza', $poliza)->groupBy('NumeroReferencia')->get();
        $poliza_eliminados_array = $poliza_eliminados->pluck('NumeroReferencia')->toArray();


        $poliza_cumulos = DB::table('poliza_deuda_temp_cartera')
            ->select(
                'Id',
                'Dui',
                'Edad',
                'Nit',
                'PrimerNombre',
                'SegundoNombre',
                'PrimerApellido',
                'SegundoApellido',
                'ApellidoCasada',
                'FechaNacimiento',
                'NumeroReferencia',
                'NoValido',
                'Perfiles',
                'EdadDesembloso',
                'FechaOtorgamiento',
                'NoValido',
                'Excluido',
                DB::raw('SUM(saldo_total) as total_saldo'),
                DB::raw("GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                //  DB::raw('SUM(SaldoCapital) as saldo_cpital'),
                DB::raw('SUM(SaldoCapital) as saldo_capital'),
                DB::raw('SUM(Intereses) as total_interes'),
                DB::raw('SUM(InteresesCovid) as total_covid'),
                DB::raw('SUM(InteresesMoratorios) as total_moratorios'),
                DB::raw('SUM(MontoNominal) as total_monto_nominal')
            )

            ->where('Edad', '<', $deuda->EdadMaximaTerminacion)
            ->where('NoValido', 0)
            ->where('PolizaDeuda', $poliza)
            ->groupBy('Dui')
            ->get();


        foreach ($poliza_cumulos as $poliza) {
            if (in_array($poliza->NumeroReferencia, $poliza_eliminados_array)) {
                $poliza->Rehabilitado = 1;
            } else {
                $poliza->Rehabilitado = 0;
            }
        }



        return view('polizas.deuda.get_creditos_excel', compact('poliza_cumulos', 'requisitos'));
    }
    public function styles(Worksheet $sheet)
    {
        // Aplica el formato de texto a la columna B (NumeroReferencia)
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    }
}
