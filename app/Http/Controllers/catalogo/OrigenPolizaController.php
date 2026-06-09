<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\OrigenPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrigenPolizaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $origen_poliza = OrigenPoliza::where('Activo', 1)->get();
        return view('catalogo.origen_poliza.index', compact('origen_poliza'));
    }

    public function create()
    {
        return Redirect::to('catalogo/origen_poliza');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:45',
        ]);

        $origen = new OrigenPoliza();
        $origen->Nombre = $request->Nombre;
        $origen->Activo = 1;
        $origen->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/origen_poliza');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/origen_poliza');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:45',
        ]);

        $origen = OrigenPoliza::findOrFail($id);
        $origen->Nombre = $request->Nombre;
        $origen->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/origen_poliza');
    }

    public function destroy($id)
    {
        OrigenPoliza::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
