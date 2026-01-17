<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaValidados;
use App\Models\temp\PolizaDeudaTempCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistroRequisitosExport implements FromCollection, WithHeadings
{

    protected $id;
    protected $tipo;

    public function __construct($id, $tipo = 1)
    {
        $this->id = $id;
        $this->tipo = $tipo;
    }




    public function collection()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $deuda = Deuda::findOrFail($this->id);

        // Determinar el valor de OmisionPerfil según el tipo
        $omisionPerfil = ($this->tipo == 2) ? 1 : 0; // tipo 2 = válidos (OmisionPerfil = 1)

        // Construir la query base según el tipo
        $query = PolizaDeudaTempCartera::query()
            ->where('poliza_deuda_temp_cartera.PolizaDeuda', $this->id)
            ->where('poliza_deuda_temp_cartera.NoValido', 0);

        // Aplicar filtros según el tipo
        if ($this->tipo == 1 || $this->tipo == 2) {
            // Tipo 1: Créditos con requisitos
            // Tipo 2: Créditos válidos
            $query->where('poliza_deuda_temp_cartera.OmisionPerfil', $omisionPerfil);
        } elseif ($this->tipo == 3) {
            // Tipo 3: Créditos rehabilitados
            $query->where('poliza_deuda_temp_cartera.Rehabilitado', 1);
        } elseif ($this->tipo == 4) {
            // Tipo 4: Histórico de validados - obtener registros validados
            $registrosValidados = DeudaValidados::where('Poliza', $this->id)
                ->pluck('NumeroReferencia')
                ->toArray();

            if (empty($registrosValidados)) {
                return collect(); // Retornar colección vacía si no hay validados
            }

            $query->whereIn('poliza_deuda_temp_cartera.NumeroReferencia', $registrosValidados);
        }

        // Fedecrédito
        if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4) {

            $data = $query
                ->join('saldos_montos as sm', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'sm.Id')
                ->join('poliza_deuda_tipo_cartera as pdtc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'pdtc.Id')
                ->join('tipo_cartera as tc', 'pdtc.TipoCartera', '=', 'tc.Id')
                ->leftJoin('poliza_deuda_cartera as pdc', function ($join) {
                    $join->on('poliza_deuda_temp_cartera.NumeroReferencia', '=', 'pdc.NumeroReferencia');
                });

            // Solo aplicar whereNull si es tipo 1 (créditos con requisitos)
            if ($this->tipo == 1) {
                $data->whereNull('pdc.NumeroReferencia'); // 🔹 Excluir registros ya existentes
            }

            $data = $data->select([
                    'poliza_deuda_temp_cartera.TipoDocumento',
                    'poliza_deuda_temp_cartera.Dui',
                    'poliza_deuda_temp_cartera.PrimerApellido',
                    'poliza_deuda_temp_cartera.SegundoApellido',
                    'poliza_deuda_temp_cartera.PrimerNombre',
                    'poliza_deuda_temp_cartera.Nacionalidad',
                    'poliza_deuda_temp_cartera.FechaNacimiento',
                    'poliza_deuda_temp_cartera.Sexo',

                    DB::raw("CONCAT(poliza_deuda_temp_cartera.NumeroReferencia, ' ') AS NumeroReferencia"),
                    'poliza_deuda_temp_cartera.FechaOtorgamiento',

                    DB::raw("IF(poliza_deuda_temp_cartera.MontoOtorgado IS NULL, '', ROUND(poliza_deuda_temp_cartera.MontoOtorgado, 2)) AS MontoOtorgado"),
                    DB::raw("IF(poliza_deuda_temp_cartera.SaldoCapital IS NULL, '', ROUND(poliza_deuda_temp_cartera.SaldoCapital, 2)) AS SaldoCapital"),
                    DB::raw("IF(poliza_deuda_temp_cartera.Intereses IS NULL, '', ROUND(poliza_deuda_temp_cartera.Intereses, 2)) AS Intereses"),
                    DB::raw("IF(poliza_deuda_temp_cartera.SaldoInteresMora IS NULL, '', ROUND(poliza_deuda_temp_cartera.SaldoInteresMora, 2)) AS MoraCapital"),
                    DB::raw("IF(poliza_deuda_temp_cartera.InteresesMoratorios IS NULL, '', ROUND(poliza_deuda_temp_cartera.InteresesMoratorios, 2)) AS InteresesMoratorios"),
                    DB::raw("IF(poliza_deuda_temp_cartera.InteresesCovid IS NULL, '', ROUND(poliza_deuda_temp_cartera.InteresesCovid, 2)) AS InteresesCovid"),

                    'poliza_deuda_temp_cartera.PorcentajeExtraprima',
                    'poliza_deuda_temp_cartera.Tasa',
                    DB::raw('tc.Nombre AS TipoCartera'),
                   // DB::raw('pdtc.MontoMaximoIndividual AS MontoMaximoIndividual'),
                    DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
                    'poliza_deuda_temp_cartera.Perfiles',
                ])
                ->groupBy(
                    'poliza_deuda_temp_cartera.Dui',
                    'poliza_deuda_temp_cartera.Pasaporte',
                    'poliza_deuda_temp_cartera.CarnetResidencia',
                    'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera',
                    'pdtc.MontoMaximoIndividual'
                )
                ->orderBy('poliza_deuda_temp_cartera.NumeroReferencia')
                ->get();

        } else {

            $data = $query
                ->join('saldos_montos as sm', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'sm.Id')
                ->join('poliza_deuda_tipo_cartera as pdtc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'pdtc.Id')
                ->join('tipo_cartera as tc', 'pdtc.TipoCartera', '=', 'tc.Id')
                ->leftJoin('poliza_deuda_cartera as pdc', function ($join) {
                    $join->on('poliza_deuda_temp_cartera.NumeroReferencia', '=', 'pdc.NumeroReferencia');
                });

            // Solo aplicar whereNull si es tipo 1 (créditos con requisitos)
            if ($this->tipo == 1) {
                $data->whereNull('pdc.NumeroReferencia'); // 🔹 excluir registros ya existentes
            }

            $data = $data->select([
                    'poliza_deuda_temp_cartera.Dui',
                    'poliza_deuda_temp_cartera.Pasaporte',
                    'poliza_deuda_temp_cartera.CarnetResidencia',
                    'poliza_deuda_temp_cartera.Nacionalidad',
                    'poliza_deuda_temp_cartera.FechaNacimiento',
                    'poliza_deuda_temp_cartera.TipoPersona',
                    'poliza_deuda_temp_cartera.Sexo',
                    'poliza_deuda_temp_cartera.PrimerApellido',
                    'poliza_deuda_temp_cartera.SegundoApellido',
                    'poliza_deuda_temp_cartera.ApellidoCasada',
                    'poliza_deuda_temp_cartera.PrimerNombre',
                    'poliza_deuda_temp_cartera.SegundoNombre',
                    'poliza_deuda_temp_cartera.FechaOtorgamiento',
                    'poliza_deuda_temp_cartera.FechaVencimiento',

                    DB::raw("CONCAT(poliza_deuda_temp_cartera.NumeroReferencia, ' ') AS NumeroReferencia"),
                    DB::raw("IF(poliza_deuda_temp_cartera.MontoOtorgado IS NULL, '', ROUND(poliza_deuda_temp_cartera.MontoOtorgado, 2)) AS MontoOtorgado"),
                    DB::raw("IF(poliza_deuda_temp_cartera.SaldoCapital IS NULL, '', ROUND(poliza_deuda_temp_cartera.SaldoCapital, 2)) AS SaldoCapital"),
                    DB::raw("IF(poliza_deuda_temp_cartera.Intereses IS NULL, '', ROUND(poliza_deuda_temp_cartera.Intereses, 2)) AS Intereses"),
                    DB::raw("IF(poliza_deuda_temp_cartera.InteresesMoratorios IS NULL, '', ROUND(poliza_deuda_temp_cartera.InteresesMoratorios, 2)) AS InteresesMoratorios"),
                    DB::raw("IF(poliza_deuda_temp_cartera.InteresesCovid IS NULL, '', ROUND(poliza_deuda_temp_cartera.InteresesCovid, 2)) AS InteresesCovid"),

                    'poliza_deuda_temp_cartera.Tasa',
                    'poliza_deuda_temp_cartera.TipoDeuda',
                    'poliza_deuda_temp_cartera.PorcentajeExtraprima',

                    DB::raw('tc.Nombre as TipoCartera'),
                    DB::raw('pdtc.MontoMaximoIndividual as MontoMaximoIndividual'),
                    DB::raw("CONCAT(sm.Abreviatura, ' - ', sm.Descripcion) AS LineaCredito"),
                    'poliza_deuda_temp_cartera.Perfiles',
                ])
                ->groupBy(
                    'poliza_deuda_temp_cartera.Dui',
                    'poliza_deuda_temp_cartera.Pasaporte',
                    'poliza_deuda_temp_cartera.CarnetResidencia',
                    'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera',
                    'pdtc.MontoMaximoIndividual'
                )
                ->orderBy('poliza_deuda_temp_cartera.NumeroReferencia')
                ->get();
        }


        return $data;
    }


    public function headings(): array
    {
        $deuda = Deuda::findOrFail($this->id);

        if ($deuda->Aseguradora == 3  || $deuda->Aseguradora == 4) {
            // Fedecrédito
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
                'Tasa',
                'Taipo cartera',
                'Linea credito',
                'Requisitos'
            ];
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
                //'NOMBRE SOCIEDAD',
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
                'Tasa',
                'TIPO CARTERA',
                'LINEA CREDITO',
                'REQUISITOS',
            ];
        }
    }
}
