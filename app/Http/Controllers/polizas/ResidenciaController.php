<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\PolizaResidenciaTempCarteraImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ConfiguracionRecibo;
use App\Models\catalogo\DatosGenerales;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\Comentario;
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
use Illuminate\Support\Str;


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
        session(['tab' => 1]);

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
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->where('Nombre', 'like', '%fede%')->orWhere('Nombre', 'like', '%seguros e inversiones%')->get();
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
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        return view('polizas.residencia.create', compact(
            'ejecutivo',
            'productos',
            'planes',
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
        //  dd($request->Planes);
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
        $residencia->Plan = $request->Planes;
        $residencia->Comision = $request->TasaComision;
        if ($request->ComisionIva == 'on') {
            $residencia->ComisionIva = 1;
        } else {
            $residencia->ComisionIva = 0;
        }
        $residencia->save();

        alert()->success('El registro ha sido creado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/residencia/' . $residencia->Id . '/edit');
    }

    /*
    public function show($id)
    {
    //
    }*/

    public function cancelar_pago(Request $request)
    {
        //  dd($request->Residencia);
        //   dd($request->MesCancelar);
        try {
            $poliza_temp = PolizaResidenciaTempCartera::where('PolizaResidencia', '=', $request->Residencia)->where('User', '=', auth()->user()->id)->first();
            $poliza = PolizaResidenciaCartera::where('PolizaResidencia', '=', $request->Residencia)->where('Mes', '=', $poliza_temp->Mes)
                ->where('Axo', '=', $poliza_temp->Axo)->where('User', '=', auth()->user()->id)
                ->delete();

            PolizaResidenciaTempCartera::where('PolizaResidencia', '=', $request->Residencia)->delete();
            // dd($poliza);
        } catch (\Throwable $th) {
            //throw $th;
        }
        session(['MontoCartera' => 0]);
        session(['ExcelURL' => null]);

        alert()->success('El cobro se ha eliminado correctamente');
        return redirect('polizas/residencia/'.$request->Residencia.'/edit?tab=2');
    }


    public function agregar_comentario(Request $request)
    {
        $time = Carbon::now('America/El_Salvador');
        $comen = new Comentario();
        $comen->Comentario = $request->Comentario;
        $comen->Activo = 1;
        if ($request->TipoComentario == '') {
            $comen->DetalleResidencia = '';
        } else {
            $comen->DetalleResidencia == $request->TipoComentario;
        }
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Residencia = $request->ResidenciaComment;
        $comen->save();
        alert()->success('El registro del comentario ha sido creado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/residencia/' . $request->ResidenciaComment . '/edit');
    }

    public function eliminar_comentario(Request $request)
    {

        $comen = Comentario::findOrFail($request->IdComment);
        $comen->Activo = 0;
        $comen->update();
        alert()->success('El registro del comentario ha sido elimando correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/residencia/' . $comen->Residencia . '/edit');
    }

    public function edit(Request $request,$id)
    {
        $tab = $request->tab ?? 1;

        $residencia = Residencia::findOrFail($id);
        $aseguradoras = Aseguradora::where('Nombre', 'like', '%fede%')->orWhere('Nombre', 'like', '%sisa%')->get();
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $detalle = DetalleResidencia::where('Residencia', $residencia->Id)->orderBy('Id', 'desc')->get();
        $ultimo_pago = DetalleResidencia::where('Residencia', $residencia->Id)->where('Activo', 1)->orderBy('Id', 'desc')->first();

        $comentarios = Comentario::where('Residencia', '=', $id)->where('Activo', 1)->get();
        $fechas = PolizaResidenciaTempCartera::where('PolizaResidencia', $id)->where('User', auth()->user()->id)->first();

        $ultimo_pago_fecha_final = null;
        if ($ultimo_pago) {
            $fecha_inicial = Carbon::parse($ultimo_pago->FechaFinal);
            $fecha_final_temp = $fecha_inicial->addMonth();
            $ultimo_pago_fecha_final = $fecha_final_temp->format('Y-m-d');
        }

        if (!$fechas) {
            $fechas = null;
        }

        //dd($ultimo_pago);

        if (strpos($residencia->aseguradoras->Nombre, 'FEDE') === false) {
            if ($residencia->Mensual == 1) {
                $valorTasa = round($residencia->Tasa / 1000 / 12, 8);
            } else {
                $valorTasa = round($residencia->Tasa / 1000 / 12, 8);
            }
        } else {
            if ($residencia->Mensual == 1) {   //modificar al confirmar
                $valorTasa = round($residencia->Tasa / 1000, 8);
            } else {
                $valorTasa = round($residencia->Tasa / 1000, 8);
            }
        }


        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }

        $fecha = PolizaResidenciaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
            ->where('PolizaResidencia', '=', $id)
            // ->where(function ($query) {
            //     $query->where('PolizaResidenciaDetalle', '=', 0)
            //         ->orWhere('PolizaResidenciaDetalle', '=', null);
            // })
            ->orderByDesc('Id')->first();

        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        // session(['MontoCartera' => 0]);

        return view('polizas.residencia.edit', compact(
            'fecha',
            'fechas',
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
            'ultimo_pago',
            'ultimo_pago_fecha_final',
            'comentarios',
            'tab'
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


            'LimiteGrupo.required' => 'El Límite Grupal es requerido',
            'LimiteIndividual.required' => 'El Límite Individual es requerido',
            'Tasa.required' => 'El valor de la Tasa es requerido',
            'Comision.required' => 'El valor de la Tas de Comisión es requerido',

        ];

        $request->validate([
            'LimiteGrupo' => 'required',
            'LimiteIndividual' => 'required',
            'Tasa' => 'required',
            'Comision' => 'required'

        ], $messages);

        $residencia = Residencia::findOrFail($id);
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        $residencia->Tasa = $request->Tasa;
        $residencia->Nit = $request->Nit;
        $residencia->Activo = 1;
        $residencia->Mensual = $request->tipoTasa;
        $residencia->Comision = $request->Comision;
        $residencia->Modificar = 0;
        $residencia->update();

        /*
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
        */
        session(['tab' => 1]);
        return back();
    }

    public function active_edit($id)
    {
        $residencia = Residencia::findOrfail($id);
        $residencia->Modificar = 1;
        $residencia->update();
        alert()->success('El activado la modificacion correctamente');
        return back();
    }
    public function desactive_edit($id)
    {
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
        alert()->success('El registro ha sido creado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/residencia');
    }

    public function create_pago(Request $request)
    {
        $fecha = Carbon::create(null, $request->Mes, 1);
        $nombreMes = $fecha->locale('es')->monthName;
        $idUnicoCartera = Str::random(40);
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

                alert()->error('El Documento NO puede tener celdas combinadas, por favor separe las siguientes celdas: ' . implode(', ', $worksheet->getMergeCells()))->showConfirmButton('Aceptar', '#3085d6');
                return back();
            }

            Excel::import(new PolizaResidenciaTempCarteraImport($request->Axo, $request->Mes, $residencia->Id, $request->FechaInicio, $request->FechaFinal), $archivo);

            $monto_cartera_total = PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)->sum('SumaAsegurada');

            $asegurados_limite_individual = PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)
                ->where('SumaAsegurada', '>', $residencia->LimiteIndividual)
                ->get();

            if ($monto_cartera_total > $residencia->LimiteGrupo) {
                alert()->error('Error, el saldo supera el limite de grupo.<br> Limite de grupo: $' . number_format($residencia->LimiteGrupo, 2, '.', ',') . '<br>Saldo total de la cartera: $' . number_format($monto_cartera_total, 2, '.', ','))->showConfirmButton('Aceptar', '#3085d6');
                return back();
            }

            if ($asegurados_limite_individual->count() > 0) {
                alert()->error('Error, Hay polizas que superan el limte individual')->showConfirmButton('Aceptar', '#3085d6');
                $idPolizaResidencia = $residencia->Id;
                return view('polizas.validacion_cartera.resultado', compact('asegurados_limite_individual', 'idPolizaResidencia'));
            }

            /* if ($request->Validar == "on") {

                $eliminados = DB::select('CALL lista_residencia_eliminados(?, ?, ?, ?, ?, ?)', [$axo_evaluar, $mes_evaluar, $residencia->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                $nuevos = DB::select('CALL lista_residencia_nuevos(?, ?, ?, ?, ?, ?)', [$axo_evaluar, $mes_evaluar, $residencia->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                return view('polizas.validacion_cartera.resultado', compact('nuevos', 'eliminados'));
            }*/

            DB::statement("CALL insertar_temp_cartera_residencia(?, ?, ?, ?, ?)", [auth()->user()->id, $request->Axo, $request->Mes, $residencia->Id, $idUnicoCartera]);

            $monto_cartera_total = PolizaResidenciaCartera::where('Axo', $request->Axo)
                ->where('Mes', $request->Mes)
                ->where('PolizaResidencia', $residencia->Id)
                ->where('User', auth()->user()->id)
                ->where('IdUnicoCartera', $idUnicoCartera)->sum('SumaAsegurada');

            session(['idUnicoCartera' => $idUnicoCartera]);
            session(['MontoCartera' => $monto_cartera_total]);
            session(['FechaInicio' => $request->FechaInicio]);
            session(['FechaFinal' => $request->FechaFinal]);
            $idA = uniqid();
            $filePath = 'documentos/polizas/' . $idA . $residencia->NumeroPoliza . '-' . $nombreMes . '-' . $request->Axo . '-Residencia.xlsx';

            $archivo->move(public_path("documentos/polizas/"), $filePath);
            // Storage::disk('public')->put($filePath, file_get_contents($archivo));

            session(['ExcelURL' => $filePath]);

            alert()->success('El registro ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');

            return redirect('polizas/residencia/'.$request->Id.'/edit?tab=2');
        } catch (Throwable $e) {
            print($e);
            return false;
        }
    }

    public function agregar_pago(Request $request)
    {

        //dd($request->MontoCartera);
        $residencia = Residencia::findOrFail($request->Residencia);
        $time = Carbon::now('America/El_Salvador');

        $recibo = DatosGenerales::orderByDesc('Id_recibo')->first();
        if (!$request->ExcelURL) {
            alert()->error('No se puede generar el pago, falta subir cartera')->showConfirmButton('Aceptar', '#3085d6');
        } else {

            $detalle = new DetalleResidencia();
            $detalle->FechaInicio = $request->FechaInicio;
            $detalle->FechaFinal = $request->FechaFinal;
            $detalle->MontoCartera = $request->MontoCartera;
            $detalle->Residencia = $request->Residencia;
            $detalle->Tasa = $request->Tasa;
            $detalle->PrimaCalculada = $request->PrimaCalculada;
            $detalle->Descuento = $request->Descuento;
            $detalle->PrimaDescontada = $request->PrimaDescontada;
            $detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
            $detalle->GastosEmision = $request->GastosEmision;
            $detalle->Otros = $request->Otros;
            $detalle->SubTotal = $request->SubTotal;
            $detalle->Iva = $request->Iva;
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

            $comen = new Comentario();
            $comen->Comentario = 'Se agrego el pago de la cartera';
            $comen->Activo = 1;
            $comen->Usuario = auth()->user()->id;
            $comen->FechaIngreso = $time;
            $comen->Residencia = $request->Residencia;
            $comen->DetalleResidencia = $detalle->Id;
            $comen->save();


            $recibo->Id_recibo = ($recibo->Id_recibo) + 1;
            $recibo->update();
            session(['MontoCartera' => 0]);
            alert()->success('El registro de pago ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        }
        return back();
    }

    public function edit_pago(Request $request)
    {
        session(['tab' => 4]);
        $detalle = DetalleResidencia::findOrFail($request->Id);
        $residencia = Residencia::findOrFail($detalle->Residencia);
        $time = Carbon::now('America/El_Salvador');

        if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
            $detalle->SaldoA = $request->SaldoA;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->Comentario = $request->Comentario;
            $detalle->update();
            $configuracion = ConfiguracionRecibo::first();
            $pdf = \PDF::loadView('polizas.residencia.recibo', compact('configuracion','detalle', 'residencia'))->setWarnings(false)->setPaper('letter');
            return $pdf->stream('Recibo.pdf');

            return back();
        } else {

            //dd($request->EnvioCartera .' 00:00:00');
            if ($request->EnvioCartera) {
                $detalle->EnvioCartera = $request->EnvioCartera;
                $detalle->ComCartera = $request->Comentario;
            }
            if ($request->EnvioPago) {
                $detalle->EnvioPago = $request->EnvioPago;
                $detalle->ComPago = $request->Comentario;
            }
            if ($request->PagoAplicado) {
                $detalle->PagoAplicado = $request->PagoAplicado;
                $detalle->ComAplicado = $request->Comentario;
            }

            $comen = new Comentario();
            $comen->Comentario = $request->Comentario;
            $comen->Activo = 1;
            $comen->Usuario = auth()->user()->id;
            $comen->FechaIngreso = $time;
            $comen->Residencia = $detalle->Residencia;
            $comen->DetalleResidencia = $detalle->Id;
            $comen->save();


            /*$detalle->EnvioPago = $request->EnvioPago;
            $detalle->PagoAplicado = $request->PagoAplicado;*/
            $detalle->update();
            alert()->success('El registro ha sid:o ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        }



        return back();
    }

    public function recibo_pago($id, Request $request)
    {
        session(['tab' => 4]);
        $detalle = DetalleResidencia::findOrFail($id);
        $residencia = Residencia::findOrFail($detalle->Residencia);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $detalle->SaldoA = $request->SaldoA;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->Referencia = $request->Referencia;
        $detalle->Anexo = $request->Anexo;
        $detalle->NumeroCorrelativo = $request->NumeroCorrelativo;
        $detalle->update();
        //$calculo = $this->monto($residencia, $detalle);
        $configuracion = ConfiguracionRecibo::first();
        $pdf = \PDF::loadView('polizas.residencia.recibo', compact('configuracion','detalle', 'residencia', 'meses'))->setWarnings(false)->setPaper('letter');
        return $pdf->stream('Recibo.pdf');

        //  return back();
    }

    public function get_recibo($id)
    {
        $detalle = DetalleResidencia::findOrFail($id);

        $residencia = Residencia::findOrFail($detalle->Residencia);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $calculo = $this->monto($residencia, $detalle);
        // dd($calculo);
        $configuracion = ConfiguracionRecibo::first();
        $pdf = \PDF::loadView('polizas.residencia.recibo', compact('configuracion','detalle', 'residencia', 'meses', 'calculo'))->setWarnings(false)->setPaper('letter');
        //  dd($detalle);
        return $pdf->stream('Recibos.pdf');
    }

    public function get_pago($id)
    {
        return DetalleResidencia::findOrFail($id);
    }

    public function monto($residencia, $detalle)
    {
        $calculo = array();
        $bomberos = Bombero::where('Activo', 1)->first();
        $monto = $detalle->MontoCartera;
        $desde = Carbon::parse($residencia->VigenciaDesde);
        $hasta = Carbon::parse($residencia->VigenciaHasta);
        $inicio = Carbon::parse($detalle->FechaInicio);
        $final = Carbon::parse($detalle->FechaFinal);
        $tasa = $residencia->Tasa;
        $dias_axo = $residencia->aseguradoras->Dias365 == 1 ? 365 : $desde->diffInDays($hasta);
        $dias_mes = $final->diffInDays($inicio);



        if (strpos($residencia->aseguradoras->Nombre, 'FEDE') === false) {
            // dd("SISA");

            if ($residencia->Mensual == 0) {
                $tasaFinal = ($tasa / 1000) / !2;
            } else {
                $tasaFinal = $tasa / 1000;
            }
        } else {
            //dd('FEDE');

            if ($residencia->Mensual == 0) {    //falta confirmacion de tasa anual
                $tasaFinal = ($tasa / 1000); // / 12;
            } else {
                $tasaFinal = $tasa / 1000;
            }
        }



        if ($residencia->aseguradoras->Diario == 1) {
                $prima_calculada = (($monto * $tasaFinal) / $dias_axo) * $dias_mes;
        } else {
            $prima_calculada = $monto * $tasaFinal;
        }

        array_push($calculo, $prima_calculada);  // prima calculada

        $prima_total = $prima_calculada + $detalle->ExtraPrima;
        $tasa_descuento = $residencia->TasaDescuento;
        if ($tasa_descuento < 0) {
            $descuento = $tasa_descuento * $prima_total;
        } else {
            $descuento = ($tasa_descuento / 100 * $prima_total);
        }

        array_push($calculo, $descuento); // descuento rentabilidad

        $prima_descontada = $prima_total - $descuento;

        array_push($calculo, $prima_descontada);   //prima descontada
        if ($bomberos) {
            $calculo_bomberos = $monto * ($bomberos->Valor / 100);
        } else {
            $calculo_bomberos = 0;
        }

        array_push($calculo, $calculo_bomberos); //calculo de bomberos

        $sub = $prima_descontada - $calculo_bomberos;

        array_push($calculo, $sub);   //calculo_subtotal

        $iva = $sub * 0.13;
        array_push($calculo, $iva);  //calculo iva

        $ccf = $prima_descontada * ($residencia->Comision / 100);
        array_push($calculo, $ccf);   //valor ccf

        $iva_ccf = $ccf * 0.13;
        array_push($calculo, $iva_ccf); // iva ccf

        $total_ccf = $ccf + $iva_ccf;
        array_push($calculo, $total_ccf);  //total ccf

        $a_pagar = $sub + $iva - $total_ccf;
        array_push($calculo, $a_pagar);   //calculo a pagar

        $facturar = $sub + $iva;
        array_push($calculo, $facturar);   //calculo a facturar

        return $calculo;
    }


    public function renovar($id)
    {
        $residencia = Residencia::findOrFail($id);
        $estados_poliza = EstadoPoliza::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', '=', 1)->get();
        return view('polizas.residencia.renovar', compact('residencia', 'estados_poliza', 'ejecutivo'));
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
        // $residencia->MontoCartera = $request->MontoCartera;
        $residencia->Tasa = $request->Tasa;
        $residencia->Ejecutivo = $request->Ejecutivo;
        $residencia->update();

        alert()->success('La poliza fue renovada correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return back();
    }

    public function delete_pago($id)
    {
        $detalle = DetalleResidencia::findOrFail($id);
        $detalle->Activo = 0;
        $detalle->update();
        alert()->success('El registro ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return back();
    }
}
