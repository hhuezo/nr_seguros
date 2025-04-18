<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\PolizaDeudaTempCarteraFedeComImport;
use App\Imports\PolizaDeudaTempCarteraFedeImport;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\PolizaDeudaTipoCartera;
use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class DeudaCarteraFedeController extends Controller
{
    //
    public function create_pago(Request $request)
    {

        $deuda_tipo_cartera = PolizaDeudaTipoCartera::findOrFail($request->PolizaDeudaTipoCartera);
        $deuda = Deuda::findOrFail($request->Id);

        if ($request->FechaFinal > $deuda->VigenciaHasta) {
            alert()->error('La fecha final no debe ser mayor que la vigencia de la poliza');
            return back();
        }




        $date_submes = Carbon::create($request->Axo, $request->Mes, "01");
        $date = Carbon::create($request->Axo, $request->Mes, "01");
        $date_mes = $date_submes->subMonth();
        $date_anterior = Carbon::create($request->Axo, $request->Mes, "01");
        $date_mes_anterior = $date_anterior->subMonth();

        $requisitos = $deuda->requisitos;
        if ($requisitos->count() == 0) {
            alert()->error('No se han definido requisitos minimos de asegurabilidad');
            $deuda->Configuracion = 0;
            $deuda->update();
            session(['tab' => 3]);
            return redirect('polizas/deuda/' . $deuda->Id);
        }


        $archivo = $request->Archivo;

        $excel = IOFactory::load($archivo);


        // Validar estructura
        $validator = Validator::make([], []); // Creamos un validador vacío

        // 1. Validar número de hojas
        if ($excel->getSheetCount() > 1) {
            $validator->errors()->add('Archivo', 'La cartera solo puede contener un solo libro de Excel (sheet)');
            return back()->withErrors($validator);
        }

        // 2. Validar primera fila
        $firstRow = $excel->getActiveSheet()->rangeToArray('A1:Z1')[0];

        if (!isset($firstRow[0])) {
            $validator->errors()->add('Archivo', 'El archivo está vacío o no tiene el formato esperado');
            return back()->withErrors($validator);
        }

        if (trim($firstRow[0]) !== "DUI o documento de identidad") {
            $validator->errors()->add('Archivo', 'Error de formato del archivo, La primera columna de la primera fila debe ser "DUI o documento de identidad"');
            return back()->withErrors($validator);
        }



        PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->delete();
        Excel::import(new PolizaDeudaTempCarteraFedeImport($date->year, $date->month, $deuda->Id, $request->FechaInicio, $request->FechaFinal, $deuda_tipo_cartera->Id), $archivo);




        //verificando creditos repetidos
        $repetidos = PolizaDeudaTempCartera::where('User', auth()->user()->id)
            ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
            ->groupBy('NumeroReferencia')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $numerosRepetidos = $repetidos->isNotEmpty() ? $repetidos->pluck('NumeroReferencia') : null;

        if ($numerosRepetidos) {
            PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->delete();
            // Convertir la colección a string para mostrarla en el error
            $numerosStr = $numerosRepetidos->implode(', ');

            $validator->errors()->add('Archivo', "Existen números de crédito repetidos: $numerosStr");
            return back()->withErrors($validator);
        }




        //calculando errores de cartera
        $cartera_temp = PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)->get();
        //  dd($cartera_temp);







        foreach ($cartera_temp as $obj) {
            $errores_array = [];
            // 1 error formato fecha nacimiento
            $validador_fecha_nacimiento = $this->validarFormatoFecha($obj->FechaNacimiento);
            if ($validador_fecha_nacimiento == false) {
                //trata de convertir la fecha excel en fecha y luego comprobar nuevamente si la fecha convertida es una fecha.
                $fecha_excel_convertida = $this->convertDate($obj->FechaNacimiento);
                $validador_fecha_nacimiento = $this->validarFormatoFecha($fecha_excel_convertida);

                if ($validador_fecha_nacimiento == false || trim($obj->FechaNacimiento) == "") {
                    $obj->TipoError = 1;
                    $obj->update();

                    array_push($errores_array, 1);
                } else {
                    $obj->FechaNacimiento = $fecha_excel_convertida;
                    $obj->update();
                }
            }



            // 2 error formato de dui
            if ($obj->Dui == null || $obj->Dui == '') {
                $validador_dui = false;
                if ($validador_dui == false) {
                    $obj->TipoError = 8;
                    $obj->update();

                    array_push($errores_array, 8);
                }
            } else {
                $validador_dui = true;
            }

            $obj->update();



            // 4 nombre o apellido
            if (trim($obj->PrimerApellido) == "" || trim($obj->PrimerNombre) == "") {
                $obj->TipoError = 4;
                $obj->update();

                array_push($errores_array, 4);
            }

            //$obj->Errores = $errores_array;

            //5 error formato fecha Otorgamiento
            $validador_fecha_otorgamiento = $this->validarFormatoFecha($obj->FechaOtorgamiento);
            if ($validador_fecha_otorgamiento == false) {
                //trata de convertir la fecha excel en fecha y luego comprobar nuevamente si la fecha convertida es una fecha.
                $fecha_excel_convertida_otorgamiento = $this->convertDate($obj->FechaOtorgamiento);
                //dd($obj->FechaOtorgamiento, $fecha_excel_convertida_otorgamiento);
                $validador_fecha_otorgamiento = $this->validarFormatoFecha($fecha_excel_convertida_otorgamiento);

                if ($validador_fecha_otorgamiento == false || trim($obj->FechaOtorgamiento) == "") {
                    $obj->TipoError = 5;
                    $obj->update();

                    array_push($errores_array, 5);
                } else {
                    //dd($fecha_excel_convertida_otorgamiento, $obj->FechaOtorgamiento);
                    $obj->FechaOtorgamiento = $fecha_excel_convertida_otorgamiento;
                    $obj->update();
                }
            }

            // 7 referencia si va vacia.
            if (trim($obj->NumeroReferencia) == "") {
                $obj->TipoError = 7;
                $obj->update();

                array_push($errores_array, 7);
            }


            // 10 error sexo
            if (trim($obj->Sexo) == "" || ($obj->Sexo != "M" && $obj->Sexo != "F")) {
                $obj->TipoError = 10;
                $obj->update();

                array_push($errores_array, 10);
            }

            $obj->Errores = $errores_array;
        }

        $data_error = $cartera_temp->where('TipoError', '<>', 0);

        //dd($data_error);

        if ($data_error->count() > 0) {
            return view('polizas.deuda.respuesta_poliza_error', compact('data_error', 'deuda', 'credito'));
        }


        //calculando edades y fechas de nacimiento
        PolizaDeudaTempCartera::where('User', auth()->user()->id)
            ->where('PolizaDeuda', $deuda->Id)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                //'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);





        //tasas diferenciadas
        $tasas_diferenciadas = $deuda_tipo_cartera->tasa_diferenciada;

        if ($deuda_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                PolizaDeudaTempCartera::where('User', auth()->user()->id)
                    ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($deuda_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                PolizaDeudaTempCartera::where('User', auth()->user()->id)
                    ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            foreach ($tasas_diferenciadas as $tasa) {
                PolizaDeudaTempCartera::where('User', auth()->user()->id)
                    ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $deuda->Tasa
                    ]);
            }
        }


        $cartera_temp = PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->get();

        foreach ($cartera_temp as $obj) {
            $obj->TotalCredito = $obj->calculoTodalSaldo();
            $obj->update();
        }



        $MontoMaximoIndividual = $deuda_tipo_cartera->MontoMaximoIndividual;
        if (isset($MontoMaximoIndividual) && $MontoMaximoIndividual > 0) {
            $duis = PolizaDeudaTempCartera::selectRaw('Dui')
                ->where('User', auth()->user()->id)
                ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                ->groupBy('Dui')
                ->havingRaw('SUM(TotalCredito) > ?', [$MontoMaximoIndividual])
                ->pluck('Dui'); // Obtiene solo los valores de la columna Dui

            // Realiza el update en los registros con los DUI filtrados
            if ($duis->isNotEmpty()) {
                PolizaDeudaTempCartera::whereIn('Dui', $duis)
                    ->update([
                        'MontoMaximoIndividual' => 1,
                        'NoValido' => 1
                    ]);
            }
        }




        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();


        //        return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
    }

    public function create_pago_recibo(Request $request)
    {


        $deuda_tipo_cartera = PolizaDeudaTipoCartera::findOrFail($request->PolizaDeudaTipoCartera);
        $deuda = Deuda::findOrFail($request->Id);

        //no lleva validacion de fecha por ser complementarios


        $requisitos = $deuda->requisitos;
        if ($requisitos->count() == 0) {
            alert()->error('No se han definido requisitos minimos de asegurabilidad');
            $deuda->Configuracion = 0;
            $deuda->update();
            session(['tab' => 3]);
            return redirect('polizas/deuda/' . $deuda->Id);
        }



        $archivo = $request->Archivo;

        $excel = IOFactory::load($archivo);

        // Validar estructura
        $validator = Validator::make([], []); // Creamos un validador vacío

        // 1. Validar número de hojas
        if ($excel->getSheetCount() > 1) {
            $validator->errors()->add('Archivo', 'La cartera solo puede contener un solo libro de Excel (sheet)');
            return back()->withErrors($validator);
        }

        // 2. Validar primera fila
        $firstRow = $excel->getActiveSheet()->rangeToArray('A1:Z1')[0];

        if (!isset($firstRow[0])) {
            $validator->errors()->add('Archivo', 'El archivo está vacío o no tiene el formato esperado');
            return back()->withErrors($validator);
        }

        if (trim($firstRow[0]) !== "NIT") {
            $validator->errors()->add('Archivo', 'Error de formato del archivo, La primera columna de la primera fila debe ser "NIT"');
            return back()->withErrors($validator);
        }

        if (!isset($firstRow[1])) {
            $validator->errors()->add('Archivo', 'Error de formato del archivo, El archivo no contiene la columna DUI');
            return back()->withErrors($validator);
        }
        try {

            PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->delete();

            Excel::import(new PolizaDeudaTempCarteraFedeComImport($deuda->Id, $request->FechaInicio, $request->FechaFinal, $deuda_tipo_cartera->Id), $archivo);
        } catch (Throwable $e) {
            // Filtramos solo nuestros errores de validación
            if (strpos($e->getMessage(), 'VALIDATION_ERROR:') === 0) {
                return back()->with('error', str_replace('VALIDATION_ERROR: ', '', $e->getMessage()));
            }

            // Otros errores
            return back()->with('error', 'Ocurrió un error al procesar el archivo');
        }




        //calculando errores de cartera
        $cartera_temp = PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('LineaCredito', '=', $deuda_tipo_cartera->Id)->get();





        foreach ($cartera_temp as $obj) {
            $errores_array = [];
            // 1 error formato fecha nacimiento
            $validador_fecha_nacimiento = $this->validarFormatoFecha($obj->FechaNacimiento);
            if ($validador_fecha_nacimiento == false) {
                //trata de convertir la fecha excel en fecha y luego comprobar nuevamente si la fecha convertida es una fecha.
                $fecha_excel_convertida = $this->convertDate($obj->FechaNacimiento);
                $validador_fecha_nacimiento = $this->validarFormatoFecha($fecha_excel_convertida);

                if ($validador_fecha_nacimiento == false || trim($obj->FechaNacimiento) == "") {
                    $obj->TipoError = 1;
                    $obj->update();

                    array_push($errores_array, 1);
                } else {
                    $obj->FechaNacimiento = $fecha_excel_convertida;
                    $obj->update();
                }
            }



            // 2 error formato de dui
            if ($obj->Dui == null || $obj->Dui == '') {
                $validador_dui = false;
                if ($validador_dui == false) {
                    $obj->TipoError = 8;
                    $obj->update();

                    array_push($errores_array, 8);
                }
            } else {
                $validador_dui = true;
            }

            $obj->update();



            // 4 nombre o apellido
            if (trim($obj->PrimerApellido) == "" || trim($obj->PrimerNombre) == "") {
                $obj->TipoError = 4;
                $obj->update();

                array_push($errores_array, 4);
            }

            //$obj->Errores = $errores_array;

            //5 error formato fecha Otorgamiento
            $validador_fecha_otorgamiento = $this->validarFormatoFecha($obj->FechaOtorgamiento);
            if ($validador_fecha_otorgamiento == false) {
                //trata de convertir la fecha excel en fecha y luego comprobar nuevamente si la fecha convertida es una fecha.
                $fecha_excel_convertida_otorgamiento = $this->convertDate($obj->FechaOtorgamiento);
                //dd($obj->FechaOtorgamiento, $fecha_excel_convertida_otorgamiento);
                $validador_fecha_otorgamiento = $this->validarFormatoFecha($fecha_excel_convertida_otorgamiento);

                if ($validador_fecha_otorgamiento == false || trim($obj->FechaOtorgamiento) == "") {
                    $obj->TipoError = 5;
                    $obj->update();

                    array_push($errores_array, 5);
                } else {
                    //dd($fecha_excel_convertida_otorgamiento, $obj->FechaOtorgamiento);
                    $obj->FechaOtorgamiento = $fecha_excel_convertida_otorgamiento;
                    $obj->update();
                }
            }


            // 7 referencia si va vacia.
            if (trim($obj->NumeroReferencia) == "") {
                $obj->TipoError = 7;
                $obj->update();

                array_push($errores_array, 7);
            }


            // 10 error sexo
            if (trim($obj->Sexo) == "" || ($obj->Sexo != "M" && $obj->Sexo != "F")) {
                $obj->TipoError = 10;
                $obj->update();

                array_push($errores_array, 10);
            }

            $obj->Errores = $errores_array;
        }



        $data_error = $cartera_temp->where('TipoError', '<>', 0);

        if ($data_error->count() > 0) {
            return view('polizas.deuda.respuesta_poliza_error', compact('data_error', 'deuda', 'credito'));
        }

        //calculando edades y fechas de nacimiento
        PolizaDeudaTempCartera::where('User', auth()->user()->id)
            ->where('PolizaDeuda', $deuda->Id)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                //'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);


        //tasas diferenciadas
        $tasas_diferenciadas = $deuda_tipo_cartera->tasa_diferenciada;

        if ($deuda_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                PolizaDeudaTempCartera::where('User', auth()->user()->id)
                    ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($deuda_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                PolizaDeudaTempCartera::where('User', auth()->user()->id)
                    ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            foreach ($tasas_diferenciadas as $tasa) {
                PolizaDeudaTempCartera::where('User', auth()->user()->id)
                    ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $deuda->Tasa
                    ]);
            }
        }


        $cartera_temp = PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->get();

        foreach ($cartera_temp as $obj) {
            $obj->TotalCredito = $obj->calculoTodalSaldo();
            $obj->update();
        }

        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();


        //        return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
    }


    public function validarFormatoFecha($data)
    {
        try {
            // Intenta crear un objeto Carbon a partir de la cadena de fecha
            $fechaCarbon = Carbon::createFromFormat('d/m/Y', $data);

            // Comprueba si la cadena de fecha tiene el formato correcto
            return $fechaCarbon && $fechaCarbon->format('d/m/Y') === $data;
        } catch (Exception $e) {
            return false;
        }
    }


    public function convertDate($dateValue)
    {
        try {
            // Si el valor es un número, asume que es una fecha de Excel y conviértelo
            if (is_numeric($dateValue)) {
                $unixDate = (intval($dateValue) - 25569) * 86400;
                return gmdate("d/m/Y", $unixDate);
            }

            // Si el valor es una cadena en formato d/m/Y, conviértelo al formato d/m/Y
            if (Carbon::hasFormat($dateValue, 'd/m/Y')) {
                $fechaCarbon = Carbon::createFromFormat('d/m/Y', $dateValue);
                return $fechaCarbon->format('d/m/Y');
            }

            // Si el valor es una cadena en formato Y/m/d, conviértelo al formato d/m/Y
            if (Carbon::hasFormat($dateValue, 'Y/m/d')) {
                $fechaCarbon = Carbon::createFromFormat('Y/m/d', $dateValue);
                return $fechaCarbon->format('d/m/Y');
            }

            // Si no coincide con ninguno de los formatos, devolver false
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
