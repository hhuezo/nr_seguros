<?php

namespace App\Imports;

use App\Models\temp\DesempleoCarteraTemp;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DesempleoCarteraCompFedeImport implements  ToModel, WithStartRow
{

    private $Poliza;
    private $FechaInicio;
    private $FechaFinal;
    private $tipo_cartera;

    public function __construct( $Poliza, $FechaInicio, $FechaFinal, $tipo_cartera)
    {

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
            // Verificar si tiene algún identificador (columna 1 = DUI/documento)
            if (empty($row[1])) {
                Log::warning('Fila sin identificador omitida.', ['row' => $row]);
                return null;
            }

            // Fedecrédito desempleo: 21 columnas. Tercer nombre se une a SegundoNombre.
            $segundoNombre = trim(($row[6] ?? '') . ' ' . ($row[7] ?? ''));
            // dd($row);

            return new DesempleoCarteraTemp([
                'PolizaDesempleo'       => $this->Poliza,
                'TipoPersona'           => $row[0] ?? null,   // Tipo de documento
                'Dui'                   => $row[1] ?? null,   // DUI o documento de identidad
                'PrimerApellido'        => $row[2] ?? null,   // Primer Apellido
                'SegundoApellido'       => $row[3] ?? null,   // Segundo Apellido
                'ApellidoCasada'        => $row[4] ?? null,   // Apellido de casada
                'PrimerNombre'          => $row[5] ?? null,   // primer nombre
                'SegundoNombre'         => $segundoNombre ?: null, // segundo + tercer nombre
                'Nacionalidad'          => $row[8] ?? null,   // Nacionalidad
                'FechaNacimiento'       => $this->convertirFecha($row[9] ?? null),   // Fecha de Nacimiento
                'Sexo'                  => $row[10] ?? null, // Género
                'NumeroReferencia'      => $row[11] ?? null,  // Nro. de Préstamo
                'FechaOtorgamiento'     => $this->convertirFecha($row[12] ?? null), // Fecha de otorgamiento
                'MontoOtorgado'         => $row[13] ?? null,  // Monto original de desembolso
                'SaldoCapital'          => $row[14] ?? null,  // Saldo de deuda capital actual
                'Intereses'             => $row[15] ?? null,  // Saldo intereses corrientes
                'MoraCapital'           => $row[16] ?? null,  // Mora capital
                'InteresesMoratorios'   => $row[17] ?? null,  // Saldo intereses por mora
                'InteresesCovid'        => $row[18] ?? null, // Intereses Covid
                'PorcentajeExtraprima'  => $row[19] ?? null, // Extra Prima
                'Tarifa'                => $row[20] ?? null,  // TARIFA

                'User'                  => auth()->user()->id,
                'Axo'                   => $row[22],
                'Mes'                   => $row[21],
                'FechaInicio'           => $this->FechaInicio,
                'FechaFinal'            => $this->FechaFinal,
                'FechaNacimientoDate'   => $this->convertirFecha($row[9] ?? null),
                'FechaOtorgamientoDate' => $this->convertirFecha($row[12] ?? null),
                'NoValido'               => 0,
                'Excluido'               => 0,
                'Rehabilitado'           => 0,
                'DesempleoTipoCartera'   => $this->tipo_cartera,
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
