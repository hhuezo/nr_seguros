<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HistoricoPagosExport implements FromView
{
    use Exportable;
    protected $tabla_historico;

    public function __construct($tabla_historico = null)
    {
        $this->tabla_historico = $tabla_historico;
    }
    public function view(): View
    {
        $tabla_historico = $this->tabla_historico;

       // dd($empleados);

       return view('polizas.deuda.export_historico', compact('tabla_historico'));
    }

    public function styles(Worksheet $sheet)
    {
        // Aplica el formato de texto a la columna B (NumeroReferencia)
        $sheet->getStyle('A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    }
}




