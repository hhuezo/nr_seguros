<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
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

class DepositoPlazoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depositoPlazo = DepositoPlazo::all();
        return view('polizas.deposito_plazo.index', compact('depositoPlazo'));
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

        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->get();
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.deposito_plazo.create', compact(
            'aseguradora',
            'cliente',
            'tipoCartera',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro'
        ));
    }

    public function get_cliente(Request $request)
    {
        $cliente = Cliente::findOrFail($request->Cliente);
        return $cliente;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            $detalle = new DetalleDepositoPlazo();
            $detalle->DepositoPlazo = $depositoPlazo->Id;
            $detalle->Comentario = $request->Comentario;
            $detalle->Tasa = $request->Tasa;
            $detalle->PrimaTotal = $request->PrimaTotal;
            $detalle->Descuento = $request->Descuento;
            $detalle->ExtraPrima = $request->ExtraPrima;
            $detalle->ValorCCF = $request->ValorCCF;
            $detalle->APagar = $request->APagar;
            $detalle->ValorDescuento = $request->ValorDescuento;
            $detalle->Retencion = $request->Retencion;
            $detalle->IvaSobreComision = $request->IvaSobreComision;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->EnvioCartera = $request->EnvioCartera;
            $detalle->EnvioPago = $request->EnvioPago;
            $detalle->PagoAplicado = $request->PagoAplicado;
            $detalle->save();
            alert()->success('El registro ha sido creado correctamente');
            return Redirect::to('poliza/deposito_plazo/create');
        }
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
        $depositoPlazo = DepositoPlazo::findOrFail($id);
        $detalle = DetalleDepositoPlazo::where('DepositoPlazo', $depositoPlazo->Id)->get();
        $detalle_last = DetalleDepositoPlazo::where('DepositoPlazo', $depositoPlazo->Id)->orderByDesc('PagoAplicado')->first();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->get();
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.deposito_plazo.edit', compact('depositoPlazo', 'detalle','detalle_last' ,'aseguradora', 'cliente', 'tipoCartera', 'estadoPoliza', 'tipoCobro', 'ejecutivo'));
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
      // dd();
        $depositoPlazo = DepositoPlazo::findOrFail($id);
        $depositoPlazo->Tasa = $request->Tasa;
        $depositoPlazo->PrimaTotal = $request->PrimaTotal;
        $depositoPlazo->Descuento = $request->Descuento;
        $depositoPlazo->ExtraPrima = $request->ExtraPrima;
        $depositoPlazo->ValorCCF = $request->ValorCCF;
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
        $depositoPlazo->update();

        $detalle = new DetalleDepositoPlazo();
        $detalle->DepositoPlazo = $depositoPlazo->Id;
        $detalle->Comentario = $request->Comentario;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->Descuento = $request->Descuento;
        $detalle->ExtraPrima = $request->ExtraPrima;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->APagar = $request->APagar;
        $detalle->ValorDescuento = $request->ValorDescuento;
        $detalle->Retencion = $request->Retencion;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->EnvioCartera = $request->EnvioCartera;
        $detalle->EnvioPago = $request->EnvioPago;
        $detalle->PagoAplicado = $request->PagoAplicado;
        $detalle->save();

        alert()->success('El registro ha sido modificado correctamente');
        return back();
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
}
