<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\suscripcion\FechasFeriadas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FechasFeriadasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fechasferiadas = FechasFeriadas::where('Activo', 1)->get();
        return view('suscripciones.fechas_feriadas.index', compact('fechasferiadas'));
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
        $reglas = [
            'FechaInicio' => [
                'required',
                'before_or_equal:FechaFinal'
            ],
            'FechaFinal' => [
                'required',
                'after_or_equal:FechaInicio'
            ],
            'Descripcion' => [
                'required'
            ]
        ];

        $mensajes = [
            'FechaInicio.required' => 'La fecha inicio es requerida',
            'FechaInicio.before_or_equal' => 'La fecha inicio no puede ser mayor a la fecha final',

            'FechaFinal.required' => 'La fecha final es requerida',
            'FechaFinal.after_or_equal' => 'La fecha final no puede ser menor a la fecha inicio',

            'Descripcion.required' => 'La descripción es requerida',
        ];

        // Ejecutar validación
        Validator::make($request->all(), $reglas, $mensajes)->validate();

        $fechasferiadas = new FechasFeriadas();
        $fechasferiadas->FechaInicio = $request->FechaInicio;
        $fechasferiadas->FechaFinal = $request->FechaFinal;
        $fechasferiadas->Descripcion = $request->Descripcion;
        $fechasferiadas->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('fechasferiadas');
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
        //validaciones
        $reglas = [
            'FechaInicio' => [
                'required',
                'before_or_equal:FechaFinal'
            ],
            'FechaFinal' => [
                'required',
                'after_or_equal:FechaInicio'
            ],
            'Descripcion' => [
                'required'
            ]
        ];

        $mensajes = [
            'FechaInicio.required' => 'La fecha inicio es requerida',
            'FechaInicio.before_or_equal' => 'La fecha inicio no puede ser mayor a la fecha final',

            'FechaFinal.required' => 'La fecha final es requerida',
            'FechaFinal.after_or_equal' => 'La fecha final no puede ser menor a la fecha inicio',

            'Descripcion.required' => 'La descripción es requerida',
        ];

        // Ejecutar validación
        Validator::make($request->all(), $reglas, $mensajes)->validate();
        $fechasferiadas = FechasFeriadas::findorfail($id);
        $fechasferiadas->FechaInicio = $request->FechaInicio;
        $fechasferiadas->FechaFinal = $request->FechaFinal;
        $fechasferiadas->Descripcion = $request->Descripcion;
        $fechasferiadas->update();
        alert()->success('El registro ha sido modificado correctamente');
        return redirect::to('fechasferiadas');
        $fechasferiadas->update();
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
        FechasFeriadas::findorfail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
