<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Perfil;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\SaldoMontos;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoCobro;
use App\Models\polizas\PolizaVidaHistorica;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class VidaRenovacionController extends Controller
{
    public function renovar($id)
    {
        $vida = Vida::findOrFail($id);
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $vida->Aseguradora)->get();
        $fechaDesdeRenovacion = $vida->VigenciaHasta;
        $fechaDesdeRenovacionTemporal = $vida->VigenciaHasta;
        $fechaDesdeRenovacionAnual = $vida->VigenciaHasta;

        // Crear una instancia de Carbon a partir de la fecha
        $fecha = Carbon::parse($vida->VigenciaHasta);

        // Agregar un año a la fecha
        $nuevaFecha  = $fecha->copy()->addYear();
        $fechaHastaRenovacion = $nuevaFecha->format('Y-m-d');


        $historicoVida = PolizaVidaHistorica::where('Vida', $id)->get();

        $registroInicial = $historicoVida->isNotEmpty() ? $historicoVida->first() : null;

        $fechaDesdeRenovacionAnual = $registroInicial ? $registroInicial->VigenciaHasta : $vida->VigenciaHasta;

        foreach ($historicoVida->sortByDesc('Id') as $historico) {
            if ($historico->TipoRenovacion == 1) {
                $fechaDesdeRenovacionAnual = $historico->FechaHastaRenovacion;
                break; // Salir del bucle si la condición se cumple
            }
        }


        // Obtener los rangos de edad para las columnas
        $columnas = [];

        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 1)->get(); //vida
        $saldos = SaldoMontos::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $historico_poliza = PolizaVidaHistorica::where('Vida', $id)->orderBy('Fecha')->get();
        // dd($registroInicial);
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        session(['tab' => 1]);

        return view('polizas.vida.renovar', compact(
            'tipoCobro',
            'historico_poliza',
            'cliente',
            'planes',
            'productos',
            'aseguradora',
            'vida',
            'fechaDesdeRenovacion',
            'fechaHastaRenovacion',
            'registroInicial',
            'fechaDesdeRenovacionAnual',
            'estadoPoliza',
            'ejecutivo',
            'perfiles',
            'tipoCartera',
            'saldos'
        ));
    }


    public function save_renovar(Request $request)
    {
        try {


            $vida = Vida::findOrFail($request->Id);

            if ($vida->VigenciaHasta == $request->VigenciaHasta) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas.'])->withInput();
            }


            if ($request->FechaDesdeRenovacion == $request->FechaHastaRenovacion) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas..'])->withInput();
            }


            $creditos = $vida->vida_tipos_cartera;
            $detalle = VidaDetalle::where('PolizaVida', $vida->Id)->get();
            $tabla_diferencia = ''; // PolizaVidaTasaDiferenciada::whereIn('PolizaDuedaCredito', $creditos->pluck('Id')->toArray())->get();


            // Guardar todo en una tabla histórica
            $historica = new PolizaVidaHistorica();
            $historica->Vida = $vida->Id;
            $historica->VigenciaHasta = $vida->VigenciaHasta;
            $historica->VigenciaDesde = $vida->VigenciaDesde;
            $historica->FechaDesdeRenovacion = $request->FechaDesdeRenovacion;
            $historica->FechaHastaRenovacion = $request->FechaHastaRenovacion;
            $historica->TipoRenovacion = $request->TipoRenovacion;
            $historica->DatosVida = json_encode($vida);
            $historica->DatosCreditos = json_encode($creditos);
            $historica->DatosTablaDiferenciada = json_encode($tabla_diferencia);
            $historica->VidaDetalle = json_encode($detalle);
            $historica->Requisito = null;
            $historica->Fecha = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
            $historica->Usuario = auth()->user()->id;
            $historica->save();

            // Actualizar los datos de la renovación
            $vida->Ejecutivo = $request->Ejecutivo;
            $vida->TipoCobro = $request->TipoCobro;
            $vida->SumaMinima = $request->SumaMinima;
            $vida->SumaMaxima = $request->SumaMaxima;
            $vida->TipoTarifa = $request->TipoTarifa;
            $vida->SumaAsegurada = $request->SumaAsegurada;
            $vida->Multitarifa = $request->Multitarifa;
            if ($request->Opcion == 0) {
                $vida->TarifaExcel = 0;
                $vida->TasaDiferenciada = 0;
            } else if ($request->Opcion == 1) {
                $vida->TasaDiferenciada = 1;
                $vida->TarifaExcel = 0;
            } else  if ($request->Opcion == 2) {
                $vida->TasaDiferenciada = 0;
                $vida->TarifaExcel = 1;
            }
            $vida->Tasa = $request->Tasa;
            $vida->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $vida->EdadTerminacion = $request->EdadTerminacion;
            $vida->Beneficios = $request->Beneficios;
            $vida->ClausulasEspeciales = $request->ClausulasEspeciales;
            $vida->Concepto = $request->Concepto;
            $vida->TasaDescuento = $request->TasaDescuento;
            $vida->TasaComision = $request->TasaComision;
            $vida->VigenciaDesde = $request->FechaDesdeRenovacion;
            $vida->VigenciaHasta = $request->FechaHastaRenovacion;
            $vida->EstadoPoliza = $request->EstadoPoliza;
            $vida->Activo = 1;
            $vida->Configuracion = 0; // Se habilita para configurar nuevamente ResponsabilidadMaxima
            $vida->update();

            alert()->success('Renovación realizada correctamente');

            return back();

            // return Redirect::to('polizas/vida');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()])->withInput();
        }
    }


    public function eliminar_renovacion($id)
    {


        $historica_id = PolizaVidaHistorica::findOrFail($id);
        $historico_poliza = PolizaVidaHistorica::where('Vida', $historica_id->Vida)->orderBy('Fecha')->get();


        $vida = Vida::findOrFail($historica_id->Vida);
        $vida->VigenciaDesde = $historica_id->VigenciaDesde;
        $vida->VigenciaHasta = $historica_id->VigenciaHasta;
        $vida->update();

        $historica_id->delete();

        alert()->success('Su registro fue eliminado con exito');
        return back();
    }
}
