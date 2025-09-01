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
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrosEliminadosExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {
        $registro_cartera = PolizaDeudaCartera::where('PolizaDeuda', $this->id)->orderBy('Axo', 'desc')->orderBy('Mes', 'desc')->first();


        $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $this->id)->where('User', auth()->user()->id)->get();
        $poliza_temporal_array = $poliza_temporal->pluck('NumeroReferencia')->toArray();

        $data = PolizaDeudaCartera::where('PolizaDeuda', $this->id)
            ->where('Mes', $registro_cartera->Mes)->where('Axo', $registro_cartera->Axo)
            ->whereNotIn('NumeroReferencia', $poliza_temporal_array)
            ->join('saldos_montos as sm', 'poliza_deuda_cartera.LineaCredito', '=', 'sm.Id')
            ->join('tipo_cartera as tc', 'poliza_deuda_cartera.PolizaDeudaTipoCartera', '=', 'tc.Id')
            ->select([
                'Dui',
                'Pasaporte',
                'CarnetResidencia',
                'Nacionalidad',
                'FechaNacimiento',
                'TipoPersona',
                'Sexo',
                'PrimerApellido',
                'SegundoApellido',
                'ApellidoCasada',
                'PrimerNombre',
                'SegundoNombre',
                'NombreSociedad',
                'FechaOtorgamiento',
                'FechaVencimiento',

                DB::raw("CONCAT(NumeroReferencia, ' ') AS NumeroReferencia"),
                DB::raw("IF(MontoOtorgado IS NULL, '', ROUND(MontoOtorgado, 2)) AS MontoOtorgado"),
                DB::raw("IF(SaldoCapital IS NULL, '', ROUND(SaldoCapital, 2)) AS SaldoCapital"),
                DB::raw("IF(Intereses IS NULL, '', ROUND(Intereses, 2)) AS Intereses"),
                DB::raw("IF(InteresesMoratorios IS NULL, '', ROUND(InteresesMoratorios, 2)) AS InteresesMoratorios"),
                DB::raw("IF(InteresesCovid IS NULL, '', ROUND(InteresesCovid, 2)) AS InteresesCovid"),

                'Tasa',
                'TipoDeuda',
                'PorcentajeExtraprima',

                /*DB::raw("IF(MontoNominal IS NULL, '', ROUND(MontoNominal, 2)) AS MontoNominal"),
                DB::raw("IF(SaldoTotal IS NULL, '', ROUND(SaldoTotal, 2)) AS SaldoTotal"),

                DB::raw("IF(TotalCredito IS NULL, '', ROUND(TotalCredito, 2)) AS total_saldo"), // Prima Mensual*/
                'tc.Nombre as TipoCartera',
                DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),

            ])
            ->groupBy('NumeroReferencia')
            ->orderBy('NumeroReferencia')
            ->get();


        return $data;
    }


    public function headings(): array
    {
       return [
            'DUI',
            'PASAPORTE',
            'CARNET RESI',
            'NACIONALIDAD',
            'FECNACIMIENTO',
            'TIPO PERSONA',
            'GENERO',
            'PRIMERAPELLIDO',
            'SEGUNDOAPELLIDO',
            'APELLIDOCASADA',
            'PRIMERNOMBRE',
            'SEGUNDONOMBRE',
            'NOMBRE SOCIEDAD',
            'FECOTORGAMIENTO',
            'FECHA DE VENCIMIENTO',

            'NUMREFERENCIA',
            'MONTO OTORGADO',
            'SALDO DE CAPITAL',
            'INTERES CORRIENTES',
            'INTERES MORATORIO',
            'INTERES COVID',

            'TARIFA',
            'TIPO DE DEUDA',
            'PORCENTAJE EXTRAPRIMA',

            'TIPO CARTERA',
            'LINEA CREDITO',
        ];
    }
}
