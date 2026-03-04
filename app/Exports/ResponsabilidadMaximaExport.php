<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\temp\PolizaDeudaTempCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResponsabilidadMaximaExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $deuda = Deuda::findOrFail($this->id);

        $responsabilidadMaxima = $deuda->ResponsabilidadMaxima ?? 1000000;



        // Fedecrédito: nuevo formato (21 columnas acordadas hasta TARIFA)
        if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4) {
            $data = PolizaDeudaTempCartera::where('poliza_deuda_temp_cartera.PolizaDeuda', $this->id)
                ->where('TotalCredito', '>', $responsabilidadMaxima)
                ->select([
                    'TipoDocumento',
                    'Dui',
                    'PrimerApellido',
                    'SegundoApellido',
                    'ApellidoCasada',
                    'PrimerNombre',
                    'SegundoNombre',
                    DB::raw("'' AS TercerNombre"),
                    'Nacionalidad',
                    'FechaNacimiento',
                    'Sexo',
                    DB::raw("CONCAT(NumeroReferencia, ' ') AS NumeroReferencia"),
                    'FechaOtorgamiento',
                    DB::raw("IF(MontoOtorgado IS NULL, '', ROUND(MontoOtorgado, 2)) AS MontoOtorgado"),
                    DB::raw("IF(SaldoCapital IS NULL, '', ROUND(SaldoCapital, 2)) AS SaldoCapital"),
                    DB::raw("IF(Intereses IS NULL, '', ROUND(Intereses, 2)) AS Intereses"),
                    DB::raw("IF(COALESCE(MoraCapital, SaldoInteresMora) IS NULL, '', ROUND(COALESCE(MoraCapital, SaldoInteresMora), 2)) AS MoraCapital"),
                    DB::raw("IF(InteresesMoratorios IS NULL, '', ROUND(InteresesMoratorios, 2)) AS InteresesMoratorios"),
                    DB::raw("IF(InteresesCovid IS NULL, '', ROUND(InteresesCovid, 2)) AS InteresesCovid"),
                    'PorcentajeExtraprima',
                    'Tasa',
                ])
                ->orderBy('NumeroReferencia')
                ->get();
            // Formato anterior Fedecrédito (por si deciden volver): select con join sm, pdtc, tc y columnas TipoCartera, LineaCredito
        } else {
            $data = PolizaDeudaTempCartera::where('poliza_deuda_temp_cartera.PolizaDeuda', $this->id)
                ->where('TotalCredito', '>', $responsabilidadMaxima)
                ->join('saldos_montos as sm', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'sm.Id')
                ->join('poliza_deuda_tipo_cartera as pdtc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'pdtc.Id')
                ->join('tipo_cartera as tc', 'pdtc.TipoCartera', '=', 'tc.Id')
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
                    'tc.Nombre as TipoCartera',
                    DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
                ])
                ->orderBy('NumeroReferencia')
                ->get();
        }

        return $data;
    }


    public function headings(): array
    {
        $deuda = Deuda::findOrFail($this->id);

        if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4) {
            // Fedecrédito: 21 columnas acordadas (hasta TARIFA)
            return [
                'Tipo de documento',
                'DUI o documento de identidad',
                'Primer Apellido',
                'Segundo Apellido',
                'Apellido de casada',
                'primer nombre',
                'segundo nombre',
                'tercer nombre',
                'Nacionalidad',
                'Fecha de Nacimiento',
                'Género',
                'Nro. de Préstamo',
                'Fecha de otorgamiento',
                'Monto original de desembolso',
                'Saldo de deuda capital actual',
                'Saldo intereses corrientes',
                'Mora capital',
                'Saldo intereses por mora',
                'Intereses Covid',
                'Extra Prima',
                'TARIFA',
            ];
            // Formato anterior: 'Nombres' en lugar de apellido casada + 3 nombres, y 'TIPO CARTERA','LINEA CREDITO' al final
        } else {
            // Otras aseguradoras
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
}
