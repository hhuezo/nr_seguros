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
use App\Imports\PolizaDeudaTempCarteraImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
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
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\DeudaCreditosValidos;
use App\Models\polizas\DeudaDetalle;
use App\Models\polizas\DeudaEliminados;
use App\Models\polizas\DeudaHistorialRecibo;
use App\Models\polizas\DeudaRequisitos;
use App\Models\polizas\DeudaValidados;
use App\Models\polizas\DeudaVida;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\polizas\PolizaDeudaExtraPrimadosMensual;
use App\Models\polizas\PolizaDeudaHistorica;
use App\Models\polizas\PolizaDeudaTasaDiferenciada;
use App\Models\polizas\PolizaDeudaTasaDiferenciadaTemp;
use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        $creditos = DeudaCredito::where('Activo', 1)->where('Deuda', $id)->get();
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

        // Crear una instancia de Carbon a partir de la fecha
        $fecha = Carbon::parse($deuda->VigenciaHasta);

        // Agregar un año a la fecha
        $nuevaFecha  = $fecha->copy()->addYear();
        $fechaHastaRenovacion = $nuevaFecha->format('Y-m-d');

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
        session(['tab' => 1]);

        return view('polizas.deuda.renovar', compact('cliente', 'planes', 'productos', 'aseguradora', 'deuda','fechaHastaRenovacion', 'estadoPoliza', 'ejecutivo', 'creditos', 'perfiles', 'columnas', 'tabla', 'columnas', 'tipoCartera', 'saldos'));
    }
    public function renovar_conf($id)
    {
        $deuda = Deuda::findOrFail($id);
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $creditos = DeudaCredito::where('Activo', 1)->where('Deuda', $id)->get();
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
        session(['tab' => 2]);

        return view('polizas.deuda.renovar', compact('cliente', 'planes', 'productos', 'aseguradora', 'deuda', 'estadoPoliza', 'ejecutivo', 'creditos', 'perfiles', 'columnas', 'tabla', 'columnas', 'tipoCartera', 'saldos'));
    }

    public function save_renovar(Request $request)
    {
        //dd('holi');

        $deuda = Deuda::findOrFail($request->Id);
        if (($deuda->VigenciaDesde == $request->VigenciaDesde && $deuda->VigenciaHasta == $request->VigenciaHasta)
            || $deuda->VigenciaDesde == $request->VigenciaDesde || $deuda->VigenciaHasta == $request->VigenciaHasta
        ) {
            alert()->error('Debe cambiar las fechas de vigencia para la renovación');
            return back();
        }

        //     dd('holi');

        $creditos = DeudaCredito::where('Deuda', $deuda->Id)->get();
        $detalle = DeudaDetalle::where('Deuda', $deuda->Id)->get();
        $tabla_diferencia = PolizaDeudaTasaDiferenciada::whereIn('PolizaDuedaCredito', $creditos->pluck('Id')->toArray())->get();
        $requisitos = DeudaRequisitos::where('Deuda', $deuda->Id)->get();
        //guardar todo en una tabla historica
        $historica = new PolizaDeudaHistorica();
        $historica->Deuda = $deuda->Id;
        $historica->DatosDeuda = json_encode($deuda);
        $historica->DatosCreditos = json_encode($creditos);
        $historica->DatosTablaDiferenciada = json_encode($tabla_diferencia);
        $historica->DeudaDetalle = json_encode($detalle);
        $historica->Requisito = json_encode($requisitos);
        $historica->Fecha = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
        $historica->Usuario = auth()->user()->id;
        $historica->save();

        //actualizar los datos de la renovacion
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
        $deuda->Configuracion = 0; // se habilita para configurar nuevamente
        if ($request->ComisionIva == 'on') {
            $deuda->ComisionIva = 1;
        } else {
            $deuda->ComisionIva = 0;
        }
        $deuda->Usuario = auth()->user()->id;
        $deuda->FechaIngreso = Carbon::now('America/El_Salvador');
        $deuda->update();



        return redirect('poliza/deuda/configuracion_renovar/' . $deuda->Id);
        // return view('polizas.deuda.renovar_conf', compact('cliente', 'planes', 'productos', 'aseguradora', 'deuda', 'estadoPoliza', 'ejecutivo', 'creditos', 'perfiles', 'columnas', 'tabla', 'columnas', 'tipoCartera', 'saldos'));
    }

    public function conf_renovar($id)
    {
        // dd('holi');
        //enviar a la vista
        $deuda = Deuda::findOrFail($id);
        $id = $deuda->Id;
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $creditos = DeudaCredito::where('Activo', 1)->where('Deuda', $id)->get();
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
        session(['tab' => 2]);
        return view('polizas.deuda.renovar_conf', compact('cliente', 'planes', 'productos', 'aseguradora', 'deuda', 'estadoPoliza', 'ejecutivo', 'creditos', 'perfiles', 'columnas', 'tabla', 'columnas', 'tipoCartera', 'saldos'));
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

        PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->where('PolizaDeuda', $request->Deuda)->delete();
        $cartera = PolizaDeudaCartera::where('FechaInicio', '=', $request->FechaInicio)->where('FechaFinal', '=', $request->FechaFinal)->update(['PolizaDeudaDetalle' => $detalle->Id]);

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
            $pdf = \PDF::loadView('polizas.deuda.recibo', compact('recibo_historial', 'detalle', 'deuda', 'meses'))->setWarnings(false)->setPaper('letter');
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
        $creditos = DeudaCredito::where('Activo', 1)->where('Deuda', $id)->get();
        //dd( $creditos);
        $saldos = SaldoMontos::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $deuda->Aseguradora)->get();

        return view('polizas.deuda.show', compact(
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
            'creditos',
            'tipoCartera',
            //'data',
            'tabla',
            'columnas'
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


    public function tasa_diferenciada($id)
    {
        $deuda_credito = DeudaCredito::find($id);
        $deuda = Deuda::find($deuda_credito->Deuda);
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
        $saldos = SaldoMontos::where('Activo', 1)->get();

        return view('polizas.deuda.tasa_diferenciada', compact('deuda', 'deuda_credito', 'tipoCartera', 'saldos'));
    }

    public function tasa_diferenciada_store(Request $request)
    {
        // Validación dinámica según el valor de TipoCalculo
        $rules = [
            'TipoCalculo' => 'required|in:1,2', // Debe ser 1 o 2
            'Tasa' => 'required|numeric|min:0', // Siempre requerido
        ];

        if ($request->TipoCalculo == 1) {
            // Si es 1, FechaDesde y FechaHasta son requeridos
            $rules['FechaDesde'] = 'required|date';
            $rules['FechaHasta'] = 'required|date|after_or_equal:FechaDesde';
        }

        if ($request->TipoCalculo == 2) {
            // Si es 2, EdadDesde y EdadHasta son requeridos
            $rules['EdadDesde'] = 'required|integer|min:0';
            $rules['EdadHasta'] = 'required|integer|gte:EdadDesde';
        }

        $messages = [
            'TipoCalculo.required' => 'El campo Tipo de Cálculo es requerido.',
            'TipoCalculo.in' => 'Seleccione un tipo de cálculo válido.',
            'FechaDesde.required' => 'La fecha de inicio es obligatoria.',
            'FechaHasta.required' => 'La fecha final es obligatoria.',
            'FechaHasta.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'EdadDesde.required' => 'La edad de inicio es obligatoria.',
            'EdadHasta.required' => 'La edad final es obligatoria.',
            'EdadHasta.min' => 'La edad final debe ser mayor o igual a la edad de inicio.',
            'Tasa.required' => 'La tasa es obligatoria.',
            'Tasa.numeric' => 'La tasa debe ser un número.',
            'Tasa.min' => 'La tasa debe ser mayor o igual a 0.',
        ];

        $request->validate($rules, $messages);

        $tasa_diferenciada = new PolizaDeudaTasaDiferenciada();
        $tasa_diferenciada->PolizaDuedaCredito = $request->Id;
        $tasa_diferenciada->TipoCalculo = $request->TipoCalculo;

        if ($request->TipoCalculo == 1) {
            $tasa_diferenciada->FechaDesde = $request->FechaDesde;
            $tasa_diferenciada->FechaHasta = $request->FechaHasta;
        } else {
            $tasa_diferenciada->FechaDesde = null;
            $tasa_diferenciada->FechaHasta = null;
        }

        if ($request->TipoCalculo == 2) {
            $tasa_diferenciada->EdadDesde = $request->EdadDesde;
            $tasa_diferenciada->EdadHasta = $request->EdadHasta;
        } else {
            $tasa_diferenciada->EdadDesde = null;
            $tasa_diferenciada->EdadHasta = null;
        }

        $tasa_diferenciada->Tasa = $request->Tasa;
        $tasa_diferenciada->Usuario = auth()->user()->id;
        $tasa_diferenciada->save();

        alert()->success('El registro ha sido configurado correctamente');
        return back();
    }

    public function tasa_diferenciada_update(Request $request, $id)
    {

        // Validación dinámica según el valor de TipoCalculo
        $rules = [
            'TipoCalculo' => 'required|in:1,2', // Debe ser 1 o 2
            'Tasa' => 'required|numeric|min:0', // Siempre requerido
        ];

        if ($request->TipoCalculo == 1) {
            // Si es 1, FechaDesde y FechaHasta son requeridos
            $rules['FechaDesde'] = 'required|date';
            $rules['FechaHasta'] = 'required|date|after_or_equal:FechaDesde';
        }

        if ($request->TipoCalculo == 2) {
            // Si es 2, EdadDesde y EdadHasta son requeridos
            $rules['EdadDesde'] = 'required|integer|min:0';
            $rules['EdadHasta'] = 'required|integer|gte:EdadDesde';
        }

        $messages = [
            'TipoCalculo.required' => 'El campo Tipo de Cálculo es requerido.',
            'TipoCalculo.in' => 'Seleccione un tipo de cálculo válido.',
            'FechaDesde.required' => 'La fecha de inicio es obligatoria.',
            'FechaHasta.required' => 'La fecha final es obligatoria.',
            'FechaHasta.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'EdadDesde.required' => 'La edad de inicio es obligatoria.',
            'EdadHasta.required' => 'La edad final es obligatoria.',
            'EdadHasta.min' => 'La edad final debe ser mayor o igual a la edad de inicio.',
            'Tasa.required' => 'La tasa es obligatoria.',
            'Tasa.numeric' => 'La tasa debe ser un número.',
            'Tasa.min' => 'La tasa debe ser mayor o igual a 0.',
        ];

        $request->validate($rules, $messages);

        $tasa_diferenciada = PolizaDeudaTasaDiferenciada::find($id);
        $tasa_diferenciada->PolizaDuedaCredito = $request->Id;
        $tasa_diferenciada->TipoCalculo = $request->TipoCalculo;

        if ($request->TipoCalculo == 1) {
            $tasa_diferenciada->FechaDesde = $request->FechaDesde;
            $tasa_diferenciada->FechaHasta = $request->FechaHasta;
        } else {
            $tasa_diferenciada->FechaDesde = null;
            $tasa_diferenciada->FechaHasta = null;
        }

        if ($request->TipoCalculo == 2) {
            $tasa_diferenciada->EdadDesde = $request->EdadDesde;
            $tasa_diferenciada->EdadHasta = $request->EdadHasta;
        } else {
            $tasa_diferenciada->EdadDesde = null;
            $tasa_diferenciada->EdadHasta = null;
        }

        $tasa_diferenciada->Tasa = $request->Tasa;
        $tasa_diferenciada->Usuario = auth()->user()->id;
        $tasa_diferenciada->save();

        alert()->success('El registro ha sido configurado correctamente');
        return back();
    }

    public function tasa_diferenciada_destroy($id)
    {
        $tasa = PolizaDeudaTasaDiferenciada::find($id);
        $tasa->delete();
        alert()->success('El registro ha sido eliminado correctamente');
        return back();
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

    public function agregar_credito(Request $request)
    {
        if ($request->EdadDesde < 18 && $request->EdadDesde != null) {
            alert()->error('Debe de tener ser mayor o igual a 18 años');
        } else {
            $credito = new DeudaCredito();
            $credito->Deuda = $request->Deuda;
            $credito->Saldos = $request->Saldos;
            $credito->TipoCartera = $request->TipoCartera;
            $credito->MontoMaximoIndividual = $request->MontoMaximoIndividual;
            $credito->Activo = 1;
            $credito->Usuario = auth()->user()->id;
            $credito->save();
            alert()->success('El registro de poliza ha sido ingresado correctamente');
        }
        session(['tab' => 2]);
        return redirect('polizas/deuda/' . $request->Deuda);
    }


    public function update_credito(Request $request, $id)
    {
        $credito = DeudaCredito::findOrFail($id);
        $credito->Saldos = $request->Saldos;
        $credito->TipoCartera = $request->TipoCartera;
        $credito->MontoMaximoIndividual = $request->MontoMaximoIndividual;
        //$credito->TasaGeneral = $request->Tasa;
        $credito->save();
        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return back();
    }







    public function eliminar_extraprima(Request $request)
    {
        $extra = PolizaDeudaExtraPrimados::findOrFail($request->IdExtraPrima)->delete();
        alert()->success('El registro ha sido eliminado correctamente');
        return back();
    }

    public function eliminar_credito($id)
    {
        $credito = DeudaCredito::findOrFail($id);
        $credito->Activo = 0;
        $credito->update();
        session(['tab' => 2]);
        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/deuda/' . $credito->Deuda);
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

            $creditos = DeudaCredito::where('Deuda', $deuda->Id)->where('Activo', 1)->get();

            PolizaDeudaTasaDiferenciadaTemp::where('PolizaDueda', $deuda->Id)->delete();



            foreach ($creditos as $credito) {

                if ($credito->tasasDiferenciadas->count() > 0) {
                    foreach ($credito->tasasDiferenciadas as $tasa_diferenciada) {

                        $temp = new PolizaDeudaTasaDiferenciadaTemp();
                        $temp->PolizaDuedaCredito = $credito->Id;
                        $temp->PolizaDueda = $deuda->Id;
                        $temp->FechaDesde = $tasa_diferenciada->FechaDesde;
                        $temp->FechaHasta = $tasa_diferenciada->FechaHasta;
                        $temp->EdadDesde = $tasa_diferenciada->EdadDesde;
                        $temp->EdadHasta = $tasa_diferenciada->EdadHasta;
                        $temp->TipoCalculo = $tasa_diferenciada->TipoCalculo;
                        $temp->Tasa = $tasa_diferenciada->Tasa;
                        $temp->EsTasaDiferenciada = 1;
                        $temp->Usuario = auth()->user()->id;
                        $temp->save();
                    }
                } else {
                    $temp = new PolizaDeudaTasaDiferenciadaTemp();
                    $temp->PolizaDuedaCredito = $credito->Id;
                    $temp->PolizaDueda = $deuda->Id;
                    $temp->Tasa = $deuda->Tasa;
                    $temp->EsTasaDiferenciada = 0;
                    $temp->Usuario = auth()->user()->id;
                    $temp->save();
                }
            }

            $tempTasaDiferenciada = PolizaDeudaTasaDiferenciadaTemp::where('PolizaDueda', $deuda->Id)->get();

            //dd($tempTasaDiferenciada);
            $lineas_credito = collect(); // Inicializa una colección vacía

            foreach ($tempTasaDiferenciada as $temp) {
                if ($temp->EsTasaDiferenciada == 1) {
                    // 1- Por fecha
                    if ($temp->TipoCalculo == 1) {
                        $result = DB::table('poliza_deuda_cartera as poliza')
                            ->join('poliza_deuda_creditos as creditos', 'poliza.LineaCredito', '=', 'creditos.Id')
                            ->join('saldos_montos as saldos', 'creditos.Saldos', '=', 'saldos.Id')
                            ->join('tipo_cartera as tipo', 'creditos.TipoCartera', '=', 'tipo.Id')
                            ->select(
                                'poliza.LineaCredito',
                                'saldos.Descripcion',
                                'saldos.Abreviatura as Abrev',
                                DB::raw("CONCAT(saldos.Abreviatura, poliza.LineaCredito," . $temp . ") as Abreviatura"),
                                'tipo.Nombre as tipo',
                                DB::raw("IFNULL(sum(poliza.MontoOtorgado), '0.00') as MontoOtorgado"),
                                DB::raw("IFNULL(sum(poliza.SaldoCapital), '0.00') as SaldoCapital"),
                                DB::raw("IFNULL(sum(poliza.Intereses), '0.00') as Intereses"),
                                DB::raw("IFNULL(sum(poliza.MontoNominal), '0.00') as MontoNominal"),
                                DB::raw("IFNULL(sum(poliza.InteresesCovid), '0.00') as InteresesCovid"),
                                DB::raw("IFNULL(sum(poliza.InteresesMoratorios), '0.00') as InteresesMoratorios"),
                                DB::raw("IFNULL(sum(poliza.saldo_total), '0.00') as saldo_total")
                            )
                            ->where(function ($query) {
                                $query->where('PolizaDeudaDetalle', null)
                                    ->orWhere('PolizaDeudaDetalle', 0);
                            })
                            ->where('poliza.PolizaDeuda', $id)
                            ->where('poliza.LineaCredito', $temp->PolizaDuedaCredito)
                            ->whereBetween('poliza.FechaOtorgamiento', [$temp->FechaDesde, $temp->FechaHasta])
                            ->get();

                        // Agregar Tasa a cada elemento del resultado
                        if ($result->first()->LineaCredito != null) {
                            foreach ($result as $item) {
                                $item->Tasa = $temp->Tasa;
                                $item->TipoCalculo = $temp->TipoCalculo;
                                $item->FechaDesde = $temp->FechaDesde;
                                $item->FechaHasta = $temp->FechaHasta;
                                $item->EdadDesde = $temp->EdadDesde;
                                $item->EdadHasta = $temp->EdadHasta;
                                $item->EsTasaDiferenciada = $temp->EsTasaDiferenciada;
                                $item->Abreviatura = $temp->Abreviatura . $temp->Id;
                            }

                            $lineas_credito = $lineas_credito->merge($result);
                        }
                    }
                    // 2- Por edad
                    else if ($temp->TipoCalculo == 2) {
                        $result = DB::table('poliza_deuda_cartera as poliza')
                            ->join('poliza_deuda_creditos as creditos', 'poliza.LineaCredito', '=', 'creditos.Id')
                            ->join('saldos_montos as saldos', 'creditos.Saldos', '=', 'saldos.Id')
                            ->join('tipo_cartera as tipo', 'creditos.TipoCartera', '=', 'tipo.Id')
                            ->select(
                                'poliza.LineaCredito',
                                'saldos.Descripcion',
                                'saldos.Abreviatura as Abrev',
                                DB::raw("CONCAT(saldos.Abreviatura, poliza.LineaCredito) as Abreviatura"),
                                'tipo.Nombre as tipo',
                                DB::raw("IFNULL(sum(poliza.MontoOtorgado), '0.00') as MontoOtorgado"),
                                DB::raw("IFNULL(sum(poliza.SaldoCapital), '0.00') as SaldoCapital"),
                                DB::raw("IFNULL(sum(poliza.Intereses), '0.00') as Intereses"),
                                DB::raw("IFNULL(sum(poliza.MontoNominal), '0.00') as MontoNominal"),
                                DB::raw("IFNULL(sum(poliza.InteresesCovid), '0.00') as InteresesCovid"),
                                DB::raw("IFNULL(sum(poliza.InteresesMoratorios), '0.00') as InteresesMoratorios"),
                                DB::raw("IFNULL(sum(poliza.saldo_total), '0.00') as saldo_total")
                            )
                            ->where(function ($query) {
                                $query->where('PolizaDeudaDetalle', null)
                                    ->orWhere('PolizaDeudaDetalle', 0);
                            })
                            ->where('poliza.PolizaDeuda', $id)
                            ->where('poliza.LineaCredito', $temp->PolizaDuedaCredito)
                            ->whereBetween('poliza.EdadDesembloso', [$temp->EdadDesde, $temp->EdadHasta])
                            ->get();

                        // Agregar Tasa a cada elemento del resultado
                        if ($result->first()->LineaCredito != null) {
                            foreach ($result as $item) {
                                $item->Tasa = $temp->Tasa;
                                $item->TipoCalculo = $temp->TipoCalculo;
                                $item->FechaDesde = $temp->FechaDesde;
                                $item->FechaHasta = $temp->FechaHasta;
                                $item->EdadDesde = $temp->EdadDesde;
                                $item->EdadHasta = $temp->EdadHasta;
                                $item->EsTasaDiferenciada = $temp->EsTasaDiferenciada;
                                $item->Abreviatura = $item->Abreviatura . $temp->Id;
                            }
                            $lineas_credito = $lineas_credito->merge($result);
                        }
                    }
                } else {
                    $result = DB::table('poliza_deuda_cartera as poliza')
                        ->join('poliza_deuda_creditos as creditos', 'poliza.LineaCredito', '=', 'creditos.Id')
                        ->join('saldos_montos as saldos', 'creditos.Saldos', '=', 'saldos.Id')
                        ->join('tipo_cartera as tipo', 'creditos.TipoCartera', '=', 'tipo.Id')
                        ->select(
                            'poliza.LineaCredito',
                            'saldos.Descripcion',
                            'saldos.Abreviatura as Abrev',
                            DB::raw("CONCAT(saldos.Abreviatura, poliza.LineaCredito) as Abreviatura"),
                            'tipo.Nombre as tipo',
                            DB::raw("IFNULL(sum(poliza.MontoOtorgado), 0.00) as MontoOtorgado"),
                            DB::raw("IFNULL(sum(poliza.SaldoCapital), 0.00) as SaldoCapital"),
                            DB::raw("IFNULL(sum(poliza.Intereses), 0.00) as Intereses"),
                            DB::raw("IFNULL(sum(poliza.MontoNominal), 0.00) as MontoNominal"),
                            DB::raw("IFNULL(sum(poliza.InteresesCovid), 0.00) as InteresesCovid"),
                            DB::raw("IFNULL(sum(poliza.InteresesMoratorios), 0.00) as InteresesMoratorios"),
                            DB::raw("IFNULL(sum(poliza.saldo_total), 0.00) as saldo_total")
                        )
                        ->where('poliza.PolizaDeuda', $id)
                        ->where(function ($query) {
                            $query->whereNull('poliza.PolizaDeudaDetalle')
                                ->orWhere('poliza.PolizaDeudaDetalle', 0);
                        })
                        ->where('poliza.LineaCredito', $temp->PolizaDuedaCredito)
                        ->get();


                    if ($result->first()->LineaCredito != null) {
                        foreach ($result as $item) {
                            $item->EsTasaDiferenciada = 0;
                        }

                        $lineas_credito = $lineas_credito->merge($result);
                    }
                }
            }
            //dd($lineas_credito);

            /*;

            $lineas_credito = DB::table('poliza_deuda_cartera as poliza')
                ->join('poliza_deuda_creditos as creditos', 'poliza.LineaCredito', '=', 'creditos.Id')
                ->join('saldos_montos as saldos', 'creditos.Saldos', '=', 'saldos.Id')
                ->join('tipo_cartera as tipo', 'creditos.TipoCartera', '=', 'tipo.Id')
                ->select(
                    'poliza.LineaCredito',
                    'saldos.Descripcion',
                    'saldos.Abreviatura as Abrev',
                    DB::raw("CONCAT(saldos.Abreviatura, poliza.LineaCredito) as Abreviatura"),
                    'tipo.Nombre as tipo',
                    DB::raw("IFNULL(sum(poliza.MontoOtorgado), '0.00') as MontoOtorgado"),
                    DB::raw("IFNULL(sum(poliza.SaldoCapital), '0.00') as SaldoCapital"),
                    DB::raw("IFNULL(sum(poliza.Intereses), '0.00') as Intereses"),
                    DB::raw("IFNULL(sum(poliza.MontoNominal), '0.00') as MontoNominal"),
                    DB::raw("IFNULL(sum(poliza.InteresesCovid), '0.00') as InteresesCovid"),
                    DB::raw("IFNULL(sum(poliza.InteresesMoratorios), '0.00') as InteresesMoratorios"),
                    DB::raw("IFNULL(sum(poliza.saldo_total), '0.00') as saldo_total")
                )
                ->where('poliza.PolizaDeuda', $id)
                ->where(function ($query) {
                    $query->where('PolizaDeudaDetalle', null)
                        ->orWhere('PolizaDeudaDetalle', 0);
                })
                ->groupBy('poliza.LineaCredito')
                ->get();

            //dd($lineas_credito);*/


            $lineas_abreviatura = $lineas_credito->pluck('Abreviatura')->toArray();
            //dd($lineas_abreviatura);


            $videuda = DeudaVida::where('Deuda', $deuda->Id)->first();
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
            $ultimo_pago = DeudaDetalle::where('Deuda', $deuda->Id)->where('Activo', 1) //->where('PagoAplicado', '<>', null)
                ->orderBy('Id', 'desc')->first();
            // dd($ultimo_pago,$detalle);

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

            $clientes = PolizaDeudaCartera::
                //  join('poliza_deuda_creditos as cred','cred.Id','=','poliza_deuda_cartera.LineaCredito')
                //->join('saldos_montos as sal','sal.Id','=','cre.Saldos')
                //->
                select(
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
                )->where('PolizaDeuda', '=', $id)->where('PolizaDeudaDetalle', '=', 0)->orWhere('PolizaDeudaDetalle', '=', null)->groupBy('NumeroReferencia')->get();

            // dd($clientes->take(20));




            $ultimo_pago_fecha_final = null;
            if ($ultimo_pago) {
                $fecha_inicial = Carbon::parse($ultimo_pago->FechaFinal);
                $fecha_final_temp = $fecha_inicial->addMonth();
                $ultimo_pago_fecha_final = $fecha_final_temp->format('Y-m-d');
                $fecha1 = PolizaDeudaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
                    ->where('PolizaDeuda', '=', $id)
                    ->where('PolizaDeudaDetalle', '=', $ultimo_pago->Id)
                    ->orderByDesc('Id')->first();
            } else {
                $ultimo_pago = '';
                $fecha1 = null;
            }

            $fecha = PolizaDeudaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
                ->where('PolizaDeuda', '=', $id)
                ->where(function ($query) {
                    $query->where('PolizaDeudaDetalle', '=', 0)
                        ->orWhere('PolizaDeudaDetalle', '=', null);
                })
                ->orderByDesc('Id')->first();

            $saldo = 0;
            if ($fecha) {
                $creditos = DeudaCredito::where('Deuda', '=', $id)->where('Activo', 1)->get();
                $montos = 0;
                foreach ($creditos as $obj) {
                    switch ($obj->Saldos) {
                        case '1':
                            # saldo a capital
                            $saldo = $this->calcularCarteraINS1($deuda, $creditos, $obj->Id, $fecha);
                            //  dd($saldo);
                            break;
                        case '2':

                            # saldo a capital mas intereses
                            $saldo = $this->calcularCarteraINS2($deuda, $creditos, $obj->Id, $fecha);
                            //  dd($saldo);
                            break;
                        case '3':
                            # saldo a capital mas intereses mas covid
                            $saldo = $this->calcularCarteraINS3($deuda, $creditos, $obj->Id, $fecha);
                            break;
                        case '4':
                            # saldo a capital as intereses mas covid mas moratorios
                            $saldo = $this->calcularCarteraINS4($deuda, $creditos, $obj->Id, $fecha);
                            break;
                        case '5':
                            # monto nominal
                            $saldo = $this->calcularCarteraINS5($deuda, $creditos, $obj->Id, $fecha);
                            break;
                        default:
                            # .monto otorgado
                            $saldo = $this->calcularCarteraINS6($deuda, $creditos, $obj->Id, $fecha);
                            break;
                    }

                    $obj->TotalLiniaCredito = $saldo;
                }
            }



            $saldo1 = 0;
            if ($fecha1) {
                $creditos1 = DeudaCredito::where('Deuda', '=', $id)->where('Activo', 1)->get();
                $montos = 0;
                foreach ($creditos1 as $obj) {
                    switch ($obj->Saldos) {
                        case '1':
                            # saldo a capital
                            $saldo1 = $this->calcularCarteraINS1($deuda, $creditos1, $obj->Id, $fecha1);
                            //  dd($saldo);
                            break;
                        case '2':

                            # saldo a capital mas intereses
                            $saldo1 = $this->calcularCarteraINS2($deuda, $creditos1, $obj->Id, $fecha1);
                            //  dd($saldo);
                            break;
                        case '3':
                            # saldo a capital mas intereses mas covid
                            $saldo1 = $this->calcularCarteraINS3($deuda, $creditos1, $obj->Id, $fecha1);
                            break;
                        case '4':
                            # saldo a capital as intereses mas covid mas moratorios
                            $saldo1 = $this->calcularCarteraINS4($deuda, $creditos1, $obj->Id, $fecha1);
                            break;
                        case '5':
                            # monto nominal
                            $saldo1 = $this->calcularCarteraINS5($deuda, $creditos1, $obj->Id, $fecha1);
                            break;
                        default:
                            # .monto otorgado
                            $saldo1 = $this->calcularCarteraINS6($deuda, $creditos1, $obj->Id, $fecha1);
                            break;
                    }

                    $obj->TotalLiniaCredito = $saldo1;
                }
            } else {
                $creditos1 = [];
            }

            $now = Carbon::now('America/El_Salvador');

            return view('polizas.deuda.edit', compact(
                'now',
                'historico',
                'creditos1',
                'fecha',
                'total_extrapima',
                'saldo',
                'clientes',
                'extraprimados',
                'ultimo_pago_fecha_final',
                'meses',
                'primerDia',
                'ultimoDia',
                'detalle',
                'videuda',
                'deuda',
                'creditos',
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
                'lineas_credito',
                'lineas_abreviatura',
                'data_temp_count',
                'id'
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
        $pdf = \PDF::loadView('polizas.deuda.recibo', compact('recibo_historial', 'detalle', 'deuda', 'meses'))->setWarnings(false)->setPaper('letter');
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
        $recibo_historial->TasaComision = $deuda->TasaComision;
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


        $pdf = \PDF::loadView('polizas.deuda.recibo', compact('recibo_historial', 'detalle', 'deuda', 'meses', 'exportar'))->setWarnings(false)->setPaper('letter');
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

    public function limpiarNombre($nombre)
    {
        // Eliminar espacios en blanco y números
        $nombreLimpio = preg_replace('/[\s\d]+/', '', $nombre);

        return $nombreLimpio;
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

    public function exportar_historial(Request $request)
    {

        return Excel::download(new HistoricoPagosExport($request), 'historico_pagos.xlsx');


        // return Excel::download(new HistoricoPagosExport($tabla_historico), 'historico_pagos.xlsx');
        /*$tabla_historico = PolizaDeudaCartera::where('Axo', $request->Axo)
        ->where('Mes', $request->Mes)
        ->where('FechaInicio', $request->FechaInicio)
        ->where('FechaFinal', $request->FechaFinal)
        ->get();

        dd($tabla_historico);*/

        /*$tabla_historico = DB::table('poliza_deuda_cartera as pdtc')
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
        //  dd($tabla_historico);*/

        // return view('polizas.deuda.get_historico', compact('tabla_historico'));

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
            ->get();



        if ($tempData->isNotEmpty()) {
            $linea_credito = $tempData->first()->LineaCredito;
            $poliza_deuda = $tempData->first()->PolizaDeuda;
            $mes_int = intval($mes);
            PolizaDeudaCartera::where('PolizaDeuda', $poliza_deuda)->where('LineaCredito', $linea_credito)->where('Axo', $anio)->where('Mes', $mes_int)->delete();
        }


        //


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
            $poliza->saldo_total = $tempRecord->saldo_total;
            $poliza->save();
        }

        // dd()

        $deuda = Deuda::findOrFail($tempRecord->PolizaDeuda);
        $cartera = PolizaDeudaCartera::where('PolizaDeuda', '=', $tempRecord->PolizaDeuda)->get();


        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/deuda/' . $tempRecord->PolizaDeuda . '/edit?tab=2');
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

    public function create_pago(Request $request)
    {


        $credito = $request->get('LineaCredito');

        $deuda_credito = DeudaCredito::findOrFail($request->get('LineaCredito'));

        $nombre_cartera = $deuda_credito->tipoCarteras->Nombre . ' ' . $deuda_credito->saldos->Abreviatura . ' ' . $deuda_credito->saldos->Descripcion;

        $date_submes = Carbon::create($request->Axo, $request->Mes, "01");
        $date = Carbon::create($request->Axo, $request->Mes, "01");
        $date_mes = $date_submes->subMonth();
        $date_anterior = Carbon::create($request->Axo, $request->Mes, "01");
        $date_mes_anterior = $date_anterior->subMonth();

        $deuda = Deuda::findOrFail($request->Id);

        $requisitos = $deuda->requisitos;

        if ($requisitos->count() == 0) {
            alert()->error('No se han definido requisitos minimos de asegurabilidad');
            $deuda->Configuracion = 0;
            $deuda->update();
            session(['tab' => 3]);
            return redirect('polizas/deuda/' . $deuda->Id);
        } else {



            //insertando cartera
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

                PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->delete();
                Excel::import(new PolizaDeudaTempCarteraImport($date->year, $date->month, $deuda->Id, $request->FechaInicio, $request->FechaFinal, $credito), $archivo);
            } catch (Throwable $e) {
                //     print($e);
                //     return false;
            }

            //calculando errores de cartera
            $cartera_temp = PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->get();

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


                // 3 error formato de nit
                // $validador_nit = $this->validarDocumento($obj->Nit, "nit");

                // if ($validador_nit == false) {
                //     $obj->TipoError = 3;
                //     $obj->update();
                // }

                //se limpia el nombre completo de espacios en blanco y numeros
                /* $obj->PrimerApellido = $this->limpiarNombre($obj->PrimerApellido);
                $obj->SegundoApellido = $this->limpiarNombre($obj->SegundoApellido);
                $obj->ApellidoCasada = $this->limpiarNombre($obj->ApellidoCasada);
                $obj->PrimerNombre = $this->limpiarNombre($obj->PrimerNombre);
                $obj->SegundoNombre = $this->limpiarNombre($obj->SegundoNombre);
                $obj->update();*/

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

            //estableciendo fecha de nacimiento date y calculando edad
            PolizaDeudaTempCartera::where('User', auth()->user()->id)
                ->where('PolizaDeuda', $request->Id)
                ->update([
                    'FechaNacimientoDate' => DB::raw("STR_TO_DATE(FechaNacimiento, '%d/%m/%Y')"),
                    'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, CURDATE())"),
                    'FechaOtorgamientoDate' => DB::raw("STR_TO_DATE(FechaOtorgamiento, '%d/%m/%Y')"),
                    'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
                ]);

            //buscando registros nuevos
            $poliza_id = $request->Id;
            $nuevos_registros = DB::table('poliza_deuda_temp_cartera')
                ->where([
                    ['Mes', $date->month],
                    ['Axo', $date->year],
                    ['PolizaDeuda', $request->Id],
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
                    ['PolizaDeuda', $request->Id],
                    ['LineaCredito', $request->LineaCredito],
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

            $maximos_minimos = DeudaRequisitos::where('Deuda', '=', $request->Id)
                ->selectRaw('MIN(MontoInicial) as min_monto_inicial, MAX(MontoFinal) as max_monto_final,MIN(EdadInicial) as min_edad_inicial, MAX(EdadFinal) as max_edad_final ')
                ->first();

            // $maximos_minimos contendrá el resultado de la consulta
            $minMontoInicial = $maximos_minimos->min_monto_inicial;
            $maxMontoFinal = $maximos_minimos->max_monto_final;
            $minEdadInicial = $maximos_minimos->min_edad_inicial;
            $maxEdadFinal = $maximos_minimos->max_edad_final;



            //definiendp el tipo de cartera a evaluar para calculo

            $linea_credito = DeudaCredito::findOrFail($request->get('LineaCredito'));

            $tipo_cartera = $linea_credito->Saldos;

            //dd($tipo_cartera);



            //cumulos por dui
            $poliza_cumulos = PolizaDeudaTempCartera::selectRaw('Id,Dui,Edad,Nit,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,ApellidoCasada,FechaNacimiento,NoValido
        NumeroReferencia,
        SUM(SaldoCapital) as total_saldo,
        SUM(MontoOtorgado) as total_monto_otorgado,
        SUM(Intereses) as total_interes,
        SUM(InteresesCovid) as total_covid,
        SUM(InteresesMoratorios) as total_moratorios,
        SUM(MontoNominal) as total_monto_nominal')->groupBy('Dui')->get();

            //  dd($poliza_cumulos->take(10));

            foreach ($poliza_cumulos as $cumulo) {
                switch ($tipo_cartera) {
                    case '1':
                        # saldo a capital
                        $saldo = $cumulo->total_saldo;
                        break;
                    case '2':
                        # saldo a capital mas intereses
                        $saldo =  $cumulo->total_saldo + $cumulo->total_interes;
                        break;
                    case '3':
                        # saldo a capital mas intereses mas covid
                        $saldo = $cumulo->total_saldo + $cumulo->total_interes +  $cumulo->total_covid;
                        break;
                    case '4':
                        # saldo a capital as intereses mas covid mas moratorios
                        $saldo = $cumulo->total_saldo + $cumulo->total_interes +  $cumulo->total_covid +  $cumulo->total_moratorios;
                        break;
                    case '5':
                        # monto nominal
                        $saldo = $cumulo->total_monto_nominal;
                        break;
                    default:
                        # .monto otorgado
                        $saldo = $cumulo->total_monto_otorgado;
                        break;
                }

                $cumulo->total_saldo = $saldo;
                $cumulo->update();

                //dd($tipo_cartera, $poliza_cumulos->take(10), $saldo );
            }
            //dd($poliza_cumulos->take(10));


            //consultando la tabla requisitos


            //definiendo edad maxima segu requisitos
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
        NumeroReferencia,NoValido,Perfiles,EdadDesembloso,FechaOtorgamiento,NoValido,
         GROUP_CONCAT(DISTINCT NumeroReferencia SEPARATOR ", ") AS ConcatenatedNumeroReferencia,SUM(SaldoCapital) as total_saldo,SUM(Intereses) as total_interes,SUM(InteresesCovid) as total_covid,
         SUM(InteresesMoratorios) as total_moratorios, SUM(MontoNominal) as total_monto_nominal')->groupBy('Dui', 'NoValido')->get();




            return view('polizas.deuda.respuesta_poliza', compact('nuevos_registros', 'registros_eliminados', 'deuda', 'poliza_cumulos', 'date_anterior', 'date', 'tipo_cartera', 'nombre_cartera'));
        }
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

            return response()->json([
                'success' => true,
                'message' => 'Operación realizada con éxito.'
            ]);
        } catch (\Exception $e) {
            // Registrar el error en los logs para su seguimiento
            \Log::error('Error en agregar_validado: ' . $e->getMessage());

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
        $polizas = PolizaDeudaTempCartera::select('Id', 'NumeroReferencia', 'saldo_total')->where('Dui', $poliza->Dui)->where('PolizaDeuda', $poliza->PolizaDeuda)->get();
        // Formatear saldo_total con 2 decimales
        foreach ($polizas as $poliza) {
            $poliza->saldo_total = number_format($poliza->saldo_total, 2, '.', ',');
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
                ->join('poliza_deuda_creditos as pdc', 'pdtc.LineaCredito', '=', 'pdc.Id')
                ->join('saldos_montos as sm', 'pdc.saldos', '=', 'sm.id')
                ->join('tipo_cartera as tc', 'pdc.TipoCartera', '=', 'tc.id') // Unir con la tabla tipo_cartera
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
                    DB::raw("sum(pdtc.saldo_total) as saldo_total"),
                    DB::raw("GROUP_CONCAT(DISTINCT pdtc.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                    DB::raw("GROUP_CONCAT(DISTINCT FORMAT(pdtc.saldo_total, 2) SEPARATOR '- ') AS ConcatenatedMonto"),
                    //'pdc.MontoMaximoIndividual as MontoMaximoIndividual',
                    'sm.Abreviatura as Abreviatura',
                    'tc.nombre AS TipoCarteraNombre' // Agregar el nombre de la TipoCartera
                )
                ->where('pdtc.NoValido', 1)
                ->where('pdtc.EdadDesembloso', '<=', $deuda->EdadMaximaTerminacion)
                ->where('pdtc.saldo_total', '<=', $deuda->ResponsabilidadMaxima)
                ->where('pdtc.PolizaDeuda', $poliza)
                ->groupBy('pdtc.Dui')
                ->get();

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

                $poliza_cumulos = PolizaDeudaTempCartera::join('poliza_deuda_creditos as pdc', 'poliza_deuda_temp_cartera.LineaCredito', '=', 'pdc.Id')
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
                        DB::raw('SUM(poliza_deuda_temp_cartera.saldo_total) as saldo_total'),
                        'pdc.MontoMaximoIndividual as MontoMaximoIndividual'
                    )
                    ->where('poliza_deuda_temp_cartera.EdadDesembloso', '<=', $deuda->EdadMaximaTerminacion)
                    ->where('poliza_deuda_temp_cartera.NoValido', 0)
                    ->where('poliza_deuda_temp_cartera.PolizaDeuda', $poliza)
                    ->where('poliza_deuda_temp_cartera.OmisionPerfil', 0)
                    ->whereNull('pdcart.NumeroReferencia') // Filtra solo los que no tienen coincidencia en poliza_deuda_cartera
                    ->groupBy('poliza_deuda_temp_cartera.Dui')
                    ->get();
            } elseif ($tipo == 2) { // creditos validos
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
                        DB::raw("GROUP_CONCAT(DISTINCT poliza_deuda_temp_cartera.NumeroReferencia SEPARATOR ', ') AS ConcatenatedNumeroReferencia"),
                        DB::raw('MAX(poliza_deuda_temp_cartera.EdadDesembloso) as EdadDesembloso'),
                        DB::raw('MAX(poliza_deuda_temp_cartera.FechaOtorgamientoDate) as FechaOtorgamiento'),
                        'poliza_deuda_temp_cartera.Excluido',
                        'poliza_deuda_temp_cartera.OmisionPerfil',
                        'poliza_deuda_temp_cartera.SaldoCumulo as saldo_total',
                        'pdc.MontoMaximoIndividual as MontoMaximoIndividual'
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
                        'poliza_deuda_temp_cartera.saldo_total'
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
                            'poliza_deuda_temp_cartera.NumeroReferencia  AS ConcatenatedNumeroReferencia',
                            'poliza_deuda_temp_cartera.EdadDesembloso as EdadDesembloso',
                            'poliza_deuda_temp_cartera.FechaOtorgamientoDate as FechaOtorgamiento',
                            'poliza_deuda_temp_cartera.Excluido',
                            'poliza_deuda_temp_cartera.OmisionPerfil',
                            'poliza_deuda_temp_cartera.saldo_total',
                            'pdc.MontoMaximoIndividual as MontoMaximoIndividual'
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
        $data = PolizaDeudaTempCartera::with('linea_credito.tipoCarteras')
            ->where('NoValido', 0)
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


        //dd($data);
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
