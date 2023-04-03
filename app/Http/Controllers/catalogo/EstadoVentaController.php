<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\EstadoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EstadoVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estado_venta = EstadoVenta::all();
        return view('catalogo.estado_venta.index', compact('estado_venta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.estado_venta.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estado_venta = new EstadoVenta();
        $estado_venta->Nombre = $request->Nombre;
        $estado_venta->Activo = 1;
        $estado_venta->save();

        
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/estado_venta/create');

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
        $estado_venta = EstadoVenta::findOrFail($id);
        return view('catalogo.estado_venta.edit', compact('estado_venta'));
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
        $estado_venta = EstadoVenta::findOrFail($id);
        $estado_venta->Nombre = $request->Nombre;
        $estado_venta->update();

        
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/estado_venta');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estado_venta = EstadoVenta::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
