<?php

namespace App\Exports\desempleo;

use App\Models\polizas\Desempleo;
use App\Models\temp\DesempleoCarteraTemp;
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

        $desempleo = Desempleo::findOrFail($this->id);
        if ($desempleo->Aseguradora == 3 || $desempleo->Aseguradora == 4) {
            $registros_rehabilitados = DB::table('poliza_desempleo_cartera_temp AS t')
                ->where('t.PolizaDesempleo', $desempleo->Id)
                ->where('t.Rehabilitado', 1)
                ->select([
                    't.TipoPersona AS TIPO_DOCUMENTO,',
                    't.Dui AS DUI,',
                    't.PrimerApellido AS PRIMERAPELLIDO,',
                    't.SegundoApellido AS SEGUNDOAPELLIDO,',
                    't.PrimerNombre AS PRIMERNOMBRE,',
                    't.Nacionalidad AS NACIONALIDAD,',
                    't.FechaNacimiento AS FECNACIMIENTO,',
                    't.Sexo AS GENERO,',
                    't.NumeroReferencia AS NUMREFERENCIA,',
                    't.FechaOtorgamiento AS FECOTORGAMIENTO,',
                    't.MontoOtorgado AS MONTO_OTORGADO,',
                    't.Tasa AS TARIFA',
                ])
                ->get();
        } else {


            $registros_rehabilitados = DB::table('poliza_desempleo_cartera_temp AS t')
                ->where('t.PolizaDesempleo', $desempleo->Id)
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
                    't.MontoOtorgado AS MONTO_OTORGADO',
                    't.Tasa AS TARIFA'
                ])
                ->get();
        }

        return $registros_rehabilitados;
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
                'MONTO OTORGADO',
                'TARIFA',
            ];
        }
    }
}
