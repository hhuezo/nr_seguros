<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Http\Requests\VidaTasaDiferenciadaRequestV1;
use App\Http\Requests\VidaTasaDiferenciadaRequestV2;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaCatalogoTipoCartera;
use App\Models\polizas\VidaTasaDiferenciada;
use App\Models\polizas\VidaTipoCartera;
use Illuminate\Http\Request;

class VidaTasaDiferenciadaController extends Controller
{
    public function show($id)
    {
        $vida = Vida::find($id);
       // dd($vida->vida_tipos_cartera);
        $tiposCartera = VidaCatalogoTipoCartera::where('Activo',1)->get();
        //dd($tiposCartera );
        return view('polizas.vida.tasa_diferenciada', compact('vida', 'tiposCartera'));
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
            $existe = VidaTipoCartera::where('PolizaVida', $id)
                ->where('VidaTipoCartera', $request->TipoCartera)
                ->exists();

            if ($existe) {
                return redirect()->back()->withErrors(['error' => 'Este registro ya existe los registros.'])->withInput();
            }


            // Crear y guardar el nuevo tipo de cartera
            $tipo_cartera = new VidaTipoCartera();
            $tipo_cartera->PolizaVida = $id;
            $tipo_cartera->VidaTipoCartera = $request->TipoCartera;
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
            $tipo_cartera = VidaTipoCartera::findOrFail($request->TipoCarteraId);
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
            $tipo_cartera = VidaTipoCartera::findOrFail($id);

            $count = VidaTipoCartera::where('VidaTipoCartera', $request->TipoCartera)->where('PolizaVida', $tipo_cartera->PolizaVida)->count();

            // Verificar si ya existe otro registro con el mismo TipoCartera y PolizaVida
            $count = VidaTipoCartera::where('VidaTipoCartera', $request->TipoCartera)
                ->where('PolizaVida', $tipo_cartera->PolizaVida)
                ->where('id', '<>', $id) // Ignorar el registro actual
                ->count();

            if ($count > 0) {
                return back()->withErrors(['TipoCartera' => 'Ya existe un registro con este Tipo de Cálculo en la misma Póliza de Vida.']);
            }



            $tipo_cartera->VidaTipoCartera = $request->TipoCartera;
            $tipo_cartera->MontoMaximoIndividual = $request->MontoMaximoIndividual;
            $tipo_cartera->save();

            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el tipo de cartera.']);
        }
    }



    public function store(VidaTasaDiferenciadaRequestV1 $request)
    {

        $vida_tipo_cartera = VidaTipoCartera::findOrFail($request->PolizaVidaTipoCartera);

        $tasa_diferenciada = new VidaTasaDiferenciada();
        $tasa_diferenciada->PolizaVidaTipoCartera = $request->PolizaVidaTipoCartera;

        if ($vida_tipo_cartera->TipoCalculo == 1) {
            if ($this->compColisionFechasDTasaDiferencial(null,  $vida_tipo_cartera->Id, $request->FechaDesde,  $request->FechaHasta)) {
                return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de fechas que desea registrar.']);
            }
            $tasa_diferenciada->FechaDesde = $request->FechaDesde;
            $tasa_diferenciada->FechaHasta = $request->FechaHasta;
        }

        if ($vida_tipo_cartera->TipoCalculo == 2) {
            if ($this->compColisionMontosDTasaDiferencial(null, $vida_tipo_cartera->Id, $request->MontoDesde,  $request->MontoHasta)) {
                return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de edad que desea registrar.']);
            }
            $tasa_diferenciada->MontoDesde = $request->MontoDesde;
            $tasa_diferenciada->MontoHasta = $request->MontoHasta;
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
            $tasa = VidaTasaDiferenciada::findOrFail($request->TasaDiferenciadaId);
            $tasa->delete();
            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el registro.']);
        }
    }

    public function update(VidaTasaDiferenciadaRequestV2 $request, $id)
    {
        try {

            $poliza_actualizar = VidaTasaDiferenciada::findOrFail($id);
           // dd($poliza_actualizar);

            if ($poliza_actualizar->poliza_vida_tipo_cartera->TipoCalculo == 1) {
                if ($this->compColisionFechasDTasaDiferencial($id, $poliza_actualizar->PolizaVidaTipoCartera, $request->FechaDesdeEdit,  $request->FechaHastaEdit)) {
                    return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de fechas que desea registrar.']);
                }
                $poliza_actualizar->FechaDesde = $request->FechaDesdeEdit;
                $poliza_actualizar->FechaHasta = $request->FechaHastaEdit;
            }

            if ($poliza_actualizar->poliza_vida_tipo_cartera->TipoCalculo == 2) {
                if ($this->compColisionMontosDTasaDiferencial($id, $poliza_actualizar->PolizaVidaTipoCartera, $request->MontoDesdeEdit,  $request->MontoHastaEdit)) {
                    return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de edad que desea registrar.']);
                }
                $poliza_actualizar->MontoDesde = $request->MontoDesdeEdit;
                $poliza_actualizar->MontoHasta = $request->MontoHastaEdit;
            }
            $poliza_actualizar->Tasa = $request->TasaEdit;
            //$poliza_actualizar->Usuario = auth()->user()->id;
            $poliza_actualizar->update();

            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el registro.']);
        }
    }

    private function compColisionMontosDTasaDiferencial($PolizaVidaTasaDiferenciadaId, $tipoCarteraId, $montoDesdeRequest, $montoHastaRequest)
    {
        // Verificar colisión de rangos
        return VidaTasaDiferenciada::query()
            ->where('PolizaVidaTipoCartera', $tipoCarteraId)
            // Condición opcional para excluir un ID (si se proporciona, de lo contrario se deja null)
            ->when($PolizaVidaTasaDiferenciadaId, function ($query) use ($PolizaVidaTasaDiferenciadaId) {
                $query->where('Id', '!=', $PolizaVidaTasaDiferenciadaId);
            })
            ->where(function ($query) use ($montoDesdeRequest, $montoHastaRequest) {
                $query->where(function ($q) use ($montoDesdeRequest, $montoHastaRequest) {
                    // Caso 1: Rango nuevo empieza dentro de un rango existente
                    $q->where('MontoDesde', '<=', $montoHastaRequest)
                        ->where('MontoHasta', '>=', $montoDesdeRequest);
                })->orWhere(function ($q) use ($montoDesdeRequest, $montoHastaRequest) {
                    // Caso 2: Rango existente está dentro del nuevo rango
                    $q->where('MontoDesde', '>=', $montoDesdeRequest)
                        ->where('MontoHasta', '<=', $montoHastaRequest);
                });
            })->exists();
    }

    private function compColisionFechasDTasaDiferencial($PolizaVidaTasaDiferenciadaId, $tipoCarteraId, $fechaDesdeRequest, $fechaHastaRequest) {
        return VidaTasaDiferenciada::query()
            ->where('PolizaVidaTipoCartera', $tipoCarteraId)
            ->when($PolizaVidaTasaDiferenciadaId, function ($query) use ($PolizaVidaTasaDiferenciadaId) {
                $query->where('Id', '!=', $PolizaVidaTasaDiferenciadaId);
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
