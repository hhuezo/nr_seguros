<?php

namespace App\Imports;

use App\Models\temp\DesempleoCarteraTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DesempleoCarteraTempImport implements ToModel, WithStartRow, SkipsEmptyRows
{
    private $Axo;
    private $Mes;
    private $Poliza;
    private $FechaInicio;
    private $FechaFinal;
    private $tipo_cartera;

    public function __construct($Axo, $Mes, $Poliza, $FechaInicio, $FechaFinal, $tipo_cartera)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
        $this->Poliza = $Poliza;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
        $this->tipo_cartera = $tipo_cartera;
    }

    /**
     * 📄 Indica que los datos inician desde la fila 2 (la primera es encabezado)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * 🧩 Convierte cada fila en un modelo
     */
    public function model(array $row)
    {
        // Si la fila está completamente vacía, la omitimos
        if (empty(array_filter($row))) {
            Log::info('Fila vacía omitida en importación DesempleoCarteraTemp.');
            return null;
        }

        try {
            // Verificar si tiene algún identificador (DUI, Pasaporte o Carnet)
            if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                Log::warning('Fila sin identificador omitida.', ['row' => $row]);
                return null;
            }

            //validar que las primeras 5 columnas no esten vacias o contengan espacios
            for ($i = 0; $i < 5; $i++) {
                if (empty(trim($row[$i])) || strpos(trim($row[$i]), ' ') !== false) {
                    return null;
                }
            }

            // Crear registro normalmente
            return new DesempleoCarteraTemp([
                'PolizaDesempleo' => $this->Poliza,
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
                'FechaOtorgamiento' => $this->convertirFecha($row[13]),
                'FechaVencimiento' => $this->convertirFecha($row[14]),
                'NumeroReferencia' => $row[15] ?? null,
                'MontoOtorgado' => $row[16] ?? null,
                'SaldoCapital' => $row[17] ?? null,
                'Intereses' => $row[18] ?? null,
                'MoraCapital' => $row[19] ?? null,
                'InteresesMoratorios' => $row[20] ?? null,
                'InteresesCovid' => $row[21] ?? null,
                'Tarifa' => $row[22] ?? null,
                'TipoDeuda' => $row[23] ?? null,
                'PorcentajeExtraprima' => $row[24] ?? null,
                'User' => auth()->id(),
                'Axo' => $this->Axo,
                'Mes' => $this->Mes,
                'FechaInicio' => $this->FechaInicio,
                'FechaFinal' => $this->FechaFinal,
                'FechaNacimientoDate' => $this->convertirFecha($row[4]),
                'FechaOtorgamientoDate' => $this->convertirFecha($row[13]),
                'NoValido' => 0,
                'Excluido' => 0,
                'Rehabilitado' => 0,
                'DesempleoTipoCartera' => $this->tipo_cartera,

            ]);
        } catch (\Throwable $e) {
            // Si ocurre un error al procesar una fila, se registra en el log
            Log::error('Error al importar fila DesempleoCarteraTemp', [
                'error' => $e->getMessage(),
                'row' => $row,
            ]);

            return null; // no interrumpe el proceso
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
