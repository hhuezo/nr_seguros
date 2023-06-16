<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\DetalleResidencia;
use App\Models\polizas\Residencia;
use Carbon\Carbon;
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
        $residencias = Residencia::where('Activo', 1)->get();
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
        $bombero = Bombero::where('Activo',1)->first();
        if($bombero){
           $bomberos = $bombero->Valor; 
        }
        else{
            $bomberos = $bombero->Valor;
        }
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
            'ubicaciones_cobro',
            'bomberos'
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
       // dd($request->MontoCartera);
        $residencia = new Residencia();
        $residencia->Numero = 1;
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
        $residencia->Ejecutivo = $request->Ejecutivo;
        $residencia->TasaDescuento = $request->TasaDescuento;
        $residencia->Nit = $request->Nit;
        $residencia->Activo = 1;
        $residencia->Mensual = $request->Mensual; //valor de radio button
        $residencia->TasaComison = $request->TasaComision;
        $residencia->save();


/*         $detalles = new DetalleResidencia();
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
        $detalles->save(); */

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('polizas/residencia/create');
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
        $bombero = Bombero::where('Activo',1)->first();
        if($bombero){
            $bomberos = $bombero->Valor; 
         }
         else{
             $bomberos = $bombero->Valor;
         }

         $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return view('polizas.residencia.edit',compact('residencia','ejecutivo','detalle',
        'cliente',
        'aseguradoras',
        'estados_poliza',
        'tipos_contribuyente',
        'rutas',
        'ubicaciones_cobro' , 'bomberos', 'meses'));
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
        
        $residencia = Residencia::findOrFail($id);
        $residencia->Activo = 0;
        $residencia->update();
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('polizas/residencia');
    }

    public function create_pago(Request $request)
    {
        $time = Carbon::now('America/El_Salvador');

        $detalle = new DetalleResidencia();
        $detalle->FechaInicio = $request->FechaInicio;
        $detalle->FechaFinal = $request->FechaFinal;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->Residencia = $request->Residencia;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->Descuento = $request->Descuento;
        $detalle->Iva = $request->Iva;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->APagar = $request->APagar;
        $detalle->Comentario = $request->Comentario;
        $detalle->DescuentoIva = $request->DescuentoIva;  //checked
        $detalle->Comision = $request->Comision;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->Retencion = $request->Retencion;
        $detalle->Residencia = $request->Residencia;
        $detalle->ExtraPrima = $request->ExtraPrima;
        $detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
        $detalle->ValorDescuento = $request->ValorDescuento;
        $detalle->TasaComision = $request->TasaComision;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        $detalle->save();

        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function edit_pago(Request $request)
    {
        $detalle = DetalleResidencia::findOrFail($request->Id);
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
        return DetalleResidencia::findOrFail($id);

    }

    public function renovar($id){
        $residencia = Residencia::findOrFail($id);
        $estados_poliza = EstadoPoliza::where('Activo', 1)->get();
        return view('polizas.residencia.renovar', compact('residencia','estados_poliza'));
    }

    public function renovarPoliza(Request $request, $id){
        $residencia = Residencia::findOrFail($id);
        $residencia->Mensual = $request->Mensual; //valor de radio button
        $residencia->EstadoPoliza = $request->EstadoPoliza;
        $residencia->VigenciaDesde = $request->VigenciaDesde;
        $residencia->VigenciaHasta = $request->VigenciaHasta;
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        $residencia->MontoCartera = $request->MontoCartera;
        $residencia->Tasa = $request->Tasa;
        $residencia->update();

        alert()->success('La poliza fue renovada correctamente');
        return back();

    }
}
