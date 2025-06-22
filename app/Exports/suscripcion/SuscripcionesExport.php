<?php

namespace App\Exports\suscripcion;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SuscripcionesExport implements FromView, WithEvents
{
    protected $suscripciones;

    public function __construct($suscripciones = null)
    {
        $this->suscripciones = $suscripciones;
    }

    public function view(): View
    {
        return view('suscripciones.suscripcion.report', [
            'suscripciones' => $this->suscripciones
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Estilo azul para encabezado
                $sheet->getStyle('A1:AK1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0070C0'],
                    ],
                ]);

                // 2. Asignar anchos fijos por columna
                $columnWidths = [
                    'A' => 20, 'B' => 19, 'C' => 20, 'D' => 22, 'E' => 18, 'F' => 18, 'G' => 20, 'H' => 25,
                    'I' => 25, 'J' => 20, 'K' => 19, 'L' => 11, 'M' => 11, 'N' => 11, 'O' => 20, 'P' => 26,
                    'Q' => 15, 'R' => 15, 'S' => 15, 'T' => 15, 'U' => 17, 'V' => 20, 'W' => 18, 'X' => 20,
                    'Y' => 20, 'Z' => 25, 'AA' => 28, 'AB' => 40, 'AC' => 25, 'AD' => 20, 'AE' => 20, 'AF' => 22,
                    'AG' => 20, 'AH' => 17, 'AI' => 50, 'AK' => 17,
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // 3. Estilo de bordes negros (toda la tabla)
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $range = 'A1:' . $highestColumn . $highestRow;

                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
