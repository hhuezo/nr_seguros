<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\VentasCampoComparativo;
use App\Models\catalogo\VentasPlanComercial;
use App\Models\catalogo\VentasPlanComercialValor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class VentasPlanComercialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $planesComerciales = VentasPlanComercial::with(['aseguradora', 'ramo', 'producto', 'plan', 'valores'])
            ->where('Activo', 1)
            ->orderBy('NecesidadProteccion', 'asc')
            ->orderBy('NombreComercial', 'asc')
            ->get();

        $catalogos = $this->catalogos();
        $productosComerciales = $catalogos['productos']->map(function ($producto) {
            return [
                'Id' => $producto->Id,
                'Nombre' => $producto->Nombre,
                'Aseguradora' => $producto->Aseguradora,
                'NecesidadProteccion' => $producto->NecesidadProteccion,
            ];
        })->values();
        $planesTecnicos = $catalogos['planes']->map(function ($plan) {
            return [
                'Id' => $plan->Id,
                'Nombre' => $plan->Nombre,
                'Producto' => $plan->Producto,
            ];
        })->values();

        return view('catalogo.ventas_plan_comercial.index', array_merge($catalogos, compact(
            'planesComerciales',
            'productosComerciales',
            'planesTecnicos'
        )));
    }

    public function create()
    {
        return Redirect::to('catalogo/ventas_plan_comercial');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        VentasPlanComercial::create($data);

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/ventas_plan_comercial');
    }

    public function show($id)
    {
        return Redirect::to('catalogo/ventas_plan_comercial');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/ventas_plan_comercial');
    }

    public function update(Request $request, $id)
    {
        $planComercial = VentasPlanComercial::findOrFail($id);
        $data = $this->validateData($request, $id);

        if ($planComercial->NecesidadProteccion != $data['NecesidadProteccion']) {
            VentasPlanComercialValor::where('PlanComercial', $planComercial->Id)->update(['Activo' => 0]);
        }

        $planComercial->fill($data);
        $planComercial->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/ventas_plan_comercial');
    }

    public function destroy($id)
    {
        VentasPlanComercial::findOrFail($id)->update(['Activo' => 0]);

        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }

    public function valores($id)
    {
        $planComercial = VentasPlanComercial::with(['aseguradora', 'ramo', 'producto', 'plan'])
            ->findOrFail($id);

        $campos = VentasCampoComparativo::where('Activo', 1)
            ->where('NecesidadProteccion', $planComercial->NecesidadProteccion)
            ->orderBy('Orden', 'asc')
            ->orderBy('Id', 'asc')
            ->get();

        $valores = VentasPlanComercialValor::where('Activo', 1)
            ->where('PlanComercial', $planComercial->Id)
            ->get()
            ->keyBy('CampoComparativo');

        return view('catalogo.ventas_plan_comercial.valores', compact('planComercial', 'campos', 'valores'));
    }

    public function valoresSave(Request $request, $id)
    {
        $planComercial = VentasPlanComercial::findOrFail($id);
        $valores = $request->input('Valores', []);

        foreach ($valores as $campoId => $valorTexto) {
            $campo = VentasCampoComparativo::where('Activo', 1)
                ->where('NecesidadProteccion', $planComercial->NecesidadProteccion)
                ->where('Id', $campoId)
                ->first();

            if (!$campo) {
                continue;
            }

            $valor = VentasPlanComercialValor::where('PlanComercial', $planComercial->Id)
                ->where('CampoComparativo', $campo->Id)
                ->first();

            if (!$valor) {
                $valor = new VentasPlanComercialValor();
                $valor->PlanComercial = $planComercial->Id;
                $valor->CampoComparativo = $campo->Id;
            }

            $valor->ValorTexto = $valorTexto !== null && $valorTexto !== ''
                ? mb_strtoupper($valorTexto, 'UTF-8')
                : null;
            $valor->Activo = 1;
            $valor->save();
        }

        alert()->success('Las especificaciones se guardaron correctamente');
        return Redirect::to('catalogo/ventas_plan_comercial/' . $planComercial->Id . '/valores');
    }

    private function validateData(Request $request, $id = null): array
    {
        $request->validate([
            'Aseguradora' => 'required|exists:aseguradora,Id',
            'NecesidadProteccion' => 'required|exists:necesidad_proteccion,Id',
            'Producto' => 'required|exists:producto,Id',
            'Plan' => 'required|exists:plan,Id',
            'NombreComercial' => 'required|string|max:200',
        ]);

        $producto = Producto::where('Activo', 1)
            ->where('Id', $request->Producto)
            ->where('Aseguradora', $request->Aseguradora)
            ->where('NecesidadProteccion', $request->NecesidadProteccion)
            ->first();

        if (!$producto) {
            throw ValidationException::withMessages([
                'Producto' => 'El producto no pertenece a la aseguradora y ramo seleccionados.',
            ]);
        }

        $plan = Plan::where('Activo', 1)
            ->where('Id', $request->Plan)
            ->where('Producto', $request->Producto)
            ->first();

        if (!$plan) {
            throw ValidationException::withMessages([
                'Plan' => 'El plan no pertenece al producto seleccionado.',
            ]);
        }

        $duplicado = VentasPlanComercial::where('Activo', 1)
            ->where('Aseguradora', $request->Aseguradora)
            ->where('NecesidadProteccion', $request->NecesidadProteccion)
            ->where('Producto', $request->Producto)
            ->where('Plan', $request->Plan)
            ->where('NombreComercial', mb_strtoupper($request->NombreComercial, 'UTF-8'))
            ->when($id, function ($query) use ($id) {
                $query->where('Id', '<>', $id);
            })
            ->first();

        if ($duplicado) {
            throw ValidationException::withMessages([
                'NombreComercial' => 'Ya existe un plan comercial activo con esta combinacion.',
            ]);
        }

        return [
            'Aseguradora' => $request->Aseguradora,
            'NecesidadProteccion' => $request->NecesidadProteccion,
            'Producto' => $request->Producto,
            'Plan' => $request->Plan,
            'NombreComercial' => mb_strtoupper($request->NombreComercial, 'UTF-8'),
            'Activo' => 1,
        ];
    }

    private function catalogos(): array
    {
        return [
            'aseguradoras' => Aseguradora::where('Activo', 1)->orderBy('Nombre', 'asc')->get(),
            'ramos' => NecesidadProteccion::where('Activo', 1)->orderBy('Nombre', 'asc')->get(),
            'productos' => Producto::where('Activo', 1)->orderBy('Nombre', 'asc')->get(),
            'planes' => Plan::where('Activo', 1)->orderBy('Nombre', 'asc')->get(),
        ];
    }
}
