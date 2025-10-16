<?php

namespace App\Exports\desempleo;

use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCartera;
use App\Models\temp\DesempleoCarteraTemp;
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

        $desempleo = Desempleo::findOrFail($this->id);
        $temp_data_fisrt = DesempleoCarteraTemp::where('PolizaDesempleo', $desempleo->id)->where('User', auth()->user()->id)->first();

        $axoActual =  $temp_data_fisrt->Axo;
        $mesActual =  $temp_data_fisrt->Mes;


        // Calcular el mes pasado
        if ($mesActual == 1) {
            $mesAnterior = 12; // Diciembre
            $axoAnterior = $axoActual - 1; // Año anterior
        } else {
            $mesAnterior = $mesActual - 1; // Mes anterior
            $axoAnterior = $axoActual; // Mismo año
        }

        $count_data_cartera = DesempleoCartera::where('PolizaDesempleo', $desempleo->id)->count();
        if ($count_data_cartera > 0) {
            //dd($mesAnterior,$axoAnterior,$request->Desempleo);
            $registros_eliminados = DB::table('poliza_desempleo_cartera AS pdc')
                ->leftJoin('poliza_desempleo_cartera_temp AS pdtc', function ($join) {
                    $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia')
                        ->where('pdtc.User', auth()->user()->id);
                })
                ->where('pdc.Mes', (int)$mesAnterior)
                ->where('pdc.Axo', (int)$axoAnterior)
                ->where('pdc.PolizaDesempleo', $desempleo->id)
                ->whereNull('pdtc.NumeroReferencia') // Solo los que no están en poliza_desempleo_temp_cartera
                ->select('pdc.*') // Selecciona columnas principales
                ->get();
        } else {
            $registros_eliminados =  DesempleoCarteraTemp::where('Id', 0)->get();
        }

        return $registros_eliminados;
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

