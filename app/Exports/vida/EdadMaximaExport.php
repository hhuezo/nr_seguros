<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EdadMaximaExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {
        $vida = Vida::findOrFail($this->id);
        //$edadTerminacion = $desempleo->EdadTerminacion ?? 100;

        //$data = VidaCarteraTemp::where('PolizaVida', $this->id)->get();

        if ($vida->Aseguradora == 3 || $vida->Aseguradora == 4) {
            //fedecredito
            $poliza_edad_maxima = collect(DB::select("
                SELECT
                    pdtc.TipoDocumento AS TIPO_DOCUMENTO,
                    pdtc.DUI AS DUI,
                    pdtc.PrimerApellido AS PRIMERAPELLIDO,
                    pdtc.SegundoApellido AS SEGUNDOAPELLIDO,
                    pdtc.PrimerNombre AS PRIMERNOMBRE,
                    pdtc.Nacionalidad AS NACIONALIDAD,
                    pdtc.FechaNacimiento AS FECNACIMIENTO,
                    pdtc.Sexo AS GENERO,
                    pdtc.NumeroReferencia AS NUMREFERENCIA,
                    pdtc.FechaOtorgamiento AS FECOTORGAMIENTO,
                    pdtc.SumaAsegurada AS SUMA_ASEGURADA,
                    pdtc.PorcentajeExtraprima AS EXTRA_PRIMA,
                    pdtc.Tasa AS TARIFA
                FROM poliza_vida_cartera_temp AS pdtc
                WHERE pdtc.PolizaVida = ? AND EdadDesembloso > ? AND EdadDesembloso > ?
            ", [$this->id, $vida->EdadMaximaInscripcion, $vida->EdadTerminacion]));
        } else {
            if ($vida->Aseguradora)
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
                WHERE pdtc.PolizaVida = ? AND EdadDesembloso > ? AND EdadDesembloso > ?
            ", [$this->id, $vida->EdadMaximaInscripcion, $vida->EdadTerminacion]));
        }

           //dd($poliza_edad_maxima);

        //$poliza_edad_maxima = $data->where('EdadDesembloso', '>', $vida->EdadMaximaInscripcion)->where('EdadDesembloso', '>', $vida->EdadTerminacion);

        //dd($poliza_edad_maxima,$data);


        return $poliza_edad_maxima;
    }


    public function headings(): array
    {
        $vida = Vida::findOrFail($this->id);

        if ($vida->Aseguradora == 3 || $vida->Aseguradora == 4) {
            //fedecredito
            return [
                'Tipo de Documento',
                'DUI o documento de identidad',
                'Primer Apellido',
                'Segundo Apellido',
                'Nombres',
                'Nacionalidad',
                'Fecha de Nacimiento',
                'Género',
                'Nro. de Préstamo',
                'Fecha de otorgamiento',
                'Suma asegurada ',
                'Extra Prima',
                'TARIFA',

            ];
        } else {
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
}
