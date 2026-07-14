<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\VentasCampoComparativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class VentasCampoComparativoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $conteos = VentasCampoComparativo::where('Activo', 1)
            ->selectRaw('NecesidadProteccion, COUNT(*) as TotalConceptos')
            ->groupBy('NecesidadProteccion')
            ->pluck('TotalConceptos', 'NecesidadProteccion');

        $ramos = NecesidadProteccion::where('Activo', 1)
            ->orderBy('Nombre', 'asc')
            ->get();

        return view('catalogo.ventas_campo_comparativo.index', compact('ramos', 'conteos'));
    }

    public function ramo($id)
    {
        $ramo = NecesidadProteccion::findOrFail($id);

        $campos = VentasCampoComparativo::where('Activo', 1)
            ->where('NecesidadProteccion', $ramo->Id)
            ->orderBy('Orden', 'asc')
            ->orderBy('Id', 'asc')
            ->get();

        $ramos = collect([$ramo]);

        return view('catalogo.ventas_campo_comparativo.ramo', compact('ramo', 'campos', 'ramos'));
    }

    public function create()
    {
        return Redirect::to('catalogo/ventas_campo_comparativo');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        VentasCampoComparativo::create($data);

        alert()->success('El registro ha sido creado correctamente');
        return $this->redirectAfterSave($request);
    }

    public function show($id)
    {
        return Redirect::to('catalogo/ventas_campo_comparativo');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/ventas_campo_comparativo');
    }

    public function update(Request $request, $id)
    {
        $campo = VentasCampoComparativo::findOrFail($id);
        $campo->fill($this->validateData($request, $id));
        $campo->update();

        alert()->success('El registro ha sido modificado correctamente');
        return $this->redirectAfterSave($request, $campo->NecesidadProteccion);
    }

    public function destroy($id)
    {
        VentasCampoComparativo::findOrFail($id)->update(['Activo' => 0]);

        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }

    private function validateData(Request $request, $id = null): array
    {
        $request->validate([
            'NecesidadProteccion' => 'required|exists:necesidad_proteccion,Id',
            'Etiqueta' => 'required|string|max:150',
            'NombreInterno' => 'nullable|string|max:150',
            'Orden' => 'nullable|integer|min:1',
        ]);

        $nombreInterno = $request->NombreInterno ?: Str::slug($request->Etiqueta, '_');
        $nombreInterno = Str::lower($nombreInterno);

        return [
            'NecesidadProteccion' => $request->NecesidadProteccion,
            'Etiqueta' => mb_strtoupper($request->Etiqueta, 'UTF-8'),
            'NombreInterno' => $nombreInterno,
            'Orden' => $request->Orden ?: 1,
            'Activo' => 1,
        ];
    }

    private function redirectAfterSave(Request $request, $ramoId = null)
    {
        $ramoId = $request->ReturnRamo ?: $ramoId;

        if ($ramoId) {
            return Redirect::to('catalogo/ventas_campo_comparativo/ramo/' . $ramoId);
        }

        return Redirect::to('catalogo/ventas_campo_comparativo');
    }
}
