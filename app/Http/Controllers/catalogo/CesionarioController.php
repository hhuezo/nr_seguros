<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cesionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CesionarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cesionarios = Cesionario::where('Activo', 1)
            ->orderBy('Nombre', 'asc')
            ->get();

        return view('catalogo.cesionario.index', compact('cesionarios'));
    }

    public function create()
    {
        return Redirect::to('catalogo/cesionario');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:200',
        ]);

        Cesionario::create([
            'Nombre' => mb_strtoupper($request->Nombre, 'UTF-8'),
            'Activo' => 1,
        ]);

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/cesionario');
    }

    public function show($id)
    {
        return Redirect::to('catalogo/cesionario');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/cesionario');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:200',
        ]);

        $cesionario = Cesionario::findOrFail($id);
        $cesionario->Nombre = mb_strtoupper($request->Nombre, 'UTF-8');
        $cesionario->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/cesionario');
    }

    public function destroy($id)
    {
        Cesionario::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
