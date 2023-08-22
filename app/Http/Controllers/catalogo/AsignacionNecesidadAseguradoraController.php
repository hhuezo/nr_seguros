<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\AsignacionNecesidadAseguradora;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\TipoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AsignacionNecesidadAseguradoraController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asignacion = AsignacionNecesidadAseguradora::where('Activo',1)->get();
        return view('catalogo.necesidad_aseguradora.index', compact('asignacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo_polizas = TipoPoliza::where('Activo',1)->get();
        $aseguradoras = Aseguradora::where('Activo',1)->get();
        $necesidades = NecesidadProteccion::where('Activo',1)->get();
        return view('catalogo.necesidad_aseguradora.create', compact('tipo_polizas','aseguradoras','necesidades'));
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
            'Aseguradora.required' => 'La aseguradora es requerido',
            'NecesidadProteccion.required' => 'La necesidad de protección es requerido',
            'TipoPoliza.required' => 'La tipo de poliza es requerido',
        ];

        $request->validate([
            'Aseguradora' => 'required',
            'NecesidadProteccion' => 'required',
            'TipoPoliza' => 'required',
        ], $messages);


        $asignacion = new AsignacionNecesidadAseguradora();
        $asignacion->aseguradora_id = $request->Aseguradora;
        $asignacion->necesidad_proteccion_id = $request->NecesidadProteccion;
        $asignacion->TipoPoliza = $request->TipoPoliza;
        $asignacion->Activo = 1;
        $asignacion->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/necesidad_aseguradora');

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
        $tipo_polizas = TipoPoliza::where('Activo',1)->get();
        $aseguradoras = Aseguradora::where('Activo',1)->get();
        $necesidades = NecesidadProteccion::where('Activo',1)->get();
        $asignacion = AsignacionNecesidadAseguradora::findOrFail($id);
        return view('catalogo.necesidad_aseguradora.edit', compact('asignacion','tipo_polizas','aseguradoras','necesidades'));
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
        $asignacion = AsignacionNecesidadAseguradora::findOrFail($id);
        $asignacion->aseguradora_id = $request->Aseguradora;
        $asignacion->necesidad_proteccion_id = $request->NecesidadProteccion;
        $asignacion->TipoPoliza = $request->TipoPoliza;
        $asignacion->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/necesidad_aseguradora');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asignacion = AsignacionNecesidadAseguradora::findOrFail($id);
        $asignacion->Activo = 0;
        $asignacion->update();

        alert()->success('El registro ha sido eliminado correctamente');
        return Redirect::to('catalogo/necesidad_aseguradora');
    }
}
