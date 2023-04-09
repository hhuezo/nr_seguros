<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoCobro;
use App\Models\polizas\DepositoPlazo;
use Illuminate\Http\Request;

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
        $aseguradora = Aseguradora::where('Activo',1)->get();
        $cliente = Cliente::where('Activo',1)->get();
        $tipoCartera = TipoCartera::where('Activo',1)->get();
        $estadoPoliza = EstadoPoliza::where('Activo',1)->get();
        $tipoCobro = TipoCobro::where('Activo',1)->get();
        $ejecutivo = Ejecutivo::where('Activo',1)->get();
        return view('polizas.deposito_plazo.create',compact('aseguradora','cliente','tipoCartera','estadoPoliza','tipoCobro','ejecutivo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
