<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
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
use App\Models\polizas\DepositoPlazo;
use App\Models\polizas\DetalleDepositoPlazo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class DepositoPlazoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $depositoPlazo = DepositoPlazo::all();
        return view('polizas.vida.index', compact('depositoPlazo'));
    }


    public function create()
    {
        $tipos_contribuyente =  TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro =  UbicacionCobro::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo',1)->first();
        if($bombero){
           $bomberos = $bombero->Valor;
        }
        else{
            $bomberos = $bombero->Valor;
        }
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->get();
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.vida.create', compact(
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

    public function get_cliente(Request $request)
    {
        $cliente = Cliente::findOrFail($request->Cliente);
        return $cliente;
    }

    public function store(Request $request)
    {
        $depositoPlazo_codigo = DepositoPlazo::where('Codigo', $request->Codigo)->first();
        if ($depositoPlazo_codigo) {
            alert()->error('El Codigo ya fue utilizado');
            return back();
        } else {

            $depositoPlazo = new DepositoPlazo();
            $depositoPlazo->NumeroPoliza = $request->NumeroPoliza;
            $depositoPlazo->Nit = $request->Nit;
            $depositoPlazo->Codigo = $request->Codigo;
            $depositoPlazo->Aseguradora = $request->Aseguradora;
            $depositoPlazo->Asegurado = $request->Asegurado;
            $depositoPlazo->GrupoAsegurado = $request->GrupoAsegurado;
            $depositoPlazo->VigenciaDesde = $request->VigenciaDesde;
            $depositoPlazo->VigenciaHasta = $request->VigenciaHasta;
            $depositoPlazo->BeneficiosAdicionales = $request->BeneficiosAdicionales;
            $depositoPlazo->ClausulasEspeciales = $request->ClausulasEspeciales;
            $depositoPlazo->Concepto = $request->Concepto;
            $depositoPlazo->Comentario = $request->Comentario;
            $depositoPlazo->Ejecutivo = $request->Ejecutivo;
            $depositoPlazo->TipoCartera = $request->TipoCartera;
            $depositoPlazo->EstadoPoliza = $request->EstadoPoliza;
            $depositoPlazo->TipoCobro = $request->TipoCobro;
            $depositoPlazo->Tasa = $request->Tasa;
            $depositoPlazo->MontoCartera = $request->MontoCartera;
            // guardar el valor del radio button
            /*if($request->tipoTasa == 1){
                $depositoPlazo->Mensual = 1;  //tasa mensual
            }elseif($request->tipoTasa == 1){
                $depositoPlazo->Mensual = 0;  //tasa anual
            }*/
            $depositoPlazo->Mensual = $request->tipoTasa;
            $depositoPlazo->PrimaDescontada = $request->PrimaDescontada;
            $depositoPlazo->TasaComision = $request->TasaComision;
            $depositoPlazo->PrimaTotal = $request->PrimaTotal;
            $depositoPlazo->Descuento = $request->Descuento;
            $depositoPlazo->ExtraPrima = $request->ExtraPrima;
            $depositoPlazo->ValorCCF = $request->ValorCCF;
            $depositoPlazo->ValorDescuento = $request->ValorDescuento;
            $depositoPlazo->Retencion = $request->Retencion;
            $depositoPlazo->IvaSobreComision = $request->IvaSobreComision;
            $depositoPlazo->APagar = $request->APagar;
            $depositoPlazo->NumeroUsuario1 = $request->NumeroUsuario1;
            $depositoPlazo->SumaAseguradora1 = $request->SumaAseguradora1;
            $depositoPlazo->Prima1 = $request->Prima1;
            $depositoPlazo->NumeroUsuario2 = $request->NumeroUsuario2;
            $depositoPlazo->SumaAseguradora2 = $request->SumaAseguradora2;
            $depositoPlazo->Prima2 = $request->Prima2;
            $depositoPlazo->NumeroUsuario3 = $request->NumeroUsuario3;
            $depositoPlazo->SumaAseguradora3 = $request->SumaAseguradora3;
            $depositoPlazo->Prima3 = $request->Prima3;
            $depositoPlazo->NumeroUsuario4 = $request->NumeroUsuario4;
            $depositoPlazo->SumaAseguradora4 = $request->SumaAseguradora4;
            $depositoPlazo->Prima4 = $request->Prima4;
            $depositoPlazo->NumeroUsuario5 = $request->NumeroUsuario5;
            $depositoPlazo->SumaAseguradora5 = $request->SumaAseguradora5;
            $depositoPlazo->Prima5 = $request->Prima5;
            $depositoPlazo->NumeroUsuario6 = $request->NumeroUsuario6;
            $depositoPlazo->SumaAseguradora6 = $request->SumaAseguradora6;
            $depositoPlazo->Prima6 = $request->Prima6;
            $depositoPlazo->Activo = 1;
            $depositoPlazo->save();

            alert()->success('El registro ha sido creado correctamente');
            return Redirect::to('poliza/vida/create');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $depositoPlazo = DepositoPlazo::findOrFail($id);
        $detalle = DetalleDepositoPlazo::where('DepositoPlazo', $depositoPlazo->Id)->get();
        $detalle_last = DetalleDepositoPlazo::where('DepositoPlazo', $depositoPlazo->Id)->orderByDesc('PagoAplicado')->first();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->get();
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $bombero = Bombero::where('Activo',1)->first();
        if($bombero){
           $bomberos = $bombero->Valor;
        }
        else{
            $bomberos = $bombero->Valor;
        }
        return view('polizas.vida.edit', compact('bomberos','depositoPlazo', 'detalle','detalle_last' ,'aseguradora', 'cliente', 'tipoCartera', 'estadoPoliza', 'tipoCobro', 'ejecutivo'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->tipoTasa);
        $depositoPlazo = DepositoPlazo::findOrFail($id);
        $depositoPlazo->NumeroUsuario1 = $request->NumeroUsuario1;
        $depositoPlazo->SumaAseguradora1 = $request->SumaAseguradora1;
        $depositoPlazo->Prima1 = $request->Prima1;
        $depositoPlazo->NumeroUsuario2 = $request->NumeroUsuario2;
        $depositoPlazo->SumaAseguradora2 = $request->SumaAseguradora2;
        $depositoPlazo->Prima2 = $request->Prima2;
        $depositoPlazo->NumeroUsuario3 = $request->NumeroUsuario3;
        $depositoPlazo->SumaAseguradora3 = $request->SumaAseguradora3;
        $depositoPlazo->Prima3 = $request->Prima3;
        $depositoPlazo->NumeroUsuario4 = $request->NumeroUsuario4;
        $depositoPlazo->SumaAseguradora4 = $request->SumaAseguradora4;
        $depositoPlazo->Prima4 = $request->Prima4;
        $depositoPlazo->NumeroUsuario5 = $request->NumeroUsuario5;
        $depositoPlazo->SumaAseguradora5 = $request->SumaAseguradora5;
        $depositoPlazo->Prima5 = $request->Prima5;
        $depositoPlazo->NumeroUsuario6 = $request->NumeroUsuario6;
        $depositoPlazo->SumaAseguradora6 = $request->SumaAseguradora6;
        $depositoPlazo->Prima6 = $request->Prima6;
        $depositoPlazo->Mensual = $request->tipoTasa;
        $depositoPlazo->update();


       /* $detalle = new DetalleDepositoPlazo();
        $detalle->DepositoPlazo = $depositoPlazo->Id;
        $detalle->Comentario = $request->Comentario;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->Descuento = $request->Descuento;
        $detalle->ExtraPrima = $request->ExtraPrima;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->APagar = $request->APagar;
        $detalle->TasaComision = $request->TasaComision;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        $detalle->ValorDescuento = $request->ValorDescuento;
        $detalle->Retencion = $request->Retencion;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->EnvioCartera = $request->EnvioCartera;
        $detalle->EnvioPago = $request->EnvioPago;
        $detalle->PagoAplicado = $request->PagoAplicado;
        $detalle->save();*/

        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }

    public function destroy($id)
    {
        //
    }

    public function create_pago(Request $request)
    {
        $time = Carbon::now('America/El_Salvador');

        $detalle = new DetalleDepositoPlazo();
        $detalle->SaldoA = $request->SaldoA;
        $detalle->DepositoPlazo = $request->Id;
        $detalle->Comentario = $request->Comentario;
        $detalle->Tasa = $request->Tasa;
        $detalle->Comision = $request->Comision;
        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->Descuento = $request->Descuento;
        $detalle->ExtraPrima = $request->ExtraPrima;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->APagar = $request->APagar;
        $detalle->TasaComision = $request->TasaComision;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        $detalle->ValorDescuento = $request->ValorDescuento;
        $detalle->Retencion = $request->Retencion;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->ImpresionRecibo = $time->toDateTimeString();
        /*$detalle->EnvioCartera = $request->EnvioCartera;
        $detalle->EnvioPago = $request->EnvioPago;
        $detalle->PagoAplicado = $request->PagoAplicado;*/
        $detalle->save();

        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function edit_pago(Request $request)
    {
        $detalle = DetalleDepositoPlazo::findOrFail($request->Id);
        //dd($request->EnvioCartera .' 00:00:00');
        if($request->EnvioCartera)
        {
            $detalle->EnvioCartera = $request->EnvioCartera;
        }
        if($request->EnvioPago)
        {
            $detalle->EnvioPago = $request->EnvioPago;
        }
        if($request->PagoAplicado)
        {
            $detalle->PagoAplicado = $request->PagoAplicado;
        }
        $detalle->Comentario = $request->Comentario;

        /*$detalle->EnvioPago = $request->EnvioPago;
        $detalle->PagoAplicado = $request->PagoAplicado;*/
        $detalle->update();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function get_pago($id)
    {
        return DetalleDepositoPlazo::findOrFail($id);

    }


}
