<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\suscripcion\Ocupacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OcupacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ocupaciones = Ocupacion::where('Activo', 1)->get();
        return view('suscripciones.ocupacion.index', compact('ocupaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //validaciones
        $request->validate(
            [
                'Nombre' => 'required|unique:sus_ocupacion,Nombre'
            ],
            [
                'Nombre.unique' => 'Esta ocupación ya existe',
                'Nombre.required' => 'El nombre es requerido'
            ]
        );
        //
        $ocupaciones = new Ocupacion();
        $ocupaciones->Nombre = $request->Nombre;
        $ocupaciones->save();

        // Si es AJAX, devuelve JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'ocupacion' => $ocupaciones,
                'message' => 'El registro ha sido creado correctamente'
            ]);
        }
        // Si no es AJAX (fallback)
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('ocupaciones');
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
        //
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
        //
        $ocupaciones = Ocupacion::findorfail($id);
        $ocupaciones->Nombre = $request->Nombre;
        $ocupaciones->update();
        alert()->success('El registro ha sido modificado correctamente');
        return redirect::to('ocupaciones');
        $ocupaciones->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $ocupaciones = Ocupacion::findorfail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
