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
                if ($obj->Nacionalidad == 'SAL' || $obj->Nacionalidad == 'Sal' || $obj->Nacionalidad == 'sal') {
                    $validador_dui = $this->validarDocumento($obj->Dui, "dui");

                    if ($validador_dui == false) {
                        $obj->TipoError = 2;
                        $obj->update();

                        array_push($errores_array, 2);
                    }
                } else {
                    $validador_dui = true;
                }
            }
            //  $obj->saldo_total = $obj->calculoTodalSaldo(); 
            //inicio calculo de tasas diferenciadas

            $fecha = PolizaDeudaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
                ->where('PolizaDeuda', '=', $deuda->Id)
                ->where(function ($query) {
                    $query->where('PolizaDeudaDetalle', '=', 0)
                        ->orWhere('PolizaDeudaDetalle', '=', null);
                })
                ->orderByDesc('Id')->first();
            $saldo = 0;
            if ($fecha) {
                $tipo_cartera = $obj->linea_credito->Saldos;
                // dd($tipo_cartera);
                try {
                    dd($deuda, $tipo_cartera, $obj->linea_credito->Id, $fecha);
                    switch ($tipo_cartera) {
                        case '1':
                            # saldo a capital
                            //  $saldo = $this->SaldoCapital;
                            $saldo = $this->calcularCarteraINS1($deuda, $tipo_cartera, $obj->linea_credito->Id, $fecha);
                            break;
                        case '2':
                            # saldo a capital mas intereses
                            $saldo =  $this->SaldoCapital + $this->Intereses;
                            break;
                        case '3':
                            # saldo a capital mas intereses mas covid
                            $saldo = $this->SaldoCapital + $this->Intereses +  $this->InteresesCovid;
                            break;
                        case '4':
                            # saldo a capital as intereses mas covid mas moratorios
                            $saldo = $this->SaldoCapital + $this->Intereses +  $this->InteresesCovid +  $this->InteresesMoratorios;
                            break;
                        case '5':
                            # .monto moninal
                            $saldo = $this->MontoNominal;
                            break;
                        case '6':
                            # .monto otorgado
                            $saldo = $this->calcularCarteraINS6($deuda, $tipo_cartera, $obj->linea_credito->Id, $fecha);

                            // $saldo = $this->MontoOtorgado;
                            break;
                        default:
                            # .sando capital
                            $saldo = $this->SaldoCapital;
                            break;
                    }

                    $obj->saldo_total = $saldo;
                } catch (Exception $e) {
                    $obj->saldo_total = 0.00;
                }
            }
            //fin calculo de tasas diferenciadas
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
            return view('polizas.deuda.respuesta_poliza_error', compact('data_error', 'deuda', 'credito'));
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

    public function calcularCarteraINS1($deuda, $tasas, $lineaCredito, $fecha)

    {
        $tasaGeneral = $deuda->Tasa;
        //    dd($deuda);
        foreach ($tasas as $obj) {
            if (!$obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {

                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))
                    ->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //   dd($saldo);
                $total = $saldo->Saldo;
                // $total = $saldo->Saldo * $tasaGeneral;
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                //existe tasa de Fecha
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = $saldo->Saldo * $obj->TasaFecha;
                $total = $saldo->Saldo;
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //  $total = $saldo->Saldo * $obj->TasaMonto;
                $total = $saldo->Saldo;
            } elseif (!$obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //    $total = $saldo->Saldo * $obj->TasaEdad;
                $total = $saldo->Saldo;
            } elseif ($obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //   $total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaMonto);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaEdad);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = ($saldo1->Saldo * $obj->TasaMonto) + ($saldo2->Saldo * $obj->TasaEdad);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif ($obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {

                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo3 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaEdad) + ($saldo3->Saldo * $obj->TasaMonto);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo) + ($saldo3->Saldo);
            }
        }
        return $total;
    }


    public function calcularCarteraINS2($deuda, $tasas, $lineaCredito, $fecha)

    {
        $tasaGeneral = $deuda->Tasa;
        foreach ($tasas as $obj) {
            if (!$obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {

                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))
                    ->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->where('NoValido', '=', 0)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses) * $tasaGeneral;
                $total = ($saldo->Saldo + $saldo->Intereses);
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                //existe tasa de Fecha
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses) * $obj->TasaFecha;
                $total = ($saldo->Saldo + $saldo->Intereses);
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {


                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses) * $obj->TasaMonto;
                $total = ($saldo->Saldo + $saldo->Intereses);
            } elseif (!$obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {

                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses) * $obj->TasaEdad;
                $total = ($saldo->Saldo + $saldo->Intereses);
            } elseif ($obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //    $total = (($saldo1->Saldo + $saldo1->Intereses) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses) * $obj->TasaMonto);
                $total = (($saldo1->Saldo + $saldo1->Intereses)) + (($saldo2->Saldo + $saldo2->Intereses));
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //$total = (($saldo1->Saldo + $saldo1->Intereses) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses) * $obj->TasaEdad);
                $total = (($saldo1->Saldo + $saldo1->Intereses)) + (($saldo2->Saldo + $saldo2->Intereses));
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = (($saldo1->Saldo + $saldo1->Intereses) * $obj->TasaMonto) + (($saldo2->Saldo + $saldo2->Intereses) * $obj->TasaEdad);
                $total = (($saldo1->Saldo + $saldo1->Intereses)) + (($saldo2->Saldo + $saldo2->Intereses));
            } elseif ($obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {

                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo3 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //$total = (($saldo1->Saldo + $saldo1->Intereses) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses) * $obj->TasaEdad) + (($saldo3->Saldo + $saldo3->Intereses) * $obj->TasaMonto);
                $total = (($saldo1->Saldo + $saldo1->Intereses)) + (($saldo2->Saldo + $saldo2->Intereses)) + ($saldo3->Saldo + $saldo3->Intereses);
            }
        }
        return $total;
    }


    public function calcularCarteraINS3($deuda, $tasas, $lineaCredito, $fecha)

    {
        $tasaGeneral = $deuda->Tasa;
        foreach ($tasas as $obj) {
            if (!$obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))

                    ->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                // $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid) * $tasaGeneral;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid);
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                //existe tasa de Fecha
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid) * $obj->TasaFecha;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid) * $tasaGeneral;
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid) * $obj->TasaMonto;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid) * $tasaGeneral;
            } elseif (!$obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //$total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid) * $obj->TasaEdad;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid) * $tasaGeneral;
            } elseif ($obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //$total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid) * $obj->TasaMonto);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid));
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //   $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid) * $obj->TasaEdad);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid));
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //   $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid) * $obj->TasaMonto) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid) * $obj->TasaEdad);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid));
            } elseif ($obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {

                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo3 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                // $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid) * $obj->TasaEdad) + (($saldo3->Saldo + $saldo3->Intereses + $saldo3->Covid) * $obj->TasaMonto);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid)) + (($saldo3->Saldo + $saldo3->Intereses + $saldo3->Covid));
            }
        }
        return $total;
    }


    public function calcularCarteraINS4($deuda, $tasas, $lineaCredito, $fecha)

    {
        $tasaGeneral = $deuda->Tasa;
        foreach ($tasas as $obj) {
            if (!$obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)
                    ->select(
                        DB::raw('SUM(SaldoCapital) as Saldo'),
                        DB::raw('SUM(Intereses) as Intereses'),
                        DB::raw('SUM(InteresesCovid) as Covid'),
                        DB::raw('Sum(InteresesMoratorios) as Mora'),
                    )
                    ->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                // $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora + $saldo->Mora) * $tasaGeneral;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora);
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                //existe tasa de Fecha
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora) * $obj->TasaFecha;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora + $saldo->Mora);
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora) * $obj->TasaMonto;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora + $saldo->Mora);
            } elseif (!$obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                // $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora) * $obj->TasaEdad;
                $total = ($saldo->Saldo + $saldo->Intereses + $saldo->Covid + $saldo->Mora + $saldo->Mora);
            } elseif ($obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //$total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora) * $obj->TasaMonto);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora));
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora) * $obj->TasaEdad);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora));
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //  $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora) * $obj->TasaMonto) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora) * $obj->TasaEdad);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora));
            } elseif ($obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {

                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo3 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(SaldoCapital) as Saldo'), DB::raw('SUM(Intereses) as Intereses'), DB::raw('SUM(InteresesCovid) as Covid'), DB::raw('Sum(InteresesMoratorios) as Mora'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                // $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora) * $obj->TasaFecha) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora) * $obj->TasaEdad) + (($saldo3->Saldo + $saldo3->Intereses + $saldo3->Covid + $saldo3->Mora) * $obj->TasaMonto);
                $total = (($saldo1->Saldo + $saldo1->Intereses + $saldo1->Covid + $saldo1->Mora)) + (($saldo2->Saldo + $saldo2->Intereses + $saldo2->Covid + $saldo2->Mora)) + (($saldo3->Saldo + $saldo3->Intereses + $saldo3->Covid + $saldo3->Mora));
            }
        }
        return $total;
    }

    public function calcularCarteraINS5($deuda, $tasas, $lineaCredito, $fecha)
    {
        $tasaGeneral = $deuda->Tasa;
        foreach ($tasas as $obj) {
            if (!$obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //$total = $saldo->Saldo * $tasaGeneral;
                $total = $saldo->Saldo;
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                //existe tasa de Fecha
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = $saldo->Saldo * $obj->TasaFecha;
                $total = $saldo->Saldo;
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                // $total = $saldo->Saldo * $obj->TasaMonto;
                $total = $saldo->Saldo;
            } elseif (!$obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //  $total = $saldo->Saldo * $obj->TasaEdad;
                $total = $saldo->Saldo;
            } elseif ($obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaMonto);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                //  $total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaEdad);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = ($saldo1->Saldo * $obj->TasaMonto) + ($saldo2->Saldo * $obj->TasaEdad);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif ($obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {

                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo3 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(MontoNominal) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();

                $total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaEdad) + ($saldo3->Saldo * $obj->TasaMonto);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo) + ($saldo3->Saldo);
            }
        }
        return $total;
    }

    public function calcularCarteraINS6($deuda, $tasas, $lineaCredito, $fecha)

    {
        dd($deuda, $tasas, $lineaCredito, $fecha);
        $tasaGeneral = $deuda->Tasa;
        //    dd($deuda);
        foreach ($tasas as $obj) {
            if (!$obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {

                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))
                    ->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //   dd($saldo);
                $total = $saldo->Saldo;
                // $total = $saldo->Saldo * $tasaGeneral;
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && !$obj->TasaEdad) {
                //existe tasa de Fecha
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //$total = $saldo->Saldo * $obj->TasaFecha;
                $total = $saldo->Saldo;
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //  $total = $saldo->Saldo * $obj->TasaMonto;
                $total = $saldo->Saldo;
            } elseif (!$obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $saldo = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //    $total = $saldo->Saldo * $obj->TasaEdad;
                $total = $saldo->Saldo;
            } elseif ($obj->TasaFecha && $obj->TasaMonto && !$obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                //   $total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaMonto);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif ($obj->TasaFecha && !$obj->TasaMonto && $obj->TasaEdad) {
                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaEdad);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif (!$obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])

                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = ($saldo1->Saldo * $obj->TasaMonto) + ($saldo2->Saldo * $obj->TasaEdad);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo);
            } elseif ($obj->TasaFecha && $obj->TasaMonto && $obj->TasaEdad) {

                $desde = Carbon::parse($obj->FechaDesde)->format('y-m-d');
                $hasta = Carbon::parse($obj->FechaHasta)->format('y-m-d');
                $saldo1 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('FechaOtorgamiento', [$desde, $hasta])

                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo2 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('Edad', [$obj->EdadDesde, $obj->EdadHasta])
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();
                $saldo3 = PolizaDeudaCartera::where('PolizaDeuda', '=', $deuda->Id)->whereBetween('MontoOtorgado', [$obj->MontoDesde, $obj->MontoHasta])
                    ->select(DB::raw('SUM(MontoOtorgado) as Saldo'))->where('LineaCredito', '=', $lineaCredito)->where('Mes', '=', $fecha->Mes)->where('Axo', '=', $fecha->Axo)->first();


                //$total = ($saldo1->Saldo * $obj->TasaFecha) + ($saldo2->Saldo * $obj->TasaEdad) + ($saldo3->Saldo * $obj->TasaMonto);
                $total = ($saldo1->Saldo) + ($saldo2->Saldo) + ($saldo3->Saldo);
            }
        }
        return $total;
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
                //'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
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
        $poliza_cumulos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,Excluido,NoValido
        NumeroReferencia,SUM(SaldoCapital) as total_saldo,SUM(Intereses) as total_interes,SUM(InteresesCovid) as total_covid,SUM(saldo_total) as saldo_total,
        SUM(InteresesMoratorios) as total_moratorios, SUM(MontoNominal) as total_monto_nominal')->groupBy('Dui')->get();


        //dejando los perfiles nulos como valor inicial
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->update(['Perfiles' => null]);


        //definiendo edad maxima segu requisitos
        //
        $maxEdadMaxima = $deuda->requisitos->max('EdadFinal');
        PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)->where('Edad', '>', $maxEdadMaxima)->update(['NoValido' => 1]);

        foreach ($requisitos as $requisito) {
            $data_dui_cartera = $poliza_cumulos->where('Edad', '>=', $requisito->EdadInicial)->where('Edad', '<=', $requisito->EdadFinal)
                ->where('saldo_total', '>=', $requisito->MontoInicial)->where('saldo_total', '<=', $requisito->MontoFinal)
                ->pluck('Dui')->toArray();

            PolizaDeudaTempCartera::where('PolizaDeuda', $deuda->Id)
                ->whereIn('Dui', $data_dui_cartera)
                ->update([
                    'Perfiles' => DB::raw(
                        'IF(Perfiles IS NULL OR Perfiles = "","' . $requisito->perfil->Descripcion . '", CONCAT(Perfiles, ",","' . $requisito->perfil->Descripcion . '"))'
                    )
                ]);
        }

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



        //poniendo valido los creditos guardados en DeudaCreditosValidos
        $creditos_validos_array = DeudaCreditosValidos::where('Poliza', $deuda->Id)->pluck('NumeroReferencia')->toArray();
        PolizaDeudaTempCartera::whereIn('NumeroReferencia', $creditos_validos_array)->update(["NoValido" => 0]);



        $poliza_cumulos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,Excluido,SegundoApellido,ApellidoCasada,FechaNacimiento,
        NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,PolizaDeuda,
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(SaldoCapital) as total_saldo,SUM(Intereses) as total_interes,SUM(InteresesCovid) as total_covid,
         SUM(InteresesMoratorios) as total_moratorios, SUM(MontoNominal) as total_monto_nominal')->where('User', auth()->user()->id)->where('PolizaDeuda', $deuda->Id)->groupBy('Dui', 'NoValido')->get();



        $poliza_cumulos_2 = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,
         NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,PolizaDeuda,Excluido,
          GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(SaldoCapital) as total_saldo,SUM(Intereses) as total_interes,SUM(InteresesCovid) as total_covid,
          SUM(InteresesMoratorios) as total_moratorios, SUM(MontoNominal) as total_monto_nominal')->where('User', auth()->user()->id)->where('PolizaDeuda', $deuda->Id)->groupBy('NumeroReferencia', 'NoValido')->get();

        $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $poliza_id)->where('User', auth()->user()->id)->get();


        $registro_mes_anterior = PolizaDeudaCartera::where('Mes', $date_anterior->month)->where('Axo', $date_mes_anterior->year)->where('PolizaDeuda', $poliza_id)->get();
        $registro_mes_anterior_array = $registro_mes_anterior->pluck('NumeroReferencia')->toArray();
        $poliza_temporal_array = $poliza_temporal->pluck('NumeroReferencia')->toArray();



        $nuevos_registros = DB::table('poliza_deuda_temp_cartera')
            ->where('Mes', $date->month)
            ->where('Axo', $date->year)
            ->where('PolizaDeuda', $poliza_id)
            ->whereNotIn('NumeroReferencia', $registro_mes_anterior_array)->get();


        $registros_eliminados =  $registro_mes_anterior->whereNotIn('NumeroReferencia', $poliza_temporal_array);


        $maximos_minimos = DeudaRequisitos::where('Deuda', '=', $poliza_id)
            ->selectRaw('MIN(MontoInicial) as min_monto_inicial, MAX(MontoFinal) as max_monto_final,MIN(EdadInicial) as min_edad_inicial, MAX(EdadFinal) as max_edad_final ')
            ->first();

        // $maximos_minimos contendrá el resultado de la consulta
        $minMontoInicial = $maximos_minimos->min_monto_inicial;
        $maxMontoFinal = $maximos_minimos->max_monto_final;
        $minEdadInicial = $maximos_minimos->min_edad_inicial;
        $maxEdadFinal = $maximos_minimos->max_edad_final;



        $poliza_cumulos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,Excluido,
        NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(saldo_total) as total_saldo')
            ->where('User', auth()->user()->id)->where('PolizaDeuda', $deuda->Id)
            ->groupBy('Dui', 'NoValido')->get();



        $extra_primados = $deuda->extra_primados;

        foreach ($extra_primados as $extra_primado) {
            $extra_primado->Existe = PolizaDeudaTempCartera::where('NumeroReferencia', $extra_primado->NumeroReferencia)->count();
        }
        $mes = $date->format('m');
        // dd($mes);
        $excluidos = DeudaExcluidos::where('Poliza', $deuda->Id)->whereMonth('FechaExclusion', $mes)->where('Activo', 0)->get();



        $excuidos = DeudaExcluidos::where('Poliza', $request->Deuda)->get();

        $excuidos_array = [];
        if ($excuidos) {
            $excuidos_array = $excuidos->pluck('NumeroReferencia')->toArray();
        }
        //dd($excuidos);

        $poliza_temporal = PolizaDeudaTempCartera::where('PolizaDeuda', $request->Deuda)->where('User', auth()->user()->id)
            ->where('Edad', '>=', $deuda->EdadMaximaTerminacion)
            ->whereNotIn('NumeroReferencia', $excuidos_array)->get();

        $conteo_excluidos = $poliza_temporal->count();


        return view('polizas.deuda.respuesta_poliza', compact(
            'excluidos',
            'poliza_temporal',
            'maxEdadMaxima',
            'nuevos_registros',
            'registros_eliminados',
            'deuda',
            'poliza_cumulos',
            'date_anterior',
            'date',
            'extra_primados',
            'requisitos',
            'conteo_excluidos'
        ));
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
