<?php

namespace App\Exports;

use App\Models\temp\PolizaDeudaTempCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistroRequisitosExport implements FromCollection, WithHeadings
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $data = PolizaDeudaTempCartera::
            where('PolizaDeuda', $this->id)->where('User', auth()->user()->id)
            ->where('NoValido',0)->where('OmisionPerfil',0)
            ->join('poliza_deuda_creditos as pdc', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'pdc.Id')
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
                DB::raw("IF(total_saldo IS NULL, '', ROUND(total_saldo, 2)) AS total_saldo"), // Prima Mensual
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




    /*protected $poliza;

    public function __construct($poliza = null)
    {
        $this->poliza = $poliza;
    }
    public function view(): View
    {
        $poliza = $this->poliza;

        $deuda = Deuda::findOrFail($poliza);
        $requisitos = $deuda->requisitos;
        $poliza_eliminados = DeudaEliminados::where('Poliza', $poliza)->groupBy('NumeroReferencia')->get();
        $poliza_eliminados_array = $poliza_eliminados->pluck('NumeroReferencia')->toArray();


        $poliza_cumulos = DB::table('poliza_deuda_temp_cartera')
            ->select(
                'Id',
                'Dui',
                'Edad',
                'Nit',
                'PrimerNombre',
                'SegundoNombre',
                'PrimerApellido',
                'SegundoApellido',
                'ApellidoCasada',
                'FechaNacimiento',
                'NumeroReferencia',
                'NoValido',
                'Perfiles',
                'EdadDesembloso',
                'FechaOtorgamiento',
                'NoValido',
                'Excluido',
                DB::raw('SUM(saldo_total) as total_saldo'),
                DB::raw("GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                //  DB::raw('SUM(SaldoCapital) as saldo_cpital'),
                DB::raw('SUM(SaldoCapital) as saldo_capital'),
                DB::raw('SUM(Intereses) as total_interes'),
                DB::raw('SUM(InteresesCovid) as total_covid'),
                DB::raw('SUM(InteresesMoratorios) as total_moratorios'),
                DB::raw('SUM(MontoNominal) as total_monto_nominal')
            )

            ->where('Edad', '<', $deuda->EdadMaximaTerminacion)
            ->where('NoValido', 0)
            ->where('PolizaDeuda', $poliza)
            ->groupBy('Dui')
            ->get();


        foreach ($poliza_cumulos as $poliza) {
            if (in_array($poliza->NumeroReferencia, $poliza_eliminados_array)) {
                $poliza->Rehabilitado = 1;
            } else {
                $poliza->Rehabilitado = 0;
            }
        }



        return view('polizas.deuda.get_creditos_excel', compact('poliza_cumulos', 'requisitos'));
    }
    public function styles(Worksheet $sheet)
    {
        // Aplica el formato de texto a la columna B (NumeroReferencia)
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    }*/
}
