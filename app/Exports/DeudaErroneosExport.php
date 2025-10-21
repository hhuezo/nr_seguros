<?php

namespace App\Exports;


use App\Models\temp\PolizaDeudaTempCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeudaErroneosExport implements FromCollection, WithHeadings
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {

        $data = PolizaDeudaTempCartera::where('poliza_deuda_temp_cartera.PolizaDeuda', $this->id)
            ->leftJoin('saldos_montos as sm', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'sm.Id')
            ->join('poliza_deuda_tipo_cartera as pdtc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'pdtc.Id')
            ->join('tipo_cartera as tc', 'pdtc.TipoCartera', '=', 'tc.Id')
            ->select([
                //'Nit',
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
                //'Ocupacion',
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
                'TipoError'
                // '' // Porcentaje Extraprima cambiar
            ])
            ->where('TipoError', '<>', 0)
            ->get();


        foreach ($data as $registro) {
            $errores = [];

            if ($registro->TipoError == 1) {
                $errores[] = 'Formato de fecha de nacimiento no válido';
            }
            if ($registro->TipoError == 2) {
                $errores[] = 'Formato de DUI no válido';
            }
            if ($registro->TipoError == 4) {
                if (!$registro->PrimerNombre) {
                    $errores[] = 'Falta el primer nombre';
                }
                if (!$registro->PrimerApellido) {
                    $errores[] = 'Falta el primer apellido';
                }
            }
            if ($registro->TipoError == 5) {
                $errores[] = 'Formato de fecha de otorgamiento no válido';
            }
            if ($registro->TipoError == 7) {
                $errores[] = 'Número de referencia no válido';
            }
            if ($registro->TipoError == 8) {
                $errores[] = 'Pasaporte no válido';
            }
            if ($registro->TipoError == 9) {
                $errores[] = 'El dato de la nacionalidad está vacío';
            }
            if ($registro->TipoError == 10) {
                $errores[] = 'El género no es válido';
            }

             if ($registro->TipoError == 11) {
                $errores[] = 'El nombre debe contener solo letras';
            }


            // Guardar el mensaje consolidado de errores en la propiedad Error
            $registro->TipoError = implode('; ', $errores);  // Concatenar todos los mensajes con "; "
        }


        return $data;
    }

    public function headings(): array
    {
        return [
            //'NIT',
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
            //'OCUPACION',
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
            'ERROR'
        ];
    }
}
