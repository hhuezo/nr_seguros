<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrosRehabilitadosExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        $vida = Vida::findOrFail($this->id);

        $registrosRehabilitados = DB::table('poliza_vida_cartera_temp AS t')
            ->where('t.PolizaVida', $vida->Id)
            ->where('t.Rehabilitado', 1)
            ->select([
                't.Dui AS DUI',
                't.Pasaporte AS PASAPORTE',
                't.CarnetResidencia AS CARNET_RESI',
                't.Nacionalidad AS NACIONALIDAD',
                't.FechaNacimiento AS FECNACIMIENTO',
                't.TipoPersona AS TIPO_PERSONA',
                't.Sexo AS GENERO',
                't.PrimerApellido AS PRIMERAPELLIDO',
                't.SegundoApellido AS SEGUNDOAPELLIDO',
                't.ApellidoCasada AS APELLIDOCASADA',
                't.PrimerNombre AS PRIMERNOMBRE',
                't.SegundoNombre AS SEGUNDONOMBRE',
                't.FechaOtorgamiento AS FECOTORGAMIENTO',
                't.FechaVencimiento AS FECHA_DE_VENCIMIENTO',
                't.NumeroReferencia AS NUMREFERENCIA',
                't.SumaAsegurada AS SUMA_ASEGURADA',
                't.Tasa AS TARIFA'
            ])
            ->get();

        return $registrosRehabilitados;
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
            'PRIMER APELLIDO',
            'SEGUNDO APELLIDO',
            'APELLIDO CASADA',
            'PRIMER NOMBRE',
            'SEGUNDO NOMBRE',
            'FECHA DE OTORGAMIENTO',
            'FECHA DE VENCIMIENTO',
            'NUMREFERENCIA',
            'SUMA ASEGURADA',
            'TARIFA',
        ];
    }
}
