<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\suscripcion\EstadoCaso;
use Illuminate\Support\Facades\Redirect;

class EstadoCasoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $estadoscasos = EstadoCaso::where('Activo', 1)->get();
        return view('suscripciones.estado_caso.index', compact('estadoscasos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $estadoscasos = new EstadoCaso();
        $estadoscasos->Nombre = $request->Nombre;
        $estadoscasos->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('estadoscasos');
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
        $estadoscasos = EstadoCaso::findOrFail($id);
        $estadoscasos->Nombre = $request->Nombre;
        $estadoscasos->update();
        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('estadoscasos');
        $estadoscasos->update();
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
        $estadoscasos = EstadoCaso::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
