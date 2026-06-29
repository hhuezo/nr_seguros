<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Parentesco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ParentescoBeneficiarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $parentescos = Parentesco::where('Activo', 1)
            ->orderBy('Nombre')
            ->get();

        return view('catalogo.parentesco_beneficiario.index', compact('parentescos'));
    }

    public function create()
    {
        return Redirect::to('catalogo/parentesco_beneficiario');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
        ]);

        $parentesco = new Parentesco();
        $parentesco->Nombre = $request->Nombre;
        $parentesco->Activo = 1;
        $parentesco->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/parentesco_beneficiario');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/parentesco_beneficiario');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
        ]);

        $parentesco = Parentesco::findOrFail($id);
        $parentesco->Nombre = $request->Nombre;
        $parentesco->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/parentesco_beneficiario');
    }

    public function destroy($id)
    {
        Parentesco::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
