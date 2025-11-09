<?php

namespace App\Exports\desempleo;

use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCartera;
use App\Models\temp\DesempleoCarteraTemp;
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
        $desempleo = Desempleo::findOrFail($this->id);
        $tempRegistro = DesempleoCarteraTemp::where('PolizaDesempleo', $desempleo->Id)->first();

        $anioAnterior = null;
        $mesAnterior = null;

        if ($tempRegistro) {
            $fechaActual = Carbon::createFromDate($tempRegistro->Axo, $tempRegistro->Mes, 1);
            $fechaAnterior = $fechaActual->copy()->subMonth();
            $anioAnterior = $fechaAnterior->year;
            $mesAnterior = $fechaAnterior->month;
        }

        $hayCartera = DesempleoCartera::where('PolizaDesempleo', $desempleo->Id)->exists();

        if ($hayCartera) {
            $registrosEliminados = DB::table('poliza_desempleo_cartera AS c')
                ->where('c.Mes', (int) $mesAnterior)
                ->where('c.Axo', (int) $anioAnterior)
                ->where('c.PolizaDesempleo', $desempleo->Id)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('poliza_desempleo_cartera_temp AS t')
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
                    'c.MontoOtorgado AS MONTO OTORGADO',
                    'c.Tasa AS TARIFA'
                ])
                ->get();
        } else {
            $registrosEliminados = collect();
        }

        return $registrosEliminados;
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
            'PRIMERAPELLIDO',
            'SEGUNDOAPELLIDO',
            'APELLIDOCASADA',
            'PRIMERNOMBRE',
            'SEGUNDONOMBRE',
            'FECOTORGAMIENTO',
            'FECHA DE VENCIMIENTO',
            'NUMREFERENCIA',
            'MONTO OTORGADO',
            'TARIFA',
        ];
    }
}
