<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EdadTerminacionExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }




    public function collection()
    {
        $vida = Vida::findOrFail($this->id);

        if ($vida->Aseguradora == 3 || $vida->Aseguradora == 4) {
            //fedecredito
            $poliza_edad_terminacion = DB::table('poliza_vida_cartera_temp AS t')
                ->where('t.PolizaVida', $vida->Id)
                ->where('t.EdadDesembloso', '>', $vida->EdadTerminacion)
                ->select([
                    't.TipoDocumento AS TIPO_DOCUMENTO',
                    't.Dui AS DUI',
                    't.PrimerApellido AS PRIMERAPELLIDO',
                    't.SegundoApellido AS SEGUNDOAPELLIDO',
                    't.PrimerNombre AS PRIMERNOMBRE',
                    't.Nacionalidad AS NACIONALIDAD',
                    't.FechaNacimiento AS FECNACIMIENTO',
                    't.Sexo AS GENERO',
                    't.NumeroReferencia AS NUMREFERENCIA',
                    't.FechaOtorgamiento AS FECOTORGAMIENTO',
                    't.SumaAsegurada AS SUMA_ASEGURADA',
                    't.PorcentajeExtraprima AS EXTRA_PRIMA',
                    't.Tasa AS TARIFA'
                ])
                ->get();
        } else {
            $poliza_edad_terminacion = DB::table('poliza_vida_cartera_temp AS t')
                ->where('t.PolizaVida', $vida->Id)
                ->where('t.EdadDesembloso', '>', $vida->EdadTerminacion)
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
        }

        return $poliza_edad_terminacion;
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
