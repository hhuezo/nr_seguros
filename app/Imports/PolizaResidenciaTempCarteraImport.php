<?php

namespace App\Imports;

use App\Models\temp\PolizaResidenciaTempCartera;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;



class PolizaResidenciaTempCarteraImport implements ToModel, WithStartRow, SkipsEmptyRows
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
    private $PolizaResidencia;


    public function __construct($Axo, $Mes, $PolizaResidencia, $FechaInicio, $FechaFinal)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
        $this->PolizaResidencia = $PolizaResidencia;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
    }

    public function model(array $row)
    {
        if (empty(trim($row[8] ?? ''))) {
            // Si el nombre completo está vacío o solo contiene espacios, no insertar
            return null;
        }

        // No insertar solo cuando las 3 columnas (1, 2 y 3) están vacías. Si alguna tiene valor, insertar.
        $col1 = trim((string) ($row[1] ?? ''));
        $col2 = trim((string) ($row[2] ?? ''));
        $col3 = trim((string) ($row[3] ?? ''));
        if ($col1 === '' && $col2 === '' && $col3 === '') {
            return null;
        }

        return new PolizaResidenciaTempCartera([
            'Dui' => $row[0],
            'Nit' => $row[1],
            'Pasaporte' => $row[2],
            'CarnetResidencia' => $row[3],
            'Nacionalidad' => $row[4],
            'FechaNacimiento' => $row[5],
            'TipoPersona' => $row[6],
            'Genero' => $row[7],
            'NombreCompleto' => $row[8],
            'NombreSociedad' => $row[9],
            'Direccion' => $row[10],
            'FechaOtorgamiento' => $row[11],
            'FechaVencimiento' => $row[12],
            'NumeroReferencia' => $row[13],
            'SumaAsegurada' => $row[14],
            'Tarifa' => $row[15],
            'PrimaMensual' => $row[16],
            'NumeroCuotas' => $row[17],
            'TipoDeuda' => $row[18],
            'ClaseCartera' => $row[19],
            'User' => auth()->user()->id,
            'Axo' =>  $this->Axo,
            'Mes' =>  $this->Mes,
            'PolizaResidencia' =>  $this->PolizaResidencia,
            'FechaInicio' =>  $this->FechaInicio,
            'FechaFinal' =>  $this->FechaFinal
        ]);
    }
}
