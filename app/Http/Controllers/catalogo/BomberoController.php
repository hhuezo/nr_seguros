<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Bombero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BomberoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bombero = Bombero::all();
        return view('catalogo.bombero.index', compact('bombero'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogo.bombero.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bombero_ultimo = Bombero::where('Activo', 1)->first();
        if ($bombero_ultimo) {
            $bombero_ultimo->Activo = 0;
            $bombero_ultimo->update();
        }
        $bombero = new Bombero();
        $bombero->Valor = $request->Valor;
        $bombero->Activo = 1;
        $bombero->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/bombero/create');
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
        $bombero = Bombero::findOrFail($id);
        return view('catalogo.bombero.edit', compact('bombero'));
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
        $bombero = Bombero::findOrFail($id);
        $bombero->Valor = $request->Valor;
        $bombero->update();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/bombero');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bombero = Bombero::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
