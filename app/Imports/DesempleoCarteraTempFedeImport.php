<?php

namespace App\Imports;

use App\Models\DesempleoCarteraTemp;
use App\Models\temp\DesempleoCarteraTemp as TempDesempleoCarteraTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DesempleoCarteraTempFedeImport implements ToModel, WithStartRow
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

            // Crear registro normalmente
            return new TempDesempleoCarteraTemp([
                'PolizaDesempleo'       => $this->Poliza,
                'TipoPersona'           => $row[0] ?? null, // TIPO DE DOCUMENTO
                'Dui'                   => $row[1] ?? null, // DUI O DOCUMENTO DE IDENTIDAD
                'PrimerApellido'        => $row[2] ?? null, // PRIMER APELLIDO
                'SegundoApellido'       => $row[3] ?? null, // SEGUNDO APELLIDO
                'PrimerNombre'          => $row[4] ?? null, // NOMBRES
                'Nacionalidad'          => $row[5] ?? null, // NACIONALIDAD
                'FechaNacimiento'       => $this->convertirFecha($row[6] ?? null), // FECHA DE NACIMIENTO
                'Sexo'                  => $row[7] ?? null, // GÉNERO
                'NumeroReferencia'      => $row[8] ?? null, // NRO. DE PRÉSTAMO
                'FechaOtorgamiento'     => $this->convertirFecha($row[9] ?? null), // FECHA DE OTORGAMIENTO
                'MontoOtorgado'         => $row[10] ?? null, // MONTO ORIGINAL DE DESEMBOLSO
                'SaldoCapital'          => $row[11] ?? null, // SALDO DE DEUDA CAPITAL ACTUAL
                'Intereses'             => $row[12] ?? null, // SALDO INTERESES CORRIENTES
                'MoraCapital'           => $row[13] ?? null, // MORA CAPITAL
                'InteresesMoratorios'   => $row[14] ?? null, // SALDO INTERESES POR MORA
                'InteresesCovid'        => $row[15] ?? null, // INTERESES COVID
                'PorcentajeExtraprima'  => $row[16] ?? null, // EXTRA PRIMA
                'Tarifa'                => $row[17] ?? null, // TARIFA

                // Valores adicionales automáticos
                'User'                  => auth()->id(),
                'Axo'                   => $this->Axo,
                'Mes'                   => $this->Mes,
                'FechaInicio'           => $this->FechaInicio,
                'FechaFinal'            => $this->FechaFinal,

                // Conversiones de fecha
                'FechaNacimientoDate'   => $this->convertirFecha($row[6] ?? null),
                'FechaOtorgamientoDate' => $this->convertirFecha($row[9] ?? null),

                // Valores por defecto
                'NoValido'              => 0,
                'Excluido'              => 0,
                'Rehabilitado'          => 0,

                // Tipo de cartera
                'DesempleoTipoCartera'  => $this->tipo_cartera,
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
