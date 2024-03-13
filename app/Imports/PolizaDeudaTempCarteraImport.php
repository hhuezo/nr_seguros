<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Exception;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PolizaDeudaTempCarteraImport implements ToModel, /*WithStartRow,*/ SkipsEmptyRows
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

    private $Axo;
    private $Mes;
    private $PolizaDeuda;
    private $FechaInicio;
    private $FechaFinal;
    private $credito;
    private $encabezados = 0;


    public function __construct($Axo, $Mes, $PolizaDeuda, $FechaInicio, $FechaFinal,$credito)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
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
                'Axo' =>  $this->Axo,
                'Mes' =>  $this->Mes,
                'PolizaDeuda' =>  $this->PolizaDeuda,
                'FechaInicio' =>  $this->FechaInicio,
                'FechaFinal' =>  $this->FechaFinal,
                'LineaCredito' => $this->credito,
            ]);
        }
    }
}
