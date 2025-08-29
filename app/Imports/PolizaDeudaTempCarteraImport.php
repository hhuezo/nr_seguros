<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
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
            // Detectar encabezados (ya no viene "NIT", sino "DUI")
            if (trim($row[0]) === "DUI") {
                $this->encabezados = 1;
                return null; // no insertar encabezado
            }

            if ($this->encabezados == 1) {
                $Tasa = null;
                if ($this->TarifaExcel == 1) {
                    $Tasa = $row[23] ?? null; // PORCENTAJE EXTRAPRIMA está en la última columna
                }

                return new PolizaDeudaTempCartera([
                    'Nit'                 => null, // ya no existe en Excel
                    'Dui'                 => $row[0],
                    'Pasaporte'           => $row[1],
                    'Nacionalidad'        => $row[3],
                    'FechaNacimiento'     => $this->convertirFecha($row[4]),
                    'TipoPersona'         => $row[5],
                    'PrimerApellido'      => $row[7],
                    'SegundoApellido'     => $row[8],
                    'ApellidoCasada'      => $row[9],
                    'PrimerNombre'        => $row[10],
                    'SegundoNombre'       => $row[11],
                    'NombreSociedad'      => $row[12],
                    'Sexo'                => $row[6], // GENERO
                    'FechaOtorgamiento'   => $this->convertirFecha($row[13]),
                    'FechaVencimiento'    => $this->convertirFecha($row[14]),
                    'Ocupacion'           => null, // Excel ya no lo trae
                    'NumeroReferencia'    => $row[15],
                    'MontoOtorgado'       => $row[16],
                    'SaldoCapital'        => $row[17],
                    'Intereses'           => $row[18],
                    'InteresesMoratorios' => $row[19],
                    'InteresesCovid'      => $row[20],
                    'MontoNominal'        => $row[21], // TARIFA
                    'SaldoTotal'          => $row[22], // TIPO DE DEUDA
                    'User'                => auth()->user()->id,
                    'Axo'                 => $this->Axo,
                    'Mes'                 => $this->Mes,
                    'PolizaDeuda'         => $this->PolizaDeuda,
                    'FechaInicio'         => $this->FechaInicio,
                    'FechaFinal'          => $this->FechaFinal,
                    'PolizaDeudaTipoCartera' => $this->credito,
                    'Tasa'                => $Tasa,
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
