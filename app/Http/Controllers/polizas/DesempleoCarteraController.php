<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\DesempleoCarteraTempFedeImport;
use App\Imports\DesempleoCarteraTempImport;
use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCartera;
use App\Models\polizas\DesempleoTasaDiferenciada;
use App\Models\polizas\DesempleoTipoCartera;
use App\Models\temp\DesempleoCarteraTemp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DesempleoCarteraController extends Controller
{
    public function subir_cartera($id)
    {
        $desempleo = Desempleo::find($id);



        $tipos_cartera = DB::table('poliza_desempleo_tasa_diferenciada as pdt')
            ->join('poliza_desempleo_tipo_cartera as pdtc', 'pdt.PolizaDesempleoTipoCartera', '=', 'pdtc.Id')
            ->leftJoin('poliza_desempleo_cartera_temp as pdct', 'pdct.DesempleoTipoCartera', '=', 'pdtc.Id')
            ->join('saldos_montos as sm', 'sm.Id', '=', 'pdt.SaldosMontos')
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
                DB::raw('COALESCE(SUM(pdct.TotalCredito), 0) as Total')
            )
            ->where('pdtc.PolizaDesempleo', $id)
            ->groupBy('pdt.PolizaDesempleoTipoCartera', 'pdtc.TipoCalculo')
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


        // 👉 Rango de años válidos según vigencia
        $vigenciaDesde = Carbon::parse($desempleo->VigenciaDesde);
        $vigenciaHasta = Carbon::parse($desempleo->VigenciaHasta);
        $anios = array_combine(
            $years = range($vigenciaDesde->year, $vigenciaHasta->year),
            $years
        );

        // ✅ Formato Y-m-d para el Blade
        $fechaInicio = $fechaInicio->format('Y-m-d');
        $fechaFinal = $fechaFinal->format('Y-m-d');

        return view('polizas.desempleo.subir_archivos', compact(
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

        // 🧩 Validar datos básicos del formulario
        $request->validate([
            'Axo' => 'required|integer',
            'Mes' => 'required|integer|between:1,12',
            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ], [
            'Axo.required' => 'El campo Año es obligatorio.',
            'Axo.integer' => 'El campo Año debe ser un número entero.',
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
        ]);

        // 🔍 Buscar la póliza
        $desempleo = Desempleo::findOrFail($id);

        // 📂 Cargar el archivo con PhpSpreadsheet
        $archivo = $request->file('Archivo');
        $excel = IOFactory::load($archivo->getPathname());

        // 🧠 Crear validador manual
        $validator = Validator::make([], []);

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
            "MONTO OTORGADO",
            "SALDO DE CAPITAL",
            "INTERES CORRIENTES",
            "INTERES MORATORIO",
            "INTERES COVID",
            "TARIFA",
            "TIPO DE DEUDA",
            "PORCENTAJE EXTRAPRIMA",
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
        DesempleoCarteraTemp::where('PolizaDesempleo', $id)->delete();

        //guardando datos de excel en base de datos
        Excel::import(new DesempleoCarteraTempImport($request->Axo, $request->Mes, $id, $request->FechaInicio, $request->FechaFinal, $request->DesempleoTipoCartera), $archivo);
        $validator = Validator::make([], []); // Creamos un validador vacío

        if ($request->validacion_credito != 'on') {
            $repetidos = DesempleoCarteraTemp::where('User', auth()->user()->id)
                //->where('PolizaDeuda', $request->Id)
                ->groupBy('NumeroReferencia')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            $numerosRepetidos = $repetidos->isNotEmpty() ? $repetidos->pluck('NumeroReferencia') : null;

            if ($numerosRepetidos) {
                DesempleoCarteraTemp::delete();
                // Convertir la colección a string para mostrarla en el error
                $numerosStr = $numerosRepetidos->implode(', ');

                $validator->errors()->add('Archivo', "Existen números de crédito repetidos: $numerosStr");
                return back()->withErrors($validator);
            }
        }

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
                else if (strtolower(trim($obj->Nacionalidad)) == 'sal') {
                    $validador_dui = $this->validarDocumento($obj->Dui, "dui");
                    if (!$validador_dui) {
                        $obj->TipoError = 4;
                        $obj->update();
                        $errores_array[] = 4; // Agregar error al array
                    }
                }
                // Validar si el pasaporte está vacío para nacionalidades no SAL
                else if (empty($obj->Pasaporte)) {
                    $validador_dui = false;
                    $obj->TipoError = 5;
                    $obj->update();
                    $errores_array[] = 5; // Agregar error al array
                } else {
                    $validador_dui = true;
                }
            }

            $obj->SaldoTotal = $obj->calculoTodalSaldo();
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


        $desempleo_cartera_temporal =  DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->get();

        foreach ($desempleo_cartera_temporal as $temp) {
            $temp->TotalCredito = $temp->calculoTodalSaldo();
            $temp->update();
        }

        $poliza_desempleo_tipo_cartera = DesempleoTipoCartera::find($request->DesempleoTipoCartera);

        $tasas_diferenciadas = $poliza_desempleo_tipo_cartera->tasa_diferenciada;

        if ($poliza_desempleo_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                DesempleoCarteraTemp::where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($poliza_desempleo_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                DesempleoCarteraTemp::where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                    ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                    ->update([
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            DesempleoCarteraTemp::where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                ->update([
                    'Tasa' => $desempleo->Tasa
                ]);
        }





        // $axoActual =  $temp_data_fisrt->Axo;
        // $mesActual =  $temp_data_fisrt->Mes;


        // // Calcular el mes pasado
        // if ($mesActual == 1) {
        //     $mesAnterior = 12; // Diciembre
        //     $axoAnterior = $axoActual - 1; // Año anterior
        // } else {
        //     $mesAnterior = $mesActual - 1; // Mes anterior
        //     $axoAnterior = $axoActual; // Mismo año
        // }



        // $data = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();
        // $poliza_edad_maxima = $data->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion);


        // //registros que no existen en el mes anterior
        // $count_data_cartera = DesempleoCartera::where('PolizaDesempleo', $id)->count();
        // if ($count_data_cartera > 0) {
        //     //dd($mesAnterior,$axoAnterior,$request->Desempleo);
        //     $registros_eliminados = DB::table('poliza_desempleo_cartera AS pdc')
        //         ->leftJoin('poliza_desempleo_cartera_temp AS pdtc', function ($join) {
        //             $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia')
        //                 ->where('pdtc.User', auth()->user()->id);
        //         })
        //         ->where('pdc.Mes', (int)$mesAnterior)
        //         ->where('pdc.Axo', (int)$axoAnterior)
        //         ->where('pdc.PolizaDesempleo', $id)
        //         ->whereNull('pdtc.NumeroReferencia') // Solo los que no están en poliza_desempleo_temp_cartera
        //         ->select('pdc.*') // Selecciona columnas principales
        //         ->get();
        // } else {
        //     $registros_eliminados =  DesempleoCarteraTemp::where('Id', 0)->get();
        // }


        // $nuevos_registros = DesempleoCarteraTemp::leftJoin(
        //     DB::raw('(
        //                 SELECT DISTINCT NumeroReferencia
        //                 FROM poliza_desempleo_cartera
        //                 WHERE PolizaDesempleo = ' . $id . '
        //             ) AS valid_references'),
        //     'poliza_desempleo_cartera_temp.NumeroReferencia',
        //     '=',
        //     'valid_references.NumeroReferencia'
        // )
        //     ->where('poliza_desempleo_cartera_temp.User', auth()->user()->id) // Filtra por el usuario autenticado
        //     ->where('poliza_desempleo_cartera_temp.PolizaDesempleo', $id)
        //     ->whereNull('valid_references.NumeroReferencia') // Los registros que no coinciden
        //     ->select('poliza_desempleo_cartera_temp.*') // Selecciona columnas de la tabla principal
        //     ->get();

        // $total = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->sum('SaldoTotal');
        // //recibos tabla de configuracion


        // $temp = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();
        // $mesAnteriorString = $axoAnterior . '-' . $mesAnterior;
        // //calcular rehabilitados
        // $referenciasAnteriores = DB::table('poliza_desempleo_cartera')
        //     ->where('PolizaDesempleo', $id)

        //     ->whereRaw('CONCAT(Axo, "-", Mes) <> ?', [$mesAnteriorString])
        //     ->pluck('NumeroReferencia')
        //     ->toArray();


        // $referenciasMesAterior = DB::table('poliza_desempleo_cartera')
        //     ->where('PolizaDesempleo', $id)

        //     ->where('Axo', $axoAnterior)
        //     ->where('Mes', $mesAnterior)
        //     ->pluck('NumeroReferencia')
        //     ->toArray();


        // foreach ($temp as $item) {
        //     // Verifica si el NumeroReferencia está en referenciasAnteriores pero no en referenciasMesAterior
        //     if (in_array($item->NumeroReferencia, $referenciasAnteriores) && !in_array($item->NumeroReferencia, $referenciasMesAterior)) {
        //         $item->Rehabilitado = 1;
        //         $item->save();
        //     }
        // }

        // $registros_rehabilitados = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->where('Rehabilitado', 1)->get();

        return back()->with('success', 'Cartera agregada correctamente');

        // return view('polizas.desempleo.respuesta_poliza', compact('total', 'desempleo', 'poliza_edad_maxima', 'registros_rehabilitados', 'registros_eliminados', 'nuevos_registros', 'axoActual', 'mesActual'));
        // } catch (\Exception $e) {
        //     // Capturar cualquier excepción y retornar un mensaje de error
        //     return back()->with('error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage());
        // }
    }

    public function create_pago_fedecredito(Request $request, $id)
    {


        // 🧩 Validar datos básicos del formulario
        $request->validate([
            'Axo' => 'required|integer',
            'Mes' => 'required|integer|between:1,12',
            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ], [
            'Axo.required' => 'El campo Año es obligatorio.',
            'Axo.integer' => 'El campo Año debe ser un número entero.',
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
        ]);

        // 🔍 Buscar la póliza
        $desempleo = Desempleo::findOrFail($id);

        // 📂 Cargar el archivo con PhpSpreadsheet
        $archivo = $request->file('Archivo');
        $excel = IOFactory::load($archivo->getPathname());

        // 🧠 Crear validador manual
        $validator = Validator::make([], []);

        // 1️⃣ Validar número de hojas
        if ($excel->getSheetCount() > 1) {
            $validator->errors()->add('Archivo', 'La cartera solo puede contener un solo libro de Excel (sheet).');
            return back()->withErrors($validator);
        }

        // 2️⃣ Validar encabezados esperados
        $expectedColumns = [
            "TIPO DE DOCUMENTO",
            "DUI O DOCUMENTO DE IDENTIDAD",
            "PRIMER APELLIDO",
            "SEGUNDO APELLIDO",
            "NOMBRES",
            "NACIONALIDAD",
            "FECHA DE NACIMIENTO",
            "GÉNERO",
            "NRO. DE PRÉSTAMO",
            "FECHA DE OTORGAMIENTO",
            "MONTO ORIGINAL DE DESEMBOLSO",
            "SALDO DE DEUDA CAPITAL ACTUAL",
            "SALDO INTERESES CORRIENTES",
            "MORA CAPITAL",
            "SALDO INTERESES POR MORA",
            "INTERESES COVID",
            "EXTRA PRIMA",
            "TARIFA",
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
        $firstRow = array_map(fn($v) => mb_strtoupper(trim($v), 'UTF-8'), $firstRow);

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
        DesempleoCarteraTemp::where('PolizaDesempleo', $id)->delete();

        //guardando datos de excel en base de datos
        Excel::import(new DesempleoCarteraTempFedeImport($request->Axo, $request->Mes, $id, $request->FechaInicio, $request->FechaFinal, $request->DesempleoTipoCartera), $archivo);
        $validator = Validator::make([], []); // Creamos un validador vacío

        if ($request->validacion_credito != 'on') {
            $repetidos = DesempleoCarteraTemp::where('User', auth()->user()->id)
                //->where('PolizaDeuda', $request->Id)
                ->groupBy('NumeroReferencia')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            $numerosRepetidos = $repetidos->isNotEmpty() ? $repetidos->pluck('NumeroReferencia') : null;

            if ($numerosRepetidos) {
                DesempleoCarteraTemp::delete();
                // Convertir la colección a string para mostrarla en el error
                $numerosStr = $numerosRepetidos->implode(', ');

                $validator->errors()->add('Archivo', "Existen números de crédito repetidos: $numerosStr");
                return back()->withErrors($validator);
            }
        }

        //calculando errores de cartera
        $cartera_temp = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();




        //calcular la tasa diferenciada





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

                $validador_dui = true;
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


        $temp_data_fisrt = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }






        /* $desempleo_cartera_temporal =  DesempleoCarteraTemp::where('PolizaDesempleo',  $id)->get();




        foreach ($desempleo_cartera_temporal as $temp) {
            $temp->TotalCredito = $temp->calculoTodalSaldo();
            $temp->update();
        }




        $poliza_desempleo_tipo_cartera = DesempleoTipoCartera::find($request->DesempleoTipoCartera);

        $tasas_diferenciadas = $poliza_desempleo_tipo_cartera->tasa_diferenciada;

        if ($poliza_desempleo_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                DesempleoCarteraTemp::where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($poliza_desempleo_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                DesempleoCarteraTemp::where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                    ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                    ->update([
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            DesempleoCarteraTemp::where('DesempleoTipoCartera',  $request->DesempleoTipoCartera)
                ->update([
                    'Tasa' => $desempleo->Tasa
                ]);
        }*/



        return back()->with('success', 'Cartera agregada correctamente');

        // return view('polizas.desempleo.respuesta_poliza', compact('total', 'desempleo', 'poliza_edad_maxima', 'registros_rehabilitados', 'registros_eliminados', 'nuevos_registros', 'axoActual', 'mesActual'));
        // } catch (\Exception $e) {
        //     // Capturar cualquier excepción y retornar un mensaje de error
        //     return back()->with('error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage());
        // }
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


    public function validar_poliza($id)
    {

        $desempleo = Desempleo::findOrFail($id);

        $temp_data_fisrt = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }


        $axoActual =  $temp_data_fisrt->Axo;
        $mesActual =  $temp_data_fisrt->Mes;


        // Calcular el mes pasado
        if ($mesActual == 1) {
            $mesAnterior = 12; // Diciembre
            $axoAnterior = $axoActual - 1; // Año anterior
        } else {
            $mesAnterior = $mesActual - 1; // Mes anterior
            $axoAnterior = $axoActual; // Mismo año
        }



        $data = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();
        $poliza_edad_maxima = $data->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion);


        //registros que no existen en el mes anterior
        $count_data_cartera = DesempleoCartera::where('PolizaDesempleo', $id)->count();
        if ($count_data_cartera > 0) {
            //dd($mesAnterior,$axoAnterior,$request->Desempleo);
            $registros_eliminados = DB::table('poliza_desempleo_cartera AS pdc')
                ->leftJoin('poliza_desempleo_cartera_temp AS pdtc', function ($join) {
                    $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia')
                        ->where('pdtc.User', auth()->user()->id);
                })
                ->where('pdc.Mes', (int)$mesAnterior)
                ->where('pdc.Axo', (int)$axoAnterior)
                ->where('pdc.PolizaDesempleo', $id)
                ->whereNull('pdtc.NumeroReferencia') // Solo los que no están en poliza_desempleo_temp_cartera
                ->select('pdc.*') // Selecciona columnas principales
                ->get();
        } else {
            $registros_eliminados =  DesempleoCarteraTemp::where('Id', 0)->get();
        }


        $nuevos_registros = DesempleoCarteraTemp::leftJoin(
            DB::raw('(
                        SELECT DISTINCT NumeroReferencia
                        FROM poliza_desempleo_cartera
                        WHERE PolizaDesempleo = ' . $id . '
                    ) AS valid_references'),
            'poliza_desempleo_cartera_temp.NumeroReferencia',
            '=',
            'valid_references.NumeroReferencia'
        )
            ->where('poliza_desempleo_cartera_temp.User', auth()->user()->id) // Filtra por el usuario autenticado
            ->where('poliza_desempleo_cartera_temp.PolizaDesempleo', $id)
            ->whereNull('valid_references.NumeroReferencia') // Los registros que no coinciden
            ->select('poliza_desempleo_cartera_temp.*') // Selecciona columnas de la tabla principal
            ->get();

        $total = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->sum('SaldoTotal');
        //recibos tabla de configuracion


        $temp = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->get();
        $mesAnteriorString = $axoAnterior . '-' . $mesAnterior;
        //calcular rehabilitados
        $referenciasAnteriores = DB::table('poliza_desempleo_cartera')
            ->where('PolizaDesempleo', $id)

            ->whereRaw('CONCAT(Axo, "-", Mes) <> ?', [$mesAnteriorString])
            ->pluck('NumeroReferencia')
            ->toArray();


        $referenciasMesAterior = DB::table('poliza_desempleo_cartera')
            ->where('PolizaDesempleo', $id)

            ->where('Axo', $axoAnterior)
            ->where('Mes', $mesAnterior)
            ->pluck('NumeroReferencia')
            ->toArray();


        foreach ($temp as $item) {
            // Verifica si el NumeroReferencia está en referenciasAnteriores pero no en referenciasMesAterior
            if (in_array($item->NumeroReferencia, $referenciasAnteriores) && !in_array($item->NumeroReferencia, $referenciasMesAterior)) {
                $item->Rehabilitado = 1;
                $item->save();
            }
        }

        $registros_rehabilitados = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->where('Rehabilitado', 1)->get();

        return view('polizas.desempleo.respuesta_poliza', compact('total', 'desempleo', 'poliza_edad_maxima', 'registros_rehabilitados', 'registros_eliminados', 'nuevos_registros', 'axoActual', 'mesActual'));
    }



    public function cancelar_pago(Request $request)
    {

        try {

            $poliza = DesempleoCartera::where('PolizaDesempleo', '=', $request->Desempleo)->where('PolizaDesempleoDetalle', null)->delete();

            DesempleoCarteraTemp::where('PolizaDesempleo', '=', $request->Desempleo)->delete();
            // dd($poliza);
        } catch (\Throwable $th) {
            //throw $th;
        }
        session(['MontoCartera' => 0]);

        alert()->success('El cobro se ha eliminado correctamente');
        return redirect('polizas/desempleo/' . $request->Desempleo . '?tab=2');
    }


    public function delete_temp(Request $request)
    {
        DesempleoCarteraTemp::where('PolizaDesempleo', '=', $request->DesempleoId)->delete();

        return redirect('polizas/desempleo/subir_cartera/' . $request->DesempleoId);
    }
}
