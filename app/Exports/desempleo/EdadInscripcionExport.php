<?php

namespace App\Exports\desempleo;

use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCarteraTemp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EdadInscripcionExport implements  FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $desempleo = Desempleo::findOrFail($this->id);
        $edadTerminacion = $desempleo->EdadMaximaTerminacion ?? 100;

        $data = DesempleoCarteraTemp::where('User', auth()->user()->id)->where('PolizaDesempleo', $this->id)->get();
        $poliza_edad_maxima = $data->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion)->where('EdadDesembloso', '<=', $desempleo->EdadMaxima);

        //dd($poliza_edad_maxima,$data);


        return $poliza_edad_maxima;
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
