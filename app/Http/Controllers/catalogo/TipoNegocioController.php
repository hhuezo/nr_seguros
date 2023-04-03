<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\TipoNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;


class TipoNegocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_negocio = TipoNegocio::all();
        return view('catalogo.tipo_negocio.index', compact('tipo_negocio'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.tipo_negocio.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo_negocio = new TipoNegocio();
        $tipo_negocio->Nombre = $request->Nombre;
        $tipo_negocio->Activo = 1;
        $tipo_negocio->save();

        alert()->success('El registro ha sido agregado correctamente');
        return redirect(TipoNegocio::index());
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
        $tipo_negocio = TipoNegocio::findOrFail($id);
        return view('catalogo.tipo_negocio.edit', compact('tipo_negocio'));
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
        $tipo_negocio = TipoNegocio::findOrFail($id);
        $tipo_negocio->Nombre = $request->Nombre;
        $tipo_negocio->update();

        
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/tipo_negocio');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_negocio = TipoNegocio::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
