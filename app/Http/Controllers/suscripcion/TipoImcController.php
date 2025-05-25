<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\suscripcion\TipoImc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TipoImcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tiposimc = TipoImc::where('Activo', 1)->get();
        return view('suscripciones.tipo_imc.index', compact('tiposimc'));
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
        $tiposimc = new TipoImc();
        $tiposimc->Nombre = $request->Nombre;
        $tiposimc->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('tiposimc');
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
        $tiposimc=TipoImc::findorfail($id);
        $tiposimc->Nombre = $request->Nombre;
        $tiposimc->update();
        alert()->success('El registro ha sido modificado correctamente');
        return redirect::to('tiposimc');
        $tiposimc->update();
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
        $tiposimc= TipoImc::findorfail($id)->update(['Activo'=>0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
