<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeudaCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrosEliminadosExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        $poliza_id = $this->id;

        $registro_cartera = PolizaDeudaCartera::where('PolizaDeuda', $poliza_id)
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        if (!$registro_cartera) {
            return collect([]);
        }

        $axoAnterior = $registro_cartera->Axo;
        $mesAnterior = $registro_cartera->Mes;

        $deuda = Deuda::findOrFail($this->id);

        // 🔹 Si es Fedecrédito (3 o 4)
        if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4) {
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
                ->join('saldos_montos as sm', 'pdc.LineaCredito', '=', 'sm.Id')
                ->join('poliza_deuda_tipo_cartera as pdtc', 'pdc.PolizaDeudaTipoCartera', '=', 'pdtc.Id')
                ->join('tipo_cartera as tc', 'pdtc.TipoCartera', '=', 'tc.Id')
                ->select([
                    'pdc.TipoDocumento',
                    'pdc.Dui',
                    'pdc.PrimerApellido',
                    'pdc.SegundoApellido',
                    DB::raw("CONCAT(pdc.PrimerNombre, ' ', pdc.SegundoNombre) AS Nombres"),
                    'pdc.Nacionalidad',
                    'pdc.FechaNacimiento',
                    'pdc.Sexo',
                    DB::raw("CONCAT(pdc.NumeroReferencia, ' ') AS NumeroReferencia"),
                    'pdc.FechaOtorgamiento',
                    DB::raw("IF(pdc.MontoOtorgado IS NULL, '', ROUND(pdc.MontoOtorgado, 2)) AS MontoOtorgado"),
                    DB::raw("IF(pdc.SaldoCapital IS NULL, '', ROUND(pdc.SaldoCapital, 2)) AS SaldoCapital"),
                    DB::raw("IF(pdc.Intereses IS NULL, '', ROUND(pdc.Intereses, 2)) AS Intereses"),
                    DB::raw("IF(pdc.SaldoInteresMora IS NULL, '', ROUND(pdc.SaldoInteresMora, 2)) AS MoraCapital"),
                    DB::raw("IF(pdc.InteresesMoratorios IS NULL, '', ROUND(pdc.InteresesMoratorios, 2)) AS InteresesMoratorios"),
                    DB::raw("IF(pdc.InteresesCovid IS NULL, '', ROUND(pdc.InteresesCovid, 2)) AS InteresesCovid"),
                    'pdc.PorcentajeExtraprima',
                    'pdc.Tasa',
                    'tc.Nombre as TipoCartera',
                    DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
                ])
                ->orderBy('pdc.NumeroReferencia')
                ->get();
        } else {
            // 🔹 Otras aseguradoras
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
                ->join('saldos_montos as sm', 'pdc.LineaCredito', '=', 'sm.Id')
                ->join('poliza_deuda_tipo_cartera as pdtc', 'pdc.PolizaDeudaTipoCartera', '=', 'pdtc.Id')
                ->join('tipo_cartera as tc', 'pdtc.TipoCartera', '=', 'tc.Id')
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
                    'tc.Nombre as TipoCartera',
                    DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
                ])
                ->orderBy('pdc.NumeroReferencia')
                ->get();
        }

        return $data;
    }

    public function headings(): array
    {
        $deuda = Deuda::findOrFail($this->id);

        if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4) {
            // 🔹 Fedecrédito
            return [
                'Tipo de documento',
                'DUI o documento de identidad',
                'Primer Apellido',
                'Segundo Apellido',
                'Nombres',
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
                'TIPO CARTERA',
                'LINEA CREDITO',
            ];
        } else {
            // 🔹 Otras aseguradoras
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
