<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NuevosRegistrosExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $vida = Vida::findOrFail($this->id);
        $nuevos_registros = VidaCarteraTemp::leftJoin(
            DB::raw('(
                        SELECT DISTINCT NumeroReferencia
                        FROM poliza_vida_cartera
                        WHERE PolizaVida = ' . $this->id . '
                    ) AS valid_references'),
            'poliza_vida_cartera_temp.NumeroReferencia',
            '=',
            'valid_references.NumeroReferencia'
        )
            ->where('poliza_vida_cartera_temp.User', auth()->user()->id) // Filtra por el usuario autenticado
            ->where('poliza_vida_cartera_temp.PolizaVida', $this->id)
            ->whereNull('valid_references.NumeroReferencia') // Los registros que no coinciden
            ->select('poliza_vida_cartera_temp.*') // Selecciona columnas de la tabla principal
            ->get();

        $data = $nuevos_registros->where('Edad', '<=', $vida->EdadMaximaInscripcion);


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
}
