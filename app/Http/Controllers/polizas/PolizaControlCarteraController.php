<?php

namespace App\Http\Controllers\polizas;

use App\Exports\ControlCarteraExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\PolizaControlCarteraTrait;
use App\Models\catalogo\PolizaDeclarativaReproceso;

use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeclarativaControl;
use App\Models\suscripcion\FechasFeriadas;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class PolizaControlCarteraController extends Controller
{

    use PolizaControlCarteraTrait;

    public function index(Request $request)
    {
        // ===============================
        // 1️⃣ Parámetros de entrada
        // ===============================
        $mes = $request->Mes ?? Carbon::now()->subMonthNoOverflow()->month;
        $anio = $request->Anio ?? Carbon::now()->subMonthNoOverflow()->year;
        $tipoPoliza = $request->TipoPoliza ?? 1;

        // ===============================
        // 2️⃣ Lógica principal (TRAIT)
        // ===============================
        $registro_control = $this->buildControlCartera(
            (int) $anio,
            (int) $mes,
            (int) $tipoPoliza
        );

        // ===============================
        // 3️⃣ Catálogos / datos auxiliares
        // ===============================
        $reprocesos = PolizaDeclarativaReproceso::where('Activo', 1)->get();


        $meses = [
            1  => 'Enero',
            2  => 'Febrero',
            3  => 'Marzo',
            4  => 'Abril',
            5  => 'Mayo',
            6  => 'Junio',
            7  => 'Julio',
            8  => 'Agosto',
            9  => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];


        // ===============================
        // 4️⃣ Retornar vista
        // ===============================
        return view(
            'polizas.control_cartera.index',
            compact(
                'registro_control',
                'anio',
                'mes',
                'meses',
                'reprocesos'
            )
        );
    }

    public function update(Request $request, $id)
    {
        try {
            $control_cartera = PolizaDeclarativaControl::findOrFail($id);

            // 👉 Asignación manual correcta
            $control_cartera->FechaRecepcionArchivo   = $request->FechaRecepcionArchivo;
            $control_cartera->FechaEnvioCia           = $request->FechaEnvioCia;
            $control_cartera->TrabajoEfectuadoDiaHabil = $request->TrabajoEfectuadoDiaHabil;
            $control_cartera->HoraTarea               = $request->HoraTarea;
            $control_cartera->FlujoAsignado           = $request->FlujoAsignado;
            $control_cartera->FechaEnvioCliente       = $request->FechaEnvioCliente;
            $control_cartera->AnexoDeclaracion        = $request->AnexoDeclaracion;
            $control_cartera->ReprocesoNRId           = $request->ReprocesoNRId;
            $control_cartera->FechaEnvioCorreccion    = $request->FechaEnvioCorreccion;
            $control_cartera->FechaSeguimientoCobros  = $request->FechaSeguimientoCobros;
            $control_cartera->FechaRecepcionPago      = $request->FechaRecepcionPago;
            $control_cartera->FechaReporteACia        = $request->FechaReporteACia;
            $control_cartera->FechaAplicacion         = $request->FechaAplicacion;

            $control_cartera->save();

            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado correctamente',
                'data' => $control_cartera
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el registro',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function exportar_excel(Request $request)
    {

        try {
            $mes = $request->Mes ?? Carbon::now()->subMonthNoOverflow()->month;
            $anio = $request->Anio ?? Carbon::now()->subMonthNoOverflow()->year;
            $tipoPoliza = $request->TipoPoliza ?? 1;

            // Obtener los registros usando el trait
            $registro_control = $this->buildControlCartera(
                (int) $anio,
                (int) $mes,
                (int) $tipoPoliza
            );

            $meses = [
                1  => 'Enero',
                2  => 'Febrero',
                3  => 'Marzo',
                4  => 'Abril',
                5  => 'Mayo',
                6  => 'Junio',
                7  => 'Julio',
                8  => 'Agosto',
                9  => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre',
            ];

            $tipoPolizaNombre = $tipoPoliza == 1 ? 'Personas' : 'Residencia';
            $mesNombre = $meses[$mes] ?? '';

            $nombreArchivo = "Control_Cartera_{$tipoPolizaNombre}_{$mesNombre}_{$anio}.xlsx";

            return Excel::download(new ControlCarteraExport($registro_control), $nombreArchivo);
        } catch (\Exception $e) {
            \Log::error('Error al exportar control cartera: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar el archivo: ' . $e->getMessage());
        }
    }
}
