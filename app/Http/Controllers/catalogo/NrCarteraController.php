<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\NrCartera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NrCarteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nr_cartera = NrCartera::where('Activo',1)->get();
        return view('catalogo.nr_cartera.index',compact('nr_cartera'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.nr_cartera.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cartera = new NrCartera();
        $cartera->Nombre = $request->Nombre;
        $cartera->Activo = 1;
        $cartera->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/nr_seguros');
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
        $cartera = NrCartera::findOrFail($id);
        
        return view('catalogo.nr_cartera.edit', compact('cartera'));
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
        $cartera = NrCartera::findOrFail($id);
        $cartera->Nombre = $request->Nombre;
        $cartera->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/nr_cartera');
    

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cartera = NrCartera::findOrFail($id);
        $cartera->Activo = 0;
        $cartera->update();

        alert()->success('El registro ha sido eliminado correctamente');
        return Redirect::to('catalogo/nr_cartera');
    }
}
