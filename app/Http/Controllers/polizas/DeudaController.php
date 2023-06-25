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
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaRequisitos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DeudaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $bombero = Bombero::where('Activo',1)->first();
        if($bombero){
           $bomberos = $bombero->Valor;
        }
        else{
            $bomberos = $bombero->Valor;
        }
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza',2)->get();  //deuda
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

    public function store_requisitos(Request $request)
    {
        $requisito = new DeudaRequisitos();
        $requisito->Requisito= $request->Requisito;
        $requisito->EdadInicial= $request->EdadInicial;
        $requisito->EdadFinal= $request->EdadFinal;
        $requisito->MontoInicial= $request->MontoInicial;
        $requisito->MontoFinal= $request->MontoFinal;
        $requisito->EdadInicial2= $request->EdadInicial2;
        $requisito->EdadFinal2= $request->EdadFinal2;
        $requisito->MontoInicial2= $request->MontoInicial2;
        $requisito->MontoFinal2= $request->MontoFinal2;
        $requisito->EdadInicial3= $request->EdadInicial3;
        $requisito->EdadFinal3= $request->EdadFinal3;
        $requisito->MontoInicial3= $request->MontoInicial3;
        $requisito->MontoFinal3= $request->MontoFinal3;
        $requisito->save();
        return $requisito->Id;
    }

    public function get_requisitos(Request $request)
    {
        $sql =  "select * from poliza_deuda_requisitos where id in ($request->Requisitos)";
        $requisitos =  DB::select($sql);

        return view('polizas.deuda.requisitos', compact('requisitos'));
    }


    public function store(Request $request)
    {
        //$deuda = new Deuda();



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
        //
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
}
