<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Exception;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PolizaDeudaTempCarteraFedeComImport implements ToModel, /*WithStartRow,*/ SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    /*public function startRow(): int
    {
        return 2;
    }*/


    private $PolizaDeuda;
    private $FechaInicio;
    private $FechaFinal;
    private $credito;
    private $encabezados = 0;


    public function __construct($PolizaDeuda, $FechaInicio, $FechaFinal, $credito)
    {

        $this->PolizaDeuda = $PolizaDeuda;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
        $this->credito = $credito;
    }
    public function model(array $row)
    {
        if (trim($row[1]) == "DUI o documento de identidad") {
            $this->encabezados = 1;
            return null;
        }

        if ($this->encabezados == 1) {




            // Nuevo formato 16 columnas: tercer nombre se une a segundo nombre si viene
            $segundoNombre = trim((string) ($row[6] ?? ''));
            $tercerNombre = trim((string) ($row[7] ?? ''));
            if ($tercerNombre !== '') {
                $segundoNombre = $segundoNombre === '' ? $tercerNombre : $segundoNombre . ' ' . $tercerNombre;
            }
          //  dd($row);

            return new PolizaDeudaTempCartera([
                'TipoDocumento' => $row[0] ?? null,
                'Dui' => $row[1] ?? null,
                'PrimerApellido' => $row[2] ?? null,
                'SegundoApellido' => $row[3] ?? null,
                'ApellidoCasada' => $row[4] ?? null,
                'PrimerNombre' => $row[5] ?? null,
                'SegundoNombre' => $segundoNombre ?: null,
                'Nacionalidad' => $row[8] ?? null,
                'FechaNacimiento' => $row[9] ?? null,
                'Sexo' => $row[10] ?? null,
                'NumeroReferencia' => $row[11] ?? null,
                'FechaOtorgamiento' => $row[12] ?? null,
                'MontoOtorgado' => $row[13] ?? null,
                'SaldoCapital' => $row[14] ?? null,
                'Intereses' => $row[15] ?? null,
                'MoraCapital' => $row[16] ?? null,
                'InteresesMoratorios' => $row[17] ?? null,
                'InteresesCovid' => $row[18] ?? null,
                'PorcentajeExtraprima' => $row[19] ?? null,
                'Tasa' => (isset($row[20]) && trim($row[20]) !== '' && is_numeric($row[20]))
                    ? (float) $row[20] : null,
                'User' => auth()->user()->id,
                'Axo' => $row[22],
                'Mes' => $row[21],
                'NoValido' => 0,
                'PolizaDeuda' =>  $this->PolizaDeuda,
                'FechaInicio' =>  $this->FechaInicio,
                'FechaFinal' =>  $this->FechaFinal,
                'PolizaDeudaTipoCartera' => $this->credito,
            ]);
        }
    }
}
