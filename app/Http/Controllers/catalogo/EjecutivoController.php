<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\EjecutivoFormRequest;
use App\Models\catalogo\AreaComercial;
use App\Models\catalogo\Ejecutivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EjecutivoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        session(['tab_menu' => 'ejecutivo']);
    }

    public function index()
    {
        $area_comercial = AreaComercial::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::with('areaComercial')->where('Activo', 1)->get();
        $identificador_carrito = session('idCarrito');

        return view('catalogo.ejecutivo.index', compact('ejecutivo', 'area_comercial'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session(['tab_menu' => 'ejecutivo']);
        $area_comercial = AreaComercial::where('Activo', 1)->get();
        return view('catalogo.ejecutivo.create', compact('area_comercial'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EjecutivoFormRequest $request)
    {

        $ejecutivo = new Ejecutivo();
        $ejecutivo->Nombre = $request->Nombre;
        $ejecutivo->Codigo = $request->Codigo;
        $ejecutivo->Telefono = $request->Telefono;
        $ejecutivo->Correo = $request->Correo;
        $ejecutivo->Activo = 1;
        $ejecutivo->AreaComercial = $request->AreaComercial;
        $ejecutivo->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/ejecutivos');
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
        $area_comercial = AreaComercial::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::findOrFail($id);
        return view('catalogo.ejecutivo.edit', compact('ejecutivo', 'area_comercial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EjecutivoFormRequest $request, $id)
    {
        $ejecutivo = Ejecutivo::findOrFail($id);
        $ejecutivo->Nombre = $request->Nombre;
        $ejecutivo->Codigo = $request->Codigo;
        $ejecutivo->Telefono = $request->Telefono;
        $ejecutivo->Correo = $request->Correo;
        $ejecutivo->AreaComercial = $request->AreaComercial;
        $ejecutivo->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/ejecutivos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ejecutivo = Ejecutivo::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
