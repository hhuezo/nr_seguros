<?php

namespace App\Exports;

use App\Models\polizas\PolizaSeguro;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PolizaSeguroCertificadosExport implements FromView, WithEvents, ShouldAutoSize
{
    private PolizaSeguro $poliza;

    private Collection $camposDinamicos;

    public function __construct(PolizaSeguro $poliza)
    {
        $this->poliza = $poliza;
        $this->camposDinamicos = collect(optional(optional($poliza->producto)->certificadoCampos)->all() ?? []);
    }

    public function view(): View
    {
        return view('polizas.seguro.exports.certificados', [
            'poliza' => $this->poliza,
            'camposDinamicos' => $this->camposDinamicos,
            'filas' => $this->filas(),
            'totalColumnas' => max($this->camposDinamicos->count() + 1, 1),
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $ultimaColumna = $sheet->getHighestColumn();
                $ultimaFila = $sheet->getHighestRow();

                $sheet->getStyle('A4:' . $ultimaColumna . '4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '5B9BD5'],
                    ],
                ]);

                $sheet->getStyle('A1:' . $ultimaColumna . $ultimaFila)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9E2F3'],
                        ],
                    ],
                ]);
            },
        ];
    }

    private function filas(): Collection
    {
        $filas = collect();

        foreach ($this->poliza->certificados as $certificado) {
            $filas->push([
                'certificado' => $certificado->NumeroCertificado,
                'valores' => $this->valoresDinamicos($certificado->DatosJson),
            ]);

            foreach ($certificado->dependientes as $dependiente) {
                $filas->push([
                    'certificado' => $certificado->NumeroCertificado,
                    'valores' => $this->valoresDinamicos($dependiente->DatosJson),
                ]);
            }
        }

        return $filas;
    }

    private function valoresDinamicos($json): array
    {
        if ($json === null || trim((string) $json) === '') {
            return [];
        }

        $datos = json_decode($json, true);
        $datos = is_array($datos) ? $datos : [];

        return collect($datos)
            ->mapWithKeys(function ($dato) {
                return [($dato['CampoId'] ?? null) => $dato['Valor'] ?? ''];
            })
            ->filter(function ($valor, $campoId) {
                return $campoId !== null;
            })
            ->all();
    }
}
