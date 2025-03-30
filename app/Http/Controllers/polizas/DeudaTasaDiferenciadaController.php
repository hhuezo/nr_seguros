<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\SaldoMontos;
use App\Models\catalogo\TipoCartera;
use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeudaTasaDiferenciada;
use App\Models\polizas\PolizaDeudaTipoCartera;
use Illuminate\Http\Request;

class DeudaTasaDiferenciadaController extends Controller
{

    public function show($id)
    {
        $deuda = Deuda::find($id);
        $tiposCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get();
        $lineas_credito = SaldoMontos::where('Activo', 1)->get();

        return view('polizas.deuda.tasa_diferenciada', compact('deuda', 'tiposCartera', 'lineas_credito'));
    }


    public function agregar_tipo_cartera(Request $request, $id)
    {
        try {
            // Validar los datos del request
            $request->validate([
                'TipoCartera' => 'required|integer',
                'TipoCalculo' => 'required|integer',
                'MontoMaximoIndividual' => 'required|integer',
            ], [
                'TipoCartera.required' => 'El campo Tipo de Cartera es obligatorio.',
                'TipoCartera.integer' => 'El campo Tipo de Cartera es obligatorio.',
                'TipoCalculo.required' => 'El campo Tipo de Cálculo es obligatorio.',
                'TipoCalculo.integer' => 'El campo Tipo de Cálculo es obligatorio.',
                'MontoMaximoIndividual.required' => 'El monto máximo individual es obligatorio.',
                'MontoMaximoIndividual.integer' => 'El monto máximo individual es obligatorio.',
            ]);


            // Verificar si los datos ya existen en la base de datos
            $existe = PolizaDeudaTipoCartera::where('PolizaDeuda', $id)
                ->where('TipoCartera', $request->TipoCartera)
                ->exists();

            if ($existe) {
                return redirect()->back()->withErrors(['error' => 'Este registro ya existe los registros.'])->withInput();
            }


            // Crear y guardar el nuevo tipo de cartera
            $tipo_cartera = new PolizaDeudaTipoCartera();
            $tipo_cartera->PolizaDeuda = $id;
            $tipo_cartera->TipoCartera = $request->TipoCartera;
            $tipo_cartera->TipoCalculo = $request->TipoCalculo;
            $tipo_cartera->MontoMaximoIndividual = $request->MontoMaximoIndividual;
            $tipo_cartera->save();

            return back()->with('success', 'Tipo de cartera agregado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error: ' . $e->getMessage())->withInput();
        }
    }

    public function delete_tipo_cartera(Request $request)
    {
        try {
            $tipo_cartera = PolizaDeudaTipoCartera::findOrFail($request->TipoCarteraId);
            if ($tipo_cartera->tasa_diferenciada->count() > 0) {
                return back()->withErrors(['Error' => 'Este registro cuenta con tasas diferenciadas']);
            }


            $tipo_cartera->delete();
            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el tipo de cartera.']);
        }
    }

    public function update_tipo_cartera(Request $request, $id)
    {
        try {
            // Validación dinámica según el valor de TipoCalculo
            $rules = [
                'TipoCartera' => 'required|numeric',
                'MontoMaximoIndividual' => 'required|numeric|min:0',
            ];

            $messages = [
                'TipoCartera.required' => 'El campo Tipo de Cálculo es requerido.',
                'TipoCartera.numeric' => 'Seleccione un tipo de cálculo válido.',
                'MontoMaximoIndividual.required' => 'El monto máximo individual es obligatorio.',
                'MontoMaximoIndividual.numeric' => 'El monto máximo individual debe ser un número.',
                'MontoMaximoIndividual.min' => 'El monto máximo individual debe ser mayor o igual a 0.',
            ];

            $request->validate($rules, $messages);

            // Buscar el registro
            $tipo_cartera = PolizaDeudaTipoCartera::findOrFail($id);

            $count = PolizaDeudaTipoCartera::where('TipoCartera', $request->TipoCartera)->where('PolizaDeuda', $tipo_cartera->PolizaDeuda)->count();

            // Verificar si ya existe otro registro con el mismo TipoCartera y PolizaDeuda
            $count = PolizaDeudaTipoCartera::where('TipoCartera', $request->TipoCartera)
                ->where('PolizaDeuda', $tipo_cartera->PolizaDeuda)
                ->where('id', '<>', $id) // Ignorar el registro actual
                ->count();

            if ($count > 0) {
                return back()->withErrors(['TipoCartera' => 'Ya existe un registro con este Tipo de Cálculo en la misma Póliza de Deuda.']);
            }



            $tipo_cartera->TipoCartera = $request->TipoCartera;
            $tipo_cartera->MontoMaximoIndividual = $request->MontoMaximoIndividual;
            $tipo_cartera->save();

            return back()->with('success', 'El registro ha sido configurado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el tipo de cartera.']);
        }
    }



    public function store(Request $request)
    {

        // Validación dinámica según el valor de TipoCalculo
        $rules = [
            'PolizaDuedaTipoCartera' => 'required|numeric', // Debe ser 1 o 2
            'Tasa' => 'required|numeric|min:0', // Siempre requerido
            'LineaCredito' => 'required|integer',
        ];

        $deuda_tipo_cartera = PolizaDeudaTipoCartera::findOrFail($request->PolizaDuedaTipoCartera);

        if ($deuda_tipo_cartera->TipoCalculo == 1) {
            // Si es 1, FechaDesde y FechaHasta son requeridos
            $rules['FechaDesde'] = 'required|date';
            $rules['FechaHasta'] = 'required|date|after_or_equal:FechaDesde';
        }

        if ($deuda_tipo_cartera->TipoCalculo == 2) {
            // Si es 2, EdadDesde y EdadHasta son requeridos
            $rules['EdadDesde'] = 'required|integer|min:0';
            $rules['EdadHasta'] = 'required|integer|gte:EdadDesde';
        }

        $messages = [
            'PolizaDuedaTipoCartera.required' => 'No se encontro el tipo de cartera asociado.',
            'PolizaDuedaTipoCartera.numeric' => 'No se encontro el tipo de cartera asociado.',
            'FechaDesde.required' => 'La fecha de inicio es obligatoria.',
            'FechaHasta.required' => 'La fecha final es obligatoria.',
            'FechaHasta.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'EdadDesde.required' => 'La edad de inicio es obligatoria.',
            'EdadHasta.required' => 'La edad final es obligatoria.',
            'EdadHasta.min' => 'La edad final debe ser mayor o igual a la edad de inicio.',
            'Tasa.required' => 'La tasa es obligatoria.',
            'Tasa.numeric' => 'La tasa debe ser un número.',
            'Tasa.min' => 'La tasa debe ser mayor o igual a 0.',
        ];

        $request->validate($rules, $messages);

        $tasa_diferenciada = new PolizaDeudaTasaDiferenciada();
        $tasa_diferenciada->PolizaDuedaTipoCartera = $request->PolizaDuedaTipoCartera;
        $tasa_diferenciada->LineaCredito = $request->LineaCredito;

        if ($deuda_tipo_cartera->TipoCalculo == 1) {
            $tasa_diferenciada->FechaDesde = $request->FechaDesde;
            $tasa_diferenciada->FechaHasta = $request->FechaHasta;
        } else {
            $tasa_diferenciada->FechaDesde = null;
            $tasa_diferenciada->FechaHasta = null;
        }

        if ($deuda_tipo_cartera->TipoCalculo == 2) {
            $tasa_diferenciada->EdadDesde = $request->EdadDesde;
            $tasa_diferenciada->EdadHasta = $request->EdadHasta;
        } else {
            $tasa_diferenciada->EdadDesde = null;
            $tasa_diferenciada->EdadHasta = null;
        }

        $tasa_diferenciada->Tasa = $request->Tasa;
        $tasa_diferenciada->Usuario = auth()->user()->id;
        $tasa_diferenciada->save();

        alert()->success('El registro ha sido configurado correctamente');
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $tasa = PolizaDeudaTasaDiferenciada::findOrFail($request->TasaDiferenciadaId);
            $tasa->delete();
            return back()->with('success', 'El registro ha sido eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el registro.']);
        }
    }
}
