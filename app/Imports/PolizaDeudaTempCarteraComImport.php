<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Exception;
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
        if (trim($row[0]) == "NIT" && trim($row[1]) == "DUI") {
            $this->encabezados = 1;
        }

        if ($this->encabezados == 1 && (trim($row[0]) != "NIT" && trim($row[1]) != "DUI")) {

            // Check if row[10] is empty and row[9] has two words with each word length >= 3
            // if (empty($row[10]) && strpos($row[9], '') !== false) {
            //     $words = explode(' ', $row[9]);
            //     if (count($words) >= 2 && strlen($words[0]) >= 3 && strlen($words[1]) >= 3) {
            //         $row[9] = $words[0];
            //         $row[10] = $words[1];
            //     }
            // }

            // $row[6] = str_replace(" ", ",", $row[6]);
            // $row[7] = str_replace(" ", ",", $row[7]);
            // $row[8] = str_replace(" ", ",", $row[8]);
            // $row[9] = str_replace(" ", ",", $row[9]);            
            // $row[10] = str_replace(" ", ",", $row[10]);


            return new PolizaDeudaTempCartera([
                'Nit' => $row[0],
                'Dui' => $row[1],
                'Pasaporte' => $row[2],
                'Nacionalidad' => $row[3],
                'FechaNacimiento' => $row[4],
                'TipoPersona' => $row[5],
                'PrimerApellido' => $row[6],
                'SegundoApellido' => $row[7],
                'ApellidoCasada' => $row[8],
                'PrimerNombre' => $row[9],
                'SegundoNombre' => $row[10],
                'NombreSociedad' => $row[11],
                'Sexo' => $row[12],
                'FechaOtorgamiento' => $row[13],
                'FechaVencimiento' => $row[14],
                'Ocupacion' => $row[15],
                'NumeroReferencia' => $row[16],
                'MontoOtorgado' => $row[17],
                'SaldoCapital' => $row[18],
                'Intereses' => $row[19],
                'InteresesMoratorios' => $row[20],
                'InteresesCovid' => $row[21],
                'MontoNominal' => $row[22],
                'SaldoTotal' => $row[23],
                'User' => auth()->user()->id,
                'Axo' =>  $row[29],
                'Mes' =>  $row[28],
                'PolizaDeuda' =>  $this->PolizaDeuda,
                'FechaInicio' =>  $this->FechaInicio,
                'FechaFinal' =>  $this->FechaFinal,
                'PolizaDeudaTipoCartera' => $this->credito,
                'NoValido' => 1,
            ]);
        }
    }
}
