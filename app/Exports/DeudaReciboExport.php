<?php

namespace App\Exports;

use App\Models\catalogo\ConfiguracionRecibo;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaDetalle;
use Illuminate\Contracts\View\View;
use App\Models\polizas\DeudaHistorialRecibo;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DeudaReciboExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function view(): View
    {

        $id = $this->id;
        $detalle = DeudaDetalle::findOrFail($id);

        //dd($detalle->Deuda);
        $deuda = Deuda::findOrFail($detalle->Deuda);

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = DeudaHistorialRecibo::where('PolizaDeudaDetalle', $id)->orderBy('id', 'desc')->first();
        $configuracion = ConfiguracionRecibo::first();

        return view('polizas.deuda.recibo_excel', compact('recibo_historial', 'detalle', 'deuda', 'meses','configuracion'));
    }


    // public function styles(Worksheet $sheet)
    // {
    //     // Aplica un fondo blanco a todo el cuerpo
    //     $sheet->getStyle('A1:G32')->applyFromArray([
    //         'fill' => [
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'argb' => 'FFFFFF', // Color blanco
    //                // 'argb' => '000000', // Color blanco
    //             ],
    //         ],
    //     ]);

    //     $sheet->getStyle('A9:G9')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         'fill' => [ // Para establecer el color de fondo
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'argb' => 'C1C1C1', // Color de fondo gris claro
    //             ],
    //         ],
    //     ]);

    //     $sheet->getStyle('A11:G11')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         'fill' => [ // Para establecer el color de fondo
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'argb' => 'C1C1C1', // Color de fondo gris claro
    //             ],
    //         ],
    //     ]);

    //     $sheet->getStyle('A13:A17')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         'fill' => [ // Para establecer el color de fondo
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'argb' => 'C1C1C1', // Color de fondo gris claro
    //             ],
    //         ],
    //     ]);

    //     $sheet->getStyle('A9:G17')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         // 'fill' => [ // Para establecer el color de fondo
    //         //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //         //     'startColor' => [
    //         //         'argb' => 'C1C1C1', // Color de fondo gris claro
    //         //     ],
    //         // ],
    //     ]);

    //     $sheet->getStyle('E7:G7')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         // 'fill' => [ // Para establecer el color de fondo
    //         //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //         //     'startColor' => [
    //         //         'argb' => 'C1C1C1', // Color de fondo gris claro
    //         //     ],
    //         // ],
    //     ]);

    //     $sheet->getStyle('A19:G19')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         'fill' => [ // Para establecer el color de fondo
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'argb' => 'C1C1C1', // Color de fondo gris claro
    //             ],
    //         ],
    //     ]);
    //     $sheet->getStyle('A20:B26')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         // 'fill' => [ // Para establecer el color de fondo
    //         //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //         //     'startColor' => [
    //         //         'argb' => 'C1C1C1', // Color de fondo gris claro
    //         //     ],
    //         // ],
    //     ]);

    //     $sheet->getStyle('E20:G26')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         // 'fill' => [ // Para establecer el color de fondo
    //         //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //         //     'startColor' => [
    //         //         'argb' => 'C1C1C1', // Color de fondo gris claro
    //         //     ],
    //         // ],
    //     ]);

    //     $sheet->getStyle('A29:G31')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         // 'fill' => [ // Para establecer el color de fondo
    //         //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //         //     'startColor' => [
    //         //         'argb' => 'C1C1C1', // Color de fondo gris claro
    //         //     ],
    //         // ],
    //     ]);

    //     $sheet->getStyle('A29:G29')->applyFromArray([
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'], // Color negro
    //             ],
    //         ],
    //         'fill' => [ // Para establecer el color de fondo
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'argb' => 'C1C1C1', // Color de fondo gris claro
    //             ],
    //         ],
    //     ]);

    // }

    public function styles(Worksheet $sheet)
    {
        // Estilo base para bordes negros finos
        $bordersThinBlack = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Color negro
                ],
            ],
        ];

        // Estilo con fondo gris claro
        $fillGrayBackground = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'C1C1C1', // Fondo gris claro
                ],
            ],
        ];

        // Estilo con fondo blanco
        $fillWhiteBackground = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFFFF', // Fondo blanco
                ],
            ],
        ];

        // Aplicar un fondo blanco a todo el cuerpo
        $sheet->getStyle('A1:G42')->applyFromArray($fillWhiteBackground);

        // Estilos específicos
        $sheet->getStyle('A9:G9')->applyFromArray(array_merge($bordersThinBlack, $fillGrayBackground));
        $sheet->getStyle('A11:G11')->applyFromArray(array_merge($bordersThinBlack, $fillGrayBackground));
        $sheet->getStyle('A13:A17')->applyFromArray(array_merge($bordersThinBlack, $fillGrayBackground));
        $sheet->getStyle('A9:G17')->applyFromArray($bordersThinBlack);
        $sheet->getRowDimension(34)->setRowHeight(70);
        $sheet->getRowDimension(42)->setRowHeight(60);
        // Estilo con bordes negros gruesos
        $bordersThickBlack = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'], // Color negro
                ],
            ],
        ];
        $sheet->getStyle('E7:G7')->applyFromArray($bordersThickBlack);

        // Estilos adicionales con bordes negros y relleno gris
        $sheet->getStyle('A19:G19')->applyFromArray(array_merge($bordersThinBlack, $fillGrayBackground));
        $sheet->getStyle('A20:B26')->applyFromArray($bordersThinBlack);
        $sheet->getStyle('E20:G27')->applyFromArray($bordersThinBlack);
        $sheet->getStyle('A29:G31')->applyFromArray($bordersThinBlack);
        $sheet->getStyle('A29:G29')->applyFromArray(array_merge($bordersThinBlack, $fillGrayBackground));
    }
}
