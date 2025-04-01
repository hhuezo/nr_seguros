<?php

namespace App\Exports;

use App\Models\temp\PolizaDeudaTempCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistroRequisitosReciboExport implements FromCollection, WithHeadings
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $data =  PolizaDeudaTempCartera::join('poliza_deuda_creditos as pdc', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'pdc.Id')
        ->leftJoin('poliza_deuda_cartera as pdcart', function ($join) {
             $join->on('poliza_deuda_temp_cartera.NumeroReferencia', '=', 'pdcart.NumeroReferencia');
         })

         ->select(
             'poliza_deuda_temp_cartera.Id',
             'poliza_deuda_temp_cartera.Dui',
             'poliza_deuda_temp_cartera.Edad',
             'poliza_deuda_temp_cartera.Nit',
             'poliza_deuda_temp_cartera.PrimerNombre',
             'poliza_deuda_temp_cartera.SegundoNombre',
             'poliza_deuda_temp_cartera.PrimerApellido',
             'poliza_deuda_temp_cartera.SegundoApellido',
             'poliza_deuda_temp_cartera.ApellidoCasada',
             'poliza_deuda_temp_cartera.FechaNacimiento',
             'poliza_deuda_temp_cartera.NumeroReferencia',
             'poliza_deuda_temp_cartera.NoValido',
             'poliza_deuda_temp_cartera.Perfiles',
             'poliza_deuda_temp_cartera.Mes',
             'poliza_deuda_temp_cartera.Axo',
             DB::raw("GROUP_CONCAT(DISTINCT poliza_deuda_temp_cartera.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
             DB::raw('MAX(poliza_deuda_temp_cartera.EdadDesembloso) as EdadDesembloso'),
             DB::raw('MAX(poliza_deuda_temp_cartera.FechaOtorgamientoDate) as FechaOtorgamiento'),
             'poliza_deuda_temp_cartera.Excluido',
             'poliza_deuda_temp_cartera.OmisionPerfil',
             "poliza_deuda_temp_cartera.TotalCredito as saldo_total",
             'pdc.MontoMaximoIndividual as MontoMaximoIndividual'
         )
      //   ->where('poliza_deuda_temp_cartera.Edad', '<', $deuda->EdadMaximaTerminacion)
        // ->where('poliza_deuda_temp_cartera.NoValido', 0)
         ->where('poliza_deuda_temp_cartera.PolizaDeuda', $this->id)
         ->where('poliza_deuda_temp_cartera.OmisionPerfil', 0)
         //->whereNull('pdcart.NumeroReferencia') // Filtra solo los que no tienen coincidencia en poliza_deuda_cartera
         ->groupBy('poliza_deuda_temp_cartera.Dui','poliza_deuda_temp_cartera.Mes','poliza_deuda_temp_cartera.Axo')
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
