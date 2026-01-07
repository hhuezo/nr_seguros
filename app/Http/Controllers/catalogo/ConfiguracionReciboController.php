<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\ConfiguracionRecibo;
use App\Models\catalogo\DatosGenerales;
use Illuminate\Http\Request;

class ConfiguracionReciboController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $configuracion = ConfiguracionRecibo::get();
        return view('catalogo.configuracion_recibo.index', compact('configuracion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form_numeracion_recibo()
    {
        $datos_generares = DatosGenerales::first();
        return view('catalogo.configuracion_recibo.numeracion_recibo', compact('datos_generares'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function numeracion_recibo(Request $request, $id)
    {
        $datos_generales = DatosGenerales::findOrFail($id);
        $datos_generales->Id_recibo = $request->Id_recibo;
        $datos_generales->save();
        return back()->with('success', 'Numeracion de recibo actualizada correctamente');
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
        $configuracion_recibo = ConfiguracionRecibo::findOrFail($id);
        return view('catalogo.configuracion_recibo.edit', compact('configuracion_recibo'));
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
        $configuracion_recibo = ConfiguracionRecibo::findOrFail($id);
        $configuracion_recibo->Nota = $request->Nota;
        $configuracion_recibo->Pie = $request->Pie;
        $configuracion_recibo->save();
        alert()->success('El registro ha sido modificados correctamente');
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
