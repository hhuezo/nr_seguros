<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cobertura;
use App\Models\catalogo\Plan;
use App\Models\catalogo\PlanCoberturaDetalle;
use App\Models\catalogo\Producto;
use Illuminate\Http\Request;

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

        $productos = Producto::where('Activo', '=', 1)->get();

        return view('catalogo.plan.create', compact('productos'));
    }

    public function getCoberturas(Request $request)
    {
        $tarificacion = ["Millar", "Porcentual", "Prima"];
        $datosRecibidos = Cobertura::where('Activo', '=', 1)->where('Producto', '=', $request->ProductoId)->get();
        foreach ($datosRecibidos as $dato) {
            $indice = (int)$dato->Tarificacion;
            $dato->Tarificacion = $tarificacion[$indice] ?? 'Desconocido';
        }

        if ($datosRecibidos->count() > 0) {
            return response()->json(['datosRecibidos' => $datosRecibidos], 200);
        } else {
            return response()->json(['datosRecibidos' => $datosRecibidos], 404);
        }
    }

    public function store(Request $request)
    {
        $Plan = new Plan();
        $Plan->Nombre = $request->Nombre;
        $Plan->Producto = $request->Producto;
        $Plan->Activo = 1;
        $Plan->save();

        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/plan/' . $Plan->Id . '/edit');
        //return Redirect::to('catalogo/producto/create');
    }

    public function show($id)
    {
        //
    }
    public function get_producto($id)
    {
        return Producto::where('Aseguradora', '=', $id)->get();
    }

    public function get_plan($id)
    {
        return Plan::where('Producto', '=', $id)->where('Activo', 1)->get();
    }


    public function edit($id)
    {
        $plan = Plan::findOrFail($id);

        // Obtén las coberturas que ya están asociadas a este plan en detalle
        $coberturasEnDetalle = $plan->coberturas->pluck('Id');

        // Obtén todas las coberturas disponibles que NO están en detalle
        $coberturasDisponibles = Cobertura::where('Activo', '=', 1)->whereNotIn('id', $coberturasEnDetalle)->where('Producto', '=', $plan->productos->Id)->get();
        $productos = Producto::where('Activo', '=', 1)->get();
        return view('catalogo/plan/edit', compact(
            'plan',
            'coberturasDisponibles',
            'productos'
        ));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->Nombre = $request->Nombre;
        $plan->update();
        alert()->success('El registro ha sido modificado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras/' . $id . 'edit');
    }

    public function destroy($id)
    {
        Plan::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras');
    }

    public function edit_cobertura_detalle(Request $request)
    {

        PlanCoberturaDetalle::updateOrInsert(
            ['Plan' => $request->Plan, 'Cobertura' => $request->Cobertura], // Condiciones de búsqueda
            ['SumaAsegurada' => $request->SumaAsegurada, 'Tasa' => $request->Tasa, 'Prima' => $request->Prima, 'Activo' => '1'] // Datos a actualizar o crear
        );

        alert()->success('El registro ha sido modificado correctamente');

        return back();
    }
}
