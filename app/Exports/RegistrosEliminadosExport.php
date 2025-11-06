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


        $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $this->id)->get();
        $poliza_temporal_array = $poliza_temporal->pluck('NumeroReferencia')->toArray();

        $poliza_id = $this->id;
        $axoAnterior = $registro_cartera->Axo;
        $mesAnterior = $registro_cartera->Mes;



        $registros_eliminados = collect(DB::select("
            SELECT pdc.*
            FROM poliza_deuda_cartera pdc
            WHERE pdc.PolizaDeuda = ?
            AND pdc.Axo = ?
            AND pdc.Mes = ?
            AND NOT EXISTS (
                SELECT 1
                FROM poliza_deuda_temp_cartera pdtc
                WHERE pdtc.NumeroReferencia = pdc.NumeroReferencia
                    AND pdtc.PolizaDeuda = ?
            )
        ", [$poliza_id, $axoAnterior, $mesAnterior, $poliza_id]));



        $data = PolizaDeudaCartera::from('poliza_deuda_cartera as pdc')
            ->where('pdc.PolizaDeuda', $poliza_id)
            ->where('pdc.Axo', $axoAnterior)
            ->where('pdc.Mes', $mesAnterior)
            ->whereNotExists(function ($query) use ($poliza_id) {
                $query->select(DB::raw(1))
                    ->from('poliza_deuda_temp_cartera as pdtc')
                    ->whereColumn('pdtc.NumeroReferencia', 'pdc.NumeroReferencia')
                    ->where('pdtc.PolizaDeuda', $poliza_id);
            })
            // ->join('saldos_montos as sm', 'pdc.LineaCredito', '=', 'sm.Id')
            // ->join('tipo_cartera as tc', 'pdc.PolizaDeudaTipoCartera', '=', 'tc.Id')
            ->select([
                'pdc.Dui',
                'pdc.Pasaporte',
                'pdc.CarnetResidencia',
                'pdc.Nacionalidad',
                'pdc.FechaNacimiento',
                'pdc.TipoPersona',
                'pdc.Sexo',
                'pdc.PrimerApellido',
                'pdc.SegundoApellido',
                'pdc.ApellidoCasada',
                'pdc.PrimerNombre',
                'pdc.SegundoNombre',
                'pdc.NombreSociedad',
                'pdc.FechaOtorgamiento',
                'pdc.FechaVencimiento',
                DB::raw("CONCAT(pdc.NumeroReferencia, ' ') AS NumeroReferencia"),
                DB::raw("IF(pdc.MontoOtorgado IS NULL, '', ROUND(pdc.MontoOtorgado, 2)) AS MontoOtorgado"),
                DB::raw("IF(pdc.SaldoCapital IS NULL, '', ROUND(pdc.SaldoCapital, 2)) AS SaldoCapital"),
                DB::raw("IF(pdc.Intereses IS NULL, '', ROUND(pdc.Intereses, 2)) AS Intereses"),
                DB::raw("IF(pdc.InteresesMoratorios IS NULL, '', ROUND(pdc.InteresesMoratorios, 2)) AS InteresesMoratorios"),
                DB::raw("IF(pdc.InteresesCovid IS NULL, '', ROUND(pdc.InteresesCovid, 2)) AS InteresesCovid"),
                'pdc.Tasa',
                'pdc.TipoDeuda',
                'pdc.PorcentajeExtraprima',
                //'tc.Nombre as TipoCartera',
                // DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
            ])
            ->orderBy('pdc.NumeroReferencia')
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

            //'TIPO CARTERA',
            //'LINEA CREDITO',
        ];
    }
}
