<?php

namespace App\Exports\vida;

use App\Models\polizas\Vida;
use App\Models\polizas\PolizaVidaExtraPrimados;
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
    protected $vida;

    public function __construct($vida = null)
    {
        $this->vida = $vida;
    }
    public function collection()
    {
        $vida = Vida::findOrFail($this->vida);
        $extra_primados = PolizaVidaExtraPrimados::from('poliza_vida_extra_primado as ep')
        ->join('poliza_vida as vida', 'ep.PolizaVida', '=', 'vida.Id')
        ->where('ep.PolizaVida', $vida->Id)
        ->select(
            'vida.NumeroPoliza',
            DB::raw("CONCAT(FORMAT(ep.PorcentajeEP, 2), '%') AS PorcentajeEP"),
            'ep.NumeroReferencia',
            'ep.Nombre',
            'ep.Dui',
            'ep.FechaOtorgamiento',
            DB::raw("CONCAT('$', FORMAT(ep.MontoOtorgamiento, 2)) AS MontoOtorgamiento"),
            // DB::raw("CONCAT('$', FORMAT(ep.Intereses, 2)) AS Intereses")
        )
        ->get();

        return $extra_primados;
    }
    public function headings(): array
    {
        return [
            'PolizaVida',
            'PorcentajeEP',
            'NumeroReferencia',
            'Nombre',
            'Dui',
            'FechaOtorgamiento',
            'MontoOtorgamiento',
            // 'Intereses'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
