<?php

namespace App\Imports;

use App\Models\temp\TempCartera;
use Maatwebsite\Excel\Concerns\ToModel;

class CarteraImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if (strlen(trim($row[1])) == 9) {

            return new TempCartera([
                'Usuario' => auth()->user()->id,
                'Nit'     => $row[0],
                'Dui'     => $row[1],
                'Pasaporte'     => $row[2],
                'Nacionalidad'     => $row[3],
                'FechaNacimiento'     => $row[4],
                'TipoPersona'     => $row[5],
                'PrimerApellido'     => $row[6],
                'SegundoApellido'     => $row[7],
                'CasadaApellido'     => $row[8],
                'PrimerNombre'     => $row[9],
                'SegundoNombre'     => $row[10],
                'SociedadNombre'     => $row[11],
                'Sexo'     => $row[12],
                'FechaOtorgamiento' => $row[13]
            ]);
        }
    }

    public function dateFormat($date)
    {
        return substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2);
    }
}
