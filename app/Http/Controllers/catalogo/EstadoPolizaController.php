<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\EstadoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class EstadoPolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estado_poliza = EstadoPoliza::all();
        return view('catalogo.estado_poliza.index', compact('estado_poliza'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.estado_poliza.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estado_poliza = new EstadoPoliza();
        $estado_poliza->Nombre = $request->Nombre;
        $estado_poliza->Activo = 1;
        $estado_poliza->save();

        
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/estado_poliza/create');

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
        $estado_poliza = EstadoPoliza::findOrFail($id);
        return view('catalogo.estado_poliza.edit', compact('estado_poliza'));
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
        $estado_poliza = EstadoPoliza::findOrFail($id);
        $estado_poliza->Nombre = $request->Nombre;
        $estado_poliza->update();

        
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/estado_poliza');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estado_poliza = EstadoPoliza::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
