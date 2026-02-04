<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\PolizaControlCarteraTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ControlPrimasController extends Controller
{
    use PolizaControlCarteraTrait;

    public function index(Request $request)
    {
        $anioActual = Carbon::now()->year;
        $mesActual = Carbon::now()->month;



        // Obtener parámetros de la petición (por defecto mes anterior)
        $anio = $request->anio ?? Carbon::now()->subMonthNoOverflow()->year;
        $mes = $request->mes ?? Carbon::now()->subMonthNoOverflow()->month;

        // 2. Crear una instancia de Carbon con el mes y año seleccionados
        $fechaSeleccionada = Carbon::createFromDate($anio, $mes, 1);

        // 3. Calcular el mes y año anterior basándose en la selección
        $fechaPasada = $fechaSeleccionada->copy()->subMonthNoOverflow();

        $mesAnterior = $fechaPasada->month;
        $anioAnterior = $fechaPasada->year;


        $tipoPoliza = $request->TipoPoliza ?? 1;

        // Generar array de años desde 2025 hasta el año actual
        $anios = [];
        for ($i = 2025; $i <= $anioActual; $i++) {
            $anios[] = $i;
        }

        // Array de meses en español
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];


        // Obtener registros usando el trait (igual que PolizaControlCarteraController)
        $registro_control = $this->buildControlCartera(
            (int) $anio,
            (int) $mes,
            (int) $tipoPoliza
        );


        $registro_control_mes_anterior = $this->buildControlCartera(
            (int) $anioAnterior,
            (int) $mesAnterior,
            (int) $tipoPoliza
        );





        $prima_emitida_alterna = $registro_control->whereIn('TipoCartera', [16, 17])->sum(function ($item) {
            return (float)($item->PrimaCalculada ?? 0) + (float)($item->ExtraPrima ?? 0);
        });


        $prima_descontada_alterna = $registro_control->whereIn('TipoCartera', [16, 17])->sum(function ($item) {
            return (float)($item->PrimaDescontada ?? 0);
        });


        $prima_emitida_alterna_mes_anterior = $registro_control_mes_anterior->whereIn('TipoCartera', [16, 17])->sum(function ($item) {
            return (float)($item->PrimaCalculada ?? 0) + (float)($item->ExtraPrima ?? 0);
        });

        $prima_descontada_alterna_mes_anterior = $registro_control_mes_anterior->whereIn('TipoCartera', [16, 17])->sum(function ($item) {
            return (float)($item->PrimaDescontada ?? 0);
        });


        $primas_alternas = ["emitida" => $prima_emitida_alterna, "descontada" =>  $prima_descontada_alterna, "emitida_mes_anterior" => $prima_emitida_alterna_mes_anterior, "descontada_mes_anterior" =>  $prima_descontada_alterna_mes_anterior];


        // Calcular totales sumando los valores de los registros
        $totales = $this->calcularTotales($registro_control);
        $totalesPorColor = $this->calcularTotalesPorColor($registro_control);

        return view('control_primas', compact('anios', 'meses', 'anioActual', 'mesActual', 'anio', 'mes', 'totales', 'totalesPorColor', 'primas_alternas'));
    }

    private function calcularTotales($registros)
    {
        return [
            'prima_bruta' => $registros->sum(function ($item) {
                return (float)($item->PrimaCalculada ?? 0);
            }),
            'extra_prima' => $registros->sum(function ($item) {
                return (float)($item->ExtraPrima ?? 0);
            }),
            'prima_emitida' => $registros->sum(function ($item) {
                return (float)($item->PrimaCalculada ?? 0) + (float)($item->ExtraPrima ?? 0);
            }),
            'valor_descuento_rentabilidad' => $registros->sum(function ($item) {
                return (float)($item->ValorDescuentoRentabilidad ?? $item->Descuento ?? 0);
            }),
            'prima_descontada' => $registros->sum(function ($item) {
                return (float)($item->PrimaDescontada ?? 0);
            }),
            'comision_neta' => $registros->sum(function ($item) {
                return (float)($item->Comision ?? 0);
            }),
            'iva_13' => $registros->sum(function ($item) {
                return (float)($item->IvaSobreComision ?? $item->Iva ?? 0);
            }),
            'retencion_1' => $registros->sum(function ($item) {
                return (float)($item->Retencion ?? 0);
            }),
            'prima_liquida' => $registros->sum(function ($item) {
                return (float)($item->APagar ?? 0);
            }),
        ];
    }

    /**
     * Totales agrupados por color (success, warning, info, orange, secondary).
     * Los colores coinciden con las filas en control_cartera.
     */
    private function calcularTotalesPorColor($registros)
    {
        $colores = ['success', 'warning', 'info', 'orange', 'secondary'];
        $resultado = [];

        foreach ($colores as $color) {
            $filtrados = $registros->filter(fn($item) => ($item->Color ?? 'secondary') === $color);
            $resultado[$color] = [
                'cantidad' => $filtrados->count(),
                'prima_bruta' => $filtrados->sum(fn($i) => (float)($i->PrimaCalculada ?? 0)),
                'extra_prima' => $filtrados->sum(fn($i) => (float)($i->ExtraPrima ?? 0)),
                'prima_emitida' => $filtrados->sum(fn($i) => (float)($i->PrimaCalculada ?? 0) + (float)($i->ExtraPrima ?? 0)),
                'valor_descuento_rentabilidad' => $filtrados->sum(fn($i) => (float)($i->ValorDescuentoRentabilidad ?? $i->Descuento ?? 0)),
                'prima_descontada' => $filtrados->sum(fn($i) => (float)($i->PrimaDescontada ?? 0)),
                'comision_neta' => $filtrados->sum(fn($i) => (float)($i->Comision ?? 0)),
                'iva_13' => $filtrados->sum(fn($i) => (float)($i->IvaSobreComision ?? $i->Iva ?? 0)),
                'retencion_1' => $filtrados->sum(fn($i) => (float)($i->Retencion ?? 0)),
                'prima_liquida' => $filtrados->sum(fn($i) => (float)($i->APagar ?? 0)),
            ];
        }

        return $resultado;
    }
}
