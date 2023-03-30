<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class ClienteController extends Controller
{

    public function index()
    {
        $clientes = Cliente::where('Activo','=',1)->get();
        return view('catalogo.cliente.index',compact( 'clientes'));
    }

    public function create()
    {
        //alert()->success('El registro ha sido agregado correctamente');
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo','=',1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo','=',1)->get();

        return view('catalogo.cliente.create',compact( 'tipos_contribuyente','rutas','ubicaciones_cobro'));
    }

    public function store(Request $request)
    {

        $time = Carbon::now();

        $cliente = new Cliente();
        $cliente->Nit = $request->get('Nit');
        $cliente->Dui = $request->get('Dui');
        $cliente->Nombre = $request->get('Nombre');
        $cliente->DireccionResidencia = $request->get('DireccionResidencia');
        $cliente->DireccionCorrespondencia = $request->get('DireccionCorrespondencia');
        $cliente->TelefonoResidencia = $request->get('TelefonoResidencia');
        $cliente->TelefonoOficina = $request->get('TelefonoOficina');
        $cliente->TelefonoCelular = $request->get('TelefonoCelular');
        $cliente->Correo = $request->get('Correo');
        $cliente->Ruta = $request->get('Ruta');
        $cliente->ResponsablePago = $request->get('ResponsablePago');
        $cliente->TipoContribuyente = $request->get('TipoContribuyente');
        $cliente->UbicacionCobro = $request->get('UbicacionCobro');
        $cliente->Contacto = $request->get('Contacto');
        $cliente->Referencia = $request->get('Referencia');
        $cliente->NumeroTarjeta = $request->get('NumeroTarjeta');
        $cliente->FechaVencimiento = $request->get('FechaVencimiento');
        $cliente->Genero = $request->get('Genero');
        $cliente->TipoPersona = $request->get('TipoPersona');
        $cliente->FechaCreacion = $time->toDateTimeString();
        $cliente->UsuarioCreacion = auth()->user()->id;
        $cliente->save();

        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo','=',1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo','=',1)->get();
        return view('catalogo.cliente.edit',compact( 'cliente','tipos_contribuyente','rutas','ubicaciones_cobro'));

    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->Nit = $request->get('Nit');
        $cliente->Dui = $request->get('Dui');
        $cliente->Nombre = $request->get('Nombre');
        $cliente->DireccionResidencia = $request->get('DireccionResidencia');
        $cliente->DireccionCorrespondencia = $request->get('DireccionCorrespondencia');
        $cliente->TelefonoResidencia = $request->get('TelefonoResidencia');
        $cliente->TelefonoOficina = $request->get('TelefonoOficina');
        $cliente->TelefonoCelular = $request->get('TelefonoCelular');
        $cliente->Correo = $request->get('Correo');
        $cliente->Ruta = $request->get('Ruta');
        $cliente->ResponsablePago = $request->get('ResponsablePago');
        $cliente->TipoContribuyente = $request->get('TipoContribuyente');
        $cliente->UbicacionCobro = $request->get('UbicacionCobro');
        $cliente->Contacto = $request->get('Contacto');
        $cliente->Referencia = $request->get('Referencia');
        $cliente->NumeroTarjeta = $request->get('NumeroTarjeta');
        $cliente->FechaVencimiento = $request->get('FechaVencimiento');
        $cliente->Genero = $request->get('Genero');
        $cliente->TipoPersona = $request->get('TipoPersona');
        $cliente->update();

        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }

    public function destroy($id)
    {
        //
    }
}
