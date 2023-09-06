<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\PolizaResidenciaTempCarteraImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\DetalleResidencia;
use App\Models\polizas\PolizaResidenciaCartera;
use App\Models\polizas\Residencia;
use App\Models\temp\PolizaResidenciaTempCartera;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ResidenciaController extends Controller
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
        $today = Carbon::now()->toDateString();

        session(['MontoCartera' => 0]);
        session(['FechaInicio' => $today]);
        session(['FechaFinal' => $today]);
        session(['ExcelURL' => '']);

        $residencias = Residencia::where('Activo', 1)->get();
        return view('polizas.residencia.index', compact('residencias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }
        $ultimo = Residencia::where('Activo', 1)->orderByDesc('Id')->first();
        if (!$ultimo) {
            $ultimo = 1;
        }
        $cliente = Cliente::where('Activo', 1)->get();
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.residencia.create', compact(
            'ejecutivo',
            'cliente',
            'aseguradoras',
            'estados_poliza',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'bomberos',
            'ultimo'
        ));
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
            'NumeroPoliza.required' => 'El Número de poliza es requerido',
            'LimiteGrupo.required' => 'El Límite Grupal es requerido',
            'LimiteIndividual.required' => 'El Límite Individual es requerido',
            'Tasa.required' => 'El valor de la Tasa es requerido',
            'TasaDescuento.required' => 'El valor de la Tasa de Descuento es requerido',
            'TasaComision.required' => 'El valor de la Tas de Comisión es requerido',

        ];

        $request->validate([
            'LimiteGrupo' => 'required',
            'LimiteIndividual' => 'required',
            'NumeroPoliza' => 'required',
            'Tasa' => 'required',
            'TasaDescuento' => 'required',
            'TasaComision' => 'required'

        ], $messages);


        $residencia = new Residencia();
        $residencia->Numero = 1;
        $residencia->NumeroPoliza = $request->NumeroPoliza;
        $residencia->Codigo = $request->Codigo;
        $residencia->Aseguradora = $request->Aseguradora;
        $residencia->Asegurado = $request->Asegurado;
        $residencia->EstadoPoliza = $request->EstadoPoliza;
        $residencia->VigenciaDesde = $request->VigenciaDesde;
        $residencia->VigenciaHasta = $request->VigenciaHasta;
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        $residencia->Tasa = $request->Tasa;
        $residencia->Ejecutivo = $request->Ejecutivo;
        $residencia->TasaDescuento = $request->TasaDescuento;
        $residencia->Nit = $request->Nit;
        $residencia->Activo = 1;
        if ($request->DescuentoIva == 'on') {
            $residencia->DescuentoIva = 1;
        } else {
            $residencia->DescuentoIva = 0;
        }
        $residencia->Mensual = $request->tipoTasa;
        $residencia->Comision = $request->TasaComision;
        $residencia->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('polizas/residencia/' . $residencia->Id . '/edit');
    }

    /*
    public function show($id)
    {
    //
    }*/

    public function edit($id)
    {
        $residencia = Residencia::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $detalle = DetalleResidencia::where('Residencia', $residencia->Id)->where('Activo', 1)->orderBy('Id', 'desc')->get();
        $ultimo_pago = DetalleResidencia::where('Residencia', $residencia->Id)->where('Activo', 1)->orderBy('Id', 'desc')->first();
        // dd($ultimo_pago);
        if ($residencia->Mensual == 1) {
            $valorTasa = $residencia->Tasa / 1000;
        } else {
            $valorTasa = $residencia->Tasa / 1000 / 12;
        }
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }

        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return view('polizas.residencia.edit', compact(
            'residencia',
            'ejecutivo',
            'detalle',
            'cliente',
            'valorTasa',
            'aseguradoras',
            'estados_poliza',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'bomberos',
            'meses',
            'ultimo_pago'
        ));
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
            'NumeroPoliza.required' => 'El Número de poliza es requerido',
            'LimiteGrupo.required' => 'El Límite Grupal es requerido',
            'LimiteIndividual.required' => 'El Límite Individual es requerido',
            'Tasa.required' => 'El valor de la Tasa es requerido',
            'TasaDescuento.required' => 'El valor de la Tasa de Descuento es requerido',
            'TasaComision.required' => 'El valor de la Tas de Comisión es requerido',

        ];

        $request->validate([
            'LimiteGrupo' => 'required',
            'LimiteIndividual' => 'required',
            'NumeroPoliza' => 'required',
            'Tasa' => 'required',
            'TasaDescuento' => 'required',
            'TasaComision' => 'required'

        ], $messages);

        $residencia = Residencia::findOrFail($id);
        $residencia->NumeroPoliza = $request->NumeroPoliza;
        $residencia->Codigo = $request->Codigo;
        $residencia->Aseguradora = $request->Aseguradora;
        $residencia->Asegurado = $request->Asegurado;
        $residencia->EstadoPoliza = $request->EstadoPoliza;
        $residencia->VigenciaDesde = $request->VigenciaDesde;
        $residencia->VigenciaHasta = $request->VigenciaHasta;
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        $residencia->Tasa = $request->Tasa;
        $residencia->Ejecutivo = $request->Ejecutivo;
        $residencia->TasaDescuento = $request->TasaDescuento;
        $residencia->Nit = $request->Nit;
        $residencia->Activo = 1;
        $residencia->Mensual = $request->tipoTasa;
        $residencia->Comision = $request->TasaComision;
        $residencia->Modificar = 0;
        $residencia->update();

        $detalles = new DetalleResidencia();
        $detalles->MontoCartera = $request->MontoCartera;
        $detalles->Tasa = $request->Tasa;
        $detalles->Prima = $request->ValorPrima;
        $detalles->Descuento = $request->Descuento;
        $detalles->Iva = $request->Iva;
        $detalles->ValorCCF = $request->ValorCCF;
        $detalles->APagar = $request->APagar;
        $detalles->ComentariosDeCobro = $request->ComentariosdeCobro;
        $detalles->DescuentoIva = $request->DescuentoIva;
        $detalles->Comision = $request->Comision;
        $detalles->IvaSobreComision = $request->IvaSobreComision;
        $detalles->Retencion = $request->Retension;
        $detalles->ImpresionRecibo = $request->ImpresionRecibo;
        $detalles->EnvioCartera = $request->EnvioCartera;
        $detalles->PagoAplicado = $request->PagoAplicado;
        $detalles->SaldoA = $request->SaldoA;
        $detalles->EnvioPago = $request->EnvioPago;
        $detalles->Residencia = $residencia->Id;
        $detalles->save();

        return back();
    }

    public function active_edit($id){
        $residencia = Residencia::findOrfail($id);
        $residencia->Modificar = 1;
        $residencia->update();
        alert()->success('El activado la modificacion correctamente');
        return back();
    }
    public function desactive_edit($id){
        $residencia = Residencia::findOrfail($id);
        $residencia->Modificar = 0;
        $residencia->update();
        alert()->success('El activado la modificacion correctamente');
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $residencia = Residencia::findOrFail($id);
        $residencia->Activo = 0;
        $residencia->update();
        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('polizas/residencia');
    }

    public function create_pago(Request $request)
    {
        $fecha = Carbon::create(null, $request->Mes, 1);
        $nombreMes = $fecha->locale('es')->monthName;

        $time = Carbon::now('America/El_Salvador');

        $residencia = Residencia::findOrFail($request->Id);

        if ($request->Mes == 1) {
            $mes_evaluar = 12;
            $axo_evaluar = $request->Axo - 1;
        } else {
            $mes_evaluar = $request->Mes - 1;
            $axo_evaluar = $request->Axo;
        }

        try {
            $archivo = $request->Archivo;
            PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)->delete();
            //PolizaResidenciaTempCartera::truncate();
            //dd(Excel::toArray(new PolizaResidenciaTempCarteraImport($request->Axo, $request->Mes, $residencia->Id, $request->FechaInicio, $request->FechaFinal), $archivo));

            $spreadsheet = IOFactory::load($archivo);
            $worksheet = $spreadsheet->getActiveSheet();
            // $worksheet->getMergeCells() Se verifica si existen celdas combinadas
            if (count($worksheet->getMergeCells())) {

                alert()->error('El Documento NO puede tener celdas combinadas, por favor separe las siguientes celdas: ' . implode(', ', $worksheet->getMergeCells()))->autoClose(100000);
                return back();
            }

            Excel::import(new PolizaResidenciaTempCarteraImport($request->Axo, $request->Mes, $residencia->Id, $request->FechaInicio, $request->FechaFinal), $archivo);

            $monto_cartera_total = PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)->sum('SumaAsegurada');

            $asegurados_limite_individual = PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)
                ->where('SumaAsegurada', '>', $residencia->LimiteIndividual)
                ->get();

            if ($monto_cartera_total > $residencia->LimiteGrupo) {
                alert()->error('Error, el saldo supera el limite de grupo');
                return back();
            }

            if ($asegurados_limite_individual->count() > 0) {
                alert()->error('Error, Hay polizas que superan el limte individual');
                return view('polizas.validacion_cartera.resultado', compact('asegurados_limite_individual'));
            }

            /* if ($request->Validar == "on") {

                $eliminados = DB::select('CALL lista_residencia_eliminados(?, ?, ?, ?, ?, ?)', [$axo_evaluar, $mes_evaluar, $residencia->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                $nuevos = DB::select('CALL lista_residencia_nuevos(?, ?, ?, ?, ?, ?)', [$axo_evaluar, $mes_evaluar, $residencia->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                return view('polizas.validacion_cartera.resultado', compact('nuevos', 'eliminados'));
            }*/

            DB::statement("CALL insertar_temp_cartera_residencia(?, ?, ?, ?)", [auth()->user()->id, $request->Axo, $request->Mes, $residencia->Id]);

            $monto_cartera_total = PolizaResidenciaCartera::where('Axo', $request->Axo)
                ->where('Mes', $request->Mes)
                ->where('PolizaResidencia', $residencia->Id)->sum('SumaAsegurada');

            session(['MontoCartera' => $monto_cartera_total]);
            session(['FechaInicio' => $request->FechaInicio]);
            session(['FechaFinal' => $request->FechaFinal]);
            $idA = uniqid();
            $filePath = 'documentos/polizas/' . $idA . $residencia->NumeroPoliza . '-' . $nombreMes . '-' . $request->Axo . '-Residencia.xlsx';

            $archivo->move(public_path("documentos/polizas/"), $filePath);
            // Storage::disk('public')->put($filePath, file_get_contents($archivo));

            session(['ExcelURL' => $filePath]);

            alert()->success('El registro ha sido ingresado correctamente');
            return back();
        } catch (Throwable $e) {
            print($e);
            return false;
        }
    }

    public function agregar_pago(Request $request)
    {

        $residencia = Residencia::findOrFail($request->Residencia);

        $detalle = new DetalleResidencia();
        $detalle->FechaInicio = $request->FechaInicio;
        $detalle->FechaFinal = $request->FechaFinal;
        $detalle->MontoCartera = $request->MontoCartera;
        $detalle->Residencia = $request->Residencia;
        $detalle->Tasa = $request->Tasa;
        $detalle->PrimaTotal = $request->PrimaTotal;
        $detalle->Descuento = $request->Descuento;
        $detalle->Iva = $request->Iva;
        $detalle->ValorCCF = $request->ValorCCF;
        $detalle->APagar = $request->APagar;
        $detalle->Comentario = $request->Comentario;
        $detalle->DescuentoIva = $request->DescuentoIva; //checked
        $detalle->Comision = $request->Comision;
        $detalle->IvaSobreComision = $request->IvaSobreComision;
        $detalle->Retencion = $request->Retencion;
        $detalle->Residencia = $request->Residencia;
        $detalle->ExtraPrima = $request->ExtraPrima;
        $detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
        $detalle->ValorDescuento = $request->ValorDescuento;
        $detalle->TasaComision = $request->TasaComision;
        $detalle->PrimaDescontada = $request->PrimaDescontada;
        $detalle->ExcelURL = $request->ExcelURL;
        $detalle->save();
        alert()->success('El registro de pago ha sido ingresado correctamente');
        return back();
    }

    public function edit_pago(Request $request)
    {
        $detalle = DetalleResidencia::findOrFail($request->Id);
        $residencia = Residencia::findOrFail($detalle->Residencia);

        // if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
        //     $detalle->SaldoA = $request->SaldoA;
        //     $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        //     $detalle->Comentario = $request->Comentario;
        //     $detalle->update();
        //     $pdf = \PDF::loadView('polizas.residencia.recibo', compact('detalle', 'residencia'))->setWarnings(false)->setPaper('letter');
        //     return $pdf->stream('Recibo.pdf');

        //     return back();
        // } else {

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
        alert()->success('El registro ha sido ingresado correctamente');
        //   }



        return back();
    }

    public function recibo_pago($id, Request $request)
    {
        $detalle = DetalleResidencia::findOrFail($id);
        $residencia = Residencia::findOrFail($detalle->Residencia);

        $detalle->SaldoA = $request->SaldoA;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->Comentario = $request->Comentario;
        $detalle->update();
        $pdf = \PDF::loadView('polizas.residencia.recibo', compact('detalle', 'residencia'))->setWarnings(false)->setPaper('letter');
        return $pdf->stream('Recibo.pdf');

        return back();
    }

    public function get_pago($id)
    {
        return DetalleResidencia::findOrFail($id);
    }


    public function renovar($id)
    {
        $residencia = Residencia::findOrFail($id);
        $estados_poliza = EstadoPoliza::where('Activo', 1)->get();
        return view('polizas.residencia.renovar', compact('residencia', 'estados_poliza'));
    }

    public function renovarPoliza(Request $request, $id)
    {
        $residencia = Residencia::findOrFail($id);
        $residencia->Mensual = $request->Mensual; //valor de radio button
        $residencia->EstadoPoliza = $request->EstadoPoliza;
        $residencia->VigenciaDesde = $request->VigenciaDesde;
        $residencia->VigenciaHasta = $request->VigenciaHasta;
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        $residencia->MontoCartera = $request->MontoCartera;
        $residencia->Tasa = $request->Tasa;
        $residencia->update();

        alert()->success('La poliza fue renovada correctamente');
        return back();
    }

    public function delete_pago($id)
    {
        $detalle = DetalleResidencia::findOrFail($id);
        $detalle->Activo = 0;
        $detalle->update();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }
}
