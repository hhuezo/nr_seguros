<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\VidaCarteraTempFedeImport;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaTipoCartera;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VidaFedeController extends Controller
{
    //
    public function create_pago(Request $request)
    {

        // dd('holi');
        $request->validate([
            'Axo' => 'required|integer',
            'Mes' => 'required|integer|between:1,12',
            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'PolizaVidaTipoCartera' => 'required|integer',
        ], [
            'Axo.required' => 'El campo Año es obligatorio.',
            'Axo.integer' => 'El campo Año debe ser un número entero.',
            'Axo.min' => 'El campo Año debe ser mayor o igual a 2022.',
            'Axo.max' => 'El campo Año no puede ser mayor al año actual.',
            'Mes.required' => 'El campo Mes es obligatorio.',
            'Mes.integer' => 'El campo Mes debe ser un número entero.',
            'Mes.between' => 'El campo Mes debe estar entre 1 y 12.',
            'FechaInicio.required' => 'El campo Fecha de inicio es obligatorio.',
            'FechaInicio.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'FechaFinal.required' => 'El campo Fecha final es obligatorio.',
            'FechaFinal.date' => 'El campo Fecha final debe ser una fecha válida.',
            'FechaFinal.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'Archivo.required' => 'El campo Archivo es obligatorio.',
            'Archivo.file' => 'El campo Archivo debe ser un archivo válido.',
            'Archivo.mimes' => 'El archivo debe ser de tipo CSV, XLSX o XLS.',
            'Archivo.max' => 'El archivo no debe superar los 2MB.',
            'PolizaVidaTipoCartera.required' => 'El campo tipo cartera es obligatorio.',
            'PolizaVidaTipoCartera.integer' => 'El campo tipo cartera no válido.',
        ]);

        // dd('holi');

        $id = $request->Id;

        $poliza_vida = Vida::findOrFail($id);

        $archivo = $request->Archivo;

        $excel = IOFactory::load($archivo);

        // Verifica si hay al menos dos hojas
        $sheetsCount = $excel->getSheetCount();

        if ($sheetsCount > 1) {
            return redirect('polizas/vida/' . $id . '?tab=2')
                ->withErrors(['excel_file' => 'La cartera solo puede contener un solo libro de Excel']);
        }


        // Crear validador vacío
        $validator = Validator::make([], []);

        // 2. Validar primera fila
        $expectedColumns = [
            "Tipo de documento",
            "DUI o documento de identidad",
            "Primer Apellido",
            "Segundo Apellido",
            "Nombres",
            "Nacionalidad",
            "Fecha de Nacimiento",
            "Género",
            "Nro. de Préstamo",
            "Fecha de otorgamiento",
            "Suma asegurada",
            "Extra Prima",
            "TARIFA",
        ];

        $firstRow = $excel->getActiveSheet()->rangeToArray('A1:Z1')[0];

        // Validar que no esté vacío
        if (empty(array_filter($firstRow))) {
            $validator->errors()->add('Archivo', 'El archivo está vacío o no tiene el formato esperado');
            return back()->withErrors($validator);
        }

        // Normalizar (trim) y pasar a minúsculas para ignorar mayúsculas
        $firstRow = array_map(fn($v) => mb_strtolower(trim($v)), $firstRow);
        $expectedColumnsLower = array_map(fn($v) => mb_strtolower($v), $expectedColumns);

        // Función para convertir índice a letra (0 => A, 1 => B, etc.)
        function columnLetter($index)
        {
            $letter = '';
            while ($index >= 0) {
                $letter = chr($index % 26 + 65) . $letter;
                $index = floor($index / 26) - 1;
            }
            return $letter;
        }

        // Validar cantidad de columnas
        if (count($firstRow) < count($expectedColumnsLower)) {
            $validator->errors()->add('Archivo', 'Error de formato: faltan columnas en la primera fila');
            return back()->withErrors($validator);
        }

        // Validar que todas las columnas sean iguales y en el mismo orden
        foreach ($expectedColumnsLower as $index => $expectedColumn) {
            if (!isset($firstRow[$index]) || $firstRow[$index] !== $expectedColumn) {
                $validator->errors()->add(
                    'Archivo',
                    "Error de formato: la columna " . columnLetter($index) . " debe ser '{$expectedColumns[$index]}'"
                );
                return back()->withErrors($validator);
            }
        }

        //borrar datos de tabla temporal
        VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->delete();

        Excel::import(new VidaCarteraTempFedeImport($request->Axo, $request->Mes, $id, $request->FechaInicio, $request->FechaFinal, $request->PolizaVidaTipoCartera), $archivo);



        //verificando creditos repetidos
        $repetidos = VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)
            ->groupBy('NumeroReferencia')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $numerosRepetidos = $repetidos->isNotEmpty() ? $repetidos->pluck('NumeroReferencia') : null;

        if ($numerosRepetidos) {
            VidaCarteraTemp::where('User', auth()->user()->id)
                ->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)
                ->delete();

            $numerosStr = $numerosRepetidos->implode(', ');

            return back()
                ->withErrors(['Archivo' => "Existen números de crédito repetidos: $numerosStr"]);
        }


        //calculando edades y fechas de nacimiento
        VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVida', $poliza_vida->Id)
            ->update([
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);

        //calculando errores de cartera
        $cartera_temp = VidaCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->get();

        //dd($cartera_temp->take(10));


        //tipo cobro 2 suma abierta
        if ($poliza_vida->TipoCobro == 2) {
            //tipo tarifa 1 suma uniforme
            if ($poliza_vida->TipoTarifa == 1) {
                $montos = [$poliza_vida->SumaAsegurada];
            }
            //tipo tarifa 2 multi categoria
            else if ($poliza_vida->TipoTarifa == 2) {
                $montos = explode(',', $poliza_vida->Multitarifa);
            }
        } else if ($poliza_vida->TipoCobro == 1) {
            $montos = [$poliza_vida->SumaMinima, $poliza_vida->SumaMaxima];
        }



        // dd($montos,$poliza_vida->TipoTarifa);

        foreach ($cartera_temp as $obj) {
            $errores_array = [];

            if ($obj->FechaNacimientoDate == null) {
                $obj->TipoError = 1;
                $obj->update();
                array_push($errores_array, 1);
            }


            if ($obj->FechaOtorgamientoDate == null) {
                $obj->TipoError = 2;
                $obj->update();
                array_push($errores_array, 1);
            }

            if ($request->validacion_dui == 'on') {
                $validador_dui = true;
            } else {
                // Validar si la nacionalidad está vacía
                if (empty($obj->Nacionalidad)) {
                    $obj->TipoError = 3;
                    $obj->update();
                    $errores_array[] = 3; // Agregar error al array
                }
                // Validar si la nacionalidad es SAL (El Salvador)
                else if (strtolower(trim($obj->Nacionalidad)) == 'el sal') {
                    $validador_dui = $this->validarDocumento($obj->Dui, "dui");
                    if (!$validador_dui) {
                        $obj->TipoError = 4;
                        $obj->update();
                        $errores_array[] = 4; // Agregar error al array
                    }
                }
                // // Validar si el pasaporte está vacío para nacionalidades no SAL
                // else if (empty($obj->Pasaporte)) {
                //     $validador_dui = false;
                //     $obj->TipoError = 5;
                //     $obj->update();
                //     $errores_array[] = 5; // Agregar error al array
                // }
                else {
                    $validador_dui = true;
                }
            }





            // 4 nombre o apellido
            if (trim($obj->PrimerApellido) == "" || trim($obj->PrimerNombre) == "") {
                $obj->TipoError = 6;
                $obj->update();

                array_push($errores_array, 6);
            }


            // 7 referencia si va vacia.
            if (trim($obj->NumeroReferencia) == "") {
                $obj->TipoError = 7;
                $obj->update();

                array_push($errores_array, 7);
            }


            // 10 error sexo
            if (empty(trim($obj->Sexo)) || !in_array($obj->Sexo, ['M', 'F'])) {
                $obj->TipoError = 8;
                $obj->update();
                $errores_array[] = 8; // Agregar error al array
            }

            //11 error por edad de terminacion
            if (trim($obj->Edad) > $poliza_vida->EdadTerminacion) {
                $obj->TipoError = 9;
                $obj->update();

                array_push($errores_array, 9);
            }



            //validar cantidad asegurada o multi categoria error 10

            // if (!in_array($obj->SumaAsegurada, $montos)) {
            //     dd("");
            //     $obj->TipoError = 10;
            //     $obj->update();

            //     array_push($errores_array, 10);
            // }


            // 11 error nombres o apellidos con caracteres inválidos
            $regex = '/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s\.\'\-]+$/u'; // letras, espacios, punto, apóstrofe y guion
            $campos = [
                $obj->PrimerNombre,
                $obj->SegundoNombre,
                $obj->PrimerApellido,
                $obj->SegundoApellido,
                $obj->ApellidoCasada
            ];

            foreach ($campos as $valor) {
                if ($valor && !preg_match($regex, $valor)) {
                    $obj->TipoError = 11;
                    $obj->update(); // guardamos inmediatamente
                    array_push($errores_array, 11);
                    break; // no necesitamos seguir revisando otros campos
                }
            }

            //error 12 tipo de cobro


            if ($poliza_vida->TipoCobro == 2) {
                //por credito
                if ($poliza_vida->TipoTarifa == 1) {
                    //tarifa uniforme
                    if ($obj->SumaAsegurada != $poliza_vida->SumaAsegurada) {
                        $obj->TipoError = 12;
                        $obj->update();

                        array_push($errores_array, 12);
                    }
                } else {
                    //multitarifa de la poliza
                    $multitarifas = array_map('intval', explode(",", $poliza_vida->Multitarifa));
                    if (!in_array($obj->SumaAsegurada, $multitarifas)) {
                        $obj->TipoError = 13;
                        $obj->update();

                        array_push($errores_array, 13);
                    }
                }
            } else if ($poliza_vida->TipoCobro == 1) {


                //suma abierta

                $min = $poliza_vida->SumaMinima;
                $max = $poliza_vida->SumaMaxima;

                if ($obj->SumaAsegurada < $min ||  $obj->SumaAsegurada > $max) {
                    $obj->TipoError = 14;
                    $obj->update();

                    array_push($errores_array, 14);
                }


                /*$sumasPorCliente = [];
                foreach ($cartera_temp as $obj1) {
                    if (!isset($sumasPorCliente[$obj1->Dui])) {
                        $sumasPorCliente[$obj1->Dui] = 0;
                    }
                    $sumasPorCliente[$obj1->Dui] += $obj1->SumaAsegurada;
                }

                foreach ($sumasPorCliente as $cliente => $sumaTotal) {


                    if ($sumaTotal < $min ||  $sumaTotal > $max) {
                        $obj->TipoError = 14;
                        $obj->update();

                        array_push($errores_array, 14);
                    }
                }*/
            }


            $obj->Errores = $errores_array;
        }


        $data_error = $cartera_temp->where('TipoError', '<>', 0);

        if ($data_error->count() > 0) {
            return view('polizas.vida.respuesta_poliza_error', compact('data_error', 'poliza_vida'));
        }


        $temp_data_fisrt = VidaCarteraTemp::where('PolizaVida', $id)->where('User', auth()->user()->id)->where('PolizaVidaTipoCartera', '=', $request->PolizaVidaTipoCartera)->first();
        // dd($temp_data_fisrt);
        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }






        //tasa diferenciada
        $vida_tipo_cartera = VidaTipoCartera::findOrFail($request->PolizaVidaTipoCartera);

        $tasas_diferenciadas = $vida_tipo_cartera->tasa_diferenciada;

        if ($vida_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($vida_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->whereBetween('SumaAsegurada', [$tasa->MontoDesde, $tasa->MontoHasta])
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $poliza_vida->Tasa
                    ]);
            }
        }

        return back();
    }

    public function validarDocumento($documento, $tipo)
    {
        if ($tipo == "dui") {
            // Define las reglas de validación para el formato 000000000
            $reglaFormato = '/^\d{9}$/';

            return preg_match($reglaFormato, $documento) === 1;
        } else if ($tipo == "nit") {
            // Define las reglas de validación para el formato 000000000
            $reglaFormato = '/^\d{14}$/';

            return preg_match($reglaFormato, $documento) === 1;
        }
    }
}
