<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoCobro;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\DetalleResidencia;
use App\Models\polizas\PolizaResidenciaHistorica;
use App\Models\polizas\Residencia;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResidenciaRenovacionController extends Controller
{
    //

    public function renovar($id)
    {
        $residencia = Residencia::findOrFail($id);
        $estadoPoliza = EstadoPoliza::get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        // $perfiles = Perfil::where('Activo', 1)->where('Aseguradora', '=', $residencia->Aseguradora)->get();
        $fechaDesdeRenovacion = $residencia->VigenciaHasta;
        $fechaDesdeRenovacionTemporal = $residencia->VigenciaHasta;
        $fechaDesdeRenovacionAnual = $residencia->VigenciaHasta;

        // Crear una instancia de Carbon a partir de la fecha
        $fecha = Carbon::parse($residencia->VigenciaHasta);

        // Agregar un año a la fecha
        $nuevaFecha  = $fecha->copy()->addYear();
        $fechaHastaRenovacion = $nuevaFecha->format('Y-m-d');


        $historicoResidencia = PolizaResidenciaHistorica::where('Residencia', $id)->get();

        $registroInicial = $historicoResidencia->isNotEmpty() ? $historicoResidencia->first() : null;

        $fechaDesdeRenovacionAnual = $registroInicial ? $registroInicial->VigenciaHasta : $residencia->VigenciaHasta;

        foreach ($historicoResidencia->sortByDesc('Id') as $historico) {
            if ($historico->TipoRenovacion == 1) {
                $fechaDesdeRenovacionAnual = $historico->FechaHastaRenovacion;
                break; // Salir del bucle si la condición se cumple
            }
        }

        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->where('Nombre', 'like', '%fede%')->orWhere('Nombre', 'like', '%seguros e inversiones%')->get();
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }
        $ultimoRegistro = Residencia::where('Activo', 1)->orderByDesc('Id')->first();
        if (!$ultimoRegistro) {
            $ultimo = 1;
        } else {
            $ultimo =  $ultimoRegistro->Id + 1;
        }
        $cliente = Cliente::where('Activo', 1)->get();
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();

        $cliente = Cliente::where('Activo', 1)->get();
        $historico_poliza = PolizaResidenciaHistorica::where('Residencia', $id)->orderBy('Fecha')->get();

        session(['tab' => 1]);

        //  dd($fechaDesdeRenovacionAnual);

        return view('polizas.residencia.renovar', compact(

            'historico_poliza',
            'cliente',
            'planes',
            'productos',
            'aseguradora',
            'residencia',
            'fechaDesdeRenovacion',
            'fechaHastaRenovacion',
            'registroInicial',
            'fechaDesdeRenovacionAnual',
            'estadoPoliza',
            'ejecutivo',

        ));
    }


    public function save_renovar(Request $request)
    {
        try {


            $residencia = Residencia::findOrFail($request->Id);

            if ($residencia->VigenciaHasta == $request->VigenciaHasta) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas.'])->withInput();
            }


            if ($request->FechaDesdeRenovacion == $request->FechaHastaRenovacion) {
                return back()->withErrors(['VigenciaDesde' => 'Las fechas de vigencia no son válidas..'])->withInput();
            }


            $creditos = $residencia->residencia_tipos_cartera;
            $detalle = DetalleResidencia::where('Residencia', $residencia->Id)->get();
            $tabla_diferencia = ''; // PolizaResidenciaTasaDiferenciada::whereIn('PolizaDuedaCredito', $creditos->pluck('Id')->toArray())->get();


            // Guardar todo en una tabla histórica
            $historica = new PolizaResidenciaHistorica();
            $historica->DatosResidencia = json_encode($residencia);
            $historica->ResidenciaDetalle = json_encode($detalle);
            $historica->Residencia = $residencia->Id;
            $historica->Fecha = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
            $historica->Usuario = auth()->user()->id;
            $historica->TipoRenovacion = $request->TipoRenovacion;
            $historica->VigenciaHasta = $residencia->VigenciaHasta;
            $historica->VigenciaDesde = $residencia->VigenciaDesde;
            $historica->FechaDesdeRenovacion = $request->FechaDesdeRenovacion;
            $historica->FechaHastaRenovacion = $request->FechaHastaRenovacion;
            $historica->save();

            // Actualizar los datos de la renovación
            $residencia->Ejecutivo = $request->Ejecutivo;
            $residencia->VigenciaDesde = $request->FechaDesdeRenovacion;
            $residencia->VigenciaHasta = $request->FechaHastaRenovacion;
            $residencia->EstadoPoliza = $request->EstadoPoliza;
            $residencia->TasaDescuento = $request->TasaDescuento;
            $residencia->LimiteGrupo = $request->LimiteGrupo;
            $residencia->LimiteIndividual = $request->LimiteIndividual;
            $residencia->Tasa = $request->Tasa;
            $residencia->Comision = $request->TasaComision;
            $residencia->Mensual = $request->tipoTasa;
            if ($request->ComisionIva == 'on') {
                $residencia->DescuentoIva = 1;
            } else {
                $residencia->DescuentoIva = 0;
            }
            $residencia->Activo = 1;
            $residencia->update();

            alert()->success('Renovación realizada correctamente');

            return back();

            // return Redirect::to('polizas/residencia');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()])->withInput();
        }
    }


    public function eliminar_renovacion($id)
    {


        $historica_id = PolizaResidenciaHistorica::findOrFail($id);
        $historico_poliza = PolizaResidenciaHistorica::where('Residencia', $historica_id->Residencia)->orderBy('Fecha')->get();


        $residencia = Residencia::findOrFail($historica_id->Residencia);
        $residencia->VigenciaDesde = $historica_id->VigenciaDesde;
        $residencia->VigenciaHasta = $historica_id->VigenciaHasta;
        $residencia->update();

        $historica_id->delete();

        alert()->success('Su registro fue eliminado con exito');
        return back();
    }
}
