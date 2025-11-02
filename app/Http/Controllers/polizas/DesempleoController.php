<?php

namespace App\Http\Controllers\polizas;

use App\Exports\desempleo\EdadInscripcionExport;
use App\Exports\desempleo\EdadMaximaExport;
use App\Exports\desempleo\NuevosRegistrosExport;
use App\Exports\desempleo\RegistrosEliminadosExport;
use App\Exports\desempleo\RegistrosRehabilitadosExport;
use App\Exports\ResponsabilidadMaximaExport;
use App\Http\Controllers\Controller;
use App\Imports\DesempleoCarteraTempImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ConfiguracionRecibo;
use App\Models\catalogo\DatosGenerales;
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
use App\Models\polizas\Comentario;
use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCartera;
use App\Models\temp\DesempleoCarteraTemp;
use App\Models\polizas\DesempleoDetalle;
use App\Models\polizas\DesempleoHistorialRecibo;
use App\Models\polizas\DesempleoTipoCartera;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DesempleoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tasa_diferenciada = collect([]);
        $tipo = DesempleoTipoCartera::get();
        foreach ($tipo as $tip) {
            $tasa_diferenciada->push([
                'PolizaDesempleoTipoCartera' => $tip->Id,
                'FechaDesde' => null,
                'FechaHasta' => null,
                'MontoDesde' => null,
                'MontoHasta' => null,
                'Tasa' => $tip->poliza_desempleo->Tasa,
                'SaldosMontos' => $tip->SaldosMontos,
                'Usuario' => 8,
            ]);
        }



        $idRegistro = $request->idRegistro ?? 0;

        $desempleo = Desempleo::orderBy('Id', 'asc')->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $desempleo->search(function ($d) use ($idRegistro) {
                return $d->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('polizas.desempleo.index', compact('desempleo', 'posicion'));
    }

    public function get_cartera($id, $mes, $axo)
    {
        $desempleo = Desempleo::findOrFail($id);
        $desempleo_cartera = DesempleoCartera::where('PolizaDesempleo', $id)->where('Mes', $mes)->where('Axo', $axo)->first();

        if ($desempleo_cartera) {
            return true;
        }
        return false;
    }

    public function create()
    {

        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //desempleo
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();

        //dd($tipoCartera);
        return view('polizas.desempleo.create', compact(
            'aseguradora',
            'cliente',
            'productos',
            'planes',
            'tipoCartera',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
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
            //'Saldos' => 'required',
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
            $desempleo->TasaDiferenciada = $request->TasaDiferenciada;
            $desempleo->VigenciaDesde = $request->VigenciaDesde;
            $desempleo->VigenciaHasta = $request->VigenciaHasta;
            $desempleo->Tasa = $request->Tasa;
            $desempleo->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $desempleo->EdadMaxima = $request->EdadTerminacion;
            $desempleo->EstadoPoliza = $request->EstadoPoliza;
            $desempleo->Descuento = $request->Descuento;
            $desempleo->Concepto = $request->Concepto;
            $desempleo->ClausulasEspeciales = $request->ClausulasEspeciales;
            $desempleo->Beneficios = $request->Beneficios;
            $desempleo->Activo = 1;
            $desempleo->Plan = $request->Planes;
            $desempleo->Usuario = auth()->id();
            $desempleo->Configuracion = 0;
            $desempleo->save();

            alert()->success('Éxito', 'La póliza de desempleo se ha creado correctamente.');
            return Redirect::to('polizas/desempleo/' . $desempleo->Id . '/edit');
        } catch (\Exception $e) {

            alert()->error('Error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function finalizar_configuracion(Request $request)
    {
        $desempleo = Desempleo::findOrFail($request->desempleo);
        if ($desempleo->Configuracion == 1) {
            $desempleo->Configuracion = 0;
            $desempleo->update();

            alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/desempleo/' . $request->desempleo . '/edit');
        } else {
            $desempleo->Configuracion = 1;
            $desempleo->update();

            alert()->success('El registro de poliza ha sido configurado correctamente');
            return redirect('polizas/desempleo/' . $request->desempleo);
        }
    }


    public function show(Request $request, $id)
    {
        //try {

        $tab = $request->tab ?: 1;

        // Buscar la póliza de desempleo por su ID
        $desempleo = Desempleo::with('desempleo_tipos_cartera')->findOrFail($id);

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];





        // 👉 Por defecto: del primer día del mes anterior al primer día del mes actual
        $fecha_inicial = now()->subMonth()->startOfMonth();
        $fecha_final = now()->startOfMonth();
        $axo = $fecha_inicial->year;
        $mes = (int) $fecha_inicial->month;


        // ✅ Fechas en formato Y-m-d
        $fechaInicio = $fecha_inicial->format('Y-m-d');
        $fechaFinal = $fecha_final->format('Y-m-d');


        // Último pago activo
        $ultimo_pago = DesempleoDetalle::where('Desempleo', $id)
            ->where('Activo', 1)
            ->latest('Id')
            ->first();


        if ($ultimo_pago) {
            // Si hay pago, tomar la fecha inicial y final con +1 mes exacto
            $fecha_inicial = Carbon::parse($ultimo_pago->FechaInicio);
            $fecha_final = $fecha_inicial->copy()->addMonth();

            $axo = $fecha_inicial->year;
            $mes = (int) $fecha_inicial->month;

            // Formato final Y-m-d
            $fechaInicio = $fecha_inicial->format('Y-m-d');
            $fechaFinal = $fecha_final->format('Y-m-d');
        }



        // Último registro temporal de cartera
        $registro_cartera = DesempleoCarteraTemp::where('PolizaDesempleo', $id)->first();

        if ($registro_cartera) {
            $axo = $registro_cartera->Axo;
            $mes = (int) $registro_cartera->Mes;

            $fecha_inicial = $registro_cartera->FechaInicio;
            $fecha_final = $registro_cartera->FechaFinal;
        }


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



        $dataPagoTemp = collect([]);
        $dataPagoId = [];

        foreach ($desempleo->desempleo_tipos_cartera as $desempleo_tipos_cartera) {


            if ($desempleo_tipos_cartera->tasa_diferenciada->count() > 0) {



                foreach ($desempleo_tipos_cartera->tasa_diferenciada as $tasa_diferenciada) {

                    $dataPagoId[] = $tasa_diferenciada->Id;


                    $linea_credito = SaldoMontos::findOrFail($tasa_diferenciada->SaldosMontos);
                    //dd($linea_credito);

                    $monto = '';

                    if ($desempleo_tipos_cartera->TipoCalculo == 2) {
                        $monto = $tasa_diferenciada->MontoDesde . ' - ' . $tasa_diferenciada->MontoHasta;
                    }

                    $fecha = '';

                    if ($desempleo_tipos_cartera->TipoCalculo == 1) {
                        $fecha = Carbon::parse($tasa_diferenciada->FechaDesde)->format('d/m/Y') .
                            ' - ' .
                            Carbon::parse($tasa_diferenciada->FechaHasta)->format('d/m/Y');
                    }

                    $dataPagoTemp->push([

                        "TipoCarteraNombre" => $desempleo_tipos_cartera->tipo_cartera->Nombre ?? '',
                        "Id" => $tasa_diferenciada->Id,
                        "PolizaDeuda" => $desempleo_tipos_cartera->PolizaDesempleo,
                        "TipoCalculo" => $desempleo_tipos_cartera->TipoCalculo,
                        "DesempleoTipoCartera" => $desempleo_tipos_cartera->Id,
                        "LineaCredito" => $linea_credito->Id,
                        "DescripcionLineaCredito" => $linea_credito ? $linea_credito->Descripcion : '',
                        "AbreviaturaLineaCredito" => $linea_credito ? $linea_credito->Abreviatura : '',
                        "Fecha" => $fecha,
                        "Monto" => $monto,
                        "FechaDesde" => $tasa_diferenciada->FechaDesde ?? null,
                        "FechaHasta" => $tasa_diferenciada->FechaHasta ?? null,
                        "MontoDesde" => $tasa_diferenciada->MontoDesde ?? null,
                        "MontoHasta" => $tasa_diferenciada->MontoHasta ?? null,

                        "Tasa" => $tasa_diferenciada->Tasa,
                    ]);
                }
            } else {


                $dataPagoId[] = $desempleo_tipos_cartera->Id;

                $linea_credito = SaldoMontos::findOrFail($desempleo_tipos_cartera->SaldosMontos);

                $dataPagoTemp->push([

                    "Id" => $desempleo_tipos_cartera->Id,
                    "TipoCalculo" => "No Aplica",
                    "DesempleoTipoCartera" => $desempleo_tipos_cartera->Id,
                    // Agregando los nuevos campos
                    "DescripcionLineaCredito" => $linea_credito ? $linea_credito->Descripcion : '',
                    "AbreviaturaLineaCredito" => $linea_credito ? $linea_credito->Abreviatura : '',
                    "Fecha" => "",
                    "Edad" => "",
                    "FechaDesde" => "",
                    "FechaHasta" => "",
                    "EdadDesde" => "",
                    "EdadHasta" => "",

                    "Tasa" => $desempleo->Tasa,
                ]);
            }
        }




        $dataPago = collect([]);

        //dd($dataPagoTemp);

        foreach ($dataPagoTemp as $item) {

            //por fechas
            if ($item['TipoCalculo'] == 1) {

                $total = DB::table('poliza_desempleo_cartera')
                    ->selectRaw('
                                        COALESCE(SUM(MontoOtorgado), 0) as MontoOtorgado,
                                        COALESCE(SUM(SaldoCapital), 0) as SaldoCapital,
                                        COALESCE(SUM(Intereses), 0) as Intereses,
                                        COALESCE(SUM(InteresesMoratorios), 0) as InteresesMoratorios,
                                        COALESCE(SUM(InteresesCovid), 0) as InteresesCovid,
                                        COALESCE(SUM(MontoNominal), 0) as MontoNominal,
                                        COALESCE(SUM(TotalCredito), 0) as TotalCredito
                                    ')
                    ->where('PolizaDesempleoDetalle', null)
                    ->where('PolizaDesempleo', $id)
                    ->where('DesempleoTipoCartera', $item['DesempleoTipoCartera'])
                    //->where('LineaCredito', $item['LineaCredito'])
                    ->whereBetween('FechaOtorgamientoDate', [$item['FechaDesde'], $item['FechaHasta']])
                    ->first();

                // dd($total);

                // Si $total es null, aseguramos que los valores sean 0
                $item['MontoOtorgado'] = $total->MontoOtorgado ?? 0;
                $item['SaldoCapital'] = $total->SaldoCapital ?? 0;
                $item['Intereses'] = $total->Intereses ?? 0;
                $item['InteresesMoratorios'] = $total->InteresesMoratorios ?? 0;
                $item['InteresesCovid'] = $total->InteresesCovid ?? 0;
                $item['MontoNominal'] = $total->MontoNominal ?? 0;
                $item['TotalCredito'] = $total->TotalCredito ?? 0;
                $item['PrimaCalculada'] = ($item['TotalCredito'] > 0 && $item['Tasa'] > 0) ? $item['TotalCredito'] * $item['Tasa'] : 0;
                $item['TipoCartera'] = $total->TipoCarteraNombre ?? '';
                //  dd($item);

                $dataPago->push($item);
            }
            //por edad
            else if ($item['TipoCalculo'] == 2) {
                $total = DB::table('poliza_desempleo_cartera')
                    ->selectRaw('
                                        COALESCE(SUM(MontoOtorgado), 0) as MontoOtorgado,
                                        COALESCE(SUM(SaldoCapital), 0) as SaldoCapital,
                                        COALESCE(SUM(Intereses), 0) as Intereses,
                                        COALESCE(SUM(InteresesMoratorios), 0) as InteresesMoratorios,
                                        COALESCE(SUM(InteresesCovid), 0) as InteresesCovid,
                                        COALESCE(SUM(MontoNominal), 0) as MontoNominal,
                                        COALESCE(SUM(TotalCredito), 0) as TotalCredito
                                    ')
                    ->where('PolizaDesempleoDetalle', null)
                    ->where('PolizaDesempleo', $id)
                    ->where('DesempleoTipoCartera', $item['DesempleoTipoCartera'])
                    ->whereBetween('TotalCredito', [$item['MontoDesde'], $item['MontoHasta']])
                    ->first();

                // Si $total es null, aseguramos que los valores sean 0
                $item['MontoOtorgado'] = $total->MontoOtorgado ?? 0;
                $item['SaldoCapital'] = $total->SaldoCapital ?? 0;
                $item['Intereses'] = $total->Intereses ?? 0;
                $item['InteresesMoratorios'] = $total->InteresesMoratorios ?? 0;
                $item['InteresesCovid'] = $total->InteresesCovid ?? 0;
                $item['MontoNominal'] = $total->MontoNominal ?? 0;
                $item['TotalCredito'] = $total->TotalCredito ?? 0;
                $item['PrimaCalculada'] = ($item['TotalCredito'] > 0 && $item['Tasa'] > 0)
                    ? $item['TotalCredito'] * $item['Tasa'] : 0;
                $item['TipoCartera'] = $total->TipoCartera ?? '';

                $dataPago->push($item);
            } else {
                $total = DB::table('poliza_desempleo_cartera')
                    ->selectRaw('
                                    COALESCE(SUM(MontoOtorgado), 0) as MontoOtorgado,
                                    COALESCE(SUM(SaldoCapital), 0) as SaldoCapital,
                                    COALESCE(SUM(Intereses), 0) as Intereses,
                                    COALESCE(SUM(InteresesMoratorios), 0) as InteresesMoratorios,
                                    COALESCE(SUM(InteresesCovid), 0) as InteresesCovid,
                                    COALESCE(SUM(MontoNominal), 0) as MontoNominal,
                                    COALESCE(SUM(TotalCredito), 0) as TotalCredito
                                ')
                    ->where('PolizaDesempleoDetalle', null)
                    ->where('PolizaDesempleo', $id)
                    ->where('DesempleoTipoCartera', $item['DesempleoTipoCartera'])
                    ->first();

                // Si $total es null, aseguramos que los valores sean 0
                $item['MontoOtorgado'] = $total->MontoOtorgado ?? 0;
                $item['SaldoCapital'] = $total->SaldoCapital ?? 0;
                $item['Intereses'] = $total->Intereses ?? 0;
                $item['InteresesMoratorios'] = $total->InteresesMoratorios ?? 0;
                $item['InteresesCovid'] = $total->InteresesCovid ?? 0;
                $item['MontoNominal'] = $total->MontoNominal ?? 0;
                $item['TotalCredito'] = $total->TotalCredito ?? 0;
                $item['PrimaCalculada'] = ($item['TotalCredito'] > 0 && $item['Tasa'] > 0)
                    ? $item['TotalCredito'] * $item['Tasa'] : 0;

                $item['TipoCartera'] =  '';

                $dataPago->push($item);
            }
        }


        $saldoCapital = 0.00;
        $intereses = 0.00;
        $montoNominal = 0.00;
        $interesesCovid = 0.00;
        $interesesMoratorios = 0.00;
        $total = 0;


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

        $detalle = DesempleoDetalle::where('Desempleo', $desempleo->Id)->orderBy('Id', 'desc')->get();

        $tipo_contribuyente = $desempleo->cliente->TipoContribuyente;
        $retencion_comision = 0;
        $valor_comision = $primaCobrar * ((float) ($desempleo->TasaComision ?? 0) / 100);
        if ($tipo_contribuyente != 1) {
            $retencion_comision = (float) $valor_comision * 0.01;
        }
        $iva_comision = $valor_comision * 0.13;
        $sub_total_ccf = $valor_comision + $iva_comision;
        $comision_ccf = $sub_total_ccf - $retencion_comision;
        $liquidoApagar = $primaCobrar - $comision_ccf;
        $prima_descontada = ($subtotal + $extraPrima) - $descuento;
        $comentarios = Comentario::where('Desempleo', $desempleo->Id)->where('Activo', '=', 1)->get();
        $ultimo_pago = DesempleoDetalle::where('Desempleo', $desempleo->Id)->where('Activo', 1) //->where('PagoAplicado', '<>', null)
            ->orderBy('Id', 'desc')->first();

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
            "primaCobrar" => $primaCobrar,
            "tasaComision" => $desempleo->TasaComision,
            "valorComision" => $valor_comision,
            "ivaComision" => $iva_comision,
            "subTotalCcf" => $sub_total_ccf,
            "retencionComision" => $retencion_comision,
            "comisionCcf" => $comision_ccf,
            "liquidoApagar" => $liquidoApagar,
            "primaDescontada" => $prima_descontada
        ];


        //dd($detalle);

        // Retornar la vista con los datos de la póliza
        return view('polizas.desempleo.show', compact(
            'desempleo',
            'data',
            'tab',
            'meses',
            'fechaInicio',
            'fechaFinal',
            'mes',
            'axo',
            'anios',
            'fechas',
            'detalle',
            'comentarios',
            'ultimo_pago',
            'dataPago',
            'dataPagoId'
        ));
        // } catch (\Exception $e) {
        //     alert()->error('No se pudo encontrar la póliza de desempleo solicitada.');
        //     return back();
        // }
    }

    public function recibo_pago($id, Request $request)
    {

        $detalle = DesempleoDetalle::findOrFail($id);

        $desempleo = Desempleo::findOrFail($detalle->Desempleo);

        $cliente = Cliente::findOrFail($desempleo->Asegurado);

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $detalle->SaldoA = $request->SaldoA;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->Referencia = $request->Referencia;
        $detalle->Anexo = $request->Anexo;
        $detalle->NumeroCorrelativo = $request->NumeroCorrelativo;
        $detalle->update();
        //$calculo = $this->monto($residencia, $detalle);

        $recibo_historial = $this->save_recibo($detalle, $desempleo);
        $configuracion = ConfiguracionRecibo::first();
        $pdf = \PDF::loadView('polizas.desempleo.recibo', compact('configuracion', 'cliente', 'recibo_historial', 'detalle', 'desempleo', 'meses'))->setWarnings(false)->setPaper('letter');
        return $pdf->stream('Recibo.pdf');

        //  return back();
    }

    public function save_recibo($detalle, $desempleo)
    {
        //      dd($detalle);
        $recibo_historial = new DesempleoHistorialRecibo();
        $recibo_historial->PolizaDesempleoDetalle = $detalle->Id;
        $recibo_historial->ImpresionRecibo = $detalle->ImpresionRecibo; //Carbon::now();
        $recibo_historial->NombreCliente = $desempleo->cliente->Nombre;
        $recibo_historial->NitCliente = $desempleo->cliente->Nit;
        $recibo_historial->DireccionResidencia = $desempleo->cliente->DireccionResidencia ?? '(vacio)';
        $recibo_historial->Departamento = $desempleo->cliente->distrito->municipio->departamento->Nombre;
        $recibo_historial->Municipio = $desempleo->cliente->distrito->municipio->Nombre;
        $recibo_historial->NumeroRecibo = $detalle->NumeroRecibo;
        $recibo_historial->CompaniaAseguradora = $desempleo->aseguradora->Nombre;
        // $recibo_historial->ProductoSeguros = $desempleo->planes->productos->Nombre;
        $recibo_historial->NumeroPoliza = $desempleo->NumeroPoliza;
        $recibo_historial->VigenciaDesde = $desempleo->VigenciaDesde;
        $recibo_historial->VigenciaHasta = $desempleo->VigenciaHasta;
        $recibo_historial->FechaInicio = $detalle->FechaInicio;
        $recibo_historial->FechaFin = $detalle->FechaFinal;
        $recibo_historial->Anexo = $detalle->Anexo;
        $recibo_historial->Referencia = $detalle->Referencia;
        $recibo_historial->FacturaNombre = $desempleo->cliente->Nombre;
        $recibo_historial->MontoCartera = $detalle->MontoCartera;
        $recibo_historial->PrimaCalculada = $detalle->PrimaCalculada;
        $recibo_historial->ExtraPrima = $detalle->ExtraPrima;
        $recibo_historial->Descuento = $detalle->Descuento ?? 0;
        $recibo_historial->PordentajeDescuento = $desempleo->Descuento;
        $recibo_historial->PrimaDescontada = $detalle->PrimaDescontada;
        $recibo_historial->ValorCCF = $detalle->ValorCCF;
        $recibo_historial->TotalAPagar = $detalle->APagar;
        $recibo_historial->TasaComision = $desempleo->TasaComision ?? 0;
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


    public function edit_pago(Request $request)
    {

        $detalle = DesempleoDetalle::findOrFail($request->Id);

        $desempleo = Desempleo::findOrFail($detalle->Desempleo);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];


        if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
            $detalle->SaldoA = $request->SaldoA;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->Comentario = $request->Comentario;
            $detalle->update();

            $recibo_historial = $this->save_recibo($detalle, $desempleo);
            $configuracion = ConfiguracionRecibo::first();
            $pdf = \PDF::loadView('polizas.desempleo.recibo', compact('configuracion', 'recibo_historial', 'detalle', 'desempleo', 'meses'))->setWarnings(false)->setPaper('letter');
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
        $comen->DetalleDesempleo = $detalle->Id;
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Desempleo = $detalle->Desempleo;
        $comen->save();

        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }


    public function get_pago($id)
    {
        return DesempleoDetalle::findOrFail($id);
    }

    public function anular_pago($id)
    {
        $detalle = DesempleoDetalle::findOrFail($id);
        $detalle->Activo = 0;
        $detalle->update();
        //recibo anulado
        DesempleoHistorialRecibo::where('PolizaDesempleoDetalle', $id)->update(['Activo' => 0]);

        DesempleoCartera::where('PolizaDesempleoDetalle', $id)->delete();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function delete_pago($id)
    {
        $detalle = DesempleoDetalle::findOrFail($id);

        // recibo eliminado
        DesempleoHistorialRecibo::where('PolizaDesempleoDetalle', $id)->delete();

        DesempleoCartera::where('PolizaDesempleoDetalle', $id)->delete();
        $detalle->delete();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function get_recibo($id, $exportar)
    {
        if (!isset($exportar)) {
            $exportar = 1;
        }
        //dd($exportar);
        $detalle = DesempleoDetalle::findOrFail($id);

        $desempleo = Desempleo::findOrFail($detalle->Desempleo);
        $cliente = Cliente::find($desempleo->Asegurado);

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = DesempleoHistorialRecibo::where('PolizaDesempleoDetalle', $id)->orderBy('id', 'desc')->first();
        //  $calculo = $this->monto($desempleo, $detalle);
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($detalle, $desempleo);
            //dd("insert");
        }

        /*  if ($exportar == 2) {
            return Excel::download(new DesempleoReciboExport($id), 'Recibo.xlsx');
            //return view('polizas.desempleo.recibo', compact('recibo_historial','detalle', 'desempleo', 'meses','exportar'));
        }*/

        $configuracion = ConfiguracionRecibo::first();
        $pdf = \PDF::loadView('polizas.desempleo.recibo', compact('configuracion', 'cliente', 'recibo_historial', 'detalle', 'desempleo', 'meses', 'exportar'))->setWarnings(false)->setPaper('letter');
        //  dd($detalle);
        return $pdf->stream('Recibos.pdf');
    }

    public function get_recibo_edit($id)
    {
        $detalle = DesempleoDetalle::findOrFail($id);
        $desempleo = Desempleo::findOrFail($detalle->Desempleo);
        $recibo_historial = DesempleoHistorialRecibo::where('PolizaDesempleoDetalle', $id)->orderBy('id', 'desc')->first();
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($detalle, $desempleo);
            //dd("insert");
        }
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = DesempleoHistorialRecibo::where('PolizaDesempleoDetalle', $id)->orderBy('id', 'desc')->first();

        if ($recibo_historial->DireccionResidencia == '' || $recibo_historial->DireccionResidencia == '(vacio)') {
            $recibo_historial->DireccionResidencia =
                $detalle->poliza_desempleo?->cliente?->DireccionResidencia
                ?? $detalle->poliza_desempleo?->cliente?->DireccionCorrespondencia
                ?? '';
        }

        if ($recibo_historial->ProductoSeguros == '') {
            $recibo_historial->ProductoSeguros =  $detalle->poliza_desempleo?->planes->productos->Nombre ?? '';
        }

        //dd($recibo_historial);
        $configuracion = ConfiguracionRecibo::first();


        return view('polizas.desempleo.recibo_edit', compact('configuracion', 'recibo_historial', 'meses'));
    }

    public function get_recibo_update(Request $request)
    {
        //modificación de ultimo recibo
        $id = $request->id_desempleo_detalle;
        $detalle = DesempleoDetalle::findOrFail($id);

        $desempleo = Desempleo::findOrFail($detalle->Desempleo);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $impresion_recibo = $request->AxoImpresionRecibo . '-' . $request->MesImpresionRecibo . '-' . $request->DiaImpresionRecibo;

        $recibo_historial_anterior = DesempleoHistorialRecibo::where('PolizaDesempleoDetalle', $id)->orderBy('id', 'desc')->first();

        $recibo_historial = new DesempleoHistorialRecibo();
        $recibo_historial->PolizaDesempleoDetalle = $id;
        //este valor cambia por eso no se manda al metodo de save_recibo
        $recibo_historial->ImpresionRecibo = Carbon::parse($impresion_recibo);
        $recibo_historial->NombreCliente = $request->NombreCliente;
        $recibo_historial->NitCliente = $request->NitCliente;
        $recibo_historial->DireccionResidencia = $request->DireccionResidencia;
        //$recibo_historial->NumeroRecibo = $request->NumeroRecibo;
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

        $recibo_historial->FechaVencimiento    = $request->FechaVencimiento;
        $recibo_historial->NumeroCorrelativo   = $request->NumeroCorrelativo ?? '01';
        $recibo_historial->Cuota               = $request->Cuota ?? '01/01';

        // 🔹 Copiar campos del recibo anterior (si existe)
        if ($recibo_historial_anterior) {
            $recibo_historial->Departamento        = $recibo_historial_anterior->Departamento;
            $recibo_historial->Municipio           = $recibo_historial_anterior->Municipio;
            $recibo_historial->MontoCartera        = $recibo_historial_anterior->MontoCartera;
            $recibo_historial->PrimaCalculada      = $recibo_historial_anterior->PrimaCalculada;
            $recibo_historial->ExtraPrima          = $recibo_historial_anterior->ExtraPrima;
            $recibo_historial->Descuento           = $recibo_historial_anterior->Descuento;
            $recibo_historial->PordentajeDescuento = $recibo_historial_anterior->PordentajeDescuento;
            $recibo_historial->PrimaDescontada     = $recibo_historial_anterior->PrimaDescontada;
            $recibo_historial->ValorCCF            = $recibo_historial_anterior->ValorCCF;
            $recibo_historial->TotalAPagar         = $recibo_historial_anterior->TotalAPagar;
            $recibo_historial->TasaComision        = $recibo_historial_anterior->TasaComision;
            $recibo_historial->Comision            = $recibo_historial_anterior->Comision;
            $recibo_historial->IvaSobreComision    = $recibo_historial_anterior->IvaSobreComision;
            $recibo_historial->SubTotalComision    = $recibo_historial_anterior->SubTotalComision;
            $recibo_historial->Retencion           = $recibo_historial_anterior->Retencion;
            $recibo_historial->ValorCCF            = $recibo_historial_anterior->ValorCCF;
            $recibo_historial->Otros               = $recibo_historial_anterior->Otros ?? 0;
        }
        $recibo_historial->Usuario = auth()->user()->id;

        $recibo_historial->save();
        //dd("insert");
        return back()->with('success', 'Actualización de Recibo Exitosa');
    }


    public function agregar_pago(Request $request)
    {

        $desempleo = Desempleo::findOrFail($request->Desempleo);
        $time = Carbon::now('America/El_Salvador');

        $recibo = DatosGenerales::orderByDesc('Id_recibo')->first();


        $detalle = new DesempleoDetalle();
        $detalle->FechaInicio = $request->FechaInicio;
        $detalle->FechaFinal = $request->FechaFinal;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->Desempleo = $request->Desempleo;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaCalculada = $request->PrimaCalculada;
        $detalle->Descuento = $request->Descuento;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        $detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
        $detalle->GastosEmision = $request->GastosEmision;
        $detalle->Otros = $request->Otros;
        $detalle->Axo = $request->Axo;
        $detalle->Mes = $request->Mes;
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
        $detalle->ExcelURL = $request->ExcelURL;
        $detalle->NumeroRecibo = ($recibo->Id_recibo) + 1;
        $detalle->Usuario = auth()->user()->id;
        $detalle->FechaIngreso = $time->format('Y-m-d');
        $detalle->save();

        DesempleoCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaDesempleo', $request->Desempleo)->delete();
        $cartera = DesempleoCartera::where('PolizaDesempleo', '=', $request->Desempleo)->where('FechaInicio', '=', $request->FechaInicio)->where('FechaFinal', '=', $request->FechaFinal)->update(['PolizaDesempleoDetalle' => $detalle->Id]);

        $comen = new Comentario();
        $comen->Comentario = 'Se agrego el pago de la cartera';
        $comen->Activo = 1;
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Desempleo = $request->Desempleo;
        $comen->DetalleDesempleo = $detalle->Id;
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



    public function store_poliza(Request $request, $id)
    {
        $mes = $request->MesActual; // El formato 'm' devuelve el mes con ceros iniciales (por ejemplo, "02")
        $anio = $request->AxoActual;

        $desempleo = Desempleo::findOrFail($id);

        // eliminando datos de la cartera si existieran
        DesempleoCartera::where('Axo', $anio)->where('Mes', $mes + 0)->where('PolizaDesempleo', $id)->delete();


        $tempData = DesempleoCarteraTemp::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('NoValido', 0)
            ->where('PolizaDesempleo', $id)
            ->get();

        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {
            //try {
            $poliza = new DesempleoCartera();
            $poliza->PolizaDesempleo = $tempRecord->PolizaDesempleo;
            $poliza->Dui = $tempRecord->Dui;
            $poliza->CarnetResidencia = $tempRecord->CarnetResidencia;
            $poliza->Pasaporte = $tempRecord->Pasaporte;
            $poliza->Nacionalidad = $tempRecord->Nacionalidad;
            $poliza->FechaNacimiento = $tempRecord->FechaNacimiento;
            $poliza->TipoPersona = $tempRecord->TipoPersona;
            $poliza->Sexo = $tempRecord->Sexo;
            $poliza->PrimerApellido = $tempRecord->PrimerApellido;
            $poliza->SegundoApellido = $tempRecord->SegundoApellido;
            $poliza->ApellidoCasada = $tempRecord->ApellidoCasada;
            $poliza->PrimerNombre = $tempRecord->PrimerNombre;
            $poliza->SegundoNombre = $tempRecord->SegundoNombre;
            $poliza->NombreSociedad = $tempRecord->NombreSociedad;

            $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento;
            $poliza->FechaVencimiento = $tempRecord->FechaVencimiento;
            $poliza->NumeroReferencia = $tempRecord->NumeroReferencia;
            $poliza->MontoOtorgado = $tempRecord->MontoOtorgado;
            $poliza->SaldoCapital = $tempRecord->SaldoCapital;
            $poliza->Intereses = $tempRecord->Intereses;
            $poliza->MoraCapital = $tempRecord->MoraCapital;
            $poliza->InteresesMoratorios = $tempRecord->InteresesMoratorios;
            $poliza->InteresesCovid = $tempRecord->InteresesCovid;
            $poliza->SaldoTotal = $tempRecord->SaldoTotal;

            $poliza->EdadDesembloso = $tempRecord->EdadDesembloso;
            $poliza->FechaOtorgamientoDate = $tempRecord->FechaOtorgamientoDate;
            $poliza->User = $tempRecord->User;
            $poliza->Axo = $tempRecord->Axo;
            $poliza->Mes = $tempRecord->Mes;
            $poliza->FechaInicio = $tempRecord->FechaInicio;
            $poliza->FechaFinal = $tempRecord->FechaFinal;
            $poliza->TipoError = $tempRecord->TipoError;
            $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate;
            $poliza->Edad = $tempRecord->Edad;

            $poliza->DesempleoTipoCartera = $tempRecord->DesempleoTipoCartera;
            $poliza->NoValido = $tempRecord->NoValido;

            $poliza->Excluido = $tempRecord->Excluido;
            $poliza->Rehabilitado = $tempRecord->Rehabilitado;
            $poliza->EdadRequisito = $tempRecord->EdadRequisito;
            $poliza->SaldosMontos = $tempRecord->SaldosMontos;
            $poliza->TotalCredito = $tempRecord->TotalCredito;
            $poliza->Tasa = $tempRecord->Tasa;
            $poliza->save();
        }


        // NUEVO BLOQUE — Guardar los datos de la tabla temporal en el historial
        DB::table('poliza_desempleo_cartera_temp_historial')
            ->where('Axo', $anio)
            ->where('Mes', $mes)
            ->where('PolizaDesempleo', $id)
            ->delete();


        DB::statement("
            INSERT INTO poliza_desempleo_cartera_temp_historial (
                SaldosMontos,
                PolizaDesempleo,
                Dui,
                Pasaporte,
                CarnetResidencia,
                Nacionalidad,
                FechaNacimiento,
                TipoPersona,
                Sexo,
                PrimerApellido,
                SegundoApellido,
                ApellidoCasada,
                PrimerNombre,
                SegundoNombre,
                NombreSociedad,
                FechaOtorgamiento,
                FechaVencimiento,
                NumeroReferencia,
                MontoOtorgado,
                SaldoCapital,
                Intereses,
                MoraCapital,
                InteresesMoratorios,
                InteresesCovid,
                Tarifa,
                TipoDeuda,
                PorcentajeExtraprima,
                SaldoTotal,
                User,
                Axo,
                Mes,
                FechaInicio,
                FechaFinal,
                TipoError,
                FechaNacimientoDate,
                FechaOtorgamientoDate,
                Edad,
                EdadDesembloso,
                NoValido,
                Excluido,
                Rehabilitado,
                EdadRequisito,
                DesempleoTipoCartera,
                TotalCredito,
                Tasa
            )
            SELECT
                SaldosMontos,
                PolizaDesempleo,
                Dui,
                Pasaporte,
                CarnetResidencia,
                Nacionalidad,
                FechaNacimiento,
                TipoPersona,
                Sexo,
                PrimerApellido,
                SegundoApellido,
                ApellidoCasada,
                PrimerNombre,
                SegundoNombre,
                NombreSociedad,
                FechaOtorgamiento,
                FechaVencimiento,
                NumeroReferencia,
                MontoOtorgado,
                SaldoCapital,
                Intereses,
                MoraCapital,
                InteresesMoratorios,
                InteresesCovid,
                Tarifa,
                TipoDeuda,
                PorcentajeExtraprima,
                SaldoTotal,
                User,
                Axo,
                Mes,
                FechaInicio,
                FechaFinal,
                TipoError,
                FechaNacimientoDate,
                FechaOtorgamientoDate,
                Edad,
                EdadDesembloso,
                NoValido,
                Excluido,
                Rehabilitado,
                EdadRequisito,
                DesempleoTipoCartera,
                TotalCredito,
                Tasa
            FROM poliza_desempleo_cartera_temp
            WHERE Axo = ? AND Mes = ? AND PolizaDesempleo = ?
        ", [$anio, $mes, $id]);

        // 🔹 Eliminar datos de la cartera temporal (como ya hacías)
        DesempleoCarteraTemp::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('PolizaDesempleo', $id)
            ->delete();


        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/desempleo/' . $id . '?tab=2');
    }


    public function primera_carga(Request $request)
    {
        $mes = $request->MesActual;
        $anio = $request->AxoActual;


        // eliminando datos de la cartera si existieran
        $tempData = DesempleoCartera::where('Axo', $anio)
            ->where('Mes', $mes + 0)->where('PolizaDesempleo', $request->Desempleo)->delete();


        // Obtener los datos de la tabla temporal
        $tempData = DesempleoCarteraTemp::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('PolizaDesempleo', $request->Desempleo)
            ->get();
        //dd($tempData);

        // Iterar sobre los resultados y realizar la inserción en la tabla principal
        foreach ($tempData as $tempRecord) {

            //try {
            $poliza = new DesempleoCartera();
            $poliza->PolizaDesempleo = $tempRecord->PolizaDesempleo;
            $poliza->Dui = $tempRecord->Dui;
            $poliza->CarnetResidencia = $tempRecord->CarnetResidencia;
            $poliza->Pasaporte = $tempRecord->Pasaporte;
            $poliza->Nacionalidad = $tempRecord->Nacionalidad;
            $poliza->FechaNacimiento = $tempRecord->FechaNacimiento;
            $poliza->TipoPersona = $tempRecord->TipoPersona;
            $poliza->Sexo = $tempRecord->Sexo;
            $poliza->PrimerApellido = $tempRecord->PrimerApellido;
            $poliza->SegundoApellido = $tempRecord->SegundoApellido;
            $poliza->ApellidoCasada = $tempRecord->ApellidoCasada;
            $poliza->PrimerNombre = $tempRecord->PrimerNombre;
            $poliza->SegundoNombre = $tempRecord->SegundoNombre;
            $poliza->NombreSociedad = $tempRecord->NombreSociedad;

            $poliza->FechaOtorgamiento = $tempRecord->FechaOtorgamiento;
            $poliza->FechaVencimiento = $tempRecord->FechaVencimiento;
            $poliza->NumeroReferencia = $tempRecord->NumeroReferencia;
            $poliza->MontoOtorgado = $tempRecord->MontoOtorgado;
            $poliza->SaldoCapital = $tempRecord->SaldoCapital;
            $poliza->Intereses = $tempRecord->Intereses;
            $poliza->MoraCapital = $tempRecord->MoraCapital;
            $poliza->InteresesMoratorios = $tempRecord->InteresesMoratorios;
            $poliza->InteresesCovid = $tempRecord->InteresesCovid;
            $poliza->SaldoTotal = $tempRecord->SaldoTotal;

            $poliza->EdadDesembloso = $tempRecord->EdadDesembloso;
            $poliza->FechaOtorgamientoDate = $tempRecord->FechaOtorgamientoDate;
            $poliza->User = $tempRecord->User;
            $poliza->Axo = $tempRecord->Axo;
            $poliza->Mes = $tempRecord->Mes;
            $poliza->FechaInicio = $tempRecord->FechaInicio;
            $poliza->FechaFinal = $tempRecord->FechaFinal;
            $poliza->TipoError = $tempRecord->TipoError;
            $poliza->FechaNacimientoDate = $tempRecord->FechaNacimientoDate;
            $poliza->Edad = $tempRecord->Edad;

            $poliza->DesempleoTipoCartera = $tempRecord->DesempleoTipoCartera;
            $poliza->NoValido = $tempRecord->NoValido;

            $poliza->Excluido = $tempRecord->Excluido;
            $poliza->Rehabilitado = $tempRecord->Rehabilitado;
            $poliza->EdadRequisito = $tempRecord->EdadRequisito;
            $poliza->SaldosMontos = $tempRecord->SaldosMontos;
            $poliza->TotalCredito = $tempRecord->TotalCredito;
            $poliza->Tasa = $tempRecord->Tasa;
            $poliza->save();
            // } catch (\Exception $e) {
            //     // Captura errores y los guarda en el log
            //     Log::error("Error al insertar en poliza_vida_cartera: " . $e->getMessage(), [
            //         'NumeroReferencia' => $tempRecord->NumeroReferencia,
            //         'Usuario' => auth()->user()->id ?? 'N/A',
            //         'Datos' => $tempRecord
            //     ]);
            // }
        }


        // NUEVO BLOQUE — Guardar los datos de la tabla temporal en el historial
        DB::table('poliza_desempleo_cartera_temp_historial')
            ->where('Axo', $anio)
            ->where('Mes', $mes)
            ->where('PolizaDesempleo', $request->Desempleo)
            ->delete();


        DB::statement("
            INSERT INTO poliza_desempleo_cartera_temp_historial (
                SaldosMontos,
                PolizaDesempleo,
                Dui,
                Pasaporte,
                CarnetResidencia,
                Nacionalidad,
                FechaNacimiento,
                TipoPersona,
                Sexo,
                PrimerApellido,
                SegundoApellido,
                ApellidoCasada,
                PrimerNombre,
                SegundoNombre,
                NombreSociedad,
                FechaOtorgamiento,
                FechaVencimiento,
                NumeroReferencia,
                MontoOtorgado,
                SaldoCapital,
                Intereses,
                MoraCapital,
                InteresesMoratorios,
                InteresesCovid,
                Tarifa,
                TipoDeuda,
                PorcentajeExtraprima,
                SaldoTotal,
                User,
                Axo,
                Mes,
                FechaInicio,
                FechaFinal,
                TipoError,
                FechaNacimientoDate,
                FechaOtorgamientoDate,
                Edad,
                EdadDesembloso,
                NoValido,
                Excluido,
                Rehabilitado,
                EdadRequisito,
                DesempleoTipoCartera,
                TotalCredito,
                Tasa
            )
            SELECT
                SaldosMontos,
                PolizaDesempleo,
                Dui,
                Pasaporte,
                CarnetResidencia,
                Nacionalidad,
                FechaNacimiento,
                TipoPersona,
                Sexo,
                PrimerApellido,
                SegundoApellido,
                ApellidoCasada,
                PrimerNombre,
                SegundoNombre,
                NombreSociedad,
                FechaOtorgamiento,
                FechaVencimiento,
                NumeroReferencia,
                MontoOtorgado,
                SaldoCapital,
                Intereses,
                MoraCapital,
                InteresesMoratorios,
                InteresesCovid,
                Tarifa,
                TipoDeuda,
                PorcentajeExtraprima,
                SaldoTotal,
                User,
                Axo,
                Mes,
                FechaInicio,
                FechaFinal,
                TipoError,
                FechaNacimientoDate,
                FechaOtorgamientoDate,
                Edad,
                EdadDesembloso,
                NoValido,
                Excluido,
                Rehabilitado,
                EdadRequisito,
                DesempleoTipoCartera,
                TotalCredito,
                Tasa
            FROM poliza_desempleo_cartera_temp
            WHERE Axo = ? AND Mes = ? AND PolizaDesempleo = ?
        ", [$anio, $mes, $request->Desempleo]);

        // 🔹 Eliminar datos de la cartera temporal (como ya hacías)
        DesempleoCarteraTemp::where('Axo', $anio)
            ->where('Mes', $mes + 0)
            ->where('PolizaDesempleo', $request->Desempleo)
            ->delete();


        alert()->success('El registro de poliza ha sido ingresado correctamente');
        return redirect('polizas/desempleo/' . $request->Desempleo . '?tab=2');
    }


    public function borrar_proceso_actual(Request $request, $id)
    {
        //borrar datos de tabla temporal
        DesempleoCarteraTemp::where('User', auth()->user()->id)->where('PolizaDesempleo', $id)->delete();

        return redirect('polizas/desempleo/' . $id . '?tab=2');
    }

    public function get_no_valido($id)
    {
        try {
            $desempleo = Desempleo::findOrFail($id);

            $count = DesempleoCarteraTemp::where('User', auth()->user()->id)
                ->where('PolizaDesempleo', $id)
                ->where('EdadDesembloso', '>', $desempleo->EdadMaximaInscripcion)
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

            $temp = DesempleoCarteraTemp::findOrFail($id);

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
        $desempleo = Desempleo::findOrFail($id);
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();


        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //desempleo
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $saldos = SaldoMontos::where('Activo', 1)->get();
        $tab = 1;

        //dd($tipoCartera);
        return view('polizas.desempleo.edit', compact(
            'tab',
            'desempleo',
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

    public function update(Request $request, $id)
    {

        $request->validate([
            'NumeroPoliza' => 'required|string|max:255',
            'Aseguradora' => 'required|exists:aseguradora,Id',
            'Asegurado' => 'required|exists:cliente,Id',
            'Nit' => 'required|string|max:255',
            'Ejecutivo' => 'required|exists:ejecutivo,Id',
            'TasaDiferenciada' => 'required|in:0,1',
            'Tasa' => 'nullable|required_if:TasaDiferenciada,0|numeric|min:0.00001',
            'VigenciaDesde' => 'required|date',
            'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
            'EdadTerminacion' => 'required|numeric|min:18',
            'EdadMaximaInscripcion' => 'required|numeric|min:18',
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
            'Ejecutivo.exists' => 'El Ejecutivo seleccionado no es válido.',
            'Saldos.required' => 'Debes seleccionar una opción de saldo y montos.',
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
            'Tasa.required_if' => 'El campo Tasa es obligatorio cuando la tasa diferenciada está desactivada.',
            'Tasa.numeric' => 'El campo Tasa debe ser un número.',
            'Tasa.min' => 'El campo Tasa debe ser al menos 0.00001.',
            'Concepto.string' => 'El campo Concepto debe ser una cadena de texto.',
            'Concepto.max' => 'El campo Concepto no debe exceder los 1000 caracteres.',
        ]);


        try {
            // Crear una nueva instancia del modelo Desempleo
            $desempleo = Desempleo::findOrFail($id);

            // Asignar los valores del formulario a los atributos del modelo
            $desempleo->NumeroPoliza = $request->NumeroPoliza;
            $desempleo->Asegurado = $request->Asegurado;
            $desempleo->Aseguradora = $request->Aseguradora;
            $desempleo->Ejecutivo = $request->Ejecutivo;
            $desempleo->TasaDiferenciada = $request->TasaDiferenciada;
            $desempleo->VigenciaDesde = $request->VigenciaDesde;
            $desempleo->VigenciaHasta = $request->VigenciaHasta;
            $desempleo->Tasa = $request->Tasa;
            $desempleo->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $desempleo->EdadMaxima = $request->EdadTerminacion;
            $desempleo->EstadoPoliza = 1;
            $desempleo->Descuento = $request->Descuento;
            $desempleo->Concepto = $request->Concepto;
            $desempleo->ClausulasEspeciales = $request->ClausulasEspeciales;
            $desempleo->Beneficios = $request->Beneficios;
            $desempleo->Plan = $request->Planes;
            $desempleo->EstadoPoliza = $request->EstadoPoliza;
            //$desempleo->Usuario = auth()->id();
            $desempleo->update();

            alert()->success('Éxito', 'La póliza de desempleo se ha modificado correctamente.');
            //return Redirect::to('polizas/desempleo');
            return back();
        } catch (\Exception $e) {

            alert()->error('Error', 'Ocurrió un error al crear la póliza de desempleo: ' . $e->getMessage())->persistent('Ok');
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        //
    }

    public function registros_edad_maxima($id)
    {
        return Excel::download(new EdadMaximaExport($id), 'creditos_edad_maxima.xlsx');
    }

    public function registros_responsabilidad_maxima($id)
    {
        return Excel::download(new EdadInscripcionExport($id), 'creditos_responsabilidad_maxima.xlsx');
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


    public function tasa_diferenciada($id)
    {
        $desempleo = Desempleo::findOrFail($id);

        return view('polizas.desempleo.tasa_diferenciada', compact('desempleo'));
    }
}
