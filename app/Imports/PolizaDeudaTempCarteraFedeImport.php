<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;

class PolizaDeudaTempCarteraFedeImport implements ToModel, /*WithStartRow,*/ SkipsEmptyRows
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


    public function __construct($Axo, $Mes, $PolizaDeuda, $FechaInicio, $FechaFinal, $credito)
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
        if (trim($row[1]) == "DUI o documento de identidad") {
            $this->encabezados = 1;
        }

        if ($this->encabezados == 1 &&  trim($row[1]) != "DUI o documento de identidad") {


            return new PolizaDeudaTempCartera([
                'TipoDocumento' => $row[0],
                'Dui' => $row[1],
                'PrimerApellido' => $row[2],
                'SegundoApellido' => $row[3],
                'PrimerNombre' => $row[4],
                'Nacionalidad' => $row[5],
                'FechaNacimiento' => $this->convertirFecha($row[6]),
                'Sexo' => $row[7],
                'NumeroReferencia' => $row[8],
                'FechaOtorgamiento' => $this->convertirFecha($row[9]),
                'MontoOtorgado' => $row[10],
                'SaldoCapital' => $row[11],
                'Intereses' => $row[12],
                'MoraCapital' => $row[13],
                'InteresesMoratorios' => !empty($row[14]) ? $row[14] : null,
                'InteresesCovid' => $row[15],
                'PorcentajeExtraprima' => $row[16],

                'Tasa' => (isset($row[17]) && trim($row[17]) !== '' && is_numeric($row[17]))
                        ? (float) $row[17] : null, // columna de tarifa

                'User' => auth()->user()->id,
                'Axo' =>  $this->Axo,
                'Mes' =>  $this->Mes,
                'PolizaDeuda' =>  $this->PolizaDeuda,
                'FechaInicio' =>  $this->FechaInicio,
                'FechaFinal' =>  $this->FechaFinal,
                'PolizaDeudaTipoCartera' => $this->credito,
            ]);
        }
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
