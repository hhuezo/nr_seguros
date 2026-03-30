<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\DesempleoCarteraCompFedeImport;
use App\Imports\DesempleoCarteraCompImport;
use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCartera;
use App\Models\polizas\DesempleoDetalle;
use App\Models\polizas\DesempleoTipoCartera;
use App\Models\temp\DesempleoCarteraTemp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DesempleoCarteraComController extends Controller
{
    //

    public function subir_cartera_recibo($id){
        $desempleo = Desempleo::find($id);



        $tipos_cartera = DB::table('poliza_desempleo_tasa_diferenciada as pdt')
            ->join('poliza_desempleo_tipo_cartera as pdtc', 'pdt.PolizaDesempleoTipoCartera', '=', 'pdtc.Id')
            ->join('saldos_montos as sm', 'sm.Id', '=', 'pdt.SaldosMontos')
            ->leftJoin(DB::raw('(
                    SELECT DesempleoTipoCartera, SUM(TotalCredito) AS Total, Mes, Axo
                    FROM poliza_desempleo_cartera_temp
                    GROUP BY DesempleoTipoCartera, Mes, Axo
                ) as pdct'), 'pdct.DesempleoTipoCartera', '=', 'pdtc.Id')
            ->select(
                'pdt.PolizaDesempleoTipoCartera',
                DB::raw("
                    CASE pdtc.TipoCalculo
                        WHEN 0 THEN 'No aplica'
                        WHEN 1 THEN 'Fecha'
                        WHEN 2 THEN 'Monto'
                        ELSE 'Desconocido'
                    END as TipoCalculoTexto
                "),
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT(sm.Abreviatura, ' ', sm.Descripcion) ORDER BY sm.Id SEPARATOR ', ') as SaldosMontosTexto"),
                DB::raw('COALESCE(pdct.Total, 0) as Total'),
                'pdct.Mes',
                'pdct.Axo'
            )
            ->where('pdtc.PolizaDesempleo', $id)
            ->groupBy('pdt.PolizaDesempleoTipoCartera', 'pdtc.TipoCalculo', 'pdct.Total', 'pdct.Mes', 'pdct.Axo')
            ->orderBy('pdt.PolizaDesempleoTipoCartera')
            ->get();



        $meses = [
            '',
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        // 👉 Por defecto: del primer día del mes anterior al primer día del mes actual
        $fechaInicio = Carbon::now()->subMonth()->startOfMonth();
        $fechaFinal = Carbon::now()->startOfMonth();
        $axo = $fechaInicio->year;
        $mes = (int) $fechaInicio->month;
        $anioSeleccionado = $fechaInicio->year;


        // ✅ Formato Y-m-d para el Blade
        $fechaInicio = $fechaInicio->format('Y-m-d');
        $fechaFinal = $fechaFinal->format('Y-m-d');

        $ultimo_pago = DesempleoDetalle::where('Desempleo', $id)->orderBy('Id', 'desc')->first();

        if ($ultimo_pago) {
            // 1. Manejo de Fechas (Calendario)
            $fecha_inicial = Carbon::parse($ultimo_pago->FechaFinal);
            $fecha_final = $fecha_inicial->copy()->addMonth();
            // Creamos una fecha temporal usando el Axo y Mes del array para incrementar el periodo
            $periodo = Carbon::createFromDate($ultimo_pago->Axo, $ultimo_pago->Mes, 1)->addMonth();

            // Ahora Axo y Mes siempre serán correctos, incluso pasando de Diciembre a Enero
            $axo = $periodo->year;
            $mes = $periodo->month;

            //Formato final para los inputs de la vista
            $fechaInicio = $fecha_inicial->format('Y-m-d');
            $fechaFinal = $fecha_final->format('Y-m-d');
        }


        // 👉 Rango de años válidos según vigencia
        $vigenciaDesde = Carbon::parse($desempleo->VigenciaDesde);
        $vigenciaHasta = Carbon::parse($desempleo->VigenciaHasta);
        $anios = array_combine(
            $years = range($vigenciaDesde->year, $vigenciaHasta->year),
            $years
        );



        return view('polizas.desempleo.subir_archivos_complementario', compact(
            'desempleo',
            'anios',
            'axo',
            'mes',
            'meses',
            'fechaInicio',
            'fechaFinal',
            'tipos_cartera'
        ));
    }


    public function create_pago(Request $request, $id)
    {

        dd($request->all());

        $cartera_count = DesempleoCartera::where('PolizaDesempleo', $id)->count();

        // if ($cartera_count > 0) {
        //     return back()
        //         ->withErrors([
        //             'Mes' => 'Ya existe una cartera registrada para este mes y año.'
        //         ])
        //         ->withInput();
        // }


        // 🧩 Validar datos básicos del formulario
        $request->validate([

            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ], [

            'FechaInicio.required' => 'El campo Fecha de inicio es obligatorio.',
            'FechaInicio.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'FechaFinal.required' => 'El campo Fecha final es obligatorio.',
            'FechaFinal.date' => 'El campo Fecha final debe ser una fecha válida.',
            'FechaFinal.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'Archivo.required' => 'El campo Archivo es obligatorio.',
            'Archivo.file' => 'El campo Archivo debe ser un archivo válido.',
            'Archivo.mimes' => 'El archivo debe ser de tipo CSV, XLSX o XLS.',
            'Archivo.max' => 'El archivo no debe superar los 2MB.',
        ]);

        //validacion de mes y año
        $tipos_cartera = DB::table('poliza_desempleo_tasa_diferenciada as pdt')
            ->join('poliza_desempleo_tipo_cartera as pdtc', 'pdt.PolizaDesempleoTipoCartera', '=', 'pdtc.Id')
            ->leftJoin('poliza_desempleo_cartera_temp as pdct', 'pdct.DesempleoTipoCartera', '=', 'pdtc.Id')
            ->join('saldos_montos as sm', 'sm.Id', '=', 'pdt.SaldosMontos')
            ->select(
                'pdct.Mes',
                'pdct.Axo'
            )
            ->where('pdtc.PolizaDesempleo', $id)
            ->groupBy('pdt.PolizaDesempleoTipoCartera', 'pdtc.TipoCalculo')
            ->orderBy('pdt.PolizaDesempleoTipoCartera')
            ->get();

        // 🧠 Crear validador manual
        $validator = Validator::make([], []);

        if ($tipos_cartera->count() > 1) {
            foreach ($tipos_cartera as $tipo) {
                // dd($tipo);
                if ($tipo->Mes && $tipo->Axo) {
                    // dd($tipo,$request->Mes, $request->Axo,$request->Axo != $tipo->Axo && $request->Mes != $tipo->Mes || $request->Axo != $tipo->Axo || $request->Mes != $tipo->Mes);
                    if ($request->Axo != $tipo->Axo || $request->Mes != $tipo->Mes) {
                        // dd('holi');
                        $validator->errors()->add('Archivo', 'El mes y año seleccionado no son iguales.');
                        return back()->withErrors($validator);
                    }
                }
            }
        }
        // 🔍 Buscar la póliza
        $desempleo = Desempleo::findOrFail($id);

        // 📂 Cargar el archivo con PhpSpreadsheet
        $archivo = $request->file('Archivo');
        $excel = IOFactory::load($archivo->getPathname());



        // 1️⃣ Validar número de hojas
        if ($excel->getSheetCount() > 1) {
            $validator->errors()->add('Archivo', 'La cartera solo puede contener un solo libro de Excel (sheet).');
            return back()->withErrors($validator);
        }

        // 2️⃣ Validar encabezados esperados
        $expectedColumns = [


            "DUI",
            "PASAPORTE",
            "CARNET RESI",
            "NACIONALIDAD",
            "FECNACIMIENTO",
            "TIPO PERSONA",
            "GENERO",
            "PRIMERAPELLIDO",
            "SEGUNDOAPELLIDO",
            "APELLIDOCASADA",
            "PRIMERNOMBRE",
            "SEGUNDONOMBRE",
            "NOMBRE SOCIEDAD",
            "FECOTORGAMIENTO",
            "FECHA DE VENCIMIENTO",
            "NUMREFERENCIA",
            "SUMA ASEGURADA",
            "SALDO DE CAPITAL",
            "INTERES CORRIENTES",
            "INTERES MORATORIO",
            "INTERES COVID",
            "TARIFA",
            "TIPO DE DEUDA",
            "PORCENTAJE EXTRAPRIMA",
            "MES",
            "AÑO",
        ];

        // Obtener la primera fila (A1:Z1)
        $sheet = $excel->getActiveSheet();
        $firstRow = $sheet->rangeToArray('A1:Z1')[0] ?? [];

        // Validar que la primera fila no esté vacía
        if (empty(array_filter($firstRow))) {
            $validator->errors()->add('Archivo', 'El archivo está vacío o no tiene el formato esperado.');
            return back()->withErrors($validator);
        }

        // Normalizar valores (eliminar espacios y convertir a mayúsculas)
        $firstRow = array_map(fn($v) => strtoupper(trim($v)), $firstRow);

        // Validar cantidad de columnas
        if (count($firstRow) < count($expectedColumns)) {
            $validator->errors()->add('Archivo', 'Error de formato: faltan columnas en la primera fila.');
            return back()->withErrors($validator);
        }

        // Validar nombre y orden de columnas
        foreach ($expectedColumns as $index => $expectedColumn) {
            if (!isset($firstRow[$index]) || $firstRow[$index] !== $expectedColumn) {
                $validator->errors()->add(
                    'Archivo',
                    "Error de formato: la columna " . ($index + 1) . " debe ser '$expectedColumn', se encontró '{$firstRow[$index]}'"
                );
                return back()->withErrors($validator);
            }
        }

        // ✅ Si pasa todas las validaciones

        //borrar datos de tabla temporal
        DesempleoCarteraTemp::where('PolizaDesempleo', $id)->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)->delete();


        //guardando datos de excel en base de datos
        Excel::import(new DesempleoCarteraCompImport($id, $request->FechaInicio, $request->FechaFinal, $request->DesempleoTipoCartera), $archivo);
        $validator = Validator::make([], []); // Creamos un validador vacío



        //calculando errores de cartera
        $cartera_temp = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();


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


            $obj->update();



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

            $obj->Errores = $errores_array;
        }

        $data_error = $cartera_temp->where('TipoError', '<>', 0);


        if ($data_error->count() > 0) {
            return view('polizas.desempleo.respuesta_poliza_error', compact('data_error', 'desempleo'));
        }


        $temp_data_fisrt = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }


        //calculando edades y fechas de nacimiento
        DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);


        $tipos_cartera = DesempleoTipoCartera::where('PolizaDesempleo', $id)->get();

        foreach ($tipos_cartera as $tipo) {


            foreach ($tipo->tasa_diferenciada as $tasa) {


                if ($tipo->TipoCalculo == 1) {
                    // 1️⃣ Un solo update para SaldosMontos, TotalCredito y Tasa
                    //Fecha
                    DesempleoCarteraTemp::where('PolizaDesempleo', $id)
                        ->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)
                        ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                        ->chunk(500, function ($temporales) use ($tasa) {
                            foreach ($temporales as $temp) {
                                $temp->SaldosMontos = $tasa->SaldosMontos;
                                $temp->Tasa = $tasa->Tasa;
                                $temp->TotalCredito = $temp->calculoTodalSaldo($tasa->SaldosMontos);
                                $temp->save();
                            }
                        });
                } else  if ($tipo->TipoCalculo == 2) {
                    //Monto

                    $temporal =  DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                        // ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                        ->get();

                    foreach ($temporal as $data) {
                        $suma = $data->calculoTodalSaldo($tasa->SaldosMontos);
                        $data->TotalCredito = $suma;
                        $data->save();
                    }

                    DesempleoCarteraTemp::where('PolizaDesempleo', $id)
                        ->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)
                        ->whereBetween('TotalCredito', [$tasa->MontoDesde, $tasa->MontoHasta])
                        ->chunk(500, function ($temporales) use ($tasa) {
                            foreach ($temporales as $temp) {
                                $temp->SaldosMontos = $tasa->SaldosMontos;
                                $temp->Tasa = $tasa->Tasa;
                                $temp->save();
                            }
                        });
                } else {
                    //NO Aplica

                    $temporal = DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                        ->get();

                    foreach ($temporal as $temp) {
                        $saldo = $temp->calculoTodalSaldo($tasa->SaldosMontos);
                        $temp->TotalCredito = $saldo;
                        $temp->SaldosMontos = $tasa->SaldosMontos;
                        $temp->Tasa = $tasa->Tasa;
                        $temp->save();
                    }
                }
            }
        }

        return back()->with('success', 'Cartera agregada correctamente');

        // return view('polizas.desempleo.respuesta_poliza', compact('total', 'desempleo', 'poliza_edad_maxima', 'registros_rehabilitados', 'registros_eliminados', 'nuevos_registros', 'axoActual', 'mesActual'));
        // } catch (\Exception $e) {
        //     // Capturar cualquier excepción y retornar un mensaje de error
        //     return back()->with('error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage());
        // }
    }

    public function create_pago_fedecredito(Request $request, $id)
    {
        // dd($request->all());
        $cartera_count = DesempleoCartera::where('PolizaDesempleo', $id)->where('Mes', $request->Mes)->where('Axo', $request->Axo)->count();

        if ($cartera_count > 0) {
            return back()
                ->withErrors([
                    'Mes' => 'Ya existe una cartera registrada para este mes y año.'
                ])
                ->withInput();
        }

        //Validar datos básicos del formulario
        $request->validate([

            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ], [

            'FechaInicio.required' => 'El campo Fecha de inicio es obligatorio.',
            'FechaInicio.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'FechaFinal.required' => 'El campo Fecha final es obligatorio.',
            'FechaFinal.date' => 'El campo Fecha final debe ser una fecha válida.',
            'FechaFinal.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'Archivo.required' => 'El campo Archivo es obligatorio.',
            'Archivo.file' => 'El campo Archivo debe ser un archivo válido.',
            'Archivo.mimes' => 'El archivo debe ser de tipo CSV, XLSX o XLS.',
            'Archivo.max' => 'El archivo no debe superar los 2MB.',
        ]);
        // 🧠 Crear validador manual
        $validator = Validator::make([], []);

        $tipos_cartera = DB::table('poliza_desempleo_tasa_diferenciada as pdt')
            ->join('poliza_desempleo_tipo_cartera as pdtc', 'pdt.PolizaDesempleoTipoCartera', '=', 'pdtc.Id')
            ->leftJoin('poliza_desempleo_cartera_temp as pdct', 'pdct.DesempleoTipoCartera', '=', 'pdtc.Id')
            ->join('saldos_montos as sm', 'sm.Id', '=', 'pdt.SaldosMontos')
            ->select(
                'pdct.Mes',
                'pdct.Axo'
            )
            ->where('pdtc.PolizaDesempleo', $id)
            ->groupBy('pdt.PolizaDesempleoTipoCartera', 'pdtc.TipoCalculo')
            ->orderBy('pdt.PolizaDesempleoTipoCartera')
            ->get();
        if ($tipos_cartera->count() > 1) {
            foreach ($tipos_cartera as $tipo) {
                // dd($tipo);
                if ($tipo->Mes && $tipo->Axo) {
                    // dd($tipo,$request->Mes, $request->Axo,$request->Axo != $tipo->Axo && $request->Mes != $tipo->Mes || $request->Axo != $tipo->Axo || $request->Mes != $tipo->Mes);
                    if ($request->Axo != $tipo->Axo || $request->Mes != $tipo->Mes) {
                        // dd('holi');
                        $validator->errors()->add('Archivo', 'El mes y año seleccionado no son iguales.');
                        return back()->withErrors($validator);
                    }
                }
            }
        }


        // 🔍 Buscar la póliza
        $desempleo = Desempleo::findOrFail($id);

        // 📂 Cargar el archivo con PhpSpreadsheet
        $archivo = $request->file('Archivo');
        $excel = IOFactory::load($archivo->getPathname());



        // 1️⃣ Validar número de hojas
        if ($excel->getSheetCount() > 1) {
            $validator->errors()->add('Archivo', 'La cartera solo puede contener un solo libro de Excel (sheet).');
            return back()->withErrors($validator);
        }

        // 2️⃣ Validar encabezados esperados (Fedecrédito desempleo: 21 columnas)
        $expectedColumns = [
            "Tipo de documento",
            "DUI o documento de identidad",
            "Primer Apellido",
            "Segundo Apellido",
            "Apellido de casada",
            "primer nombre",
            "segundo nombre",
            "tercer nombre",
            "Nacionalidad",
            "Fecha de Nacimiento",
            "Género",
            "Nro. de Préstamo",
            "Fecha de otorgamiento",
            "Monto original de desembolso",
            "Saldo de deuda capital actual",
            "Saldo intereses corrientes",
            "Mora capital",
            "Saldo intereses por mora",
            "Intereses Covid",
            "Extra Prima",
            "TARIFA",
            "MES",
            "AÑO",
        ];

        // Obtener la primera fila (A1:Z1)
        $sheet = $excel->getActiveSheet();
        $firstRow = $sheet->rangeToArray('A1:Z1')[0] ?? [];

        // Validar que la primera fila no esté vacía
        if (empty(array_filter($firstRow))) {
            $validator->errors()->add('Archivo', 'El archivo está vacío o no tiene el formato esperado.');
            return back()->withErrors($validator);
        }

        // Normalizar (trim) y minúsculas para comparación case-insensitive
        $firstRow = array_map(fn($v) => mb_strtolower(trim($v ?? ''), 'UTF-8'), $firstRow);
        $expectedColumnsLower = array_map(fn($v) => mb_strtolower($v, 'UTF-8'), $expectedColumns);

        // Validar cantidad de columnas
        if (count($firstRow) < count($expectedColumnsLower)) {
            $validator->errors()->add('Archivo', 'Error de formato: faltan columnas en la primera fila.');
            return back()->withErrors($validator);
        }

        // Validar nombre y orden de columnas
        foreach ($expectedColumnsLower as $index => $expectedColumn) {
            if (!isset($firstRow[$index]) || $firstRow[$index] !== $expectedColumn) {
                $validator->errors()->add(
                    'Archivo',
                    "Error de formato: la columna " . ($index + 1) . " debe ser '{$expectedColumns[$index]}', se encontró '{$firstRow[$index]}'"
                );
                return back()->withErrors($validator);
            }
        }

        // ✅ Si pasa todas las validaciones

        //borrar datos de tabla temporal
        DesempleoCarteraTemp::where('PolizaDesempleo', $id)->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)->delete();

        //guardando datos de excel en base de datos
        Excel::import(new DesempleoCarteraCompFedeImport($id, $request->FechaInicio, $request->FechaFinal, $request->DesempleoTipoCartera), $archivo);
        $validator = Validator::make([], []); // Creamos un validador vacío

        //calculando errores de cartera
        $cartera_temp = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)->get();
       // dd($cartera_temp);




        //calcular la tasa diferenciada

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

            $obj->Errores = $errores_array;
        }

        $data_error = $cartera_temp->where('TipoError', '<>', 0);


        if ($data_error->count() > 0) {
            return view('polizas.desempleo.respuesta_poliza_error', compact('data_error', 'desempleo'));
        }


        $temp_data_fisrt = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)->first();
       // dd($temp_data_fisrt);

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }


        //calculando edades y fechas de nacimiento
        DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);


        $tipos_cartera = DesempleoTipoCartera::where('PolizaDesempleo', $id)->get();

        foreach ($tipos_cartera as $tipo) {


            foreach ($tipo->tasa_diferenciada as $tasa) {


                if ($tipo->TipoCalculo == 1) {

                    // 1️⃣ Un solo update para SaldosMontos, TotalCredito y Tasa
                    //Fecha
                    DesempleoCarteraTemp::where('PolizaDesempleo', $id)
                        ->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)
                        ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                        ->chunk(500, function ($temporales) use ($tasa) {
                            foreach ($temporales as $temp) {
                                $temp->SaldosMontos = $tasa->SaldosMontos;
                                $temp->Tasa = $tasa->Tasa;
                                $temp->TotalCredito = $temp->calculoTodalSaldo($tasa->SaldosMontos);
                                $temp->save();
                            }
                        });
                } else  if ($tipo->TipoCalculo == 2) {
                    //Monto

                    $temporal =  DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                        // ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                        ->get();

                    foreach ($temporal as $data) {
                        $suma = $data->calculoTodalSaldo($tasa->SaldosMontos);
                        $data->TotalCredito = $suma;
                        $data->save();
                    }

                    DesempleoCarteraTemp::where('PolizaDesempleo', $id)
                        ->where('DesempleoTipoCartera', $request->DesempleoTipoCartera)
                        ->whereBetween('TotalCredito', [$tasa->MontoDesde, $tasa->MontoHasta])
                        ->chunk(500, function ($temporales) use ($tasa) {
                            foreach ($temporales as $temp) {
                                $temp->SaldosMontos = $tasa->SaldosMontos;
                                $temp->Tasa = $tasa->Tasa;
                                $temp->save();
                            }
                        });
                } else {
                    //NO Aplica

                    $temporal = DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                        ->get();

                    foreach ($temporal as $temp) {
                        $saldo = $temp->calculoTodalSaldo($tasa->SaldosMontos);
                        $temp->TotalCredito = $saldo;
                        $temp->SaldosMontos = $tasa->SaldosMontos;
                        $temp->Tasa = $tasa->Tasa;
                        $temp->save();
                    }
                }
            }
        }

        return back()->with('success', 'Cartera agregada correctamente');

        // return view('polizas.desempleo.respuesta_poliza', compact('total', 'desempleo', 'poliza_edad_maxima', 'registros_rehabilitados', 'registros_eliminados', 'nuevos_registros', 'axoActual', 'mesActual'));
        // } catch (\Exception $e) {
        //     // Capturar cualquier excepción y retornar un mensaje de error
        //     return back()->with('error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage());
        // }
    }


    public function validar_poliza($id)
    {


        $desempleo = Desempleo::findOrFail($id);

        $temp_data_fisrt = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }
        // Actualizar Identificador en tabla temporal
        DB::table('poliza_desempleo_cartera_temp')
            ->update([
                'Identificador' => DB::raw("COALESCE(NULLIF(Dui,''), NULLIF(Pasaporte,''), NULLIF(CarnetResidencia,''))")
            ]);



        $data = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();


        $total = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->sum('SaldoTotal');
        //recibos tabla de configuracion


        $temp = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $poliza_cumulos = DesempleoCarteraTemp:://join('poliza_deuda_creditos as pdc', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'pdc.Id')
            select(
                'poliza_desempleo_cartera_temp.Id',
                'poliza_desempleo_cartera_temp.Dui',
                'poliza_desempleo_cartera_temp.Edad',
                'poliza_desempleo_cartera_temp.PrimerNombre',
                'poliza_desempleo_cartera_temp.SegundoNombre',
                'poliza_desempleo_cartera_temp.PrimerApellido',
                'poliza_desempleo_cartera_temp.SegundoApellido',
                'poliza_desempleo_cartera_temp.ApellidoCasada',
                'poliza_desempleo_cartera_temp.FechaNacimiento',
                'poliza_desempleo_cartera_temp.NumeroReferencia',
                'poliza_desempleo_cartera_temp.NoValido',
                // 'poliza_desempleo_cartera_temp.Perfiles',
                'poliza_desempleo_cartera_temp.Mes',
                'poliza_desempleo_cartera_temp.Axo',
                DB::raw("GROUP_CONCAT(DISTINCT poliza_desempleo_cartera_temp.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                DB::raw('MAX(poliza_desempleo_cartera_temp.EdadDesembloso) as EdadDesembloso'),
                DB::raw('MAX(poliza_desempleo_cartera_temp.FechaOtorgamientoDate) as FechaOtorgamiento'),
                DB::raw('SUM(poliza_desempleo_cartera_temp.TotalCredito) as SaldoCumulo'),
                'poliza_desempleo_cartera_temp.Excluido',
                // 'poliza_desempleo_cartera_temp.OmisionPerfil',
                // "poliza_deuda_temp_cartera.saldo_total",
                // 'pdc.MontoMaximoIndividual as MontoMaximoIndividual'
            )
            ->where('poliza_desempleo_cartera_temp.PolizaDesempleo', $id)
            ->groupBy('poliza_desempleo_cartera_temp.Dui', 'poliza_desempleo_cartera_temp.Mes', 'poliza_desempleo_cartera_temp.Axo')
            ->get();



        return view('polizas.desempleo.respuesta_poliza_recibo', compact(  'meses',
        'desempleo',
        'poliza_cumulos'));
    }

    public function store_poliza(Request $request, $id)
    {



        $tempData = DesempleoCarteraTemp::where('PolizaDesempleo', $id)
            ->where('NoValido', 0)
            ->get();

           // dd($tempData);

        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {
            //try {
            $poliza = new DesempleoCartera();
            $poliza->PolizaDesempleo = $tempRecord->PolizaDesempleo;
            $poliza->Dui = $tempRecord->Dui;
            $poliza->CarnetResidencia = $tempRecord->CarnetResidencia;
            $poliza->Pasaporte = $tempRecord->Pasaporte;
            $poliza->Identificador = $tempRecord->Dui ?: ($tempRecord->Pasaporte ?: ($tempRecord->CarnetResidencia ?: null));

            $poliza->Nacionalidad = $tempRecord->Nacionalidad;
            $poliza->FechaNacimiento = $tempRecord->FechaNacimiento;
            $poliza->TipoPersona = $tempRecord->TipoPersona;
            $poliza->Sexo = $tempRecord->Sexo;
            $poliza->PrimerApellido = $tempRecord->PrimerApellido;
            $poliza->SegundoApellido = $tempRecord->SegundoApellido;
            $poliza->ApellidoCasada = $tempRecord->ApellidoCasada;
            $poliza->PrimerNombre = $tempRecord->PrimerNombre;
            $poliza->SegundoNombre = $tempRecord->SegundoNombre;
            $poliza->NombreSociedad = $tempRecord->NombreSociedad;

            $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento;
            $poliza->FechaVencimiento = $tempRecord->FechaVencimiento;
            $poliza->NumeroReferencia = $tempRecord->NumeroReferencia;
            $poliza->MontoOtorgado = $tempRecord->MontoOtorgado;
            $poliza->SaldoCapital = $tempRecord->SaldoCapital;
            $poliza->Intereses = $tempRecord->Intereses;
            $poliza->MoraCapital = $tempRecord->MoraCapital;
            $poliza->InteresesMoratorios = $tempRecord->InteresesMoratorios;
            $poliza->InteresesCovid = $tempRecord->InteresesCovid;
            $poliza->SaldoTotal = $tempRecord->SaldoTotal;

            $poliza->EdadDesembloso = $tempRecord->EdadDesembloso;
            $poliza->FechaOtorgamientoDate = $tempRecord->FechaOtorgamientoDate;
            $poliza->User = $tempRecord->User;
            $poliza->Axo = $tempRecord->Axo;
            $poliza->Mes = $tempRecord->Mes;
            $poliza->FechaInicio = $tempRecord->FechaInicio;
            $poliza->FechaFinal = $tempRecord->FechaFinal;
            $poliza->TipoError = $tempRecord->TipoError;
            $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate;
            $poliza->Edad = $tempRecord->Edad;

            $poliza->DesempleoTipoCartera = $tempRecord->DesempleoTipoCartera;
            $poliza->NoValido = $tempRecord->NoValido;

            $poliza->Excluido = $tempRecord->Excluido;
            $poliza->Rehabilitado = $tempRecord->Rehabilitado;
            $poliza->EdadRequisito = $tempRecord->EdadRequisito;
            $poliza->SaldosMontos = $tempRecord->SaldosMontos;
            $poliza->TotalCredito = $tempRecord->TotalCredito;
            $poliza->Tasa = $tempRecord->Tasa;
            $poliza->save();
        }



        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/desempleo/' . $id . '?tab=2');
    }


}
