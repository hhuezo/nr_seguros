<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\NecesidadProteccionFormRequest;
use App\Models\catalogo\NecesidadProteccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NecesidadProteccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $necesidad_proteccion = NecesidadProteccion::where('Activo',1)->get();
        return view('catalogo.necesidad_proteccion.index', compact('necesidad_proteccion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.necesidad_proteccion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NecesidadProteccionFormRequest $request)
    {
        $necesidad_proteccion = new NecesidadProteccion();
        $necesidad_proteccion->Nombre = $request->Nombre;
        $necesidad_proteccion->Activo = 1;
        $necesidad_proteccion->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/necesidad_proteccion');

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
        $necesidad_proteccion = NecesidadProteccion::findOrFail($id);
        return view('catalogo.necesidad_proteccion.edit', compact('necesidad_proteccion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NecesidadProteccionFormRequest $request, $id)
    {
        $necesidad_proteccion = NecesidadProteccion::findOrFail($id);
        $necesidad_proteccion->Nombre = $request->Nombre;
        $necesidad_proteccion->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/necesidad_proteccion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $necesidad_proteccion = NecesidadProteccion::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
