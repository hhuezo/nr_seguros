<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\suscripcion\TipoCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TipoClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tiposclientes = TipoCliente::where('Activo', 1)->get();
        return view('suscripciones.tipo_cliente.index', compact('tiposclientes'));
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
        $tiposclientes = new TipoCliente();
        $tiposclientes->Nombre = $request->Nombre;
        $tiposclientes->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('tiposclientes');
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
        $tiposclientes = TipoCliente::findorfail($id);
        $tiposclientes->Nombre = $request->Nombre;
        $tiposclientes->update();
        alert()->success('El registro ha sido modificado correctamente');
        return redirect::to('tiposclientes');
        $tiposclientes->update();
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
        $tiposclientes = TipoCliente::findorfail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
