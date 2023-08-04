<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoCobroFormRequest;
use App\Models\catalogo\TipoCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TipoCobroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_cobro = TipoCobro::where('Activo',1)->get();
        return view('catalogo.tipo_cobro.index', compact('tipo_cobro'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.tipo_cobro.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoCobroFormRequest $request)
    {
        $tipo_cobro = new TipoCobro();
        $tipo_cobro->Nombre = $request->Nombre;
        $tipo_cobro->Activo = 1;
        $tipo_cobro->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/tipo_cobro/create');

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
        $tipo_cobro = TipoCobro::findOrFail($id);
        return view('catalogo.tipo_cobro.edit', compact('tipo_cobro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoCobroFormRequest $request, $id)
    {
        $tipo_cobro = TipoCobro::findOrFail($id);
        $tipo_cobro->Nombre = $request->Nombre;
        $tipo_cobro->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/tipo_cobro');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_cobro = TipoCobro::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
