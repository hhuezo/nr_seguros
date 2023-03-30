<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

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
        return view('catalogo.aseguradora.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aseguradora = new Aseguradora();
        $aseguradora->Nombre = $request->Nombre;
        $aseguradora->Codigo = $request->Codigo;
        $aseguradora->Telefono = $request->Telefono;
        $aseguradora->Contacto = $request->Contacto;
        $aseguradora->Direccion = $request->Direccion;
        $aseguradora->PaginaWeb = $request->PaginaWeb;
        $aseguradora->Fax = $request->Fax;
        $aseguradora->Nit = $request->Nit;
        $aseguradora->RegistroFiscal = $request->RegistroFiscal;
        $aseguradora->Abreviatura = $request->Abreviatura;
        $aseguradora->Correo = $request->Correo;
        $aseguradora->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/aseguradoras/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aseguradora = Aseguradora::findOrFail($id);
        return view('catalogo/aseguradora/edit', compact('aseguradora'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $aseguradora = Aseguradora::findOrFail($id);
        $aseguradora->Nombre = $request->Nombre;
        $aseguradora->Codigo = $request->Codigo;
        $aseguradora->Telefono = $request->Telefono;
        $aseguradora->Contacto = $request->Contacto;
        $aseguradora->Direccion = $request->Direccion;
        $aseguradora->PaginaWeb = $request->PaginaWeb;
        $aseguradora->Fax = $request->Fax;
        $aseguradora->Nit = $request->Nit;
        $aseguradora->RegistroFiscal = $request->RegistroFiscal;
        $aseguradora->Abreviatura = $request->Abreviatura;
        $aseguradora->Correo = $request->Correo;
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
        Alert::success('El registro ha sido desactivado correctamente');
        return Redirect::to('catalogo/aseguradoras');
    }
}
