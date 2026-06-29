<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\suscripcion\ResumenGestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ResumenGestionController extends Controller
{
    public function index()
    {
        $resumenes = ResumenGestion::where('Activo', 1)->get();

        return view('suscripciones.resumen_gestion.index', compact('resumenes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Color' => 'nullable|string|in:success,danger,info,warning,primary,secondary',
        ]);

        $resumen = new ResumenGestion();
        $resumen->Nombre = $request->Nombre;
        $resumen->Color = $request->Color ?: null;
        $resumen->Activo = 1;
        $resumen->save();

        alert()->success('El registro ha sido creado correctamente');

        return Redirect::to('resumengestiones');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Color' => 'nullable|string|in:success,danger,info,warning,primary,secondary',
        ]);

        $resumen = ResumenGestion::findOrFail($id);
        $resumen->Nombre = $request->Nombre;
        $resumen->Color = $request->Color ?: null;
        $resumen->update();

        alert()->success('El registro ha sido modificado correctamente');

        return Redirect::to('resumengestiones');
    }

    public function destroy($id)
    {
        ResumenGestion::findOrFail($id)->update(['Activo' => 0]);

        alert()->error('El registro ha sido desactivado correctamente');

        return back();
    }
}
