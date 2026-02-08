<?php

namespace App\Exports;

use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeudaExtraPrimados;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExtraPrimadosExport implements FromCollection, WithStyles, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $deuda;

    public function __construct($deuda = null)
    {
        $this->deuda = $deuda;
    }
    public function collection()
    {
        $deuda = Deuda::findOrFail($this->deuda);
        $extra_primados = PolizaDeudaExtraPrimados::where('PolizaDeuda', $deuda->Id)
            ->join('poliza_deuda as deuda', 'poliza_deuda_extra_primado.PolizaDeuda', '=', 'deuda.Id')
            ->select(
                'deuda.NumeroPoliza',
                DB::raw("CONCAT(FORMAT(poliza_deuda_extra_primado.PorcentajeEP, 2), '%') AS PorcentajeEP"),
                'poliza_deuda_extra_primado.NumeroReferencia',
                'poliza_deuda_extra_primado.Nombre',
                'poliza_deuda_extra_primado.Dui',
                'poliza_deuda_extra_primado.FechaOtorgamiento',
                DB::raw("CONCAT('$',FORMAT(poliza_deuda_extra_primado.MontoOtorgamiento, 2)) AS MontoOtorgamiento"),
                DB::raw("CONCAT('$',FORMAT(poliza_deuda_extra_primado.Intereses, 2)) AS Intereses")
            )
            ->get();

        return $extra_primados;
    }
    public function headings(): array
    {
        return [
            'PolizaDeuda',
            'PorcentajeEP',
            'NumeroReferencia',
            'Nombre',
            'Dui',
            'FechaOtorgamiento',
            'MontoOtorgamiento',
            'Intereses'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
