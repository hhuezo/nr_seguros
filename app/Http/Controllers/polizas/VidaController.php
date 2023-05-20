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
use App\Models\polizas\Vida;
use App\Models\polizas\VidaDetalle;
use App\Models\polizas\VidaUsuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VidaController extends Controller
{
    public function index()
    {
        $vida = Vida::all();
        return view('polizas.vida.index', compact('vida'));
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
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza',1)->get();  //vida
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
        $vida_codigo = Vida::where('Codigo', $request->Codigo)->first();
        if ($vida_codigo) {
            alert()->error('El Codigo ya fue utilizado');
            return back();
        } else {

            $vida = new Vida();
            $vida->NumeroPoliza = $request->NumeroPoliza;
            $vida->Nit = $request->Nit;
            $vida->Codigo = $request->Codigo;
            $vida->Aseguradora = $request->Aseguradora;
            $vida->Asegurado = $request->Asegurado;
            $vida->GrupoAsegurado = $request->GrupoAsegurado;
            $vida->VigenciaDesde = $request->VigenciaDesde;
            $vida->VigenciaHasta = $request->VigenciaHasta;
            $vida->BeneficiosAdicionales = $request->BeneficiosAdicionales;
            $vida->ClausulasEspeciales = $request->ClausulasEspeciales;
            $vida->Concepto = $request->Concepto;
            $vida->Comentario = $request->Comentario;
            $vida->Ejecutivo = $request->Ejecutivo;
            $vida->TipoCartera = $request->TipoCartera;
            $vida->EstadoPoliza = $request->EstadoPoliza;
            $vida->TipoCobro = $request->TipoCobro;
            $vida->Tasa = $request->Tasa;
            $vida->MontoCartera = $request->MontoCartera;
            // guardar el valor del radio button
            /*if($request->tipoTasa == 1){
                $vida->Mensual = 1;  //tasa mensual
            }elseif($request->tipoTasa == 1){
                $vida->Mensual = 0;  //tasa anual
            }*/
            $vida->Mensual = $request->tipoTasa;
            $vida->PrimaDescontada = $request->PrimaDescontada;
            $vida->TasaComision = $request->TasaComision;
            $vida->PrimaTotal = $request->PrimaTotal;
            $vida->Descuento = $request->Descuento;
            $vida->ExtraPrima = $request->ExtraPrima;
            $vida->ValorCCF = $request->ValorCCF;
            $vida->ValorDescuento = $request->ValorDescuento;
            $vida->Retencion = $request->Retencion;
            $vida->IvaSobreComision = $request->IvaSobreComision;
            $vida->APagar = $request->APagar;
            $vida->Activo = 1;
            $vida->save();

            alert()->success('El registro ha sido creado correctamente');
            return Redirect::to('poliza/vida/create');
        }
    }

    public function show($id)
    {
        //
    }

    public function agregarUsuario(Request $request){
        //agregar form de agregar usuario
        $usuario_vida = new VidaUsuario();
        $usuario_vida->Poliza = $request->Poliza;
        $usuario_vida->NumeroUsuario = $request->NumeroUsuario;
        $usuario_vida->SumaAsegurada = $request->SumaAsegurada;
        $usuario_vida->Tasa = $request->Tasa;
        $usuario_vida->TotalAsegurado = $request->TotalAseggurado;
        $usuario_vida->save();

        $usuario_vidas = VidaUsuario::where('Poliza',$request->Poliza)->get();
        return $usuario_vidas;

        
    }

    public function edit($id)
    {
        $vida = Vida::findOrFail($id);
        $detalle = VidaDetalle::where('Vida', $vida->Id)->get();
        $detalle_last = VidaDetalle::where('Vida', $vida->Id)->orderByDesc('PagoAplicado')->first();
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
        return view('polizas.vida.edit', compact('bomberos','vida', 'detalle','detalle_last' ,'aseguradora', 'cliente', 'tipoCartera', 'estadoPoliza', 'tipoCobro', 'ejecutivo'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->tipoTasa);
        $vida = Vida::findOrFail($id);
        $vida->NumeroUsuario1 = $request->NumeroUsuario1;
        $vida->SumaAseguradora1 = $request->SumaAseguradora1;
        $vida->Prima1 = $request->Prima1;
        $vida->NumeroUsuario2 = $request->NumeroUsuario2;
        $vida->SumaAseguradora2 = $request->SumaAseguradora2;
        $vida->Prima2 = $request->Prima2;
        $vida->NumeroUsuario3 = $request->NumeroUsuario3;
        $vida->SumaAseguradora3 = $request->SumaAseguradora3;
        $vida->Prima3 = $request->Prima3;
        $vida->NumeroUsuario4 = $request->NumeroUsuario4;
        $vida->SumaAseguradora4 = $request->SumaAseguradora4;
        $vida->Prima4 = $request->Prima4;
        $vida->NumeroUsuario5 = $request->NumeroUsuario5;
        $vida->SumaAseguradora5 = $request->SumaAseguradora5;
        $vida->Prima5 = $request->Prima5;
        $vida->NumeroUsuario6 = $request->NumeroUsuario6;
        $vida->SumaAseguradora6 = $request->SumaAseguradora6;
        $vida->Prima6 = $request->Prima6;
        $vida->Mensual = $request->tipoTasa;
        $vida->update();


       /* $detalle = new vidaDetalle();
        $detalle->vida = $vida->Id;
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

        $detalle = new VidaDetalle();
        $detalle->SaldoA = $request->SaldoA;
        $detalle->Vida = $request->Id;
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
        $detalle = VidaDetalle::findOrFail($request->Id);
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
        return VidaDetalle::findOrFail($id);

    }

    public function renovar($id){
        $vida = Vida::findOrFail($id);
        $estados_poliza = EstadoPoliza::where('Activo',1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        return view('polizas.vida.renovar', compact('vida','tipoCobro','estados_poliza'));
    }
    public function renovarPoliza(Request $request, $id){
        $vida = Vida::findOrFail($id);
        $vida->Mensual = $request->Mensual; //valor de radio button
        $vida->EstadoPoliza = $request->EstadoPoliza;
        $vida->VigenciaDesde = $request->VigenciaDesde;
        $vida->VigenciaHasta = $request->VigenciaHasta;
        $vida->MontoCartera = $request->MontoCartera;
        $vida->Tasa = $request->Tasa;
        $vida->update();

        alert()->success('La poliza fue renovada correctamente');
        return back();

    }
}
