<?php

namespace App\Http\Controllers\suscripcion;

use App\Exports\suscripcion\SuscripcionesExport;
use App\Http\Controllers\Controller;
use App\Imports\SuscripcionImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\polizas\Deuda;
use App\Models\polizas\Vida;
use App\Models\suscripcion\Comentarios;
use App\Models\suscripcion\Compania;
use App\Models\suscripcion\EstadoCaso;
use App\Models\suscripcion\FechasFeriadas;
use App\Models\suscripcion\Ocupacion;
use App\Models\suscripcion\OrdenMedica;
use App\Models\suscripcion\Padecimiento;
use App\Models\suscripcion\Reproceso;
use App\Models\suscripcion\ResumenGestion;
use App\Models\suscripcion\Suscripcion;
use App\Models\suscripcion\SuscripcionTemp;
use App\Models\suscripcion\TipoCliente;
use App\Models\suscripcion\TipoCredito;
use App\Models\suscripcion\TipoImc;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SuscripcionController extends Controller
{

    public function index(Request $request)
    {
        $fecha_final = $request->FechaFinal ?? date('Y-12-31');
        $fecha_inicio = $request->FechaInicio ?? date('Y-01-01');

        $documento = $request->documento ?? null;

        $registroId = $request->Id ?? 1;

        $exportar = $request->Exportar ?? null;

        if ($request->filled('Documento')) {
            $documento =  $request->Documento;
            $suscripciones = Suscripcion::where('Dui', $documento)->get();
        } else {
            $suscripciones = Suscripcion::with([
                'gestor',
                'compania',
                'contratante',
                'polizaDeuda',
                'polizaVida',
                'ocupacion',
                'tipoCliente',
                'tipoCredito',
                'tipoImc',
                'tipoOrdenMedica',
                'estadoCaso',
                'resumenGestion',
                'comentarios'
            ])
                ->whereBetween(DB::raw('DATE(FechaIngreso)'), [$fecha_inicio, $fecha_final])
                ->orderBy('suscripcion.Id', 'desc')
                ->get();
        }

        if ($exportar) {
            return Excel::download(new SuscripcionesExport($suscripciones), 'suscripciones.xlsx');
        }


        $recordIndex = 1;
        if ($request->Id) {
            foreach ($suscripciones as $index => $suscripcion) {
                if ($suscripcion->Id == $registroId) {
                    $recordIndex = $index + 1; // +1 porque los índices de los arrays comienzan en 0
                    break;
                }
            }
        }

        return view('suscripciones.suscripcion.index', compact('suscripciones', 'fecha_inicio', 'fecha_final', 'documento', 'recordIndex'));
    }


    public function data($fechaInicio, $fechaFinal)
    {
        $suscripciones = Suscripcion::leftJoin('ejecutivo', 'ejecutivo.Id', '=', 'suscripcion.GestorId')
            ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'suscripcion.CompaniaId')
            ->leftJoin('cliente', 'cliente.Id', '=', 'suscripcion.ContratanteId')
            ->leftJoin('poliza_deuda', 'poliza_deuda.Id', '=', 'suscripcion.PolizaDeuda')
            ->leftJoin('poliza_vida', 'poliza_vida.Id', '=', 'suscripcion.PolizaVida')
            ->leftJoin('sus_tipo_cliente', 'sus_tipo_cliente.Id', '=', 'suscripcion.TipoClienteId')
            ->leftJoin('sus_orden_medica', 'sus_orden_medica.Id', '=', 'suscripcion.TipoOrdenMedicaId')
            ->leftJoin('sus_estado_caso', 'sus_estado_caso.Id', '=', 'suscripcion.EstadoId')
            ->leftJoin('sus_resumen_gestion', 'sus_resumen_gestion.Id', '=', 'suscripcion.ResumenGestion')
            ->whereBetween(DB::raw('DATE(suscripcion.FechaIngreso)'), [$fechaInicio, $fechaFinal])
            ->orderBy('suscripcion.Id', 'desc')
            ->select(
                'suscripcion.Id',
                'suscripcion.NumeroTarea',
                'suscripcion.FechaIngreso',
                'ejecutivo.Nombre as Ejecutivo',
                'aseguradora.Nombre as Aseguradora',
                'cliente.Nombre as Contratante',
                'poliza_deuda.NumeroPoliza as PolizaDeuda',
                'poliza_vida.NumeroPoliza as PolizaVida',
                'suscripcion.Asegurado',
                'suscripcion.Dui',
                'suscripcion.Edad',
                'suscripcion.Genero',
                'suscripcion.SumaAseguradaDeuda',
                'suscripcion.SumaAseguradaVida',
                'sus_tipo_cliente.Nombre as TipoCliente',
                DB::raw('CONCAT(suscripcion.Estatura, " Mts") as Estatura'),
                DB::raw('CONCAT(suscripcion.Peso, " Lb") as Peso'),
                DB::raw('FORMAT(suscripcion.Imc, 2) as Imc'),
                'suscripcion.Padecimiento',
                'sus_orden_medica.Nombre as TipoOrdenMedica',
                'sus_estado_caso.Nombre as EstadoCaso',
                'sus_resumen_gestion.Nombre as ResumenGestion',
                'sus_resumen_gestion.Color',
                'suscripcion.FechaReportadoCia',
                'suscripcion.TareasEvaSisa',
                'suscripcion.ValorExtraPrima',
                'suscripcion.FechaResolucion',
                'suscripcion.FechaEnvioResoCliente',
                'suscripcion.DiasProcesamientoResolucion',
            );

        return DataTables::of($suscripciones)
            ->editColumn('FechaIngreso', function ($row) {
                return $row->FechaIngreso ? $row->FechaIngreso->format('d/m/Y') : '';
            })
            ->editColumn('Genero', function ($row) {
                return $row->Genero == 1 ? 'F' : ($row->Genero == 2 ? 'M' : '');
            })
            ->editColumn('SumaAseguradaDeuda', function ($row) {
                return $row->SumaAseguradaDeuda !== null && $row->SumaAseguradaDeuda > 0
                    ? number_format($row->SumaAseguradaDeuda, 2)
                    : '';
            })
            ->editColumn('SumaAseguradaVida', function ($row) {
                return $row->SumaAseguradaVida !== null && $row->SumaAseguradaVida > 0
                    ? number_format($row->SumaAseguradaVida, 2)
                    : '';
            })
            ->editColumn('FechaReportadoCia', function ($row) {
                return !empty($row->FechaReportadoCia)
                    ? Carbon::parse($row->FechaReportadoCia)->format('d/m/Y')
                    : '';
            })
            ->editColumn('ValorExtraPrima', function ($row) {
                return $row->ValorExtraPrima !== null && $row->ValorExtraPrima > 0
                    ? number_format($row->ValorExtraPrima, 2)
                    : '';
            })
            ->editColumn('FechaResolucion', function ($row) {
                return !empty($row->FechaResolucion)
                    ? Carbon::parse($row->FechaResolucion)->format('d/m/Y')
                    : '';
            })
            ->editColumn('FechaEnvioResoCliente', function ($row) {
                return !empty($row->FechaEnvioResoCliente)
                    ? Carbon::parse($row->FechaEnvioResoCliente)->format('d/m/Y')
                    : '';
            })
            ->editColumn('Padecimiento', function ($row) {

                // Cargar modelo real (porque DataTables usa stdClass)
                $suscripcion = Suscripcion::with('padecimientos')
                    ->find($row->Id);

                // Si tiene padecimientos en la tabla intermedia
                if ($suscripcion && $suscripcion->padecimientos->count() > 0) {
                    return $suscripcion->padecimientos
                        ->pluck('Nombre')
                        ->implode(', ');
                }

                // Fallback al campo original
                return $row->Padecimiento ?? '';
            })
            ->addColumn('acciones', function ($row) use ($fechaInicio, $fechaFinal) {

                $id = $row->Id;

                return '
                <a href="' . url("suscripciones/{$id}/edit") .
                    '?FechaInicio=' . $fechaInicio .
                    '&FechaFinal=' . $fechaFinal . '"
                class="btn btn-primary">
                    <i class="fa fa-pencil fa-lg"></i>
                </a>

                <a href="#" class="btn btn-danger" onclick="shoModalDelete(' . $id . ')">
                    <i class="fa fa-trash fa-lg"></i>
                </a>

                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal-comentario" onclick="getComentarios(' . $id . ')">
                    <i class="fa fa-book fa-lg"></i>
                </a>
            ';
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }


    public function create()
    {
        $ocupaciones = Ocupacion::where('Activo', 1)->get();
        $tipo_creditos = TipoCredito::where('Activo', 1)->get();
        $companias = Compania::where('Activo', 1)->get();
        $tipo_clientes = TipoCliente::where('Activo', 1)->get();
        $tipo_orden = OrdenMedica::where('Activo', 1)->get();
        $estados = EstadoCaso::where('Activo', 1)->get();
        $reprocesos = Reproceso::where('Activo', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', 1)->get();
        $clientes = Cliente::where('activo', 1)->get();
        $polizas_deuda = Deuda::where('Activo', 1)->get();
        $polizas_vida = Vida::where('Activo', 1)->get();

        $tipos_imc = TipoImc::where('Activo', 1)->get();
        $resumen_gestion = ResumenGestion::get();


        $ultimo = Suscripcion::selectRaw('MAX(CAST(SUBSTRING(NumeroTarea, LOCATE("TS-", NumeroTarea) + 3) AS UNSIGNED)) as ultimo')
            ->whereRaw('LEFT(NumeroTarea, 2) = ?', [substr(date('Y'), -2)])
            ->value('ultimo');

        $nuevoCorrelativo = $ultimo ? $ultimo + 1 : 1;
        $nuevaTarea = substr(date('Y'), -2) . 'TS-' . $nuevoCorrelativo;

        $padecimientos = Padecimiento::where('Activo', 1)->get();

        //observaciones 22-5-25
        $aseguradoras = Aseguradora::where('Activo', 1)->get();


        return view('suscripciones.suscripcion.create', compact(
            'aseguradoras',
            'reprocesos',
            'tipo_clientes',
            'tipo_orden',
            'estados',
            'ejecutivos',
            'clientes',
            'polizas_deuda',
            'polizas_vida',
            'tipos_imc',
            'resumen_gestion',
            'tipo_creditos',
            'ocupaciones',
            'nuevaTarea',
            'padecimientos'
        ));
    }

    public function store(Request $request)
    {

        $request->validate([
            'FechaIngreso'               => 'required|date',
            'Gestor'                     => 'integer|exists:ejecutivo,Id',
            'CompaniaId'                 => 'nullable|integer|exists:aseguradora,Id',
            'ContratanteId'              => 'nullable|integer|exists:cliente,Id',
            'PolizaDeuda'                => 'nullable|integer|exists:poliza_deuda,Id',
            'PolizaVida'                 => 'nullable|integer|exists:poliza_vida,Id',
            'Asegurado'                  => 'required|string|max:100',
            'Dui'                        => 'nullable|string',
            'Edad'                       => 'nullable|integer|min:0|max:120',
            'Genero'                     => 'nullable|in:1,2',
            'SumaAseguradaDeuda'         => 'nullable|numeric|min:0',
            'SumaAseguradaVida'          => 'nullable|numeric|min:0',
            'TipoClienteId'              => 'nullable|integer|exists:sus_tipo_cliente,Id',
            'Peso'                       => 'nullable|numeric|min:0',
            'Estatura'                   => 'nullable|numeric|min:0',
            'Imc'                        => 'nullable|numeric|min:0',
            'TipoIMCId'                  => 'nullable|integer|exists:sus_tipo_imc,Id',

            // VALIDACIÓN ACTUALIZADA Gestor
            'Padecimiento'               => 'nullable|array',
            'Padecimiento.*'             => 'integer|exists:sus_padecimientos,Id',

            'TipoOrdenMedicaId'          => 'nullable|integer|exists:sus_orden_medica,Id',
            'EstadoId'                   => 'nullable|integer|exists:sus_estado_caso,Id',
            'ResumenGestion'             => 'nullable|integer|exists:sus_resumen_gestion,Id',
            'FechaReportadoCia'          => 'nullable|date',
            'TareasEvaSisa'              => 'nullable|string|max:255',
            'ResolucionFinal'            => 'nullable|string|max:1000',
            'ValorExtraPrima'            => 'nullable|numeric|min:0',
            'Comentarios'                => 'nullable|string|max:3000',
            'OcupacionId'                => 'nullable|integer|exists:sus_ocupacion,Id',
            'ReprocesoId'                => 'nullable|integer|exists:sus_reproceso,Id',
            'TipoCreditoId'              => 'nullable|integer|exists:sus_tipo_credito,Id',
            'FechaEntregaDocsCompletos'  => 'nullable|date',
            'DiasCompletarInfoCliente'   => 'nullable|integer',
            'TrabajadoEfectuadoDiaHabil' => 'nullable|integer',
            'FechaCierreGestion'         => 'nullable|date',
            'FechaEnvioCorreccion'       => 'nullable|date',
            'FechaResolucion'            => 'nullable|date|before_or_equal:FechaEnvioResoCliente',
            'FechaEnvioResoCliente'      => 'nullable|date|after_or_equal:FechaResolucion',

        ], [
            'required'           => 'El campo :attribute es obligatorio.',
            'date'               => 'El campo :attribute debe ser una fecha válida.',
            'integer'            => 'El campo :attribute debe ser un número entero.',
            'numeric'            => 'El campo :attribute debe ser numérico.',
            'max'                => 'El campo :attribute no debe ser mayor a :max caracteres.',
            'min'                => 'El campo :attribute debe ser al menos :min.',
            'in'                 => 'El valor seleccionado en :attribute no es válido.',
            'exists'             => 'El valor seleccionado en :attribute no existe.',
            'array'              => 'El campo :attribute debe ser una lista de opciones.',
            'Padecimiento.*.exists' => 'Uno de los padecimientos seleccionados no es válido.',
            'FechaResolucion.before_or_equal' => 'La fecha de recepción de resolución de CIA no puede ser mayor a la fecha de envió de resolución al cliente',
            'FechaEnvioResoCliente.after_or_equal' => 'La fecha de envió de resolución al cliente no puede ser menor a la fecha de recepción de resolución de CIA'
        ]);

        try {
            DB::beginTransaction();

            $ultimo = Suscripcion::selectRaw('MAX(CAST(SUBSTRING(NumeroTarea, LOCATE("TS-", NumeroTarea) + 3) AS UNSIGNED)) as ultimo')
                ->whereRaw('LEFT(NumeroTarea, 2) = ?', [substr(date('Y'), -2)])
                ->value('ultimo');

            $nuevoCorrelativo = $ultimo ? $ultimo + 1 : 1;
            $nuevaTarea = substr(date('Y'), -2) . 'TS-' . $nuevoCorrelativo;

            $suscripcion = new Suscripcion();
            $suscripcion->NumeroTarea = $request->NumeroTarea;
            $suscripcion->FechaIngreso = $request->FechaIngreso;
            $suscripcion->GestorId = $request->Gestor;
            $suscripcion->CompaniaId = $request->CompaniaId;
            $suscripcion->CategoriaSisa = $request->CategoriaSisa;
            $suscripcion->ContratanteId = $request->ContratanteId;
            $suscripcion->PolizaDeuda = $request->PolizaDeuda;
            $suscripcion->PolizaVida = $request->PolizaVida;
            $suscripcion->Asegurado = $request->Asegurado;
            $suscripcion->Dui = $request->Dui;
            $suscripcion->Edad = $request->Edad;
            $suscripcion->Genero = $request->Genero;
            $suscripcion->SumaAseguradaDeuda = $request->SumaAseguradaDeuda;
            $suscripcion->SumaAseguradaVida = $request->SumaAseguradaVida;
            $suscripcion->TipoClienteId = $request->TipoClienteId;
            $suscripcion->Peso = $request->Peso;
            $suscripcion->Estatura = $request->Estatura;
            $suscripcion->Imc = $request->Imc;
            $suscripcion->TipoIMCId = $request->TipoIMCId;
            $suscripcion->TipoOrdenMedicaId = $request->TipoOrdenMedicaId;
            $suscripcion->EstadoId = $request->EstadoId;
            $suscripcion->ReprocesoId = $request->ReprocesoId;
            $suscripcion->ResumenGestion = $request->ResumenGestion;
            $suscripcion->FechaReportadoCia = $request->FechaReportadoCia;
            $suscripcion->TareasEvaSisa = $request->TareasEvaSisa;
            $suscripcion->FechaResolucion = $request->FechaResolucion;
            $suscripcion->ResolucionFinal = $request->ResolucionFinal;
            $suscripcion->ValorExtraPrima = $request->ValorExtraPrima;
            $suscripcion->Activo = 1;
            $suscripcion->OcupacionId = $request->OcupacionId;
            $suscripcion->TipoCreditoId = $request->TipoCreditoId;
            $suscripcion->FechaEntregaDocsCompletos = $request->FechaEntregaDocsCompletos;
            $suscripcion->DiasCompletarInfoCliente = $request->DiasCompletarInfoCliente;
            $suscripcion->TrabajadoEfectuadoDiaHabil = $request->TrabajadoEfectuadoDiaHabil;
            $suscripcion->FechaCierreGestion = $request->FechaCierreGestion;
            $suscripcion->FechaEnvioResoCliente = $request->FechaEnvioResoCliente;
            $suscripcion->FechaEnvioCorreccion = $request->FechaEnvioCorreccion;
            $suscripcion->TotalDiasProceso = $request->TotalDiasProceso;

            if ($suscripcion->FechaResolucion != null && $suscripcion->FechaEnvioResoCliente != null) {
                $suscripcion->DiasProcesamientoResolucion = $this->calcularDiasHabiles($suscripcion->FechaResolucion,  $suscripcion->FechaEnvioResoCliente);
            }

            $suscripcion->save();


            // --- AGREGAR PADECIMIENTOS (Tabla Intermedia) ---
            if ($request->has('Padecimiento')) {
                // attach() toma el array de IDs del select múltiple
                $suscripcion->padecimientos()->attach($request->Padecimiento);
            }


            if ($request->Comentarios != "") {
                $comentario = new Comentarios();
                $comentario->SuscripcionId = $suscripcion->Id;
                $comentario->Usuario = $request->Gestor;
                $comentario->FechaCreacion = Carbon::now();
                $comentario->Activo = $request->Activo;
                $comentario->Comentario = $request->Comentarios;
                $comentario->save();
            }

            DB::commit();

            return redirect('suscripciones/' . $suscripcion->Id . '/edit?tab=1')->with('success', 'El registro ha sido creado correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            report($e); // Puedes también usar Log::error($e->getMessage());
            alert()->error('Ha ocurrido un error al guardar la suscripción.');
            return redirect()->back()->withInput();
        }
    }

    public function get_imc(Request $request)
    {
        $peso = $request->peso;
        $estatura = $request->estatura;

        $peso_kg = $peso / 2.2;
        $imc = $peso_kg / ($estatura * 2);

        if ($imc < 18.5) {
            $tipo_imc = 1;
        } elseif ($imc >= 18.5 && $imc < 24.9) {
            $tipo_imc = 2;
        } elseif ($imc > 25 && $imc < 29.9) {
            $tipo_imc = 3;
        } elseif ($imc >= 30 && $imc < 34.9) {
            $tipo_imc = 4;
        } elseif ($imc >= 35 && $imc < 39.9) {
            $tipo_imc = 5;
        } elseif ($imc >= 40 && $imc < 49.9) {
            $tipo_imc = 6;
        } else {
            $tipo_imc = 7;
        }

        $tipo = TipoImc::findOrFail($tipo_imc);

        $data  = [
            'imc' => round($imc, 2),
            'tipo_id' => $tipo_imc,
            'desc_tipo' => $tipo->Nombre,
        ];

        return response()->json(['data' => $data], 200);
    }


    public function show($id)
    {
        //
    }




    public function edit(Request $request, $id)
    {
        $tab = $request->tab ?? 1;

        $fechaInicio = $request->FechaInicio ?? date('Y-12-31');
        $fechaFinal = $request->FechaFinal ?? date('Y-01-01');

        $suscripcion = Suscripcion::findOrFail($id);
        $companias = Compania::where('Activo', 1)->get();
        $ocupaciones = Ocupacion::where('Activo', 1)->get();
        $tipo_clientes = TipoCliente::where('Activo', 1)->get();
        $tipo_creditos = TipoCredito::where('Activo', 1)->get();
        $tipo_orden = OrdenMedica::where('Activo', 1)->get();
        $estados = EstadoCaso::where('Activo', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', 1)->get();
        $clientes = Cliente::where('activo', 1)->get();
        $polizas_deuda = Deuda::where('activo', 1)->get();
        $polizas_vida = Vida::where('activo', 1)->get();
        $tipos_imc = TipoImc::where('Activo', 1)->get();
        $resumen_gestion = ResumenGestion::where('Activo', 1)->get();
        $reprocesos = Reproceso::where('Activo', 1)->get();

        //observaciones 22-5-25
        $aseguradoras = Aseguradora::where('Activo', 1)->get();

        $padecimientos = Padecimiento::where('Activo', 1)->get();
        $padecimientos_seleccionados = $suscripcion->padecimientos->pluck('Id')->toArray();

        return view('suscripciones.suscripcion.edit', compact(
            'reprocesos',
            'aseguradoras',
            'tipos_imc',
            'resumen_gestion',
            'polizas_vida',
            'polizas_deuda',
            'clientes',
            'ejecutivos',
            'companias',
            'ocupaciones',
            'tipo_clientes',
            'tipo_creditos',
            'tipo_orden',
            'suscripcion',
            'estados',
            'tab',
            'fechaInicio',
            'fechaFinal',
            'padecimientos',
            'padecimientos_seleccionados'
        ));
    }


    public function update(Request $request)
    {
        $request->validate([
            'FechaIngreso'         => 'required|date',
            'Gestor'               => 'nullable|integer|exists:ejecutivo,Id',
            'CompaniaId'           => 'nullable|integer|exists:aseguradora,Id',
            'ContratanteId'        => 'nullable|integer|exists:cliente,Id',
            'PolizaDeuda'          => 'nullable|integer|exists:poliza_deuda,Id',
            'PolizaVida'           => 'nullable|integer|exists:poliza_vida,Id',
            'Asegurado'            => 'required|string|max:100',
            'Dui'                  => 'nullable|string',
            'Edad'                 => 'nullable|integer|min:0|max:120',
            'Genero'               => 'nullable|in:1,2',
            'SumaAseguradaDeuda'   => 'nullable|numeric|min:0',
            'SumaAseguradaVida'    => 'nullable|numeric|min:0',
            'TipoClienteId'        => 'nullable|integer|exists:sus_tipo_cliente,Id',
            'Peso'                 => 'nullable|numeric|min:0',
            'Estatura'             => 'nullable|numeric|min:0',
            'Imc'                  => 'nullable|numeric|min:0',
            'TipoIMCId'            => 'nullable|integer|exists:sus_tipo_imc,Id',
            'Padecimiento'               => 'nullable|array',
            'Padecimiento.*'             => 'integer|exists:sus_padecimientos,Id',
            'TipoOrdenMedicaId'    => 'nullable|integer|exists:sus_orden_medica,Id',
            'EstadoId'             => 'nullable|integer|exists:sus_estado_caso,Id',
            'ReprocesoId'             => 'nullable|integer|exists:sus_reproceso,Id',
            'ResumenGestion'       => 'nullable|integer|exists:sus_resumen_gestion,Id',
            'FechaReportadoCia'    => 'nullable|date',
            'FechaEnvioCorreccion'    => 'nullable|date',
            'TareasEvaSisa'        => 'nullable|string|max:255',
            'ResolucionFinal'      => 'nullable|string|max:1000',
            'ValorExtraPrima'      => 'nullable|numeric|min:0',
            'Comentarios'          => 'nullable|string|max:3000',
            'OcupacionId'          => 'nullable|integer|exists:sus_ocupacion,Id',
            'TipoCreditoId'        => 'nullable|integer|exists:sus_tipo_credito,Id',
            'FechaEntregaDocsCompletos' => 'nullable|date',
            'DiasCompletarInfoCliente' => 'nullable|integer',
            'TrabajadoEfectuadoDiaHabil' => 'nullable|integer',
            'FechaCierreGestion'    => 'nullable|date',
            'FechaResolucion'    => 'nullable|date|before_or_equal:FechaEnvioResoCliente',
            'FechaEnvioResoCliente'    => 'nullable|date|after_or_equal:FechaResolucion',

        ], [
            'required'             => 'El campo :attribute es obligatorio.',
            'date'                 => 'El campo :attribute debe ser una fecha válida.',
            'integer'              => 'El campo :attribute debe ser un número entero.',
            'numeric'              => 'El campo :attribute debe ser numérico.',
            'max'                  => 'El campo :attribute no debe ser mayor a :max caracteres.',
            'min'                  => 'El campo :attribute debe ser al menos :min.',
            'in'                   => 'El valor seleccionado en :attribute no es válido.',
            'exists'               => 'El valor seleccionado en :attribute no existe.',
            'regex'                => 'El formato de :attribute no es válido.',
            'Padecimiento.*.exists' => 'Uno de los padecimientos seleccionados no es válido.',
            'FechaResolucion.before_or_equal' => 'La fecha de recepción de resolución de CIA no puede ser mayor a la fecha de envió de resolución al cliente',
            'FechaEnvioResoCliente.after_or_equal' => 'La fecha de envió de resolución al cliente no puede ser menor a la fecha de recepción de resolución de CIA'
        ]);



        $suscripcion = Suscripcion::findOrFail($request->Id);
        $suscripcion->FechaIngreso = $request->FechaIngreso;
        $suscripcion->GestorId = $request->Gestor ? $request->Gestor : null;
        $suscripcion->CompaniaId = $request->CompaniaId;
        $suscripcion->CategoriaSisa = $request->CategoriaSisa;
        $suscripcion->ContratanteId = $request->ContratanteId;
        $suscripcion->PolizaDeuda = $request->PolizaDeuda;
        $suscripcion->PolizaVida = $request->PolizaVida;
        $suscripcion->Asegurado = $request->Asegurado;
        $suscripcion->Dui = $request->Dui;
        $suscripcion->Edad = $request->Edad;
        $suscripcion->Genero = $request->Genero;
        $suscripcion->SumaAseguradaDeuda = $request->SumaAseguradaDeuda;
        $suscripcion->SumaAseguradaVida = $request->SumaAseguradaVida;
        $suscripcion->TipoClienteId = $request->TipoClienteId;
        $suscripcion->Peso = $request->Peso;
        $suscripcion->Estatura = $request->Estatura;
        $suscripcion->Imc = $request->Imc;
        $suscripcion->TipoIMCId = $request->TipoIMCId;
        $suscripcion->Padecimiento = $request->Padecimiento;
        $suscripcion->TipoOrdenMedicaId = $request->TipoOrdenMedicaId;
        if ($request->EstadoId != null) {
            $suscripcion->EstadoId = $request->EstadoId;
        }
        $suscripcion->ReprocesoId = $request->ReprocesoId;
        $suscripcion->ResumenGestion = $request->ResumenGestion;
        $suscripcion->FechaReportadoCia = $request->FechaReportadoCia;
        $suscripcion->TareasEvaSisa = $request->TareasEvaSisa;
        $suscripcion->FechaResolucion = $request->FechaResolucion;
        $suscripcion->ResolucionFinal = $request->ResolucionFinal;
        $suscripcion->ValorExtraPrima = $request->ValorExtraPrima;
        $suscripcion->OcupacionId = $request->OcupacionId;
        $suscripcion->TipoCreditoId = $request->TipoCreditoId;
        $suscripcion->FechaEntregaDocsCompletos = $request->FechaEntregaDocsCompletos;
        $suscripcion->DiasCompletarInfoCliente = $request->DiasCompletarInfoCliente;
        $suscripcion->TrabajadoEfectuadoDiaHabil = $request->TrabajadoEfectuadoDiaHabil;
        $suscripcion->FechaCierreGestion = $request->FechaCierreGestion;
        $suscripcion->FechaEnvioResoCliente = $request->FechaEnvioResoCliente;
        $suscripcion->FechaEnvioCorreccion = $request->FechaEnvioCorreccion;
        //$suscripcion->DiasProcesamientoResolucion = $request->DiasProcesamiento;


        if ($suscripcion->FechaResolucion != null && $suscripcion->FechaEnvioResoCliente != null) {
            $suscripcion->DiasProcesamientoResolucion = $this->calcularDiasHabiles($suscripcion->FechaResolucion,  $suscripcion->FechaEnvioResoCliente);
        }
        $suscripcion->update();



        if ($suscripcion->FechaReportadoCia != null && $suscripcion->FechaEntregaDocsCompletos != null) {
            $suscripcion->TrabajadoEfectuadoDiaHabil = $this->calcularDiasHabiles($suscripcion->FechaReportadoCia,  $suscripcion->FechaEntregaDocsCompletos);
        }
        $suscripcion->update();


        // Sincronizar padecimientos
        if ($request->has('Padecimiento')) {
            $suscripcion->padecimientos()->sync($request->Padecimiento);
        } else {
            // Si el usuario desmarcó todo, vaciamos la relación
            $suscripcion->padecimientos()->detach();
        }


        return redirect('suscripciones/' . $request->Id . '/edit?tab=1')->with('success', 'El registro ha sido modificado correctamente');
    }

    public function destroy($id)
    {
        //
        Comentarios::where('SuscripcionId', $id)->delete();
        Suscripcion::findOrFail($id)->delete();
        alert()->success('El registro ha sido eliminado correctamente');
        return back();
    }


    public function agregar_comentario(Request $request)
    {
        // Validación
        $request->validate([
            'SuscripcionId' => 'required|integer|exists:suscripcion,id',
            'Comentario'    => 'required|string|max:3000',
        ], [
            'SuscripcionId.required' => 'El campo Suscripción es obligatorio.',
            'SuscripcionId.integer'  => 'El campo Suscripción debe ser un número entero.',
            'SuscripcionId.exists'   => 'La suscripción seleccionada no existe en la base de datos.',
            'Comentario.required'    => 'Debe ingresar un comentario.',
            'Comentario.string'      => 'El comentario debe ser una cadena de texto.',
            'Comentario.max'         => 'El comentario no debe exceder los 3000 caracteres.',
        ]);


        // Guardado
        $comentario = new Comentarios();
        $comentario->SuscripcionId = $request->SuscripcionId;
        $comentario->Usuario = auth()->user()->id ?? null;
        $comentario->FechaCreacion = Carbon::now();
        $comentario->Activo = 1;
        $comentario->Comentario = $request->Comentario;
        $comentario->save();
        return redirect('suscripciones/' . $request->SuscripcionId . '/edit?tab=2')->with('success', 'El registro ha sido creado correctamente');
    }

    public function agregar_padecimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:50|unique:sus_padecimientos,Nombre',
        ], [
            'Nombre.required' => 'El nombre del padecimiento es obligatorio.',
            'Nombre.unique'   => 'Este padecimiento ya existe en el catálogo.',
            'Nombre.max'      => 'El nombre no debe exceder los 50 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->all()
            ], 422);
        }

        try {
            $padecimiento = new Padecimiento();
            $padecimiento->Nombre = mb_strtoupper($request->Nombre);
            $padecimiento->Activo = 1;
            $padecimiento->save();

            // IMPORTANTE: Accedemos a Id (Mayúscula) y forzamos a entero
            // Si tu primary key en el modelo no es 'id', asegúrate de usar la correcta
            $nuevoId = $padecimiento->Id;

            return response()->json([
                'success' => true,
                'id'      => intval($nuevoId),
                'nombre'  => $padecimiento->Nombre,
                'message' => 'Padecimiento agregado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function comentarios_update(Request $request, $id)
    {
        // Validación
        $validated = $request->validate([
            'Comentario' => [
                'required',
                'string',
                'max:3000',
            ],
        ], [
            'Comentario.required'    => 'Debe ingresar un comentario.',
            'Comentario.string'      => 'El comentario debe ser una cadena de texto.',
            'Comentario.max'         => 'El comentario no debe exceder los 3000 caracteres.',
        ]);

        $comentario = Comentarios::findOrFail($id);
        $comentario->Comentario = $request->Comentario;
        $comentario->save();
        return redirect('suscripciones/' . $comentario->SuscripcionId . '/edit?tab=2')->with('success', 'El registro ha sido modificado correctamente');
    }


    public function comentarios_get(Request $request, $id)
    {
        try {
            $comentarios = Comentarios::join('users', 'users.id', '=', 'sus_comentarios.Usuario')
                ->select(
                    DB::raw("DATE_FORMAT(sus_comentarios.FechaCreacion, '%d/%m/%Y %H:%i') as FechaCreacion"),
                    'sus_comentarios.Comentario',
                    'users.name as Usuario'
                )
                ->where('SuscripcionId', $id)->get();

            return response()->json([
                'success' => true,
                'data' => $comentarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al obtener los comentarios.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function comentarios_delete($id)
    {
        $comentario = Comentarios::findOrFail($id);
        $comentario->delete();
        return redirect('suscripciones/' . $comentario->SuscripcionId . '/edit?tab=2')->with('success', 'El registro ha sido eliminado correctamente');
    }

    /*
    * metodo que retorna los dias habiles de un rango de fechas teniendo en cuenta
    * fines de semana y dias feriado
    */
    public function calcularDiasHabiles($fechaInicio, $fechaFin)
    {
        $zonaHoraria = 'America/El_Salvador';

        $inicio = Carbon::parse($fechaInicio)->setTimezone($zonaHoraria)->startOfDay();
        $fin = Carbon::parse($fechaFin)->setTimezone($zonaHoraria)->startOfDay();

        // 🚩 Caso especial: misma fecha
        if ($inicio->equalTo($fin)) {
            return 0;
        }

        // 🚩 Caso especial: rango solo de fin de semana (ej. sábado → domingo)
        if ($inicio->isWeekend() && $fin->isWeekend() && $inicio->diffInDays($fin) <= 1) {
            return 0;
        }

        // 3. Obtener feriados que solapan con el rango
        $feriados = FechasFeriadas::where('FechaFinal', '>=', $inicio->toDateString())
            ->where('FechaInicio', '<=', $fin->toDateString())
            ->where('Activo', 1)
            ->get(['FechaInicio', 'FechaFinal']);

        // 4. Calcular días hábiles base (sin fines de semana)
        $diasHabiles = $inicio->diffInDaysFiltered(function (Carbon $fecha) {
            return !$fecha->isWeekend();
        }, $fin->copy()->addDay());

        // 5. Restar feriados que caen en días laborales
        $diasFeriados = 0;
        foreach ($feriados as $feriado) {
            $periodoFeriado = CarbonPeriod::create(
                Carbon::parse($feriado->FechaInicio)->setTimezone($zonaHoraria)->startOfDay(),
                Carbon::parse($feriado->FechaFinal)->setTimezone($zonaHoraria)->endOfDay()
            );

            foreach ($periodoFeriado as $fechaFeriado) {
                if ($fechaFeriado->between($inicio, $fin) && !$fechaFeriado->isWeekend()) {
                    $diasFeriados++;
                }
            }
        }

        return $diasHabiles - $diasFeriados - 1;
    }



    /*
    metodo para ejecutar calcularDiasHabiles en una petición ajax
    */
    public function calcularDiasHabilesJson(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $dias = $this->calcularDiasHabiles(
            $request->fecha_inicio,
            $request->fecha_fin
        );

        return response()->json(['dias_habiles' => $dias]);
    }

    public function importar(Request $request)
    {
        $request->validate([
            'Archivo' => 'required|file',
        ]);

        // try {

        //eliminar los datos de suscripciones

        SuscripcionTemp::where('Usuario', auth()->user()->id)->delete();
        $import = new SuscripcionImport;
        Excel::import($import, $request->file('Archivo'));

        $failures = $import->failures(); // Errores de validaciones con reglas
        $customFailures = collect($import->customFailures()); // Tus errores personalizados

        //dd($customFailures);
        if ($failures->isNotEmpty() || $customFailures->isNotEmpty()) {
            return view('suscripciones.suscripcion.import_error', [
                'failures' => $failures,
                'customFailures' => $customFailures,
            ]);
        }

        //no existen errores


        $sus_temp = SuscripcionTemp::where('Usuario', auth()->user()->id)->get();


        foreach ($sus_temp as $reg) {

            $ultimo = Suscripcion::selectRaw('MAX(CAST(SUBSTRING(NumeroTarea, LOCATE("TS-", NumeroTarea) + 3) AS UNSIGNED)) as ultimo')
                ->whereRaw('LEFT(NumeroTarea, 2) = ?', [substr(date('Y'), -2)])
                ->value('ultimo');

            $nuevoCorrelativo = $ultimo ? $ultimo + 1 : 1;
            $nuevaTarea = substr(date('Y'), -2) . 'TS-' . $nuevoCorrelativo;
            //valores por catalogo
            $gestor = Ejecutivo::where('Nombre', 'like', '%' . $reg->Gestor . '%')->first();
            $compania = Aseguradora::where('Nombre', 'like', '%' . $reg->Cia . '%')->first();
            $cliente = Cliente::where('Nombre', 'like', '%' . $reg->Contratante . '%')->first();
            $deuda = Deuda::where('NumeroPoliza', $reg->NumeroPolizaDeuda)->first();
            $vida = Vida::where('NumeroPoliza', $reg->NumeroPolizaVida)->first();
            $ocupacion = Ocupacion::where('Nombre', 'like', '%' . $reg->Ocupacion . '%')->first();
            $tipo_cliente = TipoCliente::where('Nombre', 'like', '%' . $reg->TipoCliente . '%')->first();
            $tipo_credito = TipoCredito::where('Nombre', 'like', '%' . $reg->TipoCredito . '%')->first();
            $tipo_imc = TipoImc::where('Nombre', 'like', '%' . $reg->TipoImc . '%')->first();
            $orden_medica = OrdenMedica::where('Nombre', 'like', '%' . $reg->TipoOrdenMedica . '%')->first();
            $estado_caso = EstadoCaso::where('Nombre', 'like', '%' . $reg->EstatusDelCaso . '%')->first();
            $resumen_gestion = ResumenGestion::where('Nombre', 'like', '%' . $reg->ResumenDeGestion . '%')->first();

            //valor nuevo en suscripcion
            $suscripcion = new Suscripcion();
            $suscripcion->NumeroTarea = $nuevaTarea;
            $suscripcion->FechaIngreso = $reg->FechaIngreso;
            $suscripcion->FechaEntregaDocsCompletos = $reg->FechaEntregaDocsCompletos;
            $suscripcion->DiasCompletarInfoCliente = $reg->DiasParaCompletarInfoCliente;
            $suscripcion->GestorId = $gestor->Id ?? null;
            $suscripcion->CompaniaId = $compania->Id ?? null;
            $suscripcion->ContratanteId = $cliente->Id ?? null;
            $suscripcion->PolizaDeuda = $deuda->Id ?? null;
            $suscripcion->PolizaVida = $vida->Id ?? null;
            $suscripcion->Asegurado = $reg->Asegurado;
            $suscripcion->OcupacionId = $ocupacion->Id ?? null;
            $suscripcion->Dui = $reg->DocumentoIdentidad;
            $suscripcion->Edad = $reg->Edad;
            $suscripcion->Genero = $reg->Genero == 'F' ? 1 : 2;
            $suscripcion->SumaAseguradaDeuda = $reg->SumaAseguradaEvaluadaDeuda;
            $suscripcion->SumaAseguradaVida = $reg->SumaAseguradaEvaluadaVida;
            $suscripcion->TipoClienteId = $tipo_cliente->Id ?? null;
            $suscripcion->TipoCreditoId = $tipo_credito->Id ?? null;
            $suscripcion->Imc = $reg->Imc;
            $suscripcion->TipoIMCId = $tipo_imc->Id ?? null;
            $suscripcion->Padecimiento = $reg->Padecimientos;
            $suscripcion->TipoOrdenMedicaId = $orden_medica->Id ?? null;
            $suscripcion->EstadoId = $estado_caso->Id ?? null;
            $suscripcion->ResumenGestion = $resumen_gestion->Id ?? null;
            $suscripcion->FechaReportadoCia = $reg->FechaReportadoCia;
            $suscripcion->TrabajadoEfectuadoDiaHabil = $reg->TrabajoEfectuadoDiaHabil;
            $suscripcion->TareasEvaSisa = $reg->TareasEvaSisa;
            $suscripcion->FechaCierreGestion = $reg->FechaCierreGestion;
            $suscripcion->FechaResolucion = $reg->FechaRecepcionResolucionCia;
            $suscripcion->ResolucionFinal = $reg->ResolucionOficial;
            $suscripcion->FechaEnvioResoCliente = $reg->FechaEnvioResolucionCliente;
            $suscripcion->DiasProcesamientoResolucion = $reg->DiasProcesamientoResolucion;
            $suscripcion->ValorExtraPrima = $reg->PorcentajeExtraprima;
            $suscripcion->save();

            $comentario = new Comentarios();
            $comentario->SuscripcionId = $suscripcion->Id;
            $comentario->Usuario = auth()->user()->id;
            $comentario->FechaCreacion = Carbon::now();
            $comentario->Comentario = $reg->ComentariosNrSuscripcion;
            $comentario->save();
        }
        alert()->success('Importación completada exitosamente.');
        return redirect()->back()->with('success', 'Importación completada exitosamente.');
        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // }
    }
}
