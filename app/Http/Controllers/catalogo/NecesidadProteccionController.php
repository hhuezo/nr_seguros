<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\NecesidadProteccionFormRequest;
use App\Models\catalogo\AgrupadorRamo;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\NecesidadProteccionCampo;
use App\Models\catalogo\TipoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NecesidadProteccionController extends Controller
{
    private function validacionesCampo(): array
    {
        return [
            'ninguna',
            'dui',
            'solo_numeros',
            'solo_numeros_letras',
            'solo_texto',
            'correo',
        ];
    }

    private function tiposCampo(): array
    {
        return ['text', 'number', 'date', 'textarea', 'email'];
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;
        $necesidad_proteccion = NecesidadProteccion::with(['tipo_poliza', 'agrupador_ramo'])
            ->where('Activo', 1)
            ->orderBy('Id', 'asc')
            ->get();
        $tipos_poliza = TipoPoliza::where('Activo', 1)->get();
        $agrupadores_ramo = AgrupadorRamo::where('Activo', 1)->orderBy('Nombre', 'asc')->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $necesidad_proteccion->search(function ($n) use ($idRegistro) {
                return $n->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('catalogo.necesidad_proteccion.index', compact('necesidad_proteccion', 'posicion', 'tipos_poliza', 'agrupadores_ramo'));
    }

    public function create()
    {
        $tipos_poliza = TipoPoliza::where('Activo', 1)->get();
        $agrupadores_ramo = AgrupadorRamo::where('Activo', 1)->orderBy('Nombre', 'asc')->get();

        return view('catalogo.necesidad_proteccion.create', compact('tipos_poliza', 'agrupadores_ramo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required',
            'AgrupadorRamo' => 'nullable|exists:agrupador_ramo,Id',
            'PorcentajeComisionNoDeclarativa' => 'nullable|numeric|min:0|max:100',
            'ComisionBomberos' => 'nullable|in:0,1',
            'PorcentajeBomberos' => 'nullable|numeric|min:0|max:100|required_if:ComisionBomberos,1',
        ], [
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'AgrupadorRamo.exists' => 'El agrupador seleccionado no es valido.',
            'PorcentajeComisionNoDeclarativa.numeric' => 'El porcentaje de comision debe ser numerico.',
            'PorcentajeComisionNoDeclarativa.min' => 'El porcentaje de comision no puede ser menor a 0.',
            'PorcentajeComisionNoDeclarativa.max' => 'El porcentaje de comision no puede ser mayor a 100.',
            'PorcentajeBomberos.numeric' => 'El porcentaje de bomberos debe ser numerico.',
            'PorcentajeBomberos.min' => 'El porcentaje de bomberos no puede ser menor a 0.',
            'PorcentajeBomberos.max' => 'El porcentaje de bomberos no puede ser mayor a 100.',
            'PorcentajeBomberos.required_if' => 'El porcentaje de bomberos es obligatorio cuando la comision de bomberos esta activa.',
        ]);

        $necesidad_proteccion = new NecesidadProteccion();
        $necesidad_proteccion->Nombre = $request->Nombre;
        $necesidad_proteccion->AgrupadorRamo = $request->AgrupadorRamo ?: null;
        $necesidad_proteccion->TipoPoliza = $request->TipoPoliza ?: null;
        $comisionBomberos = (int) ($request->ComisionBomberos ?? 0);
        $necesidad_proteccion->ComisionBomberos = $comisionBomberos;
        $necesidad_proteccion->PorcentajeComisionNoDeclarativa = $request->PorcentajeComisionNoDeclarativa !== null && $request->PorcentajeComisionNoDeclarativa !== '' ? $request->PorcentajeComisionNoDeclarativa : null;
        $necesidad_proteccion->PorcentajeBomberos = $comisionBomberos === 1
            ? ($request->PorcentajeBomberos !== null && $request->PorcentajeBomberos !== '' ? $request->PorcentajeBomberos : null)
            : null;
        $necesidad_proteccion->Activo = 1;
        $necesidad_proteccion->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/necesidad_proteccion/' . $necesidad_proteccion->Id . '/edit');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (session()->has('tab2')) {
            session(['tab1' => session('tab2')]);
            session(['tab2' => '1']);
        } else {
            session(['tab1' => '1']);
        }

        $necesidad_proteccion = NecesidadProteccion::findOrFail($id);
        $tipos_poliza = TipoPoliza::where('Activo', 1)->get();
        $agrupadores_ramo = AgrupadorRamo::where('Activo', 1)->orderBy('Nombre', 'asc')->get();
        $campos = NecesidadProteccionCampo::where('Activo', 1)
            ->where('NecesidadProteccion', $necesidad_proteccion->Id)
            ->orderBy('Id', 'asc')
            ->get();

        return view('catalogo.necesidad_proteccion.edit', compact('necesidad_proteccion', 'tipos_poliza', 'agrupadores_ramo', 'campos'));
    }

    public function update(NecesidadProteccionFormRequest $request, $id)
    {
        $request->validate([
            'Nombre' => 'required',
            'AgrupadorRamo' => 'nullable|exists:agrupador_ramo,Id',
            'PorcentajeComisionNoDeclarativa' => 'nullable|numeric|min:0|max:100',
            'ComisionBomberos' => 'nullable|in:0,1',
            'PorcentajeBomberos' => 'nullable|numeric|min:0|max:100|required_if:ComisionBomberos,1',
        ], [
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'AgrupadorRamo.exists' => 'El agrupador seleccionado no es valido.',
            'PorcentajeComisionNoDeclarativa.numeric' => 'El porcentaje de comision debe ser numerico.',
            'PorcentajeComisionNoDeclarativa.min' => 'El porcentaje de comision no puede ser menor a 0.',
            'PorcentajeComisionNoDeclarativa.max' => 'El porcentaje de comision no puede ser mayor a 100.',
            'PorcentajeBomberos.numeric' => 'El porcentaje de bomberos debe ser numerico.',
            'PorcentajeBomberos.min' => 'El porcentaje de bomberos no puede ser menor a 0.',
            'PorcentajeBomberos.max' => 'El porcentaje de bomberos no puede ser mayor a 100.',
            'PorcentajeBomberos.required_if' => 'El porcentaje de bomberos es obligatorio cuando la comision de bomberos esta activa.',
        ]);

        $necesidad_proteccion = NecesidadProteccion::findOrFail($id);
        $necesidad_proteccion->Nombre = $request->Nombre;
        $necesidad_proteccion->AgrupadorRamo = $request->AgrupadorRamo ?: null;
        $necesidad_proteccion->TipoPoliza = $request->TipoPoliza ?: null;
        $comisionBomberos = (int) ($request->ComisionBomberos ?? 0);
        $necesidad_proteccion->ComisionBomberos = $comisionBomberos;
        $necesidad_proteccion->PorcentajeComisionNoDeclarativa = $request->PorcentajeComisionNoDeclarativa !== null && $request->PorcentajeComisionNoDeclarativa !== '' ? $request->PorcentajeComisionNoDeclarativa : null;
        $necesidad_proteccion->PorcentajeBomberos = $comisionBomberos === 1
            ? ($request->PorcentajeBomberos !== null && $request->PorcentajeBomberos !== '' ? $request->PorcentajeBomberos : null)
            : null;
        $necesidad_proteccion->update();

        alert()->success('El registro ha sido modificado correctamente');
        session(['tab1' => '1']);
        return Redirect::to('catalogo/necesidad_proteccion/' . $necesidad_proteccion->Id . '/edit');
    }

    public function destroy($id)
    {
        NecesidadProteccion::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }

    public function add_campo(Request $request)
    {
        $request->validate([
            'NecesidadProteccion' => 'required|exists:necesidad_proteccion,Id',
            'Etiqueta' => 'required|string|max:150',
            'NombreCampo' => 'required|string|max:150',
            'TipoCampo' => 'required|in:' . implode(',', $this->tiposCampo()),
            'ValidacionCampo' => 'required|in:' . implode(',', $this->validacionesCampo()),
            'Requerido' => 'required|in:0,1',
            'Placeholder' => 'nullable|string|max:200',
        ]);

        $campo = new NecesidadProteccionCampo();
        $campo->NecesidadProteccion = $request->NecesidadProteccion;
        $campo->Etiqueta = $request->Etiqueta;
        $campo->NombreCampo = $request->NombreCampo;
        $campo->TipoCampo = $request->TipoCampo;
        $campo->ValidacionCampo = $request->ValidacionCampo;
        $campo->Requerido = (int) $request->Requerido;
        $campo->Placeholder = $request->Placeholder ?: null;
        $campo->Activo = 1;
        $campo->save();

        alert()->success('Campo dinamico creado correctamente');
        session(['tab2' => '2']);
        return back();
    }

    public function edit_campo(Request $request)
    {
        $request->validate([
            'Id' => 'required|exists:necesidad_proteccion_campos,Id',
            'Etiqueta' => 'required|string|max:150',
            'NombreCampo' => 'required|string|max:150',
            'TipoCampo' => 'required|in:' . implode(',', $this->tiposCampo()),
            'ValidacionCampo' => 'required|in:' . implode(',', $this->validacionesCampo()),
            'Requerido' => 'required|in:0,1',
            'Placeholder' => 'nullable|string|max:200',
        ]);

        $campo = NecesidadProteccionCampo::findOrFail($request->Id);
        $campo->Etiqueta = $request->Etiqueta;
        $campo->NombreCampo = $request->NombreCampo;
        $campo->TipoCampo = $request->TipoCampo;
        $campo->ValidacionCampo = $request->ValidacionCampo;
        $campo->Requerido = (int) $request->Requerido;
        $campo->Placeholder = $request->Placeholder ?: null;
        $campo->update();

        alert()->success('Campo dinamico actualizado correctamente');
        session(['tab2' => '2']);
        return back();
    }

    public function delete_campo(Request $request)
    {
        $request->validate([
            'Id' => 'required|exists:necesidad_proteccion_campos,Id',
        ]);

        NecesidadProteccionCampo::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('Campo dinamico eliminado correctamente');
        session(['tab2' => '2']);
        return back();
    }
}
