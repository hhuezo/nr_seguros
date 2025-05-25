<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\suscripcion\OrdenMedica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TipoOrdenMedicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tiposordenesmedicas = OrdenMedica::where('Activo', 1)->get();
        return view('suscripciones.tipo_orden_medica.index', compact('tiposordenesmedicas'));
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
        //
        $tiposordenesmedicas = new OrdenMedica();
        $tiposordenesmedicas->Nombre = $request->Nombre;
        $tiposordenesmedicas->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('tiposordenesmedicas');
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
        $tiposordenesmedicas = OrdenMedica::findOrFail($id);
        $tiposordenesmedicas->Nombre = $request->Nombre;
        $tiposordenesmedicas->update();
        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('tiposordenesmedicas');
        $tiposordenesmedicas->update();
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
        $tiposordenesmedicas = OrdenMedica::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
