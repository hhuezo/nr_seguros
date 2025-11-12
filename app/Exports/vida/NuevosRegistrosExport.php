<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\polizas\VidaCartera;
use App\Models\temp\VidaCarteraTemp;
use Carbon\Carbon;
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

        $tempRegistro = VidaCarteraTemp::where('PolizaVida', $vida->Id)->first();

        if (!$tempRegistro) {
            return collect();
        }

        $fechaActual = Carbon::createFromDate($tempRegistro->Axo, $tempRegistro->Mes, 1);
        $fechaAnterior = $fechaActual->copy()->subMonth();
        $anioAnterior = $fechaAnterior->year;
        $mesAnterior = $fechaAnterior->month;

        $id = $vida->Id;
        $axoActual = $tempRegistro->Axo;
        $mesActual = $tempRegistro->Mes;

        // 🔹 Consulta optimizada con uso de Identificador
        if ($vida->Aseguradora == 3 || $vida->Aseguradora == 4) {
            //fedecredito
            $nuevosRegistros = collect(DB::select("
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
                WHERE pdtc.PolizaVida = ?
                AND pdtc.Axo = ?
                AND pdtc.Mes = ?
                AND NOT EXISTS (
                    SELECT 1
                    FROM poliza_vida_cartera AS pdc
                    WHERE pdc.PolizaVida = ?
                        AND pdc.Axo = ?
                        AND pdc.Mes = ?
                        AND pdc.NumeroReferencia = pdtc.NumeroReferencia
                        AND pdc.Identificador = pdtc.Identificador
                )
            ", [$id, $axoActual, $mesActual, $id, $anioAnterior, $mesAnterior]));
        } else {


            $nuevosRegistros = collect(DB::select("
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
                WHERE pdtc.PolizaVida = ?
                AND pdtc.Axo = ?
                AND pdtc.Mes = ?
                AND NOT EXISTS (
                    SELECT 1
                    FROM poliza_vida_cartera AS pdc
                    WHERE pdc.PolizaVida = ?
                        AND pdc.Axo = ?
                        AND pdc.Mes = ?
                        AND pdc.NumeroReferencia = pdtc.NumeroReferencia
                        AND pdc.Identificador = pdtc.Identificador
                )
            ", [$id, $axoActual, $mesActual, $id, $anioAnterior, $mesAnterior]));
        }

        return $nuevosRegistros;
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
