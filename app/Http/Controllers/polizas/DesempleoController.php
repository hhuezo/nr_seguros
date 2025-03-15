<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\DesempleoCarteraTempImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\SaldoMontos;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoCobro;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCartera;
use App\Models\polizas\DesempleoCarteraTemp;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DesempleoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $desempleo = Desempleo::get();

        return view('polizas.desempleo.index', compact('desempleo'));
    }

    public function create()
    {

        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();


        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //deuda
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $saldos = SaldoMontos::where('Activo', 1)->get();
        return view('polizas.desempleo.create', compact(
            'aseguradora',
            'cliente',
            'productos',
            'planes',
            'tipoCartera',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'saldos'
        ));
    }

    public function store(Request $request)
    {
        // Validaciones
        $request->validate([
            'NumeroPoliza' => 'required|string|max:255',
            'Aseguradora' => 'required|exists:aseguradora,Id',
            'Asegurado' => 'required|exists:cliente,Id',
            'Nit' => 'required|string|max:255',
            'Ejecutivo' => 'required|exists:ejecutivo,Id',
            'Saldos' => 'required',
            'VigenciaDesde' => 'required|date',
            'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
            'EdadTerminacion' => 'required|numeric|min:18',
            'EdadMaximaInscripcion' => 'required|numeric|min:18',
            'Tasa' => 'required|numeric|min:0.00001',
            'Concepto' => 'nullable|string|max:1000',
        ], [
            'NumeroPoliza.required' => 'El campo Número de Póliza es obligatorio.',
            'NumeroPoliza.string' => 'El campo Número de Póliza debe ser una cadena de texto.',
            'NumeroPoliza.max' => 'El campo Número de Póliza no debe exceder los 255 caracteres.',
            'Aseguradora.required' => 'Debes seleccionar una Aseguradora.',
            'Aseguradora.exists' => 'La Aseguradora seleccionada no es válida.',
            'Asegurado.required' => 'Debes seleccionar un Asegurado.',
            'Asegurado.exists' => 'El Asegurado seleccionado no es válido.',
            'Nit.required' => 'El campo Nit es obligatorio.',
            'Nit.string' => 'El campo Nit debe ser una cadena de texto.',
            'Nit.max' => 'El campo Nit no debe exceder los 255 caracteres.',
            'Ejecutivo.required' => 'Debes seleccionar un Ejecutivo.',
            'Saldos.required' => 'Debes seleccionar una opcion de saldo y montos.',
            'Ejecutivo.exists' => 'El Ejecutivo seleccionado no es válido.',
            'VigenciaDesde.required' => 'El campo Vigencia inicial es obligatorio.',
            'VigenciaDesde.date' => 'El campo Vigencia inicial debe ser una fecha válida.',
            'VigenciaHasta.required' => 'El campo Vigencia final es obligatorio.',
            'VigenciaHasta.date' => 'El campo Vigencia final debe ser una fecha válida.',
            'VigenciaHasta.after_or_equal' => 'La fecha de Vigencia final debe ser igual o posterior a la fecha de Vigencia inicial.',
            'EdadTerminacion.required' => 'El campo Edad Terminación es obligatorio.',
            'EdadTerminacion.numeric' => 'El campo Edad Terminación debe ser un número.',
            'EdadTerminacion.min' => 'El campo Edad Terminación debe ser al menos 18.',
            'EdadMaximaInscripcion.required' => 'El campo Edad inscripción es obligatorio.',
            'EdadMaximaInscripcion.numeric' => 'El campo Edad inscripción debe ser un número.',
            'EdadMaximaInscripcion.min' => 'El campo Edad inscripción debe ser al menos 18.',
            'Tasa.required' => 'El campo Tasa es obligatorio.',
            'Tasa.numeric' => 'El campo Tasa debe ser un número.',
            'Tasa.min' => 'El campo Tasa debe ser al menos 0.',
            'Concepto.string' => 'El campo Concepto debe ser una cadena de texto.',
            'Concepto.max' => 'El campo Concepto no debe exceder los 1000 caracteres.',
        ]);

        try {
            // Crear una nueva instancia del modelo Desempleo
            $desempleo = new Desempleo();

            // Asignar los valores del formulario a los atributos del modelo
            $desempleo->NumeroPoliza = $request->NumeroPoliza;
            $desempleo->Asegurado = $request->Asegurado;
            $desempleo->Aseguradora = $request->Aseguradora;
            $desempleo->Ejecutivo = $request->Ejecutivo;
            $desempleo->Saldos = $request->Saldos;
            $desempleo->VigenciaDesde = $request->VigenciaDesde;
            $desempleo->VigenciaHasta = $request->VigenciaHasta;
            $desempleo->Tasa = $request->Tasa;
            $desempleo->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $desempleo->EdadMaxima = $request->EdadTerminacion;
            $desempleo->EstadoPoliza = 1;
            $desempleo->Descuento = $request->Descuento;
            $desempleo->Activo = 1;
            $desempleo->Usuario = auth()->id();
            $desempleo->save();

            alert()->success('Éxito', 'La póliza de desempleo se ha creado correctamente.');
            return Redirect::to('polizas/desempleo');
        } catch (\Exception $e) {

            alert()->error('Error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage());
            return back()->withInput();
        }
    }


    public function show(Request $request, $id)
    {
        // try {

        $tab = $request->tab ?: 1;

        // Buscar la póliza de desempleo por su ID
        $desempleo = Desempleo::findOrFail($id);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $fechaInicio = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $fechaFinal = Carbon::now()->startOfMonth()->toDateString();

        // Extraer el mes y el año de $fechaFinal
        $mes = Carbon::parse($fechaFinal)->month;
        $anioSeleccionado = Carbon::parse($fechaFinal)->year;

        // Fechas de ejemplo
        $vigenciaDesde = Carbon::parse($desempleo->VigenciaDesde);
        $vigenciaHasta = Carbon::parse($desempleo->VigenciaHasta);

        $anios = [];

        for ($anio = $vigenciaDesde->year; $anio <= $vigenciaHasta->year; $anio++) {
            $anios[] = $anio;
        }


        //seccion para guardar pago
        $fechas = DesempleoCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
            ->where('PolizaDesempleo', '=', $id)
            ->orderByDesc('Id')->first();

        $cartera = DesempleoCartera::where('PolizaDesempleo', '=', $id)
            ->where('PolizaDesempleoDetalle', null)
            ->select(
                DB::raw("IFNULL(sum(MontoOtorgado), '0.00') as MontoOtorgado"),
                DB::raw("IFNULL(sum(SaldoCapital), '0.00') as SaldoCapital"),
                DB::raw("IFNULL(sum(Intereses), '0.00') as Intereses"),
                DB::raw("IFNULL(sum(MontoNominal), '0.00') as MontoNominal"),
                DB::raw("IFNULL(sum(InteresesCovid), '0.00') as InteresesCovid"),
                DB::raw("IFNULL(sum(InteresesMoratorios), '0.00') as InteresesMoratorios")
            )->first();

        $saldoCapital = 0.00;
        $intereses = 0.00;
        $montoNominal = 0.00;
        $interesesCovid = 0.00;
        $interesesMoratorios = 0.00;
        $total = 0;

        if ($desempleo->Saldos == 1) {
            $total = $cartera->SaldoCapital;
            $saldoCapital = $cartera->SaldoCapital;
        } else if ($desempleo->Saldos == 2) {
            $total = $cartera->SaldoCapital + $cartera->Intereses;
            $saldoCapital = $cartera->SaldoCapital;
            $intereses = $cartera->Intereses;
        } else if ($desempleo->Saldos == 3) {
            $total = $cartera->SaldoCapital + $cartera->Intereses + $cartera->InteresesCovid;
            $saldoCapital = $cartera->SaldoCapital;
            $intereses = $cartera->Intereses;
            $interesesCovid = $cartera->InteresesCovid;
        } else if ($desempleo->Saldos == 4) {
            $total = $cartera->SaldoCapital + $cartera->Intereses + $cartera->InteresesCovid + $cartera->InteresesMoratorios;
            $saldoCapital = $cartera->SaldoCapital;
            $intereses = $cartera->Intereses;
            $interesesCovid = $cartera->InteresesCovid;
            $interesesMoratorios = $cartera->interesesMoratorios;
        } else if ($desempleo->Saldos == 5) {
            $total = $cartera->MontoNominal;
            $MontoNominal = $cartera->MontoNominal;
        } else if ($desempleo->Saldos == 6) {
            $total = $cartera->MontoOtorgado;
            $MontoOtorgado = $cartera->MontoOtorgado;
        }


        $total = $total ?? 0;

        // Calcular el subtotal
        $subtotal = $total * ($desempleo->Tasa ?? 0);

        // Asegurar que $extra_prima tenga un valor predeterminado de 0 si es null
        $extraPrima =  0;

        // Calcular el descuento
        $descuento = ($subtotal + $extraPrima) * (($desempleo->Descuento ?? 0) / 100);

        // Calcular la prima a cobrar
        $primaCobrar = ($subtotal + $extraPrima - $descuento) ?? 0;

        $comisionIva = ($desempleo->ComisionIva == 1)  ? round(($desempleo->TasaComision ?? 0) / 1.13, 2)  : ($desempleo->TasaComision ?? 0);


        //({{ $deuda->ComisionIva == 1 ? number_format($deuda->TasaComision / 1.13, 2, '.', ',') : $deuda->TasaComision }}%)


        // $tipo_contribuyente = $desempleo->cliente->TipoContribuyente ?? 0;

        // if ($tipo_contribuyente != 4) {
        //     $iva = 0;
        // } else {
        //     iva = 0;
        // }





        $data = [
            "saldoCapital" => $saldoCapital,
            "intereses" => $intereses,
            "montoNominal" => $montoNominal,
            "interesesCovid" => $interesesCovid,
            "interesesMoratorios" => $interesesMoratorios,
            "total" => $total,
            "primaPorPagar" => $subtotal,
            "descuento" => $descuento,
            "extra_prima" => $extraPrima,
            "primaCobrar" => $primaCobrar
        ];


        // Retornar la vista con los datos de la póliza
        return view('polizas.desempleo.show', compact(
            'desempleo',
            'data',
            'tab',
            'meses',
            'fechaInicio',
            'fechaFinal',
            'mes',
            'anios',
            'anioSeleccionado',
            'fechas'
        ));
        // } catch (\Exception $e) {
        //     alert()->error('No se pudo encontrar la póliza de desempleo solicitada.');
        //     return back();
        // }
    }


    public function create_pago(Request $request, $id)
    {
        // try {
        $request->validate([
            'Axo' => 'required|integer',
            'Mes' => 'required|integer|between:1,12',
            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
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
        ]);

        $desempleo = Desempleo::findOrFail($id);





        $archivo = $request->Archivo;

        $excel = IOFactory::load($archivo);

        // Verifica si hay al menos dos hojas
        $sheetsCount = $excel->getSheetCount();

        if ($sheetsCount > 1) {
            alert()->error('La cartera solo puede contener un solo libro de Excel');
            return back();
        }

        //borrar datos de tabla temporal
        DesempleoCarteraTemp::where('User', auth()->user()->id)->where('PolizaDesempleo', $id)->delete();

        //guardando datos de excel en base de datos
        Excel::import(new DesempleoCarteraTempImport($request->Axo, $request->Mes, $id, $request->FechaInicio, $request->FechaFinal), $archivo);



        //calculando errores de cartera
        $cartera_temp = DesempleoCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaDesempleo', $id)->get();


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
            return view('polizas.desempleo.respuesta_poliza_error', compact('data_error', 'desempleo'));
        }


        $temp_data_fisrt = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->where('User', auth()->user()->id)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }

        $axoActual =  $temp_data_fisrt->Axo;
        $mesActual =  $temp_data_fisrt->Mes;


        // Calcular el mes pasado
        if ($mesActual == 1) {
            $mesAnterior = 12; // Diciembre
            $axoAnterior = $axoActual - 1; // Año anterior
        } else {
            $mesAnterior = $mesActual - 1; // Mes anterior
            $axoAnterior = $axoActual; // Mismo año
        }


        //estableciendo fecha de nacimiento date y calculando edad
        DesempleoCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaDesempleo', $id)
            ->update([
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);

        $data = DesempleoCarteraTemp::where('User', auth()->user()->id)->where('PolizaDesempleo', $id)->get();
        $poliza_edad_maxima = $data->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion);


        //registros que no existen en el mes anterior
        $count_data_cartera = DesempleoCartera::where('PolizaDesempleo', $id)->count();
        if ($count_data_cartera > 0) {
            //dd($mesAnterior,$axoAnterior,$request->Deuda);
            $registros_eliminados = DB::table('poliza_desempleo_cartera AS pdc')
                ->leftJoin('poliza_desempleo_cartera_temp AS pdtc', function ($join) {
                    $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia')
                        ->where('pdtc.User', auth()->user()->id);
                })
                ->where('pdc.Mes', (int)$mesAnterior)
                ->where('pdc.Axo', (int)$axoAnterior)
                ->where('pdc.PolizaDesempleo', $id)
                ->whereNull('pdtc.NumeroReferencia') // Solo los que no están en poliza_deuda_temp_cartera
                ->select('pdc.*') // Selecciona columnas principales
                ->get();
        } else {
            $registros_eliminados =  DesempleoCarteraTemp::where('Id', 0)->get();
        }


        $nuevos_registros = DesempleoCarteraTemp::leftJoin(
            DB::raw('(
                        SELECT DISTINCT NumeroReferencia
                        FROM poliza_desempleo_cartera
                        WHERE PolizaDesempleo = ' . $id . '
                    ) AS valid_references'),
            'poliza_desempleo_cartera_temp.NumeroReferencia',
            '=',
            'valid_references.NumeroReferencia'
        )
            ->where('poliza_desempleo_cartera_temp.User', auth()->user()->id) // Filtra por el usuario autenticado
            ->where('poliza_desempleo_cartera_temp.PolizaDesempleo', $id)
            ->whereNull('valid_references.NumeroReferencia') // Los registros que no coinciden
            ->select('poliza_desempleo_cartera_temp.*') // Selecciona columnas de la tabla principal
            ->get();


        return view('polizas.desempleo.respuesta_poliza', compact('desempleo', 'poliza_edad_maxima', 'registros_eliminados', 'nuevos_registros', 'axoActual', 'mesActual'));
        // } catch (\Exception $e) {
        //     // Capturar cualquier excepción y retornar un mensaje de error
        //     return back()->with('error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage());
        // }
    }

    public function store_poliza(Request $request, $id)
    {
        $mes = $request->MesActual; // El formato 'm' devuelve el mes con ceros iniciales (por ejemplo, "02")
        $anio = $request->AxoActual;

        $desempleo = Desempleo::findOrFail($id);

        // eliminando datos de la cartera si existieran
        DesempleoCartera::where('Axo', $anio)->where('Mes', $mes + 0)->where('PolizaDesempleo', $id)->delete();


        $tempData = DesempleoCarteraTemp::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('User', auth()->user()->id)
            ->where('NoValido', 0)
            ->where('PolizaDesempleo', $id)
            //->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion)
            ->get();

        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {
            try {
                $poliza = new DesempleoCartera();
                $poliza->PolizaDesempleo = $tempRecord->PolizaDesempleo;
                $poliza->Nit = $tempRecord->Nit;
                $poliza->Dui = $tempRecord->Dui;
                $poliza->Pasaporte = $tempRecord->Pasaporte;
                $poliza->Nacionalidad = $tempRecord->Nacionalidad;
                $poliza->FechaNacimiento = $tempRecord->FechaNacimiento;
                $poliza->TipoPersona = $tempRecord->TipoPersona;
                $poliza->PrimerApellido = $tempRecord->PrimerApellido;
                $poliza->SegundoApellido = $tempRecord->SegundoApellido;
                $poliza->ApellidoCasada = $tempRecord->ApellidoCasada;
                $poliza->PrimerNombre = $tempRecord->PrimerNombre;
                $poliza->SegundoNombre = $tempRecord->SegundoNombre;
                $poliza->NombreSociedad = $tempRecord->NombreSociedad;
                $poliza->Sexo = $tempRecord->Sexo;
                $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento;
                $poliza->FechaVencimiento = $tempRecord->FechaVencimiento;
                $poliza->Ocupacion = $tempRecord->Ocupacion;
                $poliza->NumeroReferencia = $tempRecord->NumeroReferencia;
                $poliza->MontoOtorgado = $tempRecord->MontoOtorgado;
                $poliza->SaldoCapital = $tempRecord->SaldoCapital;
                $poliza->Intereses = $tempRecord->Intereses;
                $poliza->MoraCapital = $tempRecord->MoraCapital;
                $poliza->InteresesMoratorios = $tempRecord->InteresesMoratorios;
                $poliza->SaldoTotal = $tempRecord->SaldoTotal;
                $poliza->User = $tempRecord->User;
                $poliza->Axo = $tempRecord->Axo;
                $poliza->Mes = $tempRecord->Mes;
                $poliza->FechaInicio = $tempRecord->FechaInicio;
                $poliza->FechaFinal = $tempRecord->FechaFinal;
                $poliza->TipoError = $tempRecord->TipoError;
                $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate;
                $poliza->Edad = $tempRecord->Edad;
                $poliza->InteresesCovid = $tempRecord->InteresesCovid;
                $poliza->MontoNominal = $tempRecord->MontoNominal;
                $poliza->NoValido = $tempRecord->NoValido;
                $poliza->EdadDesembloso = $tempRecord->EdadDesembloso;
                $poliza->FechaOtorgamientoDate = $tempRecord->FechaOtorgamientoDate;
                $poliza->Excluido = $tempRecord->Excluido;
                $poliza->Rehabilitado = $tempRecord->Rehabilitado;
                $poliza->EdadRequisito = $tempRecord->EdadRequisito;
                $poliza->save();
            } catch (\Exception $e) {
                // Captura errores y los guarda en el log
                Log::error("Error al insertar en poliza_desempleo_cartera: " . $e->getMessage(), [
                    'NumeroReferencia' => $tempRecord->NumeroReferencia,
                    'Usuario' => auth()->user()->id ?? 'N/A',
                    'Datos' => $tempRecord
                ]);
            }
        }

        // eliminando datos de la cartera temporal
        DesempleoCarteraTemp::where('Axo', $anio)->where('Mes', $mes + 0)->where('PolizaDesempleo', $id)->delete();

        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/desempleo/' . $id . '?tab=2');
    }

    public function borrar_proceso_actual(Request $request, $id)
    {
        //borrar datos de tabla temporal
        DesempleoCarteraTemp::where('User', auth()->user()->id)->where('PolizaDesempleo', $id)->delete();

        return redirect('polizas/desempleo/' . $id . '?tab=2');
    }




    public function validarFormatoFecha($data)
    {
        try {
            // Intenta crear un objeto Carbon a partir de la cadena de fecha
            $fechaCarbon = Carbon::createFromFormat('d/m/Y', $data);

            // Comprueba si la cadena de fecha tiene el formato correcto
            return $fechaCarbon && $fechaCarbon->format('d/m/Y') === $data;
        } catch (Exception $e) {
            return false;
        }
    }


    public function convertDate($dateValue)
    {
        try {
            // Si el valor es un número, asume que es una fecha de Excel y conviértelo
            if (is_numeric($dateValue)) {
                $unixDate = (intval($dateValue) - 25569) * 86400;
                return gmdate("d/m/Y", $unixDate);
            }

            // Si el valor es una cadena en formato d/m/Y, conviértelo al formato d/m/Y
            if (Carbon::hasFormat($dateValue, 'd/m/Y')) {
                $fechaCarbon = Carbon::createFromFormat('d/m/Y', $dateValue);
                return $fechaCarbon->format('d/m/Y');
            }

            // Si el valor es una cadena en formato Y/m/d, conviértelo al formato d/m/Y
            if (Carbon::hasFormat($dateValue, 'Y/m/d')) {
                $fechaCarbon = Carbon::createFromFormat('Y/m/d', $dateValue);
                return $fechaCarbon->format('d/m/Y');
            }

            // Si no coincide con ninguno de los formatos, devolver false
            return false;
        } catch (Exception $e) {
            return false;
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


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
