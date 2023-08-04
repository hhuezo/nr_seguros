<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaComercialFormRequest;
use App\Models\catalogo\AreaComercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AreaComercialController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $area_comercial = AreaComercial::where('Activo',1)->get();
        return view('catalogo.area_comercial.index', compact('area_comercial'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.area_comercial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreaComercialFormRequest $request)
    {
        $area_comercial = new AreaComercial();
        $area_comercial->Nombre = $request->Nombre;
        $area_comercial->Activo = 1;
        $area_comercial->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/area_comercial/create');

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
        $area_comercial = AreaComercial::findOrFail($id);
        return view('catalogo.area_comercial.edit', compact('area_comercial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AreaComercialFormRequest $request, $id)
    {
        $area_comercial = AreaComercial::findOrFail($id);
        $area_comercial->Nombre = $request->Nombre;
        $area_comercial->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/area_comercial');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area_comercial = AreaComercial::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
