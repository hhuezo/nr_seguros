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
use App\Models\polizas\DeudaExcluidos;
use App\Models\polizas\DeudaRequisitos;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $total = PolizaDeudaTempCartera::where('LineaCredito', $linea->Id)->sum('saldo_total');
            $linea->Total = $total;
        }

        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $ultimo_pago = DeudaDetalle::where('Deuda', $deuda->Id)->where('Activo', 1)->orderBy('Id', 'desc')->first();
        $ultimo_pago_fecha_final = null;
        if ($ultimo_pago) {
            $fecha_inicial = Carbon::parse($ultimo_pago->FechaFinal);
            $fecha_final_temp = $fecha_inicial->addMonth();
            $ultimo_pago_fecha_final = $fecha_final_temp->format('Y-m-d');
            $fecha1 = PolizaDeudaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
                ->where('PolizaDeuda', '=', $id)
                ->where('PolizaDeudaDetalle', '<>', $ultimo_pago->Id)
                ->orderByDesc('Id')->first();
        } else {
            $ultimo_pago = '';
            $fecha1 = null;
        }
        $primerDia = Carbon::now()->startOfMonth();
        $ultimoDia = Carbon::now()->endOfMonth();


        return view('polizas.deuda.subir_archivos', compact('primerDia', 'ultimoDia', 'deuda', 'linea_credito', 'meses', 'ultimo_pago', 'ultimo_pago_fecha_final'));
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
                $validador_dui = $this->validarDocumento($obj->Dui, "dui");

                if ($validador_dui == false) {
                    $obj->TipoError = 2;
                    $obj->update();

                    array_push($errores_array, 2);
                }
            }

            $obj->saldo_total = $obj->calculoTodalSaldo();
            $obj->update();


            /*
                //se limpia el nombre completo de espacios en blanco y numeros
                $obj->PrimerApellido = $this->limpiarNombre($obj->PrimerApellido);
                $obj->SegundoApellido = $this->limpiarNombre($obj->SegundoApellido);
                $obj->ApellidoCasada = $this->limpiarNombre($obj->ApellidoCasada);
                $obj->PrimerNombre = $this->limpiarNombre($obj->PrimerNombre);
                $obj->SegundoNombre = $this->limpiarNombre($obj->SegundoNombre);
                */

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
                $validador_fecha_otorgamiento = $this->validarFormatoFecha($fecha_excel_convertida_otorgamiento);

                if ($validador_fecha_otorgamiento == false || trim($obj->FechaOtorgamiento) == "") {
                    $obj->TipoError = 5;
                    $obj->update();

                    array_push($errores_array, 5);
                } else {
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
            return view('polizas.deuda.respuesta_poliza_error', compact('data_error', 'deuda'));
        }


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

        //dd($tempData->take(20));

        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();


        // return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
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
    { //función para convertir fechas  excel a fechas unix(que reconoce php)
        try {

            $unixDate = (intval($dateValue) - 25569) * 86400;
            return $unixDate = gmdate("d/m/Y", $unixDate);
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

    public function add_excluidos($deuda, $val)
    {
        $ex_existe = DeudaExcluidos::where('NumeroReferencia', $deuda->NumeroReferencia)->first();
        $sub_total = $deuda->total_saldo + $deuda->total_interes + $deuda->total_covid + $deuda->total_moratorios + $deuda->total_monto_nominal;

        if ($ex_existe) {
            if ($val == 1) {
                $ex_existe->Edad = $deuda->Edad;
                $ex_existe->EdadMaxima = 1;
            }
            if ($val == 0) {

                $ex_existe->Responsabilidad = $sub_total;
                $ex_existe->ResponsabilidadMaxima = 1;
            }
            $ex_existe->update();
        } else {
            $excluidos = new DeudaExcluidos();
            $excluidos->Dui = $deuda->Dui;
            $excluidos->Nombre = $deuda->PrimerNombre . ' ' . $deuda->SegundoNombre . ' ' . $deuda->PrimerApellido . ' ' . $deuda->SegundoApellido . ' ' . $deuda->ApellidoCasada;
            $excluidos->NumeroReferencia = $deuda->NumeroReferencia;
            $excluidos->Poliza = $deuda->PolizaDeuda;
            $excluidos->FechaExclusion = Carbon::now('America/El_Salvador');
            $excluidos->Usuario = auth()->user()->id;
            if ($val == 1) {
                $excluidos->Edad = $deuda->Edad;
                $excluidos->EdadMaxima = 1;
            }
            if ($val == 0) {
                $excluidos->Responsabilidad = $sub_total;
                $excluidos->ResponsabilidadMaxima = 1;
            }
            $excluidos->save();
        }
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
        $date_submes = Carbon::create($temp_data_fisrt->Axo, $temp_data_fisrt->Mes, "01");
        $date = Carbon::create($temp_data_fisrt->Axo, $temp_data_fisrt->Mes, "01");
        $date_mes = $date_submes->subMonth();
        $date_anterior = Carbon::create($temp_data_fisrt->Axo, $temp_data_fisrt->Mes, "01");
        $date_mes_anterior = $date_anterior->subMonth();







        //estableciendo fecha de nacimiento date y calculando edad
        PolizaDeudaTempCartera::where('User', auth()->user()->id)
            ->where('PolizaDeuda', $poliza_id)
            ->update([
                'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);


        $requisitos = $deuda->requisitos;

        if ($requisitos->count() == 0) {
            alert()->error('No se han definido requisitos minimos de asegurabilidad');
            $deuda->Configuracion = 0;
            $deuda->update();
            session(['tab' => 3]);
            return redirect('polizas/deuda/' . $deuda->Id);
        }


        //cumulos por dui
        $poliza_cumulos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,NoValido
        NumeroReferencia,SUM(SaldoCapital) as total_saldo,SUM(Intereses) as total_interes,SUM(InteresesCovid) as total_covid,
        SUM(InteresesMoratorios) as total_moratorios, SUM(MontoNominal) as total_monto_nominal')->groupBy('Dui')->get();




        //definiendo edad maxima segu requisitos
        //
        $maxEdadMaxima = $deuda->requisitos->max('EdadFinal');
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('Edad', '>', $maxEdadMaxima)->update(['NoValido' => 1]);

        foreach ($requisitos as $requisito) {
            $data_dui_cartera = $poliza_cumulos->where('Edad', '>=', $requisito->EdadInicial)->where('Edad', '<=', $requisito->EdadFinal)
                ->where('total_saldo', '>=', $requisito->MontoInicial)->where('total_saldo', '<=', $requisito->MontoFinal)
                ->pluck('Dui')->toArray();


            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('Perfiles', null)->whereIn('Dui', $data_dui_cartera)->update(['Perfiles' => $requisito->perfil->Descripcion]);

            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('Perfiles', '<>', null)->whereIn('Dui', $data_dui_cartera)->update(['Perfiles' => DB::raw('CONCAT(Perfiles, "," ,"' . $requisito->perfil->Descripcion . '")')]);
        }


        //update para los que son mayores a la edad inicial
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
            ->where('NoValido', 0)
            ->where(function ($query) {
                $query->where('Perfiles', null)
                    ->orWhere('Perfiles', '=', '');
            })
            ->update(['NoValido' => 1]);


        //poniendo valido los creditos guardados en DeudaCreditosValidos
        $creditos_validos_array = DeudaCreditosValidos::where('Poliza', $deuda->Id)->pluck('NumeroReferencia')->toArray();
        PolizaDeudaTempCartera::whereIn('NumeroReferencia', $creditos_validos_array)->update(["NoValido" => 0]);



        $poliza_cumulos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,
        NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,PolizaDeuda,
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(SaldoCapital) as total_saldo,SUM(Intereses) as total_interes,SUM(InteresesCovid) as total_covid,
         SUM(InteresesMoratorios) as total_moratorios, SUM(MontoNominal) as total_monto_nominal')->where('User', auth()->user()->id)->where('PolizaDeuda', $deuda->Id)->groupBy('Dui', 'NoValido')->get();



        $poliza_cumulos_2 = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,
         NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,PolizaDeuda,
          GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(SaldoCapital) as total_saldo,SUM(Intereses) as total_interes,SUM(InteresesCovid) as total_covid,
          SUM(InteresesMoratorios) as total_moratorios, SUM(MontoNominal) as total_monto_nominal')->where('User', auth()->user()->id)->where('PolizaDeuda', $deuda->Id)->groupBy('NumeroReferencia', 'NoValido')->get();

        $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->where('User', auth()->user()->id)->get();

        //   $poliza_cumulos->where('Edad','>',$deuda->EdadMaximaTerminacion)->update(['NoValido' => 1]);
        //sobre pasan la edad maxima 
        /* PolizaDeudaTempCartera::where('Edad','>',$deuda->EdadMaximaTerminacion)
        ->where('PolizaDeuda', $deuda->Id)   
        ->update(['NoValido' => 1]);*/

        //sobre la responsabilidad maxima que se establecio

        foreach ($poliza_cumulos as $registro) {

            $sub_total = $registro->total_saldo + $registro->total_interes + $registro->total_covid + $registro->total_moratorios + $registro->total_monto_nominal;
            // array_push($sub,$sub_total);
            // $excluidos = DeudaExcluidos::whereMonth('FechaExclusion', $date_mes)->get();
            // $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->where('User', auth()->user()->id)->get();
            $excluidos = DeudaExcluidos::whereMonth('FechaExclusion', $poliza_temporal->first()->Mes)->where('Poliza', $deuda->Id)->get();
            foreach ($excluidos as $obj) {
                if ($sub_total < $deuda->ResponsabilidadMaxima && $obj->ResponsabilidadMaxima == 1 && $obj->EdadMaxima == null) {

                    if ($obj->Dui == $registro->Dui) {
                        $obj->Activo = 1;
                        $obj->update();
                    }
                }
            }
            if ($sub_total > $deuda->ResponsabilidadMaxima) {
                $registro->NoValido = 1;
                $registro->update();
                //agregar a tabla excluidos
                //val 0: Responsabilidad
                //   array_push($pisto,$registro->Id);

                $val = 0;
                $this->add_excluidos($registro, $val);
            }
        }
        foreach ($poliza_temporal as $registro) {

            if ($registro->Edad > $deuda->EdadMaximaTerminacion) {
                $registro->NoValido = 1;
                $registro->update();
                //agregar a tabla excluidos
                //val 1: Edad
                //  array_push($edadM,$registro->Id);

                $val = 1;
                $this->add_excluidos($registro, $val);
            }
        }

        //  dd($deuda->ResponsabilidadMaxima , $sub,$edadM,$pisto);





        $nuevos_registros = DB::table('poliza_deuda_temp_cartera')
            ->where([
                ['Mes', $date->month],
                ['Axo', $date->year],
                ['PolizaDeuda', $poliza_id],
            ])
            ->whereNotExists(function ($query) use ($date_anterior, $date_mes_anterior, $poliza_id) {
                $query->select(DB::raw(1))
                    ->from('poliza_deuda_cartera')
                    ->where('poliza_deuda_cartera.Mes', $date_anterior->month)
                    ->where('poliza_deuda_cartera.Axo', $date_mes_anterior->year)
                    ->where('PolizaDeuda', $poliza_id)
                    ->where(function ($subQuery) {
                        $subQuery->whereColumn('poliza_deuda_temp_cartera.NumeroReferencia', '=', 'poliza_deuda_cartera.NumeroReferencia');
                        //$subQuery->whereColumn('poliza_deuda_temp_cartera.Dui', '=', 'poliza_deuda_cartera.Dui');
                        // ->orWhere('poliza_deuda_temp_cartera.Nit', '=', 'poliza_deuda_cartera.Nit');
                    });
            })->get();


        /* $registros_eliminados = DB::table('poliza_deuda_cartera')
            ->where([
                ['Mes', $date_anterior->month],
                ['Axo', $date_mes_anterior->year],
                ['PolizaDeuda', $poliza_id],
            ])
            ->whereNotExists(function ($query) use ($date, $date_mes, $poliza_id) {
                $query->select(DB::raw(1))
                    ->from('poliza_deuda_temp_cartera')
                    ->where('poliza_deuda_temp_cartera.Mes', $date->month)
                    ->where('poliza_deuda_temp_cartera.Axo', $date_mes->year)
                    ->where('PolizaDeuda', $poliza_id)
                    ->where('poliza_deuda_cartera.NumeroReferencia', 'poliza_deuda_temp_cartera.NumeroReferencia');
                    // ->where(function ($subQuery) {
                    //     $subQuery->whereColumn('poliza_deuda_cartera.NumeroReferencia', '=', 'poliza_deuda_temp_cartera.NumeroReferencia');
                    //     //$subQuery->whereColumn('poliza_deuda_cartera.Dui', '=', 'poliza_deuda_temp_cartera.Dui');
                    //     // ->orWhere('poliza_deuda_cartera.Nit', '=', 'poliza_deuda_temp_cartera.Nit');
                    // })
            })->get();*/


        $registros_eliminados = DB::table('poliza_deuda_cartera as c')
            ->where('c.PolizaDeuda',  $poliza_id)
            ->where('c.Axo', $date_mes_anterior->year)
            ->where('c.Mes',  $date_anterior->month)
            ->whereNotExists(function ($query) use ($date, $date_mes, $poliza_id) {
                $query->select(DB::raw(1))
                    ->from('poliza_deuda_temp_cartera as t')
                    ->whereRaw('t.NumeroReferencia = c.NumeroReferencia')
                    ->whereRaw('t.PolizaDeuda = c.PolizaDeuda')
                    ->whereRaw('t.Mes = ' . $date->month)
                    ->whereRaw('t.Axo = ' . $date_mes->year);
            })
            ->get();

        $maximos_minimos = DeudaRequisitos::where('Deuda', '=', $poliza_id)
            ->selectRaw('MIN(MontoInicial) as min_monto_inicial, MAX(MontoFinal) as max_monto_final,MIN(EdadInicial) as min_edad_inicial, MAX(EdadFinal) as max_edad_final ')
            ->first();

        // $maximos_minimos contendrá el resultado de la consulta
        $minMontoInicial = $maximos_minimos->min_monto_inicial;
        $maxMontoFinal = $maximos_minimos->max_monto_final;
        $minEdadInicial = $maximos_minimos->min_edad_inicial;
        $maxEdadFinal = $maximos_minimos->max_edad_final;



        $poliza_cumulos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,
        NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(saldo_total) as total_saldo')
            ->where('User', auth()->user()->id)->where('PolizaDeuda', $deuda->Id)
            ->groupBy('Dui', 'NoValido')->get();


        $extra_primados = $deuda->extra_primados;

        foreach ($extra_primados as $extra_primado) {
            $extra_primado->Existe = PolizaDeudaTempCartera::where('NumeroReferencia', $extra_primado->NumeroReferencia)->count();
        }



        return view('polizas.deuda.respuesta_poliza', compact('poliza_temporal', 'maxEdadMaxima', 'nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'extra_primados', 'requisitos'));
    }



    public function store_poliza(Request $request)
    {
        // Convertir la cadena en un objeto Carbon (la clase de fecha en Laravel)
        $fecha = \Carbon\Carbon::parse($request->MesActual);

        // Obtener el mes y el año
        $mes = $fecha->format('m'); // El formato 'm' devuelve el mes con ceros iniciales (por ejemplo, "02")
        $anio = $fecha->format('Y');


        // Obtener los datos de la tabla temporal
        $tempData = PolizaDeudaTempCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('User', auth()->user()->id)
            ->where('NoValido', 0)
            ->where('PolizaDeuda', $request->Deuda)
            ->get();



        if ($tempData->isNotEmpty()) {
            $linea_credito = $tempData->first()->LineaCredito;
            $poliza_deuda = $tempData->first()->PolizaDeuda;
            $mes_int = intval($mes);
            PolizaDeudaCartera::where('PolizaDeuda', $poliza_deuda)->where('LineaCredito', $linea_credito)->where('Axo', $anio)->where('Mes', $mes_int)->delete();
        }

        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {
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
        }

        // dd()

        $deuda = Deuda::findOrFail($tempRecord->PolizaDeuda);
        $cartera = PolizaDeudaCartera::where('PolizaDeuda', '=', $tempRecord->PolizaDeuda)->get();


        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/deuda/' . $tempRecord->PolizaDeuda . '/edit?tab=2');
    }

    public function exportar_excel(Request $request)
    {
        $deuda = Deuda::findOrFail($request->Deuda);
        //edad maxima
        $tipo = $request->Tipo;
        $mes = $request->MesActual;
        if ($tipo == 1) {
            $excluidos = DeudaExcluidos::where('Poliza', $deuda->Id)->where('EdadMaxima', 1)->whereMonth('FechaExclusion', $mes)->where('Activo', 0)->get();
        } else {
            $excluidos = DeudaExcluidos::where('Poliza', $deuda->Id)->where('ResponsabilidadMaxima', 1)->whereMonth('FechaExclusion', $mes)->where('Activo', 0)->get();
        }

        return Excel::download(new ExcluidosExport($excluidos, $tipo), 'Clientes Excluidos.xlsx');
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
