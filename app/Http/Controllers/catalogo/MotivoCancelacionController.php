<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\MotivoCancelacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MotivoCancelacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $motivos_cancelacion = MotivoCancelacion::where('Activo', 1)->get();
        return view('catalogo.motivo_cancelacion.index', compact('motivos_cancelacion'));
    }

    public function create()
    {
        return Redirect::to('catalogo/motivo_cancelacion');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
        ]);

        $motivo = new MotivoCancelacion();
        $motivo->Nombre = $request->Nombre;
        $motivo->Activo = 1;
        $motivo->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/motivo_cancelacion');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/motivo_cancelacion');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
        ]);

        $motivo = MotivoCancelacion::findOrFail($id);
        $motivo->Nombre = $request->Nombre;
        $motivo->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/motivo_cancelacion');
    }

    public function destroy($id)
    {
        MotivoCancelacion::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
