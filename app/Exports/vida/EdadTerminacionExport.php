<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\temp\VidaCarteraTemp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EdadTerminacionExport implements  FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $vida = Vida::findOrFail($this->id);
        $edadTerminacion = $vida->EdadMaximaTerminacion ?? 100;

        $data = VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $this->id)->get();
        $poliza_edad_maxima = $data->where('EdadDesembloso', '>', $vida->EdadTerminacion);

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
