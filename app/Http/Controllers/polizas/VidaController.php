<?php

namespace App\Http\Controllers\polizas;

use App\Exports\vida\EdadMaximaExport;
use App\Exports\vida\EdadInscripcionExport;
use App\Exports\vida\EdadTerminacionExport;
use App\Exports\vida\ExtraPrimadosExcluidosExport;
use App\Exports\vida\NuevosRegistrosExport;
use App\Exports\vida\RegistrosEliminadosExport;
use App\Exports\vida\RegistrosRehabilitadosExport;
use App\Exports\vida\VidaExport;
use App\Exports\vida\VidaFedeExport;
use App\Http\Controllers\Controller;
use App\Imports\VidaCarteraTempImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ConfiguracionRecibo;
use App\Models\catalogo\DatosGenerales;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Perfil;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\TipoCobro;
use App\Models\polizas\Comentario;
use App\Models\polizas\PolizaVidaExtraPrimados;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaCartera;
use App\Models\polizas\VidaDetalle;
use App\Models\polizas\VidaHistorialRecibo;
use App\Models\polizas\VidaTasaDiferenciada;
use App\Models\polizas\VidaTipoCartera;
use App\Models\polizas\VidaUsuario;
use App\Models\temp\VidaCarteraTemp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VidaController extends Controller
{
    public function index()
    {
        $vida = Vida::all();
        return view('polizas.vida.index', compact('vida'));
    }


    public function create()
    {
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.vida.create', compact(
            'aseguradora',
            'cliente',
            'tipoCobro',
            'ejecutivo',
            'productos',
            'planes',
        ));
    }

    public function get_cliente(Request $request)
    {
        $cliente = Cliente::findOrFail($request->Cliente);
        return $cliente;
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'NumeroPoliza' => 'required|unique:poliza_vida,NumeroPoliza',
                'Aseguradora' => 'required|exists:aseguradora,Id',
                'Productos' => 'required|exists:producto,Id',
                'Planes' => 'required|exists:plan,Id',
                'Asegurado' => 'required|exists:cliente,Id',
                'Ejecutivo' => 'required|exists:ejecutivo,Id',
                'TipoCobro' => 'required|exists:tipo_cobro,Id',
                'VigenciaDesde' => 'required|date',
                'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
                'EdadMaximaInscripcion' => 'required|numeric|min:18|max:100',
                'EdadTerminacion' => 'required|numeric|min:18|max:100|gte:EdadMaximaInscripcion',
                'Tasa' => 'required|numeric|min:0',
                'TasaDescuento' => 'nullable|numeric|min:0|max:100',
                'Concepto' => 'nullable|string|max:500'
            ];

            // Reglas condicionales
            if ($request->TipoCobro == 1) {
                $rules['SumaMinima'] = 'required|numeric|min:0.01';
                $rules['SumaMaxima'] = 'required|numeric|gt:SumaMinima';
            }


            if ($request->TipoCobro == 2 && $request->TipoTarifa == 1) {
                $rules['SumaAsegurada'] = 'required|numeric|min:0.01';
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 2) {
                $rules['Multitarifa'] = [
                    'required',
                    'string',
                    'regex:/^(\d+(\.\d{1,2})?)(,\d+(\.\d{1,2})?)*$/'
                ];
            }

            $messages = [
                'NumeroPoliza.required' => 'El número de póliza es obligatorio',
                'NumeroPoliza.unique' => 'Este número de póliza ya existe',
                'Aseguradora.required' => 'Seleccione una aseguradora',
                'Productos.required' => 'Seleccione un producto',
                'Planes.required' => 'Seleccione un plan',
                'Asegurado.required' => 'Seleccione un asegurado',
                'Ejecutivo.required' => 'Seleccione un ejecutivo',
                'VigenciaHasta.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
                'EdadTerminacion.gte' => 'La edad de terminación debe ser mayor o igual a la edad máxima de inscripción',
                'SumaAsegurada.min' => 'La suma asegurada mínima es 0.01',
                'Multitarifa.required' => 'El campo multitarifa es obligatorio',
                'Multitarifa.regex' => 'El formato de multitarifa es inválido. Use números separados por coma, con hasta 2 decimales.',


                // Mensajes para SumaMinima y SumaMaxima
                'SumaMinima.required' => 'La suma mínima es obligatoria',
                'SumaMinima.numeric' => 'La suma mínima debe ser un valor numérico',
                'SumaMinima.min' => 'La suma mínima debe ser al menos 0.01',
                'SumaMaxima.required' => 'La suma máxima es obligatoria',
                'SumaMaxima.numeric' => 'La suma máxima debe ser un valor numérico',
                'SumaMaxima.gt' => 'La suma máxima debe ser mayor que la suma mínima',
            ];

            // Validación
            $validatedData = Validator::make($request->all(), $rules, $messages)->validate();



            DB::beginTransaction();

            $vida = new Vida();
            $vida->NumeroPoliza = $request->NumeroPoliza;
            $vida->Nit = $request->Nit ?? null;
            $vida->Aseguradora = $request->Aseguradora;
            $vida->Producto = $request->Productos;
            $vida->Plan = $request->Planes;
            $vida->Asegurado = $request->Asegurado;
            $vida->VigenciaDesde = $request->VigenciaDesde;
            $vida->VigenciaHasta = $request->VigenciaHasta;
            $vida->Concepto = $request->Concepto ?? null;
            $vida->Ejecutivo = $request->Ejecutivo;
            $vida->TipoCobro = $request->TipoCobro;
            $vida->TipoTarifa = $request->TipoTarifa ?? null;
            $vida->Tasa = $request->Tasa;
            $vida->TasaDescuento = $request->TasaDescuento ?? null;
            $vida->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $vida->EdadTerminacion = $request->EdadTerminacion;
            $vida->EstadoPoliza = 1;
            $vida->Activo = 1;


            if ($request->TipoCobro == 1) {
                $vida->SumaMinima = $request->SumaMinima ?? null;
                $vida->SumaMaxima = $request->SumaMaxima ?? null;
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 1) {
                $vida->SumaAsegurada = $request->SumaAsegurada ?? null;
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 2) {
                $vida->Multitarifa = $request->Multitarifa ?? null;
            }

            if ($request->TarifaExcel == 'on') {
                $vida->TarifaExcel = 1;
            } else {
                $vida->TarifaExcel = 0;
            }


            $vida->save();

            DB::commit();

            return redirect('polizas/vida/' . $vida->Id . '/edit?tab=1')
                ->with('success', 'El registro ha sido creado correctamente');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear póliza: ' . $e->getMessage());

            $validator = Validator::make([], []);
            $validator->errors()->add('general', 'Ocurrió un error: ' . $e->getMessage());

            return back()
                ->withErrors($validator)
                ->withInput();
        }
    }


    public function validar_store(Request $request)
    {
        try {
            $rules = [
                'NumeroPoliza' => 'required|unique:poliza_vida,NumeroPoliza',
                'Aseguradora' => 'required|exists:aseguradora,Id',
                'Productos' => 'required|exists:producto,Id',
                'Planes' => 'required|exists:plan,Id',
                'Asegurado' => 'required|exists:cliente,Id',
                'Ejecutivo' => 'required|exists:ejecutivo,Id',
                'TipoCobro' => 'required|exists:tipo_cobro,Id',
                'VigenciaDesde' => 'required|date',
                'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
                'EdadMaximaInscripcion' => 'required|numeric|min:18|max:100',
                'EdadTerminacion' => 'required|numeric|min:18|max:100|gte:EdadMaximaInscripcion',
                'Tasa' => 'required|numeric|min:0',
                'TasaDescuento' => 'nullable|numeric|min:0|max:100',
                'Concepto' => 'nullable|string|max:500'
            ];

            // Reglas condicionales
            if ($request->TipoCobro == 1) {
                $rules['SumaMinima'] = 'required|numeric|min:0.01';
                $rules['SumaMaxima'] = 'required|numeric|gt:SumaMinima';
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 1) {
                $rules['SumaAsegurada'] = 'required|numeric|min:0.01';
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 2) {
                $rules['Multitarifa'] = [
                    'required',
                    'string',
                    'regex:/^(\d+(\.\d{1,2})?)(,\d+(\.\d{1,2})?)*$/'
                ];
            }

            $messages = [
                'NumeroPoliza.required' => 'El número de póliza es obligatorio',
                'NumeroPoliza.unique' => 'Este número de póliza ya existe',
                'Aseguradora.required' => 'Seleccione una aseguradora',
                'Productos.required' => 'Seleccione un producto',
                'Planes.required' => 'Seleccione un plan',
                'Asegurado.required' => 'Seleccione un asegurado',
                'Ejecutivo.required' => 'Seleccione un ejecutivo',
                'VigenciaHasta.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
                'EdadTerminacion.gte' => 'La edad de terminación debe ser mayor o igual a la edad máxima de inscripción',
                'SumaAsegurada.min' => 'La suma asegurada mínima es 0.01',
                'LimiteMaximoIndividual.min' => 'El límite máximo individual debe ser al menos 0.01',
                'Multitarifa.required' => 'El campo multitarifa es obligatorio',
                'Multitarifa.regex' => 'El formato de multitarifa es inválido. Use números separados por coma, con hasta 2 decimales.',

                // Mensajes para SumaMinima y SumaMaxima
                'SumaMinima.required' => 'La suma mínima es obligatoria',
                'SumaMinima.numeric' => 'La suma mínima debe ser un valor numérico',
                'SumaMinima.min' => 'La suma mínima debe ser al menos 0.01',
                'SumaMaxima.required' => 'La suma máxima es obligatoria',
                'SumaMaxima.numeric' => 'La suma máxima debe ser un valor numérico',
                'SumaMaxima.gt' => 'La suma máxima debe ser mayor que la suma mínima',


            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            return response()->json(['success' => true, 'data' => $validator->validated()]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['server' => ['Error interno del servidor']]
            ], 500);
        }
    }

    public function validar_edit(Request $request, $id)
    {
        try {
            $rules = [
                'NumeroPoliza' => 'required|unique:poliza_vida,NumeroPoliza,' . $id . ',Id',
                'Aseguradora' => 'required|exists:aseguradora,Id',
                'Productos' => 'required|exists:producto,Id',
                'Planes' => 'required|exists:plan,Id',
                'Asegurado' => 'required|exists:cliente,Id',
                'Ejecutivo' => 'required|exists:ejecutivo,Id',
                'TipoCobro' => 'required|exists:tipo_cobro,Id',
                'VigenciaDesde' => 'required|date',
                'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
                'EdadMaximaInscripcion' => 'required|numeric|min:18|max:100',
                'EdadTerminacion' => 'required|numeric|min:18|max:100|gte:EdadMaximaInscripcion',
                'Tasa' => 'required|numeric|min:0',
                'TasaDescuento' => 'nullable|numeric|min:0|max:100',
                'Concepto' => 'nullable|string|max:500'
            ];

            // Reglas condicionales
            if ($request->TipoCobro == 1) {
                $rules['SumaMinima'] = 'required|numeric|min:0.01';
                $rules['SumaMaxima'] = 'required|numeric|gt:SumaMinima';
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 1) {
                $rules['SumaAsegurada'] = 'required|numeric|min:0.01';
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 2) {
                $rules['Multitarifa'] = [
                    'required',
                    'string',
                    'regex:/^(\d+(\.\d{1,2})?)(,\d+(\.\d{1,2})?)*$/'
                ];
            }

            $messages = [
                'NumeroPoliza.required' => 'El número de póliza es obligatorio',
                'NumeroPoliza.unique' => 'Este número de póliza ya existe',
                'Aseguradora.required' => 'Seleccione una aseguradora',
                'Productos.required' => 'Seleccione un producto',
                'Planes.required' => 'Seleccione un plan',
                'Asegurado.required' => 'Seleccione un asegurado',
                'Ejecutivo.required' => 'Seleccione un ejecutivo',
                'VigenciaHasta.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
                'EdadTerminacion.gte' => 'La edad de terminación debe ser mayor o igual a la edad máxima de inscripción',
                'SumaAsegurada.min' => 'La suma asegurada mínima es 0.01',
                'LimiteMaximoIndividual.min' => 'El límite máximo individual debe ser al menos 0.01',
                'Multitarifa.required' => 'El campo multitarifa es obligatorio',
                'Multitarifa.regex' => 'El formato de multitarifa es inválido. Use números separados por coma, con hasta 2 decimales.',

                // Mensajes para SumaMinima y SumaMaxima
                'SumaMinima.required' => 'La suma mínima es obligatoria',
                'SumaMinima.numeric' => 'La suma mínima debe ser un valor numérico',
                'SumaMinima.min' => 'La suma mínima debe ser al menos 0.01',
                'SumaMaxima.required' => 'La suma máxima es obligatoria',
                'SumaMaxima.numeric' => 'La suma máxima debe ser un valor numérico',
                'SumaMaxima.gt' => 'La suma máxima debe ser mayor que la suma mínima',


            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            return response()->json(['success' => true, 'data' => $validator->validated()]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['server' => ['Error interno del servidor']]
            ], 500);
        }
    }







    public function edit($id, Request $request)
    {
        $vida = Vida::findOrFail($id);


        $tab = $request->tab ?? 1;


        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->orderBy('Id', 'desc')->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $tiposCartera = VidaTipoCartera::get();
        // $historico_poliza = PolizaDeudaHistorica::where('Deuda', $id)->get();
        // $registroInicial = $historico_poliza->isNotEmpty() ? $historico_poliza->first() : null;


        return view('polizas.vida.edit', compact(
            'vida',
            'aseguradora',
            'cliente',
            'tipoCobro',
            'ejecutivo',
            'productos',
            'planes',
            'tiposCartera',
            'tab'
        ));
    }

    public function finalizar_configuracion(Request $request)
    {
        $vida = Vida::findOrFail($request->vida);
        if ($vida->Configuracion == 1) {
            $vida->Configuracion = 0;
            $vida->update();

            //alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/vida/' . $request->vida . '/edit')
                ->with('El registro de poliza ha sido configurado correctamente');
        } else {
            $vida->Configuracion = 1;
            $vida->update();

            //alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/vida/' . $request->vida)->with('El registro de poliza ha sido configurado correctamente');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'NumeroPoliza' => 'required|unique:poliza_vida,NumeroPoliza,' . $id . ',Id',
                'Aseguradora' => 'required|exists:aseguradora,Id',
                'Productos' => 'required|exists:producto,Id',
                'Planes' => 'required|exists:plan,Id',
                'Asegurado' => 'required|exists:cliente,Id',
                'Ejecutivo' => 'required|exists:ejecutivo,Id',
                'TipoCobro' => 'required|exists:tipo_cobro,Id',
                'VigenciaDesde' => 'required|date',
                'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
                'EdadMaximaInscripcion' => 'required|numeric|min:18|max:100',
                'EdadTerminacion' => 'required|numeric|min:18|max:100|gte:EdadMaximaInscripcion',
                'Tasa' => 'required|numeric|min:0',
                'TasaDescuento' => 'nullable|numeric|min:0|max:100',
                'Concepto' => 'nullable|string|max:500'
            ];

            // Reglas condicionales
            if ($request->TipoCobro == 1) {
                $rules['SumaMinima'] = 'required|numeric|min:0.01';
                $rules['SumaMaxima'] = 'required|numeric|gt:SumaMinima';
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 1) {
                $rules['SumaAsegurada'] = 'required|numeric|min:0.01';
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 2) {
                $rules['Multitarifa'] = [
                    'required',
                    'string',
                    'regex:/^(\d+(\.\d{1,2})?)(,\d+(\.\d{1,2})?)*$/'
                ];
            }

            $messages = [
                'NumeroPoliza.required' => 'El número de póliza es obligatorio',
                'NumeroPoliza.unique' => 'Este número de póliza ya existe',
                'Aseguradora.required' => 'Seleccione una aseguradora',
                'Productos.required' => 'Seleccione un producto',
                'Planes.required' => 'Seleccione un plan',
                'Asegurado.required' => 'Seleccione un asegurado',
                'Ejecutivo.required' => 'Seleccione un ejecutivo',
                'VigenciaHasta.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
                'EdadTerminacion.gte' => 'La edad de terminación debe ser mayor o igual a la edad máxima de inscripción',
                'SumaAsegurada.min' => 'La suma asegurada mínima es 0.01',
                'Multitarifa.required' => 'El campo multitarifa es obligatorio',
                'Multitarifa.regex' => 'El formato de multitarifa es inválido. Use números separados por coma, con hasta 2 decimales.',
                'SumaMinima.required' => 'La suma mínima es obligatoria',
                'SumaMinima.numeric' => 'La suma mínima debe ser un valor numérico',
                'SumaMinima.min' => 'La suma mínima debe ser al menos 0.01',
                'SumaMaxima.required' => 'La suma máxima es obligatoria',
                'SumaMaxima.numeric' => 'La suma máxima debe ser un valor numérico',
                'SumaMaxima.gt' => 'La suma máxima debe ser mayor que la suma mínima',
            ];

            $validatedData = Validator::make($request->all(), $rules, $messages)->validate();

            DB::beginTransaction();

            $vida = Vida::findOrFail($id);
            $vida->NumeroPoliza = $request->NumeroPoliza;
            $vida->Nit = $request->Nit ?? null;
            $vida->Aseguradora = $request->Aseguradora;
            $vida->Producto = $request->Productos;
            $vida->Plan = $request->Planes;
            $vida->Asegurado = $request->Asegurado;
            $vida->VigenciaDesde = $request->VigenciaDesde;
            $vida->VigenciaHasta = $request->VigenciaHasta;
            $vida->Concepto = $request->Concepto ?? null;
            $vida->Ejecutivo = $request->Ejecutivo;
            $vida->TipoCobro = $request->TipoCobro;
            $vida->TipoTarifa = $request->TipoTarifa ?? null;
            $vida->Tasa = $request->Tasa;
            $vida->TasaDescuento = $request->TasaDescuento ?? null;
            $vida->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $vida->EdadTerminacion = $request->EdadTerminacion;
            $vida->EstadoPoliza = 1;
            $vida->Activo = 1;

            if ($request->TipoCobro == 1) {
                // Si es 1, solo aplica SumaMinima y SumaMaxima
                $vida->SumaMinima   = $request->SumaMinima ?? null;
                $vida->SumaMaxima   = $request->SumaMaxima ?? null;
                $vida->SumaAsegurada = null;
                $vida->Multitarifa   = null;
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 1) {
                // Si es 2 y tarifa 1, aplica SumaAsegurada
                $vida->SumaAsegurada = $request->SumaAsegurada ?? null;
                $vida->Multitarifa   = null;
                $vida->SumaMinima    = null;
                $vida->SumaMaxima    = null;
            }

            if ($request->TipoCobro == 2 && $request->TipoTarifa == 2) {
                // Si es 2 y tarifa 2, aplica Multitarifa
                $vida->Multitarifa   = $request->Multitarifa ?? null;
                $vida->SumaAsegurada = null;
                $vida->SumaMinima    = null;
                $vida->SumaMaxima    = null;
            }



            $vida->TarifaExcel = $request->TarifaExcel == 'on' ? 1 : 0;

            $vida->save();

            DB::commit();

            return redirect('polizas/vida/' . $vida->Id . '/edit?tab=2')
                ->with('success', 'El registro ha sido modificado correctamente');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar póliza: ' . $e->getMessage());

            $validator = Validator::make([], []);
            $validator->errors()->add('general', 'Ocurrió un error: ' . $e->getMessage());

            return back()
                ->withErrors($validator)
                ->withInput();
        }
    }



    public function show(Request $request, $id)
    {
        $poliza_vida = Vida::findOrFail($id);
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        //$tipoCartera = TipoCartera::where('Activo', 1)->get();
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();

        $tab = $request->tab ? $request->tab : 1;






        // tab2 si no es multi categoria
        if ($poliza_vida->TarifaExcel != 1) {
            $cartera = VidaCartera::where('PolizaVida', '=', $id)
                ->where('PolizaVidaDetalle', null)
                ->select(DB::raw("IFNULL(sum(SumaAsegurada), '0.00') as SumaAsegurada"))->first();

            $tipos_cartera = $poliza_vida->vida_tipos_cartera;

            $dataPago = collect();
            $dataPagoId = [];

            foreach ($tipos_cartera as $tipo) {
                foreach ($tipo->tasa_diferenciada as $tasa) {
                    $dataPagoId[] = $tasa->Id;
                    //dd($tasa);
                    //por fechas

                    $item['Id'] =  $tasa->Id;
                    if ($tipo->TipoCalculo == 1) {

                        $total = VidaCartera::where('PolizaVidaDetalle', null)
                            ->where('PolizaVida', $id)
                            ->where('PolizaVidaTipoCartera', $tipo->Id)
                            ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                            ->sum('SumaAsegurada');


                        $item['TipoCartera'] = $tipo->catalogo_tipo_cartera->Nombre;
                        $item['Tasa'] = $tasa->Tasa;
                        $item['Monto'] = "";
                        $item['Fecha'] = $tasa->FechaDesde . " - " . $tasa->FechaHasta;
                        $item['SumaAsegurada'] = $total;
                        $item['PrimaCalculada'] = $total * $tasa->Tasa;
                        $dataPago->push($item);
                    }
                    //por monto
                    else if ($tipo->TipoCalculo == 2) {
                        $total = VidaCartera::where('PolizaVidaDetalle', null)
                            ->where('PolizaVida', $id)
                            ->where('PolizaVidaTipoCartera', $tipo->Id)
                            ->whereBetween('SumaAsegurada', [$tasa->MontoDesde, $tasa->MontoHasta])
                            ->sum('SumaAsegurada');

                        $item['TipoCartera'] = $tipo->catalogo_tipo_cartera->Nombre;
                        $item['Tasa'] = $tasa->Tasa;
                        $item['Monto'] = $tasa->MontoDesde . " - " . $tasa->MontoHasta;
                        $item['Fecha'] = "";
                        $item['SumaAsegurada'] = $total;
                        $item['PrimaCalculada'] = $total * $tasa->Tasa;
                        $dataPago->push($item);
                    } else {
                        $total = VidaCartera::where('PolizaVidaDetalle', null)
                            ->where('PolizaVida', $id)
                            ->where('PolizaVidaTipoCartera', $tipo->Id)
                            ->sum('SumaAsegurada');

                        $item['TipoCartera'] = $tipo->catalogo_tipo_cartera->Nombre;
                        $item['Tasa'] = $tasa->Tasa;
                        $item['Monto'] = "";
                        $item['Fecha'] = "";
                        $item['SumaAsegurada'] = $total;
                        $item['PrimaCalculada'] = $total * $tasa->Tasa;
                        $dataPago->push($item);
                    }
                }
            }
        } else {

            $cartera = VidaCartera::where('PolizaVida', '=', $id)
                ->where('PolizaVidaDetalle', null)
                ->select(DB::raw("IFNULL(sum(SumaAsegurada), '0.00') as SumaAsegurada"))->first();

            $tipos_cartera = $poliza_vida->vida_tipos_cartera;

            $dataPago = collect();
            $dataPagoId = [];


            foreach ($tipos_cartera as $tipo) {

                $calculo_totales = VidaCartera::select('Tasa', DB::raw('SUM(SumaAsegurada) as total'))
                    ->whereNull('PolizaVidaDetalle')
                    ->where('PolizaVida', $id)
                    ->where('PolizaVidaTipoCartera', $tipo->Id)
                    ->groupBy('Tasa')
                    ->get();


                foreach ($calculo_totales as $calculo) {
                    $dataPagoId[] = $tipo->Id . $calculo->Tasa;
                    $item['Id'] =  $tipo->Id . $calculo->Tasa;
                    $item['TipoCartera'] = $tipo->catalogo_tipo_cartera->Nombre;
                    $item['Tasa'] = $calculo->Tasa;
                    $item['Monto'] = "";
                    $item['Fecha'] = "";
                    $item['SumaAsegurada'] = $calculo->total;
                    $item['PrimaCalculada'] = $calculo->total * $calculo->Tasa;
                    $dataPago->push($item);
                }
            }
        }

        //tab 3

        $ultimo_pago = VidaDetalle::where('PolizaVida', $id)->orderBy('Id', 'desc')->first();
        $detalle = VidaDetalle::where('PolizaVida', $id)->orderBy('Id', 'desc')->get();
        $comentarios = Comentario::where('Vida', $poliza_vida->Id)->where('Activo', '=', 1)->get();
        //dd($comentarios);

        //extraprima
        $clientes = VidaCartera::select(
            'Id',
            'PrimerNombre',
            DB::raw("TRIM(CONCAT(
                    IFNULL(PrimerNombre, ''),
                    IF(IFNULL(SegundoNombre, '') != '', CONCAT(' ', SegundoNombre), ''),
                    IF(IFNULL(PrimerApellido, '') != '', CONCAT(' ', PrimerApellido), ''),
                    IF(IFNULL(SegundoApellido, '') != '', CONCAT(' ', SegundoApellido), ''),
                    IF(IFNULL(ApellidoCasada, '') != '', CONCAT(' ', ApellidoCasada), '')
                )) as Nombre"),
            'Dui',
            'NumeroReferencia',
            'SumaAsegurada',
            'Axo',
            'Mes'
        )->where('PolizaVida', '=', $id)->where('PolizaVidaDetalle', null)
            ->orWhere('PolizaVidaDetalle', '=', null)->groupBy('NumeroReferencia')->get();

        $extraprimados = PolizaVidaExtraPrimados::where('PolizaVida', $id)->get();

        foreach ($extraprimados as $extraprimado) {
            //consultando calculos de extraprimados
            $data_array = $extraprimado->getPagoEP($extraprimado->Id);

            $extraprimado->SumaAsegurada = $data_array['SumaAsegurada'] ?? 0.00;
            $extraprimado->PrimaNeta = $data_array['PrimaNeta'] ?? 0.00;
            $extraprimado->ExtraPrima = $data_array['ExtraPrima'] ?? 0.00;


            // dd($data_array);
        }

        $total_extrapima = $extraprimados->sum('ExtraPrima') ?? 0.00;

        $fechas = VidaCartera::where('PolizaVida', '=', $id)->where('PolizaVidaDetalle', null)->first();

        //conteo por si existe tasa diferenciada
        $count_tasas_diferencidas = VidaTasaDiferenciada::join('poliza_vida_tipo_cartera', 'poliza_vida_tipo_cartera.Id', '=', 'poliza_vida_tasa_diferenciada.PolizaVidaTipoCartera')
            ->where('poliza_vida_tipo_cartera.PolizaVida', $id)
            ->whereIn('poliza_vida_tipo_cartera.TipoCalculo', [1, 2])->count();


        return view('polizas.vida.show', compact(
            'extraprimados',
            'clientes',
            'total_extrapima',
            'poliza_vida',
            'detalle',
            'aseguradora',
            'cliente',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
            'tab',
            'cartera',
            'comentarios',
            'dataPago',
            'dataPagoId',
            'fechas',
            //tab3
            'ultimo_pago',
            'count_tasas_diferencidas'
        ));
    }


    public function subir_cartera($id)
    {
        $poliza_vida = Vida::findOrFail($id);
        $poliza_vida_tipo_cartera = $poliza_vida->vida_tipos_cartera;

        foreach ($poliza_vida_tipo_cartera as $item) {
            $item->Total = VidaCarteraTemp::where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $item->Id)->sum('SumaAsegurada');
        }

        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');


        $fechaInicio = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $fechaFinal = Carbon::now()->startOfMonth()->toDateString();

        // Extraer el mes y el año de $fechaFinal
        $mes = Carbon::parse($fechaFinal)->month;
        $anioSeleccionado = Carbon::parse($fechaFinal)->year;

        $vigenciaDesde = Carbon::parse($poliza_vida->VigenciaDesde);
        $vigenciaHasta = Carbon::parse($poliza_vida->VigenciaHasta);

        $anios = array_combine(
            $years = range($vigenciaDesde->year, $vigenciaHasta->year),
            $years
        );

        $fechas = VidaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
            ->where('PolizaVida', '=', $id)
            ->orderByDesc('Id')->first();

        return view('polizas.vida.subir_archivos', compact(
            'poliza_vida',
            'poliza_vida_tipo_cartera',
            'meses',
            'anios',
            'mes'
        ));
    }


    public function validar_poliza($id)
    {
        $poliza_vida = Vida::findOrFail($id);


        $temp_data_fisrt = VidaCarteraTemp::where('PolizaVida', $id)->where('User', auth()->user()->id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }

        $axoActual =  $temp_data_fisrt->Axo;
        $mesActual =  $temp_data_fisrt->Mes;


        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $mesString = (isset($mesActual) && isset($meses[$mesActual])) ? $meses[$mesActual] : '';

        // Calcular el mes pasado
        if ($mesActual == 1) {
            $mesAnterior = 12; // Diciembre
            $axoAnterior = $axoActual - 1; // Año anterior
        } else {
            $mesAnterior = $mesActual - 1; // Mes anterior
            $axoAnterior = $axoActual; // Mismo año
        }

        $poliza_edad_maxima = VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('EdadDesembloso', '>', $poliza_vida->EdadMaximaInscripcion)->get();
        $poliza_edad_terminacion = VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('EdadDesembloso', '>', $poliza_vida->EdadTerminacion)->get();


        if ($poliza_vida->TipoCobro == 1) {
            $poliza_responsabilidad_maxima = VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)
                ->whereColumn('SumaAsegurada', '>', 'MontoMaximoIndividual')->get();
        } else {
            $poliza_responsabilidad_maxima = VidaCarteraTemp::where('Id', 0)->get();
        }




        //registros que no existen en el mes anterior
        $count_data_cartera = VidaCartera::where('PolizaVida', $id)->count();
        if ($count_data_cartera > 0) {
            //dd($mesAnterior,$axoAnterior,$request->Desempleo);
            $registros_eliminados = DB::table('poliza_vida_cartera AS pdc')
                ->leftJoin('poliza_vida_cartera_temp AS pdtc', function ($join) {
                    $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia')
                        ->where('pdtc.User', auth()->user()->id);
                })
                ->where('pdc.Mes', (int)$mesAnterior)
                ->where('pdc.Axo', (int)$axoAnterior)
                ->where('pdc.PolizaVida', $id)
                ->whereNull('pdtc.NumeroReferencia') // Solo los que no están en poliza_desempleo_temp_cartera
                ->select('pdc.*') // Selecciona columnas principales
                ->get();
        } else {
            $registros_eliminados =  VidaCarteraTemp::where('Id', 0)->get();
        }


        $nuevos_registros = VidaCarteraTemp::leftJoin(
            DB::raw('(
                  SELECT DISTINCT NumeroReferencia
                  FROM poliza_vida_cartera
                  WHERE PolizaVida = ' . $id . '
              ) AS valid_references'),
            'poliza_vida_cartera_temp.NumeroReferencia',
            '=',
            'valid_references.NumeroReferencia'
        )
            ->where('poliza_vida_cartera_temp.User', auth()->user()->id) // Filtra por el usuario autenticado
            ->where('poliza_vida_cartera_temp.PolizaVida', $id)
            ->whereNull('valid_references.NumeroReferencia') // Los registros que no coinciden
            ->select('poliza_vida_cartera_temp.*') // Selecciona columnas de la tabla principal
            ->get();


        $total = VidaCarteraTemp::where('PolizaVida', $id)->sum('SumaAsegurada');



        $temp = VidaCarteraTemp::where('PolizaVida', $id)->get();
        $mesAnteriorString = $axoAnterior . '-' . $mesAnterior;
        //calcular rehabilitados
        $referenciasAnteriores = DB::table('poliza_vida_cartera')
            ->where('PolizaVida', $id)
            ->where('User', auth()->user()->id)
            ->whereRaw('CONCAT(Axo, "-", Mes) <> ?', [$mesAnteriorString])
            ->pluck('NumeroReferencia')
            ->toArray();


        $referenciasMesAterior = DB::table('poliza_vida_cartera')
            ->where('PolizaVida', $id)
            ->where('User', auth()->user()->id)
            ->where('Axo', $axoAnterior)
            ->where('Mes', $mesAnterior)
            ->pluck('NumeroReferencia')
            ->toArray();


        foreach ($temp as $item) {
            // Verifica si el NumeroReferencia está en referenciasAnteriores pero no en referenciasMesAterior
            if (in_array($item->NumeroReferencia, $referenciasAnteriores) && !in_array($item->NumeroReferencia, $referenciasMesAterior)) {
                $item->Rehabilitado = 1;
                $item->save();
            }
        }

        $registros_rehabilitados = VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('Rehabilitado', 1)->get();

        $extra_primados = $poliza_vida->extra_primados;

        foreach ($extra_primados as $extra_primado) {
            //$extra_primado->Existe =
            $registro  = VidaCarteraTemp::where('NumeroReferencia', $extra_primado->NumeroReferencia)
                ->sum('SumaAsegurada') ?? 0;

            if ($registro > 0) {
                $extra_primado->Existe = 1;
                $extra_primado->MontoOtorgamiento = $registro;
            } else {
                $extra_primado->Existe = 0;
            }
        }

        return view('polizas.vida.respuesta_poliza', compact(
            'total',
            'poliza_vida',
            'poliza_edad_maxima',
            'poliza_edad_terminacion',
            'registros_rehabilitados',
            'registros_eliminados',
            'nuevos_registros',
            'mesString',
            'axoActual',
            'mesActual',
            'poliza_responsabilidad_maxima',
            'extra_primados'
        ));
    }



    public function create_pago(Request $request)
    {


        $request->validate([
            'Axo' => 'required|integer',
            'Mes' => 'required|integer|between:1,12',
            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'PolizaVidaTipoCartera' => 'required|integer',
        ], [
            'Axo.required' => 'El campo Año es obligatorio.',
            'Axo.integer' => 'El campo Año debe ser un número entero.',
            'Axo.min' => 'El campo Año debe ser mayor o igual a 2022.',
            'Axo.max' => 'El campo Año no puede ser mayor al año actual.',
            'Mes.required' => 'El campo Mes es obligatorio.',
            'Mes.integer' => 'El campo Mes debe ser un número entero.',
            'Mes.between' => 'El campo Mes debe estar entre 1 y 12.',
            'FechaInicio.required' => 'El campo Fecha de inicio es obligatorio.',
            'FechaInicio.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'FechaFinal.required' => 'El campo Fecha final es obligatorio.',
            'FechaFinal.date' => 'El campo Fecha final debe ser una fecha válida.',
            'FechaFinal.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'Archivo.required' => 'El campo Archivo es obligatorio.',
            'Archivo.file' => 'El campo Archivo debe ser un archivo válido.',
            'Archivo.mimes' => 'El archivo debe ser de tipo CSV, XLSX o XLS.',
            'Archivo.max' => 'El archivo no debe superar los 2MB.',
            'PolizaVidaTipoCartera.required' => 'El campo tipo cartera es obligatorio.',
            'PolizaVidaTipoCartera.integer' => 'El campo tipo cartera no válido.',
        ]);


        $id = $request->Id;

        $poliza_vida = Vida::findOrFail($id);

        $archivo = $request->Archivo;

        $excel = IOFactory::load($archivo);

        // Verifica si hay al menos dos hojas
        $sheetsCount = $excel->getSheetCount();

        if ($sheetsCount > 1) {
            return redirect('polizas/vida/' . $id . '?tab=2')
                ->withErrors(['excel_file' => 'La cartera solo puede contener un solo libro de Excel']);
        }


        // Crear validador vacío
        $validator = Validator::make([], []);

        // 2. Validar primera fila
        $expectedColumns = [
            "DUI",
            "PASAPORTE",
            "CARNET RESI",
            "NACIONALIDAD",
            "FECNACIMIENTO",
            "TIPO PERSONA",
            "GENERO",
            "PRIMERAPELLIDO",
            "SEGUNDOAPELLIDO",
            "APELLIDOCASADA",
            "PRIMERNOMBRE",
            "SEGUNDONOMBRE",
            "NOMBRE SOCIEDAD",
            "FECOTORGAMIENTO",
            "FECHA DE VENCIMIENTO",
            "NUMREFERENCIA",
            "SUMA ASEGURADA",
            "SALDO DE CAPITAL",
            "INTERES CORRIENTES",
            "INTERES MORATORIO",
            "INTERES COVID",
            "TARIFA",
            "TIPO DE DEUDA",
            "PORCENTAJE EXTRAPRIMA",
        ];

        $firstRow = $excel->getActiveSheet()->rangeToArray('A1:Z1')[0];

        // Validar que no esté vacío
        if (empty(array_filter($firstRow))) {
            $validator->errors()->add('Archivo', 'El archivo está vacío o no tiene el formato esperado');
            return back()->withErrors($validator);
        }

        // Normalizar (trim) y pasar a minúsculas para ignorar mayúsculas
        $firstRow = array_map(fn($v) => mb_strtolower(trim($v)), $firstRow);
        $expectedColumnsLower = array_map(fn($v) => mb_strtolower($v), $expectedColumns);

        // Función para convertir índice a letra (0 => A, 1 => B, etc.)
        function columnLetter($index)
        {
            $letter = '';
            while ($index >= 0) {
                $letter = chr($index % 26 + 65) . $letter;
                $index = floor($index / 26) - 1;
            }
            return $letter;
        }

        // Validar cantidad de columnas
        if (count($firstRow) < count($expectedColumnsLower)) {
            $validator->errors()->add('Archivo', 'Error de formato: faltan columnas en la primera fila');
            return back()->withErrors($validator);
        }

        // Validar que todas las columnas sean iguales y en el mismo orden
        foreach ($expectedColumnsLower as $index => $expectedColumn) {
            if (!isset($firstRow[$index]) || $firstRow[$index] !== $expectedColumn) {
                $validator->errors()->add(
                    'Archivo',
                    "Error de formato: la columna " . columnLetter($index) . " debe ser '{$expectedColumns[$index]}'"
                );
                return back()->withErrors($validator);
            }
        }





        //borrar datos de tabla temporal
        VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->delete();

        Excel::import(new VidaCarteraTempImport($request->Axo, $request->Mes, $id, $request->FechaInicio, $request->FechaFinal, $request->PolizaVidaTipoCartera, $poliza_vida->TarifaExcel), $archivo);



        //verificando creditos repetidos
        $repetidos = VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)
            ->groupBy('NumeroReferencia')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $numerosRepetidos = $repetidos->isNotEmpty() ? $repetidos->pluck('NumeroReferencia') : null;

        if ($numerosRepetidos) {
            VidaCarteraTemp::where('User', auth()->user()->id)
                ->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)
                ->delete();

            $numerosStr = $numerosRepetidos->implode(', ');

            return back()
                ->withErrors(['Archivo' => "Existen números de crédito repetidos: $numerosStr"]);
        }


        //calculando edades y fechas de nacimiento
        VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVida', $poliza_vida->Id)
            ->update([
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);

        //calculando errores de cartera
        $cartera_temp = VidaCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->get();

        //dd($cartera_temp->take(10));

        //arreglo para la multicategoria
        if ($poliza_vida->TarifaExcel == 0) {
            $montos = [$poliza_vida->SumaAsegurada];
        } else {
            $montos = explode(',', $poliza_vida->Multitarifa);

            dd($montos);
        }

        foreach ($cartera_temp as $obj) {
            $errores_array = [];

            if ($obj->FechaNacimientoDate == null) {
                $obj->TipoError = 1;
                $obj->update();
                array_push($errores_array, 1);
            }


            if ($obj->FechaOtorgamientoDate == null) {
                $obj->TipoError = 2;
                $obj->update();
                array_push($errores_array, 1);
            }

            if ($request->validacion_dui == 'on') {
                $validador_dui = true;
            } else {
                // Validar si la nacionalidad está vacía
                if (empty($obj->Nacionalidad)) {
                    $obj->TipoError = 3;
                    $obj->update();
                    $errores_array[] = 3; // Agregar error al array
                }
                // Validar si la nacionalidad es SAL (El Salvador)
                else if (strtolower(trim($obj->Nacionalidad)) == 'sal') {
                    $validador_dui = $this->validarDocumento($obj->Dui, "dui");
                    if (!$validador_dui) {
                        $obj->TipoError = 4;
                        $obj->update();
                        $errores_array[] = 4; // Agregar error al array
                    }
                }
                // Validar si el pasaporte está vacío para nacionalidades no SAL
                else if (empty($obj->Pasaporte)) {
                    $validador_dui = false;
                    $obj->TipoError = 5;
                    $obj->update();
                    $errores_array[] = 5; // Agregar error al array
                } else {
                    $validador_dui = true;
                }
            }





            // 4 nombre o apellido
            if (trim($obj->PrimerApellido) == "" || trim($obj->PrimerNombre) == "") {
                $obj->TipoError = 6;
                $obj->update();

                array_push($errores_array, 6);
            }


            // 7 referencia si va vacia.
            if (trim($obj->NumeroReferencia) == "") {
                $obj->TipoError = 7;
                $obj->update();

                array_push($errores_array, 7);
            }


            // 10 error sexo
            if (empty(trim($obj->Sexo)) || !in_array($obj->Sexo, ['M', 'F'])) {
                $obj->TipoError = 8;
                $obj->update();
                $errores_array[] = 8; // Agregar error al array
            }

            //11 error por edad de terminacion
            if (trim($obj->Edad) > $poliza_vida->EdadTerminacion) {
                $obj->TipoError = 9;
                $obj->update();

                array_push($errores_array, 9);
            }



            //validar cantidad asegurada o multi categoria error 10

            if (!in_array($obj->SumaAsegurada, $montos)) {
                $obj->TipoError = 10;
                $obj->update();

                array_push($errores_array, 10);
            }


            // 11 error nombres o apellidos con caracteres inválidos
            $regex = '/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s\.\'\-]+$/u'; // letras, espacios, punto, apóstrofe y guion
            $campos = [
                $obj->PrimerNombre,
                $obj->SegundoNombre,
                $obj->PrimerApellido,
                $obj->SegundoApellido,
                $obj->ApellidoCasada
            ];

            foreach ($campos as $valor) {
                if ($valor && !preg_match($regex, $valor)) {
                    $obj->TipoError = 11;
                    $obj->update(); // guardamos inmediatamente
                    array_push($errores_array, 11);
                    break; // no necesitamos seguir revisando otros campos
                }
            }



            $obj->Errores = $errores_array;
        }

        $data_error = $cartera_temp->where('TipoError', '<>', 0);

        if ($data_error->count() > 0) {
            return view('polizas.vida.respuesta_poliza_error', compact('data_error', 'poliza_vida'));
        }


        $temp_data_fisrt = VidaCarteraTemp::where('PolizaVida', $id)->where('User', auth()->user()->id)->where('PolizaVidaTipoCartera', '=', $request->PolizaVidaTipoCartera)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }






        //tasa diferenciada
        $vida_tipo_cartera = VidaTipoCartera::findOrFail($request->PolizaVidaTipoCartera);

        $tasas_diferenciadas = $vida_tipo_cartera->tasa_diferenciada;

        if ($vida_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($vida_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->whereBetween('SumaAsegurada', [$tasa->MontoDesde, $tasa->MontoHasta])
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $poliza_vida->Tasa
                    ]);
            }
        }

        //agregar la validacion para las edades maximas de inscripcion

        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();
    }

    public function delete_temp($id)
    {
        try {
            $deleted = VidaCarteraTemp::where('PolizaVida', $id)->delete();

            if ($deleted === 0) {
                return redirect('polizas/vida/' . $id . '?tab=2')
                    ->withErrors(['delete_error' => 'No se encontraron registros para eliminar']);
            }

            return redirect('polizas/vida/' . $id . '?tab=2')
                ->with('success', 'Registros temporales eliminados correctamente');
        } catch (\Exception $e) {
            return redirect('polizas/vida/' . $id . '?tab=2')
                ->withErrors(['delete_error' => 'Error al eliminar registros: ' . $e->getMessage()]);
        }
    }


    public function agregar_pago(Request $request)
    {

        //$poliza_vida = Vida::findOrFail($request->PolizaVida);
        $time = Carbon::now('America/El_Salvador');

        $recibo = DatosGenerales::orderByDesc('Id_recibo')->first();

        $detalle = new VidaDetalle();
        $detalle->FechaInicio = $request->FechaInicio;
        $detalle->FechaFinal = $request->FechaFinal;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->PolizaVida = $request->PolizaVida;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaCalculada = $request->PrimaCalculada;
        $detalle->Descuento = $request->Descuento;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        //$detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
        //$detalle->GastosEmision = $request->GastosEmision;
        //$detalle->Otros = $request->Otros;
        // $detalle->SubTotal = $request->SubTotal;
        // $detalle->Iva = $request->Iva;
        $detalle->TasaComision = $request->TasaComision;
        $detalle->Comision = $request->Comision;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->Retencion = $request->Retencion;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->Comentario = $request->Comentario;
        $detalle->APagar = $request->APagar;

        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->DescuentoIva = $request->DescuentoIva; //checked
        $detalle->ExtraPrima = $request->ExtraPrima;
        //$detalle->ExcelURL = $request->ExcelURL;
        $detalle->NumeroRecibo = ($recibo->Id_recibo) + 1;
        $detalle->Usuario = auth()->user()->id;
        $detalle->FechaIngreso = $time->format('Y-m-d');
        $detalle->save();

        //DesempleoCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaDesempleo', $request->Desempleo)->delete();
        VidaCartera::where('PolizaVida', $request->PolizaVida)->where('PolizaVidaDetalle', null)->update(['PolizaVidaDetalle' => $detalle->Id]);

        $comen = new Comentario();
        $comen->Comentario = 'Se agrego el pago de la cartera';
        $comen->Activo = 1;
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Vida = $request->PolizaVida;
        $comen->DetalleVida = $detalle->Id;
        $comen->save();


        $recibo->Id_recibo = ($recibo->Id_recibo) + 1;
        $recibo->update();

        /// $extraprimados = PolizaDesempleoExtraPrimados::where('PolizaDesempleo', $request->Desempleo)->get();
        //$total_extrapima = 0;
        /*foreach ($extraprimados as $extraprimado) {
            //consultando calculos de extraprimados
            $data_array = $extraprimado->getPagoEP($extraprimado->Id);

            $extraprimado->total = $data_array['total'];
            $extraprimado->saldo_capital = $data_array['saldo_capital'];
            $extraprimado->interes = $data_array['interes'];
            $extraprimado->prima_neta = $data_array['prima_neta'];
            $extraprimado->extra_prima = $data_array['extra_prima'];
            $total_extrapima += $data_array['extra_prima'];


            $prima_mensual = new PolizaDesempleoExtraPrimadosMensual();
            $prima_mensual->PolizaDesempleo = $request->Desempleo;
            $prima_mensual->Dui = $extraprimado->Dui;
            $prima_mensual->NumeroReferencia = $extraprimado->NumeroReferencia;
            $prima_mensual->Nombre = $extraprimado->Nombre;
            $prima_mensual->FechaOtorgamiento = $extraprimado->FechaOtorgamiento;
            $prima_mensual->MontoOtorgamiento = $extraprimado->MontoOtorgamiento;
            $prima_mensual->Tarifa = $extraprimado->Tarifa;
            $prima_mensual->PorcentajeEP = $extraprimado->PorcentajeEP;
            $prima_mensual->PagoEP = $extraprimado->PagoEP;
            $prima_mensual->DesempleoDetalle = $detalle->Id;
            $prima_mensual->save();
        } */





        //session(['MontoCartera' => 0]);
        alert()->success('El Registro de cobro ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        // }
        return back();
    }

    public function cancelar_pago(Request $request)
    {
        try {
            VidaCarteraTemp::where('PolizaVida', '=', $request->PolizaVida)->delete();

            VidaCartera::where('PolizaVida', '=', $request->PolizaVida)->where('User', auth()->user()->id)->where('PolizaVidaDetalle', null)->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }
        alert()->success('El cobro se ha eliminado correctamente');
        return redirect('polizas/vida/' . $request->PolizaVida . '?tab=2');
    }

    public function recibo_pago($id, Request $request)
    {
        //try {
        $detalle = VidaDetalle::findOrFail($id);
        $poliza_vida = Vida::findOrFail($detalle->PolizaVida);

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        // Actualizar campos del detalle
        $detalle->SaldoA = $request->SaldoA;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->Referencia = $request->Referencia;
        $detalle->Anexo = $request->Anexo;
        $detalle->NumeroCorrelativo = $request->NumeroCorrelativo;

        if (!$detalle->update()) {
            throw new \Exception("Error al actualizar el detalle de la póliza");
        }

        $recibo_historial = $this->save_recibo($detalle, $poliza_vida);

        if (!$recibo_historial) {
            throw new \Exception("Error al guardar el historial del recibo");
        }

        $configuracion = ConfiguracionRecibo::first();

        if (!$configuracion) {
            throw new \Exception("No se encontró la configuración de recibos");
        }

        $pdf = \PDF::loadView('polizas.vida.recibo', compact('configuracion', 'recibo_historial', 'detalle', 'meses', 'poliza_vida'))
            ->setWarnings(false)
            ->setPaper('letter');

        return $pdf->stream('Recibo.pdf');
        // } catch (\Exception $e) {
        //     // Mostrar información detallada del error
        //     alert()->success('Erro al registrar el recibo');
        //     //return back();
        //     dd([
        //         'error' => $e->getMessage(),
        //         'file' => $e->getFile(),
        //         'line' => $e->getLine(),
        //         'request_data' => $request->all(),
        //         'detalle_id' => $id,
        //         'detalle_data' => isset($detalle) ? $detalle->toArray() : null
        //     ]);
        // }
    }


    public function save_recibo($detalle, $poliza_vida)
    {
        //      dd($detalle);
        $recibo_historial = new VidaHistorialRecibo();
        $recibo_historial->PolizaVidaDetalle = $detalle->Id;
        $recibo_historial->ImpresionRecibo = $detalle->ImpresionRecibo; //Carbon::now();
        $recibo_historial->NombreCliente = $poliza_vida->cliente->Nombre;
        $recibo_historial->NitCliente = $poliza_vida->cliente->Nit;
        $recibo_historial->DireccionResidencia = $poliza_vida->cliente->DireccionResidencia ?? '(vacio)';
        $recibo_historial->Departamento = $poliza_vida->cliente->distrito->municipio->departamento->Nombre;
        $recibo_historial->Municipio = $poliza_vida->cliente->distrito->municipio->Nombre;
        $recibo_historial->NumeroRecibo = $detalle->NumeroRecibo;
        $recibo_historial->CompaniaAseguradora = $poliza_vida->aseguradora->Nombre;
        $recibo_historial->ProductoSeguros = $poliza_vida->planes->productos->Nombre;
        $recibo_historial->NumeroPoliza = $poliza_vida->NumeroPoliza;
        $recibo_historial->VigenciaDesde = $poliza_vida->VigenciaDesde;
        $recibo_historial->VigenciaHasta = $poliza_vida->VigenciaHasta;
        $recibo_historial->FechaInicio = $detalle->FechaInicio;
        $recibo_historial->FechaFin = $detalle->FechaFinal;
        $recibo_historial->Anexo = $detalle->Anexo;
        $recibo_historial->Referencia = $detalle->Referencia;
        $recibo_historial->FacturaNombre = $poliza_vida->cliente->Nombre;
        $recibo_historial->MontoCartera = $detalle->MontoCartera;
        $recibo_historial->PrimaCalculada = $detalle->PrimaCalculada;
        $recibo_historial->ExtraPrima = $detalle->ExtraPrima;
        $recibo_historial->Descuento = $detalle->Descuento ?? 0;
        $recibo_historial->PordentajeDescuento = $poliza_vida->Descuento;
        $recibo_historial->PrimaDescontada = $detalle->PrimaDescontada;
        $recibo_historial->ValorCCF = $detalle->ValorCCF;
        $recibo_historial->TotalAPagar = $detalle->APagar;
        $recibo_historial->TasaComision = $poliza_vida->TasaComision ?? 0;
        $recibo_historial->Comision = $detalle->Comision;
        $recibo_historial->IvaSobreComision = $detalle->IvaSobreComision;
        $recibo_historial->SubTotalComision =  $detalle->IvaSobreComision + $detalle->Comision;
        $recibo_historial->Retencion = $detalle->Retencion;
        $recibo_historial->ValorCCF = $detalle->ValorCCF;
        $recibo_historial->FechaVencimiento = $detalle->FechaInicio;
        $recibo_historial->NumeroCorrelativo = $detalle->NumeroCorrelativo ?? '01';
        $recibo_historial->Cuota = '01/01';
        $recibo_historial->Otros = $detalle->Otros ?? 0;

        $recibo_historial->Usuario = auth()->user()->id;

        $recibo_historial->save();
        return $recibo_historial;
    }



    public function validarDocumento($documento, $tipo)
    {
        if ($tipo == "dui") {
            // Define las reglas de validación para el formato 000000000
            $reglaFormato = '/^\d{9}$/';

            return preg_match($reglaFormato, $documento) === 1;
        } else if ($tipo == "nit") {
            // Define las reglas de validación para el formato 000000000
            $reglaFormato = '/^\d{14}$/';

            return preg_match($reglaFormato, $documento) === 1;
        }
    }


    public function get_no_valido($id)
    {
        try {
            $poliza_vida = Vida::findOrFail($id);

            $count = VidaCarteraTemp::where('PolizaVida', $id)
                //->where('EdadDesembloso', '>', $poliza_vida->EdadMaximaInscripcion) EdadTerminacion
                ->where('EdadDesembloso', '>', $poliza_vida->EdadMaximaInscripcion)
                ->where('NoValido', 0)
                ->count();



            return response()->json([
                'success' => true,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            // Retornar error en caso de excepción
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500); // Código de estado HTTP 500 para errores del servidor
        }
    }

    public function agregar_no_valido($id)
    {
        try {

            $temp = VidaCarteraTemp::findOrFail($id);

            // Alternar el valor de NoValido entre 0 y 1
            $temp->NoValido = $temp->NoValido == 0 ? 1 : 0;
            $temp->save();

            // Retornar éxito
            return response()->json([
                'success' => true,
                'message' => 'Estado de NoValido actualizado correctamente.',
            ]);
        } catch (\Exception $e) {
            // Retornar error en caso de excepción
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function store_poliza(Request $request, $id)
    {
        $mes = $request->MesActual; // El formato 'm' devuelve el mes con ceros iniciales (por ejemplo, "02")
        $anio = $request->AxoActual;

        //$desempleo = Desempleo::findOrFail($id);

        // eliminando datos de la cartera si existieran
        VidaCartera::where('Axo', $anio)->where('Mes', $mes + 0)->where('PolizaVida', $id)->delete();


        $tempData = VidaCarteraTemp::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            //->where('User', auth()->user()->id)
            ->where('NoValido', 0)
            ->where('PolizaVida', $id)
            //->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion)
            ->get();

        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {
            try {
                $poliza = new VidaCartera();
                $poliza->PolizaVida = $tempRecord->PolizaVida ?? null;
                $poliza->Nit = $tempRecord->Nit ?? null;
                $poliza->Dui = $tempRecord->Dui ?? null;
                $poliza->Pasaporte = $tempRecord->Pasaporte ?? null;
                $poliza->Nacionalidad = $tempRecord->Nacionalidad ?? null;
                $poliza->FechaNacimiento = $tempRecord->FechaNacimiento ?? null;
                $poliza->TipoPersona = $tempRecord->TipoPersona ?? null;
                $poliza->PrimerApellido = $tempRecord->PrimerApellido ?? null;
                $poliza->SegundoApellido = $tempRecord->SegundoApellido ?? null;
                $poliza->ApellidoCasada = $tempRecord->ApellidoCasada ?? null;
                $poliza->PrimerNombre = $tempRecord->PrimerNombre ?? null;
                $poliza->SegundoNombre = $tempRecord->SegundoNombre ?? null;
                $poliza->Sexo = $tempRecord->Sexo ?? null;
                $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento ?? null;
                $poliza->FechaVencimiento = $tempRecord->FechaVencimiento ?? null;
                $poliza->NumeroReferencia = $tempRecord->NumeroReferencia ?? null;
                $poliza->SumaAsegurada = $tempRecord->SumaAsegurada ?? null;
                $poliza->User = $tempRecord->User;
                $poliza->Axo = $tempRecord->Axo ?? null;
                $poliza->Mes = $tempRecord->Mes ?? null;
                $poliza->FechaInicio = $tempRecord->FechaInicio ?? null;
                $poliza->FechaFinal = $tempRecord->FechaFinal ?? null;
                $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate ?? null;
                $poliza->FechaOtorgamientoDate = $tempRecord->FechaOtorgamientoDate ?? null;
                $poliza->Edad = $tempRecord->Edad ?? null;
                $poliza->EdadDesembloso = $tempRecord->EdadDesembloso ?? null;
                $poliza->PolizaVidaTipoCartera = $tempRecord->PolizaVidaTipoCartera ?? null;
                $poliza->Tasa = $tempRecord->Tasa ?? null;

                $poliza->save();
            } catch (\Exception $e) {
                // Captura errores y los guarda en el log
                Log::error("Error al insertar en poliza_vida_cartera: " . $e->getMessage(), [
                    'NumeroReferencia' => $tempRecord->NumeroReferencia,
                    'Usuario' => auth()->user()->id ?? 'N/A',
                    'Datos' => $tempRecord
                ]);
            }
        }

        // eliminando datos de la cartera temporal
        VidaCarteraTemp::where('Axo', $anio)->where('Mes', $mes + 0)->where('PolizaVida', $id)->delete();

        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/vida/' . $id . '?tab=2');
    }

    public function update_extraprimado(Request $request)
    {
        $extra_primado = PolizaVidaExtraPrimados::findOrFail($request->IdExtraPrima);
        // dd($extra_primado);
        $extra_primado->PorcentajeEP = $request->PorcentajeEP;
        // $extra_primado->PagoEP = $request->PagoEP;
        $extra_primado->update();
        alert()->success('El registro de poliza ha sido modificado correctamente');
        return redirect('polizas/vida/' . $extra_primado->PolizaVida . '?tab=5');
    }

    public function extraprimados_excluidos($id)
    {
        return Excel::download(new ExtraPrimadosExcluidosExport($id), 'creditos_extraprimados.xlsx');
    }

    public function store_extraprimado(Request $request)
    {
        try {
            $cliente = new PolizaVidaExtraPrimados();
            $cliente->NumeroReferencia = $request->NumeroReferencia;
            $cliente->PolizaVida = $request->PolizaVida;
            $cliente->Nombre = $request->Nombre;
            $cliente->FechaOtorgamiento = $request->FechaOtorgamiento;
            $cliente->MontoOtorgamiento = $request->MontoOtorgamiento;
            $cliente->PorcentajeEP = $request->PorcentajeEP;
            $cliente->Dui = $request->Dui;
            $cliente->save();

            alert()->success('Extraprimado agregado correctamente.');
            return redirect('polizas/vida/' . $request->PolizaVida . '?tab=5');
        } catch (\Exception $e) {
            // Log del error para depuración
            Log::error('Error al guardar extraprimado: ' . $e->getMessage());

            // Mensaje de error para el usuario
            alert()->error('Error al guardar el registro (verificar si el registro ya fue agregado anteriormente).')->persistent('Ok');
            return redirect()->back()->withInput();
        }
    }

    public function get_extraprimado($id, $dui)
    {
        $cliente = VidaCartera::select(
            'poliza_vida_cartera.Id',
            DB::raw("TRIM(CONCAT(
                    IFNULL(poliza_vida_cartera.PrimerNombre, ''),
                    IF(IFNULL(poliza_vida_cartera.SegundoNombre, '') != '', CONCAT(' ', poliza_vida_cartera.SegundoNombre), ''),
                    IF(IFNULL(poliza_vida_cartera.PrimerApellido, '') != '', CONCAT(' ', poliza_vida_cartera.PrimerApellido), ''),
                    IF(IFNULL(poliza_vida_cartera.SegundoApellido, '') != '', CONCAT(' ', poliza_vida_cartera.SegundoApellido), ''),
                    IF(IFNULL(poliza_vida_cartera.ApellidoCasada, '') != '', CONCAT(' ', poliza_vida_cartera.ApellidoCasada), '')
                )) as Nombre"),
            'poliza_vida_cartera.Dui',
            'poliza_vida_cartera.NumeroReferencia',
            'poliza_vida_cartera.SumaAsegurada',
            'poliza_vida_cartera.FechaOtorgamiento',

        )
            ->where('PolizaVida', $id)->where('Dui', $dui)->first();

        return response()->json($cliente);
    }

    public function get_recibo($id, $exportar)
    {
        if (!isset($exportar)) {
            $exportar = 1;
        }
        //dd($exportar);
        $detalle = VidaDetalle::findOrFail($id);

        $vida = Vida::findOrFail($detalle->PolizaVida);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = VidaHistorialRecibo::where('PolizaDesempleoDetalle', $id)->orderBy('id', 'desc')->first();
        //  $calculo = $this->monto($desempleo, $detalle);
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($detalle, $vida);
            //dd("insert");
        }

        /*  if ($exportar == 2) {
            return Excel::download(new DesempleoReciboExport($id), 'Recibo.xlsx');
            //return view('polizas.desempleo.recibo', compact('recibo_historial','detalle', 'desempleo', 'meses','exportar'));
        }*/

        $configuracion = ConfiguracionRecibo::first();
        $pdf = \PDF::loadView('polizas.vida.recibo', compact('configuracion', 'recibo_historial', 'detalle', 'vida', 'meses', 'exportar'))->setWarnings(false)->setPaper('letter');
        //  dd($detalle);
        return $pdf->stream('Recibos.pdf');
    }

    public function get_recibo_edit($id)
    {
        $detalle = VidaDetalle::findOrFail($id);
        $vida = Vida::findOrFail($detalle->PolizaVida);
        $recibo_historial = VidaHistorialRecibo::where('PolizaVidaDetalle', $id)->orderBy('id', 'desc')->first();
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($detalle, $vida);
            //dd("insert");
        }
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = VidaHistorialRecibo::where('PolizaDesempleoDetalle', $id)->orderBy('id', 'desc')->first();
        //dd($recibo_historial);
        $configuracion = ConfiguracionRecibo::first();

        return view('polizas.vida.recibo_edit', compact('configuracion', 'recibo_historial', 'meses'));
    }

    public function get_recibo_update(Request $request)
    {
        //modificación de ultimo recibo
        $id = $request->id_desempleo_detalle;
        $detalle = VidaDetalle::findOrFail($id);

        $vida = Vida::findOrFail($detalle->PolizaVida);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $impresion_recibo = $request->AxoImpresionRecibo . '-' . $request->MesImpresionRecibo . '-' . $request->DiaImpresionRecibo;

        $recibo_historial = new VidaHistorialRecibo();
        $recibo_historial->PolizaVidaDetalle = $id;
        //este valor cambia por eso no se manda al metodo de save_recibo
        $recibo_historial->ImpresionRecibo = Carbon::parse($impresion_recibo);
        $recibo_historial->NombreCliente = $request->NombreCliente;
        $recibo_historial->NitCliente = $request->NitCliente;
        $recibo_historial->DireccionResidencia = $request->DireccionResidencia;
        $recibo_historial->Departamento = $request->Departamento;
        $recibo_historial->Municipio = $request->Municipio;
        $recibo_historial->NumeroRecibo = $request->NumeroRecibo;
        $recibo_historial->CompaniaAseguradora = $request->CompaniaAseguradora;
        $recibo_historial->ProductoSeguros = $request->ProductoSeguros;
        $recibo_historial->NumeroPoliza = $request->NumeroPoliza;
        $recibo_historial->VigenciaDesde = $request->VigenciaDesde;
        $recibo_historial->VigenciaHasta = $request->VigenciaHasta;
        $recibo_historial->FechaInicio = $request->FechaInicio;
        $recibo_historial->FechaFin = $request->FechaFin;
        $recibo_historial->Anexo = $request->Anexo;
        $recibo_historial->Referencia = $request->Referencia;
        $recibo_historial->FacturaNombre = $request->FacturaNombre;
        $recibo_historial->MontoCartera = $request->MontoCartera;
        $recibo_historial->PrimaCalculada = $request->PrimaCalculada;
        $recibo_historial->ExtraPrima = $request->ExtraPrima;
        $recibo_historial->Descuento = $request->Descuento;
        $recibo_historial->PordentajeDescuento = $request->PordentajeDescuento;
        $recibo_historial->PrimaDescontada = $request->PrimaDescontada;
        $recibo_historial->ValorCCF = $request->ValorCCF;
        $recibo_historial->TotalAPagar = $request->TotalAPagar;
        $recibo_historial->TasaComision = $request->TasaComision;
        $recibo_historial->Comision = $request->Comision;
        $recibo_historial->IvaSobreComision = $request->IvaSobreComision;
        $recibo_historial->SubTotalComision = $request->SubTotalComision;
        $recibo_historial->Retencion = $request->Retencion;
        $recibo_historial->ValorCCF = $request->ValorCCF;
        $recibo_historial->FechaVencimiento = $request->FechaVencimiento ?? $detalle->FechaInicio;
        $recibo_historial->NumeroCorrelativo = $request->NumeroCorrelativo ??  '01';
        $recibo_historial->Cuota = $request->Cuota ?? '01/01';
        $recibo_historial->Otros = $detalle->Otros ?? 0;

        $recibo_historial->Usuario = auth()->user()->id;

        $recibo_historial->save();
        //dd("insert");
        alert()->success('Actualizacion de Recibo Exitoso');
        return redirect('polizas/vida/' . $vida->Id . '/edit');
    }


    public function get_pago($id)
    {
        return VidaDetalle::findOrFail($id);
    }

    public function edit_pago(Request $request)
    {

        $detalle = VidaDetalle::findOrFail($request->Id);
        //dd($detalle);

        $vida = Vida::findOrFail($detalle->PolizaVida);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        // dd($vida);
        if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
            $detalle->SaldoA = $request->SaldoA;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->Comentario = $request->Comentario;
            $detalle->update();

            $recibo_historial = $this->save_recibo($detalle, $vida);
            $configuracion = ConfiguracionRecibo::first();
            $pdf = \PDF::loadView('polizas.vida.recibo', compact('configuracion', 'recibo_historial', 'detalle', 'vida', 'meses'))->setWarnings(false)->setPaper('letter');
            return $pdf->stream('Recibo.pdf');

            return back();
        } else {

            //dd($request->EnvioCartera .' 00:00:00');
            if ($request->EnvioCartera) {
                $detalle->EnvioCartera = $request->EnvioCartera;
            }
            if ($request->EnvioPago) {
                $detalle->EnvioPago = $request->EnvioPago;
            }
            if ($request->PagoAplicado) {
                $detalle->PagoAplicado = $request->PagoAplicado;
            }
            $detalle->Comentario = $request->Comentario;

            /*$detalle->EnvioPago = $request->EnvioPago;
            $detalle->PagoAplicado = $request->PagoAplicado;*/
            $detalle->update();
        }

        $time = Carbon::now('America/El_Salvador');
        $comen = new Comentario();
        $comen->Comentario = $request->Comentario;
        $comen->Activo = 1;
        $comen->DetalleVida = $detalle->Id;
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Vida = $detalle->PolizaVida;
        $comen->save();

        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function anular_pago($id)
    {
        $detalle = VidaDetalle::findOrFail($id);
        $detalle->Activo = 0;
        $detalle->update();
        //recibo anulado
        VidaHistorialRecibo::where('PolizaVidaDetalle', $id)->update(['Activo' => 0]);

        VidaCartera::where('PolizaVidaDetalle', $id)->delete();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function delete_pago($id)
    {
        $detalle = VidaDetalle::findOrFail($id);

        // recibo eliminado
        VidaHistorialRecibo::where('PolizaVidaDetalle', $id)->delete();

        VidaCartera::where('PolizaVidaDetalle', $id)->delete();
        $detalle->delete();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function exportar_excel(Request $request)
    {
        $vida = $request->Vida;
        $detalle = $request->VidaDetalle;
        $cartera = VidaCartera::where('PolizaVidaDetalle', $detalle)->where('PolizaVida', $vida)->get();

        return Excel::download(new VidaExport($cartera), 'Cartera.xlsx');
        //  dd($cartera->take(25),$request->Deuda,$request->DeudaDetalle);
    }

    public function exportar_excel_fede(Request $request)
    {
        $vida = $request->Vida;
        $detalle = $request->VidaDetalle;
        $cartera = VidaCartera::where('PolizaVidaDetalle', $detalle)->where('PolizaVida', $vida)->get();

        return Excel::download(new VidaFedeExport($cartera), 'Cartera.xlsx');
        //  dd($cartera->take(25),$request->Deuda,$request->DeudaDetalle);
    }
    public function registros_edad_maxima($id)
    {
        return Excel::download(new EdadMaximaExport($id), 'creditos_edad_maxima.xlsx');
    }


    public function registros_responsabilidad_maxima($id)
    {
        return Excel::download(new EdadInscripcionExport($id), 'creditos_responsabilidad_maxima.xlsx');
    }

    public function registros_responsabilidad_terminacion($id)
    {
        return Excel::download(new EdadTerminacionExport($id), 'registros_responsabilidad_terminacion.xlsx');
    }

    public function exportar_nuevos_registros($id)
    {
        return Excel::download(new NuevosRegistrosExport($id), 'nuevos_registros.xlsx');
    }

    public function exportar_registros_eliminados($id)
    {
        return Excel::download(new RegistrosEliminadosExport($id), 'registros_eliminados.xlsx');
    }

    public function exportar_registros_rehabilitados($id)
    {
        return Excel::download(new RegistrosRehabilitadosExport($id), 'registros_rehabilitados.xlsx');
    }
}
