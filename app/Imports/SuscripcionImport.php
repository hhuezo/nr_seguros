<?php

namespace App\Imports;

use App\Models\polizas\Deuda;
use App\Models\suscripcion\SuscripcionTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class SuscripcionImport implements ToModel, WithStartRow,  SkipsOnFailure, SkipsEmptyRows, WithCalculatedFormulas
{
    use SkipsFailures;

    protected $customFailures = [];
    protected $currentRow = 6;

    public function customFailures()
    {
        return $this->customFailures;
    }

    // 👇 Esto indica que los datos comienzan en la fila 6
    public function startRow(): int
    {
        return 6;
    }

    public function model(array $row)
    {

        //dd($row);

        if (trim($row[0]) != '' && trim($row[1]) != '' && (array_key_exists(9, $row) && trim($row[9]) != '')) {

            // Verificar si todos los campos están vacíos
            if (collect($row)->filter(function ($item) {
                return trim($item) !== '';
            })->isEmpty()) {
                return null;
            }



            if (!isset($row[7]) || trim($row[7]) === '') {
                $this->customFailures[] = [
                    'row' => $this->currentRow,
                    'attribute' => 'NumeroPolizaDeuda',
                    'errors' => ['El valor Numero Poliza Deuda es requerido.'],
                    'value' => $row[7]
                ];
            }

            if (isset($row[7]) && trim($row[7]) !== '') {

                $deuda = Deuda::where('NumeroPoliza', $row[7])->exists();
                if ($deuda == false) {
                    $this->customFailures[] = [
                        'row' => $this->currentRow,
                        'attribute' => 'NumeroPolizaDeuda',
                        'errors' => ['El valor ' . $row[7] . ' no es válido como número de póliza.'],
                        'value' => $row[7]
                    ];
                }
            }

            if (!isset($row[9]) || trim($row[9]) === '') {
                $this->customFailures[] = [
                    'row' => $this->currentRow,
                    'attribute' => 'Asegurado',
                    'errors' => ['El valor Asegurado es requerido.'],
                    'value' => ""
                ];
            }
            $this->currentRow++;


            return new SuscripcionTemp([
                'FechaIngreso' => $this->convertirFecha($row[1]) ?? null,
                'FechaEntregaDocsCompletos' => $this->convertirFecha($row[2]) ?? null,
                'DiasParaCompletarInfoCliente' => (is_numeric($row[3]) && $row[3] < 0) ? null : $row[3] ?? null,
                'Gestor' => $row[4] ?? null,
                'Cia' => $row[5] ?? null,
                'Contratante' => $row[6] ?? null,
                'NumeroPolizaDeuda' => $row[7] ?? null,
                'NumeroPolizaVida' => $row[8] ?? null,
                'Asegurado' => $row[9] ?? null,
                'Ocupacion' => $row[10] ?? null,
                'DocumentoIdentidad' => $row[11] ?? null,
                'Edad' => $row[12] ?? null,
                'Genero' => $row[13] ?? null,
                'SumaAseguradaEvaluadaDeuda' => $row[14] ?? null,
                'SumaAseguradaEvaluadaVida' => $row[15] ?? null,
                'TipoCliente' => $row[16] ?? null,
                'TipoCredito' => $row[17] ?? null,
                'Imc' => $row[18] ?? null,
                'TipoImc' => $row[19] ?? null,
                'Padecimientos' => $row[20] ?? null,
                'TipoOrdenMedica' => $row[21] ?? null,
                'EstatusDelCaso' => $row[22] ?? null,
                'ResumenDeGestion' => $row[23] ?? null,
                'FechaReportadoCia' => isset($row[24]) ? $this->convertirFecha($row[24]) : null,
                'TrabajoEfectuadoDiaHabil' => $row[25] ?? null,
                'TareasEvaSisa' => $row[26] ?? null,
                'ComentariosNrSuscripcion' => $row[27] ?? null,
                'FechaCierreGestion' => isset($row[28]) ? $this->convertirFecha($row[28]) : null,
                'FechaRecepcionResolucionCia' => isset($row[29]) ? $this->convertirFecha($row[29]) : null,
                'FechaEnvioResolucionCliente' => isset($row[30]) ? $this->convertirFecha($row[30]) : null,
                'DiasProcesamientoResolucion' => $row[31] ?? null,
                'ResolucionOficial' => $row[32] ?? null,
                'PorcentajeExtraprima' => $row[33] ?? null,
                'Usuario' => auth()->user()->id,
            ]);
        }
    }

    private function convertirFecha($fechaExcel)
    {
        // Verificar si es un número (fecha en formato Excel)
        if (is_numeric($fechaExcel)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($fechaExcel - 2)->format('Y-m-d');
        }

        // Verificar si es un string en formato de fecha (dd/mm/yyyy o similar)
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fechaExcel)) {
            return Carbon::createFromFormat('d/m/Y', $fechaExcel)->format('Y-m-d');
        }

        // Verificar si es un string en formato de fecha (Y-m-d)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaExcel)) {
            return Carbon::createFromFormat('Y-m-d', $fechaExcel)->format('Y-m-d');
        }

        // Si no es un número de Excel ni un formato de fecha válido, devolver null
        return null;
    }
}
