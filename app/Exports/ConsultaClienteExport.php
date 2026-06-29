<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsultaClienteExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private Collection $resultados;

    public function __construct(Collection $resultados)
    {
        $this->resultados = $resultados;
    }

    public function collection()
    {
        return $this->resultados->map(function ($resultado) {
            return [
                $resultado->AseguradoraNombre ?? '',
                $resultado->ProductoPlan ?? '',
                $resultado->NumeroPoliza ?? '',
                $resultado->PeriodoRegistro ?? '',
                $this->formatearPorcentaje($resultado->TarifaMes ?? null, 4),
                $resultado->ContratanteNombre ?? '',
                $resultado->LineaDescripcion ?? '',
                $this->documentoIdentidad($resultado),
                $resultado->Nacionalidad ?? '',
                $this->formatearFecha($resultado->FechaNacimiento ?? null),
                $resultado->PrimerApellido ?? '',
                $resultado->SegundoApellido ?? '',
                $resultado->ApellidoCasada ?? '',
                $resultado->PrimerNombre ?? '',
                $resultado->SegundoNombre ?? '',
                $resultado->NombreSociedad ?? '',
                $this->formatearFecha($resultado->FechaOtorgamiento ?? null),
                $this->formatearFecha($resultado->FechaVencimiento ?? null),
                $resultado->NumeroReferencia ?? '',
                $this->formatearDinero($resultado->MontoOtorgado ?? null),
                $this->formatearDinero($resultado->SumaAsegurada ?? null),
                $this->formatearDinero($resultado->SaldoCapital ?? null),
                $this->formatearDinero($resultado->Intereses ?? null),
                $this->formatearDinero($resultado->InteresesMoratorios ?? null),
                $this->formatearDinero($resultado->InteresesCovid ?? null),
                $this->formatearPorcentaje($resultado->PorcentajeExtraprima ?? null, 2),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Aseguradora',
            'Producto / Plan',
            'Poliza',
            'Periodo',
            'Tarifa Mes',
            'Contratante',
            'Linea',
            'Documento Identidad',
            'Nacionalidad',
            'Fec. Nacimiento',
            'Primer Apellido',
            'Segundo Apellido',
            'Apellido Casada',
            'Primer Nombre',
            'Segundo Nombre',
            'Nombre Sociedad',
            'Fec. Otorgamiento',
            'Fec. Vencimiento',
            'Num. Referencia',
            'Monto Otorgado',
            'Suma Asegurada',
            'Saldo Capital',
            'Interes Corriente',
            'Interes Moratorio',
            'Interes COVID',
            '% Extraprima',
        ];
    }

    private function documentoIdentidad($resultado): string
    {
        if (!empty($resultado->Dui)) {
            return 'DUI: ' . $resultado->Dui;
        }

        if (!empty($resultado->Nit)) {
            return 'NIT: ' . $resultado->Nit;
        }

        if (!empty($resultado->Pasaporte)) {
            return 'Pasaporte: ' . $resultado->Pasaporte;
        }

        if (!empty($resultado->CarnetResidencia)) {
            return 'Carnet: ' . $resultado->CarnetResidencia;
        }

        return '';
    }

    private function formatearFecha($fecha): string
    {
        if (!$fecha) {
            return '';
        }

        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', (string) $fecha)) {
                return Carbon::createFromFormat('d/m/Y', $fecha)->format('d/m/Y');
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}/', (string) $fecha)) {
                return Carbon::parse($fecha)->format('d/m/Y');
            }
        } catch (\Exception $e) {
            return (string) $fecha;
        }

        return (string) $fecha;
    }

    private function formatearDinero($valor): string
    {
        if ($valor === '' || $valor === null) {
            return '';
        }

        return '$' . number_format((float) $valor, 2, '.', ',');
    }

    private function formatearPorcentaje($valor, int $decimales): string
    {
        if ($valor === '' || $valor === null) {
            return '';
        }

        $valorFormateado = number_format((float) $valor, $decimales, '.', '');
        $valorFormateado = rtrim(rtrim($valorFormateado, '0'), '.');

        return $valorFormateado . '%';
    }
}
