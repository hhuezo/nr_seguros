<?php

namespace App\Imports;

use App\Models\polizas\DesempleoCarteraTemp;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class DesempleoCarteraTempImport implements ToModel, SkipsEmptyRows
{

    private $Axo;
    private $Mes;
    private $Poliza;
    private $FechaInicio;
    private $FechaFinal;
    private $encabezados = 0;


    public function __construct($Axo, $Mes, $Poliza, $FechaInicio, $FechaFinal)
    {
        $this->Axo = $Axo;
        $this->Mes = $Mes;
        $this->Poliza = $Poliza;
        $this->FechaInicio = $FechaInicio;
        $this->FechaFinal = $FechaFinal;
    }

    public function model(array $row)
    {
        // Saltar la fila de encabezados
        if (trim($row[0]) == "NIT" && trim($row[1]) == "DUI") {
            $this->encabezados = 1;
            return null;
        }

        // Procesar solo las filas de datos
        if ($this->encabezados == 1 && (trim($row[0]) != "NIT" && trim($row[1]) != "DUI")) {

            // Verificar que al menos uno de los dos campos (NIT o DUI) tenga datos
            if (!empty(trim($row[0])) || !empty(trim($row[1]))) {
                return new DesempleoCarteraTemp([
                    'PolizaDesempleo' => $this->Poliza,
                    'Nit' => $row[0] ?? null, // NIT
                    'Dui' => $row[1] ?? null, // DUI
                    'Pasaporte' => $row[2] ?? null, // PASAPORTE O CARNET DE RESIDENTE ASEGURADO
                    'Nacionalidad' => $row[3] ?? null, // NACIONALIDAD
                    'FechaNacimiento' => $this->convertirFecha($row[4] ?? null), // FECHA NACIMIENTO
                    'TipoPersona' => $row[5] ?? null, // TIPO DE PERSONA
                    'PrimerApellido' => $row[6] ?? null, // PRIMER APELLIDO
                    'SegundoApellido' => $row[7] ?? null, // SEGUNDO APELLIDO
                    'ApellidoCasada' => $row[8] ?? null, // APELLIDO CASADA
                    'PrimerNombre' => $row[9] ?? null, // PRIMER NOMBRE
                    'SegundoNombre' => $row[10] ?? null, // SEGUNDO NOMBRE
                    'NombreSociedad' => $row[11] ?? null, // NOMBRE SOCIEDAD
                    'Sexo' => $row[12] ?? null, // SEXO
                    'FechaOtorgamiento' => $this->convertirFecha($row[13] ?? null), // FECHA DE OTORGAMIENTO
                    'FechaVencimiento' => $this->convertirFecha($row[14] ?? null), // FECHA DE VENCIMIENTO
                    'Ocupacion' => $row[15] ?? null, // OCUPACION
                    'NumeroReferencia' => $row[16] ?? null, // No DE REFERENCIA DEL CRÉDITO
                    'MontoOtorgado' => $row[17] ?? null, // MONTO OTORGADO DEL CREDITO
                    'SaldoCapital' => $row[18] ?? null, // SALDO VIGENTE DE CAPITAL
                    'Intereses' => $row[19] ?? null, // INTERESES
                    'InteresesMoratorios' => $row[20] ?? null, // INTERESES MORATORIOS
                    'InteresesCovid' => $row[21] ?? null, // INTERESES COVID
                    'MontoNominal' => $row[22] ?? null, // MONTO NOMINAL
                    'SaldoTotal' => $row[23] ?? null, // SALDO TOTAL
                    'ValorContratado' => $row[24] ?? null, // VALOR CONTRATADO
                    'User' => auth()->id(), // ID del usuario autenticado
                    'Axo' => $this->Axo,
                    'Mes' => $this->Mes,
                    'FechaInicio' => $this->FechaInicio,
                    'FechaFinal' => $this->FechaFinal,
                    'FechaNacimientoDate' => $this->convertirFecha($row[4] ?? null, 'Y-m-d'), // FECHA NACIMIENTO (formato Y-m-d)
                    'FechaOtorgamientoDate' => $this->convertirFecha($row[13] ?? null, 'Y-m-d'), // FECHA DE OTORGAMIENTO (formato Y-m-d)
                    'NoValido' => 0, // Valor por defecto
                    'Excluido' => 0, // Valor por defecto
                    'Rehabilitado' => 0, // Valor por defecto
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
