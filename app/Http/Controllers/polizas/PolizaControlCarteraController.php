<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\PolizaDeclarativaReproceso;
use App\Models\polizas\Desempleo;
use App\Models\polizas\DesempleoCartera;
use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeclarativaControl;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\polizas\PolizaResidenciaCartera;
use App\Models\polizas\Residencia;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaCartera;
use App\Models\suscripcion\FechasFeriadas;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolizaControlCarteraController extends Controller
{
    public function index(Request $request)
    {

        // Obtener mes y año desde el request, o usar los actuales si no se envían
        $mes = $request->Mes ?? Carbon::now()->subMonthNoOverflow()->format('n');

        $anio = $request->Anio ?? Carbon::now()->year;
        $tipo_poliza = $request->TipoPoliza ?? 1;


        if ($tipo_poliza == 1) {


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

            // =====================================================
            // 2️⃣ Insertar controles de VIDA si no existen
            // =====================================================
            $vidaIdArray = Vida::pluck('Id')->toArray();

            foreach ($vidaIdArray as $idVida) {
                $existe = PolizaDeclarativaControl::where('PolizaVidaId', $idVida)
                    ->where('Mes', $mes)
                    ->where('Axo', $anio)
                    ->exists();

                if (!$existe) {
                    PolizaDeclarativaControl::create([
                        'PolizaVidaId' => $idVida,
                        'Axo' => $anio,
                        'Mes' => $mes,
                    ]);
                }
            }

            // =====================================================
            // 3 Insertar controles de DESEMPLEO si no existen
            // =====================================================

            $desempleoIdArray = Desempleo::pluck('Id')->toArray();

            foreach ($desempleoIdArray as $idDesempleo) {
                $existe = PolizaDeclarativaControl::where('PolizaDesempleoId', $idDesempleo)
                    ->where('Mes', $mes)
                    ->where('Axo', $anio)
                    ->exists();

                if (!$existe) {
                    PolizaDeclarativaControl::create([
                        'PolizaDesempleoId' => $idDesempleo,
                        'Axo' => $anio,
                        'Mes' => $mes,
                    ]);
                }
            }



            // === Consulta 1: DEUDA ===
            $deuda = PolizaDeclarativaControl::query()
                ->where('poliza_declarativa_control.Axo', $anio)
                ->where('poliza_declarativa_control.Mes', $mes)
                ->join('poliza_deuda', 'poliza_deuda.Id', '=', 'poliza_declarativa_control.PolizaDeudaId')
                ->leftJoin('poliza_deuda_detalle', function ($join) use ($anio, $mes) {
                    $join->on('poliza_deuda_detalle.Deuda', '=', 'poliza_deuda.Id')
                        ->where('poliza_deuda_detalle.Axo', '=', $anio)
                        ->where('poliza_deuda_detalle.Mes', '=', $mes);
                })
                ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_deuda.Aseguradora')
                ->join('cliente', 'cliente.Id', '=', 'poliza_deuda.Asegurado')
                ->join('plan', 'plan.Id', '=', 'poliza_deuda.Plan')
                ->join('producto', 'producto.Id', '=', 'plan.Producto')
                ->leftJoin('poliza_declarativa_reproceso', 'poliza_declarativa_reproceso.Id', '=', 'poliza_declarativa_control.ReprocesoNRId')
                ->select(
                    // === Control ===
                    'poliza_declarativa_control.Id',
                    'poliza_declarativa_control.PolizaDeudaId',
                    'poliza_declarativa_control.FechaRecepcionArchivo',
                    'poliza_declarativa_control.FechaEnvioCia',
                    'poliza_declarativa_control.TrabajoEfectuadoDiaHabil',
                    'poliza_declarativa_control.HoraTarea',
                    'poliza_declarativa_control.FlujoAsignado',
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
                    DB::raw("'DEUDA' AS TipoPoliza"),

                    // === poliza_deuda ===
                    'poliza_deuda.NumeroPoliza',
                    'poliza_deuda.VigenciaDesde',
                    'poliza_deuda.VigenciaHasta',
                    'poliza_deuda.Descuento',

                    // === detalle ===
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
                    'aseguradora.Abreviatura',

                    // === Subqueries originales ===
                    DB::raw("(SELECT COUNT(*) FROM poliza_deuda_cartera AS c
                        WHERE c.PolizaDeuda = poliza_deuda.Id
                        AND c.Axo = {$anio}
                        AND c.Mes = {$mes}
                        AND c.PolizaDeudaDetalle is not null
                    ) AS UsuariosReportados"),

                    DB::raw("(SELECT u.name
                        FROM poliza_deuda_cartera AS c
                        INNER JOIN users AS u ON u.id = c.User
                        WHERE c.PolizaDeuda = poliza_deuda.Id
                        AND c.Axo = {$anio}
                        AND c.Mes = {$mes}
                        ORDER BY c.Id ASC
                        LIMIT 1
                    ) AS Usuario")
                )
                ->orderBy('poliza_deuda.Id')
                ->groupBy('poliza_declarativa_control.Id')
                ->get();


            // === Consulta 2: VIDA (subqueries adaptadas) ===
            $vida = PolizaDeclarativaControl::query()
                ->where('poliza_declarativa_control.Axo', $anio)
                ->where('poliza_declarativa_control.Mes', $mes)
                ->join('poliza_vida', 'poliza_vida.Id', '=', 'poliza_declarativa_control.PolizaVidaId')
                ->leftJoin('poliza_vida_detalle', function ($join) use ($anio, $mes) {
                    $join->on('poliza_vida_detalle.PolizaVida', '=', 'poliza_vida.Id')
                        ->where('poliza_vida_detalle.Axo', '=', $anio)
                        ->where('poliza_vida_detalle.Mes', '=', $mes);
                })
                ->join('cliente', 'cliente.Id', '=', 'poliza_vida.Asegurado')
                ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_vida.Aseguradora')
                ->join('plan', 'plan.Id', '=', 'poliza_vida.Plan')
                ->join('producto', 'producto.Id', '=', 'plan.Producto')
                ->leftJoin('poliza_declarativa_reproceso', 'poliza_declarativa_reproceso.Id', '=', 'poliza_declarativa_control.ReprocesoNRId')
                ->select(
                    'poliza_declarativa_control.*',
                    DB::raw("'VIDA' AS TipoPoliza"),
                    'poliza_vida.NumeroPoliza',
                    'poliza_vida.VigenciaDesde',
                    'poliza_vida.VigenciaHasta',
                    'poliza_vida_detalle.MontoCartera',
                    'poliza_vida_detalle.PrimaCalculada',
                    'poliza_vida_detalle.ExtraPrima',
                    'poliza_vida_detalle.PrimaDescontada',
                    'poliza_vida_detalle.TasaComision',
                    'poliza_vida_detalle.Comision',
                    'poliza_vida_detalle.Retencion',
                    'poliza_vida_detalle.IvaSobreComision',
                    'poliza_vida_detalle.APagar',
                    'poliza_vida_detalle.FechaIngreso',
                    'poliza_vida_detalle.FechaInicio',
                    'poliza_vida_detalle.NumeroRecibo',
                    'poliza_vida_detalle.Axo',
                    'cliente.Nombre as ClienteNombre',
                    'cliente.Nit as ClienteNit',
                    'plan.Nombre as PlanNombre',
                    'producto.Nombre as ProductoNombre',
                    'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',
                    'aseguradora.Abreviatura',

                    // === Subqueries adaptadas a VIDA ===
                    DB::raw("(SELECT COUNT(*)
                    FROM poliza_vida_cartera AS c
                    WHERE c.PolizaVida = poliza_vida.Id
                    AND c.Axo = {$anio}
                    AND c.Mes = {$mes}
                    AND c.PolizaVidaDetalle is not null
                ) AS UsuariosReportados"),

                    DB::raw("(SELECT u.name
                    FROM poliza_vida_cartera AS c
                    INNER JOIN users AS u ON u.id = c.User
                    WHERE c.PolizaVida = poliza_vida.Id
                    AND c.Axo = {$anio}
                    AND c.Mes = {$mes}
                    ORDER BY c.Id ASC
                    LIMIT 1
                ) AS Usuario")
                )
                ->groupBy('poliza_declarativa_control.Id')
                ->get();


            // === Consulta 3: DESEMPLEO (subqueries adaptadas) ===
            $desempleo = PolizaDeclarativaControl::query()
                ->where('poliza_declarativa_control.Axo', $anio)
                ->where('poliza_declarativa_control.Mes', $mes)
                ->join('poliza_desempleo', 'poliza_desempleo.Id', '=', 'poliza_declarativa_control.PolizaDesempleoId')
                ->leftJoin('poliza_desempleo_detalle', function ($join) use ($anio, $mes) {
                    $join->on('poliza_desempleo_detalle.Desempleo', '=', 'poliza_desempleo.Id')
                        ->where('poliza_desempleo_detalle.Axo', '=', $anio)
                        ->where('poliza_desempleo_detalle.Mes', '=', $mes);
                })
                ->join('cliente', 'cliente.Id', '=', 'poliza_desempleo.Asegurado')
                ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_desempleo.Aseguradora')
                ->join('plan', 'plan.Id', '=', 'poliza_desempleo.Plan')
                ->join('producto', 'producto.Id', '=', 'plan.Producto')
                ->leftJoin('poliza_declarativa_reproceso', 'poliza_declarativa_reproceso.Id', '=', 'poliza_declarativa_control.ReprocesoNRId')
                ->select(
                    'poliza_declarativa_control.*',
                    DB::raw("'DESEMPLEO' AS TipoPoliza"),
                    'poliza_desempleo.NumeroPoliza',
                    'poliza_desempleo.VigenciaDesde',
                    'poliza_desempleo.VigenciaHasta',
                    'poliza_desempleo_detalle.MontoCartera',
                    'poliza_desempleo_detalle.PrimaCalculada',
                    'poliza_desempleo_detalle.ExtraPrima',
                    'poliza_desempleo_detalle.PrimaDescontada',
                    'poliza_desempleo_detalle.TasaComision',
                    'poliza_desempleo_detalle.Comision',
                    'poliza_desempleo_detalle.Retencion',
                    'poliza_desempleo_detalle.IvaSobreComision',
                    'poliza_desempleo_detalle.APagar',
                    'poliza_desempleo_detalle.FechaIngreso',
                    'poliza_desempleo_detalle.FechaInicio',
                    'poliza_desempleo_detalle.NumeroRecibo',
                    'poliza_desempleo_detalle.Axo',
                    'cliente.Nombre as ClienteNombre',
                    'cliente.Nit as ClienteNit',
                    'plan.Nombre as PlanNombre',
                    'producto.Nombre as ProductoNombre',
                    'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',
                    'aseguradora.Abreviatura',

                    // === Subqueries adaptadas a DESEMPLEO ===
                    DB::raw("(SELECT COUNT(*)
                    FROM poliza_desempleo_cartera AS c
                    WHERE c.PolizaDesempleo = poliza_desempleo.Id
                    AND c.Axo = {$anio}
                    AND c.Mes = {$mes}
                    AND c.PolizaDesempleoDetalle is not null
                ) AS UsuariosReportados"),

                    DB::raw("(SELECT u.name
                    FROM poliza_desempleo_cartera AS c
                    INNER JOIN users AS u ON u.id = c.User
                    WHERE c.PolizaDesempleo = poliza_desempleo.Id
                    AND c.Axo = {$anio}
                    AND c.Mes = {$mes}
                    ORDER BY c.Id ASC
                    LIMIT 1
                ) AS Usuario")
                )
                ->groupBy('poliza_declarativa_control.Id')
                ->get();


            // === COMBINAR LAS TRES ===
            $registro_control = $deuda
                ->concat($vida)
                ->concat($desempleo)
                ->sortBy('ClienteNombre')   // orden ascendente por nombre del cliente
                ->values();




            // =============================================================
            // 1️⃣ Asignar color de estado (aplica igual para ambas pólizas)
            // =============================================================
            foreach ($registro_control as $item) {
                if (!empty($item->FechaAplicacion)) {
                    $item->Color = 'success'; // ✅ Ya aplicada
                } elseif (!empty($item->FechaRecepcionPago) || !empty($item->FechaReporteACia)) {
                    $item->Color = 'warning'; // ⚠️ En proceso de pago o reporte
                } elseif (
                    !empty($item->AnexoDeclaracion) ||
                    !empty($item->NumeroSisco) ||
                    !empty($item->FechaVencimiento) ||
                    !empty($item->FechaEnvioCliente) ||
                    !empty($item->ReprocesoNRId) ||
                    !empty($item->FechaEnvioCorreccion) ||
                    !empty($item->FechaSeguimientoCobros)
                ) {
                    $item->Color = 'info'; // ℹ️ En trámite administrativo
                } elseif (
                    !empty($item->FechaRecepcionArchivo) ||
                    !empty($item->FechaEnvioCia) ||
                    !empty($item->TrabajoEfectuadoDiaHabil)
                ) {
                    $item->Color = 'orange'; // 🟠 Registro inicial / en recepción
                } else {
                    $item->Color = 'secondary'; // ⚪ Sin avance
                }
            }


            // =============================================================
            // 2️⃣ Procesar pólizas según tipo
            // =============================================================
            foreach ($registro_control as $item) {

                // === Si es póliza de DEUDA ===
                if ($item->TipoPoliza === 'DEUDA') {

                    if (empty($item->MontoCartera)) {
                        $poliza = Deuda::find($item->PolizaDeudaId);
                        if (!$poliza) continue;

                        $montoCartera = PolizaDeudaCartera::where('PolizaDeuda', $poliza->Id)
                            ->whereNull('PolizaDeudaDetalle')
                            ->where('Axo', $anio)
                            ->where('Mes', $mes)
                            ->sum('TotalCredito');

                        if ($montoCartera > 0) {
                            $item->MontoCartera = $montoCartera;

                            $primas = PolizaDeudaCartera::where('PolizaDeuda', $poliza->Id)
                                ->whereNull('PolizaDeudaDetalle')
                                ->where('Axo', $anio)
                                ->where('Mes', $mes)
                                ->select('Tasa', DB::raw('SUM(TotalCredito) * Tasa AS total'), DB::raw('count(*) AS conteoUsuarios'))
                                ->groupBy('Tasa')
                                ->get();

                            $item->UsuariosReportados = $primas->sum('conteoUsuarios');
                            $totalPrimas = $primas->sum('total');
                            $tasas = $primas->pluck('Tasa')->unique()->implode(', ');

                            // Cálculos estándar
                            $extraPrima = $poliza->ExtraPrima ?? 0;
                            $descuento = $poliza->Descuento ?? 0;
                            $tasaComision = $poliza->TasaComision ?? 0;
                            $tipoContribuyente = $poliza->clientes->TipoContribuyente ?? 0;

                            $primaDescontada = ($totalPrimas + $extraPrima) - (($totalPrimas + $extraPrima) * ($descuento / 100));
                            $valorComision = $primaDescontada * ($tasaComision / 100);
                            $ivaSobreComision = $tipoContribuyente != 4 ? $valorComision * 0.13 : 0;
                            $retencion = ($tipoContribuyente != 1 && $valorComision >= 100) ? $valorComision * 0.01 : 0;
                            $comisionTotal = $valorComision + $ivaSobreComision - $retencion;
                            $aPagar = $primaDescontada - $comisionTotal;

                            // Asignar
                            $item->Tasa = $tasas;
                            $item->PrimaCalculada = $totalPrimas;
                            $item->ExtraPrima = $extraPrima;
                            $item->PrimaDescontada = $primaDescontada;
                            $item->TasaComision = $tasaComision;
                            $item->Comision = $valorComision;
                            $item->Retencion = $retencion;
                            $item->IvaSobreComision = $ivaSobreComision;
                            $item->Iva = $ivaSobreComision;
                            $item->APagar = $aPagar;
                        }
                    }
                }

                // === Si es póliza de VIDA ===
                elseif ($item->TipoPoliza === 'VIDA') {

                    if (empty($item->MontoCartera)) {
                        $poliza = Vida::find($item->PolizaVidaId);
                        if (!$poliza) continue;

                        $montoCartera = VidaCartera::where('PolizaVida', $poliza->Id)
                            ->whereNull('PolizaVidaDetalle')
                            ->where('Axo', $anio)
                            ->where('Mes', $mes)
                            ->sum('SumaAsegurada');

                        if ($montoCartera > 0) {
                            $item->MontoCartera = $montoCartera;

                            $primas = VidaCartera::where('PolizaVida', $poliza->Id)
                                ->whereNull('PolizaVidaDetalle')
                                ->where('Axo', $anio)
                                ->where('Mes', $mes)
                                ->select('Tasa', DB::raw('SUM(SumaAsegurada) * Tasa AS total'), DB::raw('count(*) AS conteoUsuarios'))
                                ->groupBy('Tasa')
                                ->get();

                            $item->UsuariosReportados = $primas->sum('conteoUsuarios');
                            $totalPrimas = $primas->sum('total');
                            $tasas = $primas->pluck('Tasa')->unique()->implode(', ');

                            // Cálculos adaptados a VIDA
                            $extraPrima = $poliza->ExtraPrima ?? 0;
                            $descuento = $poliza->TasaDescuento ?? 0;
                            $tasaComision = $poliza->TasaComision ?? 0;
                            $tipoContribuyente = $poliza->clientes->TipoContribuyente ?? 0;

                            $primaDescontada = ($totalPrimas + $extraPrima) - (($totalPrimas + $extraPrima) * ($descuento / 100));
                            $valorComision = $primaDescontada * ($tasaComision / 100);
                            $ivaSobreComision = $tipoContribuyente != 4 ? $valorComision * 0.13 : 0;
                            $retencion = ($tipoContribuyente != 1 && $valorComision >= 100) ? $valorComision * 0.01 : 0;
                            $comisionTotal = $valorComision + $ivaSobreComision - $retencion;
                            $aPagar = $primaDescontada - $comisionTotal;

                            // Asignar
                            $item->Tasa = $tasas;
                            $item->PrimaCalculada = $totalPrimas;
                            $item->ExtraPrima = $extraPrima;
                            $item->PrimaDescontada = $primaDescontada;
                            $item->TasaComision = $tasaComision;
                            $item->Comision = $valorComision;
                            $item->Retencion = $retencion;
                            $item->IvaSobreComision = $ivaSobreComision;
                            $item->Iva = $ivaSobreComision;
                            $item->APagar = $aPagar;
                        }
                    }
                }


                // === Si es póliza de DESEMPLEO ===
                elseif ($item->TipoPoliza === 'DESEMPLEO') {

                    if (empty($item->MontoCartera)) {
                        $poliza = Desempleo::find($item->PolizaDesempleoId);
                        if (!$poliza) continue;

                        // === Monto de cartera ===
                        $montoCartera = DesempleoCartera::where('PolizaDesempleo', $poliza->Id)
                            ->whereNull('PolizaDesempleoDetalle')
                            ->where('Axo', $anio)
                            ->where('Mes', $mes)
                            ->sum('TotalCredito');

                        if ($montoCartera > 0) {
                            $item->MontoCartera = $montoCartera;

                            // === Agrupar primas según tasa ===
                            $primas = DesempleoCartera::where('PolizaDesempleo', $poliza->Id)
                                ->whereNull('PolizaDesempleoDetalle')
                                ->where('Axo', $anio)
                                ->where('Mes', $mes)
                                ->select('Tasa', DB::raw('SUM(TotalCredito) * Tasa AS total'), DB::raw('count(*) AS conteoUsuarios'))
                                ->groupBy('Tasa')
                                ->get();

                            $item->UsuariosReportados = $primas->sum('conteoUsuarios');
                            $totalPrimas = $primas->sum('total');
                            $tasas = $primas->pluck('Tasa')->unique()->implode(', ');

                            // === Cálculos adaptados a DESEMPLEO ===
                            $extraPrima = $poliza->ExtraPrima ?? 0;
                            $descuento = $poliza->Descuento ?? 0;
                            $tasaComision = $poliza->TasaComision ?? 0;
                            $tipoContribuyente = $poliza->clientes->TipoContribuyente ?? 0;
                            $comisionIva = $poliza->ComisionIva ?? 0;

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
                            $item->Iva = $ivaSobreComision;
                            $item->APagar = $aPagar;
                        }
                    }
                }
            }
        } else {
            $residenciaIdArray = Residencia::pluck('Id')->toArray();



            foreach ($residenciaIdArray as $idResidencia) {

                // Verificar si ya existe un registro con ese IdDeuda, mes y año
                $existe = PolizaDeclarativaControl::where('PolizaResidenciaId', $idResidencia)
                    ->where('Mes', $mes)
                    ->where('Axo', $anio)
                    ->exists();

                if (!$existe) {
                    // Si no existe, insertar un nuevo registro
                    PolizaDeclarativaControl::create([
                        'PolizaResidenciaId' => $idResidencia,
                        'Axo' => $anio,
                        'Mes' => $mes,
                    ]);
                }
            }


            // === Consulta 1: DEUDA ===

            $registro_control = PolizaDeclarativaControl::query()
                ->where('poliza_declarativa_control.Axo', $anio)
                ->where('poliza_declarativa_control.Mes', $mes)
                ->join('poliza_residencia', 'poliza_residencia.Id', '=', 'poliza_declarativa_control.PolizaResidenciaId')
                ->leftJoin('poliza_residencia_detalle', function ($join) use ($anio, $mes) {
                    $join->on('poliza_residencia_detalle.Residencia', '=', 'poliza_residencia.Id')
                        ->where('poliza_residencia_detalle.Axo', '=', $anio)
                        ->where('poliza_residencia_detalle.Mes', '=', $mes);
                })
                ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_residencia.Aseguradora')
                ->join('cliente', 'cliente.Id', '=', 'poliza_residencia.Asegurado')
                ->join('plan', 'plan.Id', '=', 'poliza_residencia.Plan')
                ->join('producto', 'producto.Id', '=', 'plan.Producto')
                ->leftJoin('poliza_declarativa_reproceso', 'poliza_declarativa_reproceso.Id', '=', 'poliza_declarativa_control.ReprocesoNRId')
                ->select(
                    // === Control ===
                    'poliza_declarativa_control.Id',
                    'poliza_declarativa_control.PolizaResidenciaId',
                    'poliza_declarativa_control.FechaRecepcionArchivo',
                    'poliza_declarativa_control.FechaEnvioCia',
                    'poliza_declarativa_control.TrabajoEfectuadoDiaHabil',
                    'poliza_declarativa_control.HoraTarea',
                    'poliza_declarativa_control.FlujoAsignado',
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
                    DB::raw("'RESIDENCIA' AS TipoPoliza"),

                    // === poliza_residencia ===
                    'poliza_residencia.NumeroPoliza',
                    'poliza_residencia.VigenciaDesde',
                    'poliza_residencia.VigenciaHasta',
                    'poliza_residencia.Descuento',

                    // === detalle ===
                    'poliza_residencia_detalle.MontoCartera',
                    'poliza_residencia_detalle.Tasa',
                    'poliza_residencia_detalle.PrimaCalculada',
                    'poliza_residencia_detalle.ExtraPrima',
                    'poliza_residencia_detalle.PrimaDescontada',
                    'poliza_residencia_detalle.TasaComision',
                    'poliza_residencia_detalle.Comision',
                    'poliza_residencia_detalle.Retencion',
                    'poliza_residencia_detalle.IvaSobreComision',
                    'poliza_residencia_detalle.Iva',
                    'poliza_residencia_detalle.APagar',
                    'poliza_residencia_detalle.Anexo',
                    'poliza_residencia_detalle.Comentario',
                    'poliza_residencia_detalle.FechaIngreso',
                    'poliza_residencia_detalle.FechaInicio',
                    'poliza_residencia_detalle.Descuento as ValorDescuentoRentabilidad',
                    'poliza_residencia_detalle.NumeroRecibo',
                    'poliza_residencia_detalle.Axo',

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
                    'aseguradora.Abreviatura',

                    // === Subqueries ===
                    DB::raw("(SELECT COUNT(*) FROM poliza_residencia_cartera AS c
                        WHERE c.PolizaResidencia = poliza_residencia.Id
                        AND c.Axo = {$anio}
                        AND c.Mes = {$mes}
                        AND c.IdUnicoCartera IS NOT NULL
                    ) AS UsuariosReportados"),

                    DB::raw("(SELECT u.name
                        FROM poliza_residencia_cartera AS c
                        INNER JOIN users AS u ON u.id = c.User
                        WHERE c.PolizaResidencia = poliza_residencia.Id
                        AND c.Axo = {$anio}
                        AND c.Mes = {$mes}
                        ORDER BY c.Id ASC
                        LIMIT 1
                    ) AS Usuario")
                )
                ->orderBy('poliza_residencia.Id')
                ->groupBy('poliza_declarativa_control.Id')
                ->get()
                ->sortBy(fn($item) => strtolower($item->ClienteNombre)) // 🔹 Ordena alfabéticamente ignorando mayúsculas
                ->values(); // 🔹 Reindexa la colección


            // =============================================================
            // 1️⃣ Asignar color de estado (aplica igual para ambas pólizas)
            // =============================================================
            foreach ($registro_control as $item) {
                if (!empty($item->FechaAplicacion)) {
                    $item->Color = 'success'; // ✅ Ya aplicada
                } elseif (!empty($item->FechaRecepcionPago) || !empty($item->FechaReporteACia)) {
                    $item->Color = 'warning'; // ⚠️ En proceso de pago o reporte
                } elseif (
                    !empty($item->AnexoDeclaracion) ||
                    !empty($item->NumeroSisco) ||
                    !empty($item->FechaVencimiento) ||
                    !empty($item->FechaEnvioCliente) ||
                    !empty($item->ReprocesoNRId) ||
                    !empty($item->FechaEnvioCorreccion) ||
                    !empty($item->FechaSeguimientoCobros)
                ) {
                    $item->Color = 'info'; // ℹ️ En trámite administrativo
                } elseif (
                    !empty($item->FechaRecepcionArchivo) ||
                    !empty($item->FechaEnvioCia) ||
                    !empty($item->TrabajoEfectuadoDiaHabil)
                ) {
                    $item->Color = 'orange'; // 🟠 Registro inicial / en recepción
                } else {
                    $item->Color = 'secondary'; // ⚪ Sin avance
                }
            }


            foreach ($registro_control as $item) {
                if (empty($item->MontoCartera)) {

                    // Buscar la póliza de residencia asociada
                    $poliza = Residencia::find($item->PolizaResidenciaId);
                    if (!$poliza) continue;

                    // Sumar monto total de cartera (Total de SumaAsegurada o PrimaMensual, según tu caso)
                    $montoCartera = PolizaResidenciaCartera::where('PolizaResidencia', $poliza->Id)
                        ->whereNull('IdUnicoCartera') // según tu estructura de control
                        ->where('Axo', $anio)
                        ->where('Mes', $mes)
                        ->sum('SumaAsegurada'); // campo equivalente al TotalCredito

                    if ($montoCartera > 0) {
                        $item->MontoCartera = $montoCartera;

                        // Agrupar por tasa para calcular primas
                        $primas = PolizaResidenciaCartera::where('PolizaResidencia', $poliza->Id)
                            ->whereNull('IdUnicoCartera')
                            ->where('Axo', $anio)
                            ->where('Mes', $mes)
                            ->select('Tarifa as Tasa', DB::raw('SUM(SumaAsegurada) * Tarifa AS total'), DB::raw('count(*) AS conteoUsuarios'))
                            ->groupBy('Tarifa')
                            ->get();

                        $item->UsuariosReportados = $primas->sum('conteoUsuarios');
                        $totalPrimas = $primas->sum('total');
                        $tasas = $primas->pluck('Tasa')->unique()->implode(', ');

                        // === Cálculos estándar ===
                        $extraPrima = $poliza->ExtraPrima ?? 0;
                        $descuento = $poliza->Descuento ?? 0;
                        $tasaComision = $poliza->Comision ?? 0;
                        $tipoContribuyente = $poliza->cliente->TipoContribuyente ?? 0;

                        $primaDescontada = ($totalPrimas + $extraPrima) - (($totalPrimas + $extraPrima) * ($descuento / 100));
                        $valorComision = $primaDescontada * ($tasaComision / 100);
                        $ivaSobreComision = $tipoContribuyente != 4 ? $valorComision * 0.13 : 0;
                        $retencion = ($tipoContribuyente != 1 && $valorComision >= 100) ? $valorComision * 0.01 : 0;
                        $comisionTotal = $valorComision + $ivaSobreComision - $retencion;
                        $aPagar = $primaDescontada - $comisionTotal;

                        // === Asignar resultados al registro ===
                        $item->Tasa = $tasas;
                        $item->PrimaCalculada = $totalPrimas;
                        $item->ExtraPrima = $extraPrima;
                        $item->PrimaDescontada = $primaDescontada;
                        $item->TasaComision = $tasaComision;
                        $item->Comision = $valorComision;
                        $item->Retencion = $retencion;
                        $item->IvaSobreComision = $ivaSobreComision;
                        $item->Iva = $ivaSobreComision;
                        $item->APagar = $aPagar;
                    }
                }
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


        return redirect()->back()->with('success', 'Registro actualizado correctamente')->withFragment('fila-' . $control_cartera->Id);
    }












    //metodos para actualizar dias habiles
    public function actualizacion(Request $request)
    {
        $zonaHoraria = 'America/El_Salvador';

        // 1️⃣ Buscar los registros con ambas fechas
        $registros = PolizaDeclarativaControl::whereNotNull('FechaRecepcionArchivo')
            ->whereNotNull('FechaEnvioCia')
            ->get();

        $contador = 0;

        foreach ($registros as $registro) {
            // 2️⃣ Validar que ambas fechas existan y sean válidas
            if (!$registro->FechaRecepcionArchivo || !$registro->FechaEnvioCia) {
                continue;
            }

            // 3️⃣ Calcular días hábiles usando tu función existente
            $diasHabiles = $this->calcularDiasHabiles(
                Carbon::parse($registro->FechaRecepcionArchivo)->setTimezone($zonaHoraria),
                Carbon::parse($registro->FechaEnvioCia)->setTimezone($zonaHoraria)
            );

            // 4️⃣ Actualizar el campo en la base de datos
            $registro->TrabajoEfectuadoDiaHabil = $diasHabiles;
            $registro->save();

            $contador++;
        }

        return response()->json([
            'mensaje' => "✅ Se actualizaron {$contador} registros correctamente.",
            'total_registros' => $registros->count(),
            'actualizados' => $contador
        ]);
    }



    public function calcularDiasHabilesJson(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $dias = $this->calcularDiasHabiles(
            $request->fecha_inicio,
            $request->fecha_fin
        );

        return response()->json(['dias_habiles' => $dias]);
    }

    public function calcularDiasHabiles($fechaInicio, $fechaFin)
    {
        $zonaHoraria = 'America/El_Salvador';

        $inicio = Carbon::parse($fechaInicio)->setTimezone($zonaHoraria)->startOfDay();
        $fin = Carbon::parse($fechaFin)->setTimezone($zonaHoraria)->startOfDay();

        // 🚩 Caso especial: misma fecha
        if ($inicio->equalTo($fin)) {
            return 0;
        }

        // 🚩 Caso especial: rango solo de fin de semana (ej. sábado → domingo)
        if ($inicio->isWeekend() && $fin->isWeekend() && $inicio->diffInDays($fin) <= 1) {
            return 0;
        }

        // 3. Obtener feriados que solapan con el rango
        $feriados = FechasFeriadas::where('FechaFinal', '>=', $inicio->toDateString())
            ->where('FechaInicio', '<=', $fin->toDateString())
            ->where('Activo', 1)
            ->get(['FechaInicio', 'FechaFinal']);

        // 4. Calcular días hábiles base (sin fines de semana)
        $diasHabiles = $inicio->diffInDaysFiltered(function (Carbon $fecha) {
            return !$fecha->isWeekend();
        }, $fin->copy()->addDay());

        // 5. Restar feriados que caen en días laborales
        $diasFeriados = 0;
        foreach ($feriados as $feriado) {
            $periodoFeriado = CarbonPeriod::create(
                Carbon::parse($feriado->FechaInicio)->setTimezone($zonaHoraria)->startOfDay(),
                Carbon::parse($feriado->FechaFinal)->setTimezone($zonaHoraria)->endOfDay()
            );

            foreach ($periodoFeriado as $fechaFeriado) {
                if ($fechaFeriado->between($inicio, $fin) && !$fechaFeriado->isWeekend()) {
                    $diasFeriados++;
                }
            }
        }

        return $diasHabiles - $diasFeriados - 1;
    }
}
