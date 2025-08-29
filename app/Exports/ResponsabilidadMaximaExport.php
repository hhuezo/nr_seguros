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

        $data = PolizaDeudaTempCartera::where('PolizaDeuda', $this->id)
            ->where('User', auth()->user()->id)
            ->where('TotalCredito', '>', $responsabilidadMaxima)
            ->join('saldos_montos as sm', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'sm.Id')
            ->join('tipo_cartera as tc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'tc.Id')
            ->select([
                'Dui',                                 // DUI
                'Pasaporte',                           // PASAPORTE
                DB::raw("'' AS CarnetResi"),          // CARNET RESI (si no existe, lo dejamos vacío)
                'Nacionalidad',                        // NACIONALIDAD
                'FechaNacimiento',                      // FECNACIMIENTO
                'TipoPersona',                         // TIPO PERSONA
                'Sexo',                                // GENERO
                'PrimerApellido',                      // PRIMERAPELLIDO
                'SegundoApellido',                     // SEGUNDOAPELLIDO
                'ApellidoCasada',                      // APELLIDOCASADA
                'PrimerNombre',                        // PRIMERNOMBRE
                'SegundoNombre',                       // SEGUNDONOMBRE
                'NombreSociedad',                       // NOMBRE SOCIEDAD
                'FechaOtorgamiento',                    // FECOTORGAMIENTO
                'FechaVencimiento',                     // FECHA DE VENCIMIENTO
                DB::raw("NumeroReferencia AS NumReferencia"),  // NUMREFERENCIA
                'MontoOtorgado',                        // MONTO OTORGADO
                'SaldoCapital',                         // SALDO DE CAPITAL
                'Intereses AS InteresCorrientes',       // INTERES CORRIENTES
                'InteresesMoratorios',                  // INTERES MORATORIO
                'InteresesCovid',                        // INTERES COVID
                'MontoNominal AS Tarifa',               // TARIFA
                'tc.Nombre AS TipoDeuda',               // TIPO DE DEUDA
                'Tasa AS PorcentajeExtraprima',        // PORCENTAJE EXTRAPRIMA
            ])
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
        ];
        /*return [
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
        ];*/
    }
}
