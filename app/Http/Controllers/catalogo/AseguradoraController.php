<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\TipoContribuyente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AseguradoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aseguradora = Aseguradora::all();
        return view('catalogo.aseguradora.index', compact('aseguradora'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo_contribuyente = TipoContribuyente::get();
        return view('catalogo.aseguradora.create', compact('tipo_contribuyente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'Nombre.required' => 'El campo nombre es requerido',
            'Nombre.unique' => 'El nombre ya existe',
            'Nit.required' => 'El campo NIT es requerido',
            'Nit.unique' => 'El Nit ya existe',
        ];



        $request->validate([   
            'Nombre' => 'required|unique:aseguradora',
            'Nit' => 'required|unique:aseguradora',
        ], $messages);

        $max = Aseguradora::max('Codigo');

        $aseguradora = new Aseguradora();
        $aseguradora->Nombre = $request->Nombre;
        $aseguradora->Codigo = $max + 1;
        $aseguradora->Nit = $request->Nit;
        $aseguradora->RegistroFiscal = $request->RegistroFiscal;
        $aseguradora->Abreviatura = $request->Abreviatura;
        $aseguradora->FechaVinculacion = $request->FechaVinculacion;
        $aseguradora->TipoContribuyente = $request->TipoContribuyente;
        $aseguradora->PaginaWeb = $request->PaginaWeb;
        $aseguradora->FechaConstitucion = $request->FechaConstitucion;
        $aseguradora->Direccion = $request->Direccion;
        $aseguradora->TelefonoFijo = $request->TelefonoFijo;
        $aseguradora->TelefonoWhatsapp = $request->TelefonoWhatsapp;
        $aseguradora->Activo = 1;
        $aseguradora->save();

        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/aseguradoras/' . $aseguradora->Id . '/edit');
        //return Redirect::to('catalogo/aseguradoras/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $aseguradora = Aseguradora::findOrFail($id);
        $tipo_contribuyente = TipoContribuyente::get();
        return view('catalogo/aseguradora/edit', compact('aseguradora','tipo_contribuyente'));
    }

    public function update(Request $request, $id)
    {

        $messages = [
            'Nombre.required' => 'El campo nombre es requerido',
        ];



        $request->validate([
            'Nombre' => 'required',
        ], $messages);

        $aseguradora = Aseguradora::findOrFail($id);
        $aseguradora->Nombre = $request->Nombre;
        $aseguradora->Nit = $request->Nit;
        $aseguradora->RegistroFiscal = $request->RegistroFiscal;
        $aseguradora->Abreviatura = $request->Abreviatura;
        $aseguradora->FechaVinculacion = $request->FechaVinculacion;
        $aseguradora->TipoContribuyente = $request->TipoContribuyente;
        $aseguradora->PaginaWeb = $request->PaginaWeb;
        $aseguradora->FechaConstitucion = $request->FechaConstitucion;
        $aseguradora->Direccion = $request->Direccion;
        $aseguradora->TelefonoFijo = $request->TelefonoFijo;
        $aseguradora->TelefonoWhatsapp = $request->TelefonoWhatsapp;
        $aseguradora->update();

        alert()->success('El registro ha sido creado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras/' . $id . 'edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aseguradora = Aseguradora::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras');
    }
}
