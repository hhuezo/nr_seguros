<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\DetalleResidencia;
use App\Models\polizas\Residencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ResidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $residencias = Residencia::all();
        return view('polizas.residencia.index', compact('residencias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipos_contribuyente =  TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro =  UbicacionCobro::where('Activo', '=', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.residencia.create', compact(
            'ejecutivo',
            'cliente',
            'aseguradoras',
            'estados_poliza',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $residencia = new Residencia();
        $residencia->NumeroPoliza = $request->NumeroPoliza;
        $residencia->Codigo = $request->Codigo;
        $residencia->Aseguradora = $request->Aseguradora;
        $residencia->Asegurado = $request->Asegurado;
        $residencia->EstadoPoliza = $request->EstadoPoliza;
        $residencia->VigenciaDesde = $request->VigenciaDesde;
        $residencia->VigenciaHasta = $request->VigenciaHasta;
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        $residencia->MontoCartera = $request->MontoCartera;
        $residencia->Tasa = $request->Tasa;
        $residencia->Prima = $request->ValorPrima;
        $residencia->Vendedor = $request->Ejecutivo;
        $residencia->Descuento = $request->Descuento;
        $residencia->GastosEmision = $request->GastosEmision;
        $residencia->ImpuestoBomberos = $request->ImpuertoBomberos;
        $residencia->Iva = $request->Iva;
        $residencia->ValorCCF = $request->ValorCCF;
        $residencia->APagar = $request->APagar;
        $residencia->ComentariosDeCobro = $request->Comentario;
        $residencia->DescuentoIva = $request->DescuentoIva;
        $residencia->Nit = $request->Nit;
        $residencia->Comision = $request->Comision;
        $residencia->IvaSobreComision = $request->IvaSobreComision;
        $residencia->Retencion = $request->Retencion;
        $residencia->Activo = $request->Activo;
        $residencia->ImpresionRecibo = $request->ImpresionRecibo;
        $residencia->EnvioCartera = $request->EnvioCartera;
        $residencia->PagoAplicado = $request->PagoAplicado;
        $residencia->SaldoA = $request->SaldoA;
        $residencia->EnvioPago = $request->EnvioPago;
        $residencia->save();


        $detalles = new DetalleResidencia();
        $detalles->MontoCartera = $request->MontoCartera;
        $detalles->Tasa = $request->Tasa;
        $detalles->Prima = $request->ValorPrima;
        $detalles->Descuento = $request->Descuento;
        $detalles->Iva = $request->Iva;
        $detalles->ValorCCF = $request->ValorCCF;
        $detalles->APagar = $request->APagar;
        $detalles->ComentariosDeCobro = $request->ComentariosdeCobro;
        $detalles->DescuentoIva = $request->DescuentoIva;
        $detalles->Comision = $request->Comision;
        $detalles->IvaSobreComision = $request->IvaSobreComision;
        $detalles->Retencion = $request->Retension;
        $detalles->ImpresionRecibo = $request->ImpresionRecibo;
        $detalles->EnvioCartera = $request->EnvioCartera;
        $detalles->PagoAplicado = $request->PagoAplicado;
        $detalles->SaldoA = $request->SaldoA;
        $detalles->EnvioPago = $request->EnvioPago;
        $detalles->Residencia = $residencia->Id;
        $detalles->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('poliza/residencia/create');
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
        $residencia = Residencia::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipos_contribuyente =  TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro =  UbicacionCobro::where('Activo', '=', 1)->get();
        $detalle = DetalleResidencia::where('Residencia',$residencia->Id)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();

        return view('polizas.residencia.edit',compact('residencia','ejecutivo','detalle',
        'cliente',
        'aseguradoras',
        'estados_poliza',
        'tipos_contribuyente',
        'rutas',
        'ubicaciones_cobro'));
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
        $residencia = Residencia::findOrFail($id);
        $detalles = new DetalleResidencia();
        $detalles->MontoCartera = $request->MontoCartera;
        $detalles->Tasa = $request->Tasa;
        $detalles->Prima = $request->ValorPrima;
        $detalles->Descuento = $request->Descuento;
        $detalles->Iva = $request->Iva;
        $detalles->ValorCCF = $request->ValorCCF;
        $detalles->APagar = $request->APagar;
        $detalles->ComentariosDeCobro = $request->ComentariosdeCobro;
        $detalles->DescuentoIva = $request->DescuentoIva;
        $detalles->Comision = $request->Comision;
        $detalles->IvaSobreComision = $request->IvaSobreComision;
        $detalles->Retencion = $request->Retension;
        $detalles->ImpresionRecibo = $request->ImpresionRecibo;
        $detalles->EnvioCartera = $request->EnvioCartera;
        $detalles->PagoAplicado = $request->PagoAplicado;
        $detalles->SaldoA = $request->SaldoA;
        $detalles->EnvioPago = $request->EnvioPago;
        $detalles->Residencia = $residencia->Id;
        $detalles->save();

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
