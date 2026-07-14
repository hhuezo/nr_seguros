<?php

namespace App\Http\Controllers\ventas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoVenta;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\NrCartera;
use App\Models\catalogo\VentasCampoComparativo;
use App\Models\catalogo\VentasPlanComercial;
use Illuminate\Http\Request;

class VentasOfertaController extends Controller
{
    public function index()
    {
        return view('ventas.ofertas.index');
    }

    public function formulario()
    {
        $ejecutivos = Ejecutivo::where('Activo', 1)
            ->orderBy('Nombre')
            ->get();

        $tiposCarteraNr = NrCartera::where('Activo', 1)
            ->orderBy('Nombre')
            ->get();

        $ramos = NecesidadProteccion::where('Activo', 1)
            ->orderBy('Nombre')
            ->get();

        $aseguradoras = Aseguradora::where('Activo', 1)
            ->orderBy('Nombre')
            ->get();

        $estadosVenta = EstadoVenta::where('Activo', 1)
            ->orderBy('Nombre')
            ->get();

        $clientes = Cliente::query()
            ->select(
                'Id',
                'Nombre',
                'Dui',
                'Nit',
                'Pasaporte',
                'TelefonoCelular',
                'TelefonoCelular2',
                'TelefonoResidencia',
                'TelefonoOficina',
                'CorreoPrincipal',
                'CorreoSecundario'
            )
            ->where('Activo', 1)
            ->orderBy('Nombre')
            ->get();

        $camposComparativos = VentasCampoComparativo::where('Activo', 1)
            ->orderBy('NecesidadProteccion')
            ->orderBy('Orden')
            ->orderBy('Id')
            ->get()
            ->map(function ($campo) {
                return [
                    'id' => $campo->Id,
                    'ramo' => $campo->NecesidadProteccion,
                    'etiqueta' => $campo->Etiqueta,
                    'orden' => $campo->Orden,
                ];
            })
            ->values();

        $planesComerciales = VentasPlanComercial::with([
                'aseguradora',
                'producto',
                'plan',
                'valores.campoComparativo',
            ])
            ->where('Activo', 1)
            ->orderBy('NecesidadProteccion')
            ->orderBy('NombreComercial')
            ->get()
            ->map(function ($planComercial) {
                $valores = [];

                foreach ($planComercial->valores as $valor) {
                    if ($valor->CampoComparativo) {
                        $valores[$valor->CampoComparativo] = $valor->ValorTexto;
                    }
                }

                return [
                    'id' => $planComercial->Id,
                    'ramo' => $planComercial->NecesidadProteccion,
                    'aseguradora' => optional($planComercial->aseguradora)->Nombre,
                    'producto' => optional($planComercial->producto)->Nombre,
                    'plan' => optional($planComercial->plan)->Nombre,
                    'nombre_comercial' => $planComercial->NombreComercial,
                    'texto' => trim(implode(' / ', array_filter([
                        optional($planComercial->aseguradora)->Nombre,
                        optional($planComercial->producto)->Nombre,
                        optional($planComercial->plan)->Nombre,
                        $planComercial->NombreComercial,
                    ]))),
                    'valores' => $valores,
                ];
            })
            ->values();

        return view('ventas.ofertas.formulario', compact(
            'ejecutivos',
            'tiposCarteraNr',
            'ramos',
            'aseguradoras',
            'estadosVenta',
            'clientes',
            'camposComparativos',
            'planesComerciales'
        ));
    }

    public function buscarClientes(Request $request)
    {
        $term = trim($request->get('term', $request->get('q', '')));
        $termNormalizado = preg_replace('/[\s\-_.]/', '', $term);

        if (strlen($term) < 1) {
            return response()->json([
                'results' => [],
            ]);
        }

        $clientes = Cliente::query()
            ->select(
                'Id',
                'Nombre',
                'Dui',
                'Nit',
                'Pasaporte',
                'TelefonoCelular',
                'TelefonoCelular2',
                'TelefonoResidencia',
                'TelefonoOficina',
                'CorreoPrincipal',
                'CorreoSecundario'
            )
            ->where('Activo', 1)
            ->where(function ($query) use ($term, $termNormalizado) {
                $like = '%' . $term . '%';
                $likeNormalizado = '%' . $termNormalizado . '%';

                $query->where('Nombre', 'like', $like)
                    ->orWhere('Dui', 'like', $like)
                    ->orWhere('Nit', 'like', $like)
                    ->orWhere('Pasaporte', 'like', $like)
                    ->orWhere('TelefonoCelular', 'like', $like)
                    ->orWhere('TelefonoCelular2', 'like', $like)
                    ->orWhere('CorreoPrincipal', 'like', $like)
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(COALESCE(Dui, ''), '-', ''), ' ', ''), '.', '') like ?", [$likeNormalizado])
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(COALESCE(Nit, ''), '-', ''), ' ', ''), '.', '') like ?", [$likeNormalizado])
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(COALESCE(TelefonoCelular, ''), '-', ''), ' ', ''), '.', '') like ?", [$likeNormalizado]);
            })
            ->orderBy('Nombre')
            ->limit(20)
            ->get()
            ->map(function ($cliente) {
                return [
                    'id' => $cliente->Id,
                    'text' => $this->textoCliente($cliente),
                ];
            });

        return response()->json([
            'results' => $clientes,
        ]);
    }

    public function clienteDetalle($id)
    {
        $cliente = Cliente::where('Activo', 1)->findOrFail($id);

        return response()->json([
            'cliente' => [
                'Id' => $cliente->Id,
                'Nombre' => $cliente->Nombre,
                'Telefono' => $cliente->TelefonoCelular
                    ?: ($cliente->TelefonoCelular2 ?: ($cliente->TelefonoResidencia ?: $cliente->TelefonoOficina)),
                'Correo' => $cliente->CorreoPrincipal ?: $cliente->CorreoSecundario,
                'Dui' => $cliente->Dui,
                'Nit' => $cliente->Nit,
                'Pasaporte' => $cliente->Pasaporte,
                'TipoPersona' => $cliente->TipoPersona,
            ],
        ]);
    }

    private function textoCliente($cliente)
    {
        $documento = $cliente->Dui ?: ($cliente->Nit ?: $cliente->Pasaporte);
        $telefono = $cliente->TelefonoCelular ?: ($cliente->TelefonoCelular2 ?: $cliente->TelefonoResidencia);

        return trim($cliente->Nombre . ($documento ? ' / ' . $documento : '') . ($telefono ? ' / ' . $telefono : ''));
    }
}
