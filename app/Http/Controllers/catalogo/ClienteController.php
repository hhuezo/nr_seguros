<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ClienteEstado;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\Deuda;
use App\Models\polizas\Residencia;
use App\Models\polizas\Vida;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class ClienteController extends Controller
{

    public function index()
    {
        $clientes = Cliente::get();
        return view('catalogo.cliente.index',compact( 'clientes'));
    }

    public function create()
    {

        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo','=',1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo','=',1)->get();
        $cliente_estados = ClienteEstado::get();

        return view('catalogo.cliente.create',compact( 'tipos_contribuyente','rutas','ubicaciones_cobro','cliente_estados'));
    }

    public function store(Request $request)
    {

        $time = Carbon::now();

        $cliente = new Cliente();
        $cliente->Nit = $request->get('Nit');
        $cliente->Dui = $request->get('Dui');

        $cliente->RegistroFiscal = $request->get('RegistroFiscal');
        $cliente->Nombre = $request->get('Nombre');
        $cliente->FechaNacimiento = $request->get('FechaNacimiento');
        $cliente->EstadoFamiliar = $request->get('EstadoFamiliar');
        $cliente->NumeroDependientes = $request->get('NumeroDependientes');
        $cliente->Ocupacion = $request->get('Ocupacion');
        $cliente->DireccionResidencia = $request->get('DireccionResidencia');
        $cliente->DireccionCorrespondencia = $request->get('DireccionCorrespondencia');
        $cliente->TelefonoRecidencia = $request->get('TelefonoRecidencia');

        $cliente->TelefonoOficina = $request->get('TelefonoOficina');
        $cliente->TelefonoCelular = $request->get('TelefonoCelular');
        $cliente->CorreoPrincipal = $request->get('CorreoPrincipal');
        $cliente->CorreoSecundario = $request->get('CorreoSecundario');
        $cliente->FechaVinculacion = $request->get('FechaVinculacion');
        $cliente->FechaBaja = $request->get('FechaBaja');
        //$cliente->Ruta = $request->get('Ruta');

        $cliente->ResponsablePago = $request->get('ResponsablePago');
        $cliente->TipoContribuyente = $request->get('TipoContribuyente');
        $cliente->UbicacionCobro = $request->get('UbicacionCobro');

        // //$cliente->Contacto = $request->get('Contacto');
        $cliente->Referencia = $request->get('Referencia');
        //$cliente->NumeroTarjeta = $request->get('NumeroTarjeta');
        //$cliente->FechaVencimiento = $request->get('FechaVencimiento');
        $cliente->Estado = $request->get('Estado');
        $cliente->Genero = $request->get('Genero');
        $cliente->TipoPersona = $request->get('TipoPersona');
        $cliente->FechaIngreso = $time->toDateTimeString();
        $cliente->UsuarioIngreso = auth()->user()->id;

        $cliente->save();

        alert()->success('El registro ha sido creado correctamente');

        return redirect('catalogo/cliente/'.$cliente->Id.'/edit');

        //return back();
    }

    public function cliente_create(Request $request)
    {
      //  dd("holi");
       $time = Carbon::now();

        $cliente = new  Cliente();
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

        return Cliente::where('Activo','=',1)->get();
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo','=',1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo','=',1)->get();
        $cliente_estados = ClienteEstado::get();
        $action = 1;

        $poliza_deudas = Deuda::get();
        $poliza_residencias = Residencia::get();
        $poliza_vidas = Vida::get();

        return view('catalogo.cliente.edit',compact( 'tipos_contribuyente','rutas','ubicaciones_cobro','cliente_estados',
                'cliente','action','poliza_deudas','poliza_residencias','poliza_vidas'));

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
        Cliente::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
