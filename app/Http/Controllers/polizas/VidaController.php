<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\VidaCarteraTempImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Perfil;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\TipoCobro;
use App\Models\polizas\Comentario;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaCartera;
use App\Models\polizas\VidaDetalle;
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
use Throwable;

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
            $validatedData = $request->validate([
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
                'SumaAsegurada' => 'required|numeric|min:100',
                'TasaDescuento' => 'nullable|numeric|min:0|max:100',
                'Concepto' => 'nullable|string|max:500'
            ], [
                'NumeroPoliza.required' => 'El número de póliza es obligatorio',
                'NumeroPoliza.unique' => 'Este número de póliza ya existe',
                'Aseguradora.required' => 'Seleccione una aseguradora',
                'Productos.required' => 'Seleccione un producto',
                'Planes.required' => 'Seleccione un plan',
                'Asegurado.required' => 'Seleccione un asegurado',
                'Ejecutivo.required' => 'Seleccione un ejecutivo',
                'VigenciaHasta.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
                'EdadTerminacion.gte' => 'La edad de terminación debe ser mayor o igual a la edad máxima de inscripción',
                'SumaAsegurada.min' => 'La suma asegurada mínima es 100',
                '*.exists' => 'El valor seleccionado no es válido',
                '*.required' => 'Este campo es obligatorio',
                '*.numeric' => 'Debe ser un valor numérico',
                '*.date' => 'Debe ser una fecha válida'
            ]);

            DB::beginTransaction();

            $vida = new Vida();
            $vida->NumeroPoliza = $request->NumeroPoliza;
            $vida->Nit = $request->Nit;
            $vida->Aseguradora = $request->Aseguradora;
            $vida->Producto = $request->Productos;
            $vida->Plan = $request->Planes;
            $vida->Asegurado = $request->Asegurado;
            $vida->VigenciaDesde = $request->VigenciaDesde;
            $vida->VigenciaHasta = $request->VigenciaHasta;
            $vida->Concepto = $request->Concepto;
            $vida->Ejecutivo = $request->Ejecutivo;
            $vida->TipoCobro = $request->TipoCobro;
            $vida->EstadoPoliza = 1;
            $vida->Tasa = $request->Tasa;
            $vida->SumaAsegurada = $request->SumaAsegurada;
            $vida->TasaDescuento = $request->TasaDescuento ?? null;
            $vida->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $vida->EdadTerminacion = $request->EdadTerminacion;
            $vida->Activo = 1;
            $vida->save();

            DB::commit();

            alert()->success('El registro ha sido creado correctamente');
            //return back();
            return redirect('polizas/vida/' . $vida->Id . '/edit');
        } catch (ValidationException $e) {
            // Esto captura específicamente los errores de validación
            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            // Log del error para depuración
            Log::error('Error al crear póliza: ' . $e->getMessage());

            // Crear un validador manual con el mensaje de error
            $validator = Validator::make([], []); // Crear validador vacío
            $validator->errors()->add('general', 'Ocurrió un error: ' . $e->getMessage());

            return back()
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function edit($id)
    {
        $vida = Vida::findOrFail($id);


        if (!session()->has('tab')) {
            session(['tab' => 2]);
        }

        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
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
            'tiposCartera'
        ));
    }

    public function finalizar_configuracion(Request $request)
    {
        $vida = Vida::findOrFail($request->vida);
        if ($vida->Configuracion == 1) {
            $vida->Configuracion = 0;
            $vida->update();

            alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/vida/' . $request->vida . 'edit');
        } else {
            $vida->Configuracion = 1;
            $vida->update();

            alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/vida/' . $request->vida);
        }
    }

    public function update(Request $request, $id)
    {
        // dd('hli update');

        $vida = Vida::findOrFail($id);
        $vida->NumeroPoliza = $request->NumeroPoliza;
        $vida->Nit = $request->Nit;
        $vida->Aseguradora = $request->Aseguradora;
        $vida->Producto = $request->Productos;
        $vida->Plan = $request->Planes;
        $vida->Asegurado = $request->Asegurado;
        $vida->VigenciaDesde = $request->VigenciaDesde;
        $vida->VigenciaHasta = $request->VigenciaHasta;
        $vida->Concepto = $request->Concepto;
        $vida->Ejecutivo = $request->Ejecutivo;
        $vida->TipoCobro = $request->TipoCobro;
        $vida->EstadoPoliza = 1;
        $vida->Tasa = $request->Tasa;
        $vida->SumaAsegurada = $request->SumaAsegurada;
        $vida->TasaDescuento = $request->TasaDescuento ?? null;
        $vida->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
        $vida->EdadTerminacion = $request->EdadTerminacion;
        $vida->Activo = 1;
        $vida->update();

        alert()->success('Registro modificado');
        return back();
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






        // tab2
        $cartera = VidaCartera::where('PolizaVida', '=', $id)
            ->where('PolizaVidaDetalle', null)
            ->select(DB::raw("IFNULL(sum(SumaAsegurada), '0.00') as SumaAsegurada"))->first();

        //tab 3

        $ultimo_pago = VidaDetalle::where('PolizaVida', $id)->orderBy('Id', 'desc')->first();
        $detalle = VidaDetalle::where('PolizaVida', $id)->orderBy('Id', 'desc')->get();
        $comentarios = Comentario::where('Id', $id)->where('Activo', '=', 1)->get();

        return view('polizas.vida.show', compact(
            //'bomberos',
            'poliza_vida',
            'detalle',
            //'detalle_last',
            'aseguradora',
            'cliente',
            //'anios',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
            // 'usuario_vidas',
            // 'mes',
            // 'meses',
            // 'anioSeleccionado',
            // 'fechaInicio',
            // 'fechaFinal',
            'tab',

            //tab2
            // 'fechas',
            'cartera',
            'comentarios',

            //tab3
            'ultimo_pago',
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
            // 'fecha_inicial',
            // 'fecha_final',
            // 'axo',
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

        return view('polizas.vida.respuesta_poliza', compact(
            'total',
            'poliza_vida',
            'poliza_edad_maxima',
            'registros_rehabilitados',
            'registros_eliminados',
            'nuevos_registros',
            'mesString',
            'axoActual',
            'mesActual'
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

        //borrar datos de tabla temporal
        VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->delete();

        Excel::import(new VidaCarteraTempImport($request->Axo, $request->Mes, $id, $request->FechaInicio, $request->FechaFinal, $request->PolizaVidaTipoCartera), $archivo);




        //verificando creditos repetidos
        /*$repetidos = VidaCarteraTemp::where('User', auth()->user()->id)
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
        }*/



        //calculando errores de cartera
        $cartera_temp = VidaCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->get();


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



        //calculando edades y fechas de nacimiento
        VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVida', $poliza_vida->Id)
            ->update([
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);



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
                        //'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($vida_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->whereBetween('SumaAsegurada', [$tasa->MontoDesde, $tasa->MontoHasta])
                    ->update([
                        //'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->update([
                        //'LineaCredito' => $tasa->LineaCredito,
                        'Tasa' => $poliza_vida->Tasa
                    ]);
            }
        }

        //agregar la validacion para las edades maximas de inscripcion

        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();

        /*$axoActual =  $temp_data_fisrt->Axo;
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

        //estableciendo fecha de nacimiento date y calculando edad
        VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVida', $id)
            ->update([
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);

        $poliza_edad_maxima = VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('EdadDesembloso', '>', $poliza_vida->EdadMaximaInscripcion)->get();



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
            'valid_references.NumeroReferencia')
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

        return view('polizas.vida.respuesta_poliza', compact(
            'total',
            'poliza_vida',
            'poliza_edad_maxima',
            'registros_rehabilitados',
            'registros_eliminados',
            'nuevos_registros',
            'mesString',
            'axoActual',
            'mesActual'
        ));



        dd($nuevos_registros);

        // try {
        //     $archivo = $request->Archivo;
        //     TempCartera::where('Usuario', '=', auth()->user()->id)->delete();
        //     Excel::import(new CarteraImport, $archivo);

        //     $datos = DB::select("call dateFormat(" . auth()->user()->id . ")");

        //     //$temp = TempCartera::where('Usuario', '=', auth()->user()->id)->get();

        //     $calculo_saldo = $temp = TempCartera::where('Usuario', '=', auth()->user()->id)->sum('SaldoVigenteCapital');


        //     //si hay validaciones
        //     if ($request->Validar == "on") {
        //         if ($calculo_saldo > $vida->LimiteGrupo) {
        //             alert()->error('Error, el saldo supera el limite de grupo');
        //             return back();
        //         }

        //         $calculo_limite_individual  = TempCartera::where('Usuario', '=', auth()->user()->id)
        //             ->select(DB::raw('*,SUM(SaldoVigenteCapital) as suma'))
        //             ->groupBy('Dui')
        //             ->having('suma', '>', $vida->LimiteIndividual)
        //             ->get();

        //         if ($calculo_limite_individual->count() > 0) {
        //             return view('polizas.validacion_cartera.resultado', compact('calculo_limite_individual'));
        //         }


        //         $nuevos = TempCartera::select('Id', 'Dui', 'Nit', 'PrimerApellido', 'SegundoApellido', 'CasadaApellido', 'PrimerNombre', 'SegundoNombre', 'SociedadNombre', 'NoRefereciaCredito', 'Edad', DB::raw('(select count(*) from cartera_mensual where
        // (cartera_mensual.Dui = temp_cartera.Dui or cartera_mensual.Nit = temp_cartera.Nit) and temp_cartera.NoRefereciaCredito = cartera_mensual.NoRefereciaCredito
        //  and cartera_mensual.Mes = ' . $mes_evaluar . ' and  cartera_mensual.Axo = ' . $axo . ') as conteo'))
        //             ->where('Usuario', '=', auth()->user()->id)
        //             ->having('conteo', '=', 0)
        //             ->get();

        //         return view('polizas.validacion_cartera.resultado', compact('nuevos'));
        //     }
        // } catch (Throwable $e) {
        //     print($e);

        //     return false;
        // }

        // //dd(auth()->user()->id,$request->FechaInicio,$request->FechaFinal );
        // $insert = DB::select("call create_cartera_mensual(" . auth()->user()->id . ",'$request->FechaInicio','$request->FechaFinal')");

        // $monto_cartera = CarteraMensual::where('Mes', '=', $mes_evaluar)->where('Axo', '=', $axo)->where('Vida', '=', $vida->Id)->sum('SaldoTotal');

        // //74126861.7

        // if ($vida->Mesual == 0) {
        //     $tasaFinal = ($vida->Tasa / 1000) / 12;
        // } else {
        //     $tasaFinal = $vida->Tasa / 1000;
        // }

        // $sub_total = $monto_cartera * $tasaFinal;

        // $prima_total = $sub_total;
        // $prima_descontada = $sub_total * 2;

        // $time = Carbon::now('America/El_Salvador');

        // $detalle = new VidaDetalle();
        // $detalle->SaldoA = $request->SaldoA;
        // $detalle->Vida = $vida->Id;
        // //$detalle->Comentario = $request->Comentario;
        // $detalle->Tasa = $tasaFinal;
        // //$detalle->Comision = $request->Comision;
        // $detalle->PrimaTotal = $prima_total;
        // //$detalle->Descuento = $request->Descuento;
        // //$detalle->ExtraPrima = $request->ExtraPrima;
        // //$detalle->ValorCCF = $request->ValorCCF;
        // $detalle->APagar = $sub_total;
        // //$detalle->TasaComision = $request->TasaComision;
        // $detalle->MontoCartera = $monto_cartera;
        // $detalle->PrimaDescontada = $prima_descontada;
        // //$detalle->ValorDescuento = $request->ValorDescuento;
        // //$detalle->Retencion = $request->Retencion;
        // //$detalle->IvaSobreComision = $request->IvaSobreComision;
        // //$detalle->ImpresionRecibo = $time->toDateTimeString();
        // $detalle->save();
        // /*$detalle->EnvioCartera = $request->EnvioCartera;
        // $detalle->EnvioPago = $request->EnvioPago;
        // $detalle->PagoAplicado = $request->PagoAplicado;


        alert()->success('El registro ha sido ingresado correctamente');
        return back();*/
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

            $count = VidaCarteraTemp::where('User', auth()->user()->id)
                ->where('PolizaVida', $id)
                //->where('EdadDesembloso', '>', $poliza_vida->EdadMaximaInscripcion) EdadTerminacion
                ->where('EdadDesembloso', '>', $poliza_vida->EdadTerminacion)
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

    // public function edit_pago(Request $request)
    // {
    //     $detalle = VidaDetalle::findOrFail($request->Id);
    //     //dd($request->EnvioCartera .' 00:00:00');
    //     if ($request->EnvioCartera) {
    //         $detalle->EnvioCartera = $request->EnvioCartera;
    //     }
    //     if ($request->EnvioPago) {
    //         $detalle->EnvioPago = $request->EnvioPago;
    //     }
    //     if ($request->PagoAplicado) {
    //         $detalle->PagoAplicado = $request->PagoAplicado;
    //     }
    //     $detalle->Comentario = $request->Comentario;

    //     /*$detalle->EnvioPago = $request->EnvioPago;
    //     $detalle->PagoAplicado = $request->PagoAplicado;*/
    //     $detalle->update();
    //     alert()->success('El registro ha sido ingresado correctamente');
    //     return back();
    // }

    // public function get_pago($id)
    // {
    //     return VidaDetalle::findOrFail($id);
    // }

    // public function renovar($id)
    // {
    //     $vida = Vida::findOrFail($id);
    //     $estados_poliza = EstadoPoliza::where('Activo', 1)->get();
    //     $tipoCobro = TipoCobro::where('Activo', 1)->get();
    //     return view('polizas.vida.renovar', compact('vida', 'tipoCobro', 'estados_poliza'));
    // }
    // public function renovarPoliza(Request $request, $id)
    // {
    //     $vida = Vida::findOrFail($id);
    //     $vida->Mensual = $request->Mensual; //valor de radio button
    //     $vida->EstadoPoliza = $request->EstadoPoliza;
    //     $vida->VigenciaDesde = $request->VigenciaDesde;
    //     $vida->VigenciaHasta = $request->VigenciaHasta;
    //     $vida->MontoCartera = $request->MontoCartera;
    //     $vida->Tasa = $request->Tasa;
    //     $vida->update();

    //     alert()->success('La poliza fue renovada correctamente');
    //     return back();
    // }
}
