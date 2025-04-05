<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Perfil;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\SaldoMontos;
use App\Models\catalogo\TipoCartera;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaDetalle;
use App\Models\polizas\DeudaRequisitos;
use App\Models\polizas\PolizaDeudaHistorica;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DeudaRenovacionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function renovar($id)
    {
        $deuda = Deuda::findOrFail($id);
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $deuda->Aseguradora)->get();
        // Estructura de la tabla
        $tabla = [];
        $requisitos = DeudaRequisitos::where('Activo', 1)->where('Deuda', $id)->get();
        foreach ($requisitos as $requisito) {
            $perfil = $requisito->perfil->Descripcion;
            $perfilId = $requisito->Perfil;
            $edadRango = "{$requisito->EdadInicial}-{$requisito->EdadFinal}";
            $montoRango = "{$requisito->MontoInicial}-{$requisito->MontoFinal}";

            // Guardar el id del requisito para su uso posterior
            $tabla[$perfil][$edadRango] = [
                'monto' => $montoRango,
                'id' => $requisito->Id,
                'perfilId'  => $perfilId,
            ];
        }


        $fechaDesdeRenovacion = $deuda->VigenciaHasta;
        $fechaDesdeRenovacionTemporal = $deuda->VigenciaHasta;
        $fechaDesdeRenovacionAnual = $deuda->VigenciaHasta;

        // Crear una instancia de Carbon a partir de la fecha
        $fecha = Carbon::parse($deuda->VigenciaHasta);

        // Agregar un año a la fecha
        $nuevaFecha  = $fecha->copy()->addYear();
        $fechaHastaRenovacion = $nuevaFecha->format('Y-m-d');


        $historicoDeuda = PolizaDeudaHistorica::where('Deuda', $id)->get();

        $registroInicial = $historicoDeuda->isNotEmpty() ? $historicoDeuda->first() : null;

        $fechaDesdeRenovacionAnual = $registroInicial ? $registroInicial->VigenciaHasta : $deuda->VigenciaHasta;

        foreach ($historicoDeuda->sortByDesc('Id') as $historico) {
            if ($historico->TipoRenovacion == 1) {
                $fechaDesdeRenovacionAnual = $historico->FechaHastaRenovacion;
                break; // Salir del bucle si la condición se cumple
            }
        }


        // Obtener los rangos de edad para las columnas
        $columnas = [];
        foreach ($tabla as $filas) {
            $columnas = array_merge($columnas, array_keys($filas));
        }
        $columnas = array_unique($columnas);
        sort($columnas);
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
        $saldos = SaldoMontos::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $historico_poliza = PolizaDeudaHistorica::where('Deuda', $id)->orderBy('Fecha')->get();
       // dd($registroInicial);
        session(['tab' => 1]);

        return view('polizas.deuda.renovar', compact(
            'historico_poliza',
            'cliente',
            'planes',
            'productos',
            'aseguradora',
            'deuda',
            'fechaDesdeRenovacion',
            'fechaHastaRenovacion',
            'registroInicial',
            'fechaDesdeRenovacionAnual',
            'estadoPoliza',
            'ejecutivo',
            'perfiles',
            'columnas',
            'tabla',
            'columnas',
            'tipoCartera',
            'saldos'
        ));
    }


    public function save_renovar(Request $request)
    {
        try {
            $numericValue = str_replace(',', '', $request->ResponsabilidadMaxima);

            if (!is_numeric($numericValue)) {
                return back()->withErrors(['ResponsabilidadMaxima' => 'El campo Responsabilidad Máxima debe ser un número válido.'])->withInput();
            }

            $deuda = Deuda::findOrFail($request->Id);

            if ($deuda->VigenciaHasta == $request->VigenciaHasta) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas.'])->withInput();
            }


            if ($request->FechaDesdeRenovacion == $request->FechaHastaRenovacion) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas..'])->withInput();
            }


            $creditos = $deuda->deuda_tipos_cartera;
            $detalle = DeudaDetalle::where('Deuda', $deuda->Id)->get();
            $tabla_diferencia = ''; // PolizaDeudaTasaDiferenciada::whereIn('PolizaDuedaCredito', $creditos->pluck('Id')->toArray())->get();
            $requisitos = DeudaRequisitos::where('Deuda', $deuda->Id)->get();

            // Guardar todo en una tabla histórica
            $historica = new PolizaDeudaHistorica();
            $historica->Deuda = $deuda->Id;
            $historica->VigenciaHasta = $deuda->VigenciaHasta;
            $historica->VigenciaDesde = $deuda->VigenciaDesde;
            $historica->FechaDesdeRenovacion = $request->FechaDesdeRenovacion;
            $historica->FechaHastaRenovacion = $request->FechaHastaRenovacion;
            $historica->TipoRenovacion = $request->TipoRenovacion;
            $historica->DatosDeuda = json_encode($deuda);
            $historica->DatosCreditos = json_encode($creditos);
            $historica->DatosTablaDiferenciada = json_encode($tabla_diferencia);
            $historica->DeudaDetalle = json_encode($detalle);
            $historica->Requisito = json_encode($requisitos);
            $historica->Fecha = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
            $historica->Usuario = auth()->user()->id;
            $historica->save();

            // Actualizar los datos de la renovación
            $deuda->Ejecutivo = $request->Ejecutivo;
            $deuda->VigenciaDesde = $request->FechaDesdeRenovacion;
            $deuda->VigenciaHasta = $request->FechaHastaRenovacion;
            $deuda->Tasa = $request->Tasa;
            $deuda->Beneficios = $request->Beneficios;
            $deuda->ClausulasEspeciales = $request->ClausulasEspeciales;
            $deuda->Concepto = $request->Concepto;
            $deuda->EstadoPoliza = $request->EstadoPoliza;
            $deuda->Descuento = $request->Descuento;
            $deuda->TasaComision = $request->TasaComision;
            $deuda->FechaIngreso = $request->FechaIngreso;
            $deuda->Activo = 1;
            $deuda->Vida = $request->Vida;
            $deuda->Mensual = $request->tipoTasa;
            $deuda->Desempleo = $request->Desempleo;
            $deuda->EdadMaximaTerminacion = $request->EdadMaximaTerminacion;
            $deuda->ResponsabilidadMaxima = $numericValue;
            $deuda->Configuracion = 0; // Se habilita para configurar nuevamente ResponsabilidadMaxima
            $deuda->ComisionIva = $request->ComisionIva == 'on' ? 1 : 0;
            $deuda->Usuario = auth()->user()->id;
            $deuda->FechaIngreso = Carbon::now('America/El_Salvador');
            $deuda->update();

            alert()->success('Renovación realizada correctamente');

            return back();

            return Redirect::to('polizas/deuda');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()])->withInput();
        }

        //return redirect('poliza/deuda/configuracion_renovar/' . $deuda->Id);
        // return view('polizas.deuda.renovar_conf', compact('cliente', 'planes', 'productos', 'aseguradora', 'deuda', 'estadoPoliza', 'ejecutivo', 'creditos', 'perfiles', 'columnas', 'tabla', 'columnas', 'tipoCartera', 'saldos'));
    }

    public function get_fechas_renovacion(Request $request)
    {
        //dd('holi');
        // Buscar la deuda por ID
        $deuda = Deuda::findOrFail($request->Deuda);

        // Verificar si 'VigenciaHasta' tiene un valor válido
        if (!$deuda->VigenciaHasta) {
            return response()->json(['error' => 'La fecha VigenciaHasta es inválida'], 400);
        }

        // Convertir la fecha a un objeto Carbon
        $fecha = Carbon::parse($deuda->VigenciaHasta);
        $fechaDesdeRenovacion = $deuda->VigenciaHasta;

        // Obtener la poliza_deuda_historica más reciente según las condiciones
        $resultado = DB::table('poliza_deuda_historica as pdh')
            ->where('pdh.Id', function ($query) {
                $query->select(DB::raw('CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM poliza_deuda_historica
                    WHERE TipoRenovacion = 1
                ) THEN (
                    SELECT MIN(Id)
                    FROM poliza_deuda_historica
                    WHERE TipoRenovacion = 2
                    AND Id > (
                        SELECT MAX(Id)
                        FROM poliza_deuda_historica
                        WHERE TipoRenovacion = 1
                    )
                )
                ELSE (
                    SELECT MIN(Id)
                    FROM poliza_deuda_historica
                    WHERE TipoRenovacion = 2
                )
            END'));
            })
            ->where('pdh.Deuda', $deuda->id)
            ->first();

        // Si hay un resultado, actualizar la fecha
        if ($request->TipoRenovacion == 2) {
            //  dd('2');
            if ($resultado && isset($resultado->VigenciaHasta)) {
                $fechaDesdeRenovacion = $resultado->VigenciaHasta;
                $fecha = Carbon::parse($resultado->VigenciaHasta);
            }
        } elseif ($request->TipoRenovacion == 1) {
            //dd('1');
            $fecha_renovacion = PolizaDeudaHistorica::where('Deuda', $deuda->Id)->where('TipoRenovacion', 2)->orderBy('Fecha')->first();
            // dd($fecha_renovacion);
            $fechaDesdeRenovacion = $fecha_renovacion->VigenciaHasta;
            $fecha = Carbon::parse($fecha_renovacion->VigenciaHasta);
        }

        // Verificar si $fecha es válida antes de intentar sumarle años
        if (!$fecha) {
            return response()->json(['error' => 'No se pudo obtener una fecha válida'], 400);
        }

        // Agregar 2 años a la fecha
        $nuevaFecha = $fecha->copy()->addYears();
        $fechaHastaRenovacion = $nuevaFecha->format('Y-m-d');

        // Crear la respuesta
        $data = [
            "VigenciaDesde" => $fechaDesdeRenovacion,
            "VigenciaHasta" => $fechaHastaRenovacion
        ];

        return response()->json(['mensaje' => 'Se ha procesado con éxito', 'data' => $data]);
    }

    public function renovar_conf($id)
    {
        $deuda = Deuda::findOrFail($id);
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        //$creditos = DeudaCredito::where('Activo', 1)->where('Deuda', $id)->get();
        $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $deuda->Aseguradora)->get();
        // Estructura de la tabla
        $tabla = [];
        $requisitos = DeudaRequisitos::where('Activo', 1)->where('Deuda', $id)->get();
        foreach ($requisitos as $requisito) {
            $perfil = $requisito->perfil->Descripcion;
            $perfilId = $requisito->Perfil;
            $edadRango = "{$requisito->EdadInicial}-{$requisito->EdadFinal}";
            $montoRango = "{$requisito->MontoInicial}-{$requisito->MontoFinal}";

            // Guardar el id del requisito para su uso posterior
            $tabla[$perfil][$edadRango] = [
                'monto' => $montoRango,
                'id' => $requisito->Id,
                'perfilId'  => $perfilId,
            ];
        }

        // Obtener los rangos de edad para las columnas
        $columnas = [];
        foreach ($tabla as $filas) {
            $columnas = array_merge($columnas, array_keys($filas));
        }
        $columnas = array_unique($columnas);
        sort($columnas);
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
        $saldos = SaldoMontos::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $historico_poliza = PolizaDeudaHistorica::where('Deuda', $id)->orderBy('Fecha')->get();
        session(['tab' => 4]);


        return view('polizas.deuda.renovar', compact('historico_poliza', 'cliente', 'planes', 'productos', 'aseguradora', 'deuda', 'estadoPoliza', 'ejecutivo', 'creditos', 'perfiles', 'columnas', 'tabla', 'columnas', 'tipoCartera', 'saldos'));
    }

    public function renovacion_poliza(Request $request)
    {

        //Validar fechas
        if ($request->VigenciaDesde >= $request->VigenciaHasta) {
            alert()->error('La vigencia desde es mayor a vigencia hasta');
            return back();
        }

        $deuda = Deuda::findOrFail($request->Deuda);


        // $creditos = DeudaCredito::where('Deuda', $deuda->Id)->get();
        $detalle = DeudaDetalle::where('Deuda', $deuda->Id)->get();
        $tabla_diferencia = ''; // PolizaDeudaTasaDiferenciada::whereIn('PolizaDuedaCredito', $creditos->pluck('Id')->toArray())->get();
        $requisitos = DeudaRequisitos::where('Deuda', $deuda->Id)->get();
        $historico_poliza = PolizaDeudaHistorica::findOrFail($request->id_historica);
        if ($historico_poliza->VigenciaDesde == null && $historico_poliza->VigenciaHasta == null) {
            //actualiza el registro que no tenia vigencias
            $historico_poliza->VigenciaDesde = $deuda->VigenciaDesde;
            $historico_poliza->VigenciaHasta = $deuda->VigenciaHasta;
            $historico_poliza->TipoRenovacion = $request->TipoRenovacion;
            $historico_poliza->update();
        } else {
            //crea el registro de vigencia de tipo de renovacion parcial
            $historico_poliza = new PolizaDeudaHistorica();
            $historico_poliza->Deuda = $deuda->Id;
            $historico_poliza->VigenciaDesde = $deuda->VigenciaDesde;
            $historico_poliza->VigenciaHasta = $deuda->VigenciaHasta;
            $historico_poliza->DatosDeuda = json_encode($deuda);
            //$historico_poliza->DatosCreditos = json_encode($creditos);
            $historico_poliza->DatosTablaDiferenciada = json_encode($tabla_diferencia);
            $historico_poliza->DeudaDetalle = json_encode($detalle);
            $historico_poliza->Requisito = json_encode($requisitos);
            $historico_poliza->Fecha = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
            $historico_poliza->Usuario = auth()->user()->id;
            $historico_poliza->TipoRenovacion = $request->TipoRenovacion;
            $historico_poliza->save();
        }
        //actualizamos la vigencia de la poliza
        $deuda->VigenciaDesde = $request->VigenciaDesde;
        $deuda->VigenciaHasta = $request->VigenciaHasta;
        $deuda->update();
        session(['tab' => 4]);
        alert()->success('Su poliza fue modificada');
        return back();
    }

    public function conf_renovar($id)
    {
        // dd('holi');
        //enviar a la vista
        $deuda = Deuda::findOrFail($id);
        $id = $deuda->Id;
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $deuda->Aseguradora)->get();
        // Estructura de la tabla
        $tabla = [];
        $requisitos = DeudaRequisitos::where('Activo', 1)->where('Deuda', $id)->get();
        foreach ($requisitos as $requisito) {
            $perfil = $requisito->perfil->Descripcion;
            $perfilId = $requisito->Perfil;
            $edadRango = "{$requisito->EdadInicial}-{$requisito->EdadFinal}";
            $montoRango = "{$requisito->MontoInicial}-{$requisito->MontoFinal}";

            // Guardar el id del requisito para su uso posterior
            $tabla[$perfil][$edadRango] = [
                'monto' => $montoRango,
                'id' => $requisito->Id,
                'perfilId'  => $perfilId,
            ];
        }

        // Obtener los rangos de edad para las columnas
        $columnas = [];
        foreach ($tabla as $filas) {
            $columnas = array_merge($columnas, array_keys($filas));
        }
        $columnas = array_unique($columnas);
        sort($columnas);
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
        $saldos = SaldoMontos::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();

        $fechaDesdeRenovacion = $deuda->VigenciaHasta;


        // Crear una instancia de Carbon a partir de la fecha
        $fecha = Carbon::parse($deuda->VigenciaHasta);

        $resultado = DB::table('poliza_deuda_historica as pdh')
            ->where('pdh.Id', function ($query) {
                $query->select(DB::raw('CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM poliza_deuda_historica
                        WHERE TipoRenovacion = 1
                    ) THEN (
                        SELECT MIN(Id)
                        FROM poliza_deuda_historica
                        WHERE TipoRenovacion = 2
                        AND Id > (
                            SELECT MAX(Id)
                            FROM poliza_deuda_historica
                            WHERE TipoRenovacion = 1
                        )
                    )
                    ELSE (
                        SELECT MIN(Id)
                        FROM poliza_deuda_historica
                        WHERE TipoRenovacion = 2
                    )
                END'));
            })->where('pdh.Deuda', $id) // Condición adicional
            ->first();

        //dd( $resultado);

        if ($resultado) {
            $fechaDesdeRenovacion = $resultado->VigenciaHasta;
            $fecha = Carbon::parse($resultado->VigenciaHasta);
        } else {
            $fecha_renovacion = PolizaDeudaHistorica::where('Deuda', $id)->where('TipoRenovacion', 2)->orderBy('Fecha')->first();
            //dd($fecha_renovacion);
            if ($fecha_renovacion <> null) {
                $fechaDesdeRenovacion = $fecha_renovacion->VigenciaHasta;
                $fecha = Carbon::parse($fecha_renovacion->VigenciaHasta);
            }
        }

        $nuevaFecha = $fecha->copy()->addYears();
        $fechaHastaRenovacion = $nuevaFecha->format('Y-m-d');
        $historico_poliza = PolizaDeudaHistorica::where('Deuda', $id)->get();

        session(['tab' => 4]);
        return view('polizas.deuda.renovar_conf', compact(
            'historico_poliza',
            'fechaHastaRenovacion',
            'fechaDesdeRenovacion',
            'cliente',
            'planes',
            'productos',
            'aseguradora',
            'deuda',
            'estadoPoliza',
            'ejecutivo',
            'perfiles',
            'columnas',
            'tabla',
            'columnas',
            'tipoCartera',
            'saldos'
        ));
    }

    public function eliminar_renovacion($id)
    {
        //se debe eliminar el registro seleccionado del historico y actualizar los datos de la poliza a la nueva ultima renovacion
       // dd('eliminar');

        $historica_id = PolizaDeudaHistorica::findOrFail($id);
        $historico_poliza = PolizaDeudaHistorica::where('Deuda', $historica_id->Deuda)->orderBy('Fecha')->get();


        $deuda = Deuda::findOrFail($historica_id->Deuda);
        $deuda->VigenciaDesde = $historica_id->VigenciaDesde;
        $deuda->VigenciaHasta = $historica_id->VigenciaHasta;
        $deuda->update();

        $historica_id->delete();

        alert()->success('Su registro fue eliminado con exito');
        return back();
    }
}
