<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\polizas\VidaCatalogoTipoCartera;
use Illuminate\Http\Request;

class TipoCarteraVidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_cartera = VidaCatalogoTipoCartera::where('Activo',1)->get();
        return view('catalogo.tipo_cartera_vida.index',compact('tipo_cartera'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.tipo_cartera_vida.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo_cartera = new VidaCatalogoTipoCartera();
        $tipo_cartera->Nombre = $request->Nombre;
        $tipo_cartera->Activo = 1;
        $tipo_cartera->save();
        alert()->success('Registro agregado correctamente');
        return redirect('catalogo/tipo_cartera_vida');
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
        $tipo_cartera = VidaCatalogoTipoCartera::findOrFail($id);
        return view('catalogo.tipo_cartera_vida.edit', compact('tipo_cartera'));

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
        $tipo_cartera = VidaCatalogoTipoCartera::findOrFail($id);
        $tipo_cartera->Nombre = $request->Nombre;
        $tipo_cartera->update();

        alert()->success('Registro modificado correctamente');
        return redirect('catalogo/tipo_cartera_vida');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_cartera = VidaCatalogoTipoCartera::findOrFail($id);
        $tipo_cartera->Activo = 0;
        $tipo_cartera->update();

        alert()->error('Registro eliminado correctamente');
        return redirect('catalogo/tipo_cartera_vida');
    }
}
