<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\PolizaDeudaTempCarteraImport;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\DeudaCreditosValidos;
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


            //se limpia el nombre completo de espacios en blanco y numeros
            $obj->PrimerApellido = $this->limpiarNombre($obj->PrimerApellido);
            $obj->SegundoApellido = $this->limpiarNombre($obj->SegundoApellido);
            $obj->ApellidoCasada = $this->limpiarNombre($obj->ApellidoCasada);
            $obj->PrimerNombre = $this->limpiarNombre($obj->PrimerNombre);
            $obj->SegundoNombre = $this->limpiarNombre($obj->SegundoNombre);
            $obj->update();

            // 4 nombre o apellido
            if ($obj->PrimerApellido == "" || $obj->PrimerNombre == "") {
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



        /*if ($tempData->isNotEmpty()) {
            $linea_credito = $tempData->first()->LineaCredito;
            $poliza_deuda = $tempData->first()->PolizaDeuda;
            $mes_int = intval($mes);
            PolizaDeudaCartera::where('PolizaDeuda', $poliza_deuda)->where('LineaCredito', $linea_credito)->where('Axo', $anio)->where('Mes', $mes_int)
                ->where(function ($query) {
                    $query->where('PolizaDeudaDetalle', 0)
                        ->orWhereNull('PolizaDeudaDetalle');
                })
                ->delete();
        }*/


        PolizaDeudaCartera::where('LineaCredito', $credito)
            ->where(function ($query) {
                $query->where('PolizaDeudaDetalle', 0)
                    ->orWhereNull('PolizaDeudaDetalle');
            })
            ->delete();

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




        return back();


        // return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
    }


    public function limpiarNombre($nombre)
    {
        // Eliminar espacios en blanco y números
        $nombreLimpio = preg_replace('/[\s\d]+/', '', $nombre);

        return $nombreLimpio;
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
        
        $temp_data_fisrt = PolizaDeudaTempCartera::where('User', auth()->user()->id)->first();
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

        $registros_eliminados = DB::table('poliza_deuda_cartera')
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
                    ->where(function ($subQuery) {
                        $subQuery->whereColumn('poliza_deuda_cartera.NumeroReferencia', '=', 'poliza_deuda_temp_cartera.NumeroReferencia');
                        //$subQuery->whereColumn('poliza_deuda_cartera.Dui', '=', 'poliza_deuda_temp_cartera.Dui');
                        // ->orWhere('poliza_deuda_cartera.Nit', '=', 'poliza_deuda_temp_cartera.Nit');
                    });
            })->get();

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
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(total_saldo) as total_saldo')
         ->where('User',auth()->user()->id)
         ->groupBy('Dui', 'NoValido')->get();

     
         return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date'));
    }
}