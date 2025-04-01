<?php

namespace App\Exports;

use App\Models\polizas\PolizaDeudaCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpParser\Node\Expr\AssignOp\Concat;

class HistoricoPagosExport implements FromCollection, WithHeadings
{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }




    public function collection()
    {

        $data = PolizaDeudaCartera::where('Axo', $this->request->Axo)
            ->where('Mes', $this->request->Mes)
            ->where('FechaInicio', $this->request->FechaInicio)
            ->where('FechaFinal', $this->request->FechaFinal)
            ->join('poliza_deuda_creditos as pdc', 'poliza_deuda_cartera.LineaCredito', '=', 'pdc.Id')
            ->join('saldos_montos as sm', 'pdc.saldos', '=', 'sm.id')
            ->join('tipo_cartera as tc', 'pdc.TipoCartera', '=', 'tc.id')
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
            //->take(10)
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
