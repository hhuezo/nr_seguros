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

        $valido = function ($v) {
            $s = is_string($v) ? trim($v) : trim((string) ($v ?? ''));
            return $s !== '' && strpos($s, ' ') === false;
        };

        // Al menos una de las 3 primeras (Dui, Nit, Pasaporte) debe tener datos
        $tieneDocumento = $valido($row[0] ?? '') || $valido($row[1] ?? '') || $valido($row[2] ?? '');
        if (!$tieneDocumento) {
            Log::debug('[PolizaResidenciaTempCarteraImport] fila saltada: al menos uno de Dui/Nit/Pasaporte debe tener datos', ['row0' => $row[0] ?? '', 'row1' => $row[1] ?? '', 'row2' => $row[2] ?? '']);
            return null;
        }

        // Columnas 5 y 6 (Nacionalidad, FechaNacimiento) son obligatorias
        if (!$valido($row[4] ?? '') || !$valido($row[5] ?? '')) {
            Log::debug('[PolizaResidenciaTempCarteraImport] fila saltada: Nacionalidad y FechaNacimiento son obligatorios', ['row4' => $row[4] ?? '', 'row5' => $row[5] ?? '']);
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
