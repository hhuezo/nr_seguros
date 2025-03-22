<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\DesempleoCarteraTempImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
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
use App\Models\polizas\DesempleoCarteraTemp;
use App\Models\polizas\DesempleoDetalle;
use App\Models\polizas\DesempleoHistorialRecibo;
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
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 2)->get(); //desempleo
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $saldos = SaldoMontos::where('Activo', 1)->get();

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
            'anios',
            'anioSeleccionado',
            'fechas',
            'detalle',
            'comentarios',
            'ultimo_pago'
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
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $detalle->SaldoA = $request->SaldoA;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->Referencia = $request->Referencia;
        $detalle->Anexo = $request->Anexo;
        $detalle->NumeroCorrelativo = $request->NumeroCorrelativo;
        $detalle->update();
        //$calculo = $this->monto($residencia, $detalle);

        $recibo_historial = $this->save_recibo($detalle, $desempleo);
        $pdf = \PDF::loadView('polizas.desempleo.recibo', compact('recibo_historial', 'detalle', 'desempleo', 'meses'))->setWarnings(false)->setPaper('letter');
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
        //dd($detalle);

        $desempleo = Desempleo::findOrFail($detalle->Desempleo);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];


        if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
            $detalle->SaldoA = $request->SaldoA;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->Comentario = $request->Comentario;
            $detalle->update();

            $recibo_historial = $this->save_recibo($detalle, $desempleo);
            $pdf = \PDF::loadView('polizas.desempleo.recibo', compact('recibo_historial', 'detalle', 'desempleo', 'meses'))->setWarnings(false)->setPaper('letter');
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


        $pdf = \PDF::loadView('polizas.desempleo.recibo', compact('recibo_historial', 'detalle', 'desempleo', 'meses', 'exportar'))->setWarnings(false)->setPaper('letter');
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
        //dd($recibo_historial);
        return view('polizas.desempleo.recibo_edit', compact('recibo_historial', 'meses'));
    }

    public function get_recibo_update(Request $request)
    {
        //modificación de ultimo recibo
        $id = $request->id_desempleo_detalle;
        $detalle = DesempleoDetalle::findOrFail($id);

        $desempleo = Desempleo::findOrFail($detalle->Desempleo);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $impresion_recibo = $request->AxoImpresionRecibo . '-' . $request->MesImpresionRecibo . '-' . $request->DiaImpresionRecibo;

        $recibo_historial = new DesempleoHistorialRecibo();
        $recibo_historial->PolizaDesempleoDetalle = $id;
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
        return redirect('polizas/desempleo/' . $desempleo->Id . '/edit');
    }


    public function agregar_pago(Request $request)
    {

        $desempleo = Desempleo::findOrFail($request->Desempleo);
        $time = Carbon::now('America/El_Salvador');

        $recibo = DatosGenerales::orderByDesc('Id_recibo')->first();
        // if (!$request->ExcelURL) {
        //     alert()->error('No se puede generar el pago, falta subir cartera')->showConfirmButton('Aceptar', '#3085d6');
        // } else {

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
        $detalle->ExcelURL = $request->ExcelURL;
        $detalle->NumeroRecibo = ($recibo->Id_recibo) + 1;
        $detalle->Usuario = auth()->user()->id;
        $detalle->FechaIngreso = $time->format('Y-m-d');
        $detalle->save();

        DesempleoCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaDesempleo', $request->Desempleo)->delete();
        $cartera = DesempleoCartera::where('FechaInicio', '=', $request->FechaInicio)->where('FechaFinal', '=', $request->FechaFinal)->update(['PolizaDesempleoDetalle' => $detalle->Id]);

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
            //dd($mesAnterior,$axoAnterior,$request->Desempleo);
            $registros_eliminados = DB::table('poliza_desempleo_cartera AS pdc')
                ->leftJoin('poliza_desempleo_cartera_temp AS pdtc', function ($join) {
                    $join->on('pdc.NumeroReferencia', '=', 'pdtc.NumeroReferencia')
                        ->where('pdtc.User', auth()->user()->id);
                })
                ->where('pdc.Mes', (int)$mesAnterior)
                ->where('pdc.Axo', (int)$axoAnterior)
                ->where('pdc.PolizaDesempleo', $id)
                ->whereNull('pdtc.NumeroReferencia') // Solo los que no están en poliza_desempleo_temp_cartera
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
