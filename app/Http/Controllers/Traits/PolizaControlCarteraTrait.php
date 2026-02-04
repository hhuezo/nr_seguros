<?php

namespace App\Http\Controllers\Traits;

use App\Models\polizas\Desempleo;
use App\Models\polizas\Deuda;
use App\Models\polizas\Residencia;
use App\Models\polizas\Vida;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;





trait PolizaControlCarteraTrait
{
    /* ============================================================
     |  MÉTODO PRINCIPAL (orquestador)
     ============================================================ */
    protected function buildControlCartera(int $anio, int $mes, int $tipoPoliza)
    {
        // 1️⃣ Inserciones automáticas
        $this->insertarControlesBase($anio, $mes, $tipoPoliza);

        // 2️⃣ Obtener registros
        $registros = $this->obtenerRegistros($anio, $mes, $tipoPoliza);

        // 3️⃣ Aplicar color de estado
        $this->asignarEstados($registros);

        // 4️⃣ Prima del mes anterior
        $this->aplicarPrimaMesAnterior($registros, $anio, $mes);

        // 5️⃣ Cálculos financieros
        $this->calcularPrimas($registros, $anio, $mes);

        return $registros;
    }

    /* ============================================================
     |  INSERCIÓN DE REGISTROS BASE
     ============================================================ */
    protected function insertarControlesBase($anio, $mes, $tipoPoliza)
    {
        if ($tipoPoliza == 1) {
            $this->insertarPorModelo(Deuda::pluck('Id'), 'PolizaDeudaId', $anio, $mes);
            $this->insertarPorModelo(Vida::pluck('Id'), 'PolizaVidaId', $anio, $mes);
            $this->insertarPorModelo(Desempleo::pluck('Id'), 'PolizaDesempleoId', $anio, $mes);
        } else {
            $this->insertarPorModelo(Residencia::pluck('Id'), 'PolizaResidenciaId', $anio, $mes);
        }
    }

    protected function insertarPorModelo($ids, $campo, $anio, $mes)
    {
        $rows = $ids->map(fn($id) => [
            $campo => $id,
            'Axo'   => $anio,
            'Mes'   => $mes,
        ])->toArray();

        DB::table('poliza_declarativa_control')->insertOrIgnore($rows);
    }

    /* ============================================================
     |  CONSULTA PRINCIPAL (TU QUERY EXISTENTE VA AQUÍ)
     ============================================================ */
    protected function obtenerRegistros($anio, $mes, $tipoPoliza)
    {

        $usuariosMap = User::pluck('name', 'id');

        /*
     |--------------------------------------------------------------------------
     | PERSONAS: DEUDA + VIDA + DESEMPLEO
     |--------------------------------------------------------------------------
     */
        if ($tipoPoliza == 1) {

            /* =========================
         | DEUDA
         ========================= */


            $deuda = DB::table('poliza_declarativa_control')
                ->where('poliza_declarativa_control.Axo', $anio)
                ->where('poliza_declarativa_control.Mes', $mes)
                ->join('poliza_deuda', 'poliza_deuda.Id', '=', 'poliza_declarativa_control.PolizaDeudaId')
                ->join('poliza_deuda_tipo_cartera', 'poliza_deuda_tipo_cartera.PolizaDeuda', '=', 'poliza_deuda.Id')
                ->leftJoin('poliza_deuda_detalle', function ($join) use ($anio, $mes) {
                    $join->on('poliza_deuda_detalle.Deuda', '=', 'poliza_deuda.Id')
                        ->where('poliza_deuda_detalle.Axo', $anio)
                        ->where('poliza_deuda_detalle.Mes', $mes)
                        ->where('poliza_deuda_detalle.Activo', 1);
                })
                ->join('cliente', 'cliente.Id', '=', 'poliza_deuda.Asegurado')
                ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_deuda.Aseguradora')
                ->join('plan', 'plan.Id', '=', 'poliza_deuda.Plan')
                ->join('producto', 'producto.Id', '=', 'plan.Producto')
                ->leftJoin(
                    'poliza_declarativa_reproceso',
                    'poliza_declarativa_reproceso.Id',
                    '=',
                    'poliza_declarativa_control.ReprocesoNRId'
                )
                ->select(
                    'poliza_declarativa_control.*',
                    DB::raw("'DEUDA' AS TipoPoliza"),

                    'poliza_deuda.NumeroPoliza',
                    'poliza_deuda.VigenciaDesde',
                    'poliza_deuda.VigenciaHasta',
                    'poliza_deuda.Descuento',
                    'poliza_deuda_tipo_cartera.TipoCartera',

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
                    'poliza_deuda_detalle.NumeroRecibo',
                    'poliza_deuda_detalle.Axo',
                    'poliza_deuda_detalle.FechaInicio',
                    'poliza_deuda_detalle.Usuario',
                    'poliza_deuda_detalle.UsuariosReportados',

                    'cliente.Nombre as ClienteNombre',
                    'cliente.Nit as ClienteNit',

                    'plan.Nombre as PlanNombre',
                    'producto.Nombre as ProductoNombre',
                    'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',
                    'aseguradora.Abreviatura'
                )
                ->get();

            $deuda = $deuda->map(function ($item) use ($usuariosMap) {
                $item->Usuario = $usuariosMap[$item->Usuario] ?? null;
                return $item;
            });


            /* =========================
            | VIDA
            ========================= */
            $vida = DB::table('poliza_declarativa_control')
                ->where('poliza_declarativa_control.Axo', $anio)
                ->where('poliza_declarativa_control.Mes', $mes)
                ->join('poliza_vida', 'poliza_vida.Id', '=', 'poliza_declarativa_control.PolizaVidaId')
                ->leftJoin('poliza_vida_detalle', function ($join) use ($anio, $mes) {
                    $join->on('poliza_vida_detalle.PolizaVida', '=', 'poliza_vida.Id')
                        ->where('poliza_vida_detalle.Axo', $anio)
                        ->where('poliza_vida_detalle.Mes', $mes)
                        ->where('poliza_vida_detalle.Activo', 1);
                })
                ->join('cliente', 'cliente.Id', '=', 'poliza_vida.Asegurado')
                ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_vida.Aseguradora')
                ->join('plan', 'plan.Id', '=', 'poliza_vida.Plan')
                ->join('producto', 'producto.Id', '=', 'plan.Producto')
                ->leftJoin(
                    'poliza_declarativa_reproceso',
                    'poliza_declarativa_reproceso.Id',
                    '=',
                    'poliza_declarativa_control.ReprocesoNRId'
                )
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
                    'poliza_vida_detalle.NumeroRecibo',
                    'poliza_vida_detalle.Axo',
                    'poliza_vida_detalle.FechaInicio',
                    'poliza_vida_detalle.Usuario',
                    'poliza_vida_detalle.UsuariosReportados',

                    'cliente.Nombre as ClienteNombre',
                    'cliente.Nit as ClienteNit',

                    'plan.Nombre as PlanNombre',
                    'producto.Nombre as ProductoNombre',
                    'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',
                    'aseguradora.Abreviatura'
                )
                ->get();


            $vida = $vida->map(function ($item) use ($usuariosMap) {
                $item->Usuario = $usuariosMap[$item->Usuario] ?? null;
                return $item;
            });

            /* =========================
         | DESEMPLEO
         ========================= */
            $desempleo = DB::table('poliza_declarativa_control')
                ->where('poliza_declarativa_control.Axo', $anio)
                ->where('poliza_declarativa_control.Mes', $mes)
                ->join('poliza_desempleo', 'poliza_desempleo.Id', '=', 'poliza_declarativa_control.PolizaDesempleoId')
                ->leftJoin('poliza_desempleo_detalle', function ($join) use ($anio, $mes) {
                    $join->on('poliza_desempleo_detalle.Desempleo', '=', 'poliza_desempleo.Id')
                        ->where('poliza_desempleo_detalle.Axo', $anio)
                        ->where('poliza_desempleo_detalle.Mes', $mes)
                        ->where('poliza_desempleo_detalle.Activo', 1);
                })
                ->join('cliente', 'cliente.Id', '=', 'poliza_desempleo.Asegurado')
                ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_desempleo.Aseguradora')
                ->join('plan', 'plan.Id', '=', 'poliza_desempleo.Plan')
                ->join('producto', 'producto.Id', '=', 'plan.Producto')
                ->leftJoin(
                    'poliza_declarativa_reproceso',
                    'poliza_declarativa_reproceso.Id',
                    '=',
                    'poliza_declarativa_control.ReprocesoNRId'
                )
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
                    'poliza_desempleo_detalle.NumeroRecibo',
                    'poliza_desempleo_detalle.Axo',
                    'poliza_desempleo_detalle.FechaInicio',
                    'poliza_desempleo_detalle.Usuario',
                    'poliza_desempleo_detalle.UsuariosReportados',

                    'cliente.Nombre as ClienteNombre',
                    'cliente.Nit as ClienteNit',

                    'plan.Nombre as PlanNombre',
                    'producto.Nombre as ProductoNombre',
                    'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',
                    'aseguradora.Abreviatura'
                )
                ->get();

            $desempleo = $desempleo->map(function ($item) use ($usuariosMap) {
                $item->Usuario = $usuariosMap[$item->Usuario] ?? null;
                return $item;
            });

            return $deuda
                ->concat($vida)
                ->concat($desempleo)
                ->sortBy('ClienteNombre')
                ->values();
        }

        /*
     |--------------------------------------------------------------------------
     | RESIDENCIA
     |--------------------------------------------------------------------------
     */
        /*return DB::table('poliza_declarativa_control')
            ->where('poliza_declarativa_control.Axo', $anio)
            ->where('poliza_declarativa_control.Mes', $mes)
            ->join('poliza_residencia', 'poliza_residencia.Id', '=', 'poliza_declarativa_control.PolizaResidenciaId')
            ->leftJoin('poliza_residencia_detalle', function ($join) use ($anio, $mes) {
                $join->on('poliza_residencia_detalle.Residencia', '=', 'poliza_residencia.Id')
                    ->where('poliza_residencia_detalle.Axo', $anio)
                    ->where('poliza_residencia_detalle.Mes', $mes);
            })
            ->join('cliente', 'cliente.Id', '=', 'poliza_residencia.Asegurado')
            ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_residencia.Aseguradora')
            ->join('plan', 'plan.Id', '=', 'poliza_residencia.Plan')
            ->join('producto', 'producto.Id', '=', 'plan.Producto')
            ->leftJoin(
                'poliza_declarativa_reproceso',
                'poliza_declarativa_reproceso.Id',
                '=',
                'poliza_declarativa_control.ReprocesoNRId'
            )
            ->select(
                'poliza_declarativa_control.*',
                DB::raw("'RESIDENCIA' AS TipoPoliza"),

                'poliza_residencia.NumeroPoliza',
                'poliza_residencia.VigenciaDesde',
                'poliza_residencia.VigenciaHasta',

                'poliza_residencia_detalle.MontoCartera',
                'poliza_residencia_detalle.PrimaCalculada',
                'poliza_residencia_detalle.ExtraPrima',
                'poliza_residencia_detalle.PrimaDescontada',
                'poliza_residencia_detalle.TasaComision',
                'poliza_residencia_detalle.Comision',
                'poliza_residencia_detalle.Retencion',
                'poliza_residencia_detalle.IvaSobreComision',
                'poliza_residencia_detalle.APagar',
                'poliza_residencia_detalle.NumeroRecibo',
                'poliza_residencia_detalle.Axo',
                'poliza_residencia_detalle.FechaInicio',
                'poliza_residencia_detalle.Usuario',
                'poliza_residencia_detalle.UsuariosReportados',

                'cliente.Nombre as ClienteNombre',
                'cliente.Nit as ClienteNit',

                'plan.Nombre as PlanNombre',
                'producto.Nombre as ProductoNombre',
                'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',
                'aseguradora.Abreviatura'
            )
            ->groupBy('poliza_declarativa_control.Id')
            ->orderBy('cliente.Nombre')
            ->get();*/


        $residencia = DB::table('poliza_declarativa_control')
            ->where('poliza_declarativa_control.Axo', $anio)
            ->where('poliza_declarativa_control.Mes', $mes)
            ->join('poliza_residencia', 'poliza_residencia.Id', '=', 'poliza_declarativa_control.PolizaResidenciaId')
            ->leftJoin('poliza_residencia_detalle', function ($join) use ($anio, $mes) {
                $join->on('poliza_residencia_detalle.Residencia', '=', 'poliza_residencia.Id')
                    ->where('poliza_residencia_detalle.Axo', $anio)
                    ->where('poliza_residencia_detalle.Mes', $mes)
                     ->where('poliza_residencia_detalle.Activo', 1);
            })
            ->join('cliente', 'cliente.Id', '=', 'poliza_residencia.Asegurado')
            ->join('aseguradora', 'aseguradora.Id', '=', 'poliza_residencia.Aseguradora')
            ->join('plan', 'plan.Id', '=', 'poliza_residencia.Plan')
            ->join('producto', 'producto.Id', '=', 'plan.Producto')
            ->leftJoin(
                'poliza_declarativa_reproceso',
                'poliza_declarativa_reproceso.Id',
                '=',
                'poliza_declarativa_control.ReprocesoNRId'
            )
            ->select(
                'poliza_declarativa_control.*',
                DB::raw("'RESIDENCIA' AS TipoPoliza"),

                'poliza_residencia.NumeroPoliza',
                'poliza_residencia.VigenciaDesde',
                'poliza_residencia.VigenciaHasta',

                'poliza_residencia_detalle.MontoCartera',
                'poliza_residencia_detalle.PrimaCalculada',
                'poliza_residencia_detalle.ExtraPrima',
                'poliza_residencia_detalle.PrimaDescontada',
                'poliza_residencia_detalle.TasaComision',
                'poliza_residencia_detalle.Comision',
                'poliza_residencia_detalle.Retencion',
                'poliza_residencia_detalle.IvaSobreComision',
                'poliza_residencia_detalle.APagar',
                'poliza_residencia_detalle.NumeroRecibo',
                'poliza_residencia_detalle.Axo',
                'poliza_residencia_detalle.FechaInicio',
                'poliza_residencia_detalle.Usuario',
                'poliza_residencia_detalle.UsuariosReportados',

                'cliente.Nombre as ClienteNombre',
                'cliente.Nit as ClienteNit',

                'plan.Nombre as PlanNombre',
                'producto.Nombre as ProductoNombre',
                'poliza_declarativa_reproceso.Nombre as ReprocesoNombre',
                'aseguradora.Abreviatura',
                'poliza_residencia_detalle.Residencia',
            )
            ->get();


        $residencia = $residencia->map(function ($item) use ($usuariosMap) {
            $item->Usuario = $usuariosMap[$item->Usuario] ?? null;
            return $item;
        });

        return $residencia;
    }


    /* ============================================================
     |  ESTADOS / COLORES
     ============================================================ */
    protected function asignarEstados(&$registros)
    {
        foreach ($registros as $item) {

            if (!empty($item->FechaAplicacion)) {
                $item->Color = 'success';
            } elseif (!empty($item->FechaRecepcionPago) || !empty($item->FechaReporteACia)) {
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
                $item->Color = 'info';
            } elseif (
                !empty($item->FechaRecepcionArchivo) ||
                !empty($item->FechaEnvioCia) ||
                !empty($item->TrabajoEfectuadoDiaHabil)
            ) {
                $item->Color = 'orange';
            } else {
                $item->Color = 'secondary';
            }
        }
    }

    /* ============================================================
     |  PRIMA MES ANTERIOR
     ============================================================ */
    protected function aplicarPrimaMesAnterior(&$registros, $anio, $mes)
    {
        $mesAnterior  = $mes - 1;
        $anioAnterior = $anio;

        if ($mesAnterior == 0) {
            $mesAnterior = 12;
            $anioAnterior--;
        }

        foreach ($registros as $item) {

            if ($item->Color !== 'secondary') {
                continue;
            }

            if ($item->TipoPoliza === 'DEUDA') {
                $item->PrimaCalculada = DB::table('poliza_deuda_detalle')
                    ->where('Deuda', $item->PolizaDeudaId)
                    ->where('Mes', $mesAnterior)
                    ->where('Axo', $anioAnterior)
                    ->value('PrimaCalculada') ?? $item->PrimaCalculada;
            }

            if ($item->TipoPoliza === 'VIDA') {
                $item->PrimaCalculada = DB::table('poliza_vida_detalle')
                    ->where('PolizaVida', $item->PolizaVidaId)
                    ->where('Mes', $mesAnterior)
                    ->where('Axo', $anioAnterior)
                    ->value('PrimaCalculada') ?? $item->PrimaCalculada;
            }

            if ($item->TipoPoliza === 'DESEMPLEO') {
                $item->PrimaCalculada = DB::table('poliza_desempleo_detalle')
                    ->where('Desempleo', $item->PolizaDesempleoId)
                    ->where('Mes', $mesAnterior)
                    ->where('Axo', $anioAnterior)
                    ->value('PrimaCalculada') ?? $item->PrimaCalculada;
            }

            if ($item->TipoPoliza === 'RESIDENCIA') {
                $item->PrimaCalculada = DB::table('poliza_residencia_detalle')
                    ->where('Residencia', $item->PolizaResidenciaId)
                    ->where('Mes', $mesAnterior)
                    ->where('Axo', $anioAnterior)
                    ->value('PrimaCalculada') ?? $item->PrimaCalculada;
            }
        }
    }

    /* ============================================================
     |  CÁLCULOS FINANCIEROS
     ============================================================ */
    protected function calcularPrimas(&$registros, $anio, $mes)
    {
        foreach ($registros as $item) {

            if (!empty($item->MontoCartera)) {
                continue;
            }

            switch ($item->TipoPoliza) {
                case 'DEUDA':
                    $this->calcularDeuda($item, $anio, $mes);
                    break;

                case 'VIDA':
                    $this->calcularVida($item, $anio, $mes);
                    break;

                case 'DESEMPLEO':
                    $this->calcularDesempleo($item, $anio, $mes);
                    break;

                case 'RESIDENCIA':
                    $this->calcularResidencia($item, $anio, $mes);
                    break;
            }
        }
    }

    /* ============================================================
     |  MÉTODOS DE CÁLCULO POR TIPO
     ============================================================ */

    protected function calcularDeuda(&$item, $anio, $mes) {}
    protected function calcularVida(&$item, $anio, $mes) {}
    protected function calcularDesempleo(&$item, $anio, $mes) {}
    protected function calcularResidencia(&$item, $anio, $mes) {}
}
