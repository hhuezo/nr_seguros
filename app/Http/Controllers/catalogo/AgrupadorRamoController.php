<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgrupadorRamoFormRequest;
use App\Models\catalogo\AgrupadorRamo;
use Illuminate\Support\Facades\Redirect;

class AgrupadorRamoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $agrupadores = AgrupadorRamo::where('Activo', 1)->orderBy('Id', 'asc')->get();
        return view('catalogo.agrupador_ramo.index', compact('agrupadores'));
    }

    public function create()
    {
        return view('catalogo.agrupador_ramo.create');
    }

    public function store(AgrupadorRamoFormRequest $request)
    {
        $agrupador = new AgrupadorRamo();
        $agrupador->Nombre = $request->Nombre;
        $agrupador->Activo = 1;
        $agrupador->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/agrupador_ramo');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $agrupador = AgrupadorRamo::findOrFail($id);
        return view('catalogo.agrupador_ramo.edit', compact('agrupador'));
    }

    public function update(AgrupadorRamoFormRequest $request, $id)
    {
        $agrupador = AgrupadorRamo::findOrFail($id);
        $agrupador->Nombre = $request->Nombre;
        $agrupador->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/agrupador_ramo');
    }

    public function destroy($id)
    {
        AgrupadorRamo::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}

