<?php

namespace App\Imports;

use App\Models\temp\VidaCarteraTemp as TempVidaCarteraTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class VidaCarteraTempImport implements ToModel, WithStartRow
{
    private $Axo;
    private $Mes;
    private $Poliza;
    private $FechaInicio;
    private $FechaFinal;
    private $PolizaVidaTipoCartera;
    private $TarifaExcel;

    public function __construct($Axo, $Mes, $Poliza, $FechaInicio, $FechaFinal, $PolizaVidaTipoCartera, $TarifaExcel)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
        $this->Poliza = $Poliza;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
        $this->PolizaVidaTipoCartera = $PolizaVidaTipoCartera;
        $this->TarifaExcel = $TarifaExcel;
    }

    /**
     * Indica que debe empezar a leer desde la fila 2
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            if (!empty(trim($row[0])) || !empty(trim($row[1]))) { // Al menos DUI o Pasaporte

                $modelData = [
                    'Dui' => $row[0] ?? null,
                    'Pasaporte' => $row[1] ?? null,
                    'CarnetResidencia' => $row[2] ?? null,
                    'Nacionalidad' => $row[3] ?? null,
                    'FechaNacimiento' => $this->convertirFecha($row[4] ?? null),
                    'TipoPersona' => $row[5] ?? null,
                    'Sexo' => $row[6] ?? null,
                    'PrimerApellido' => $row[7] ?? null,
                    'SegundoApellido' => $row[8] ?? null,
                    'ApellidoCasada' => $row[9] ?? null,
                    'PrimerNombre' => $row[10] ?? null,
                    'SegundoNombre' => $row[11] ?? null,
                    'NombreSociedad' => $row[12] ?? null,
                    'FechaOtorgamiento' => $this->convertirFecha($row[13] ?? null),
                    'FechaVencimiento' => $this->convertirFecha($row[14] ?? null),
                    'NumeroReferencia' => $row[15] ?? null,
                    'SumaAsegurada' => $row[16] ?? null,

                    'SaldoCapital'        => $row[17],
                    'Intereses'           => $row[18],
                    'InteresesMoratorios' => $row[19],
                    'InteresesCovid'      => $row[20],

                    'Tasa' => (isset($row[21]) && trim($row[21]) !== '' && is_numeric($row[21]))
                        ? (float) $row[21] : null, // columna de tarifa

                    'TipoDeuda'          => $row[22],
                    'PorcentajeExtraprima' => $row[23],

                    'User' => auth()->id(),
                    'Axo' => $this->Axo,
                    'Mes' => $this->Mes,
                    'FechaInicio' => $this->FechaInicio,
                    'FechaFinal' => $this->FechaFinal,
                    'FechaNacimientoDate' => $this->convertirFecha($row[4] ?? null, 'Y-m-d'),
                    'FechaOtorgamientoDate' => $this->convertirFecha($row[13] ?? null, 'Y-m-d'),
                    'PolizaVidaTipoCartera' => $this->PolizaVidaTipoCartera,
                    'PolizaVida' => $this->Poliza,
                ];

                return new TempVidaCarteraTemp($modelData);
            } else {
                Log::warning('Fila omitida: DUI y Pasaporte vacíos', ['fila' => $row]);
            }
        } catch (\Exception $e) {
            Log::error('Error al procesar fila en model()', [
                'fila' => $row,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }

    private function convertirFecha($fechaExcel, $format = 'd/m/Y')
    {
        if (is_numeric($fechaExcel)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($fechaExcel - 2)->format($format);
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fechaExcel)) {
            return Carbon::createFromFormat('d/m/Y', $fechaExcel)->format($format);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaExcel)) {
            return Carbon::createFromFormat('Y-m-d', $fechaExcel)->format($format);
        }

        return null;
    }
}
