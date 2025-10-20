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
use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoDetalle;
use App\Models\polizas\PolizaDesempleoHistorica;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DesempleoRenovacionController extends Controller
{
    //
        public function renovar($id)
    {
        $desempleo = Desempleo::findOrFail($id);
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $desempleo->Aseguradora)->get();
        $fechaDesdeRenovacion = $desempleo->VigenciaHasta;
        $fechaDesdeRenovacionTemporal = $desempleo->VigenciaHasta;
        $fechaDesdeRenovacionAnual = $desempleo->VigenciaHasta;

        // Crear una instancia de Carbon a partir de la fecha
        $fecha = Carbon::parse($desempleo->VigenciaHasta);

        // Agregar un año a la fecha
        $nuevaFecha  = $fecha->copy()->addYear();
        $fechaHastaRenovacion = $nuevaFecha->format('Y-m-d');


        $historicoDesempleo = PolizaDesempleoHistorica::where('Desempleo', $id)->get();

        $registroInicial = $historicoDesempleo->isNotEmpty() ? $historicoDesempleo->first() : null;

        $fechaDesdeRenovacionAnual = $registroInicial ? $registroInicial->VigenciaHasta : $desempleo->VigenciaHasta;

        foreach ($historicoDesempleo->sortByDesc('Id') as $historico) {
            if ($historico->TipoRenovacion == 1) {
                $fechaDesdeRenovacionAnual = $historico->FechaHastaRenovacion;
                break; // Salir del bucle si la condición se cumple
            }
        }


        // Obtener los rangos de edad para las columnas
        $columnas = [];

        $saldos = SaldoMontos::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $historico_poliza = PolizaDesempleoHistorica::where('Desempleo', $id)->orderBy('Fecha')->get();
        // dd($registroInicial);
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        session(['tab' => 1]);

        return view('polizas.desempleo.renovar', compact(
            'tipoCobro',
            'historico_poliza',
            'cliente',
            'planes',
            'productos',
            'aseguradora',
            'desempleo',
            'fechaDesdeRenovacion',
            'fechaHastaRenovacion',
            'registroInicial',
            'fechaDesdeRenovacionAnual',
            'estadoPoliza',
            'ejecutivo',
            'perfiles',
            'saldos'
        ));
    }


    public function save_renovar(Request $request)
    {
        try {


            $desempleo = Desempleo::findOrFail($request->Id);

            if ($desempleo->VigenciaHasta == $request->VigenciaHasta) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas.'])->withInput();
            }


            if ($request->FechaDesdeRenovacion == $request->FechaHastaRenovacion) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas..'])->withInput();
            }


            $creditos = $desempleo->desempleo_tipos_cartera;
            $detalle = DesempleoDetalle::where('Desempleo', $desempleo->Id)->get();
            $tabla_diferencia = ''; // PolizaDesempleoTasaDiferenciada::whereIn('PolizaDuedaCredito', $creditos->pluck('Id')->toArray())->get();


            // Guardar todo en una tabla histórica
            $historica = new PolizaDesempleoHistorica();
            $historica->Desempleo = $desempleo->Id;
            $historica->VigenciaHasta = $desempleo->VigenciaHasta;
            $historica->VigenciaDesde = $desempleo->VigenciaDesde;
            $historica->FechaDesdeRenovacion = $request->FechaDesdeRenovacion;
            $historica->FechaHastaRenovacion = $request->FechaHastaRenovacion;
            $historica->TipoRenovacion = $request->TipoRenovacion;
            $historica->DatosDesempleo = json_encode($desempleo);
            $historica->DatosCreditos = json_encode($creditos);
            $historica->DatosTablaDiferenciada = json_encode($tabla_diferencia);
            $historica->DesempleoDetalle = json_encode($detalle);
            $historica->Requisito = null;
            $historica->Fecha = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
            $historica->Usuario = auth()->user()->id;
            $historica->save();

            // Actualizar los datos de la renovación
            $desempleo->Ejecutivo = $request->Ejecutivo;
            $desempleo->TasaDiferenciada = $request->TasaDiferenciada;
            $desempleo->Tasa = $request->Tasa;
            $desempleo->EdadMaximaInscripcion = $request->EdadMaximaInscripcion;
            $desempleo->EdadMaxima = $request->EdadMaxima;
            $desempleo->Beneficios = $request->Beneficios;
            $desempleo->ClausulasEspeciales = $request->ClausulasEspeciales;
            $desempleo->Concepto = $request->Concepto;
            $desempleo->Descuento = $request->Descuento;
            $desempleo->VigenciaDesde = $request->FechaDesdeRenovacion;
            $desempleo->VigenciaHasta = $request->FechaHastaRenovacion;
            $desempleo->EstadoPoliza = $request->EstadoPoliza;
            $desempleo->Activo = 1;
            $desempleo->Configuracion = 0; // Se habilita para configurar nuevamente ResponsabilidadMaxima
            $desempleo->update();

            alert()->success('Renovación realizada correctamente');

            return back();

            // return Redirect::to('polizas/desempleo');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()])->withInput();
        }
    }


    public function eliminar_renovacion($id)
    {


        $historica_id = PolizaDesempleoHistorica::findOrFail($id);
        $historico_poliza = PolizaDesempleoHistorica::where('Desempleo', $historica_id->Desempleo)->orderBy('Fecha')->get();


        $desempleo = Desempleo::findOrFail($historica_id->Desempleo);
        $desempleo->VigenciaDesde = $historica_id->VigenciaDesde;
        $desempleo->VigenciaHasta = $historica_id->VigenciaHasta;
        $desempleo->update();

        $historica_id->delete();

        alert()->success('Su registro fue eliminado con exito');
        return back();
    }
}
