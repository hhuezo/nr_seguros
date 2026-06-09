<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Deducible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DeducibleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tipo_deducible = Deducible::where('Activo', 1)->get();
        return view('catalogo.tipo_deducible.index', compact('tipo_deducible'));
    }

    public function create()
    {
        return Redirect::to('catalogo/tipo_deducible');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:45',
        ]);

        $deducible = new Deducible();
        $deducible->Nombre = $request->Nombre;
        $deducible->Activo = 1;
        $deducible->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/tipo_deducible');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/tipo_deducible');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:45',
        ]);

        $deducible = Deducible::findOrFail($id);
        $deducible->Nombre = $request->Nombre;
        $deducible->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/tipo_deducible');
    }

    public function destroy($id)
    {
        Deducible::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
