<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoVenta;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\TipoNegocio;
use App\Models\catalogo\TipoPoliza;
use Carbon\Carbon;

class NegocioController extends Controller
{

    public function index()
    {
        $negocios = Negocio::get();
        return view('catalogo.negocio.index', compact('negocios')  );
    }

    public function create()
    {
        $aseguradoras = Aseguradora::where('Activo','=',1)->get();
        $tipos_poliza = TipoPoliza::where('Activo','=',1)->get();
        $tipos_negocio = TipoNegocio::where('Activo','=',1)->get();
        $estados_venta = EstadoVenta::where('Activo','=',1)->get();
        $ejecutivos = Ejecutivo::where('Activo','=',1)->get();
        return view('catalogo.negocio.create', compact('aseguradoras','tipos_poliza','tipos_negocio','estados_venta','ejecutivos'));
        
    }

    public function store(Request $request)
    {
        $time = Carbon::now();

        $negocio = new Negocio();
        $negocio->Asegurado = $request->Asegurado;
        $negocio->Aseguradora = $request->Aseguradora;
        $negocio->FechaVenta = $request->FechaVenta;
        $negocio->TipoPoliza = $request->TipoPoliza;
        $negocio->InicioVigencia = $request->InicioVigencia;
        $negocio->SumaAsegurada = $request->SumaAsegurada;
        $negocio->Prima = $request->Prima;
        $negocio->Observacion = $request->Observacion;
        $negocio->TipoNegocio = $request->TipoNegocio;
        $negocio->EstadoVenta = $request->EstadoVenta;
        $negocio->Ejecutivo = $request->Ejecutivo;
        $negocio->FechaIngreso = $time->toDateTimeString(); 
        $negocio->UsuarioIngreso = auth()->user()->id;
        $negocio->save();

        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function show($id)
    {
        $ejecutivo = Ejecutivo::where('Activo','1')->get();
        return view('catalogo.negocio.show', compact('ejecutivo'));
    }

    public function consultar(Request $request){
        $negocio = Negocio::with('aseguradora')->whereBetween('FechaVenta',[$request->FechaInicio, $request->FechaFinal])->get();
        //dd($negocio);
        return view('catalogo.negocio.consulta', compact('negocio')); 
    }

    public function edit($id)
    {
        $negocio = Negocio::findOrFail($id); 
        $aseguradoras = Aseguradora::where('Activo','=',1)->get();
        $tipos_poliza = TipoPoliza::where('Activo','=',1)->get();
        $tipos_negocio = TipoNegocio::where('Activo','=',1)->get();
        $estados_venta = EstadoVenta::where('Activo','=',1)->get();
        $ejecutivos = Ejecutivo::where('Activo','=',1)->get();

        return view('catalogo.negocio.edit', compact('negocio','aseguradoras','tipos_poliza','tipos_negocio','estados_venta','ejecutivos') );
    }

    public function update(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        $negocio->Asegurado = $request->Asegurado;
        $negocio->Aseguradora = $request->Aseguradora;
        $negocio->FechaVenta = $request->FechaVenta;
        $negocio->TipoPoliza = $request->TipoPoliza;
        $negocio->InicioVigencia = $request->InicioVigencia;
        $negocio->SumaAsegurada = $request->SumaAsegurada;
        $negocio->Prima = $request->Prima;
        $negocio->Observacion = $request->Observacion;
        $negocio->TipoNegocio = $request->TipoNegocio;
        $negocio->EstadoVenta = $request->EstadoVenta;
        $negocio->Ejecutivo = $request->Ejecutivo;
        $negocio->update();
        alert()->success('El registro ha sido modificado correctamente');
        return back(); 
    }

    public function destroy($id)
    {
        Negocio::findOrFail($id)->update(['Activo' => 0]);       
        alert()->error('El registro ha sido desactivado correctamente');
        return back(); 
    }
}
