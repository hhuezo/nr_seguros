<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\suscripcion\Compania;
use Illuminate\Support\Facades\Redirect;

class CompaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $companias = Compania::where('Activo', 1)->get();
        return view('suscripciones.compania.index', compact('companias'));
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
        $companias = new Compania();
        $companias->Nombre = $request->Nombre;
        $companias->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('companias');
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
        $companias = Compania::findOrFail($id);
        $companias->Nombre = $request->Nombre;
        $companias->update();
        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('companias');
        $companias->update();
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
        $companias = Compania::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
