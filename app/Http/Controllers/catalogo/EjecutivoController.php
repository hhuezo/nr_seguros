<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
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
        $identificador_carrito = session('idCarrito');
        $ejecutivo = Ejecutivo::where('Activo','=',1)->get();
        return view('catalogo.ejecutivo.index',compact('ejecutivo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session(['tab_menu' => 'ejecutivo']);
        $area_comercial = AreaComercial::where('Activo',1)->get();
        return view('catalogo.ejecutivo.create', compact('area_comercial'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'Nombre.required' => 'El campo nombre es requerido',
        ];

        $request->validate([
            'Nombre' => 'required',
        ], $messages);


        $ejecutivo = new Ejecutivo();
        $ejecutivo->Nombre = $request->Nombre;
        $ejecutivo->Codigo = $request->Codigo;
        $ejecutivo->Telefono = $request->Telefono;
        $ejecutivo->Activo = 1;
        $ejecutivo->AreaComercial = $request->AreaComercial;
        $ejecutivo->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/ejecutivo/create');
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
        $area_comercial = AreaComercial::where('Activo',1)->get();
        $ejecutivo = Ejecutivo::findOrFail($id);
        return view('catalogo.ejecutivo.edit', compact('ejecutivo','area_comercial'));
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
        $ejecutivo = Ejecutivo::findOrFail($id);
        $ejecutivo->Nombre = $request->Nombre;
        $ejecutivo->Codigo = $request->Codigo;
        $ejecutivo->Telefono = $request->Telefono;
        $ejecutivo->AreaComercial = $request->AreaComercial;
        $ejecutivo->update();

        alert()->success('El registro ha sido modificado correctamente');
        return back();
        
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
