<?php

namespace App\Imports;

use App\Models\polizas\Residencia;
use App\Models\temp\PolizaResidenciaTempCartera;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;



class PolizaResidenciaTempCarteraFedeImport implements ToModel, WithStartRow, SkipsEmptyRows
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
    private $FechaInicio;
    private $FechaFinal;


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

        // No insertar solo cuando las 3 columnas (1, 2 y 3) están vacías. Si alguna tiene valor, insertar.
        $col1 = trim((string) ($row[1] ?? ''));
        $col2 = trim((string) ($row[2] ?? ''));
        $col3 = trim((string) ($row[3] ?? ''));
        if ($col1 === '' && $col2 === '' && $col3 === '') {
            return null;
        }


        return new PolizaResidenciaTempCartera([
            'TipoPersona' => $row[0],  //TipoDocumento
            'Dui' => $row[1],
            'NombreCompleto' => trim($row[2] . ' ' . $row[3] . ' ' . $row[4] . ' ' . $row[5] . ' ' . $row[6] . ' ' . $row[7]),
            'Nacionalidad' => $row[8],
            'FechaNacimiento' => $this->convertirFecha($row[9]),
            'Genero' => $row[10],
            'NumeroReferencia' => $row[11],
            'SumaAsegurada' => $row[12],
            'Direccion' => $row[18] . ',' . $row[15] . ',' . $row[14] . ',' . $row[13],
            'User' => auth()->user()->id,
            'Axo' =>  $this->Axo,
            'Mes' =>  $this->Mes,
            'PolizaResidencia' =>  $this->PolizaResidencia,
            'FechaInicio' =>  $this->FechaInicio,
            'FechaFinal' =>  $this->FechaFinal
        ]);
    }

    private function convertirFecha($fechaExcel)
    {
        // Verificar si es un número (fecha en formato Excel)
        if (is_numeric($fechaExcel)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($fechaExcel - 2)->format('d/m/Y');
        }

        // Verificar si es un string en formato de fecha (dd/mm/yyyy o similar)
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fechaExcel)) {
            return Carbon::createFromFormat('d/m/Y', $fechaExcel)->format('d/m/Y');
        }

        // Verificar si es un string en formato de fecha (Y-m-d)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaExcel)) {
            return Carbon::createFromFormat('Y-m-d', $fechaExcel)->format('d/m/Y');
        }

        // Si no es un número de Excel ni un formato de fecha válido, devolver null
        return null;
    }
}
