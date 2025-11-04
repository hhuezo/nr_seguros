<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\PolizaDeclarativaReproceso;
use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeclarativaControl;
use App\Models\polizas\PolizaDeudaCartera;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolizaControlCarteraController extends Controller
{
    public function index(Request $request)
    {

        // Obtener mes y año desde el request, o usar los actuales si no se envían
        $mes = $request->Mes ?? Carbon::now()->subMonthNoOverflow()->format('n');

        $anio = $request->Anio ?? Carbon::now()->year;
        $tipo_poliza_id = $request->TipoPoliza ?? 1;


        $deudaIdArray = Deuda::pluck('Id')->toArray();



        foreach ($deudaIdArray as $idDeuda) {

            // Verificar si ya existe un registro con ese IdDeuda, mes y año
            $existe = PolizaDeclarativaControl::where('PolizaDeudaId', $idDeuda)
                ->where('Mes', $mes)
                ->where('Axo', $anio)
                ->exists();

            if (!$existe) {
                // Si no existe, insertar un nuevo registro
                PolizaDeclarativaControl::create([
                    'PolizaDeudaId' => $idDeuda,
                    'Axo' => $anio,
                    'Mes' => $mes,
                ]);
            }
        }



        $registro_control = PolizaDeclarativaControl::query()
            ->where('poliza_declarativa_control.Axo', $anio)
            ->where('poliza_declarativa_control.Mes', $mes)


            // === Joins base ===
            ->join('poliza_deuda', 'poliza_deuda.Id', '=', 'poliza_declarativa_control.PolizaDeudaId')
            ->leftJoin('poliza_deuda_detalle', function ($join) use ($anio, $mes) {
                $join->on('poliza_deuda_detalle.Deuda', '=', 'poliza_deuda.Id')
                    ->where('poliza_deuda_detalle.Axo', '=', $anio)
                    ->where('poliza_deuda_detalle.Mes', '=', $mes);
            })
            ->join('cliente', 'cliente.Id', '=', 'poliza_deuda.Asegurado')
            ->join('users', 'users.id', '=', 'poliza_deuda.Usuario')

            // === Joins adicionales ===
            ->join('plan', 'plan.Id', '=', 'poliza_deuda.Plan')
            ->join('producto', 'producto.Id', '=', 'plan.Producto')
            ->leftJoin('poliza_declarativa_reproceso', 'poliza_declarativa_reproceso.Id', '=', 'poliza_declarativa_control.ReprocesoNRId')

            // === Campos seleccionados ===
            ->select(
                // Campos principales del control
                'poliza_declarativa_control.Id',
                'poliza_declarativa_control.PolizaDeudaId',
                'poliza_declarativa_control.FechaRecepcionArchivo',
                'poliza_declarativa_control.FechaEnvioCia',
                'poliza_declarativa_control.TrabajoEfectuadoDiaHabil',
                'poliza_declarativa_control.HoraTarea',
                'poliza_declarativa_control.FlujoAsignado',
                //'poliza_declarativa_control.PorcentajeRentabilidad',
                //'poliza_declarativa_control.ValorDescuentoRentabilidad',
                'poliza_declarativa_control.AnexoDeclaracion',
                'poliza_declarativa_control.NumeroSisco',
                'poliza_declarativa_control.FechaVencimiento',
                'poliza_declarativa_control.FechaEnvioCliente',
                'poliza_declarativa_control.ReprocesoNRId',
                'poliza_declarativa_control.FechaEnvioCorreccion',
                'poliza_declarativa_control.FechaSeguimientoCobros',
                'poliza_declarativa_control.FechaRecepcionPago',
                'poliza_declarativa_control.FechaReporteACia',
                'poliza_declarativa_control.FechaAplicacion',
                'poliza_declarativa_control.Comentarios',

                // === Campos de poliza_deuda ===
                'poliza_deuda.NumeroPoliza',
                'poliza_deuda.VigenciaDesde',
                'poliza_deuda.VigenciaHasta',
                'poliza_deuda.Descuento',
                'users.name as Usuario',

                // === Campos de poliza_deuda_detalle ===
                'poliza_deuda_detalle.MontoCartera',
                'poliza_deuda_detalle.Tasa',
                'poliza_deuda_detalle.PrimaCalculada',
                'poliza_deuda_detalle.ExtraPrima',
                'poliza_deuda_detalle.PrimaDescontada',
                'poliza_deuda_detalle.TasaComision',
                'poliza_deuda_detalle.Comision',
                'poliza_deuda_detalle.Retencion',
                'poliza_deuda_detalle.IvaSobreComision',
                'poliza_deuda_detalle.Iva',
                'poliza_deuda_detalle.APagar',
                'poliza_deuda_detalle.Anexo',
                'poliza_deuda_detalle.Comentario',
                'poliza_deuda_detalle.FechaIngreso',
                'poliza_deuda_detalle.FechaInicio',
                'poliza_deuda_detalle.Descuento as ValorDescuentoRentabilidad',
                'poliza_deuda_detalle.NumeroRecibo',
                'poliza_deuda_detalle.Axo',

                // === Cliente ===
                'cliente.Nombre as ClienteNombre',
                'cliente.Dui as ClienteDui',
                'cliente.Nit as ClienteNit',
                'cliente.CorreoPrincipal as ClienteCorreo',
                'cliente.TelefonoCelular as ClienteTelefono',

                // === Producto / plan / reproceso ===
                'plan.Nombre as PlanNombre',
                'producto.Nombre as ProductoNombre',
                'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',

                // === Conteo de usuarios reportados ===
                DB::raw("(SELECT COUNT(*)
                    FROM poliza_deuda_cartera AS c
                    WHERE c.PolizaDeuda = poliza_deuda.Id
                    AND c.Axo = {$anio}
                    AND c.Mes = {$mes}
                    AND c.PolizaDeudaDetalle is not null
                ) AS UsuariosReportados")
            )
            ->orderBy('poliza_deuda.Id')
            ->groupBy('poliza_declarativa_control.Id')
            ->get();




        foreach ($registro_control as $item) {
            if (!empty($item->FechaAplicacion)) {
                // ✅ Ya aplicada
                $item->Color = 'success';
            } elseif (!empty($item->FechaRecepcionPago) || !empty($item->FechaReporteACia)) {
                // ⚠️ En proceso de pago o reporte
                $item->Color = 'warning';
            } elseif (
                !empty($item->AnexoDeclaracion) ||
                !empty($item->NumeroSisco) ||
                !empty($item->FechaVencimiento) ||
                !empty($item->FechaEnvioCliente) ||
                !empty($item->ReprocesoNRId) ||
                !empty($item->FechaEnvioCorreccion) ||
                !empty($item->FechaSeguimientoCobros)
            ) {
                // ℹ️ En trámite administrativo
                $item->Color = 'info';
            } elseif (
                !empty($item->FechaRecepcionArchivo) ||
                !empty($item->FechaEnvioCia) ||
                !empty($item->TrabajoEfectuadoDiaHabil)
            ) {
                // 🟠 Registro inicial / en recepción
                $item->Color = 'orange';
            } else {
                // ⚪ Sin avance
                $item->Color = 'secondary';
            }
        }


        $filtrados = $registro_control->where('MontoCartera', null);

        foreach ($filtrados as  $item) {
            $poliza = Deuda::find($item->PolizaDeudaId);

            $montoCartera = PolizaDeudaCartera::where('PolizaDeuda', $poliza->Id)->where('PolizaDeudaDetalle', null)
                ->where('Axo', $anio)->where('Mes', $mes)->sum('TotalCredito');


            if ($montoCartera > 0) {
                $item->MontoCartera = $montoCartera;

                $primasCalculadas = PolizaDeudaCartera::where('PolizaDeuda', $poliza->Id)
                    ->whereNull('PolizaDeudaDetalle')
                    ->where('Axo', $anio)
                    ->where('Mes', $mes)
                    ->select('Tasa', DB::raw('SUM(TotalCredito) * Tasa AS total'), DB::raw('count(*) AS conteoUsuarios'))
                    ->groupBy('Tasa')
                    ->get();


                $conteoUsuarios = $primasCalculadas->sum('conteoUsuarios');
                $item->UsuariosReportados = $conteoUsuarios;


                $totalPrimas = $primasCalculadas->sum('total');
                $tasas = $primasCalculadas->pluck('Tasa')->unique()->implode(', ');



                // === Cálculos adicionales ===
                $extraPrima = $poliza->ExtraPrima ?? 0;
                $descuento = $poliza->Descuento ?? 0;
                $tasaComision = $poliza->TasaComision ?? 0;
                $comisionIva = $poliza->ComisionIva ?? 0;
                $tipoContribuyente = $poliza->clientes->TipoContribuyente ?? 0;

                // Prima descontada (después del descuento de rentabilidad)
                $primaDescontada = ($totalPrimas + $extraPrima) - (($totalPrimas + $extraPrima) * ($descuento / 100));

                // Comisión base
                $valorComision = $primaDescontada * ($tasaComision / 100);

                // IVA sobre comisión
                $ivaSobreComision = $tipoContribuyente != 4 ? $valorComision * 0.13 : 0;

                // Retención
                $retencion = ($tipoContribuyente != 1 && $valorComision >= 100) ? $valorComision * 0.01 : 0;

                // Comisión total (CCF)
                $comisionTotal = $valorComision + $ivaSobreComision - $retencion;

                // Total a pagar (líquido)
                $aPagar = $primaDescontada - $comisionTotal;

                // === Asignación final de campos ===
                $item->Tasa = $tasas;
                $item->PrimaCalculada = $totalPrimas;
                $item->ExtraPrima = $extraPrima;
                $item->PrimaDescontada = $primaDescontada;
                $item->TasaComision = $tasaComision;
                $item->Comision = $valorComision;
                $item->Retencion = $retencion;
                $item->IvaSobreComision = $ivaSobreComision;
                $item->Iva = $ivaSobreComision; // si manejas IVA general, cámbialo
                $item->APagar = $aPagar;
            }
        }




        $reprocesos = PolizaDeclarativaReproceso::where('Activo', 1)->get();


        // Meses para selector
        $meses = [
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        return view('polizas.control_cartera.index', compact('registro_control', 'anio', 'mes', 'meses', 'reprocesos'));
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
        $control_cartera = PolizaDeclarativaControl::findOrFail($id);

        $control_cartera->FechaRecepcionArchivo      = $request->FechaRecepcionArchivo ?: null;
        $control_cartera->FechaEnvioCia              = $request->FechaEnvioCia ?: null;
        $control_cartera->TrabajoEfectuadoDiaHabil   = $request->TrabajoEfectuadoDiaHabil !== '' ? $request->TrabajoEfectuadoDiaHabil : null;
        $control_cartera->HoraTarea                  = $request->HoraTarea ?: null;
        $control_cartera->FlujoAsignado              = $request->FlujoAsignado ?: null;
        $control_cartera->AnexoDeclaracion           = $request->AnexoDeclaracion ?: null;
        $control_cartera->NumeroSisco                = $request->NumeroSisco ?: null;
        $control_cartera->FechaVencimiento           = $request->FechaVencimiento ?: null;
        $control_cartera->FechaEnvioCliente          = $request->FechaEnvioCliente ?: null;
        $control_cartera->ReprocesoNRId              = $request->ReprocesoNRId ?: null;
        $control_cartera->FechaEnvioCorreccion       = $request->FechaEnvioCorreccion ?: null;
        $control_cartera->FechaSeguimientoCobros     = $request->FechaSeguimientoCobros ?: null;
        $control_cartera->FechaRecepcionPago         = $request->FechaRecepcionPago ?: null;
        $control_cartera->FechaReporteACia           = $request->FechaReporteACia ?: null;
        $control_cartera->FechaAplicacion            = $request->FechaAplicacion ?: null;
        $control_cartera->Comentarios                = $request->Comentarios ?: null;

        $control_cartera->save();

        return redirect()->back()->with('success', 'Control de cartera actualizado correctamente.');
    }
}
