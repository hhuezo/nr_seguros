<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeudaTasaDiferenciadaRequestV1;
use App\Http\Requests\DeudaTasaDiferenciadaRequestV2;
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



    public function store(DeudaTasaDiferenciadaRequestV1 $request)
    {

        $deuda_tipo_cartera = PolizaDeudaTipoCartera::findOrFail($request->PolizaDuedaTipoCartera);

        $tasa_diferenciada = new PolizaDeudaTasaDiferenciada();
        $tasa_diferenciada->PolizaDuedaTipoCartera = $request->PolizaDuedaTipoCartera;
        $tasa_diferenciada->LineaCredito = $request->LineaCredito;

        if ($deuda_tipo_cartera->TipoCalculo == 1) {
            if ($this->compColisionFechasDTasaDiferencial(null,  $deuda_tipo_cartera->Id, $request->FechaDesde,  $request->FechaHasta)) {
                return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de fechas que desea registrar.']);
            }
            $tasa_diferenciada->FechaDesde = $request->FechaDesde;
            $tasa_diferenciada->FechaHasta = $request->FechaHasta;
        }

        if ($deuda_tipo_cartera->TipoCalculo == 2) {
            if ($this->compColisionEdadesDTasaDiferencial(null, $deuda_tipo_cartera->Id, $request->EdadDesde,  $request->EdadHasta)) {
                return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de edad que desea registrar.']);
            }
            $tasa_diferenciada->EdadDesde = $request->EdadDesde;
            $tasa_diferenciada->EdadHasta = $request->EdadHasta;
        }

        $tasa_diferenciada->Tasa = $request->Tasa;
        $tasa_diferenciada->Usuario = auth()->user()->id;
        $tasa_diferenciada->save();

        alert()->success('El registro ha sido configurado correctamente');
        return back()->with('success', 'El registro ha sido creado correctamente.');
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

    public function update(DeudaTasaDiferenciadaRequestV2 $request, $id)
    {
        try {

            $poliza_actualizar = PolizaDeudaTasaDiferenciada::findOrFail($id);
            //dd($request);

            if ($poliza_actualizar->poliza_deuda_tipo_cartera->TipoCalculo == 1) {
                if ($this->compColisionFechasDTasaDiferencial($id, $poliza_actualizar->PolizaDuedaTipoCartera, $request->FechaDesdeEdit,  $request->FechaHastaEdit)) {
                    return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de fechas que desea registrar.']);
                }
                $poliza_actualizar->FechaDesde = $request->FechaDesdeEdit;
                $poliza_actualizar->FechaHasta = $request->FechaHastaEdit;
            }

            if ($poliza_actualizar->poliza_deuda_tipo_cartera->TipoCalculo == 2) {
                if ($this->compColisionEdadesDTasaDiferencial($id, $poliza_actualizar->PolizaDuedaTipoCartera, $request->EdadDesdeEdit,  $request->EdadHastaEdit)) {
                    return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de edad que desea registrar.']);
                }
                $poliza_actualizar->EdadDesde = $request->EdadDesdeEdit;
                $poliza_actualizar->EdadHasta = $request->EdadHastaEdit;
            }
            $poliza_actualizar->LineaCredito = $request->LineaCreditoEdit;
            $poliza_actualizar->Tasa = $request->TasaEdit;
            //$poliza_actualizar->Usuario = auth()->user()->id;
            $poliza_actualizar->update();

            return back()->with('success', 'El registro ha sido actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el registro.']);
        }
    }

    private function compColisionEdadesDTasaDiferencial($PolizaDeudaTasaDiferenciadaId, $tipoCarteraId, $edadDesdeRequest, $edadHastaRequest)
    {
        // Verificar colisión de rangos
        return PolizaDeudaTasaDiferenciada::query()
            ->where('PolizaDuedaTipoCartera', $tipoCarteraId)
            // Condición opcional para excluir un ID (si se proporciona, de lo contrario se deja null)
            ->when($PolizaDeudaTasaDiferenciadaId, function ($query) use ($PolizaDeudaTasaDiferenciadaId) {
                $query->where('Id', '!=', $PolizaDeudaTasaDiferenciadaId);
            })
            ->where(function ($query) use ($edadDesdeRequest, $edadHastaRequest) {
                $query->where(function ($q) use ($edadDesdeRequest, $edadHastaRequest) {
                    // Caso 1: Rango nuevo empieza dentro de un rango existente
                    $q->where('EdadDesde', '<=', $edadHastaRequest)
                        ->where('EdadHasta', '>=', $edadDesdeRequest);
                })->orWhere(function ($q) use ($edadDesdeRequest, $edadHastaRequest) {
                    // Caso 2: Rango existente está dentro del nuevo rango
                    $q->where('EdadDesde', '>=', $edadDesdeRequest)
                        ->where('EdadHasta', '<=', $edadHastaRequest);
                });
            })->exists();
    }

    private function compColisionFechasDTasaDiferencial($PolizaDeudaTasaDiferenciadaId, $tipoCarteraId, $fechaDesdeRequest, $fechaHastaRequest) {
        return PolizaDeudaTasaDiferenciada::query()
            ->where('PolizaDuedaTipoCartera', $tipoCarteraId)
            ->when($PolizaDeudaTasaDiferenciadaId, function ($query) use ($PolizaDeudaTasaDiferenciadaId) {
                $query->where('Id', '!=', $PolizaDeudaTasaDiferenciadaId);
            })
            ->where(function ($query) use ($fechaDesdeRequest, $fechaHastaRequest) {
                $query->where(function ($q) use ($fechaDesdeRequest, $fechaHastaRequest) {
                    // Caso 1: Rango nuevo se superpone con rangos existentes
                    $q->where('FechaDesde', '<=', $fechaHastaRequest)
                      ->where('FechaHasta', '>=', $fechaDesdeRequest);
                })->orWhere(function ($q) use ($fechaDesdeRequest, $fechaHastaRequest) {
                    // Caso 2: Rango existente está completamente dentro del nuevo rango
                    $q->where('FechaDesde', '>=', $fechaDesdeRequest)
                      ->where('FechaHasta', '<=', $fechaHastaRequest);
                });
            })
            ->exists();
    }
}
