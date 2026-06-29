<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\EstadoCertificado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EstadoCertificadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $estados_certificado = EstadoCertificado::where('Activo', 1)->get();
        return view('catalogo.estado_certificado.index', compact('estados_certificado'));
    }

    public function create()
    {
        return Redirect::to('catalogo/estado_certificado');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
        ]);

        $estado = new EstadoCertificado();
        $estado->Nombre = $request->Nombre;
        $estado->Activo = 1;
        $estado->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/estado_certificado');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/estado_certificado');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
        ]);

        $estado = EstadoCertificado::findOrFail($id);
        $estado->Nombre = $request->Nombre;
        $estado->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/estado_certificado');
    }

    public function destroy($id)
    {
        EstadoCertificado::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
