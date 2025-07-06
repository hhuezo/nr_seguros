<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\TipoCartera;
use Illuminate\Http\Request;

class PolizaSeguroController extends Controller
{
    public function index()
    {
        return view('polizas.seguro.index');
    }


    public function create()
    {
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $clientes = Cliente::where('Activo',1)->get();
        $ofertas = Negocio::get();
        $tipo_cartera_nr = TipoCartera::get();
        $forma_pago = [0 => '', 1 => 'Anual', 2 => 'Semestral',3 => 'Trimestral',4 => 'Mensual'];
        $estado_poliza = EstadoPoliza::get();
        //dd($ofertas);

        return view('polizas.seguro.create', compact('estado_poliza','forma_pago','tipo_cartera_nr','ofertas','productos','planes','aseguradora','clientes'));
    }

    public function get_oferta(Request $request){
        $negocio = Negocio::findOrFail($request->Oferta);
        //dd($negocio->cotizaciones,$negocio);
        
        $oferta = [
            'id' => $negocio->Id,
            'id_cliente' => $negocio->clientes->Id,
            'dui_cliente' => $negocio->clientes->Dui,
            'nombre_cliente' => $negocio->clientes->Nombre,
            'forma_pago' => $negocio->PeriodoPago,
            'num_cuotas' => $negocio->NumCoutas,
            'productos' => $negocio->cotizaciones ?? $negocio->cotizaciones->planes->productos,
            'planes' => $negocio->cotizaciones ?? $negocio->cotizaciones->planes,

        ];
        return response()->json(['oferta' => $oferta]);
        
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
