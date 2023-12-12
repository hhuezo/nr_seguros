<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perfiles = Perfil::where('Activo',1)->get();
        return view('catalogo.perfiles.index', compact('perfiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aseguradoras = Aseguradora::where('Activo',1)->get();
        return view('catalogo.perfiles.create', compact('aseguradoras'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $perfiles = new Perfil();
        $perfiles->Descripcion = $request->get('Descripcion');
        $perfiles->Aseguradora = $request->get('Aseguradora');
        $perfiles->save();

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
        $perfil = Perfil::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo',1)->get();
        return view('catalogo.perfiles.edit', compact('perfil','aseguradoras'));
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
        $perfiles = Perfil::findOrFail($id);
        $perfiles->Descripcion = $request->get('Descripcion');
        $perfiles->Aseguradora = $request->get('Aseguradora');
        $perfiles->update();

        return redirect('catalogo/perfiles');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $perfiles = Perfil::findOrFail($id);
        $perfiles->Activo = 0;
        $perfiles->update();

        return redirect('catalogo/perfiles');
    }
}
