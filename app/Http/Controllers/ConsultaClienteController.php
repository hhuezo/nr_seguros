<?php

namespace App\Http\Controllers;

use App\Exports\ConsultaClienteExport;
use App\Models\polizas\DesempleoCartera;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\polizas\PolizaResidenciaCartera;
use App\Models\polizas\VidaCartera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ConsultaClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('consulta.cliente.index', [
            'tipo_busqueda' => 'documento',
        ]);
    }

    public function buscar(Request $request)
    {
        $filtros = $this->resolverFiltrosBusqueda($request);
        if ($filtros['mensaje'] !== null) {
            return view('consulta.cliente.index', [
                'resultados' => collect(),
                'busqueda' => $filtros['busqueda'],
                'tipo_busqueda' => $filtros['tipo_busqueda'],
                'mensaje' => $filtros['mensaje'],
            ]);
        }

        $consulta = $this->obtenerConsultaClienteData($filtros['busqueda'], $filtros['tipo_busqueda']);

        return view('consulta.cliente.index', [
            'resultados' => $consulta['resultados'],
            'busqueda' => $filtros['busqueda'],
            'tipo_busqueda' => $filtros['tipo_busqueda'],
            'totales' => $consulta['totales'],
        ]);
    }

    public function exportar(Request $request)
    {
        $filtros = $this->resolverFiltrosBusqueda($request);
        if ($filtros['mensaje'] !== null) {
            return redirect()->to(url('consulta/cliente/buscar') . '?' . http_build_query([
                'busqueda' => $filtros['busqueda'],
                'tipo_busqueda' => $filtros['tipo_busqueda'],
            ]));
        }

        $consulta = $this->obtenerConsultaClienteData($filtros['busqueda'], $filtros['tipo_busqueda']);

        return Excel::download(
            new ConsultaClienteExport($consulta['resultados']),
            'consulta_cliente_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    private function resolverFiltrosBusqueda(Request $request): array
    {
        $busqueda = trim($request->get('busqueda', ''));
        $tipoBusqueda = $request->get('tipo_busqueda', 'documento');
        $tiposBusquedaValidos = ['dui', 'nit', 'pasaporte', 'documento', 'nombre'];

        if (!in_array($tipoBusqueda, $tiposBusquedaValidos, true)) {
            $tipoBusqueda = 'documento';
        }

        if ($busqueda === '') {
            return [
                'busqueda' => '',
                'tipo_busqueda' => $tipoBusqueda,
                'mensaje' => 'Por favor ingrese un valor para buscar',
            ];
        }

        if ($tipoBusqueda === 'nombre' && mb_strlen($busqueda) < 4) {
            return [
                'busqueda' => $busqueda,
                'tipo_busqueda' => $tipoBusqueda,
                'mensaje' => 'Para buscar por nombre completo debe ingresar al menos 4 caracteres',
            ];
        }

        return [
            'busqueda' => $busqueda,
            'tipo_busqueda' => $tipoBusqueda,
            'mensaje' => null,
        ];
    }

    private function obtenerConsultaClienteData(string $busqueda, string $tipoBusqueda): array
    {
        $deudaExtraPrimaMensual = DB::table('poliza_deuda_extra_primado_mensual')
            ->select(
                'DeudaDetalle',
                'PolizaDeuda',
                'NumeroReferencia',
                DB::raw('MAX(PorcentajeEP) as PorcentajeEP'),
                DB::raw('SUM(PagoEP) as PagoEP')
            )
            ->groupBy('DeudaDetalle', 'PolizaDeuda', 'NumeroReferencia');

        $deudaExtraPrimaMaestro = DB::table('poliza_deuda_extra_primado')
            ->select(
                'PolizaDeuda',
                'NumeroReferencia',
                DB::raw('MAX(PorcentajeEP) as PorcentajeEP')
            )
            ->groupBy('PolizaDeuda', 'NumeroReferencia');

        $vidaExtraPrimaMensual = DB::table('poliza_vida_extra_primado_mensual')
            ->select(
                'VidaDetalle',
                'PolizaVida',
                'NumeroReferencia',
                DB::raw('MAX(PorcentajeEP) as PorcentajeEP'),
                DB::raw('SUM(PagoEP) as PagoEP')
            )
            ->groupBy('VidaDetalle', 'PolizaVida', 'NumeroReferencia');

        $vidaExtraPrimaMaestro = DB::table('poliza_vida_extra_primado')
            ->select(
                'PolizaVida',
                'NumeroReferencia',
                DB::raw('MAX(PorcentajeEP) as PorcentajeEP')
            )
            ->groupBy('PolizaVida', 'NumeroReferencia');

        $ultimoDeuda = PolizaDeudaCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        $deudaCartera = PolizaDeudaCartera::query();
        $this->aplicarFiltroBusqueda(
            $deudaCartera,
            $tipoBusqueda,
            $busqueda,
            [
                'dui' => 'poliza_deuda_cartera.Dui',
                'pasaporte' => 'poliza_deuda_cartera.Pasaporte',
            ],
            [
                "TRIM(CONCAT_WS(' ', COALESCE(poliza_deuda_cartera.PrimerNombre, ''), COALESCE(poliza_deuda_cartera.SegundoNombre, ''), COALESCE(poliza_deuda_cartera.PrimerApellido, ''), COALESCE(poliza_deuda_cartera.SegundoApellido, ''), COALESCE(poliza_deuda_cartera.ApellidoCasada, '')))",
                "poliza_deuda_cartera.NombreSociedad",
            ]
        );

        if ($ultimoDeuda) {
            $deudaCartera->where('poliza_deuda_cartera.Axo', $ultimoDeuda->Axo)
                ->where('poliza_deuda_cartera.Mes', $ultimoDeuda->Mes);
        }

        $deudaCartera = $deudaCartera
            ->leftJoin('poliza_deuda', 'poliza_deuda.Id', '=', 'poliza_deuda_cartera.PolizaDeuda')
            ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_deuda.Aseguradora')
            ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_deuda.Asegurado')
            ->leftJoin('plan as plan_deuda', 'plan_deuda.Id', '=', 'poliza_deuda.Plan')
            ->leftJoin('producto as producto_deuda', 'producto_deuda.Id', '=', 'plan_deuda.Producto')
            ->leftJoin('saldos_montos', 'saldos_montos.Id', '=', 'poliza_deuda_cartera.LineaCredito')
            ->leftJoin('poliza_deuda_detalle as deuda_detalle', 'deuda_detalle.Id', '=', 'poliza_deuda_cartera.PolizaDeudaDetalle')
            ->leftJoinSub($deudaExtraPrimaMensual, 'deuda_ep_mensual', function ($join) {
                $join->on('deuda_ep_mensual.DeudaDetalle', '=', 'deuda_detalle.Id')
                    ->on('deuda_ep_mensual.PolizaDeuda', '=', 'poliza_deuda_cartera.PolizaDeuda')
                    ->whereRaw('deuda_ep_mensual.NumeroReferencia COLLATE utf8mb4_unicode_ci = poliza_deuda_cartera.NumeroReferencia COLLATE utf8mb4_unicode_ci');
            })
            ->leftJoinSub($deudaExtraPrimaMaestro, 'deuda_ep_maestro', function ($join) {
                $join->on('deuda_ep_maestro.PolizaDeuda', '=', 'poliza_deuda_cartera.PolizaDeuda')
                    ->whereRaw('deuda_ep_maestro.NumeroReferencia COLLATE utf8mb4_unicode_ci = poliza_deuda_cartera.NumeroReferencia COLLATE utf8mb4_unicode_ci');
            })
            ->select(
                DB::raw("'Deuda' as TipoCartera"),
                'poliza_deuda_cartera.PrimerNombre',
                'poliza_deuda_cartera.SegundoNombre',
                'poliza_deuda_cartera.PrimerApellido',
                'poliza_deuda_cartera.SegundoApellido',
                'poliza_deuda_cartera.ApellidoCasada',
                'poliza_deuda_cartera.NombreSociedad',
                'poliza_deuda_cartera.Dui',
                DB::raw("NULL as Nit"),
                'poliza_deuda_cartera.Pasaporte',
                DB::raw("NULL as CarnetResidencia"),
                'poliza_deuda_cartera.Nacionalidad',
                'poliza_deuda_cartera.FechaNacimiento',
                'poliza_deuda_cartera.NumeroReferencia',
                'poliza_deuda_cartera.MontoOtorgado',
                'poliza_deuda_cartera.SaldoCapital',
                DB::raw("NULL as SumaAsegurada"),
                'poliza_deuda_cartera.Intereses',
                'poliza_deuda_cartera.InteresesMoratorios',
                'poliza_deuda_cartera.InteresesCovid',
                'poliza_deuda_cartera.FechaOtorgamiento',
                'poliza_deuda_cartera.FechaVencimiento',
                'poliza_deuda_cartera.Axo',
                'poliza_deuda_cartera.Mes',
                'poliza_deuda_cartera.Tasa as TarifaMes',
                'poliza_deuda.NumeroPoliza',
                'aseguradora.Nombre as AseguradoraNombre',
                DB::raw("TRIM(CONCAT(COALESCE(producto_deuda.Nombre, ''), CASE WHEN producto_deuda.Nombre IS NOT NULL AND plan_deuda.Nombre IS NOT NULL THEN ' / ' ELSE '' END, COALESCE(plan_deuda.Nombre, ''))) as ProductoPlan"),
                'cliente.Nombre as ContratanteNombre',
                'saldos_montos.Descripcion as LineaDescripcion',
                DB::raw("(COALESCE(poliza_deuda_cartera.TotalCredito, 0) * COALESCE(poliza_deuda_cartera.Tasa, 0)) as PrimaMes"),
                DB::raw("COALESCE(deuda_ep_mensual.PorcentajeEP, deuda_ep_maestro.PorcentajeEP, poliza_deuda_cartera.PorcentajeExtraprima) as PorcentajeExtraprima"),
                DB::raw("
                    CASE
                        WHEN COALESCE(deuda_ep_mensual.PorcentajeEP, deuda_ep_maestro.PorcentajeEP, poliza_deuda_cartera.PorcentajeExtraprima) IS NULL THEN NULL
                        WHEN deuda_ep_mensual.PagoEP IS NOT NULL AND deuda_ep_mensual.PagoEP <> 0 THEN deuda_ep_mensual.PagoEP
                        ELSE (COALESCE(poliza_deuda_cartera.TotalCredito, 0) * COALESCE(poliza_deuda_cartera.Tasa, 0))
                            * (COALESCE(deuda_ep_mensual.PorcentajeEP, deuda_ep_maestro.PorcentajeEP, poliza_deuda_cartera.PorcentajeExtraprima) / 100)
                    END as ExtraPrimaMes
                ")
            )
            ->get()
            ->map(function ($item) {
                $item->TipoCartera = 'Deuda';
                $nombre = trim(($item->PrimerNombre ?? '') . ' ' . ($item->SegundoNombre ?? '') . ' ' .
                    ($item->PrimerApellido ?? '') . ' ' . ($item->SegundoApellido ?? '') . ' ' .
                    ($item->ApellidoCasada ?? ''));
                $item->NombreCompleto = $nombre ?: ($item->NombreSociedad ?? '');
                return $item;
            });

        $ultimoResidencia = PolizaResidenciaCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        $residenciaCartera = PolizaResidenciaCartera::query();
        $this->aplicarFiltroBusqueda(
            $residenciaCartera,
            $tipoBusqueda,
            $busqueda,
            [
                'dui' => 'poliza_residencia_cartera.Dui',
                'nit' => 'poliza_residencia_cartera.Nit',
                'pasaporte' => 'poliza_residencia_cartera.Pasaporte',
            ],
            [
                "poliza_residencia_cartera.NombreCompleto",
                "poliza_residencia_cartera.NombreSociedad",
            ]
        );

        if ($ultimoResidencia) {
            $residenciaCartera->where('poliza_residencia_cartera.Axo', $ultimoResidencia->Axo)
                ->where('poliza_residencia_cartera.Mes', $ultimoResidencia->Mes);
        }

        $residenciaCartera = $residenciaCartera
            ->leftJoin('poliza_residencia', 'poliza_residencia.Id', '=', 'poliza_residencia_cartera.PolizaResidencia')
            ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_residencia.Aseguradora')
            ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_residencia.Asegurado')
            ->leftJoin('plan as plan_residencia', 'plan_residencia.Id', '=', 'poliza_residencia.Plan')
            ->leftJoin('producto as producto_residencia', 'producto_residencia.Id', '=', 'plan_residencia.Producto')
            ->select(
                DB::raw("'Residencia' as TipoCartera"),
                'poliza_residencia_cartera.NombreCompleto',
                DB::raw("NULL as PrimerNombre"),
                DB::raw("NULL as SegundoNombre"),
                DB::raw("NULL as PrimerApellido"),
                DB::raw("NULL as SegundoApellido"),
                DB::raw("NULL as ApellidoCasada"),
                'poliza_residencia_cartera.NombreSociedad',
                'poliza_residencia_cartera.Dui',
                'poliza_residencia_cartera.Nit',
                'poliza_residencia_cartera.Pasaporte',
                'poliza_residencia_cartera.CarnetResidencia',
                'poliza_residencia_cartera.Nacionalidad',
                'poliza_residencia_cartera.FechaNacimiento',
                'poliza_residencia_cartera.NumeroReferencia',
                DB::raw("NULL as MontoOtorgado"),
                DB::raw("NULL as SaldoCapital"),
                'poliza_residencia_cartera.SumaAsegurada',
                DB::raw("NULL as Intereses"),
                DB::raw("NULL as InteresesMoratorios"),
                DB::raw("NULL as InteresesCovid"),
                'poliza_residencia_cartera.FechaOtorgamiento',
                'poliza_residencia_cartera.FechaVencimiento',
                'poliza_residencia_cartera.Axo',
                'poliza_residencia_cartera.Mes',
                'poliza_residencia_cartera.Tarifa as TarifaMes',
                'poliza_residencia.NumeroPoliza',
                'aseguradora.Nombre as AseguradoraNombre',
                DB::raw("TRIM(CONCAT(COALESCE(producto_residencia.Nombre, ''), CASE WHEN producto_residencia.Nombre IS NOT NULL AND plan_residencia.Nombre IS NOT NULL THEN ' / ' ELSE '' END, COALESCE(plan_residencia.Nombre, ''))) as ProductoPlan"),
                'cliente.Nombre as ContratanteNombre',
                DB::raw("NULL as LineaDescripcion"),
                DB::raw("poliza_residencia_cartera.PrimaMensual as PrimaMes"),
                DB::raw("NULL as PorcentajeExtraprima"),
                DB::raw("NULL as ExtraPrimaMes")
            )
            ->get()
            ->map(function ($item) {
                $item->TipoCartera = 'Residencia';
                return $item;
            });

        $ultimoVida = VidaCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        $vidaCartera = VidaCartera::query();
        $this->aplicarFiltroBusqueda(
            $vidaCartera,
            $tipoBusqueda,
            $busqueda,
            [
                'dui' => 'poliza_vida_cartera.Dui',
                'nit' => 'poliza_vida_cartera.Nit',
                'pasaporte' => 'poliza_vida_cartera.Pasaporte',
            ],
            [
                "TRIM(CONCAT_WS(' ', COALESCE(poliza_vida_cartera.PrimerNombre, ''), COALESCE(poliza_vida_cartera.SegundoNombre, ''), COALESCE(poliza_vida_cartera.PrimerApellido, ''), COALESCE(poliza_vida_cartera.SegundoApellido, ''), COALESCE(poliza_vida_cartera.ApellidoCasada, '')))",
            ]
        );

        if ($ultimoVida) {
            $vidaCartera->where('poliza_vida_cartera.Axo', $ultimoVida->Axo)
                ->where('poliza_vida_cartera.Mes', $ultimoVida->Mes);
        }

        $vidaCartera = $vidaCartera
            ->leftJoin('poliza_vida', 'poliza_vida.Id', '=', 'poliza_vida_cartera.PolizaVida')
            ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_vida.Aseguradora')
            ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_vida.Asegurado')
            ->leftJoin('plan as plan_vida', 'plan_vida.Id', '=', 'poliza_vida.Plan')
            ->leftJoin('producto as producto_vida_plan', 'producto_vida_plan.Id', '=', 'plan_vida.Producto')
            ->leftJoin('producto as producto_vida_directo', 'producto_vida_directo.Id', '=', 'poliza_vida.Producto')
            ->leftJoin('poliza_vida_detalle as vida_detalle', 'vida_detalle.Id', '=', 'poliza_vida_cartera.PolizaVidaDetalle')
            ->leftJoinSub($vidaExtraPrimaMensual, 'vida_ep_mensual', function ($join) {
                $join->on('vida_ep_mensual.VidaDetalle', '=', 'vida_detalle.Id')
                    ->on('vida_ep_mensual.PolizaVida', '=', 'poliza_vida_cartera.PolizaVida')
                    ->whereRaw('vida_ep_mensual.NumeroReferencia COLLATE utf8mb4_unicode_ci = poliza_vida_cartera.NumeroReferencia COLLATE utf8mb4_unicode_ci');
            })
            ->leftJoinSub($vidaExtraPrimaMaestro, 'vida_ep_maestro', function ($join) {
                $join->on('vida_ep_maestro.PolizaVida', '=', 'poliza_vida_cartera.PolizaVida')
                    ->whereRaw('vida_ep_maestro.NumeroReferencia COLLATE utf8mb4_unicode_ci = poliza_vida_cartera.NumeroReferencia COLLATE utf8mb4_unicode_ci');
            })
            ->select(
                DB::raw("'Vida' as TipoCartera"),
                'poliza_vida_cartera.PrimerNombre',
                'poliza_vida_cartera.SegundoNombre',
                'poliza_vida_cartera.PrimerApellido',
                'poliza_vida_cartera.SegundoApellido',
                'poliza_vida_cartera.ApellidoCasada',
                DB::raw("NULL as NombreSociedad"),
                'poliza_vida_cartera.Dui',
                'poliza_vida_cartera.Nit',
                'poliza_vida_cartera.Pasaporte',
                DB::raw("NULL as CarnetResidencia"),
                'poliza_vida_cartera.Nacionalidad',
                'poliza_vida_cartera.FechaNacimiento',
                'poliza_vida_cartera.NumeroReferencia',
                DB::raw("NULL as MontoOtorgado"),
                DB::raw("NULL as SaldoCapital"),
                'poliza_vida_cartera.SumaAsegurada',
                DB::raw("NULL as Intereses"),
                DB::raw("NULL as InteresesMoratorios"),
                DB::raw("NULL as InteresesCovid"),
                'poliza_vida_cartera.FechaOtorgamiento',
                'poliza_vida_cartera.FechaVencimiento',
                'poliza_vida_cartera.Axo',
                'poliza_vida_cartera.Mes',
                'poliza_vida_cartera.Tasa as TarifaMes',
                'poliza_vida.NumeroPoliza',
                'aseguradora.Nombre as AseguradoraNombre',
                DB::raw("TRIM(CONCAT(COALESCE(producto_vida_directo.Nombre, producto_vida_plan.Nombre, ''), CASE WHEN COALESCE(producto_vida_directo.Nombre, producto_vida_plan.Nombre) IS NOT NULL AND plan_vida.Nombre IS NOT NULL THEN ' / ' ELSE '' END, COALESCE(plan_vida.Nombre, ''))) as ProductoPlan"),
                'cliente.Nombre as ContratanteNombre',
                DB::raw("NULL as LineaDescripcion"),
                DB::raw("(COALESCE(poliza_vida_cartera.SumaAsegurada, 0) * COALESCE(poliza_vida_cartera.Tasa, 0)) as PrimaMes"),
                DB::raw("COALESCE(vida_ep_mensual.PorcentajeEP, vida_ep_maestro.PorcentajeEP, poliza_vida_cartera.PorcentajeExtraprima) as PorcentajeExtraprima"),
                DB::raw("
                    CASE
                        WHEN COALESCE(vida_ep_mensual.PorcentajeEP, vida_ep_maestro.PorcentajeEP, poliza_vida_cartera.PorcentajeExtraprima) IS NULL THEN NULL
                        WHEN vida_ep_mensual.PagoEP IS NOT NULL AND vida_ep_mensual.PagoEP <> 0 THEN vida_ep_mensual.PagoEP
                        ELSE (COALESCE(poliza_vida_cartera.SumaAsegurada, 0) * COALESCE(poliza_vida_cartera.Tasa, 0))
                            * (COALESCE(vida_ep_mensual.PorcentajeEP, vida_ep_maestro.PorcentajeEP, poliza_vida_cartera.PorcentajeExtraprima) / 100)
                    END as ExtraPrimaMes
                ")
            )
            ->get()
            ->map(function ($item) {
                $item->TipoCartera = 'Vida';
                $nombre = trim(($item->PrimerNombre ?? '') . ' ' . ($item->SegundoNombre ?? '') . ' ' .
                    ($item->PrimerApellido ?? '') . ' ' . ($item->SegundoApellido ?? '') . ' ' .
                    ($item->ApellidoCasada ?? ''));
                $item->NombreCompleto = $nombre ?: '';
                return $item;
            });

        $ultimoDesempleo = DesempleoCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        $desempleoCartera = DesempleoCartera::query();
        $this->aplicarFiltroBusqueda(
            $desempleoCartera,
            $tipoBusqueda,
            $busqueda,
            [
                'dui' => 'poliza_desempleo_cartera.Dui',
                'nit' => 'poliza_desempleo_cartera.Nit',
                'pasaporte' => 'poliza_desempleo_cartera.Pasaporte',
            ],
            [
                "TRIM(CONCAT_WS(' ', COALESCE(poliza_desempleo_cartera.PrimerNombre, ''), COALESCE(poliza_desempleo_cartera.SegundoNombre, ''), COALESCE(poliza_desempleo_cartera.PrimerApellido, ''), COALESCE(poliza_desempleo_cartera.SegundoApellido, ''), COALESCE(poliza_desempleo_cartera.ApellidoCasada, '')))",
                "poliza_desempleo_cartera.NombreSociedad",
            ]
        );

        if ($ultimoDesempleo) {
            $desempleoCartera->where('poliza_desempleo_cartera.Axo', $ultimoDesempleo->Axo)
                ->where('poliza_desempleo_cartera.Mes', $ultimoDesempleo->Mes);
        }

        $desempleoCartera = $desempleoCartera
            ->leftJoin('poliza_desempleo', 'poliza_desempleo.Id', '=', 'poliza_desempleo_cartera.PolizaDesempleo')
            ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_desempleo.Aseguradora')
            ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_desempleo.Asegurado')
            ->leftJoin('plan as plan_desempleo', 'plan_desempleo.Id', '=', 'poliza_desempleo.Plan')
            ->leftJoin('producto as producto_desempleo', 'producto_desempleo.Id', '=', 'plan_desempleo.Producto')
            ->select(
                DB::raw("'Desempleo' as TipoCartera"),
                'poliza_desempleo_cartera.PrimerNombre',
                'poliza_desempleo_cartera.SegundoNombre',
                'poliza_desempleo_cartera.PrimerApellido',
                'poliza_desempleo_cartera.SegundoApellido',
                'poliza_desempleo_cartera.ApellidoCasada',
                'poliza_desempleo_cartera.NombreSociedad',
                'poliza_desempleo_cartera.Dui',
                'poliza_desempleo_cartera.Nit',
                'poliza_desempleo_cartera.Pasaporte',
                DB::raw("NULL as CarnetResidencia"),
                'poliza_desempleo_cartera.Nacionalidad',
                'poliza_desempleo_cartera.FechaNacimiento',
                'poliza_desempleo_cartera.NumeroReferencia',
                'poliza_desempleo_cartera.MontoOtorgado',
                'poliza_desempleo_cartera.SaldoCapital',
                DB::raw("NULL as SumaAsegurada"),
                'poliza_desempleo_cartera.Intereses',
                'poliza_desempleo_cartera.InteresesMoratorios',
                'poliza_desempleo_cartera.InteresesCovid',
                'poliza_desempleo_cartera.FechaOtorgamiento',
                'poliza_desempleo_cartera.FechaVencimiento',
                'poliza_desempleo_cartera.Axo',
                'poliza_desempleo_cartera.Mes',
                'poliza_desempleo_cartera.Tasa as TarifaMes',
                'poliza_desempleo.NumeroPoliza',
                'aseguradora.Nombre as AseguradoraNombre',
                DB::raw("TRIM(CONCAT(COALESCE(producto_desempleo.Nombre, ''), CASE WHEN producto_desempleo.Nombre IS NOT NULL AND plan_desempleo.Nombre IS NOT NULL THEN ' / ' ELSE '' END, COALESCE(plan_desempleo.Nombre, ''))) as ProductoPlan"),
                'cliente.Nombre as ContratanteNombre',
                DB::raw("NULL as LineaDescripcion"),
                DB::raw("(COALESCE(poliza_desempleo_cartera.TotalCredito, 0) * COALESCE(poliza_desempleo_cartera.Tasa, 0)) as PrimaMes"),
                DB::raw("NULL as PorcentajeExtraprima"),
                DB::raw("NULL as ExtraPrimaMes")
            )
            ->get()
            ->map(function ($item) {
                $item->TipoCartera = 'Desempleo';
                $nombre = trim(($item->PrimerNombre ?? '') . ' ' . ($item->SegundoNombre ?? '') . ' ' .
                    ($item->PrimerApellido ?? '') . ' ' . ($item->SegundoApellido ?? '') . ' ' .
                    ($item->ApellidoCasada ?? ''));
                $item->NombreCompleto = $nombre ?: ($item->NombreSociedad ?? '');
                return $item;
            });

        $resultados = $deudaCartera
            ->concat($residenciaCartera)
            ->concat($vidaCartera)
            ->concat($desempleoCartera)
            ->map(function ($item) {
                $mes = isset($item->Mes) ? (int) $item->Mes : 0;
                $axo = isset($item->Axo) ? (string) $item->Axo : '';
                $item->PeriodoRegistro = ($mes > 0 && $axo !== '')
                    ? str_pad((string) $mes, 2, '0', STR_PAD_LEFT) . '/' . $axo
                    : '-';

                return $item;
            })
            ->values();

        $totales = [
            'MontoOtorgado' => round($resultados->sum(function ($item) {
                return (float) ($item->MontoOtorgado ?? 0);
            }), 2),
            'SumaAsegurada' => round($resultados->sum(function ($item) {
                return (float) ($item->SumaAsegurada ?? 0);
            }), 2),
            'SaldoCapital' => round($resultados->sum(function ($item) {
                return (float) ($item->SaldoCapital ?? 0);
            }), 2),
            'Intereses' => round($resultados->sum(function ($item) {
                return (float) ($item->Intereses ?? 0);
            }), 2),
            'InteresesMoratorios' => round($resultados->sum(function ($item) {
                return (float) ($item->InteresesMoratorios ?? 0);
            }), 2),
            'InteresesCovid' => round($resultados->sum(function ($item) {
                return (float) ($item->InteresesCovid ?? 0);
            }), 2),
            'PrimaMes' => round($resultados->sum(function ($item) {
                return (float) ($item->PrimaMes ?? 0);
            }), 2),
            'ExtraPrimaMes' => round($resultados->sum(function ($item) {
                return (float) ($item->ExtraPrimaMes ?? 0);
            }), 2),
        ];

        return [
            'resultados' => $resultados,
            'totales' => $totales,
        ];
    }

    private function aplicarFiltroBusqueda($query, string $tipoBusqueda, string $busqueda, array $columnasDocumento, array $expresionesNombre = []): void
    {
        $busquedaLike = '%' . $busqueda . '%';

        $query->where(function ($subQuery) use ($tipoBusqueda, $busquedaLike, $columnasDocumento, $expresionesNombre) {
            if (in_array($tipoBusqueda, ['dui', 'nit', 'pasaporte'], true)) {
                if (isset($columnasDocumento[$tipoBusqueda])) {
                    $subQuery->where($columnasDocumento[$tipoBusqueda], 'like', $busquedaLike);
                } else {
                    $subQuery->whereRaw('1 = 0');
                }

                return;
            }

            if ($tipoBusqueda === 'documento') {
                $columnas = collect(['dui', 'nit', 'pasaporte'])
                    ->map(function ($tipo) use ($columnasDocumento) {
                        return $columnasDocumento[$tipo] ?? null;
                    })
                    ->filter()
                    ->values();

                if ($columnas->isEmpty()) {
                    $subQuery->whereRaw('1 = 0');
                    return;
                }

                foreach ($columnas as $indice => $columna) {
                    if ($indice === 0) {
                        $subQuery->where($columna, 'like', $busquedaLike);
                    } else {
                        $subQuery->orWhere($columna, 'like', $busquedaLike);
                    }
                }

                return;
            }

            if ($tipoBusqueda === 'nombre') {
                if (empty($expresionesNombre)) {
                    $subQuery->whereRaw('1 = 0');
                    return;
                }

                foreach ($expresionesNombre as $indice => $expresion) {
                    if ($indice === 0) {
                        $subQuery->whereRaw($expresion . ' like ?', [$busquedaLike]);
                    } else {
                        $subQuery->orWhereRaw($expresion . ' like ?', [$busquedaLike]);
                    }
                }
            }
        });
    }
}
