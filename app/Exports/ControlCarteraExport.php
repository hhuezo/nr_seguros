<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ControlCarteraExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $registros;

    public function __construct($registros)
    {
        $this->registros = $registros;
    }

    public function collection()
    {
        // Usar los registros pasados desde el controlador
        $registros = collect($this->registros);

        // Función auxiliar para formatear fechas de forma segura
        $formatDate = function ($date) {
            if (empty($date) || $date === null) {
                return '';
            }
            try {
                return Carbon::parse($date)->format('d/m/Y');
            } catch (\Exception $e) {
                return '';
            }
        };

        // Función auxiliar para formatear números
        $formatNumber = function ($number) {
            if ($number === null || $number === '') {
                return '';
            }
            return number_format((float)$number, 2, '.', ',');
        };

        // Formatear los datos para Excel
        return $registros->map(function ($item) use ($formatDate, $formatNumber) {
            return [
                $item->ClienteNombre ?? '',
                $formatDate($item->VigenciaDesde),
                $formatDate($item->VigenciaHasta),
                $item->PlanNombre ?? '',
                $item->Abreviatura ?? '',
                $item->NumeroPoliza ?? '',
                $formatDate($item->FechaRecepcionArchivo),
                $formatDate($item->FechaEnvioCia),
                $item->TrabajoEfectuadoDiaHabil ?? '',
                $item->HoraTarea ?? '',
                $item->FlujoAsignado ?? '',
                $item->Usuario ?? '',
                $formatNumber($item->UsuariosReportados),
                $formatNumber($item->MontoCartera),
                $item->Tasa ?? '',
                $formatNumber($item->PrimaCalculada),
                $formatNumber($item->ExtraPrima),
                $formatNumber($item->PrimaDescontada),
                $formatNumber($item->Descuento ?? ''),
                $formatNumber($item->ValorDescuentoRentabilidad ?? ''),
                $formatNumber($item->PrimaDescontada),
                $formatNumber($item->TasaComision),
                $formatNumber($item->Comision),
                $formatNumber($item->IvaSobreComision ?? $item->Iva ?? null),
                $formatNumber($item->Retencion),
                $formatNumber($item->APagar),
                $item->AnexoDeclaracion ?? '',
                $item->NumeroRecibo ?? '',
                $formatDate($item->FechaInicio),
                $formatDate($item->FechaEnvioCliente),
                $item->ReprocesoNombre ?? '',
                $formatDate($item->FechaEnvioCorreccion),
                $formatDate($item->FechaSeguimientoCobros),
                $formatDate($item->FechaRecepcionPago),
                $formatDate($item->FechaReporteACia),
                $formatDate($item->FechaAplicacion),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Asegurado',
            'Vigencia desde',
            'Vigencia hasta',
            'Tipo de póliza',
            'CIA. de seguros',
            'Póliza No.',
            'Fecha recepción archivo',
            'Fecha envío a CIA.',
            'Trabajo efectuado día hábil',
            'Hora tarea',
            'Flujo asignado',
            'Usuario',
            'Usuarios reportados',
            'Suma asegurada',
            'Tarifa',
            'Prima bruta',
            'Extra prima',
            'Prima emitida',
            '% de rentabilidad',
            'Valor descuento rentabilidad',
            'Prima descontada',
            '% de comisión',
            'Comisión neta',
            'IVA 13%',
            'Retención 1%',
            'Prima líquida',
            'Anexo de declaración',
            'Número AC SISCO',
            'Fecha vencimiento',
            'Fecha de envío a cliente',
            'Reproceso de NR',
            'Fecha de envío de corrección',
            'Fecha seguimiento cobros',
            'Fecha recepción de pago',
            'Fecha de reporte a CIA.',
            'Fecha de aplicación',
        ];
    }
}
