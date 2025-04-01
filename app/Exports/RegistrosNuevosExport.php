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
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrosNuevosExport implements FromCollection, WithHeadings
{
    /*protected $poliza;

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



        $nuevos_registros = DB::table('poliza_deuda_temp_cartera')
            ->where('Mes', $date->month)
            ->where('Axo', $date->year)
            ->where('PolizaDeuda', $poliza_id)
            ->whereNotIn('NumeroReferencia', $registro_mes_anterior_array)->get();

        return view('polizas.deuda.nuevos_registros', compact('nuevos_registros'));
    }
    public function styles(Worksheet $sheet)
    {
        // Aplica el formato de texto a la columna B (NumeroReferencia)
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
    }*/



    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {
        $registro_cartera = PolizaDeudaCartera::where('PolizaDeuda', $this->id)->orderBy('Axo', 'desc')->orderBy('Mes', 'desc')->first();

        $registro_mes_anterior = PolizaDeudaCartera::where('Mes', $registro_cartera->Mes)->where('Axo', $registro_cartera->Axo)->where('PolizaDeuda', $this->id)->get();
        $registro_mes_anterior_array = $registro_mes_anterior->pluck('NumeroReferencia')->toArray();

        $data = PolizaDeudaTempCartera::
            where('PolizaDeuda', $this->id)->where('User', auth()->user()->id)
            ->whereNotIn('NumeroReferencia', $registro_mes_anterior_array)
            ->join('saldos_montos as sm', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'sm.Id')
            ->join('tipo_cartera as tc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'tc.Id')
            ->select([
                'Nit',
                'Dui',
                'Pasaporte',
                'Nacionalidad',
                'FechaNacimiento',
                'TipoPersona',
                'PrimerApellido',
                'SegundoApellido',
                'ApellidoCasada',
                'PrimerNombre',
                'SegundoNombre',
                'NombreSociedad',
                'Sexo',
                'FechaOtorgamiento',
                'FechaVencimiento',
                'Ocupacion',
                DB::raw("CONCAT(NumeroReferencia, ' ') AS NumeroReferencia"),
                DB::raw("IF(MontoOtorgado IS NULL, '', ROUND(MontoOtorgado, 2)) AS MontoOtorgado"),
                DB::raw("IF(SaldoCapital IS NULL, '', ROUND(SaldoCapital, 2)) AS SaldoCapital"),
                DB::raw("IF(Intereses IS NULL, '', ROUND(Intereses, 2)) AS Intereses"),
                DB::raw("IF(InteresesMoratorios IS NULL, '', ROUND(InteresesMoratorios, 2)) AS InteresesMoratorios"),
                DB::raw("IF(InteresesCovid IS NULL, '', ROUND(InteresesCovid, 2)) AS InteresesCovid"),
                DB::raw("IF(MontoNominal IS NULL, '', ROUND(MontoNominal, 2)) AS MontoNominal"),
                DB::raw("IF(SaldoTotal IS NULL, '', ROUND(SaldoTotal, 2)) AS SaldoTotal"),
                DB::raw("IF(TotalCredito IS NULL, '', ROUND(TotalCredito, 2)) AS total_saldo"), // Prima Mensual
                'tc.Nombre as TipoCartera',
                DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
                // '' // Porcentaje Extraprima cambiar
            ])
            ->orderBy('NumeroReferencia')
            ->get();

        return $data;
    }


    public function headings(): array
    {
        return [
            'NIT',
            'DUI',
            'PASAPORTE O CARNET DE RESIDENTE ASEGURADO',
            'SALVADOREÑO',
            'FECHA NACIMIENTO',
            'TIPO DE PERSONA',
            'PRIMER APELLIDO',
            'SEGUNDO APELLIDO',
            'APELLIDO CASADA',
            'PRIMER NOMBRE',
            'SEGUNDO NOMBRE',
            'NOMBRE SOCIEDAD',
            'SEXO',
            'FECHA DE OTORGAMIENTO',
            'FECHA DE VENCIMIENTO',
            'OCUPACION',
            'No DE REFERENCIA DEL CRÉDITO',
            'MONTO OTORGADO DEL CREDITO',
            'SALDO VIGENTE DE CAPITAL',
            'INTERESES',
            'INTERESES MORATORIOS',
            'INTERESES COVID',
            'MONTO NOMINAL',
            'SALDO TOTAL',
            'PRIMA MENSUAL',
            'TIPO CARTERA',
            'LINEA CREDITO',
            //'PORCENTAJE EXTRAPRIMA'
        ];
    }
}
