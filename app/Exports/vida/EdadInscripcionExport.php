<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EdadInscripcionExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {

        $vida = Vida::findOrFail($this->id);
        //$edadTerminacion = $vida->EdadTerminacion ?? 100;

        $data = VidaCarteraTemp::where('PolizaVida', $this->id)->get();
        $poliza_edad_maxima = collect(DB::select("
                SELECT
                    pdtc.Dui AS DUI,
                    pdtc.Pasaporte AS PASAPORTE,
                    pdtc.CarnetResidencia AS CARNET_RESI,
                    pdtc.Nacionalidad AS NACIONALIDAD,
                    pdtc.FechaNacimiento AS FECNACIMIENTO,
                    pdtc.TipoPersona AS TIPO_PERSONA,
                    pdtc.Sexo AS GENERO,
                    pdtc.PrimerApellido AS PRIMERAPELLIDO,
                    pdtc.SegundoApellido AS SEGUNDOAPELLIDO,
                    pdtc.ApellidoCasada AS APELLIDOCASADA,
                    pdtc.PrimerNombre AS PRIMERNOMBRE,
                    pdtc.SegundoNombre AS SEGUNDONOMBRE,
                    pdtc.FechaOtorgamiento AS FECOTORGAMIENTO,
                    pdtc.FechaVencimiento AS FECHA_DE_VENCIMIENTO,
                    pdtc.NumeroReferencia AS NUMREFERENCIA,
                    pdtc.SumaAsegurada AS SUMA_ASEGURADA,
                    pdtc.Tasa AS TARIFA
                FROM poliza_vida_cartera_temp AS pdtc
                WHERE pdtc.PolizaVida = ? AND EdadDesembloso > ?
            ", [$this->id, $vida->EdadMaximaInscripcion]));
        // $poliza_edad_maxima = $data->where('EdadDesembloso', '>', $vida->EdadMaximaInscripcion);

        //dd($poliza_edad_maxima);


        return $poliza_edad_maxima;
    }


    public function headings(): array
    {
        return [
            'DUI',
            'PASAPORTE',
            'CARNET RESI',
            'NACIONALIDAD',
            'FECHA NACIMIENTO',
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
