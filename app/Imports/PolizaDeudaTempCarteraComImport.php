<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PolizaDeudaTempCarteraComImport implements ToModel, /*WithStartRow,*/ SkipsEmptyRows
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
        if (trim($row[0]) === "DUI") {
            $this->encabezados = 1;
            return null;
        }

        if ($this->encabezados == 1 ) {




            return new PolizaDeudaTempCartera([
                // 'Nit' => $row[0],

                'Dui' => $row[0] ?? null,
                'Pasaporte' => $row[1] ?? null,
                'CarnetResidencia' => $row[2] ?? null,
                'Nacionalidad' => $row[3] ?? null,
                'FechaNacimiento' => $row[4] ?? null,
                'TipoPersona' => $row[5] ?? null,
                'Sexo' => $row[6] ?? null,
                'PrimerApellido' => $row[7] ?? null,
                'SegundoApellido' => $row[8] ?? null,
                'ApellidoCasada' => $row[9] ?? null,
                'PrimerNombre' => $row[10] ?? null,
                'SegundoNombre' => $row[11] ?? null,
                'NombreSociedad' => $row[12] ?? null,
                'FechaOtorgamiento' => $row[13] ?? null,
                'FechaVencimiento' => $row[14] ?? null,
                'NumeroReferencia' => $row[15] ?? null,
                'MontoOtorgado' => $row[16] ?? null,
                'SaldoCapital'        => $row[17],
                'Intereses'           => $row[18],
                'InteresesMoratorios' => $row[19],
                'InteresesCovid'      => $row[20],
                'Tasa' => (isset($row[21]) && trim($row[21]) !== '' && is_numeric($row[21]))
                    ? (float) $row[21] : null, // columna de tarifa

                'TipoDeuda'          => $row[22],
                'PorcentajeExtraprima' => $row[23],

                'User' => auth()->id(),
                'Axo' => $row[25],
                'Mes' => $row[24],

                'PolizaDeuda' =>  $this->PolizaDeuda,
                'FechaInicio' =>  $this->FechaInicio,
                'FechaFinal' =>  $this->FechaFinal,
                'PolizaDeudaTipoCartera' => $this->credito,
                'NoValido' => 0,
            ]);
        }
    }


}
