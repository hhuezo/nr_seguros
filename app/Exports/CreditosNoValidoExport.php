<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\temp\PolizaDeudaTempCartera;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CreditosNoValidoExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $deuda = Deuda::findOrFail($this->id);
        // Fedecrédito: nuevo formato (21 columnas acordadas hasta TARIFA) + Motivo
        if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4) {

            $data = PolizaDeudaTempCartera::from('poliza_deuda_temp_cartera as pdtc')
                ->where('pdtc.PolizaDeuda', $this->id)
                ->where(function ($query) use ($deuda) {
                    $query->where('pdtc.NoValido', 1)
                        ->orWhere(function ($subquery) use ($deuda) {
                            $subquery->where('pdtc.EdadDesembloso', '>', $deuda->EdadMaximaTerminacion)
                                ->orWhere('pdtc.TotalCredito', '>', $deuda->ResponsabilidadMaxima);
                        });
                })
                ->select([
                    'pdtc.TipoDocumento',
                    'pdtc.Dui',
                    'pdtc.PrimerApellido',
                    'pdtc.SegundoApellido',
                    'pdtc.ApellidoCasada',
                    'pdtc.PrimerNombre',
                    'pdtc.SegundoNombre',
                    DB::raw("'' AS TercerNombre"),
                    'pdtc.Nacionalidad',
                    'pdtc.FechaNacimiento',
                    'pdtc.Sexo',
                    DB::raw("CONCAT(pdtc.NumeroReferencia, ' ') AS NumeroReferencia"),
                    'pdtc.FechaOtorgamiento',
                    DB::raw("IF(pdtc.MontoOtorgado IS NULL, '', ROUND(pdtc.MontoOtorgado, 2)) AS MontoOtorgado"),
                    DB::raw("IF(pdtc.SaldoCapital IS NULL, '', ROUND(pdtc.SaldoCapital, 2)) AS SaldoCapital"),
                    DB::raw("IF(pdtc.Intereses IS NULL, '', ROUND(pdtc.Intereses, 2)) AS Intereses"),
                    DB::raw("IF(COALESCE(pdtc.MoraCapital, pdtc.SaldoInteresMora) IS NULL, '', ROUND(COALESCE(pdtc.MoraCapital, pdtc.SaldoInteresMora), 2)) AS MoraCapital"),
                    DB::raw("IF(pdtc.InteresesMoratorios IS NULL, '', ROUND(pdtc.InteresesMoratorios, 2)) AS InteresesMoratorios"),
                    DB::raw("IF(pdtc.InteresesCovid IS NULL, '', ROUND(pdtc.InteresesCovid, 2)) AS InteresesCovid"),
                    'pdtc.PorcentajeExtraprima',
                    'pdtc.Tasa',
                    'pdtc.EdadDesembloso',
                    DB::raw('pdtc.TotalCredito AS saldo_total'),
                ])
                ->groupBy('pdtc.NumeroReferencia')
                ->orderBy('pdtc.NumeroReferencia')
                ->get();
            // Formato anterior: leftJoin sm, pd_tipo, tc; sin ApellidoCasada, SegundoNombre, TercerNombre; TipoCartera, LineaCredito; groupBy con tc.Id
        } else {
            $data = PolizaDeudaTempCartera::where('poliza_deuda_temp_cartera.PolizaDeuda', $this->id)
                ->where('NoValido', 1)
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

                    /*DB::raw("IF(MontoNominal IS NULL, '', ROUND(MontoNominal, 2)) AS MontoNominal"),
                DB::raw("IF(SaldoTotal IS NULL, '', ROUND(SaldoTotal, 2)) AS SaldoTotal"),

                DB::raw("IF(TotalCredito IS NULL, '', ROUND(TotalCredito, 2)) AS total_saldo"), // Prima Mensual*/
                    'tc.Nombre as TipoCartera',
                    DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
                ])
                ->groupBy('NumeroReferencia')
                ->orderBy('NumeroReferencia')
                ->get();
        }

        // Obtener los rangos de asegurabilidad de la póliza
        $edades = DB::table('poliza_deuda_requisitos')
            ->where('Deuda', $this->id)
            ->selectRaw('
            MIN(EdadInicial) AS EdadInicial,
            MAX(EdadFinal) AS EdadFinal,
            MIN(MontoInicial) AS MontoInicial,
            MAX(MontoFinal) AS MontoFinal
        ')
            ->first();

        foreach ($data as $cumulo) {
            if ($cumulo->EdadDesembloso < $edades->EdadInicial || $cumulo->EdadDesembloso > $edades->EdadFinal) {
                $cumulo->Motivo = 'Revisar edad: fuera de los rangos de asegurabilidad.';
            } elseif ($cumulo->saldo_total > $edades->MontoFinal) {
                $cumulo->Motivo = 'Excede el límite de suma asegurada del rango permitido.';
            } else {
                $cumulo->Motivo = 'No cumple los criterios de asegurabilidad.';
            }
        }


        return $data;
    }


    public function headings(): array
    {
        $deuda = Deuda::findOrFail($this->id);

        if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4) {
            // Fedecrédito: 21 columnas acordadas (hasta TARIFA) + columnas para cálculo + MOTIVO
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
                'Edad desembolso',
                'Saldo total',
                'MOTIVO',
            ];
            // Formato anterior: 'Nombres', 'TIPO CARTERA', 'LINEA CREDITO', 'MOTIVO'
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
                'MOTIVO',
            ];
        }
    }
}
