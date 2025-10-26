<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaControlCartera;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolizaControlCarteraController extends Controller
{
    public function index(Request $request)
    {
        // Obtener mes y año desde el request, o usar los actuales si no se envían
        $mes = $request->Mes ?? Carbon::now()->format('m');
        $anio = $request->Anio ?? Carbon::now()->year;
        $tipo_poliza_id = $request->TipoPoliza ?? 1;

        // Consulta principal
        $polizas_deuda = Deuda::leftJoin('poliza_deuda_detalle', 'poliza_deuda_detalle.Deuda', '=', 'poliza_deuda.Id')
            ->select('poliza_deuda.*', 'poliza_deuda_detalle.*')
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM poliza_deuda_cartera WHERE poliza_deuda_cartera.PolizaDeuda = poliza_deuda.Id) as UsuariosReportados'))
            ->with(['control_cartera_por_mes_anio' => function ($query) use ($mes, $anio) {
                $query->where('Mes', $mes)
                    ->where('Axo', $anio);
            }])
            ->get();

        // Meses para selector
        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        return view('polizas.control_cartera.index', compact('polizas_deuda', 'anio', 'mes', 'meses'));
    }


    public function edit($id, $tipo, $anio, $mes)
    {
        $poliza = Deuda::where('Id', $id)
            ->with(['control_cartera_por_mes_anio' => function ($query) use ($mes, $anio) {
                $query->where('Mes', $mes)
                    ->where('Axo', $anio);
            }])
            ->first();

        return view('polizas.control_cartera.edit', compact('poliza', 'tipo', 'anio', 'mes'));
    }


    public function update(Request $request, $id)
    {
        if ($request->Tipo == 1) {
            $control_cartera = PolizaControlCartera::where('DeudaId', $id)
                ->where('Axo', $request->Anio)
                ->where('Mes', $request->Mes)
                ->first();

            if (!$control_cartera) {
                $control_cartera = new PolizaControlCartera();
                $control_cartera->DeudaId = $id;
                $control_cartera->Axo = $request->Anio;
                $control_cartera->Mes = $request->Mes;
                $control_cartera->save();
            }
        }

        // Asignación en el mismo orden de la migración
        $control_cartera->FechaRecepcionArchivo   = $request->FechaRecepcionArchivo;
        $control_cartera->FechaEnvioCia           = $request->FechaEnvioCia;
        $control_cartera->TrabajoEfectuado        = $request->TrabajoEfectuado;
        $control_cartera->HoraTarea               = $request->HoraTarea;
        $control_cartera->FlujoAsignado           = $request->FlujoAsignado;
        $control_cartera->Usuario                 = $request->Usuario;
        $control_cartera->UsuariosReportados      = $request->UsuariosReportados;
        $control_cartera->Tarifa                  = $request->Tarifa;
        $control_cartera->PrimaBruta              = $request->PrimaBruta;
        $control_cartera->ExtraPrima              = $request->ExtraPrima;
        $control_cartera->PrimaEmitida            = $request->PrimaEmitida;
        $control_cartera->PorcentajeComision      = $request->PorcentajeComision;
        $control_cartera->ComisionNeta            = $request->ComisionNeta;
        $control_cartera->Iva                     = $request->Iva;
        $control_cartera->PrimaLiquida            = $request->PrimaLiquida;
        $control_cartera->AnexoDeclaracion        = $request->AnexoDeclaracion;
        $control_cartera->FechaVencimiento        = $request->FechaVencimiento;
        $control_cartera->FechaEnvioCorreccion    = $request->FechaEnvioCorreccion;
        $control_cartera->FechaSeguimientoCobro   = $request->FechaSeguimientoCobro;
        $control_cartera->FechaReporteCia         = $request->FechaReporteCia;
        $control_cartera->RepocesoNr              = $request->RepocesoNr;
        $control_cartera->FechaAplicacion         = $request->FechaAplicacion;
        $control_cartera->Comentarios             = $request->Comentarios;
        $control_cartera->NumeroCisco             = $request->NumeroCisco;

        $control_cartera->save();

        return back()->with('success', 'El registro ha sido actualizado correctamente');
    }
}
