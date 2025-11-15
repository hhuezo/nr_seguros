<?php

namespace App\Exports\desempleo;

use App\Models\polizas\Desempleo;
use App\Models\temp\DesempleoCarteraTemp;
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

        $desempleo = Desempleo::findOrFail($this->id);
        $edadTerminacion = $desempleo->EdadMaximaTerminacion ?? 100;

        $data = DesempleoCarteraTemp::where('User', auth()->user()->id)->where('PolizaDesempleo', $this->id)->get();
        if ($desempleo->Aseguradora == 3 || $desempleo->Aseguradora == 4) {
            //
            $poliza_edad_maxima = collect(DB::select("
                SELECT
                    pdtc.TipoPersona AS TIPO_DOCUMENTO,
                    pdtc.Dui AS DUI,
                    pdtc.PrimerApellido AS PRIMERAPELLIDO,
                    pdtc.SegundoApellido AS SEGUNDOAPELLIDO,
                    pdtc.PrimerNombre AS PRIMERNOMBRE,
                    pdtc.Nacionalidad AS NACIONALIDAD,
                    pdtc.FechaNacimiento AS FECNACIMIENTO,
                    pdtc.Sexo AS GENERO,
                    pdtc.NumeroReferencia AS NUMREFERENCIA,
                    pdtc.FechaOtorgamiento AS FECOTORGAMIENTO,
                    pdtc.MontoOtorgado AS MONTO_OTORGADO,
                    pdtc.Tasa AS TARIFA
                    FROM poliza_desempleo_cartera_temp AS pdtc
                WHERE pdtc.PolizaDesempleo = ? AND EdadDesembloso > ? AND EdadDesembloso >= ?
            ", [$this->id, $desempleo->EdadMaximaInscripcion, $desempleo->EdadMaxima]));
        } else {


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
                    pdtc.MontoOtorgado AS MONTO_OTORGADO,
                    pdtc.Tasa AS TARIFA
                    FROM poliza_desempleo_cartera_temp AS pdtc
                WHERE pdtc.PolizaDesempleo = ? AND EdadDesembloso > ? AND EdadDesembloso >= ?
            ", [$this->id, $desempleo->EdadMaximaInscripcion, $desempleo->EdadMaxima]));
            // $poliza_edad_maxima = $data->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion)->where('EdadDesembloso', '<=', $desempleo->EdadMaxima);

            //dd($poliza_edad_maxima,$data);
        }

        return $poliza_edad_maxima;
    }


    public function headings(): array
    {
        $desempleo = Desempleo::findOrFail($this->id);
        if ($desempleo->Aseguradora == 3 || $desempleo->Aseguradora == 4) {
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
                'Monto Otorgado',
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
                'MONTO OTORGADO',
                'TARIFA',
            ];
        }
    }
}
