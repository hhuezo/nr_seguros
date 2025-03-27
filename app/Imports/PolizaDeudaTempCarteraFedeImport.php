<?php

namespace App\Imports;

use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

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
         //dd($row[0]);
        if (trim($row[0]) == "DUI o documento de identidad") {
            $this->encabezados = 1;
        }

        if ($this->encabezados == 1 &&  trim($row[0]) != "DUI o documento de identidad") {


            return new PolizaDeudaTempCartera([
                'Dui' => $row[0],
                'PrimerApellido' => $row[1],
                'SegundoApellido' => $row[2],
                'PrimerNombre' => $row[3],
                'FechaNacimiento' => $this->convertirFecha($row[4]),
                'Sexo' => $row[5],
                'NumeroReferencia' => $row[6],
                'FechaOtorgamiento' => $this->convertirFecha($row[7]),
                'MontoOtorgado' => $row[8],
                'SaldoCapital' => $row[9],
                'Intereses' => $row[10],
                'MoraCapital' => $row[11],
                'InteresesMoratorios' => $row[12],
                'InteresesCovid' => $row[13],
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
