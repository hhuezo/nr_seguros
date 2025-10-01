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
        if (trim($row[1]) == "DUI o documento de identidad") {
            $this->encabezados = 1;
            return null;
        }
        
        // dd($row);
        // Procesar solo las filas de datos
        if ($this->encabezados == 1 && (trim($row[1]) != "DUI o documento de identidad")) {

            // Verificar que al menos uno de los dos campos (NIT o DUI) tenga datos
            if (!empty(trim($row[0])) || !empty(trim($row[1]))) {
                return new VidaCarteraTemp([
                    'PolizaVida' => $this->Poliza,
                    //'Nit' => $row[0] ?? null,
                    'Dui' => $row[1] ?? null,
                    'PrimerApellido' => $row[2] ?? null,
                    'SegundoApellido' => $row[3] ?? null,
                    'PrimerNombre' => $row[4] ?? null,
                    'Nacionalidad' => $row[5] ?? null,
                    'FechaNacimiento' => $this->convertirFecha($row[6] ?? null),
                    'Sexo' => $row[7] ?? null,
                    'NumeroReferencia' => $row[8] ?? null,
                    'SumaAsegurada' => $row[10] ?? null,
                    'User' => auth()->id(),
                    'Axo' => $this->Axo,
                    'Mes' => $this->Mes,
                    'FechaInicio' => $this->FechaInicio,
                    'FechaFinal' => $this->FechaFinal,
                    'FechaNacimientoDate' => $this->convertirFecha($row[6] ?? null, 'Y-m-d'), // FECHA NACIMIENTO (formato Y-m-d)
                    'PolizaVidaTipoCartera' => $this->PolizaVidaTipoCartera,
                    'FechaOtorgamiento' => $this->convertirFecha($row[9] ?? null),
                    'FechaOtorgamientoDate' => $this->convertirFecha($row[9] ?? null, 'Y-m-d'),
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
