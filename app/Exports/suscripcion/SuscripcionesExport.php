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
                    'A' => 10, 'B' => 12, 'C' => 15, 'D' => 30, 'E' => 30, 'F' => 30, 'G' => 30, 'H' => 15,
                    'I' => 15, 'J' => 30, 'K' => 20, 'L' => 11, 'M' => 6, 'N' => 6, 'O' => 15, 'P' => 15,
                    'Q' => 15, 'R' => 15, 'S' => 10, 'T' => 15, 'U' => 17, 'V' => 15, 'W' => 15, 'X' => 20,
                    'Y' => 15, 'Z' => 10, 'AA' => 20, 'AB' => 40, 'AC' => 10, 'AD' => 10, 'AE' => 10, 'AF' => 10,
                    'AG' => 20, 'AH' => 10, 'AI' => 10,'AJ' => 30, 'AK' => 10,
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
