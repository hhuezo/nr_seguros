<?php

namespace App\Http\Controllers\polizas;

use App\Exports\CreditosNoValidoExport;
use App\Exports\DeudaErroneosExport;
use App\Exports\DeudaExport;
use App\Exports\DeudaFedeExport;
use App\Exports\DeudaReciboExport;
use App\Exports\EdadMaximaExport;
use App\Exports\ExtraPrimadosExcluidosExport;
use App\Exports\HistoricoPagosExport;
use App\Exports\RegistroRequisitosExport;
use App\Exports\RegistroRequisitosReciboExport;
use App\Exports\RegistrosEliminadosExport;
use App\Exports\RegistrosNuevosExport;
use App\Exports\ResponsabilidadMaximaExport;
use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ConfiguracionRecibo;
use App\Models\catalogo\DatosGenerales;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Perfil;
use App\Models\catalogo\Plan;
use App\Models\polizas\PolizaDeudaExtraPrimados;
use App\Models\catalogo\Producto;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\SaldoMontos;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoCobro;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\Comentario;
use App\Models\polizas\Desempleo;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\DeudaDetalle;
use App\Models\polizas\DeudaHistorialRecibo;
use App\Models\polizas\DeudaRequisitos;
use App\Models\polizas\DeudaValidados;
use App\Models\polizas\DeudaVida;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\polizas\PolizaDeudaExtraPrimadosMensual;
use App\Models\polizas\PolizaDeudaHistorica;
use App\Models\polizas\PolizaDeudaTasaDiferenciada;
use App\Models\polizas\Vida;
use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Throwable;

class DeudaController extends Controller
{


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






        /*$fechaDesdeRenovacion = $deuda->VigenciaHasta;




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
        }




     */







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

    public function index()
    {
        $today = Carbon::now()->toDateString();

        session(['MontoCarteraDeuda' => 0]);
        session(['FechaInicioDeuda' => $today]);
        session(['FechaFinalDeuda' => $today]);
        session(['ExcelURLDeuda' => '']);
        $deuda = Deuda::where('Activo', 1)->get();
        return view('polizas.deuda.index', compact('deuda'));
    }


    public function create()
    {

        $ultimos = Deuda::where('Activo', 1)->orderByDesc('Id')->first();

        if (!$ultimos) {
            $ultimo = 1;
        } else {
            $ultimo = $ultimos->Id + 1;
        }

        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.deuda.create', compact(
            'aseguradora',
            'cliente',
            'productos',
            'planes',
            'tipoCartera',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'bomberos',
            'ultimo'
        ));
    }

    public function agregar_pago(Request $request)
    {

        $deuda = Deuda::findOrFail($request->Deuda);
        $time = Carbon::now('America/El_Salvador');

        $recibo = DatosGenerales::orderByDesc('Id_recibo')->first();
        // if (!$request->ExcelURL) {
        //     alert()->error('No se puede generar el pago, falta subir cartera')->showConfirmButton('Aceptar', '#3085d6');
        // } else {

        $detalle = new DeudaDetalle();
        $detalle->FechaInicio = $request->FechaInicio;
        $detalle->FechaFinal = $request->FechaFinal;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->Deuda = $request->Deuda;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaCalculada = $request->PrimaCalculada;
        $detalle->Descuento = $request->Descuento;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        $detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
        $detalle->GastosEmision = $request->GastosEmision;
        $detalle->Otros = $request->Otros;
        $detalle->SubTotal = $request->SubTotal;
        $detalle->Iva = $request->Iva;
        $detalle->TasaComision = $request->TasaComision;
        $detalle->Comision = $request->Comision;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->Retencion = $request->Retencion;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->Comentario = $request->Comentario;
        $detalle->APagar = $request->APagar;

        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->DescuentoIva = $request->DescuentoIva; //checked
        $detalle->ExtraPrima = $request->ExtraPrima;
        $detalle->ExcelURL = $request->ExcelURL;
        $detalle->NumeroRecibo = ($recibo->Id_recibo) + 1;
        $detalle->Usuario = auth()->user()->id;
        $detalle->FechaIngreso = $time->format('Y-m-d');
        $detalle->save();


        PolizaDeudaTempCartera::where('User', auth()->user()->id)->where('PolizaDeuda', $request->Deuda)->delete();
        $cartera = PolizaDeudaCartera::where('FechaInicio', $request->FechaInicio)->where('FechaFinal', $request->FechaFinal)->where('PolizaDeuda', $request->Deuda)->update(['PolizaDeudaDetalle' => $detalle->Id]);

        $comen = new Comentario();
        $comen->Comentario = 'Se agrego el pago de la cartera';
        $comen->Activo = 1;
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Deuda = $request->Deuda;
        $comen->DetalleDeuda = $detalle->Id;
        $comen->save();


        $recibo->Id_recibo = ($recibo->Id_recibo) + 1;
        $recibo->update();

        $extraprimados = PolizaDeudaExtraPrimados::where('PolizaDeuda', $request->Deuda)->get();
        $total_extrapima = 0;
        foreach ($extraprimados as $extraprimado) {
            //consultando calculos de extraprimados
            $data_array = $extraprimado->getPagoEP($extraprimado->Id);


            $extraprimado->total = $data_array['total'];
            $extraprimado->saldo_capital = $data_array['saldo_capital'];
            $extraprimado->interes = $data_array['interes'];
            $extraprimado->prima_neta = $data_array['prima_neta'];
            $extraprimado->extra_prima = $data_array['extra_prima'];
            $total_extrapima += $data_array['extra_prima'];


            $prima_mensual = new PolizaDeudaExtraPrimadosMensual();
            $prima_mensual->PolizaDeuda = $request->Deuda;
            $prima_mensual->Dui = $extraprimado->Dui;
            $prima_mensual->NumeroReferencia = $extraprimado->NumeroReferencia;
            $prima_mensual->Nombre = $extraprimado->Nombre;
            $prima_mensual->FechaOtorgamiento = $extraprimado->FechaOtorgamiento;
            $prima_mensual->MontoOtorgamiento = $extraprimado->MontoOtorgamiento;
            $prima_mensual->Tarifa = $extraprimado->Tarifa;
            $prima_mensual->PorcentajeEP = $extraprimado->PorcentajeEP;
            $prima_mensual->PagoEP = $extraprimado->PagoEP;
            $prima_mensual->DeudaDetalle = $detalle->Id;
            $prima_mensual->save();
        }



        //session(['MontoCartera' => 0]);
        alert()->success('El Registro de cobro ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        // }
        return back();
    }

    public function store(Request $request)
    {

        $deuda = new Deuda();
        $deuda->NumeroPoliza = $request->NumeroPoliza;
        $deuda->Nit = $request->Nit;
        $deuda->Plan = $request->Planes;
        $deuda->Codigo = $request->Codigo;
        $deuda->Asegurado = $request->Asegurado;
        $deuda->Aseguradora = $request->Aseguradora;
        $deuda->Ejecutivo = $request->Ejecutivo;
        $deuda->VigenciaDesde = $request->VigenciaDesde;
        $deuda->VigenciaHasta = $request->VigenciaHasta;
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
        $deuda->ResponsabilidadMaxima = $request->ResponsabilidadMaxima;
        if ($request->ComisionIva == 'on') {
            $deuda->ComisionIva = 1;
        } else {
            $deuda->ComisionIva = 0;
        }
        $deuda->Usuario = auth()->user()->id;
        $deuda->FechaIngreso = Carbon::now('America/El_Salvador');
        $deuda->save();

        alert()->success('El registro de poliza ha sido ingresado correctamente');
        //  return view('polizas.deuda.create_edit',compact('deuda','tab','aseguradora','cliente','estadoPoliza','ejecutivo') );  //enviar show

        return redirect('polizas/deuda/' . $deuda->Id);
    }

    public function get_pago($id)
    {
        return DeudaDetalle::findOrFail($id);
    }

    public function edit_pago(Request $request)
    {

        $detalle = DeudaDetalle::findOrFail($request->Id);
        //dd($detalle);

        $deuda = Deuda::findOrFail($detalle->Deuda);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];


        if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
            $detalle->SaldoA = $request->SaldoA;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->Comentario = $request->Comentario;
            $detalle->update();

            $recibo_historial = $this->save_recibo($detalle, $deuda);
            $configuracion = ConfiguracionRecibo::first();

            $pdf = \PDF::loadView('polizas.deuda.recibo', compact('configuracion', 'recibo_historial', 'detalle', 'deuda', 'meses'))->setWarnings(false)->setPaper('letter');
            return $pdf->stream('Recibo.pdf');

            return back();
        } else {

            //dd($request->EnvioCartera .' 00:00:00');
            if ($request->EnvioCartera) {
                $detalle->EnvioCartera = $request->EnvioCartera;
            }
            if ($request->EnvioPago) {
                $detalle->EnvioPago = $request->EnvioPago;
            }
            if ($request->PagoAplicado) {
                $detalle->PagoAplicado = $request->PagoAplicado;
            }
            $detalle->Comentario = $request->Comentario;

            /*$detalle->EnvioPago = $request->EnvioPago;
            $detalle->PagoAplicado = $request->PagoAplicado;*/
            $detalle->update();
        }

        $time = Carbon::now('America/El_Salvador');
        $comen = new Comentario();
        $comen->Comentario = $request->Comentario;
        $comen->Activo = 1;
        $comen->DetalleDeuda = $detalle->Id;
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Deuda = $detalle->Deuda;
        $comen->save();

        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function store_requisitos(Request $request)
    {
        $requisito = new DeudaRequisitos();
        $requisito->Requisito = $request->Requisito;
        $requisito->EdadInicial = $request->EdadInicial;
        $requisito->EdadFinal = $request->EdadFinal;
        $requisito->MontoInicial = $request->MontoInicial;
        $requisito->MontoFinal = $request->MontoFinal;
        $requisito->EdadInicial2 = $request->EdadInicial2;
        $requisito->EdadFinal2 = $request->EdadFinal2;
        $requisito->MontoInicial2 = $request->MontoInicial2;
        $requisito->MontoFinal2 = $request->MontoFinal2;
        $requisito->EdadInicial3 = $request->EdadInicial3;
        $requisito->EdadFinal3 = $request->EdadFinal3;
        $requisito->MontoInicial3 = $request->MontoInicial3;
        $requisito->MontoFinal3 = $request->MontoFinal3;
        $requisito->save();
        return $requisito->Id;
    }

    public function get_requisitos(Request $request)
    {
        $sql = "select * from poliza_deuda_requisitos where id in ($request->Requisitos)";
        $requisitos = DB::select($sql);

        return view('polizas.deuda.requisitos', compact('requisitos'));
    }

    public function finalizar_configuracion(Request $request)
    {
        $deuda = Deuda::findOrFail($request->deuda);
        if ($deuda->Configuracion == 1) {
            $deuda->Configuracion = 0;
            $deuda->update();

            alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/deuda/' . $request->deuda);
        } else {
            $deuda->Configuracion = 1;
            $deuda->update();

            alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/deuda/' . $request->deuda . '/edit');
        }
    }

    public function show(Request $request, $id)
    {

        if (!session()->has('tab')) {
            session(['tab' => 2]);
        }

        $requisitos = DeudaRequisitos::where('Activo', 1)->where('Deuda', $id)->get();
        // Estructura de la tabla
        $tabla = [];
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


        $deuda = Deuda::findOrFail($id);

        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        //$creditos = DeudaCredito::where('Activo', 1)->where('Deuda', $id)->get();
        //dd( $creditos) esto se va eliminar ;

        $polizas_vida = Vida::get();
        $polizas_desempleo = Desempleo::get();



        $saldos = SaldoMontos::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $deuda->Aseguradora)->get();


        $tiposCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get();
        $lineas_credito = SaldoMontos::where('Activo', 1)->get();
        $historico_poliza = PolizaDeudaHistorica::where('Deuda', $id)->get();


        $registroInicial = $historico_poliza->isNotEmpty() ? $historico_poliza->first() : null;


        return view('polizas.deuda.show', compact(
            'historico_poliza',
            'registroInicial',
            'polizas_vida',
            'polizas_desempleo',
            'requisitos',
            'planes',
            'productos',
            'perfiles',
            'saldos',
            'deuda',
            'aseguradora',
            'cliente',
            'estadoPoliza',
            'ejecutivo',
            //'creditos',
            'tipoCartera',
            //'data',
            'tabla',
            'columnas',
            'tiposCartera',
            'lineas_credito'

        ));
    }

    public function eliminar_requisito(Request $request)
    {
        $requisito = DeudaRequisitos::findOrFail($request->id);
        $requisito->delete();
        alert()->success('Se ha eliminado con exito');
        return back();
        // return response()->json(['mensaje' => 'Se ha eliminado con exito', 'title' => 'Requisito!', 'icon' => 'success', 'showConfirmButton' => 'true']);
    }

    public function update_requisito(Request $request)
    {
        try {
            // Buscar el requisito por su ID
            $requisito = DeudaRequisitos::findOrFail($request->Id);

            // Actualizar los datos del requisito
            $requisito->EdadInicial = $request->EdadInicial;
            $requisito->EdadFinal = $request->EdadFinal;
            $requisito->MontoInicial = $request->MontoInicial;
            $requisito->MontoFinal = $request->MontoFinal;
            $requisito->Perfil = $request->Perfil;

            // Guardar los cambios
            $requisito->save();

            // Respuesta en caso de éxito
            alert()->success('La póliza se configuró correctamente', '¡Éxito!');
            return back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si no se encuentra el requisito
            alert()->success('Requisito no encontrado');
            return back();
        } catch (\Exception $e) {
            // Cualquier otro error
            alert()->success('Ocurrió un error al actualizar el requisito.');
            return back();
        }
    }


    public function datos_asegurabilidad(Request $request)
    {
        if ($request->EdadInicial < 18) {
            alert()->error('Debe ser mayor a 18 años');
        } else {
            $asegurabilidad = new DeudaRequisitos();
            $asegurabilidad->Deuda = $request->Deuda;
            $asegurabilidad->Perfil = $request->Perfiles;
            $asegurabilidad->EdadInicial = $request->EdadInicial;
            $asegurabilidad->EdadFinal = $request->EdadFinal;
            $asegurabilidad->MontoInicial = $request->MontoInicial;
            $asegurabilidad->MontoFinal = $request->MontoFinal;
            $asegurabilidad->save();
            alert()->success('El registro de poliza ha sido ingresado correctamente');
        }

        session(['tab' => 3]);
        return redirect('polizas/deuda/' . $request->Deuda);
    }

    public function actualizar(Request $request)
    {

        $deuda = Deuda::findOrFail($request->Deuda);
        $deuda->NumeroPoliza = $request->NumeroPoliza;
        $deuda->Plan = $request->Planes;
        $deuda->Nit = $request->Nit;
        $deuda->Codigo = $request->Codigo;
        $deuda->Asegurado = $request->Asegurado;
        $deuda->Aseguradora = $request->Aseguradora;
        $deuda->Ejecutivo = $request->Ejecutivo;
        $deuda->VigenciaDesde = $request->VigenciaDesde;
        $deuda->VigenciaHasta = $request->VigenciaHasta;
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
        $deuda->Desempleo = $request->Desempleo;
        $deuda->Mensual = $request->tipoTasa;
        $deuda->EdadMaximaTerminacion = $request->EdadMaximaTerminacion;
        $deuda->ResponsabilidadMaxima = $request->ResponsabilidadMaxima;
        $deuda->PolizaVida = $request->PolizaVida;
        $deuda->PolizaDesempleo = $request->PolizaDesempleo;
        if ($request->ComisionIva == 'on') {
            $deuda->ComisionIva = 1;
        } else {
            $deuda->ComisionIva = 0;
        }
        $deuda->Usuario = auth()->user()->id;
        // $deuda->FechaIngreso = Carbon::now('America/El_Salvador');
        $deuda->update();

        session(['tab' => 1]);

        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/deuda/' . $deuda->Id);
    }


    public function eliminar_extraprima(Request $request)
    {
        $extra = PolizaDeudaExtraPrimados::findOrFail($request->IdExtraPrima)->delete();
        alert()->success('El registro ha sido eliminado correctamente');
        return back();
    }



    public function edit($id)
    {
        //  dd("holi");
        $deuda = Deuda::findOrFail($id);
        //   dd($deuda);
        if ($deuda->Configuracion == 0) {
            //  dd("si");
            //  alert()->success('La configuracion no ha sido terminada');
            //session(['tab' => 1]);
            return redirect('polizas/deuda/' . $id);
        } else {
            if ($deuda->EdadMaximaTerminacion == null || $deuda->ResponsabilidadMaxima == null) {
                //session(['tab' => 1]);
                alert()->success('Debe agregar Edad Máxima y Responsabilidad Máxima');
                return redirect('polizas/deuda/' . $id);
            }

            // dd("no");
            $requisitos = DeudaRequisitos::where('Activo', 1)->where('Deuda', $id)->get();

            //formando encabezados
            $data[0][0] = "REQUISITOS";

            $i = 1;
            $uniqueRequisitos = $requisitos->unique(function ($item) {
                return $item->EdadInicial . '-' . $item->EdadFinal;
            });

            $i = 1;
            foreach ($uniqueRequisitos as $requisito) {
                $data[0][$i] = 'DESDE ' . $requisito->EdadInicial . ' AÑOS HASTA ' . $requisito->EdadFinal . ' AÑOS';
                $i++;
            }

            $i = 1;
            foreach ($requisitos->unique('Perfil') as $requisito) {
                $data[$i][0] = $requisito->perfil->Descripcion;
                $j = 1;
                for ($j = 1; $j < count($data[0]); $j++) {
                    $data[$i][$j] = "";
                }
                $i++;
            }

            $i = 1;
            foreach ($requisitos->unique('Perfil') as $requisito) {
                $records = DeudaRequisitos::where('Activo', 1)->where('Deuda', $id)->where('Perfil', $requisito->Perfil)->get();

                foreach ($records as $record) {
                    $valorBuscado = 'DESDE ' . $record->EdadInicial . ' AÑOS HASTA ' . $record->EdadFinal . ' AÑOS';
                    $columnaEncontrada = array_search($valorBuscado, $data[0]);
                    $data[$i][$columnaEncontrada] = 'Desde $' . number_format($record->MontoInicial, 2, '.', ',') . ' HASTA $' . number_format($record->MontoFinal, 2, '.', ',');
                }

                $i++;
            }


            //tab 2

            $dataPagoTemp = collect([]);
            $dataPagoId = [];


            foreach ($deuda->deuda_tipos_cartera as $deuda_tipos_cartera) {

                foreach ($deuda_tipos_cartera->tasa_diferenciada as $tasa_diferenciada) {

                    $dataPagoId[] = $tasa_diferenciada->Id;

                    $linea_credito = SaldoMontos::findOrFail($tasa_diferenciada->LineaCredito);

                    $edad = '';

                    if ($deuda_tipos_cartera->TipoCalculo == 2) {
                        $edad = $tasa_diferenciada->EdadDesde . ' - ' . $tasa_diferenciada->EdadHasta . ' años';
                    }

                    $fecha = '';

                    if ($deuda_tipos_cartera->TipoCalculo == 1) {
                        $fecha = Carbon::parse($tasa_diferenciada->FechaDesde)->format('d/m/Y') .
                            ' - ' .
                            Carbon::parse($tasa_diferenciada->FechaHasta)->format('d/m/Y');
                    }



                    $dataPagoTemp->push([
                        "Id" => $tasa_diferenciada->Id,
                        "PolizaDeuda" => $deuda_tipos_cartera->PolizaDeuda,
                        "TipoCartera" => $deuda_tipos_cartera->TipoCartera,
                        "DescripcionTipoCartera" => $deuda_tipos_cartera->TipoCartera,
                        "TipoCalculo" => $deuda_tipos_cartera->TipoCalculo,
                        "MontoMaximoIndividual" => $deuda_tipos_cartera->MontoMaximoIndividual,
                        // Agregando los nuevos campos
                        "PolizaDuedaTipoCartera" => $tasa_diferenciada->PolizaDuedaTipoCartera,
                        "LineaCredito" => $tasa_diferenciada->LineaCredito,
                        "DescripcionLineaCredito" => $linea_credito ? $linea_credito->Descripcion : '',
                        "AbreviaturaLineaCredito" => $linea_credito ? $linea_credito->Abreviatura : '',
                        "Fecha" => $fecha,
                        "Edad" => $edad,
                        "FechaDesde" => $tasa_diferenciada->FechaDesde ?? null,
                        "FechaHasta" => $tasa_diferenciada->FechaHasta ?? null,
                        "EdadDesde" => $tasa_diferenciada->EdadDesde ?? null,
                        "EdadHasta" => $tasa_diferenciada->EdadHasta ?? null,

                        "Tasa" => $tasa_diferenciada->Tasa,
                    ]);
                }
            }


            $dataPago = collect([]);


            foreach ($dataPagoTemp as $item) {

                //dd($item);
                //por fechas
                if ($item['TipoCalculo'] == 1) {

                    $total = DB::table('poliza_deuda_cartera')
                        ->selectRaw('
                        COALESCE(SUM(MontoOtorgado), 0) as MontoOtorgado,
                        COALESCE(SUM(SaldoCapital), 0) as SaldoCapital,
                        COALESCE(SUM(Intereses), 0) as Intereses,
                        COALESCE(SUM(InteresesMoratorios), 0) as InteresesMoratorios,
                        COALESCE(SUM(InteresesCovid), 0) as InteresesCovid,
                        COALESCE(SUM(MontoNominal), 0) as MontoNominal,
                        COALESCE(SUM(TotalCredito), 0) as TotalCredito
                    ')
                        ->where('PolizaDeudaDetalle', 0)
                        ->where('PolizaDeuda', $id)
                        ->where('PolizaDeudaTipoCartera', $item['PolizaDuedaTipoCartera'])
                        ->where('LineaCredito', $item['LineaCredito'])
                        ->whereBetween('FechaOtorgamientoDate', [$item['FechaDesde'], $item['FechaHasta']])
                        ->first();

                    // Si $total es null, aseguramos que los valores sean 0
                    $item['MontoOtorgado'] = $total->MontoOtorgado ?? 0;
                    $item['SaldoCapital'] = $total->SaldoCapital ?? 0;
                    $item['Intereses'] = $total->Intereses ?? 0;
                    $item['InteresesMoratorios'] = $total->InteresesMoratorios ?? 0;
                    $item['InteresesCovid'] = $total->InteresesCovid ?? 0;
                    $item['MontoNominal'] = $total->MontoNominal ?? 0;
                    $item['TotalCredito'] = $total->TotalCredito ?? 0;
                    $item['PrimaCalculada'] = ($item['TotalCredito'] > 0 && $item['Tasa'] > 0)
                        ? $item['TotalCredito'] * $item['Tasa'] : 0;

                    $dataPago->push($item);
                }
                //por edad
                else if ($item['TipoCalculo'] == 2) {
                    $total = DB::table('poliza_deuda_cartera')
                        ->selectRaw('
                            COALESCE(SUM(MontoOtorgado), 0) as MontoOtorgado,
                            COALESCE(SUM(SaldoCapital), 0) as SaldoCapital,
                            COALESCE(SUM(Intereses), 0) as Intereses,
                            COALESCE(SUM(InteresesMoratorios), 0) as InteresesMoratorios,
                            COALESCE(SUM(InteresesCovid), 0) as InteresesCovid,
                            COALESCE(SUM(MontoNominal), 0) as MontoNominal,
                            COALESCE(SUM(TotalCredito), 0) as TotalCredito
                        ')
                        ->where('PolizaDeudaDetalle', 0)
                        ->where('PolizaDeuda', $id)
                        ->where('PolizaDeudaTipoCartera', $item['PolizaDuedaTipoCartera'])
                        ->where('LineaCredito', $item['LineaCredito'])
                        ->whereBetween('EdadDesembloso', [$item['EdadDesde'], $item['EdadHasta']])
                        ->first();

                    // Si $total es null, aseguramos que los valores sean 0
                    $item['MontoOtorgado'] = $total->MontoOtorgado ?? 0;
                    $item['SaldoCapital'] = $total->SaldoCapital ?? 0;
                    $item['Intereses'] = $total->Intereses ?? 0;
                    $item['InteresesMoratorios'] = $total->InteresesMoratorios ?? 0;
                    $item['InteresesCovid'] = $total->InteresesCovid ?? 0;
                    $item['MontoNominal'] = $total->MontoNominal ?? 0;
                    $item['TotalCredito'] = $total->TotalCredito ?? 0;
                    $item['PrimaCalculada'] = ($item['TotalCredito'] > 0 && $item['Tasa'] > 0)
                        ? $item['TotalCredito'] * $item['Tasa'] : 0;

                    $dataPago->push($item);
                } else {
                    $total = DB::table('poliza_deuda_cartera')
                        ->selectRaw('
                    COALESCE(SUM(MontoOtorgado), 0) as MontoOtorgado,
                    COALESCE(SUM(SaldoCapital), 0) as SaldoCapital,
                    COALESCE(SUM(Intereses), 0) as Intereses,
                    COALESCE(SUM(InteresesMoratorios), 0) as InteresesMoratorios,
                    COALESCE(SUM(InteresesCovid), 0) as InteresesCovid,
                    COALESCE(SUM(MontoNominal), 0) as MontoNominal,
                    COALESCE(SUM(TotalCredito), 0) as TotalCredito
                ')
                        ->where('PolizaDeudaDetalle', 0)
                        ->where('PolizaDeuda', $id)
                        ->where('PolizaDeudaTipoCartera', $item['PolizaDuedaTipoCartera'])
                        ->where('LineaCredito', $item['LineaCredito'])
                        //->whereBetween('FechaOtorgamientoDate', [$item['FechaDesde'], $item['FechaHasta']])
                        ->first();

                    // Si $total es null, aseguramos que los valores sean 0
                    $item['MontoOtorgado'] = $total->MontoOtorgado ?? 0;
                    $item['SaldoCapital'] = $total->SaldoCapital ?? 0;
                    $item['Intereses'] = $total->Intereses ?? 0;
                    $item['InteresesMoratorios'] = $total->InteresesMoratorios ?? 0;
                    $item['InteresesCovid'] = $total->InteresesCovid ?? 0;
                    $item['MontoNominal'] = $total->MontoNominal ?? 0;
                    $item['TotalCredito'] = $total->TotalCredito ?? 0;
                    $item['PrimaCalculada'] = ($item['TotalCredito'] > 0 && $item['Tasa'] > 0)
                        ? $item['TotalCredito'] * $item['Tasa'] : 0;

                    $dataPago->push($item);
                }
            }

            $polizas_vida = Vida::get();
            $polizas_desempleo = Desempleo::get();
            $requisitos = DeudaRequisitos::where('Deuda', $deuda->Id)->get();
            $tipos_contribuyente = TipoContribuyente::get();
            $rutas = Ruta::where('Activo', '=', 1)->get();
            $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
            $bombero = Bombero::where('Activo', 1)->first();

            if ($bombero) {
                $bomberos = $bombero->Valor;
            } else {
                $bomberos = 0;
            }
            $aseguradora = Aseguradora::where('Activo', 1)->get();
            $cliente = Cliente::where('Activo', 1)->get();
            $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
            $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
            $tipoCobro = TipoCobro::where('Activo', 1)->get();
            $ejecutivo = Ejecutivo::where('Activo', 1)->get();
            $productos = Producto::where('Activo', 1)->get();
            $planes = Plan::where('Activo', 1)->get();
            $detalle = DeudaDetalle::where('Deuda', $deuda->Id)->orderBy('Id', 'desc')->get();

            //para fechas de modal
            $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $comentarios = Comentario::where('Deuda', $deuda->Id)->where('Activo', '=', 1)->get();

            $primerDia = Carbon::now()->startOfMonth();
            $ultimoDia = Carbon::now()->endOfMonth();



            //tab 7
            $data_temp_count = PolizaDeudaTempCartera::where('PolizaDeuda', $id)->count();
            $extraprimados = PolizaDeudaExtraPrimados::where('PolizaDeuda', $id)->get();
            $total_extrapima = 0;
            foreach ($extraprimados as $extraprimado) {
                //consultando calculos de extraprimados
                $data_array = $extraprimado->getPagoEP($extraprimado->Id);

                $extraprimado->total = $data_array['total'];
                $extraprimado->saldo_capital = $data_array['saldo_capital'];
                $extraprimado->interes = $data_array['interes'];
                $extraprimado->prima_neta = $data_array['prima_neta'];
                $extraprimado->extra_prima = $data_array['extra_prima'];
                $total_extrapima += $data_array['extra_prima'];

                $extraprimado->Existe = PolizaDeudaTempCartera::where('NumeroReferencia', $extraprimado->NumeroReferencia)->count();
            }

            //tab 8
            $historico = DB::table('poliza_deuda_cartera')
                ->select(
                    'Axo',
                    'Mes',
                    'FechaInicio',
                    'FechaFinal',
                    DB::raw('COUNT(*) AS total_registros'),  // Cuenta el número total de registros (usuarios)
                )
                ->where('PolizaDeuda', $id)  // Filtro por PolizaDeuda
                ->where('NoValido', 0)
                ->groupBy('Axo', 'Mes', 'FechaInicio', 'FechaFinal')  // Agrupación por los campos especificados
                ->orderBy('Axo', 'asc')  // Ordenar primero por Axo
                ->orderBy('Mes', 'asc')  // Luego ordenar por Mes
                ->orderBy('FechaInicio', 'asc')  // Finalmente ordenar por FechaInicio
                ->get();



            $array_dui = $extraprimados->pluck('Dui')->toArray();

            $clientesQuery = PolizaDeudaCartera::select(
                'Id',
                DB::raw("TRIM(CONCAT(
                IFNULL(PrimerNombre, ''),
                IF(IFNULL(SegundoNombre, '') != '', CONCAT(' ', SegundoNombre), ''),
                IF(IFNULL(PrimerApellido, '') != '', CONCAT(' ', PrimerApellido), ''),
                IF(IFNULL(SegundoApellido, '') != '', CONCAT(' ', SegundoApellido), ''),
                IF(IFNULL(ApellidoCasada, '') != '', CONCAT(' ', ApellidoCasada), '')
            )) as Nombre"),
                'Dui',
                'NumeroReferencia',
                'MontoOtorgado',
                'SaldoCapital'
            )->where('PolizaDeudaDetalle', '=', 0);

            // Verificar si $array_dui tiene datos antes de agregar la condición whereNotIn
            if (!empty($array_dui)) {

                $clientesQuery->whereNotIn('Dui', $array_dui);
            }
            // dd("holi");

            $clientes = PolizaDeudaCartera::select(
                'Id',
                'PrimerNombre',
                DB::raw("TRIM(CONCAT(
                        IFNULL(PrimerNombre, ''),
                        IF(IFNULL(SegundoNombre, '') != '', CONCAT(' ', SegundoNombre), ''),
                        IF(IFNULL(PrimerApellido, '') != '', CONCAT(' ', PrimerApellido), ''),
                        IF(IFNULL(SegundoApellido, '') != '', CONCAT(' ', SegundoApellido), ''),
                        IF(IFNULL(ApellidoCasada, '') != '', CONCAT(' ', ApellidoCasada), '')
                    )) as Nombre"),
                'Dui',
                'LineaCredito',
                'NumeroReferencia',
                'MontoOtorgado',
                'SaldoCapital',
                'Intereses',
                'InteresesCovid',
                'InteresesMoratorios',
                'MontoNominal',
                'TotalCredito',
            )->where('PolizaDeuda', '=', $id)->where('PolizaDeudaDetalle', '=', 0)
            ->orWhere('PolizaDeudaDetalle', '=', null)->groupBy('NumeroReferencia')->get();

             //dd($clientes->take(20));


            $ultimo_pago = DeudaDetalle::where('Deuda', $id)->orderBy('Id', 'desc')->first() ?? null; // Si no hay datos, asigna null



            $totalUltimoPago = null;

            if ($ultimo_pago !== null) {
                $totalUltimoPago = PolizaDeudaCartera::join('tipo_cartera as tc', 'poliza_deuda_cartera.PolizaDeudaTipoCartera', '=', 'tc.Id')
                    ->join('saldos_montos as lc', 'poliza_deuda_cartera.LineaCredito', '=', 'lc.Id')
                    ->where('poliza_deuda_cartera.PolizaDeudaDetalle', $ultimo_pago->Id)
                    ->select(DB::raw('SUM(poliza_deuda_cartera.TotalCredito) as TotalCredito,
                    tc.Nombre as TipoCarteraNombre,
                    lc.Descripcion as LineaCreditoDescripcion,
                    lc.Abreviatura as LineaCreditoAbreviatura'))
                    ->groupBy('poliza_deuda_cartera.PolizaDeudaTipoCartera', 'poliza_deuda_cartera.LineaCredito')
                    ->get();
            }


            $ultimaCartera = PolizaDeudaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
                ->where('PolizaDeuda', '=', $id)
                ->where(function ($query) {
                    $query->where('PolizaDeudaDetalle', '=', 0)
                        ->orWhere('PolizaDeudaDetalle', '=', null);
                })->orderByDesc('Id')->first();

            //conteo por si existe tasa diferenciada
            $count_tasas_diferencidas = PolizaDeudaTasaDiferenciada::
                join('poliza_deuda_tipo_cartera','poliza_deuda_tipo_cartera.Id','=','poliza_deuda_tasa_diferenciada.PolizaDuedaTipoCartera')
                ->where('poliza_deuda_tipo_cartera.PolizaDeuda',$id)
                ->whereIn('poliza_deuda_tipo_cartera.TipoCalculo',[1,2])->count();


            return view('polizas.deuda.edit', compact(
                'historico',
                'totalUltimoPago',
                'ultimaCartera',
                'total_extrapima',
                //'saldo',
                'clientes',
                'extraprimados',
                //'ultimo_pago_fecha_final',
                'meses',
                'primerDia',
                'ultimoDia',
                'detalle',
                'polizas_vida',
                'polizas_desempleo',
                'deuda',
                'requisitos',
                'aseguradora',
                'cliente',
                'tipoCartera',
                'estadoPoliza',
                'tipoCobro',
                'ejecutivo',
                'tipos_contribuyente',
                'rutas',
                'ubicaciones_cobro',
                'bomberos',
                'ultimo_pago',
                'productos',
                'planes',
                'data',
                'comentarios',
                'data_temp_count',
                'id',
                //tab2
                'dataPago',
                'dataPagoId',
                'count_tasas_diferencidas'
            ));
        }
    }

    public function recibo_pago($id, Request $request)
    {

        $detalle = DeudaDetalle::findOrFail($id);
        $deuda = Deuda::findOrFail($detalle->Deuda);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $detalle->SaldoA = $request->SaldoA;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->Referencia = $request->Referencia;
        $detalle->Anexo = $request->Anexo;
        $detalle->NumeroCorrelativo = $request->NumeroCorrelativo;
        $detalle->update();
        //$calculo = $this->monto($residencia, $detalle);

        $recibo_historial = $this->save_recibo($detalle, $deuda);
        $configuracion = ConfiguracionRecibo::first();
        $pdf = \PDF::loadView('polizas.deuda.recibo', compact('configuracion', 'recibo_historial', 'detalle', 'deuda', 'meses'))->setWarnings(false)->setPaper('letter');
        return $pdf->stream('Recibo.pdf');

        //  return back();
    }

    public function save_recibo($detalle, $deuda)
    {
        //      dd($detalle);
        $recibo_historial = new DeudaHistorialRecibo();
        $recibo_historial->PolizaDeudaDetalle = $detalle->Id;
        $recibo_historial->ImpresionRecibo = $detalle->ImpresionRecibo; //Carbon::now();
        $recibo_historial->NombreCliente = $deuda->clientes->Nombre;
        $recibo_historial->NitCliente = $deuda->clientes->Nit;
        $recibo_historial->DireccionResidencia = $deuda->clientes->DireccionResidencia ?? '(vacio)';
        $recibo_historial->Departamento = $deuda->clientes->distrito->municipio->departamento->Nombre;
        $recibo_historial->Municipio = $deuda->clientes->distrito->municipio->Nombre;
        $recibo_historial->NumeroRecibo = $detalle->NumeroRecibo;
        $recibo_historial->CompaniaAseguradora = $deuda->aseguradoras->Nombre;
        $recibo_historial->ProductoSeguros = $deuda->planes->productos->Nombre;
        $recibo_historial->NumeroPoliza = $deuda->NumeroPoliza;
        $recibo_historial->VigenciaDesde = $deuda->VigenciaDesde;
        $recibo_historial->VigenciaHasta = $deuda->VigenciaHasta;
        $recibo_historial->FechaInicio = $detalle->FechaInicio;
        $recibo_historial->FechaFin = $detalle->FechaFinal;
        $recibo_historial->Anexo = $detalle->Anexo;
        $recibo_historial->Referencia = $detalle->Referencia;
        $recibo_historial->FacturaNombre = $deuda->clientes->Nombre;
        $recibo_historial->MontoCartera = $detalle->MontoCartera;
        $recibo_historial->PrimaCalculada = $detalle->PrimaCalculada;
        $recibo_historial->ExtraPrima = $detalle->ExtraPrima;
        $recibo_historial->Descuento = $detalle->Descuento ?? 0;
        $recibo_historial->PordentajeDescuento = $deuda->Descuento;
        $recibo_historial->PrimaDescontada = $detalle->PrimaDescontada;
        $recibo_historial->ValorCCF = $detalle->ValorCCF;
        $recibo_historial->TotalAPagar = $detalle->APagar;
        $recibo_historial->TasaComision = $deuda->TasaComision ?? 0;
        $recibo_historial->Comision = $detalle->Comision;
        $recibo_historial->IvaSobreComision = $detalle->IvaSobreComision;
        $recibo_historial->SubTotalComision =  $detalle->IvaSobreComision + $detalle->Comision;
        $recibo_historial->Retencion = $detalle->Retencion;
        $recibo_historial->ValorCCF = $detalle->ValorCCF;
        $recibo_historial->FechaVencimiento = $detalle->FechaInicio;
        $recibo_historial->NumeroCorrelativo = $detalle->NumeroCorrelativo ?? '01';
        $recibo_historial->Cuota = '01/01';
        $recibo_historial->Otros = $detalle->Otros ?? 0;

        $recibo_historial->Usuario = auth()->user()->id;

        $recibo_historial->save();
        return $recibo_historial;
    }

    public function get_recibo($id, $exportar)
    {
        if (!isset($exportar)) {
            $exportar = 1;
        }
        //dd($exportar);
        $detalle = DeudaDetalle::findOrFail($id);

        $deuda = Deuda::findOrFail($detalle->Deuda);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = DeudaHistorialRecibo::where('PolizaDeudaDetalle', $id)->orderBy('id', 'desc')->first();
        //  $calculo = $this->monto($deuda, $detalle);
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($detalle, $deuda);
            //dd("insert");
        }

        if ($exportar == 2) {
            return Excel::download(new DeudaReciboExport($id), 'Recibo.xlsx');
            //return view('polizas.deuda.recibo', compact('recibo_historial','detalle', 'deuda', 'meses','exportar'));
        }

        $configuracion = ConfiguracionRecibo::first();

        $pdf = \PDF::loadView('polizas.deuda.recibo', compact('configuracion', 'recibo_historial', 'detalle', 'deuda', 'meses', 'exportar'))->setWarnings(false)->setPaper('letter');
        //  dd($detalle);
        return $pdf->stream('Recibos.pdf');
    }

    public function get_recibo_edit($id)
    {
        $detalle = DeudaDetalle::findOrFail($id);
        $deuda = Deuda::findOrFail($detalle->Deuda);
        $recibo_historial = DeudaHistorialRecibo::where('PolizaDeudaDetalle', $id)->orderBy('id', 'desc')->first();
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($detalle, $deuda);
            //dd("insert");
        }
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = DeudaHistorialRecibo::where('PolizaDeudaDetalle', $id)->orderBy('id', 'desc')->first();
        //dd($recibo_historial);
        return view('polizas.deuda.recibo_edit', compact('recibo_historial', 'meses'));
    }

    public function get_recibo_update(Request $request)
    {
        //modificación de ultimo recibo
        $id = $request->id_deuda_detalle;
        $detalle = DeudaDetalle::findOrFail($id);

        $deuda = Deuda::findOrFail($detalle->Deuda);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $impresion_recibo = $request->AxoImpresionRecibo . '-' . $request->MesImpresionRecibo . '-' . $request->DiaImpresionRecibo;

        $recibo_historial = new DeudaHistorialRecibo();
        $recibo_historial->PolizaDeudaDetalle = $id;
        //este valor cambia por eso no se manda al metodo de save_recibo
        $recibo_historial->ImpresionRecibo = Carbon::parse($impresion_recibo);
        $recibo_historial->NombreCliente = $request->NombreCliente;
        $recibo_historial->NitCliente = $request->NitCliente;
        $recibo_historial->DireccionResidencia = $request->DireccionResidencia;
        $recibo_historial->Departamento = $request->Departamento;
        $recibo_historial->Municipio = $request->Municipio;
        $recibo_historial->NumeroRecibo = $request->NumeroRecibo;
        $recibo_historial->CompaniaAseguradora = $request->CompaniaAseguradora;
        $recibo_historial->ProductoSeguros = $request->ProductoSeguros;
        $recibo_historial->NumeroPoliza = $request->NumeroPoliza;
        $recibo_historial->VigenciaDesde = $request->VigenciaDesde;
        $recibo_historial->VigenciaHasta = $request->VigenciaHasta;
        $recibo_historial->FechaInicio = $request->FechaInicio;
        $recibo_historial->FechaFin = $request->FechaFin;
        $recibo_historial->Anexo = $request->Anexo;
        $recibo_historial->Referencia = $request->Referencia;
        $recibo_historial->FacturaNombre = $request->FacturaNombre;
        $recibo_historial->MontoCartera = $request->MontoCartera;
        $recibo_historial->PrimaCalculada = $request->PrimaCalculada;
        $recibo_historial->ExtraPrima = $request->ExtraPrima;
        $recibo_historial->Descuento = $request->Descuento;
        $recibo_historial->PordentajeDescuento = $request->PordentajeDescuento;
        $recibo_historial->PrimaDescontada = $request->PrimaDescontada;
        $recibo_historial->ValorCCF = $request->ValorCCF;
        $recibo_historial->TotalAPagar = $request->TotalAPagar;
        $recibo_historial->TasaComision = $request->TasaComision;
        $recibo_historial->Comision = $request->Comision;
        $recibo_historial->IvaSobreComision = $request->IvaSobreComision;
        $recibo_historial->SubTotalComision = $request->SubTotalComision;
        $recibo_historial->Retencion = $request->Retencion;
        $recibo_historial->ValorCCF = $request->ValorCCF;
        $recibo_historial->FechaVencimiento = $request->FechaVencimiento ?? $detalle->FechaInicio;
        $recibo_historial->NumeroCorrelativo = $request->NumeroCorrelativo ??  '01';
        $recibo_historial->Cuota = $request->Cuota ?? '01/01';
        $recibo_historial->Otros = $detalle->Otros ?? 0;

        $recibo_historial->Usuario = auth()->user()->id;

        $recibo_historial->save();
        //dd("insert");
        alert()->success('Actualizacion de Recibo Exitoso');
        return redirect('polizas/deuda/' . $deuda->Id . '/edit');
    }

    public function exportar_excel(Request $request)
    {
        $deuda = $request->Deuda;
        $detalle = $request->DeudaDetalle;
        $cartera = PolizaDeudaCartera::where('PolizaDeudaDetalle', $detalle)->where('PolizaDeuda', $deuda)->where('NoValido', 0)->get();

        return Excel::download(new DeudaExport($cartera), 'Cartera.xlsx');
        //  dd($cartera->take(25),$request->Deuda,$request->DeudaDetalle);
    }

    public function exportar_excel_fede(Request $request)
    {
        $deuda = $request->Deuda;
        $detalle = $request->DeudaDetalle;
        $cartera = PolizaDeudaCartera::where('PolizaDeudaDetalle', $detalle)->where('PolizaDeuda', $deuda)->where('NoValido', 0)->get();

        return Excel::download(new DeudaFedeExport($cartera), 'Cartera.xlsx');
        //  dd($cartera->take(25),$request->Deuda,$request->DeudaDetalle);
    }

    public function agregar_comentario(Request $request)
    {
        $time = Carbon::now('America/El_Salvador');
        $comen = new Comentario();
        $comen->Comentario = $request->Comentario;
        $comen->Activo = 1;
        if ($request->TipoComentario == '') {
            $comen->DetalleDeuda = '';
        } else {
            $comen->DetalleDeuda == $request->TipoComentario;
        }
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Deuda = $request->DeudaComment;
        $comen->save();
        alert()->success('El registro del comentario ha sido creado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/deuda/' . $request->DeudaComment . '/edit');
    }

    public function eliminar_comentario(Request $request)
    {

        $comen = Comentario::findOrFail($request->IdComment);
        $comen->Activo = 0;
        $comen->update();
        alert()->success('El registro del comentario ha sido elimando correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/deuda/' . $comen->Deuda . '/edit');
    }


    public function get_extraprimado($id, $dui)
    {
        $cliente = PolizaDeudaCartera::join('poliza_deuda_creditos as cred', 'cred.Id', '=', 'poliza_deuda_cartera.LineaCredito')
            ->join('saldos_montos as sal', 'sal.Id', '=', 'cred.Saldos')
            ->select(
                'poliza_deuda_cartera.Id',
                // DB::raw("CONCAT(poliza_deuda_cartera.PrimerNombre, ' ', poliza_deuda_cartera.SegundoNombre, ' ', poliza_deuda_cartera.PrimerApellido, ' ', poliza_deuda_cartera.SegundoApellido, ' ', ' ', poliza_deuda_cartera.ApellidoCasada) as Nombre"),
                DB::raw("TRIM(CONCAT(
                    IFNULL(poliza_deuda_cartera.PrimerNombre, ''),
                    IF(IFNULL(poliza_deuda_cartera.SegundoNombre, '') != '', CONCAT(' ', poliza_deuda_cartera.SegundoNombre), ''),
                    IF(IFNULL(poliza_deuda_cartera.PrimerApellido, '') != '', CONCAT(' ', poliza_deuda_cartera.PrimerApellido), ''),
                    IF(IFNULL(poliza_deuda_cartera.SegundoApellido, '') != '', CONCAT(' ', poliza_deuda_cartera.SegundoApellido), ''),
                    IF(IFNULL(poliza_deuda_cartera.ApellidoCasada, '') != '', CONCAT(' ', poliza_deuda_cartera.ApellidoCasada), '')
                )) as Nombre"),
                'poliza_deuda_cartera.Dui',
                'sal.Id as Linea',
                'poliza_deuda_cartera.NumeroReferencia',
                'poliza_deuda_cartera.MontoOtorgado',
                'poliza_deuda_cartera.SaldoCapital',
                'poliza_deuda_cartera.Intereses',
                'poliza_deuda_cartera.InteresesCovid',
                'poliza_deuda_cartera.InteresesMoratorios',
                'poliza_deuda_cartera.MontoNominal',
                'poliza_deuda_cartera.FechaOtorgamiento',

            )
            ->where('PolizaDeuda', $id)->where('Dui', $dui)->first();
        // dd($cliente);

        return response()->json($cliente);
    }

    public function store_extraprimado(Request $request)
    {
        try {
            $cliente = new PolizaDeudaExtraPrimados();
            $cliente->NumeroReferencia = $request->NumeroReferencia;
            $cliente->PolizaDeuda = $request->PolizaDeuda;
            $cliente->Nombre = $request->Nombre;
            $cliente->FechaOtorgamiento = $request->FechaOtorgamiento;
            $cliente->MontoOtorgamiento = $request->MontoOtorgamiento;
            $cliente->PorcentajeEP = $request->PorcentajeEP;
            $cliente->Dui = $request->Dui;
            $cliente->save();

            alert()->success('Extraprimado agregado correctamente.');
            return redirect('polizas/deuda/' . $request->PolizaDeuda . '/edit?tab=7');
        } catch (\Exception $e) {
            // Log del error para depuración
            Log::error('Error al guardar extraprimado: ' . $e->getMessage());

            // Mensaje de error para el usuario
            alert()->error('Error al guardar el registro (verificar si el registro ya fue agregado anteriormente).')->persistent('Ok');
            return redirect()->back()->withInput();
        }
    }


    public function update_extraprimado(Request $request)
    {
        $extra_primado = PolizaDeudaExtraPrimados::findOrFail($request->IdExtraPrima);
        // dd($extra_primado);
        $extra_primado->PorcentajeEP = $request->PorcentajeEP;
        // $extra_primado->PagoEP = $request->PagoEP;
        $extra_primado->update();
        alert()->success('El registro de poliza ha sido modificado correctamente');
        return redirect('polizas/deuda/' . $extra_primado->PolizaDeuda . '/edit?tab=7');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $deuda = Deuda::findOrFail($id);
        $deuda->Activo = 0;
        $deuda->update();

        alert()->success('Eliminada con exito');
        return back();
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
    { //función para convertir fechas  excel a fechas unix(que reconoce php)
        try {

            $unixDate = (intval($dateValue) - 25569) * 86400;
            return $unixDate = gmdate("d/m/Y", $unixDate);
        } catch (Exception $e) {
            return false;
        }
    }



    public function exportar_historial(Request $request)
    {

        return Excel::download(new HistoricoPagosExport($request), 'historico_pagos.xlsx');
    }

    public function exportar_nuevos_registros($id)
    {
        return Excel::download(new RegistrosNuevosExport($id), 'nuevos_registros.xlsx');
    }

    public function exportar_registros_eliminados($id)
    {
        return Excel::download(new RegistrosEliminadosExport($id), 'registros_eliminados.xlsx');
    }

    public function registros_no_validos($id)
    {
        return Excel::download(new CreditosNoValidoExport($id), 'creditos_no_validos.xlsx');
    }


    public function registros_requisitos($id)
    {
        return Excel::download(new RegistroRequisitosExport($id), 'creditos_con requisitos.xlsx');
    }
    public function registros_requisitos_recibos($id)
    {
        return Excel::download(new RegistroRequisitosReciboExport($id), 'creditos_con requisitos.xlsx');
    }

    public function extraprimados_excluidos($id)
    {
        return Excel::download(new ExtraPrimadosExcluidosExport($id), 'creditos_extraprimados.xlsx');
    }

    public function registros_edad_maxima($id)
    {
        return Excel::download(new EdadMaximaExport($id), 'creditos_edad_maxima.xlsx');
    }

    public function registros_erroneos($id)
    {
        return Excel::download(new DeudaErroneosExport($id), 'creditos_erroneos.xlsx');
    }



    public function registros_responsabilidad_maxima($id)
    {
        return Excel::download(new ResponsabilidadMaximaExport($id), 'creditos_responsabilidad_maxima.xlsx');
    }


    public function borrar_proceso_actual(Request $request)
    {
        // dd($request->deuda_id);

        PolizaDeudaCartera::where('PolizaDeuda', $request->deuda_id)->where('PolizaDeudaDetalle', 0)->delete();

        PolizaDeudaTempCartera::where('PolizaDeuda', $request->deuda_id)->delete();
        return redirect('polizas/deuda/' . $request->deuda_id . '/edit');
    }
    public function anular_pago($id)
    {
        $detalle = DeudaDetalle::findOrFail($id);
        $detalle->Activo = 0;
        $detalle->update();
        //recibo anulado
        DeudaHistorialRecibo::where('PolizaDeudaDetalle', $id)->update(['Activo' => 0]);

        PolizaDeudaCartera::where('PolizaDeudaDetalle', $id)->delete();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function delete_pago($id)
    {
        $detalle = DeudaDetalle::findOrFail($id);

        // recibo eliminado
        DeudaHistorialRecibo::where('PolizaDeudaDetalle', $id)->delete();

        PolizaDeudaCartera::where('PolizaDeudaDetalle', $id)->delete();
        $detalle->delete();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }


    public function cancelar_pago(Request $request)
    {
        // dd($request->Deuda);
        //  dd($request->MesCancelar);
        try {
            $poliza_temp = PolizaDeudaTempCartera::where('PolizaDeuda', '=', $request->Deuda)->where('User', '=', auth()->user()->id)->first();
            $poliza = PolizaDeudaCartera::where('PolizaDeuda', '=', $request->Deuda)->where('Mes', '=', $poliza_temp->Mes)
                ->where('Axo', '=', $poliza_temp->Axo)->where('User', '=', auth()->user()->id)
                ->delete();

            PolizaDeudaTempCartera::where('PolizaDeuda', '=', $request->Deuda)->delete();
            // dd($poliza);
        } catch (\Throwable $th) {
            //throw $th;
        }


        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }


    public function agregar_valido(Request $request)
    {
        $temp = PolizaDeudaTempCartera::findOrFail($request->id);
        $temp->NoValido = 0;
        $temp->OmisionPerfil = 1;
        $temp->update();

        $registro = new DeudaValidados();
        $registro->Dui = $temp->Dui;
        $registro->Nombre = $temp->Nombre;
        $registro->NumeroReferencia = $temp->NumeroReferencia;
        $registro->Poliza = $temp->PolizaDeuda; // Asegúrate de usar PolizaDeuda aquí
        $registro->Mes = $temp->Mes;
        $registro->Axo = $temp->Axo;
        $registro->Usuario = auth()->user()->id;
        $registro->save();

        return $registro;
    }

    public function agregar_validado(Request $request)
    {
        try {
            $temp = PolizaDeudaTempCartera::where('NumeroReferencia', $request->NumeroReferencia)->first();

            if (!$temp) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el registro temporal con el Número de Referencia proporcionado.'
                ], 404);
            }

            $registro = DeudaValidados::where('NumeroReferencia', $request->NumeroReferencia)
                ->where('Poliza', $temp->PolizaDeuda)
                ->first();

            if ($registro) {
                $registro->delete();
            } else {
                $registro = new DeudaValidados();
                $registro->Dui = $temp->Dui;
                $registro->Nombre = $temp->Nombre;
                $registro->NumeroReferencia = $temp->NumeroReferencia;
                $registro->Poliza = $temp->PolizaDeuda; // Asegúrate de usar PolizaDeuda aquí
                $registro->Mes = $temp->Mes;
                $registro->Axo = $temp->Axo;
                $registro->Usuario = auth()->user()->id;
                $registro->save();
            }

            $data = PolizaDeudaTempCartera::leftJoin(
                'poliza_deuda_validados',
                'poliza_deuda_validados.NumeroReferencia',
                '=',
                'poliza_deuda_temp_cartera.NumeroReferencia'
            )
                ->whereNull('poliza_deuda_validados.NumeroReferencia') // Filtra los que no tienen coincidencia
                ->where('poliza_deuda_temp_cartera.Dui', $temp->Dui)
                ->where('poliza_deuda_temp_cartera.NoValido', 0)
                ->count();




            return response()->json([
                'success' => true,
                'count' => $data,
                'message' => 'Operación realizada con éxito.'
            ]);
        } catch (\Exception $e) {
            // Registrar el error en los logs para su seguimiento
            Log::error('Error en agregar_validado: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function get_referencia_creditos($id)
    {
        $poliza = PolizaDeudaTempCartera::findOrFail($id);
        $polizas = PolizaDeudaTempCartera::select('Id', 'NumeroReferencia', 'TotalCredito')->where('Dui', $poliza->Dui)
            ->where('NoValido', 1)
            ->where('PolizaDeuda', $poliza->PolizaDeuda)->get();
        // Formatear saldo_total con 2 decimales
        foreach ($polizas as $poliza) {
            $poliza->saldo_total = number_format($poliza->TotalCredito, 2, '.', ',');
        }
        return response()->json($polizas);
    }

    public function get_creditos($poliza, Request $request)
    {
        $buscar = $request->buscar;
        $opcion = $request->opcion;
        $tipo_cartera = $request->tipo_cartera;
        $deuda = Deuda::findOrFail($poliza);
        $requisitos = $deuda->requisitos;

        if ($opcion == 1) {



            $poliza_cumulos = DB::table('poliza_deuda_temp_cartera as pdtc')
                ->leftJoin('saldos_montos as sm', 'pdtc.LineaCredito', '=', 'sm.id')
                ->leftJoin('tipo_cartera as tc', 'pdtc.PolizaDeudaTipoCartera', '=', 'tc.id') // Unir con la tabla tipo_cartera
                ->select(
                    'pdtc.Id',
                    'pdtc.Dui',
                    'pdtc.Edad',
                    'pdtc.Nit',
                    'pdtc.PrimerNombre',
                    'pdtc.SegundoNombre',
                    'pdtc.PrimerApellido',
                    'pdtc.SegundoApellido',
                    'pdtc.ApellidoCasada',
                    'pdtc.FechaNacimiento',
                    'pdtc.NumeroReferencia',
                    'pdtc.NoValido',
                    'pdtc.Perfiles',
                    'pdtc.EdadDesembloso',
                    'pdtc.FechaOtorgamiento',
                    'pdtc.NoValido',
                    'pdtc.Excluido',
                    'pdtc.MontoMaximoIndividual',
                    DB::raw("sum(pdtc.TotalCredito) as saldo_total"),
                    DB::raw("GROUP_CONCAT(DISTINCT pdtc.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                    DB::raw("GROUP_CONCAT(DISTINCT FORMAT(pdtc.TotalCredito, 2) SEPARATOR '- ') AS ConcatenatedMonto"),
                    'sm.Abreviatura as Abreviatura',
                    'tc.nombre AS TipoCarteraNombre' // Agregar el nombre de la TipoCartera
                )
                ->where('pdtc.NoValido', 1)
                ->where('pdtc.EdadDesembloso', '<=', $deuda->EdadMaximaTerminacion)
                ->where('pdtc.TotalCredito', '<=', $deuda->ResponsabilidadMaxima)
                ->where('pdtc.PolizaDeuda', $poliza)
                ->groupBy('pdtc.Dui')
                ->get();

            // dd($poliza_cumulos);

            $edades = DB::table('poliza_deuda_requisitos')
                ->where('Deuda', $poliza)
                ->selectRaw('MIN(EdadInicial) as EdadInicial, MAX(EdadFinal) as EdadFinal,MIN(MontoInicial) as MontoInicial,MAX(MontoFinal) as MontoFinal')
                ->first();
            //dd($edades,$poliza);


            foreach ($poliza_cumulos as $cumulo) {
                if ($cumulo->EdadDesembloso < $edades->EdadInicial || $cumulo->EdadDesembloso > $edades->EdadFinal) {
                    $cumulo->Motivo = 'Revisar edad ya que no se encuentra dentro de los rangos de asegurabilidad';
                } else if ($cumulo->saldo_total > $edades->MontoFinal) {
                    $cumulo->Motivo = 'La persona excede el límite de suma del rango de asegurabilidad';
                } else {
                    $cumulo->Motivo = 'La persona se encuentra fuera del rango de asegurabilidad';
                }
            }

            return view('polizas.deuda.get_creditos', compact('poliza_cumulos', 'opcion', 'requisitos'));
        } else {
            $tipo = 1;
            if ($request->buscar) {
                $tipo = $request->buscar;
            }


            if ($tipo == 1) {  //creditos con requisitos

                $poliza_cumulos = PolizaDeudaTempCartera::join('poliza_deuda_tipo_cartera as ptc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'ptc.Id')
                    ->leftJoin('poliza_deuda_cartera as pdcart', function ($join) {
                        $join->on('poliza_deuda_temp_cartera.NumeroReferencia', '=', 'pdcart.NumeroReferencia');
                    })
                    ->select(
                        'poliza_deuda_temp_cartera.Id',
                        'poliza_deuda_temp_cartera.PolizaDeuda',
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
                        //DB::raw("GROUP_CONCAT(DISTINCT poliza_deuda_temp_cartera.NumeroReferencia ORDER BY poliza_deuda_temp_cartera.FechaOtorgamientoDate ASC SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                        DB::raw('MAX(poliza_deuda_temp_cartera.EdadDesembloso) as EdadDesembloso'),
                        DB::raw('MAX(poliza_deuda_temp_cartera.FechaOtorgamientoDate) as FechaOtorgamiento'),
                        'poliza_deuda_temp_cartera.Excluido',
                        'poliza_deuda_temp_cartera.OmisionPerfil',
                        DB::raw('SUM(poliza_deuda_temp_cartera.TotalCredito) as saldo_total'),
                        'ptc.MontoMaximoIndividual as MontoMaximoIndividual'
                    )
                    ->where('poliza_deuda_temp_cartera.EdadDesembloso', '<=', $deuda->EdadMaximaTerminacion)
                    ->where('poliza_deuda_temp_cartera.NoValido', 0)
                    ->where('poliza_deuda_temp_cartera.PolizaDeuda', $poliza)
                    ->where('poliza_deuda_temp_cartera.OmisionPerfil', 0)
                    ->whereNull('pdcart.NumeroReferencia')
                    ->groupBy('poliza_deuda_temp_cartera.Dui')
                    ->get();


                foreach ($poliza_cumulos as $cumulo) {
                    $count = PolizaDeudaTempCartera::leftJoin(
                        'poliza_deuda_validados',
                        'poliza_deuda_validados.NumeroReferencia',
                        '=',
                        'poliza_deuda_temp_cartera.NumeroReferencia'
                    )
                        ->whereNull('poliza_deuda_validados.NumeroReferencia') // Filtra los que no tienen coincidencia
                        ->where('poliza_deuda_temp_cartera.Dui', $cumulo->Dui)
                        ->where('poliza_deuda_temp_cartera.NoValido', 0)
                        ->count();
                    $cumulo->Validado = $count;
                }
            } elseif ($tipo == 2) { // creditos validos
                $poliza_cumulos = PolizaDeudaTempCartera::join('poliza_deuda_tipo_cartera as ptc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'ptc.Id')
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
                        DB::raw("GROUP_CONCAT(DISTINCT poliza_deuda_temp_cartera.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                        DB::raw('MAX(poliza_deuda_temp_cartera.EdadDesembloso) as EdadDesembloso'),
                        DB::raw('MAX(poliza_deuda_temp_cartera.FechaOtorgamientoDate) as FechaOtorgamiento'),
                        'poliza_deuda_temp_cartera.Excluido',
                        'poliza_deuda_temp_cartera.OmisionPerfil',
                        'poliza_deuda_temp_cartera.SaldoCumulo as saldo_total',
                        'ptc.MontoMaximoIndividual as MontoMaximoIndividual'
                    )
                    ->where('poliza_deuda_temp_cartera.EdadDesembloso', '<=', $deuda->EdadMaximaTerminacion)
                    ->where('poliza_deuda_temp_cartera.NoValido', 0)
                    ->where('poliza_deuda_temp_cartera.PolizaDeuda', $poliza)
                    ->where('poliza_deuda_temp_cartera.OmisionPerfil', 1)
                    ->groupBy('poliza_deuda_temp_cartera.Dui')
                    ->get();
                return view('polizas.deuda.get_creditos', compact('poliza_cumulos', 'opcion', 'requisitos', 'tipo', 'deuda'));
            } elseif ($tipo == 3) { // creditos rehabilitados

                $poliza_cumulos = PolizaDeudaTempCartera::where('poliza_deuda_temp_cartera.PolizaDeuda', $poliza)
                    ->where('poliza_deuda_temp_cartera.NoValido', 0)
                    ->where('poliza_deuda_temp_cartera.Rehabilitado', 1)
                    ->select(
                        'poliza_deuda_temp_cartera.Id',
                        'poliza_deuda_temp_cartera.PolizaDeuda',
                        'poliza_deuda_temp_cartera.Dui',
                        'poliza_deuda_temp_cartera.Edad',
                        'poliza_deuda_temp_cartera.Nit',
                        'poliza_deuda_temp_cartera.PrimerNombre',
                        'poliza_deuda_temp_cartera.SegundoNombre',
                        'poliza_deuda_temp_cartera.PrimerApellido',
                        'poliza_deuda_temp_cartera.SegundoApellido',
                        'poliza_deuda_temp_cartera.ApellidoCasada',
                        'poliza_deuda_temp_cartera.FechaNacimiento',
                        'poliza_deuda_temp_cartera.NumeroReferencia as ConcatenatedNumeroReferencia',
                        'poliza_deuda_temp_cartera.NoValido',
                        'poliza_deuda_temp_cartera.Perfiles',
                        'poliza_deuda_temp_cartera.EdadDesembloso',
                        'poliza_deuda_temp_cartera.FechaOtorgamientoDate as FechaOtorgamiento',
                        'poliza_deuda_temp_cartera.Excluido',
                        'poliza_deuda_temp_cartera.OmisionPerfil',
                        'poliza_deuda_temp_cartera.TotalCredito as saldo_total'
                    )
                    //->groupBy('poliza_deuda_temp_cartera.NumeroReferencia')
                    ->get();

                $meses = array(
                    "",
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                );


                foreach ($poliza_cumulos as $cumulo) {
                    $registro = PolizaDeudaCartera::where('NumeroReferencia', $cumulo->ConcatenatedNumeroReferencia)->orderBy('Id', 'desc')->first();

                    if ($registro) {
                        $cumulo->UltimoRegistro =  $meses[$registro->Mes]  . '-' .  $registro->Axo;
                    }
                }



                $poliza_cumulos =  $poliza_cumulos->where('Id', '<>', null);
            } elseif ($tipo == 4) { // creditos fuera del monto limite

                $registros_cartera = PolizaDeudaCartera::where('Id', $poliza)->count();
                if ($registros_cartera > 0) {
                    $registrosValidados = DeudaValidados::where('Poliza', $poliza)->pluck('NumeroReferencia')->toArray();

                    $poliza_cumulos = PolizaDeudaTempCartera::join('poliza_deuda_tipo_cartera as ptc', 'poliza_deuda_temp_cartera.PolizaDeudaTipoCartera', '=', 'ptc.Id')
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
                            'poliza_deuda_temp_cartera.NumeroReferencia  AS ConcatenatedNumeroReferencia',
                            'poliza_deuda_temp_cartera.EdadDesembloso as EdadDesembloso',
                            'poliza_deuda_temp_cartera.FechaOtorgamientoDate as FechaOtorgamiento',
                            'poliza_deuda_temp_cartera.Excluido',
                            'poliza_deuda_temp_cartera.OmisionPerfil',
                            'poliza_deuda_temp_cartera.TotalCredito as saldo_total',
                            'ptc.MontoMaximoIndividual as MontoMaximoIndividual'
                        )
                        ->where('poliza_deuda_temp_cartera.PolizaDeuda', $poliza)
                        ->whereIn('poliza_deuda_temp_cartera.NumeroReferencia', $registrosValidados)
                        ->get();
                } else {
                    $poliza_cumulos = PolizaDeudaTempCartera::where('Id', 0)->get();
                }



                return view('polizas.deuda.get_creditos', compact('poliza_cumulos', 'opcion', 'requisitos', 'tipo', 'deuda'));
            }
        }

        return view('polizas.deuda.get_creditos', compact('poliza_cumulos', 'opcion', 'requisitos', 'tipo', 'deuda'));
    }


    public function get_creditos_detalle($documento, $poliza, $tipo)
    {


        $data = PolizaDeudaTempCartera::where('NoValido', 0)
            ->where('PolizaDeuda', $poliza)
            ->where(function ($query) use ($documento) {
                $query->where('Dui', $documento)
                    ->orWhere('Nit', $documento);
            })
            ->orderBy('FechaOtorgamientoDate')
            ->get();

        foreach ($data as $obj) {
            $count = DeudaValidados::where('NumeroReferencia', $obj->NumeroReferencia)->where('Poliza', $obj->PolizaDeuda)->count();
            $obj->Validado = $count;
        }

        return view('polizas.deuda.get_creditos_detalle', compact('data', 'tipo'));
    }



    public function get_historico(Request $request)
    {
        // Obtener las fechas en formato YYYYMMDD
        $fechaInicio = $request->input('FechaInicio');
        $fechaFinal = $request->input('FechaFinal');

        // Convertir las fechas a un formato legible usando Carbon
        $fechaInicio = Carbon::createFromFormat('Ymd', $fechaInicio)->format('Y-m-d');
        $fechaFinal = Carbon::createFromFormat('Ymd', $fechaFinal)->format('Y-m-d');

        $tabla_historico = DB::table('poliza_deuda_cartera as pdtc')
            ->select(
                'pdtc.Id',
                'pdtc.Dui',
                'pdtc.Edad',
                'pdtc.Nit',
                'pdtc.PrimerNombre',
                'pdtc.SegundoNombre',
                'pdtc.PrimerApellido',
                'pdtc.SegundoApellido',
                'pdtc.ApellidoCasada',
                'pdtc.FechaNacimiento',
                'pdtc.NumeroReferencia',
                'pdtc.NoValido',
                'pdtc.EdadDesembloso',
                'pdtc.FechaOtorgamiento',
                'pdtc.NoValido',
                'pdtc.NumeroReferencia AS ConcatenatedNumeroReferencia',
                DB::raw('SUM(pdtc.saldo_total) as total_saldo'),
                //DB::raw("GROUP_CONCAT(DISTINCT pdtc.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                //  DB::raw('SUM(SaldoCapital) as saldo_cpital'),
                DB::raw('SUM(pdtc.SaldoCapital) as saldo_capital'),
                DB::raw('SUM(pdtc.Intereses) as total_interes'),
                DB::raw('SUM(pdtc.InteresesCovid) as total_covid'),
                DB::raw('SUM(pdtc.InteresesMoratorios) as total_moratorios'),
                DB::raw('SUM(pdtc.MontoNominal) as total_monto_nominal'),
                'pdc.MontoMaximoIndividual as MontoMaximoIndividual',
                'sm.Abreviatura as Abreviatura',
                'tc.nombre AS TipoCarteraNombre' // Agregar el nombre de la TipoCartera
            )
            ->join('poliza_deuda_creditos as pdc', 'pdtc.LineaCredito', '=', 'pdc.Id')
            ->join('saldos_montos as sm', 'pdc.saldos', '=', 'sm.id')
            ->join('tipo_cartera as tc', 'pdc.TipoCartera', '=', 'tc.id') // Unir con la tabla tipo_cartera
            ->where('pdtc.NoValido', 0)
            ->where('Axo', $request->Axo)
            ->where('Mes', $request->Mes)
            ->where('FechaInicio', $request->FechaInicio)
            ->where('FechaFinal', $request->FechaFinal)
            ->where('PolizaDeuda', $request->PolizaDeuda)
            ->groupBy('pdtc.Dui', 'pdtc.NumeroReferencia')
            ->get();

        return view('polizas.deuda.get_historico', compact('tabla_historico'));
    }
}
