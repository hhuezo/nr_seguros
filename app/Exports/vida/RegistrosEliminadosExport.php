<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\polizas\VidaCartera;
use App\Models\temp\VidaCarteraTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrosEliminadosExport implements FromCollection, WithHeadings
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

        $anioAnterior = null;
        $mesAnterior = null;

        if ($tempRegistro) {
            $fechaActual = Carbon::createFromDate($tempRegistro->Axo, $tempRegistro->Mes, 1);
            $fechaAnterior = $fechaActual->copy()->subMonth();
            $anioAnterior = $fechaAnterior->year;
            $mesAnterior = $fechaAnterior->month;
        }

        $hayCartera = VidaCartera::where('PolizaVida', $vida->Id)->exists();

        if ($hayCartera) {
            if ($vida->Aseguradora == 3 || $vida->Aseguradora == 4) {
                //fedecredito
                $registrosEliminados = DB::table('poliza_vida_cartera AS c')
                    ->where('c.Mes', (int) $mesAnterior)
                    ->where('c.Axo', (int) $anioAnterior)
                    ->where('c.PolizaVida', $vida->Id)
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('poliza_vida_cartera_temp AS t')
                            ->whereRaw('t.NumeroReferencia = c.NumeroReferencia')
                            ->whereRaw('t.Identificador = c.Identificador');
                    })
                    ->select([
                        'c.TipoDocumento AS TIPO_DOCUMENTO',
                        'c.Dui AS DUI',
                        'c.PrimerApellido AS PRIMERAPELLIDO',
                        'c.SegundoApellido AS SEGUNDOAPELLIDO',
                        'c.PrimerNombre AS PRIMERNOMBRE',
                        'c.Nacionalidad AS NACIONALIDAD',
                        'c.FechaNacimiento AS FECNACIMIENTO',
                        'c.Sexo AS GENERO',
                        'c.NumeroReferencia AS NUMREFERENCIA',
                        'c.FechaOtorgamiento AS FECOTORGAMIENTO',
                        'c.SumaAsegurada AS SUMA_ASEGURADA',
                        'c.PorcentajeExtraprima AS EXTRA_PRIMA',
                        'c.Tasa AS TARIFA',
                    ])
                    ->get();
            } else {
                $registrosEliminados = DB::table('poliza_vida_cartera AS c')
                    ->where('c.Mes', (int) $mesAnterior)
                    ->where('c.Axo', (int) $anioAnterior)
                    ->where('c.PolizaVida', $vida->Id)
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('poliza_vida_cartera_temp AS t')
                            ->whereRaw('t.NumeroReferencia = c.NumeroReferencia')
                            ->whereRaw('t.Identificador = c.Identificador');
                    })
                    ->select([
                        'c.Dui AS DUI',
                        'c.Pasaporte AS PASAPORTE',
                        'c.CarnetResidencia AS CARNET_RESI',
                        'c.Nacionalidad AS NACIONALIDAD',
                        'c.FechaNacimiento AS FECNACIMIENTO',
                        'c.TipoPersona AS TIPO_PERSONA',
                        'c.Sexo AS GENERO',
                        'c.PrimerApellido AS PRIMERAPELLIDO',
                        'c.SegundoApellido AS SEGUNDOAPELLIDO',
                        'c.ApellidoCasada AS APELLIDOCASADA',
                        'c.PrimerNombre AS PRIMERNOMBRE',
                        'c.SegundoNombre AS SEGUNDONOMBRE',
                        'c.FechaOtorgamiento AS FECOTORGAMIENTO',
                        'c.FechaVencimiento AS FECHA_DE_VENCIMIENTO',
                        'c.NumeroReferencia AS NUMREFERENCIA',
                        'c.SumaAsegurada AS SUMA_ASEGURADA',
                        'c.Tasa AS TARIFA'
                    ])
                    ->get();
            }
        } else {
            $registrosEliminados = collect();
        }

        return $registrosEliminados;
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
                'FECNACIMIENTO',
                'TIPO PERSONA',
                'GENERO',
                'PRIMERAPELLIDO',
                'SEGUNDOAPELLIDO',
                'APELLIDOCASADA',
                'PRIMERNOMBRE',
                'SEGUNDONOMBRE',
                'FECOTORGAMIENTO',
                'FECHA DE VENCIMIENTO',
                'NUMREFERENCIA',
                'SUMA ASEGURADA',
                'TARIFA',
            ];
        }
    }
}
