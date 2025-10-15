<?php

namespace App\Http\Controllers\polizas;

use App\Exports\ExcluidosExport;
use App\Http\Controllers\Controller;
use App\Imports\PolizaDeudaTempCarteraComImport;
use App\Imports\PolizaDeudaTempCarteraImport;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\DeudaDetalle;
use App\Models\polizas\DeudaEliminados;
use App\Models\polizas\DeudaExcluidos;
use App\Models\polizas\PolizaDeudaCartera;
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

class DeudaCarteraController extends Controller
{

    public function subir_cartera($id)
    {
        $deuda = Deuda::findOrFail($id);
        $deuda_tipo_cartera = $deuda->deuda_tipos_cartera;

        foreach ($deuda_tipo_cartera as $tipo_cartera) {
            $tasas_diferenciadas = $tipo_cartera->tasa_diferenciada;

            $tipo_cartera->Descripcion = $tasas_diferenciadas
                ->pluck('linea_credito.Descripcion')
                ->unique()
                ->implode(',');

            $tipo_cartera->Abreviatura = $tasas_diferenciadas
                ->pluck('linea_credito.Abreviatura')
                ->unique()
                ->implode(',');

            $tipo_cartera->Total = PolizaDeudaTempCartera::where([
                ['PolizaDeudaTipoCartera', $tipo_cartera->Id],
                ['User', auth()->id()]
            ])->sum('TotalCredito');
        }

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
        $fecha_inicial = now()->subMonth()->startOfMonth();
        $fecha_final = now()->startOfMonth();
        $axo = $fecha_inicial->year;
        $mes = (int) $fecha_inicial->month;


        // ✅ Fechas en formato Y-m-d
        $fecha_inicial = $fecha_inicial->format('Y-m-d');
        $fecha_final = $fecha_final->format('Y-m-d');

        // Último pago activo
        $ultimo_pago = DeudaDetalle::where('Deuda', $deuda->Id)
            ->where('Activo', 1)
            ->latest('Id')
            ->first();


        if ($ultimo_pago) {
            // Si hay pago, tomar la fecha inicial y final con +1 mes exacto
            $fecha_inicial = Carbon::parse($ultimo_pago->FechaFinal);
            $fecha_final = $fecha_inicial->copy()->addMonth();

            $axo = $fecha_inicial->year;
            $mes = (int) $fecha_inicial->month;

            // Formato final Y-m-d
            $fecha_inicial = $fecha_inicial->format('Y-m-d');
            $fecha_final = $fecha_final->format('Y-m-d');

        }


        // Último registro temporal de cartera
        $registro_cartera = PolizaDeudaTempCartera::where('PolizaDeuda', $id)->first();

        if ($registro_cartera) {
            $axo = $registro_cartera->Axo;
            $mes = (int) $registro_cartera->Mes;

            $fecha_inicial = $registro_cartera->FechaInicio;
            $fecha_final = $registro_cartera->FechaFinal;
        }



        return view('polizas.deuda.subir_archivos', compact(
            'deuda',
            'deuda_tipo_cartera',
            'meses',
            'fecha_inicial',
            'fecha_final',
            'axo',
            'mes'
        ));
    }

    public function get_cartera($id, $mes, $axo)
    {
        $deuda = Deuda::findOrFail($id);
        $deuda_cartera = PolizaDeudaCartera::where('PolizaDeuda', $id)->where('Mes', $mes)->where('Axo', $axo)->first();

        if ($deuda_cartera) {
            return true;
        }
        return false;
    }






    public function recibo_complementario($id)
    {

        $deuda = Deuda::findOrFail($id);

        $deuda_tipo_cartera = $deuda->deuda_tipos_cartera;

        foreach ($deuda_tipo_cartera as $tipo_cartera) {
            $tasas_diferenciadas  = $tipo_cartera->tasa_diferenciada;

            // Obtener todas las descripciones de línea de crédito y unirlas con coma
            $tipo_cartera->Descripcion = implode(',', $tasas_diferenciadas->pluck('linea_credito.Descripcion')->unique()->toArray());
            $tipo_cartera->Abreviatura = implode(',', $tasas_diferenciadas->pluck('linea_credito.Abreviatura')->unique()->toArray());
            $tipo_cartera->Total = PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $tipo_cartera->Id)->sum('TotalCredito');
        }


        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $ultimo_pago = DeudaDetalle::where('Deuda', $deuda->Id)->where('Activo', 1)->orderBy('Id', 'desc')->first();


        //inicializando valores
        // Primer día del mes anterior
        $fecha_inicial = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        // Primer día del mes actual
        $fecha_final = Carbon::now()->startOfMonth()->format('Y-m-d');

        $axo = Carbon::parse()->format('Y');
        $mes = Carbon::parse()->format('m');

        if ($ultimo_pago) {
            $fecha_inicial = Carbon::parse($ultimo_pago->FechaInicio);
            $fecha_inicial->addMonth();

            $axo = $fecha_inicial->format('Y');
            $mes = $fecha_inicial->format('m') + 0;

            $fecha_inicial = $fecha_inicial->format('Y-m-d');

            $fecha_final = Carbon::parse($ultimo_pago->FechaFinal);
            $fecha_final->addMonth();
            $fecha_final = $fecha_final->format('Y-m-d');
        }



        //ultimo registro de cartera
        $registro_cartera = PolizaDeudaTempCartera::where('PolizaDeuda', $id)->first();

        if ($registro_cartera) {
            $axo = $registro_cartera->Axo;
            $mes = $registro_cartera->Mes + 0;
        }



        return view('polizas.deuda.recibo_complementario', compact(
            'deuda',
            'deuda_tipo_cartera',
            'meses',
            'fecha_inicial',
            'fecha_final',
            'axo',
            'mes'
        ));
    }

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



        // try {
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

        $firstRow = $excel->getActiveSheet()->rangeToArray('A1:Z1')[0];

        // Validar que no esté vacío
        if (empty(array_filter($firstRow))) {
            $validator->errors()->add('Archivo', 'El archivo está vacío o no tiene el formato esperado');
            return back()->withErrors($validator);
        }

        // Normalizar (trim) para evitar espacios extras
        $firstRow = array_map('trim', $firstRow);

        // Validar cantidad de columnas
        if (count($firstRow) < count($expectedColumns)) {
            $validator->errors()->add('Archivo', 'Error de formato: faltan columnas en la primera fila');
            return back()->withErrors($validator);
        }

        // Validar que todas las columnas sean iguales y en el mismo orden
        foreach ($expectedColumns as $index => $expectedColumn) {
            if (!isset($firstRow[$index]) || $firstRow[$index] !== $expectedColumn) {
                $validator->errors()->add('Archivo', "Error de formato: la columna " . ($index + 1) . " debe ser '$expectedColumn'");
                return back()->withErrors($validator);
            }
        }



        PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->delete();

        try {
            Excel::import(new PolizaDeudaTempCarteraImport($date->year, $date->month, $deuda->Id, $request->FechaInicio, $request->FechaFinal, $deuda_tipo_cartera->Id, $deuda->TarifaExcel), $archivo);
        } catch (\Exception $e) {
            // Filtramos solo nuestros errores de validación
            if (strpos($e->getMessage(), 'VALIDATION_ERROR:') === 0) {
                return back()->with('error', str_replace('VALIDATION_ERROR: ', '', $e->getMessage()));
            }

            // Otros errores
            return back()->with('error', 'Ocurrió un error al procesar el archivo');
        }





        // 🔍 Buscar DUI con caracteres inválidos (#, !, %, etc.)
        $duiInvalidos = PolizaDeudaTempCartera::where('User', auth()->id())
            ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
            ->whereRaw("Dui REGEXP '[^0-9-]'") // Detección de caracteres no válidos
            ->pluck('NumeroReferencia')
            ->toArray();

        if (count($duiInvalidos) > 0) {
            // 👇 Redirigir hacia atrás con los errores en la vista
            return back()
                ->with('warning', 'Se detectaron DUI inválidos en el archivo en los creditos.')
                ->with('errores', $duiInvalidos)
                ->withInput();
        }




        //verificando creditos repetidos

        //dd($request->validacion_credito);
        if ($request->validacion_credito != 'on') {
            $repetidos = PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                //->where('PolizaDeuda', $request->Id)
                ->groupBy('NumeroReferencia')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            $numerosRepetidos = $repetidos->isNotEmpty() ? $repetidos->pluck('NumeroReferencia') : null;

            if ($numerosRepetidos) {
                PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->delete();
                // Convertir la colección a string para mostrarla en el error
                $numerosStr = $numerosRepetidos->implode(', ');

                $validator->errors()->add('Archivo', "Existen números de crédito repetidos: $numerosStr");
                return back()->withErrors($validator);
            }
        }






        //calculando errores de cartera
        $cartera_temp = PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->get();






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
            if ($request->validacion_dui == 'on') {
                $validador_dui = true;
            } else {
                if (empty($obj->Nacionalidad)) {
                    $obj->TipoError = 9;
                    $obj->update();

                    array_push($errores_array, 9);
                } else if ($obj->Nacionalidad == 'SAL' || $obj->Nacionalidad == 'Sal' || $obj->Nacionalidad == 'sal') {
                    $validador_dui = $this->validarDocumento($obj->Dui, "dui");



                    if ($validador_dui == false) {
                        $obj->TipoError = 2;
                        $obj->update();

                        array_push($errores_array, 2);
                    }
                } else {

                    if ($obj->Pasaporte == null && $obj->CarnetResidencia == null) {
                        $validador_dui = false;
                        if ($validador_dui == false) {
                            $obj->TipoError = 8;
                            $obj->update();

                            array_push($errores_array, 8);
                        }
                    } else {
                        $validador_dui = true;
                    }
                }
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



            //6 error formato fecha vencimiento
            // $validador_fecha_vencimiento = $this->validarFormatoFecha($obj->FechaVencimiento);
            // if ($validador_fecha_vencimiento == false) {
            //     //trata de convertir la fecha excel en fecha y luego comprobar nuevamente si la fecha convertida es una fecha.
            //     $fecha_excel_convertida_vencimiento = $this->convertDate($obj->FechaVencimiento);
            //     $validador_fecha_vencimiento = $this->validarFormatoFecha($fecha_excel_convertida_vencimiento);

            //     if ($validador_fecha_vencimiento == false || trim($obj->FechaVencimiento) == "") {
            //         $obj->TipoError = 6;
            //         $obj->update();

            //         array_push($errores_array, 6);
            //     } else {
            //         $obj->FechaVencimiento = $fecha_excel_convertida_vencimiento;
            //         $obj->update();
            //     }
            // }

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


            $obj->Errores = $errores_array;
        }






        $data_error = $cartera_temp->where('TipoError', '<>', 0);


        //dd($data_error);

        if ($data_error->count() > 0) {
            $deuda_tipo_cartera_id = $deuda_tipo_cartera->Id;
            return view('polizas.deuda.respuesta_poliza_error', compact('data_error', 'deuda', 'deuda_tipo_cartera_id'));
        }



        // Filtrar nombres y apellidos que contengan caracteres inválidos
        $errores_nombre_apellido = $cartera_temp->filter(function ($item) {
            // Solo letras, espacios y punto
            $regex = '/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s\.]+$/u';

            $campos = [
                $item->PrimerNombre,
                $item->SegundoNombre,
                $item->PrimerApellido,
                $item->SegundoApellido,
                $item->ApellidoCasada
            ];

            foreach ($campos as $valor) {
                if ($valor && !preg_match($regex, $valor)) {
                    return true; // hay error
                }
            }

            return false; // todos correctos
        });

        // Actualizar TipoError = 11 en los registros que tienen errores
        foreach ($errores_nombre_apellido as $obj) {
            $obj->TipoError = 11;
            $obj->save();
        }

        // Si hay errores en nombres o apellidos
        if ($errores_nombre_apellido->count() > 0) {
            $deuda_tipo_cartera_id = $deuda_tipo_cartera->Id;
            return view('polizas.deuda.respuesta_poliza_error', [
                'data_error' => $errores_nombre_apellido,
                'deuda' => $deuda,
                'deuda_tipo_cartera_id' => $deuda_tipo_cartera_id
            ]);
        }




        //calculando edades y fechas de nacimiento
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                //'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);





        //tasas diferenciadas solo los que no traen la tarifa en el excel
        if ($deuda->TarifaExcel != 1) {
            $tasas_diferenciadas = $deuda_tipo_cartera->tasa_diferenciada;

            if ($deuda_tipo_cartera->TipoCalculo == 1) {

                foreach ($tasas_diferenciadas as $tasa) {
                    //dd($tasa);
                    PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                        ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                        ->update([
                            'LineaCredito' => $tasa->LineaCredito,
                            'Tasa' => $tasa->Tasa
                        ]);
                }
            } else  if ($deuda_tipo_cartera->TipoCalculo == 2) {

                foreach ($tasas_diferenciadas as $tasa) {
                    PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                        ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                        ->update([
                            'LineaCredito' => $tasa->LineaCredito,
                            'Tasa' => $tasa->Tasa
                        ]);
                }
            } else {
                foreach ($tasas_diferenciadas as $tasa) {
                    PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                        ->update([
                            'LineaCredito' => $tasa->LineaCredito,
                            'Tasa' => $deuda->Tasa
                        ]);
                }
            }
        } else {
            //tasas diferenciadas solo los que si traen la tarifa en el excel
            $tasas_diferenciadas = $deuda_tipo_cartera->tasa_diferenciada;
            foreach ($tasas_diferenciadas as $tasa) {
                PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        //'Tasa' => $deuda->Tasa
                    ]);
            }
        }

        $cartera_temp = PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->get();

        foreach ($cartera_temp as $obj) {
            $obj->TotalCredito = $obj->calculoTodalSaldo();
            $obj->update();
        }





        //$linea_credito = DeudaCredito::find($credito);

        //$deuda_tipo_cartera = PolizaDeudaTipoCartera::findOrFail($request->PolizaDeudaTipoCartera);

        $MontoMaximoIndividual = $deuda_tipo_cartera->MontoMaximoIndividual;
        if (isset($MontoMaximoIndividual) && $MontoMaximoIndividual > 0) {
          // Paso 1: obtener los identificadores (DUI/Pasaporte/Carnet) que superan el límite
            $personas = PolizaDeudaTempCartera::selectRaw("
                    COALESCE(NULLIF(Dui, ''), NULLIF(Pasaporte, ''), NULLIF(CarnetResidencia, '')) AS Identificador
                ")
                ->where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                ->whereNotNull(DB::raw("COALESCE(NULLIF(Dui, ''), NULLIF(Pasaporte, ''), NULLIF(CarnetResidencia, ''))"))
                ->groupBy('Identificador')
                ->havingRaw('SUM(TotalCredito) > ?', [$MontoMaximoIndividual])
                ->pluck('Identificador');

            if ($personas->isNotEmpty()) {
                PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->where(function ($query) use ($personas) {
                        $query->where(function ($q) use ($personas) {
                            $q->whereNotNull('Dui')
                                ->where('Dui', '!=', '')
                                ->whereIn('Dui', $personas);
                        })
                            ->orWhere(function ($q) use ($personas) {
                                $q->whereNotNull('Pasaporte')
                                    ->where('Pasaporte', '!=', '')
                                    ->whereIn('Pasaporte', $personas);
                            })
                            ->orWhere(function ($q) use ($personas) {
                                $q->whereNotNull('CarnetResidencia')
                                    ->where('CarnetResidencia', '!=', '')
                                    ->whereIn('CarnetResidencia', $personas);
                            });
                    })
                    ->update([
                        'MontoMaximoIndividual' => 1,
                        'NoValido' => 1,
                    ]);
            }
        }




        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();


        return view('polizas.deuda.validacion_poliza.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
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


        PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->delete();

        try {
            Excel::import(new PolizaDeudaTempCarteraComImport($deuda->Id, $request->FechaInicio, $request->FechaFinal, $deuda_tipo_cartera->Id), $archivo);
        } catch (Throwable $e) {
            // Filtramos solo nuestros errores de validación
            if (strpos($e->getMessage(), 'VALIDATION_ERROR:') === 0) {
                return back()->with('error', str_replace('VALIDATION_ERROR: ', '', $e->getMessage()));
            }

            // Otros errores
            return back()->with('error', 'Ocurrió un error al procesar el archivo');
        }

        //calculando errores de cartera
        $cartera_temp = PolizaDeudaTempCartera::where('LineaCredito', '=', $deuda_tipo_cartera->Id)->get();

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
            if ($request->validacion_dui == 'on') {
                $validador_dui = true;
            } else {
                if (empty($obj->Nacionalidad)) {
                    $obj->TipoError = 9;
                    $obj->update();

                    array_push($errores_array, 9);
                } else if ($obj->Nacionalidad == 'SAL' || $obj->Nacionalidad == 'Sal' || $obj->Nacionalidad == 'sal') {
                    $validador_dui = $this->validarDocumento($obj->Dui, "dui");

                    if ($validador_dui == false) {
                        $obj->TipoError = 2;
                        $obj->update();

                        array_push($errores_array, 2);
                    }
                } else {
                    if ($obj->Pasaporte == null && $obj->CarnetResidencia == null) {
                        $validador_dui = false;
                        if ($validador_dui == false) {
                            $obj->TipoError = 8;
                            $obj->update();

                            array_push($errores_array, 8);
                        }
                    } else {
                        $validador_dui = true;
                    }
                }
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



            //6 error formato fecha vencimiento
            $validador_fecha_vencimiento = $this->validarFormatoFecha($obj->FechaVencimiento);
            if ($validador_fecha_vencimiento == false) {
                //trata de convertir la fecha excel en fecha y luego comprobar nuevamente si la fecha convertida es una fecha.
                $fecha_excel_convertida_vencimiento = $this->convertDate($obj->FechaVencimiento);
                $validador_fecha_vencimiento = $this->validarFormatoFecha($fecha_excel_convertida_vencimiento);

                if ($validador_fecha_vencimiento == false || trim($obj->FechaVencimiento) == "") {
                    $obj->TipoError = 6;
                    $obj->update();

                    array_push($errores_array, 6);
                } else {
                    $obj->FechaVencimiento = $fecha_excel_convertida_vencimiento;
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
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                //'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);




        //tasas diferenciadas
        $tasas_diferenciadas = $deuda_tipo_cartera->tasa_diferenciada;
        // dd($deuda_tipo_cartera, $tasas_diferenciadas);

        if ($deuda_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($deuda_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {

                PolizaDeudaTempCartera::
                where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->whereBetween('EdadDesembloso', [$tasa->EdadDesde, $tasa->EdadHasta])
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            foreach ($tasas_diferenciadas as $tasa) {
                PolizaDeudaTempCartera::
                where('PolizaDeudaTipoCartera', $deuda_tipo_cartera->Id)
                    ->update([
                        'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $deuda->Tasa
                    ]);
            }
        }


        $cartera_temp = PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', '=', $deuda_tipo_cartera->Id)->get();

        foreach ($cartera_temp as $obj) {
            $obj->TotalCredito = $obj->calculoTodalSaldo();
            $obj->update();
        }

        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();


        //        return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
    }


    public function deleteLineaCredito(Request $request)
    {
        PolizaDeudaTempCartera::where('PolizaDeudaTipoCartera', '=', $request->PolizaDeudaTipoCartera)->delete();

        return redirect('polizas/deuda/subir_cartera/' . $request->DeudaId);
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



    public function add_excluidos(Request $request)
    {
        $registro =  PolizaDeudaTempCartera::findOrFail($request->id);

        $deuda = Deuda::findOrFail($registro->PolizaDeuda);
        $registro->NoValido = 1;
        $registro->save();

        $existe = DeudaExcluidos::where('NumeroReferencia', $registro->NumeroReferencia)->where('Poliza', $registro->PolizaDeuda)->where('EdadMaxima', 1)->first();

        if ($existe) {
            $existe->delete();
        } else {
            $excluidos = new DeudaExcluidos();
            $excluidos->Dui = $registro->Dui;
            $excluidos->Nombre = $registro->PrimerNombre . ' ' . $registro->SegundoNombre . ' ' . $registro->PrimerApellido . ' ' . $registro->SegundoApellido . ' ' . $registro->ApellidoCasada;
            $excluidos->NumeroReferencia = $registro->NumeroReferencia;
            $excluidos->Poliza = $registro->PolizaDeuda;
            $excluidos->FechaExclusion = Carbon::now('America/El_Salvador');
            $excluidos->Usuario = auth()->user()->id;
            $excluidos->Edad = $registro->EdadDesembloso;
            $excluidos->EdadMaxima = 1;
            $excluidos->save();
        }

        $conteo = $deuda->conteoEdadMaxima();


        return response()->json(['success' => true, 'conteo' => $conteo]);
    }


    public function add_excluidos_responsabilidad(Request $request)
    {
        $registro =  PolizaDeudaTempCartera::findOrFail($request->id);

        $deuda = Deuda::findOrFail($registro->PolizaDeuda);
        $registro->NoValido = 1;
        $registro->save();

        $existe = DeudaExcluidos::where('NumeroReferencia', $registro->NumeroReferencia)->where('Poliza', $registro->PolizaDeuda)->where('ResponsabilidadMaxima', 1)->first();

        if ($existe) {
            $existe->delete();
        } else {
            $excluidos = new DeudaExcluidos();
            $excluidos->Dui = $registro->Dui;
            $excluidos->Nombre = $registro->PrimerNombre . ' ' . $registro->SegundoNombre . ' ' . $registro->PrimerApellido . ' ' . $registro->SegundoApellido . ' ' . $registro->ApellidoCasada;
            $excluidos->NumeroReferencia = $registro->NumeroReferencia;
            $excluidos->Poliza = $registro->PolizaDeuda;
            $excluidos->FechaExclusion = Carbon::now('America/El_Salvador');
            $excluidos->Usuario = auth()->user()->id;
            //$excluidos->Edad = $registro->EdadDesembloso;
            $excluidos->ResponsabilidadMaxima = 1;
            $excluidos->Responsabilidad = $request->subtotal;
            $excluidos->save();
        }

        $conteo = $deuda->conteoEdadMaxima();


        return response()->json(['success' => true, 'conteo' => $conteo]);
    }

    public function delete_excluido(Request $request)
    {
        $registro = PolizaDeudaTempCartera::findOrFail($request->id);
        $registro->NoValido = 1;
        $registro->Excluido = 0;
        $registro->update();
        $id_exx = 0;
        $excluido = DeudaExcluidos::findOrFail($request->id_ex)->delete();

        return response()->json(['excluido' => $id_exx, 'conteo_excluidos' => 1]);
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




    public function validar_poliza(Request $request)
    {

        $poliza_id = $request->Deuda;
        $deuda = Deuda::findOrFail($request->Deuda);

        $temp_data_fisrt = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }


        $requisitos = $deuda->requisitos;

        if ($requisitos->count() == 0) {
            alert()->error('No se han definido requisitos minimos de asegurabilidad');
            $deuda->Configuracion = 0;
            $deuda->update();
            return redirect('polizas/deuda/' . $deuda->Id);
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

        //dd( $axoTemp ,$mesTemp, $axoAnterior ,$mesAnterior);


        //dejar valores a cero
        DB::table('poliza_deuda_temp_cartera')
            ->where('PolizaDeuda', $poliza_id)
            ->update(['Rehabilitado' => 0]);


        $count_cartera = PolizaDeudaCartera::where('PolizaDeuda', $poliza_id)->where('Mes', '<>', $mesAnterior)->where('Mes', '<>', $mesAnterior)->count();

        $mesAnteriorString = $axoAnterior . '-' . $mesAnterior;
        if ($count_cartera > 0) {
            //calcular rehabilitados
            $referenciasAnteriores = DB::table('poliza_deuda_cartera')
                ->where('PolizaDeuda', $poliza_id)
                ->whereRaw('CONCAT(Axo, "-", Mes) <> ?', [$mesAnteriorString])
                ->pluck('NumeroReferencia')
                ->toArray();


            $referenciasMesAterior = DB::table('poliza_deuda_cartera')
                ->where('PolizaDeuda', $poliza_id)

                ->where('Axo', $axoAnterior)
                ->where('Mes', $mesAnterior)
                ->pluck('NumeroReferencia')
                ->toArray();


            $temp = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->get();


            foreach ($temp as $item) {
                // Verifica si el NumeroReferencia está en referenciasAnteriores pero no en referenciasMesAterior
                if (in_array($item->NumeroReferencia, $referenciasAnteriores) && !in_array($item->NumeroReferencia, $referenciasMesAterior)) {
                    $item->Rehabilitado = 1;
                    $item->save();
                }
            }
        }


        //calcular los registros que pasan de la edad maxima
        $poliza_edad_maxima = PolizaDeudaTempCartera::where('PolizaDeuda', $request->Deuda)
            ->where('Edad', '>', $deuda->EdadMaximaTerminacion)->get();


        //$deuda->ResponsabilidadMaxima = 25000;
        //calcular los registros que pasan de la responsabilidad maxima
        $poliza_responsabilidad_maxima = PolizaDeudaTempCartera::selectRaw('Id,Dui,NumeroReferencia,Edad,CarnetResidencia,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,Excluido,NoValido,TotalCredito,EdadDesembloso,Excluido,FechaOtorgamiento')
            ->having('TotalCredito', '>', $deuda->ResponsabilidadMaxima)
            ->where('PolizaDeuda', $request->Deuda)
            ->get();



        //registros que no existen en el mes anterior
        $count_data_cartera = PolizaDeudaCartera::where('PolizaDeuda', $poliza_id)->count();
        if ($count_data_cartera > 0) {
            //dd($mesAnterior,$axoAnterior,$request->Deuda);
            $registros_eliminados = DB::table('poliza_deuda_cartera AS pdc')
                ->leftJoin('poliza_deuda_temp_cartera AS pdtc', function ($join) {
                    $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia');
                })
                ->where('pdc.Mes', (int)$mesAnterior)
                ->where('pdc.Axo', (int)$axoAnterior)
                ->where('pdc.PolizaDeuda', $request->Deuda)
                ->whereNull('pdtc.NumeroReferencia') // Solo los que no están en poliza_deuda_temp_cartera
                ->select('pdc.*') // Selecciona columnas principales
                ->get();
        } else {
            $registros_eliminados =  PolizaDeudaTempCartera::where('Id', 0)->get();
        }




        $nuevos_registros = PolizaDeudaTempCartera::leftJoin(
            DB::raw('(
                        SELECT DISTINCT NumeroReferencia
                        FROM poliza_deuda_cartera
                        WHERE PolizaDeuda = ' . $request->Deuda . '
                    ) AS valid_references'),
            'poliza_deuda_temp_cartera.NumeroReferencia',
            '=',
            'valid_references.NumeroReferencia'
        )
            ->where('poliza_deuda_temp_cartera.PolizaDeuda', $request->Deuda)
            ->whereNull('valid_references.NumeroReferencia') // Los registros que no coinciden
            ->select('poliza_deuda_temp_cartera.*') // Selecciona columnas de la tabla principal
            ->get();



        $extra_primados = $deuda->extra_primados;

        foreach ($extra_primados as $extra_primado) {
            //$extra_primado->Existe =
            $registro  = PolizaDeudaTempCartera::where('NumeroReferencia', $extra_primado->NumeroReferencia)
                ->sum('TotalCredito') ?? 0;
            if ($registro > 0) {
                $extra_primado->Existe = 1;
                $extra_primado->MontoOtorgamiento = $registro;
            } else {
                $extra_primado->Existe = 0;
            }
        }



        //cumulos por dui y linea credito

        DB::statement("
            UPDATE poliza_deuda_temp_cartera p1
            JOIN (
                SELECT
                    COALESCE(Dui, '') AS Dui,
                    COALESCE(PolizaDeudaTipoCartera, '') AS PolizaDeudaTipoCartera,
                    COALESCE(Pasaporte, '') AS Pasaporte,
                    COALESCE(CarnetResidencia, '') AS CarnetResidencia,
                    SUM(TotalCredito) AS total_saldo_cumulo
                FROM poliza_deuda_temp_cartera
                WHERE PolizaDeuda = ?
                GROUP BY
                    COALESCE(Dui, ''),
                    COALESCE(PolizaDeudaTipoCartera, ''),
                    COALESCE(Pasaporte, ''),
                    COALESCE(CarnetResidencia, '')
            ) p2
                ON COALESCE(p1.Dui, '') = p2.Dui
                AND COALESCE(p1.PolizaDeudaTipoCartera, '') = p2.PolizaDeudaTipoCartera
                AND COALESCE(p1.Pasaporte, '') = p2.Pasaporte
                AND COALESCE(p1.CarnetResidencia, '') = p2.CarnetResidencia
            SET p1.SaldoCumulo = p2.total_saldo_cumulo
            WHERE p1.PolizaDeuda = ?", [$request->Deuda, $request->Deuda]);




        $poliza_cumulos = PolizaDeudaTempCartera::where('PolizaDeuda', $request->Deuda)->get();

        foreach ($requisitos as $requisito) {
            if ($requisito->perfil->PagoAutomatico == 1 || $requisito->perfil->DeclaracionJurada == 1) {
                $requisito->OmicionPerfil = 1;
                //$requisito->NoValido = 0;
            } else {
                $requisito->OmicionPerfil = 0;
                //$requisito->NoValido = 1;
            }
        }



        // Ordenar la colección por OmicionPerfil de forma descendente
        $requisitos = $requisitos->sortByDesc('OmicionPerfil');




        foreach ($requisitos as $requisito) {

            $ids_cartera = $poliza_cumulos->where('EdadDesembloso', '>=', $requisito->EdadInicial)->where('EdadDesembloso', '<=', $requisito->EdadFinal)
                ->where('SaldoCumulo', '>=', $requisito->MontoInicial)->where('SaldoCumulo', '<=', $requisito->MontoFinal)
                ->pluck('Id')->toArray();

            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->whereIn('Id', $ids_cartera)
                ->update([
                    'Perfiles' => DB::raw(
                        'IF(Perfiles IS NULL OR Perfiles = "", "' . $requisito->perfil->Descripcion . '", CONCAT(Perfiles, ",", "' . $requisito->perfil->Descripcion . '"))'
                    ),
                    'OmisionPerfil'   => $requisito->OmicionPerfil,
                    'MontoRequisito'  => null,
                    'EdadRequisito'   => null
                ]);


            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->whereIn('Id', $ids_cartera)
                ->where('SaldoCumulo', '>=', $requisito->MontoInicial)->where('SaldoCumulo', '<=', $requisito->MontoFinal)
                ->where('EdadDesembloso', '>=', $requisito->EdadInicial)->where('EdadDesembloso', '<=', $requisito->EdadFinal)
                ->update([
                    'MontoRequisito' =>  $requisito->MontoInicial,
                    'EdadRequisito' =>  $requisito->EdadInicial
                ]);

            // if ($requisito->perfil->PagoAutomatico == 1 || $requisito->perfil->DeclaracionJurada == 1) {
            //     $ids_cartera = $poliza_cumulos->where('EdadDesembloso', '>=', $requisito->EdadInicial)->where('EdadDesembloso', '<=', $requisito->EdadFinal)
            //         ->where('TotalCredito', '>=', $requisito->MontoInicial)->where('TotalCredito', '<=', $requisito->MontoFinal)
            //         ->pluck('Id')->toArray();

            //     PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
            //         ->whereIn('Id', $ids_cartera)
            //         ->update([
            //             'PagoAutomatico' =>  1
            //         ]);
            // }
        }

        // dd($requisitos);
        //inicializamos los no validos a cero
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('MontoMaximoIndividual', 0)
            ->update(['NoValido' => 0]);

        $edades = DB::table('poliza_deuda_requisitos')
            ->where('Deuda', $request->Deuda)
            ->selectRaw('MIN(EdadInicial) as EdadInicial, MAX(EdadFinal) as EdadFinal,MIN(MontoInicial) as MontoInicial,MAX(MontoFinal) as MontoFinal')
            ->first();

        if ($edades) {
            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->where('EdadDesembloso', '<', $edades->EdadInicial)
                ->orWhere('EdadDesembloso', '>', $edades->EdadFinal)
                ->update(['NoValido' => 1]);

            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->where('SaldoCumulo', '>', $edades->MontoFinal)
                ->update(['NoValido' => 1]);
        }

        $novalidos = PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('NoValido', 1)->get();
        //dd($novalidos);

        //update para los que son mayores a la edad inicial
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
            ->where('NoValido', 0)
            ->where(function ($query) {
                $query->where('Perfiles', null)
                    ->orWhere('Perfiles', '=', '');
            })
            ->update(['NoValido' => 1]);

        //haciendo trim a perfiles
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
            ->update([
                'Perfiles' => DB::raw('TRIM(Perfiles)')
            ]);




        return view('polizas.deuda.validacion_poliza.respuesta_poliza', compact(
            'deuda',
            'axoActual',
            'mesActual',
            'axoAnterior',
            'mesAnterior',
            'registros_eliminados',
            'poliza_edad_maxima',
            'poliza_responsabilidad_maxima',
            'nuevos_registros',
            'extra_primados',
        ));
    }

    public function validar_poliza_recibos(Request $request)

    {

        $poliza_id = $request->Deuda;
        $deuda = Deuda::findOrFail($request->Deuda);

        $temp_data_fisrt = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }

        //validacion de exisistencia de requisitos
        $requisitos = $deuda->requisitos;

        if ($requisitos->count() == 0) {
            alert()->error('No se han definido requisitos minimos de asegurabilidad');
            $deuda->Configuracion = 0;
            $deuda->update();
            session(['tab' => 3]);
            return redirect('polizas/deuda/' . $deuda->Id);
        }


        //estableciendo fecha de nacimiento date y calculando edad
        PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);


        $poliza_cumulos = PolizaDeudaTempCartera::join('poliza_deuda_creditos as pdc', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'pdc.Id')
            ->select(
                'poliza_deuda_temp_cartera.Id',
                'poliza_deuda_temp_cartera.Dui',
                'poliza_deuda_temp_cartera.Edad',
                'poliza_deuda_temp_cartera.Nit',
                'poliza_deuda_temp_cartera.PrimerNombre',
                'poliza_deuda_temp_cartera.SegundoNombre',
                'poliza_deuda_temp_cartera.PrimerApellido',
                'poliza_deuda_temp_cartera.SegundoApellido',
                'poliza_deuda_temp_cartera.ApellidoCasada',
                'poliza_deuda_temp_cartera.FechaNacimiento',
                'poliza_deuda_temp_cartera.NumeroReferencia',
                'poliza_deuda_temp_cartera.NoValido',
                'poliza_deuda_temp_cartera.Perfiles',
                'poliza_deuda_temp_cartera.Mes',
                'poliza_deuda_temp_cartera.Axo',
                DB::raw("GROUP_CONCAT(DISTINCT poliza_deuda_temp_cartera.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                DB::raw('MAX(poliza_deuda_temp_cartera.EdadDesembloso) as EdadDesembloso'),
                DB::raw('MAX(poliza_deuda_temp_cartera.FechaOtorgamientoDate) as FechaOtorgamiento'),
                'poliza_deuda_temp_cartera.Excluido',
                'poliza_deuda_temp_cartera.OmisionPerfil',
                "poliza_deuda_temp_cartera.saldo_total",
                'pdc.MontoMaximoIndividual as MontoMaximoIndividual'
            )
            ->where('poliza_deuda_temp_cartera.PolizaDeuda', $deuda->Id)
            ->groupBy('poliza_deuda_temp_cartera.Dui', 'poliza_deuda_temp_cartera.Mes', 'poliza_deuda_temp_cartera.Axo')
            ->get();


        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return view('polizas.deuda.respuesta_poliza_recibo', compact(
            'meses',
            'deuda',
            'poliza_cumulos',
            'requisitos',

        ));
    }


    public function store_poliza(Request $request)
    {
        $mes = $request->MesActual; // El formato 'm' devuelve el mes con ceros iniciales (por ejemplo, "02")
        $anio = $request->AxoActual;


        // eliminando datos de la cartera si existieran
        $tempData = PolizaDeudaCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)->where('PolizaDeuda', $request->Deuda)->delete();


        // Obtener los datos de la tabla temporal
        $tempData = PolizaDeudaTempCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('User', auth()->user()->id)
            ->where('NoValido', 0)
            ->where('OmisionPerfil', 1)
            ->where('PolizaDeuda', $request->Deuda)
            ->get();



        $tempDataValidados = PolizaDeudaTempCartera::join('poliza_deuda_validados', 'poliza_deuda_validados.NumeroReferencia', '=', 'poliza_deuda_temp_cartera.NumeroReferencia')
            ->where('poliza_deuda_temp_cartera.Axo', $anio)
            ->where('poliza_deuda_temp_cartera.Mes', $mes + 0)
            ->where('poliza_deuda_temp_cartera.User', auth()->user()->id)
            ->where('poliza_deuda_temp_cartera.OmisionPerfil', 0)
            ->where('NoValido', 0)
            ->where('poliza_deuda_temp_cartera.PolizaDeuda', $request->Deuda)
            ->select('poliza_deuda_temp_cartera.*')
            ->get();



        if (!empty($request->Eliminados)) {
            $eliminadosArray = explode(', ', $request->Eliminados);
        } else {
            $eliminadosArray = []; // Un array vacío si la cadena está vacía
        }

        $eliminados = PolizaDeudaCartera::whereIn('NumeroReferencia', $eliminadosArray)
            ->where('PolizaDeuda', $request->Deuda)
            ->groupBy('NumeroReferencia')
            ->orderBy('Id', 'desc')
            ->get();
        //dd($eliminados);

        if ($eliminados->isNotEmpty()) {
            foreach ($eliminados as $eliminado) {

                $nombreCompleto =
                    ($eliminado->PrimerNombre ?? '') . ' ' .
                    ($eliminado->SegundoNombre ?? '') . ' ' .
                    ($eliminado->PrimerApellido ?? '') . ' ' .
                    ($eliminado->SegundoApellido ?? '') . ' ' .
                    ($eliminado->ApellidoCasada ?? '');

                // Eliminar espacios en exceso (en caso de valores nulos o vacíos)
                $nombreCompleto = trim(preg_replace('/\s+/', ' ', $nombreCompleto));

                $eliminado_obj = new DeudaEliminados();
                $eliminado_obj->Dui = $eliminado->Dui;
                $eliminado_obj->Nombre = $nombreCompleto;
                $eliminado_obj->NumeroReferencia = $eliminado->NumeroReferencia;
                $eliminado_obj->Poliza = $eliminado->PolizaDeuda;
                $eliminado_obj->Mes = $mes;
                $eliminado_obj->Usuario = auth()->user()->id;
                $eliminado_obj->save();
            }
        }


        if ($tempData->isNotEmpty()) {
            $linea_credito = $tempData->first()->LineaCredito;
            $poliza_deuda = $tempData->first()->PolizaDeuda;
            $mes_int = intval($mes);
            PolizaDeudaCartera::where('PolizaDeuda', $poliza_deuda)->where('LineaCredito', $linea_credito)->where('Axo', $anio)->where('Mes', $mes_int)->delete();
        }



        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {

            try {
                $poliza = new PolizaDeudaCartera();
                $poliza->CarnetResidencia = $tempRecord->CarnetResidencia;
                $poliza->Dui = $tempRecord->Dui;
                $poliza->Pasaporte = $tempRecord->Pasaporte;
                $poliza->Nacionalidad = $tempRecord->Nacionalidad;
                $poliza->FechaNacimiento = $tempRecord->FechaNacimiento;
                $poliza->TipoPersona = $tempRecord->TipoPersona;
                $poliza->PrimerApellido = $tempRecord->PrimerApellido;
                $poliza->SegundoApellido = $tempRecord->SegundoApellido;
                $poliza->ApellidoCasada = $tempRecord->ApellidoCasada;
                $poliza->PrimerNombre = $tempRecord->PrimerNombre;
                $poliza->SegundoNombre = $tempRecord->SegundoNombre;
                $poliza->NombreSociedad = $tempRecord->NombreSociedad;
                $poliza->Sexo = $tempRecord->Sexo;
                $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento;
                $poliza->FechaVencimiento = $tempRecord->FechaVencimiento;
                $poliza->NumeroReferencia = $tempRecord->NumeroReferencia;
                $poliza->MontoOtorgado = $tempRecord->MontoOtorgado;
                $poliza->SaldoCapital = $tempRecord->SaldoCapital;
                $poliza->Intereses = $tempRecord->Intereses;
                $poliza->InteresesCovid = $tempRecord->InteresesCovid;
                $poliza->InteresesMoratorios = $tempRecord->InteresesMoratorios;
                $poliza->MontoNominal = $tempRecord->MontoNominal;
                $poliza->User = $tempRecord->User;
                $poliza->Axo = $tempRecord->Axo;
                $poliza->Mes = $tempRecord->Mes;
                $poliza->PolizaDeuda = $tempRecord->PolizaDeuda;
                $poliza->FechaInicio = $tempRecord->FechaInicio;
                $poliza->FechaFinal = $tempRecord->FechaFinal;
                $poliza->TipoError = $tempRecord->TipoError;
                $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate;
                $poliza->Edad = $tempRecord->Edad;
                $poliza->EdadDesembloso = $tempRecord->EdadDesembloso;
                $poliza->LineaCredito = $tempRecord->LineaCredito;
                $poliza->NoValido = $tempRecord->NoValido;
                $poliza->PolizaDeudaTipoCartera = $tempRecord->PolizaDeudaTipoCartera;
                $poliza->Tasa = $tempRecord->Tasa;
                $poliza->TotalCredito = $tempRecord->TotalCredito;
                $poliza->FechaOtorgamientoDate = $tempRecord->FechaOtorgamientoDate;

                $poliza->TipoDeuda = $tempRecord->TipoDeuda;
                $poliza->PorcentajeExtraprima = $tempRecord->PorcentajeExtraprima;
                $poliza->TipoDocumento = $tempRecord->TipoDocumento;
                $poliza->SaldoInteresMora = $tempRecord->SaldoInteresMora;

                $poliza->save();
            } catch (\Exception $e) {
                // Captura errores y los guarda en el log
                Log::error("Error al insertar en poliza_deuda_cartera: " . $e->getMessage(), [
                    'NumeroReferencia' => $tempRecord->NumeroReferencia,
                    'Usuario' => auth()->user()->id ?? 'N/A',
                    'Datos' => $tempRecord
                ]);
            }
        }


        foreach ($tempDataValidados as $tempRecordV) {
            try {
                $poliza = new PolizaDeudaCartera();
                $poliza->CarnetResidencia = $tempRecordV->CarnetResidencia;
                $poliza->Dui = $tempRecordV->Dui;
                $poliza->Pasaporte = $tempRecordV->Pasaporte;
                $poliza->Nacionalidad = $tempRecordV->Nacionalidad;
                $poliza->FechaNacimiento = $tempRecordV->FechaNacimiento;
                $poliza->TipoPersona = $tempRecordV->TipoPersona;
                $poliza->PrimerApellido = $tempRecordV->PrimerApellido;
                $poliza->SegundoApellido = $tempRecordV->SegundoApellido;
                $poliza->ApellidoCasada = $tempRecordV->ApellidoCasada;
                $poliza->PrimerNombre = $tempRecordV->PrimerNombre;
                $poliza->SegundoNombre = $tempRecordV->SegundoNombre;
                $poliza->NombreSociedad = $tempRecordV->NombreSociedad;
                $poliza->Sexo = $tempRecordV->Sexo;
                $poliza->FechaOtorgamiento = $tempRecordV->FechaOtorgamiento;
                $poliza->FechaVencimiento = $tempRecordV->FechaVencimiento;
                $poliza->NumeroReferencia = $tempRecordV->NumeroReferencia;
                $poliza->MontoOtorgado = $tempRecordV->MontoOtorgado;
                $poliza->SaldoCapital = $tempRecordV->SaldoCapital;
                $poliza->Intereses = $tempRecordV->Intereses;
                $poliza->InteresesCovid = $tempRecordV->InteresesCovid;
                $poliza->InteresesMoratorios = $tempRecordV->InteresesMoratorios;
                $poliza->MontoNominal = $tempRecordV->MontoNominal;
                $poliza->User = $tempRecordV->User;
                $poliza->Axo = $tempRecordV->Axo;
                $poliza->Mes = $tempRecordV->Mes;
                $poliza->PolizaDeuda = $tempRecordV->PolizaDeuda;
                $poliza->FechaInicio = $tempRecordV->FechaInicio;
                $poliza->FechaFinal = $tempRecordV->FechaFinal;
                $poliza->TipoError = $tempRecordV->TipoError;
                $poliza->FechaNacimientoDate = $tempRecordV->FechaNacimientoDate;
                $poliza->Edad = $tempRecordV->Edad;
                $poliza->EdadDesembloso = $tempRecordV->EdadDesembloso;
                $poliza->LineaCredito = $tempRecordV->LineaCredito;
                $poliza->NoValido = $tempRecordV->NoValido;
                $poliza->PolizaDeudaTipoCartera = $tempRecordV->PolizaDeudaTipoCartera;
                $poliza->Tasa = $tempRecordV->Tasa;
                $poliza->TotalCredito = $tempRecordV->TotalCredito;
                $poliza->FechaOtorgamientoDate = $tempRecordV->FechaOtorgamientoDate;

                $poliza->TipoDeuda = $tempRecordV->TipoDeuda;
                $poliza->PorcentajeExtraprima = $tempRecordV->PorcentajeExtraprima;
                $poliza->TipoDocumento = $tempRecordV->TipoDocumento;
                $poliza->SaldoInteresMora = $tempRecordV->SaldoInteresMora;
                $poliza->save();
            } catch (\Exception $e) {
                // Captura errores y los guarda en el log
                Log::error("Error al insertar en poliza_deuda_cartera2: " . $e->getMessage(), [
                    'NumeroReferencia' => $tempRecordV->NumeroReferencia,
                    'Usuario' => auth()->user()->id ?? 'N/A',
                    'Datos' => $tempRecordV
                ]);
            }
        }

        PolizaDeudaTempCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('PolizaDeuda', $request->Deuda)->delete();

        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/deuda/' . $request->Deuda . '/edit?tab=2');
    }

    public function store_poliza_recibo(Request $request)
    {


        // Obtener los datos de la tabla temporal
        $tempData = PolizaDeudaTempCartera::where('NoValido', 0)
            ->where('PolizaDeuda', $request->Deuda)
            ->get();

        //dd($tempData);

        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {
            try {
                $poliza = new PolizaDeudaCartera();
                $poliza->Nit = $tempRecord->Nit;
                $poliza->Dui = $tempRecord->Dui;
                $poliza->Pasaporte = $tempRecord->Pasaporte;
                $poliza->Nacionalidad = $tempRecord->Nacionalidad;
                $poliza->FechaNacimiento = $tempRecord->FechaNacimiento;
                $poliza->TipoPersona = $tempRecord->TipoPersona;
                $poliza->PrimerApellido = $tempRecord->PrimerApellido;
                $poliza->SegundoApellido = $tempRecord->SegundoApellido;
                $poliza->ApellidoCasada = $tempRecord->ApellidoCasada;
                $poliza->PrimerNombre = $tempRecord->PrimerNombre;
                $poliza->SegundoNombre = $tempRecord->SegundoNombre;
                $poliza->NombreSociedad = $tempRecord->NombreSociedad;
                $poliza->Sexo = $tempRecord->Sexo;
                $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento;
                $poliza->FechaVencimiento = $tempRecord->FechaVencimiento;
                $poliza->Ocupacion = $tempRecord->Ocupacion;
                $poliza->NumeroReferencia = $tempRecord->NumeroReferencia;
                $poliza->MontoOtorgado = $tempRecord->MontoOtorgado;
                $poliza->SaldoCapital = $tempRecord->SaldoCapital;
                $poliza->Intereses = $tempRecord->Intereses;
                $poliza->InteresesCovid = $tempRecord->InteresesCovid;
                $poliza->InteresesMoratorios = $tempRecord->InteresesMoratorios;
                $poliza->MontoNominal = $tempRecord->MontoNominal;
                $poliza->SaldoTotal = $tempRecord->SaldoTotal;
                $poliza->User = $tempRecord->User;
                $poliza->Axo = $tempRecord->Axo;
                $poliza->Mes = $tempRecord->Mes;
                $poliza->PolizaDeuda = $tempRecord->PolizaDeuda;
                $poliza->FechaInicio = $tempRecord->FechaInicio;
                $poliza->FechaFinal = $tempRecord->FechaFinal;
                $poliza->TipoError = $tempRecord->TipoError;
                $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate;
                $poliza->Edad = $tempRecord->Edad;
                $poliza->LineaCredito = $tempRecord->LineaCredito;
                $poliza->NoValido = $tempRecord->NoValido;
                $poliza->save();
            } catch (\Exception $e) {
                // Captura errores y los guarda en el log
                Log::error("Error al insertar en poliza_deuda_cartera: " . $e->getMessage(), [
                    'NumeroReferencia' => $tempRecord->NumeroReferencia,
                    'Usuario' => auth()->user()->id ?? 'N/A',
                    'Datos' => $tempRecord
                ]);
            }
        }

        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/deuda/' . $request->Deuda . '/edit?tab=2');
    }


    public function primera_carga(Request $request)
    {
        $mes = $request->MesActual;
        $anio = $request->AxoActual;


        // eliminando datos de la cartera si existieran
        $tempData = PolizaDeudaCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)->where('PolizaDeuda', $request->Deuda)->delete();


        // Obtener los datos de la tabla temporal
        $tempData = PolizaDeudaTempCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('PolizaDeuda', $request->Deuda)
            ->get();


        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {

            try {
                $poliza = new PolizaDeudaCartera();
                $poliza->CarnetResidencia = $tempRecord->CarnetResidencia;
                $poliza->Dui = $tempRecord->Dui;
                $poliza->Pasaporte = $tempRecord->Pasaporte;
                $poliza->Nacionalidad = $tempRecord->Nacionalidad;
                $poliza->FechaNacimiento = $tempRecord->FechaNacimiento;
                $poliza->TipoPersona = $tempRecord->TipoPersona;
                $poliza->PrimerApellido = $tempRecord->PrimerApellido;
                $poliza->SegundoApellido = $tempRecord->SegundoApellido;
                $poliza->ApellidoCasada = $tempRecord->ApellidoCasada;
                $poliza->PrimerNombre = $tempRecord->PrimerNombre;
                $poliza->SegundoNombre = $tempRecord->SegundoNombre;
                $poliza->NombreSociedad = $tempRecord->NombreSociedad;
                $poliza->Sexo = $tempRecord->Sexo;
                $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento;
                $poliza->FechaVencimiento = $tempRecord->FechaVencimiento;
                $poliza->NumeroReferencia = $tempRecord->NumeroReferencia;
                $poliza->MontoOtorgado = $tempRecord->MontoOtorgado;
                $poliza->SaldoCapital = $tempRecord->SaldoCapital;
                $poliza->Intereses = $tempRecord->Intereses;
                $poliza->InteresesCovid = $tempRecord->InteresesCovid;
                $poliza->InteresesMoratorios = $tempRecord->InteresesMoratorios;
                $poliza->MontoNominal = $tempRecord->MontoNominal;
                $poliza->User = $tempRecord->User;
                $poliza->Axo = $tempRecord->Axo;
                $poliza->Mes = $tempRecord->Mes;
                $poliza->PolizaDeuda = $tempRecord->PolizaDeuda;
                $poliza->FechaInicio = $tempRecord->FechaInicio;
                $poliza->FechaFinal = $tempRecord->FechaFinal;
                $poliza->TipoError = $tempRecord->TipoError;
                $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate;
                $poliza->Edad = $tempRecord->Edad;
                $poliza->EdadDesembloso = $tempRecord->EdadDesembloso;
                $poliza->LineaCredito = $tempRecord->LineaCredito;
                $poliza->NoValido = $tempRecord->NoValido;
                $poliza->PolizaDeudaTipoCartera = $tempRecord->PolizaDeudaTipoCartera;
                $poliza->Tasa = $tempRecord->Tasa;
                $poliza->TotalCredito = $tempRecord->TotalCredito;
                $poliza->FechaOtorgamientoDate = $tempRecord->FechaOtorgamientoDate;

                $poliza->TipoDeuda = $tempRecord->TipoDeuda;
                $poliza->PorcentajeExtraprima = $tempRecord->PorcentajeExtraprima;
                $poliza->TipoDocumento = $tempRecord->TipoDocumento;
                $poliza->SaldoInteresMora = $tempRecord->SaldoInteresMora;

                $poliza->save();
            } catch (\Exception $e) {
                // Captura errores y los guarda en el log
                Log::error("Error al insertar en poliza_deuda_cartera: " . $e->getMessage(), [
                    'NumeroReferencia' => $tempRecord->NumeroReferencia,
                    'Usuario' => auth()->user()->id ?? 'N/A',
                    'Datos' => $tempRecord
                ]);
            }
        }


        PolizaDeudaTempCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('PolizaDeuda', $request->Deuda)->delete();

        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/deuda/' . $request->Deuda . '/edit?tab=2');
    }




    public function exportar_excel(Request $request)
    {
        $deuda = Deuda::findOrFail($request->Deuda);
        //edad maxima
        $tipo = $request->Tipo;
        $mes = $request->MesActual;
        if ($tipo == 1) {
            //edad maxima
            // $excluidos = DeudaExcluidos::where('Poliza', $deuda->Id)->where('EdadMaxima', 1)->whereMonth('FechaExclusion', $mes)->where('Activo', 0)->get();
            $excluidos = PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('Edad', '>=', $deuda->EdadMaximaTerminacion)->get();
        } else {
            //$excluidos = DeudaExcluidos::where('Poliza', $deuda->Id)->where('ResponsabilidadMaxima', 1)->whereMonth('FechaExclusion', $mes)->where('Activo', 0)->get();
            $excluidos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,Excluido,
        NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(saldo_total) as total_saldo')
                ->where('PolizaDeuda', $deuda->Id)
                ->groupBy('Dui', 'NoValido')->get();
        }

        return Excel::download(new ExcluidosExport($excluidos, $tipo, $mes, $deuda), 'Clientes Excluidos.xlsx');
    }

    public function aumentar_techo(Request $request)
    {
        $deuda = Deuda::findOrFail($request->Deuda);
        $deuda->ResponsabilidadMaxima = $request->ResponsabilidadMaxima;
        $deuda->update();

        alert()->success('El registro de poliza ha sido modificado correctamente');
        return back();
    }
}
