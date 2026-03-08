<?php

namespace App\Http\Controllers;

use App\Models\polizas\PolizaDeudaCartera;
use App\Models\polizas\PolizaResidenciaCartera;
use App\Models\polizas\VidaCartera;
use App\Models\polizas\DesempleoCartera;
use App\Models\polizas\CarteraMensual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultaClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('consulta.cliente.index');
    }

    public function buscar(Request $request)
    {
        $busqueda = trim($request->get('busqueda', ''));

        if (empty($busqueda)) {
            return view('consulta.cliente.index', [
                'resultados' => collect(),
                'busqueda' => '',
                'mensaje' => 'Por favor ingrese un DUI, NIT o Pasaporte para buscar'
            ]);
        }

        $resultados = collect();

        // Obtener el último mes y año de PolizaDeudaCartera
        $ultimoDeuda = PolizaDeudaCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        // Buscar en PolizaDeudaCartera - solo del último mes
        $deudaCartera = PolizaDeudaCartera::where(function($query) use ($busqueda) {
            $query->where('poliza_deuda_cartera.Dui', 'like', '%' . $busqueda . '%')
                  ->orWhere('poliza_deuda_cartera.Pasaporte', 'like', '%' . $busqueda . '%');
        });

        if ($ultimoDeuda) {
            $deudaCartera->where('poliza_deuda_cartera.Axo', $ultimoDeuda->Axo)
                         ->where('poliza_deuda_cartera.Mes', $ultimoDeuda->Mes);
        }

        $deudaCartera = $deudaCartera
        ->leftJoin('poliza_deuda', 'poliza_deuda.Id', '=', 'poliza_deuda_cartera.PolizaDeuda')
        ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_deuda.Aseguradora')
        ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_deuda.Asegurado')
        ->leftJoin('saldos_montos', 'saldos_montos.Id', '=', 'poliza_deuda_cartera.LineaCredito')
        ->select(
            DB::raw("'Deuda' as TipoCartera"),
            'poliza_deuda_cartera.PrimerNombre',
            'poliza_deuda_cartera.SegundoNombre',
            'poliza_deuda_cartera.PrimerApellido',
            'poliza_deuda_cartera.SegundoApellido',
            'poliza_deuda_cartera.ApellidoCasada',
            'poliza_deuda_cartera.NombreSociedad',
            'poliza_deuda_cartera.Dui',
            // 'poliza_deuda_cartera.Nit',
            'poliza_deuda_cartera.Pasaporte',
            'poliza_deuda_cartera.Nacionalidad',
            'poliza_deuda_cartera.FechaNacimiento',
            'poliza_deuda_cartera.NumeroReferencia',
            'poliza_deuda_cartera.MontoOtorgado',
            'poliza_deuda_cartera.SaldoCapital',
            'poliza_deuda_cartera.Intereses',
            'poliza_deuda_cartera.InteresesMoratorios',
            'poliza_deuda_cartera.InteresesCovid',
            'poliza_deuda_cartera.FechaOtorgamiento',
            'poliza_deuda_cartera.FechaVencimiento',
            'poliza_deuda_cartera.Axo',
            'poliza_deuda_cartera.Mes',
            'poliza_deuda.NumeroPoliza',
            'aseguradora.Nombre as AseguradoraNombre',
            'cliente.Nombre as ContratanteNombre',
            'saldos_montos.Descripcion as LineaDescripcion',
            DB::raw("COALESCE(poliza_deuda_cartera.PorcentajeExtraprima, NULL) as PorcentajeExtraprima")
        )
        ->get()
        ->map(function($item) {
            $item->TipoCartera = 'Deuda';
            // Construir nombre completo
            $nombre = trim(($item->PrimerNombre ?? '') . ' ' . ($item->SegundoNombre ?? '') . ' ' .
                         ($item->PrimerApellido ?? '') . ' ' . ($item->SegundoApellido ?? '') . ' ' .
                         ($item->ApellidoCasada ?? ''));
            $item->NombreCompleto = $nombre ?: ($item->NombreSociedad ?? '');
            return $item;
        });

        // Obtener el último mes y año de PolizaResidenciaCartera
        $ultimoResidencia = PolizaResidenciaCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        // Buscar en PolizaResidenciaCartera - solo del último mes
        $residenciaCartera = PolizaResidenciaCartera::where(function($query) use ($busqueda) {
            $query->where('poliza_residencia_cartera.Dui', 'like', '%' . $busqueda . '%')
                  ->orWhere('poliza_residencia_cartera.Nit', 'like', '%' . $busqueda . '%')
                  ->orWhere('poliza_residencia_cartera.Pasaporte', 'like', '%' . $busqueda . '%')
                  ->orWhere('poliza_residencia_cartera.CarnetResidencia', 'like', '%' . $busqueda . '%');
        });

        if ($ultimoResidencia) {
            $residenciaCartera->where('poliza_residencia_cartera.Axo', $ultimoResidencia->Axo)
                               ->where('poliza_residencia_cartera.Mes', $ultimoResidencia->Mes);
        }

        $residenciaCartera = $residenciaCartera
        ->leftJoin('poliza_residencia', 'poliza_residencia.Id', '=', 'poliza_residencia_cartera.PolizaResidencia')
        ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_residencia.Aseguradora')
        ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_residencia.Asegurado')
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
            'poliza_residencia.NumeroPoliza',
            'aseguradora.Nombre as AseguradoraNombre',
            'cliente.Nombre as ContratanteNombre',
            DB::raw("NULL as LineaDescripcion"),
            DB::raw("NULL as PorcentajeExtraprima")
        )
        ->get()
        ->map(function($item) {
            $item->TipoCartera = 'Residencia';
            return $item;
        });

        // Obtener el último mes y año de VidaCartera
        $ultimoVida = VidaCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        // Buscar en VidaCartera - solo del último mes
        $vidaCartera = VidaCartera::where(function($query) use ($busqueda) {
            $query->where('poliza_vida_cartera.Dui', 'like', '%' . $busqueda . '%')
                  ->orWhere('poliza_vida_cartera.Nit', 'like', '%' . $busqueda . '%')
                  ->orWhere('poliza_vida_cartera.Pasaporte', 'like', '%' . $busqueda . '%');
        });

        if ($ultimoVida) {
            $vidaCartera->where('poliza_vida_cartera.Axo', $ultimoVida->Axo)
                        ->where('poliza_vida_cartera.Mes', $ultimoVida->Mes);
        }

        $vidaCartera = $vidaCartera
        ->leftJoin('poliza_vida', 'poliza_vida.Id', '=', 'poliza_vida_cartera.PolizaVida')
        ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_vida.Aseguradora')
        ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_vida.Asegurado')
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
            'poliza_vida.NumeroPoliza',
            'aseguradora.Nombre as AseguradoraNombre',
            'cliente.Nombre as ContratanteNombre',
            DB::raw("NULL as LineaDescripcion"),
            DB::raw("NULL as PorcentajeExtraprima")
        )
        ->get()
        ->map(function($item) {
            $item->TipoCartera = 'Vida';
            $nombre = trim(($item->PrimerNombre ?? '') . ' ' . ($item->SegundoNombre ?? '') . ' ' .
                         ($item->PrimerApellido ?? '') . ' ' . ($item->SegundoApellido ?? '') . ' ' .
                         ($item->ApellidoCasada ?? ''));
            $item->NombreCompleto = $nombre ?: '';
            return $item;
        });

        // Obtener el último mes y año de DesempleoCartera
        $ultimoDesempleo = DesempleoCartera::select('Axo', 'Mes')
            ->whereNotNull('Axo')
            ->whereNotNull('Mes')
            ->orderBy('Axo', 'desc')
            ->orderBy('Mes', 'desc')
            ->first();

        // Buscar en DesempleoCartera - solo del último mes
        $desempleoCartera = DesempleoCartera::where(function($query) use ($busqueda) {
            $query->where('poliza_desempleo_cartera.Dui', 'like', '%' . $busqueda . '%')
                  ->orWhere('poliza_desempleo_cartera.Pasaporte', 'like', '%' . $busqueda . '%');
        });

        if ($ultimoDesempleo) {
            $desempleoCartera->where('poliza_desempleo_cartera.Axo', $ultimoDesempleo->Axo)
                              ->where('poliza_desempleo_cartera.Mes', $ultimoDesempleo->Mes);
        }

        $desempleoCartera = $desempleoCartera
        ->leftJoin('poliza_desempleo', 'poliza_desempleo.Id', '=', 'poliza_desempleo_cartera.PolizaDesempleo')
        ->leftJoin('aseguradora', 'aseguradora.Id', '=', 'poliza_desempleo.Aseguradora')
        ->leftJoin('cliente', 'cliente.Id', '=', 'poliza_desempleo.Asegurado')
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
            'poliza_desempleo_cartera.Nacionalidad',
            'poliza_desempleo_cartera.FechaNacimiento',
            'poliza_desempleo_cartera.NumeroReferencia',
            'poliza_desempleo_cartera.MontoOtorgado',
            'poliza_desempleo_cartera.SaldoCapital',
            'poliza_desempleo_cartera.Intereses',
            'poliza_desempleo_cartera.InteresesMoratorios',
            'poliza_desempleo_cartera.InteresesCovid',
            'poliza_desempleo_cartera.FechaOtorgamiento',
            'poliza_desempleo_cartera.FechaVencimiento',
            'poliza_desempleo_cartera.Axo',
            'poliza_desempleo_cartera.Mes',
            'poliza_desempleo.NumeroPoliza',
            'aseguradora.Nombre as AseguradoraNombre',
            'cliente.Nombre as ContratanteNombre',
            DB::raw("NULL as LineaDescripcion"),
            DB::raw("NULL as PorcentajeExtraprima")
        )
        ->get()
        ->map(function($item) {
            $item->TipoCartera = 'Desempleo';
            $nombre = trim(($item->PrimerNombre ?? '') . ' ' . ($item->SegundoNombre ?? '') . ' ' .
                         ($item->PrimerApellido ?? '') . ' ' . ($item->SegundoApellido ?? '') . ' ' .
                         ($item->ApellidoCasada ?? ''));
            $item->NombreCompleto = $nombre ?: ($item->NombreSociedad ?? '');
            return $item;
        });



        // Combinar todos los resultados
        $resultados = $deudaCartera
            ->concat($residenciaCartera)
            ->concat($vidaCartera)
            ->concat($desempleoCartera);

        return view('consulta.cliente.index', [
            'resultados' => $resultados,
            'busqueda' => $busqueda
        ]);
    }
}
