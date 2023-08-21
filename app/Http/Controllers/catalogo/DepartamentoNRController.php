<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartamentoNRFormRequest;
use App\Models\catalogo\DepartamentoNR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class DepartamentoNRController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $departamento_nr = DepartamentoNR::where('Activo',1)->get();
        return view('catalogo.departamento_nr.index', compact('departamento_nr'));
    }

    public function create()
    {
        return view('catalogo.departamento_nr.create');
    }

    public function store(DepartamentoNRFormRequest $request)
    {
        $departamento_nr = new DepartamentoNR();
        $departamento_nr->Nombre = $request->Nombre;
        $departamento_nr->Activo = 1;
        $departamento_nr->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/departamento_nr');

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $departamento_nr = DepartamentoNR::findOrFail($id);
        return view('catalogo.departamento_nr.edit', compact('departamento_nr'));
    }

    public function update(DepartamentoNRFormRequest $request, $id)
    {
        $departamento_nr = DepartamentoNR::findOrFail($id);
        $departamento_nr->Nombre = $request->Nombre;
        $departamento_nr->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/departamento_nr');
    }

    public function destroy($id)
    {
        DepartamentoNR::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }

}
