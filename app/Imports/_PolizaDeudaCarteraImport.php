<?php

namespace App\Imports;

use App\Models\polizas\PolizaDeudaCartera;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PolizaDeudaCarteraImport implements ToModel, WithStartRow,SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2;
    }

    private $Axo;
    private $Mes;
    private $PolizaDeuda;


    public function __construct($Axo,$Mes,$PolizaDeuda,$FechaInicio,$FechaFinal)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
        $this->PolizaDeuda = $PolizaDeuda;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
    }
    public function model(array $row)
    {
        return new PolizaDeudaCartera([
            'Dui' => $row[0],
            'Pasaporte' => $row[1],
            'CarnetResidencia' => $row[2],
            'Nacionalidad' => $row[3],
            'FechaNacimiento' => $row[4],
            'TipoPersona' => $row[5],
            'Genero' => $row[6],
            'NombreCompleto' => $row[7],
            'NombreSociedad' => $row[8],
            'Direccion' => $row[9],
            'FechaOtorgamiento' => $row[10],
            'FechaVencimiento' => $row[11],
            'NumeroReferencia' => $row[12],
            'SumaAsegurada' => $row[13],
            'Tarifa' => $row[14],
            'PrimaMensual' => $row[15],
            'NumeroCuotas' => $row[16],
            'TipoDeuda' => $row[17],
            'ClaseCartera' => $row[18],
            'User' => auth()->user()->id,
            'Axo' =>  $this->Axo,
            'Mes' =>  $this->Mes,
            'PolizaDeuda' =>  $this->PolizaDeuda,
            'FechaInicio' =>  $this->FechaInicio,
            'FechaFinal' =>  $this->FechaFinal
        ]);
    }
}
