<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\SaldoMontos;
use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoTasaDiferenciada;
use App\Models\polizas\DesempleoTipoCartera;
use Illuminate\Http\Request;

class DesempleoTasaDiferenciadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $messages = [
            'Tasa.required' => 'El campo Tasa es obligatorio.',
            'Tasa.numeric' => 'El campo Tasa debe ser un número.',

            'FechaDesde.required' => 'La Fecha inicio es obligatoria cuando el tipo de cálculo es por periodo.',
            'FechaHasta.required' => 'La Fecha final es obligatoria cuando el tipo de cálculo es por periodo.',
            'FechaHasta.after_or_equal' => 'La Fecha final debe ser igual o posterior a la Fecha inicio.',

            'MontoDesde.required' => 'El Monto inicio es obligatorio cuando el tipo de cálculo es por monto.',
            'MontoHasta.required' => 'El Monto final es obligatorio cuando el tipo de cálculo es por monto.',
            'MontoHasta.gte' => 'El Monto final debe ser mayor o igual al Monto inicio.',
        ];

        // Siempre validar Tasa
        $request->validate([
            'Tasa' => 'required|numeric',
        ], $messages);

        // Validar por periodo (TipoCalculoIngreso == 1)
        if ($request->TipoCalculoIngreso == 1) {
            $request->validate([
                'FechaDesde' => 'required|date',
                'FechaHasta' => 'required|date|after_or_equal:FechaDesde',
            ], $messages);
        }

        // Validar por monto (TipoCalculoIngreso == 2)
        if ($request->TipoCalculoIngreso == 2) {
            $request->validate([
                'MontoDesde' => 'required|numeric',
                'MontoHasta' => 'required|numeric|gte:MontoDesde',
            ], $messages);
        }



        $desempleo_tipo_cartera = DesempleoTipoCartera::findOrFail($request->PolizaDesempleoTipoCartera);


        $tasa_diferenciada = new DesempleoTasaDiferenciada();
        $tasa_diferenciada->PolizaDesempleoTipoCartera = $request->PolizaDesempleoTipoCartera;

        if ($desempleo_tipo_cartera->TipoCalculo == 1) {
            if ($this->compColisionFechasDTasaDiferencial(null,  $desempleo_tipo_cartera->Id, $request->FechaDesde,  $request->FechaHasta)) {
                return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de fechas que desea registrar.']);
            }
            $tasa_diferenciada->FechaDesde = $request->FechaDesde;
            $tasa_diferenciada->FechaHasta = $request->FechaHasta;
        }

        if ($desempleo_tipo_cartera->TipoCalculo == 2) {
            if ($this->compColisionMontosDTasaDiferencial(null, $desempleo_tipo_cartera->Id, $request->MontoDesde,  $request->MontoHasta)) {
                return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de edad que desea registrar.']);
            }
            $tasa_diferenciada->MontoDesde = $request->MontoDesde;
            $tasa_diferenciada->MontoHasta = $request->MontoHasta;
        }

        $tasa_diferenciada->SaldosMontos = $request->SaldosMontos;
        $tasa_diferenciada->Tasa = $request->Tasa;
        $tasa_diferenciada->Usuario = auth()->user()->id;
        $tasa_diferenciada->save();

        alert()->success('El registro ha sido configurado correctamente');
        return back();
    }

    private function compColisionFechasDTasaDiferencial($PolizaDesempleoTasaDiferenciadaId, $tipoCarteraId, $fechaDesdeRequest, $fechaHastaRequest)
    {

        return DesempleoTasaDiferenciada::query()
            ->where('PolizaDesempleoTipoCartera', $tipoCarteraId)
            ->when($PolizaDesempleoTasaDiferenciadaId, function ($query) use ($PolizaDesempleoTasaDiferenciadaId) {
                $query->where('Id', '!=', $PolizaDesempleoTasaDiferenciadaId);
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

    private function compColisionMontosDTasaDiferencial($PolizaDesempleoTasaDiferenciadaId, $tipoCarteraId, $montoDesdeRequest, $montoHastaRequest)
    {
        // Verificar colisión de rangos
        return DesempleoTasaDiferenciada::query()
            ->where('PolizaDesempleoTipoCartera', $tipoCarteraId)
            // Condición opcional para excluir un ID (si se proporciona, de lo contrario se deja null)
            ->when($PolizaDesempleoTasaDiferenciadaId, function ($query) use ($PolizaDesempleoTasaDiferenciadaId) {
                $query->where('Id', '!=', $PolizaDesempleoTasaDiferenciadaId);
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
        $messages = [
            'TasaEdit.required' => 'El campo Tasa es obligatorio.',
            'TasaEdit.numeric' => 'El campo Tasa debe ser un número.',

            'FechaDesdeEdit.required' => 'La Fecha inicio es obligatoria cuando el tipo de cálculo es por periodo.',
            'FechaHastaEdit.required' => 'La Fecha final es obligatoria cuando el tipo de cálculo es por periodo.',
            'FechaHastaEdit.after_or_equal' => 'La Fecha final debe ser igual o posterior a la Fecha inicio.',

            'MontoDesdeEdit.required' => 'El Monto inicio es obligatorio cuando el tipo de cálculo es por monto.',
            'MontoHastaEdit.required' => 'El Monto final es obligatorio cuando el tipo de cálculo es por monto.',
            'MontoHastaEdit.gte' => 'El Monto final debe ser mayor o igual al Monto inicio.',
        ];

        // Siempre validar TasaEdit
        $request->validate([
            'TasaEdit' => 'required|numeric',
        ], $messages);

        // Validar por periodo (TipoCalculoIngreso == 1)
        if ($request->TipoCalculoIngreso == 1) {
            $request->validate([
                'FechaDesdeEdit' => 'required|date',
                'FechaHastaEdit' => 'required|date|after_or_equal:FechaDesdeEdit',
            ], $messages);
        }

        // Validar por monto (TipoCalculoIngreso == 2)
        if ($request->TipoCalculoIngreso == 2) {
            $request->validate([
                'MontoDesdeEdit' => 'required|numeric',
                'MontoHastaEdit' => 'required|numeric|gte:MontoDesdeEdit',
            ], $messages);
        }
        try {

            $poliza_actualizar = DesempleoTasaDiferenciada::findOrFail($id);

           //dd($poliza_actualizar->poliza_desempleo_tipo_cartera);
            if ($poliza_actualizar->poliza_desempleo_tipo_cartera->TipoCalculo == 1) {
                if ($this->compColisionFechasDTasaDiferencial($id, $poliza_actualizar->PolizaDesempleoTipoCartera, $request->FechaDesdeEdit,  $request->FechaHastaEdit)) {
                    return back()->withErrors(['error' => 'Ya existe una línea de crédito que cubre total o parcialmente el rango de fechas que desea registrar.']);
                }
                $poliza_actualizar->FechaDesde = $request->FechaDesdeEdit;
                $poliza_actualizar->FechaHasta = $request->FechaHastaEdit;
            }

            if ($poliza_actualizar->poliza_desempleo_tipo_cartera->TipoCalculo == 2) {
                if ($this->compColisionMontosDTasaDiferencial($id, $poliza_actualizar->PolizaDesempleoTipoCartera, $request->MontoDesdeEdit,  $request->MontoHastaEdit)) {
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $tasa = DesempleoTasaDiferenciada::findOrFail($request->TasaDiferenciadaId);
            $tasa->delete();
            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el registro.']);
        }
    }

    public function tasa_diferenciada($id)
    {
        $desempleo = Desempleo::findOrFail($id);
        $saldos_montos = SaldoMontos::get();
        return view('polizas.desempleo.tasa_diferenciada', compact('desempleo', 'saldos_montos'));
    }


    public function agregar_tipo_cartera(Request $request, $id)
    {
        // dd($request->all());
        try {

            $rules = [
                //'SaldosMontos' => 'required|integer',
                'TipoCalculo' => 'required|integer',
            ];

            // Mensajes personalizados
            $messages = [
                'SaldosMontos.required' => 'El campo saldo y montos es obligatorio.',
                'SaldosMontos.integer' => 'El campo saldo y montos debe ser un número entero.',
                'TipoCalculo.required' => 'El campo Tipo de Cálculo es obligatorio.',
                'TipoCalculo.integer' => 'El campo Tipo de Cálculo debe ser un número entero.'
            ];

            // Validar
            $request->validate($rules, $messages);



            // Crear y guardar el nuevo tipo de cartera
            $tipo_cartera = new DesempleoTipoCartera();
            $tipo_cartera->PolizaDesempleo = $id;
            //$tipo_cartera->SaldosMontos = $request->SaldosMontos;
            $tipo_cartera->TipoCalculo = $request->TipoCalculo;
            $tipo_cartera->save();

            return back()->with('success', 'Registro agregado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error: ' . $e->getMessage())->withInput();
        }
    }

    public function update_tipo_cartera(Request $request, $id)
    {
        $desempleo = Desempleo::findOrFail($id);

       try {
            // Reglas base
            $rules = [
                'TipoCartera' => 'required|numeric',
            ];



            // Mensajes personalizados
            $messages = [
                'TipoCartera.required' => 'El campo Tipo de Cálculo es requerido.',
                'TipoCartera.numeric' => 'Seleccione un tipo de cálculo válido.',
                'MontoMaximoIndividual.required' => 'El monto máximo individual es obligatorio.',
                'MontoMaximoIndividual.numeric' => 'El monto máximo individual debe ser un número.',
                'MontoMaximoIndividual.min' => 'El monto máximo individual debe ser mayor o igual a 0.',
            ];


            // Validación
            $request->validate($rules, $messages);

            $id = $request->DesempleoTipoCartera;

            // Buscar el registro
            $tipo_cartera = DesempleoTipoCartera::findOrFail($id);

            // Verificar duplicados (ignorando el actual)
            $count = DesempleoTipoCartera::where('SaldosMontos', $request->TipoCartera)
                ->where('PolizaDesempleo', $tipo_cartera->PolizaDesempleo)
                ->where('id', '<>', $id)
                ->count();

            if ($count > 0) {
                return back()->withErrors(['TipoCartera' => 'Ya existe un registro con este Tipo de Cálculo en la misma Póliza de Desempleo.']);
            }

            // Guardar cambios
            $tipo_cartera->SaldosMontos = $request->TipoCartera;

            $tipo_cartera->save();

            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el tipo de cartera.']);
        }
    }

    public function delete_tipo_cartera(Request $request)
    {
        try {
            $tipo_cartera = DesempleoTipoCartera::findOrFail($request->TipoCarteraId);
            if ($tipo_cartera->tasa_diferenciada->count() > 0) {
                return back()->withErrors(['Error' => 'Este registro cuenta con tasas diferenciadas']);
            }


            $tipo_cartera->delete();
            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el tipo de cartera.']);
        }
    }
}
