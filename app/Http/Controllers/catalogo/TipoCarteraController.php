<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoCarteraFormRequest;
use App\Models\catalogo\TipoCartera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TipoCarteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_cartera = TipoCartera::orderBy('Poliza')->where('Activo',1)->get();
        return view('catalogo.tipo_cartera.index', compact('tipo_cartera'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.tipo_cartera.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoCarteraFormRequest $request)
    {
        $tipo_cartera = new TipoCartera();
        $tipo_cartera->Nombre = $request->Nombre;
        $tipo_cartera->Activo = 1;
        $tipo_cartera->Poliza = $request->Poliza;
        $tipo_cartera->save();

        alert()->success('El registro ha sido agregado correctamente');
        return back();
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
        $tipo_cartera = TipoCartera::findOrFail($id);
        return view('catalogo.tipo_cartera.edit', compact('tipo_cartera'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoCarteraFormRequest $request, $id)
    {
        $tipo_cartera = TipoCartera::findOrFail($id);
        $tipo_cartera->Nombre = $request->Nombre;
        $tipo_cartera->Poliza = $request->Poliza;
        $tipo_cartera->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/tipo_cartera');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_cartera = TipoCartera::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
