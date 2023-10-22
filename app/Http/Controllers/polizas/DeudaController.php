<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\PolizaDeudaTempCarteraImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoCobro;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\DeudaDetalle;
use App\Models\polizas\DeudaRequisitos;
use App\Models\polizas\DeudaVida;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\temp\PolizaDeudaTempCartera;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DeudaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::now()->toDateString();

        session(['MontoCarteraDeuda' => 0]);
        session(['FechaInicioDeuda' => $today]);
        session(['FechaFinalDeuda' => $today]);
        session(['ExcelURLDeuda' => '']);
        $deuda = Deuda::get();
        return view('polizas.deuda.index', compact('deuda'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos_contribuyente =  TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro =  UbicacionCobro::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get();  //deuda
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.deuda.create', compact(
            'aseguradora',
            'cliente',
            'tipoCartera',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'bomberos'
        ));
    }

    public function agregar_pago(Request $request)
    {

        $deuda = Deuda::findOrFail($request->Deuda);

        $detalle = new DeudaDetalle();
        $detalle->FechaInicio = $request->FechaInicio;
        $detalle->FechaFinal = $request->FechaFinal;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->Deuda = $request->Deuda;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->Descuento = $request->Descuento;
        $detalle->Iva = $request->Iva;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->APagar = $request->APagar;
        $detalle->Comentario = $request->Comentario;
       // $detalle->DescuentoIva = $request->DescuentoIva; //checked
        $detalle->Comision = $request->Comision;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->Retencion = $request->Retencion;
        $detalle->ExtraPrima = $request->ExtraPrima;
        $detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
        $detalle->ValorDescuento = $request->ValorDescuento;
        $detalle->TasaComision = $request->TasaComision;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        $detalle->ExcelURL = $request->ExcelURL;
        $detalle->save();
        alert()->success('El registro de pago ha sido ingresado correctamente');
        return back();
    }

    public function store(Request $request)
    {
        //dd($request->Vida);
        $deuda = new Deuda();
        $deuda->NumeroPoliza = $request->NumeroPoliza;
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
        $deuda->Descuento = $request->TasaDescuento;
        $deuda->Comision = $request->TasaComision;
        $deuda->FechaIngreso = $request->FechaIngreso;
        $deuda->Activo = $request->Activo;
        $deuda->Bomberos = $request->Bomberos;
        if ($request->Vida == 'on') {
            $deuda->Videuda = 1;
        } else {
            $deuda->Videuda = 0;
        }
        $deuda->LimiteMaximo = $request->LimiteMaximo;
        $deuda->FechaIngreso = Carbon::now('America/El_Salvador');
        $deuda->Activo = 1;
        $deuda->save();

        $creditos = new DeudaCredito();
        $creditos->Deuda = $deuda->Id;
        $creditos->TipoCartera = $request->TipoCartera1;
        $creditos->Tasa = $request->TasaCartera1;
        $creditos->save();

        $creditos = new DeudaCredito();
        $creditos->Deuda = $deuda->Id;
        $creditos->TipoCartera = $request->TipoCartera2;
        $creditos->Tasa = $request->TasaCartera2;
        $creditos->save();

        $videuda = new DeudaVida();
        $videuda->NumeroPoliza = $request->NumeroPolizaVida;
        $videuda->Deuda = $deuda->Id;
        $videuda->SumaUniforme = $request->SumaUniforme;
        $videuda->Tasa = $request->TasaVida;
        $videuda->Activo = 1;
        $videuda->save();

        DeudaRequisitos::whereIn('Id', [$request->Requisitos])->update(['Poliza' => $deuda->Id]);
        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return back();
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

        if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
            $detalle->SaldoA = $request->SaldoA;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->Comentario = $request->Comentario;
            $detalle->update();
            $pdf = \PDF::loadView('polizas.deuda.recibo', compact('detalle','deuda'))->setWarnings(false)->setPaper('letter');
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
            alert()->success('El registro ha sido ingresado correctamente');
        }



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
        $sql =  "select * from poliza_deuda_requisitos where id in ($request->Requisitos)";
        $requisitos =  DB::select($sql);

        return view('polizas.deuda.requisitos', compact('requisitos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deuda = Deuda::findOrFail($id);
        $creditos = DeudaCredito::where('Deuda', $deuda->Id)->get();
        $videuda = DeudaVida::where('Deuda',$deuda->Id)->first();
        $requisitos = DeudaRequisitos::where('Poliza', $deuda->Id)->get();
        $tipos_contribuyente =  TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro =  UbicacionCobro::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();

        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get();  //deuda
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $detalle = DeudaDetalle::where('Deuda', $deuda->Id)->where('Activo',1)->orderBy('Id','desc')->get();
        $ultimo_pago = DeudaDetalle::where('Deuda',$deuda->Id)->where('Activo',1)->orderBy('Id','desc')->first();
        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return view('polizas.deuda.edit', compact(
            'meses',
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
            'ultimo_pago'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function create_pago(Request $request)
    {
        /*session(['MontoCarteraDeuda' => 0]);
        session(['FechaInicioDeuda' => $today]);
        session(['FechaFinalDeuda' => $today]);
        session(['ExcelURLDeuda' => '']);*/
        $fecha = Carbon::create(null, $request->Mes, 1);
        $nombreMes = $fecha->locale('es')->monthName;

        $time = Carbon::now('America/El_Salvador');

        $deuda= Deuda::findOrFail($request->Id);

        if ($request->Mes == 1) {
            $mes_evaluar = 12;
            $axo_evaluar = $request->Axo - 1;
        } else {
            $mes_evaluar = $request->Mes - 1;
            $axo_evaluar = $request->Axo;
        }

        try {
            $archivo = $request->Archivo;
            PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->delete();

            //dd(Excel::toArray(new PolizaDeudaTempCarteraImport($request->Axo, $request->Mes, $deuda->Id, $request->FechaInicio, $request->FechaFinal), $archivo));
            Excel::import(new PolizaDeudaTempCarteraImport($request->Axo, $request->Mes, $deuda->Id, $request->FechaInicio, $request->FechaFinal), $archivo);

            $monto_cartera_total = PolizaDeudaTempCartera::where('User', '=', auth()->user()->id)->sum('SumaAsegurada');

            if ($monto_cartera_total > $deuda->LimiteMaximo) {
                alert()->error('Error, el saldo supera el Limite Maximo');
                return back();
            }

            if ($request->Validar == "on") {

                $eliminados = DB::select('CALL lista_deuda_eliminados(?, ?, ?, ?, ?, ?)', [ $axo_evaluar, $mes_evaluar, $deuda->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                $nuevos = DB::select('CALL lista_deuda_nuevos(?, ?, ?, ?, ?, ?)', [ $axo_evaluar, $mes_evaluar, $deuda->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                return view('polizas.validacion_cartera.resultado', compact('nuevos', 'eliminados'));
            }

            DB::statement("CALL insertar_temp_cartera_deuda(?, ?, ?, ?)", [auth()->user()->id, $request->Axo, $request->Mes, $deuda->Id]);

            $monto_cartera_total = PolizaDeudaCartera::where('Axo', $request->Axo)
                ->where('Mes', $request->Mes)
                ->where('PolizaDeuda', $deuda->Id)->sum('SumaAsegurada');

                session(['MontoCarteraDeuda' => $monto_cartera_total]);
                session(['FechaInicioDeuda' => $request->FechaInicio]);
                session(['FechaFinalDeuda' => $request->FechaFinal]);

                $filePath = 'documentos/polizas/' . $deuda->NumeroPoliza . '-' . $nombreMes . '-' . $request->Axo . '-Deuda.xlsx';
                Storage::disk('public')->put($filePath, file_get_contents($archivo));

                session(['ExcelURLDeuda' => $filePath]);

                alert()->success('El registro ha sido ingresado correctamente');
                return back();
        } catch (Throwable $e) {
            print($e);
            return false;
        }
    }
    public function delete_pago($id)
    {
        $detalle = DeudaDetalle::findOrFail($id);
        $detalle->Activo = 0;
        $detalle->update();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }
}
