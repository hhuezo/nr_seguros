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
            // Fedecrédito: formato 21 columnas (nombres desglosados + saldos/intereses)
            $registros_rehabilitados = collect(DB::select("
                SELECT
                    t.TipoPersona AS TIPO_DOCUMENTO,
                    t.Dui AS DUI,
                    t.PrimerApellido AS PRIMERAPELLIDO,
                    t.SegundoApellido AS SEGUNDOAPELLIDO,
                    t.ApellidoCasada AS APELLIDOCASADA,
                    t.PrimerNombre AS PRIMERNOMBRE,
                    t.SegundoNombre AS SEGUNDONOMBRE,
                    '' AS TERCERNOMBRE,
                    t.Nacionalidad AS NACIONALIDAD,
                    t.FechaNacimiento AS FECNACIMIENTO,
                    t.Sexo AS GENERO,
                    t.NumeroReferencia AS NUMREFERENCIA,
                    t.FechaOtorgamiento AS FECOTORGAMIENTO,
                    t.MontoOtorgado AS MONTO_ORIGINAL_DESEMBOLSO,
                    t.SaldoCapital AS SALDO_DEUDA_CAPITAL,
                    t.Intereses AS SALDO_INTERESES_CORRIENTES,
                    t.MoraCapital AS MORA_CAPITAL,
                    t.InteresesMoratorios AS SALDO_INTERESES_MORA,
                    t.InteresesCovid AS INTERESES_COVID,
                    t.PorcentajeExtraprima AS EXTRA_PRIMA,
                    t.Tasa AS TARIFA
                FROM poliza_desempleo_cartera_temp AS t
                WHERE t.PolizaDesempleo = ? AND t.Rehabilitado = 1
            ", [$desempleo->Id]));
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
            // Fedecrédito desempleo: 21 columnas
            return [
                'Tipo de documento',
                'DUI o documento de identidad',
                'Primer Apellido',
                'Segundo Apellido',
                'Apellido de casada',
                'primer nombre',
                'segundo nombre',
                'tercer nombre',
                'Nacionalidad',
                'Fecha de Nacimiento',
                'Género',
                'Nro. de Préstamo',
                'Fecha de otorgamiento',
                'Monto original de desembolso',
                'Saldo de deuda capital actual',
                'Saldo intereses corrientes',
                'Mora capital',
                'Saldo intereses por mora',
                'Intereses Covid',
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
