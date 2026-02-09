<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;

class PolizaDeudaTempCarteraImport implements ToModel, /*WithStartRow,*/ SkipsEmptyRows
{
    private $Axo;
    private $Mes;
    private $PolizaDeuda;
    private $FechaInicio;
    private $FechaFinal;
    private $credito;
    private $encabezados = 0;
    private $TarifaExcel;


    public function __construct($Axo, $Mes, $PolizaDeuda, $FechaInicio, $FechaFinal, $credito, $TarifaExcel)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
        $this->PolizaDeuda = $PolizaDeuda;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
        $this->credito = $credito;
        $this->TarifaExcel = $TarifaExcel;
    }


    public function model(array $row)
    {
       try {

            // 🔹 LIMPIAR TODA LA FILA
            $row = array_map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            }, $row);

            // Detectar encabezados (ya no viene "NIT", sino "DUI")
            if (trim($row[0]) === "DUI") {
                $this->encabezados = 1;
                return null; // no insertar encabezado
            }

            if ($this->encabezados == 1) {

                $valido = function ($v) {
                    $s = is_string($v) ? trim($v) : trim((string) ($v ?? ''));
                    return $s !== '' && strpos($s, ' ') === false;
                };

                // Al menos una de las 3 primeras (DUI, Pasaporte, CarnetResidencia) debe tener datos
                $tieneDocumento = $valido($row[0] ?? '') || $valido($row[1] ?? '') || $valido($row[2] ?? '');
                if (!$tieneDocumento) {
                    Log::debug('[PolizaDeudaTempCarteraImport] fila saltada: al menos uno de DUI/Pasaporte/Carnet debe tener datos', ['row0' => $row[0] ?? '', 'row1' => $row[1] ?? '', 'row2' => $row[2] ?? '']);
                    return null;
                }

                // Columnas 4 y 5 (Nacionalidad, FechaNacimiento) son obligatorias
                if (!$valido($row[3] ?? '') || !$valido($row[4] ?? '')) {
                    Log::debug('[PolizaDeudaTempCarteraImport] fila saltada: Nacionalidad y FechaNacimiento son obligatorios', ['row3' => $row[3] ?? '', 'row4' => $row[4] ?? '']);
                    return null;
                }

                return new PolizaDeudaTempCartera([
                    'Dui'                 => trim($row[0]),
                    'Pasaporte'           => $row[1],
                    'CarnetResidencia'    => $row[2],
                    'Nacionalidad'        => $row[3],
                    'FechaNacimiento'     => $this->convertirFecha($row[4]),
                    'TipoPersona'         => $row[5],
                    'Sexo'                => $row[6], // GENERO
                    'PrimerApellido'      => $row[7],
                    'SegundoApellido'     => $row[8],
                    'ApellidoCasada'      => $row[9],
                    'PrimerNombre'        => $row[10],
                    'SegundoNombre'       => $row[11],
                    'NombreSociedad'      => $row[12],

                    'FechaOtorgamiento'   => $this->convertirFecha($row[13]),
                    'FechaVencimiento'    => $this->convertirFecha($row[14]),
                    'NumeroReferencia'    => $row[15],
                    'MontoOtorgado'       => $row[16],
                    'SaldoCapital'        => $row[17],
                    'Intereses'           => $row[18],
                    'InteresesMoratorios' => $row[19],
                    'InteresesCovid'      => $row[20],

                    'Tasa' => (isset($row[21]) && trim($row[21]) !== '' && is_numeric($row[21]))
                        ? (float) $row[21] : null, // columna de tarifa

                    'TipoDeuda'          => $row[22],
                    'PorcentajeExtraprima'        => $row[23],


                    'User'                => auth()->user()->id,
                    'Axo'                 => $this->Axo,
                    'Mes'                 => $this->Mes,
                    'PolizaDeuda'         => $this->PolizaDeuda,
                    'FechaInicio'         => $this->FechaInicio,
                    'FechaFinal'          => $this->FechaFinal,
                    'PolizaDeudaTipoCartera' => $this->credito,
                ]);
            }

            return null; // si no es encabezado y no es data, saltar fila
        } catch (\Exception $e) {
            // dd("Error en model(): " . $e->getMessage(), $row);
        }
    }


    private function convertirFecha($fechaExcel)
    {
        // Verificar si es un número (fecha en formato Excel)
        if (is_numeric($fechaExcel)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($fechaExcel - 2)->format('d/m/Y');
        }

        // Verificar si es un string en formato de fecha (dd/mm/yyyy o similar)
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fechaExcel)) {
            return Carbon::createFromFormat('d/m/Y', $fechaExcel)->format('d/m/Y');
        }

        // Verificar si es un string en formato de fecha (Y-m-d)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaExcel)) {
            return Carbon::createFromFormat('Y-m-d', $fechaExcel)->format('d/m/Y');
        }

        // Si no es un número de Excel ni un formato de fecha válido, devolver null
        return null;
    }
}
