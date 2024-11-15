<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrosEliminadosExport implements FromView, ShouldAutoSize
{
    protected $poliza;

    public function __construct($poliza = null)
    {
        $this->poliza = $poliza;
    }
    public function view(): View
    {
        $poliza = $this->poliza;

        $poliza_id = $poliza;
        $deuda = Deuda::findOrFail($poliza);

        $temp_data_fisrt = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->where('User', auth()->user()->id)->first();
        $date_submes = Carbon::create($temp_data_fisrt->Axo, $temp_data_fisrt->Mes, "01");
        $date = Carbon::create($temp_data_fisrt->Axo, $temp_data_fisrt->Mes, "01");
        $date_mes = $date_submes->subMonth();
        $date_anterior = Carbon::create($temp_data_fisrt->Axo, $temp_data_fisrt->Mes, "01");
        $date_mes_anterior = $date_anterior->subMonth();

        $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->where('User', auth()->user()->id)->get();


        $registro_mes_anterior = PolizaDeudaCartera::where('Mes', $date_anterior->month)->where('Axo', $date_mes_anterior->year)->where('PolizaDeuda', $poliza_id)->get();
        $registro_mes_anterior_array = $registro_mes_anterior->pluck('NumeroReferencia')->toArray();
        $poliza_temporal_array = $poliza_temporal->pluck('NumeroReferencia')->toArray();



        $registros_eliminados =  $registro_mes_anterior->whereNotIn('NumeroReferencia', $poliza_temporal_array);

            return view('polizas.deuda.registros_eliminados', compact('registros_eliminados'));



        
    }
    public function styles(Worksheet $sheet)
    {
        // Aplica el formato de texto a la columna B (NumeroReferencia)
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    }
}
