<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoPolizaFormRequest;
use App\Models\catalogo\TipoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TipoPolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_poliza = TipoPoliza::where('Activo',1)->get();
        return view('catalogo.tipo_poliza.index', compact('tipo_poliza'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.tipo_poliza.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoPolizaFormRequest $request)
    {
        $tipo_poliza = new TipoPoliza();
        $tipo_poliza->Nombre = $request->Nombre;
        $tipo_poliza->Activo = 1;
        $tipo_poliza->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/tipo_poliza');

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
        $tipo_poliza = TipoPoliza::findOrFail($id);
        return view('catalogo.tipo_poliza.edit', compact('tipo_poliza'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoPolizaFormRequest $request, $id)
    {
        $tipo_poliza = TipoPoliza::findOrFail($id);
        $tipo_poliza->Nombre = $request->Nombre;
        $tipo_poliza->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/tipo_poliza');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_poliza = TipoPoliza::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
