<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cobertura;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;
        $planes = Plan::where('Activo', 1)->orderBy('Id', 'asc')->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $planes->search(function ($p) use ($idRegistro) {
                return $p->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('catalogo.plan.index', compact('planes', 'posicion'));
    }

    public function create()
    {
        $productos = Producto::where('Activo', 1)->orderBy('Nombre')->get();

        return view('catalogo.plan.create', compact('productos'));
    }

    public function getCoberturas(Request $request)
    {
        $datosRecibidos = Cobertura::with('tarificacion')
            ->where('Activo', 1)
            ->where('Producto', $request->ProductoId)
            ->get();

        foreach ($datosRecibidos as $dato) {
            $dato->TarificacionId = $dato->Tarificacion;
            $dato->TarificacionNombre = $dato->tarificacion->Nombre ?? '';
            $dato->Tarificacion = $dato->TarificacionNombre;
        }

        if ($datosRecibidos->count() > 0) {
            return response()->json(['datosRecibidos' => $datosRecibidos], 200);
        }

        return response()->json(['datosRecibidos' => $datosRecibidos], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Producto' => 'required|integer|exists:producto,Id',
        ]);

        $plan = new Plan();
        $plan->Nombre = $request->Nombre;
        $plan->Producto = $request->Producto;
        $plan->Activo = 1;
        $plan->save();

        alert()->success('El registro ha sido creado correctamente');

        return redirect('catalogo/plan/' . $plan->Id . '/edit');
    }

    public function show($id)
    {
        //
    }

    public function get_producto($id, $tipo)
    {
        $ramosId = NecesidadProteccion::where('TipoPoliza', $tipo)->pluck('Id');

        return Producto::whereIn('NecesidadProteccion', $ramosId)
            ->where('Aseguradora', $id)
            ->orderBy('Nombre')
            ->get();
    }

    public function get_plan($id)
    {
        return Plan::where('Producto', $id)->where('Activo', 1)->orderBy('Nombre')->get();
    }

    public function edit($id)
    {
        $plan = Plan::with(['productos', 'coberturas.tarificacion'])->findOrFail($id);

        // Coberturas ya copiadas desde el producto al plan.
        $coberturasEnDetalle = $plan->coberturas->pluck('Id');

        // Coberturas del producto que aun no se han configurado en este plan.
        $coberturasDisponibles = Cobertura::with('tarificacion')
            ->where('Activo', 1)
            ->whereNotIn('Id', $coberturasEnDetalle)
            ->where('Producto', $plan->productos->Id)
            ->orderBy('Nombre')
            ->get();

        $productos = Producto::where('Activo', 1)->orderBy('Nombre')->get();

        return view('catalogo/plan/edit', compact(
            'plan',
            'coberturasDisponibles',
            'productos'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Producto' => 'required|integer|exists:producto,Id',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->Nombre = $request->Nombre;
        $plan->Producto = $request->Producto;
        $plan->update();

        alert()->success('El registro ha sido modificado correctamente');

        return back();
    }

    public function destroy($id)
    {
        Plan::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');

        return back();
    }

    public function edit_cobertura_detalle(Request $request)
    {
        $request->validate([
            'Plan' => 'required|integer|exists:plan,Id',
            'Cobertura' => 'required|integer|exists:cobertura,Id',
            'SumaAsegurada' => 'nullable|numeric|min:0',
            'Tasa' => 'nullable|numeric|min:0',
            'Prima' => 'nullable|numeric|min:0',
            'CoberturaPrincipal' => 'nullable|boolean',
        ]);

        $plan = Plan::findOrFail($request->Plan);
        $cobertura = Cobertura::with('tarificacion')->where('Activo', 1)->findOrFail($request->Cobertura);

        if ((int) $cobertura->Producto !== (int) $plan->Producto) {
            return back()
                ->withErrors(['Cobertura' => 'La cobertura seleccionada no pertenece al producto del plan.'])
                ->withInput();
        }

        $tarificacionNombre = $cobertura->tarificacion->Nombre ?? '';
        $valores = $this->valoresCoberturaPlanPorTarificacion($request, $tarificacionNombre);

        DB::transaction(function () use ($request, $cobertura, $tarificacionNombre, $valores) {
            $esPrincipal = $request->boolean('CoberturaPrincipal');

            if ($esPrincipal) {
                DB::table('plan_cobertura_detalle')
                    ->where('Plan', $request->Plan)
                    ->update(['CoberturaPrincipal' => 0]);
            }

            DB::table('plan_cobertura_detalle')->updateOrInsert(
                ['Plan' => $request->Plan, 'Cobertura' => $request->Cobertura],
                [
                    'Tarificacion' => $cobertura->Tarificacion,
                    'TarificacionNombre' => $tarificacionNombre,
                    'SumaAsegurada' => $valores['SumaAsegurada'],
                    'Tasa' => $valores['Tasa'],
                    'Prima' => $valores['Prima'],
                    'CoberturaPrincipal' => $esPrincipal ? 1 : 0,
                    'Activo' => 1,
                ]
            );
        });

        alert()->success('El registro ha sido modificado correctamente');

        return back();
    }

    private function valoresCoberturaPlanPorTarificacion(Request $request, string $tarificacionNombre): array
    {
        $tipo = strtolower($tarificacionNombre);

        if (str_contains($tipo, 'sin cobro')) {
            return ['SumaAsegurada' => 0, 'Tasa' => 0, 'Prima' => 0];
        }

        if (str_contains($tipo, 'prima')) {
            $request->validate(['Prima' => 'required|numeric|min:0']);

            return ['SumaAsegurada' => 0, 'Tasa' => 0, 'Prima' => $request->Prima];
        }

        if (str_contains($tipo, 'millar') || str_contains($tipo, 'porcentual')) {
            $request->validate([
                'SumaAsegurada' => 'required|numeric|min:0',
                'Tasa' => 'required|numeric|min:0',
            ]);

            return ['SumaAsegurada' => $request->SumaAsegurada, 'Tasa' => $request->Tasa, 'Prima' => 0];
        }

        return [
            'SumaAsegurada' => $request->SumaAsegurada ?? 0,
            'Tasa' => $request->Tasa ?? 0,
            'Prima' => $request->Prima ?? 0,
        ];
    }
}
