<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\NecesidadProteccionFormRequest;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\TipoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NecesidadProteccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;
        $necesidad_proteccion = NecesidadProteccion::where('Activo', 1)->orderBy('Id', 'asc')->get();
        $tipos_poliza = TipoPoliza::where('Activo', 1)->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $necesidad_proteccion->search(function ($n) use ($idRegistro) {
                return $n->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('catalogo.necesidad_proteccion.index', compact('necesidad_proteccion', 'posicion', 'tipos_poliza'));
    }

    public function create()
    {
        return view('catalogo.necesidad_proteccion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required',
            //'TipoPoliza' => 'required',
        ], [
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'TipoPoliza.required' => 'Debe seleccionar un módulo.',
        ]);
        $necesidad_proteccion = new NecesidadProteccion();
        $necesidad_proteccion->Nombre = $request->Nombre;
        $necesidad_proteccion->TipoPoliza = $request->TipoPoliza ?: null;
        $necesidad_proteccion->Activo = 1;
        $necesidad_proteccion->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/necesidad_proteccion?idRegistro=' . $necesidad_proteccion->Id);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $necesidad_proteccion = NecesidadProteccion::findOrFail($id);
        return view('catalogo.necesidad_proteccion.edit', compact('necesidad_proteccion'));
    }

    public function update(NecesidadProteccionFormRequest $request, $id)
    {
        $request->validate([
            'Nombre' => 'required',
            //'TipoPoliza' => 'required',
        ], [
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'TipoPoliza.required' => 'Debe seleccionar un módulo.',
        ]);

        $necesidad_proteccion = NecesidadProteccion::findOrFail($id);
        $necesidad_proteccion->Nombre = $request->Nombre;
        $necesidad_proteccion->TipoPoliza = $request->TipoPoliza ?: null;
        $necesidad_proteccion->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/necesidad_proteccion?idRegistro=' . $necesidad_proteccion->Id);
    }

    public function destroy($id)
    {
        $necesidad_proteccion = NecesidadProteccion::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
