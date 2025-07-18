<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\polizas\Deuda;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteDeclarativasController extends Controller
{
    public function polizas_declarativas(Request $request)
    {
        // Obtener mes y año desde el request, o usar los actuales si no se envían
        $mes = $request->input('mes') ?? Carbon::now()->month;
        $anio = $request->input('anio') ?? Carbon::now()->year;

        // Crear una fecha con el mes y año proporcionados
        $inicioMes = Carbon::createFromDate($anio, $mes, 1)->startOfMonth()->toDateString();
        $finMes = Carbon::createFromDate($anio, $mes, 1)->endOfMonth()->toDateString();

        // Filtrar las pólizas cuya vigencia incluya el mes y año indicados
        $polizas_deuda = Deuda::where('VigenciaDesde', '<=', $finMes)
            ->where('VigenciaHasta', '>=', $inicioMes)
            ->get();

        return view('polizas.reporte.declarativas', compact('polizas_deuda'));
    }
}
