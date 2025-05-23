<?php

namespace App\Imports;

use App\Models\temp\VidaCarteraTemp;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class VidaCarteraTempFedeImport implements ToModel
{
    private $Axo;
    private $Mes;
    private $Poliza;
    private $FechaInicio;
    private $FechaFinal;
    private $encabezados = 0;
    private $PolizaVidaTipoCartera;

    public function __construct($Axo, $Mes, $Poliza, $FechaInicio, $FechaFinal,$PolizaVidaTipoCartera)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
        $this->Poliza = $Poliza;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
        $this->PolizaVidaTipoCartera = $PolizaVidaTipoCartera;
    }

    public function model(array $row)
    {
        // Saltar la fila de encabezados
        if (trim($row[0]) == "DUI") {
            $this->encabezados = 1;
            return null;
        }

        // Procesar solo las filas de datos
        if ($this->encabezados == 1 && (trim($row[0]) != "DUI")) {

            // Verificar que al menos uno de los dos campos (NIT o DUI) tenga datos
            if (!empty(trim($row[0])) || !empty(trim($row[1]))) {
                return new VidaCarteraTemp([
                    'PolizaVida' => $this->Poliza,
                    //'Nit' => $row[0] ?? null,
                    'Dui' => $row[0] ?? null,
                    'PrimerApellido' => $row[1] ?? null,
                    'SegundoApellido' => $row[2] ?? null,
                    'PrimerNombre' => $row[3] ?? null,
                    'FechaNacimiento' => $this->convertirFecha($row[4] ?? null),
                    'Sexo' => $row[5] ?? null,
                    'NumeroReferencia' => $row[6] ?? null,
                    'SumaAsegurada' => $row[7] ?? null,
                    'User' => auth()->id(),
                    'Axo' => $this->Axo,
                    'Mes' => $this->Mes,
                    'FechaInicio' => $this->FechaInicio,
                    'FechaFinal' => $this->FechaFinal,
                    'FechaNacimientoDate' => $this->convertirFecha($row[4] ?? null, 'Y-m-d'), // FECHA NACIMIENTO (formato Y-m-d)
                    'PolizaVidaTipoCartera' => $this->PolizaVidaTipoCartera,
                ]);
            }
        }

        return null;
    }

    private function convertirFecha($fechaExcel)
    {

        // Verificar si es un número (fecha en formato Excel)
        if (is_numeric($fechaExcel)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($fechaExcel - 2)->format('Y-m-d');
        }

        // Verificar si es un string en formato de fecha (dd/mm/yyyy o similar)
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fechaExcel)) {
            return Carbon::createFromFormat('d/m/Y', $fechaExcel)->format('Y-m-d');
        }

        // Verificar si es un string en formato de fecha (Y-m-d)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaExcel)) {
            return Carbon::createFromFormat('Y-m-d', $fechaExcel)->format('Y-m-d');
        }

        //dd($fechaExcel,4);
        // Si no es un número de Excel ni un formato de fecha válido, devolver null
        return null;
    }
}
