<?php

namespace App\Imports;

use App\Models\temp\PolizaResidenciaTempCartera;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PolizaResidenciaTempCarteraImport implements ToModel,WithStartRow
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

    public function model(array $row)
    {
        return new PolizaResidenciaTempCartera([
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
            'NumeroReferencia' => $row[11],
            'SumaAsegurada' => $row[12],
            'Tarifa' => $row[13],
            'PrimaMensual' => $row[14],
            'NumeroCuotas' => $row[15],
            'TipoDeuda' => $row[16],
            'ClaseCartera' => $row[17],
        ]);
    }
}
