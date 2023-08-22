<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\UbicacionCobroFormRequest;
use App\Models\catalogo\UbicacionCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UbicacionCobroController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ubicacion_cobro = UbicacionCobro::where('Activo',1)->get();
        return view('catalogo.ubicacion_cobro.index', compact('ubicacion_cobro'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.ubicacion_cobro.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UbicacionCobroFormRequest $request)
    {
        $ubicacion_cobro = new UbicacionCobro();
        $ubicacion_cobro->Nombre = $request->Nombre;
        $ubicacion_cobro->Activo = 1;
        $ubicacion_cobro->save();

        alert()->success('El registro ha sido agregado correctamente');
        return Redirect::to('catalogo/ubicacion_cobro');
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
        $ubicacion_cobro = UbicacionCobro::findOrFail($id);
        return view('catalogo.ubicacion_cobro.edit', compact('ubicacion_cobro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UbicacionCobroFormRequest $request, $id)
    {
        $ubicacion_cobro = UbicacionCobro::findOrFail($id);
        $ubicacion_cobro->Nombre = $request->Nombre;
        $ubicacion_cobro->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/ubicacion_cobro');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ubicacion_cobro = UbicacionCobro::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
