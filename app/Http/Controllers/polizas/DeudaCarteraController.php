<?php

namespace App\Http\Controllers\polizas;

use App\Exports\EdadMaximaExport;
use App\Exports\ExcluidosExport;
use App\Http\Controllers\Controller;
use App\Imports\PolizaDeudaCarteraImport;
use App\Imports\PolizaDeudaTempCarteraImport;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\DeudaCreditosValidos;
use App\Models\polizas\DeudaDetalle;
use App\Models\polizas\DeudaEliminados;
use App\Models\polizas\DeudaExcluidos;
use App\Models\polizas\DeudaRequisitos;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class DeudaCarteraController extends Controller
{

    public function subir_cartera($id)
    {

        $deuda = Deuda::findOrFail($id);
        $linea_credito = DeudaCredito::where('Deuda', $id)->where('Activo', 1)->get();


        foreach ($linea_credito as $linea) {
            $total = PolizaDeudaTempCartera::where('LineaCredito', $linea->Id)->where('User', auth()->user()->id)->sum('saldo_total');
            $linea->Total = $total;
        }

        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $ultimo_pago = DeudaDetalle::where('Deuda', $deuda->Id)->where('Activo', 1)->orderBy('Id', 'desc')->first();



        //inicializando valores
        // Primer día del mes anterior
        $fecha_inicial = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        // Primer día del mes actual
        $fecha_final = Carbon::now()->startOfMonth()->format('Y-m-d');



        //dd($registro_cartera);

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
        $registro_cartera = PolizaDeudaTempCartera::where('PolizaDeuda', $id)->where('User', auth()->user()->id)->first();

        if ($registro_cartera) {
            $axo = $registro_cartera->Axo;
            $mes = $registro_cartera->Mes + 0;
        }



        return view('polizas.deuda.subir_archivos', compact(
            'deuda',
            'linea_credito',
            'meses',
            'fecha_inicial',
            'fecha_final',
            'axo',
            'mes'
        ));
    }



    public function create_pago(Request $request)
    {

        $credito = $request->get('LineaCredito');
        $deuda = Deuda::findOrFail($request->Id);

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



        try {
            $archivo = $request->Archivo;

            $excel = IOFactory::load($archivo);

            // Verifica si hay al menos dos hojas
            $sheetsCount = $excel->getSheetCount();

            if ($sheetsCount > 1) {
                // El archivo tiene al menos dos hojas
                alert()->error('La cartera solo puede contener un solo libro de Excel (sheet)');
                return back();
            }

            PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('LineaCredito', '=', $credito)->delete();
            Excel::import(new PolizaDeudaTempCarteraImport($date->year, $date->month, $deuda->Id, $request->FechaInicio, $request->FechaFinal, $credito), $archivo);
        } catch (Throwable $e) {
            alert()->error('Problema al procesar el archivo excel');
            return back();
        }




        //calculando errores de cartera
        $cartera_temp = PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('LineaCredito', '=', $credito)->get();





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
                if ($obj->Nacionalidad == 'SAL' || $obj->Nacionalidad == 'Sal' || $obj->Nacionalidad == 'sal') {
                    $validador_dui = $this->validarDocumento($obj->Dui, "dui");

                    if ($validador_dui == false) {
                        $obj->TipoError = 2;
                        $obj->update();

                        array_push($errores_array, 2);
                    }
                } else {
                    if ($obj->Pasaporte == null || $obj->Pasaporte == '') {
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

            $obj->saldo_total = $obj->calculoTodalSaldo();
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

            $obj->Errores = $errores_array;
        }



        $data_error = $cartera_temp->where('TipoError', '<>', 0);

        if ($data_error->count() > 0) {
            return view('polizas.deuda.respuesta_poliza_error', compact('data_error', 'deuda', 'credito'));
        }

        /*
        // Convertir la cadena en un objeto Carbon (la clase de fecha en Laravel)
        $fecha = \Carbon\Carbon::parse($date);

        // Obtener el mes y el año
        $mes = $fecha->format('m'); // El formato 'm' devuelve el mes con ceros iniciales (por ejemplo, "02")
        $anio = $fecha->format('Y');



        // Obtener los datos de la tabla temporal
        $tempData = PolizaDeudaTempCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('User', auth()->user()->id)
            ->where('NoValido', 0)
            ->where('LineaCredito', '=', $credito)
            ->get();

        //dd($tempData->take(20));*/

        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();


        return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
    }


    public function deleteLineaCredito(Request $request)
    {

        PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('LineaCredito', '=', $request->LineaCredito)->delete();

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


    public function limpiarNombre($nombre)
    {
        // Eliminar espacios en blanco y números
        $nombreLimpio = preg_replace('/[\s\d]+/', '', $nombre);

        return $nombreLimpio;
    }

    public function add_excluidos(Request $request)
    {
        $registro =  PolizaDeudaTempCartera::findOrFail($request->id);
        $deuda = Deuda::findOrFail($registro->PolizaDeuda);
        $registro->NoValido = 1;


        $ex_existe = DeudaExcluidos::where('NumeroReferencia', $registro->NumeroReferencia)->first();
        //$sub_total = $deuda->total_saldo + $deuda->total_interes + $deuda->total_covid + $deuda->total_moratorios + $deuda->total_monto_nominal;
        $val = $request->val;
        $sub_total = $request->subtotal;

        if ($ex_existe) {
            if ($val == 1) {
                $ex_existe->Edad = $registro->Edad;
                $ex_existe->EdadMaxima = 1;
            }
            if ($val == 0) {
                $excluidos = DeudaExcluidos::whereMonth('FechaExclusion', $registro->Mes)->where('Poliza', $deuda->Id)->get();
                foreach ($excluidos as $obj) {
                    if ($sub_total < $deuda->ResponsabilidadMaxima && $obj->ResponsabilidadMaxima == 1 && $obj->EdadMaxima == null) {

                        if ($obj->Dui == $registro->Dui) {
                            $obj->Activo = 1;
                            $obj->update();

                            $registro->NoValido = 0;
                            $registro->update();
                        }
                    }
                }
                $ex_existe->Responsabilidad = $sub_total;
                $ex_existe->ResponsabilidadMaxima = 1;
            }
            $ex_existe->update();
            $id = $ex_existe->Id;
        } else {
            $excluidos = new DeudaExcluidos();
            $excluidos->Dui = $registro->Dui;
            $excluidos->Nombre = $registro->PrimerNombre . ' ' . $registro->SegundoNombre . ' ' . $registro->PrimerApellido . ' ' . $registro->SegundoApellido . ' ' . $registro->ApellidoCasada;
            $excluidos->NumeroReferencia = $registro->NumeroReferencia;
            $excluidos->Poliza = $registro->PolizaDeuda;
            $excluidos->FechaExclusion = Carbon::now('America/El_Salvador');
            $excluidos->Usuario = auth()->user()->id;
            if ($val == 1) {
                $excluidos->Edad = $registro->Edad;
                $excluidos->EdadMaxima = 1;
            }
            if ($val == 0) {
                $excluidos->Responsabilidad = $sub_total;
                $excluidos->ResponsabilidadMaxima = 1;
            }
            $excluidos->save();
            $id = $excluidos->Id;
        }
        $registro->Excluido = $id;
        $registro->update();



        $excuidos = DeudaExcluidos::where('Poliza', $registro->PolizaDeuda)->get();

        $excuidos_array = [];
        if ($excuidos) {
            $excuidos_array = $excuidos->pluck('NumeroReferencia')->toArray();
        }
        //dd($excuidos);

        $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $registro->PolizaDeuda)->where('User', auth()->user()->id)
            ->where('Edad', '>=', $deuda->EdadMaximaTerminacion)
            ->whereNotIn('NumeroReferencia', $excuidos_array)->get();

        $conteo_excluidos = $poliza_temporal->count();

        return response()->json(['excluido' => $id, 'conteo_excluidos' => $conteo_excluidos]);
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

        $temp_data_fisrt = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->where('User', auth()->user()->id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }


        $requisitos = $deuda->requisitos;

        if ($requisitos->count() == 0) {
            alert()->error('No se han definido requisitos minimos de asegurabilidad');
            $deuda->Configuracion = 0;
            $deuda->update();
            session(['tab' => 3]);
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
            ->where('User', auth()->user()->id)
            ->update(['Rehabilitado' => 0]);


        //calcular rehabilitados
        $datos_rehabilitado = PolizaDeudaTempCartera::leftJoin(
            DB::raw('(
                SELECT DISTINCT NumeroReferencia
                FROM poliza_deuda_cartera
                WHERE Mes = ' . $mesAnterior . ' AND Axo = ' . $axoAnterior . '
            ) AS valid_references'),
            'poliza_deuda_temp_cartera.NumeroReferencia',
            '=',
            'valid_references.NumeroReferencia'
        )
            ->where('poliza_deuda_temp_cartera.User', auth()->user()->id)
            ->whereNull('valid_references.NumeroReferencia')
            ->select('poliza_deuda_temp_cartera.*') // Selecciona las columnas de la tabla principal
            ->get();



        foreach ($datos_rehabilitado  as $dato) {
            $habilitar = $dato->creditoRehabilitado($dato->NumeroReferencia, $temp_data_fisrt->Axo, $temp_data_fisrt->Mes);

            if ($habilitar == 1) {
                $dato->Rehabilitado = 1;
                $dato->save();
            }
        }



        //estableciendo fecha de nacimiento date y calculando edad
        PolizaDeudaTempCartera::where('User', auth()->user()->id)
            ->where('PolizaDeuda', $poliza_id)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                //'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);





        //calcular los registros que pasan de la edad maxima
        $poliza_edad_maxima = PolizaDeudaTempCartera::where('PolizaDeuda', $request->Deuda)->where('User', auth()->user()->id)
            ->where('Edad', '>', $deuda->EdadMaximaTerminacion)->get();


        //$deuda->ResponsabilidadMaxima = 25000;
        //calcular los registros que pasan de la responsabilidad maxima
        $poliza_responsabilidad_maxima = PolizaDeudaTempCartera::selectRaw('Id,Dui,NumeroReferencia,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,Excluido,NoValido,saldo_total,EdadDesembloso,Excluido')
            ->having('saldo_total', '>', $deuda->ResponsabilidadMaxima)
            ->where('PolizaDeuda', $request->Deuda)->where('User', auth()->user()->id)->get();



        //registros que no existen en el mes anterior
        $count_data_cartera = PolizaDeudaCartera::where('PolizaDeuda', $poliza_id)->count();
        if ($count_data_cartera > 0) {
            //dd($mesAnterior,$axoAnterior,$request->Deuda);
            $registros_eliminados = DB::table('poliza_deuda_cartera AS pdc')
                ->leftJoin('poliza_deuda_temp_cartera AS pdtc', function ($join) {
                    $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia')
                        ->where('pdtc.User', auth()->user()->id);
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
            ->where('poliza_deuda_temp_cartera.User', auth()->user()->id) // Filtra por el usuario autenticado
            ->where('poliza_deuda_temp_cartera.PolizaDeuda', $request->Deuda)
            ->whereNull('valid_references.NumeroReferencia') // Los registros que no coinciden
            ->select('poliza_deuda_temp_cartera.*') // Selecciona columnas de la tabla principal
            ->get();



        $extra_primados = $deuda->extra_primados;

        foreach ($extra_primados as $extra_primado) {
            $extra_primado->Existe = PolizaDeudaTempCartera::where('NumeroReferencia', $extra_primado->NumeroReferencia)->count();
        }



        //cumulos por dui
        $poliza_cumulos = PolizaDeudaTempCartera::get();

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
            $data_dui_cartera = $poliza_cumulos->where('Edad', '>=', $requisito->EdadInicial)->where('Edad', '<=', $requisito->EdadFinal)
                ->where('saldo_total', '>=', $requisito->MontoInicial)->where('saldo_total', '<=', $requisito->MontoFinal)
                ->pluck('Dui')->toArray();

            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->whereIn('Dui', $data_dui_cartera)
                ->update([
                    'Perfiles' => DB::raw(
                        'IF(Perfiles IS NULL OR Perfiles = "","' . $requisito->perfil->Descripcion . '", CONCAT(Perfiles, ",","' . $requisito->perfil->Descripcion . '"))'
                    ),
                    'OmisionPerfil' =>   $requisito->OmicionPerfil
                    //,'NoValido' =>   $requisito->NoValido
                ]);
        }


        //inicializamos los no validos a cero
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
            ->update(['NoValido' => 0]);

        $edades = DB::table('poliza_deuda_requisitos')
            ->where('Deuda', $request->Deuda)
            ->selectRaw('MIN(EdadInicial) as EdadInicial, MAX(EdadFinal) as EdadFinal,MIN(MontoInicial) as MontoInicial,MAX(MontoFinal) as MontoFinal')
            ->first();
        if ($edades) {
            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->where('Edad', '<', $edades->EdadInicial)
                ->orWhere('Edad', '>', $edades->EdadFinal)
                ->update(['NoValido' => 1]);

            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->where('saldo_total', '>', $edades->MontoFinal)
                ->update(['NoValido' => 1]);
        }

        $novalidos = PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('NoValido', 1)->get();
        //dd($novalidos);

        //dd($requisitos->take(10));
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




        return view('polizas.deuda.respuesta_poliza', compact(
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
            //'conteo_excluidos',
            //'excluidos',
            // 'poliza_temporal',
            // 'maxEdadMaxima',
            // 'nuevos_registros',

            // 'poliza_cumulos',
            //'date_anterior',
            //'date',
            // 'extra_primados',
            // 'requisitos',

        ));
    }






    public function store_poliza(Request $request)
    {


        $mes = $request->MesActual; // El formato 'm' devuelve el mes con ceros iniciales (por ejemplo, "02")
        $anio = $request->AxoActual;


         // eliminando datos de la cartera si existieran
         $tempData = PolizaDeudaCartera::where('Axo', $anio)
         ->where('Mes', $mes + 0) ->where('PolizaDeuda', $request->Deuda)->delete();


        // Obtener los datos de la tabla temporal
        $tempData = PolizaDeudaTempCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('User', auth()->user()->id)
            ->where('NoValido', 0)
            ->where('OmisionPerfil', 1)
            ->where('PolizaDeuda', $request->Deuda)
            ->get();



        $tempDataValidados = PolizaDeudaTempCartera::
            join('poliza_deuda_validados', 'poliza_deuda_validados.NumeroReferencia', '=', 'poliza_deuda_temp_cartera.NumeroReferencia')
            ->where('poliza_deuda_temp_cartera.Axo', $anio)
            ->where('poliza_deuda_temp_cartera.Mes',$mes + 0)
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
                //$poliza->Id = $tempRecord->Id;
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


        foreach ($tempDataValidados as $tempRecordV) {
            try {
                $poliza = new PolizaDeudaCartera();
                $poliza->Nit = $tempRecordV->Nit;
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
                $poliza->Ocupacion = $tempRecordV->Ocupacion;
                $poliza->NumeroReferencia = $tempRecordV->NumeroReferencia;
                $poliza->MontoOtorgado = $tempRecordV->MontoOtorgado;
                $poliza->SaldoCapital = $tempRecordV->SaldoCapital;
                $poliza->Intereses = $tempRecordV->Intereses;
                $poliza->InteresesCovid = $tempRecordV->InteresesCovid;
                $poliza->InteresesMoratorios = $tempRecordV->InteresesMoratorios;
                $poliza->MontoNominal = $tempRecordV->MontoNominal;
                $poliza->SaldoTotal = $tempRecordV->SaldoTotal;
                $poliza->User = $tempRecordV->User;
                $poliza->Axo = $tempRecordV->Axo;
                $poliza->Mes = $tempRecordV->Mes;
                $poliza->PolizaDeuda = $tempRecordV->PolizaDeuda;
                $poliza->FechaInicio = $tempRecordV->FechaInicio;
                $poliza->FechaFinal = $tempRecordV->FechaFinal;
                $poliza->TipoError = $tempRecordV->TipoError;
                $poliza->FechaNacimientoDate = $tempRecordV->FechaNacimientoDate;
                $poliza->Edad = $tempRecordV->Edad;
                $poliza->LineaCredito = $tempRecordV->LineaCredito;
                $poliza->NoValido = $tempRecordV->NoValido;
                $poliza->save();
            } catch (\Exception $e) {
                // Captura errores y los guarda en el log
                Log::error("Error al insertar en poliza_deuda_cartera: " . $e->getMessage(), [
                    'NumeroReferencia' => $tempRecordV->NumeroReferencia,
                    'Usuario' => auth()->user()->id ?? 'N/A',
                    'Datos' => $tempRecordV
                ]);
            }
        }



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
            $excluidos = PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('Edad', '>=', $deuda->EdadMaximaTerminacion)->where('User', auth()->user()->id)->get();
        } else {
            //$excluidos = DeudaExcluidos::where('Poliza', $deuda->Id)->where('ResponsabilidadMaxima', 1)->whereMonth('FechaExclusion', $mes)->where('Activo', 0)->get();
            $excluidos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,Excluido,
        NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(saldo_total) as total_saldo')
                ->where('User', auth()->user()->id)->where('PolizaDeuda', $deuda->Id)
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
