<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ClienteContactoFrecuente;
use App\Models\catalogo\ClienteEstado;
use App\Models\catalogo\ClienteHabitoConsumo;
use App\Models\catalogo\ClienteRetroalimentacion;
use App\Models\catalogo\ClienteTarjetaCredito;
use App\Models\catalogo\FormaPago;
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
        $clientes = Cliente::get();
        return view('catalogo.cliente.index',compact( 'clientes'));
    }

    public function create()
    {
        //alert()->success('El registro ha sido agregado correctamente');
        $tipos_contribuyente = TipoContribuyente::get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo','=',1)->get();
        $formas_pago = FormaPago::where('Activo','=',1)->get();
        $cliente_estados = ClienteEstado::get();

        return view('catalogo.cliente.create',compact( 'tipos_contribuyente','formas_pago','ubicaciones_cobro'
        ,'cliente_estados'));
    }

    public function store(Request $request)
    {

        $time = Carbon::now();

        $cliente = new Cliente();
        $cliente->Nit = $request->get('Nit');
        $cliente->Dui = $request->get('Dui');
        $cliente->Nombre = $request->get('Nombre');
        $cliente->RegistroFiscal = $request->get('RegistroFiscal');
        $cliente->FechaNacimiento = $request->get('FechaNacimiento');
        $cliente->EstadoFamiliar = $request->get('EstadoFamiliar');
        $cliente->NumeroDependientes = $request->get('NumeroDependientes');
        $cliente->Ocupacion = $request->get('Ocupacion');
        $cliente->DireccionResidencia = $request->get('DireccionResidencia');
        $cliente->DireccionCorrespondencia = $request->get('DireccionCorrespondencia');
        $cliente->TelefonoResidencia = $request->get('TelefonoResidencia');
        $cliente->TelefonoOficina = $request->get('TelefonoOficina');
        $cliente->TelefonoCelular = $request->get('TelefonoCelular');
        $cliente->CorreoPrincipal = $request->get('CorreoPrincipal');
        $cliente->CorreoSecundario = $request->get('CorreoSecundario');
        $cliente->FechaVinculacion = $request->get('FechaVinculacion');
        $cliente->FechaBaja = $request->get('FechaBaja');
        $cliente->ResponsablePago = $request->get('ResponsablePago');
        $cliente->UbicacionCobro = $request->get('UbicacionCobro');
        $cliente->FormaPago = $request->get('FormaPago');
        $cliente->Estado = $request->get('Estado');
        $cliente->TipoPersona = $request->get('TipoPersona');
        $cliente->Genero = $request->get('Genero');
        $cliente->TipoContribuyente = $request->get('TipoContribuyente');
        $cliente->Referencia = $request->get('Referencia');
        $cliente->FechaIngreso = $time->toDateTimeString();
        $cliente->UsuarioIngreso = auth()->user()->id;
        $cliente->save();

        alert()->success('El registro ha sido creado correctamente');

        return redirect('catalogo/cliente/' . $cliente->id . '/edit');

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
        $ubicaciones_cobro = UbicacionCobro::where('Activo','=',1)->get();
        $formas_pago = FormaPago::where('Activo','=',1)->get();
        $cliente_estados = ClienteEstado::get();

        //contactos
        $contactos = ClienteContactoFrecuente::where('Cliente','=',$id)->get();
        $tarjetas = ClienteTarjetaCredito::where('Cliente','=',$id)->get();
        $habitos = ClienteHabitoConsumo::where('Cliente','=',$id)->get();  
        $retroalimentacion = ClienteRetroalimentacion::where('Cliente','=',$id)->get(); 

        return view('catalogo.cliente.edit',compact( 'cliente','tipos_contribuyente','formas_pago',
        'ubicaciones_cobro','cliente_estados','contactos'
        ,'tarjetas','habitos','retroalimentacion'));

    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->Nit = $request->get('Nit');
        $cliente->Dui = $request->get('Dui');
        $cliente->Nombre = $request->get('Nombre');
        $cliente->RegistroFiscal = $request->get('RegistroFiscal');
        $cliente->FechaNacimiento = $request->get('FechaNacimiento');
        $cliente->EstadoFamiliar = $request->get('EstadoFamiliar');
        $cliente->NumeroDependientes = $request->get('NumeroDependientes');
        $cliente->Ocupacion = $request->get('Ocupacion');
        $cliente->DireccionResidencia = $request->get('DireccionResidencia');
        $cliente->DireccionCorrespondencia = $request->get('DireccionCorrespondencia');
        $cliente->TelefonoResidencia = $request->get('TelefonoResidencia');
        $cliente->TelefonoOficina = $request->get('TelefonoOficina');
        $cliente->TelefonoCelular = $request->get('TelefonoCelular');
        $cliente->CorreoPrincipal = $request->get('CorreoPrincipal');
        $cliente->CorreoSecundario = $request->get('CorreoSecundario');
        $cliente->FechaVinculacion = $request->get('FechaVinculacion');
        $cliente->FechaBaja = $request->get('FechaBaja');
        $cliente->ResponsablePago = $request->get('ResponsablePago');
        $cliente->UbicacionCobro = $request->get('UbicacionCobro');
        $cliente->FormaPago = $request->get('FormaPago');
        $cliente->Estado = $request->get('Estado');
        $cliente->TipoPersona = $request->get('TipoPersona');
        $cliente->Genero = $request->get('Genero');
        $cliente->TipoContribuyente = $request->get('TipoContribuyente');
        $cliente->Referencia = $request->get('Referencia');
        $cliente->update();

        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }


    public function add_contacto(Request $request)
    {
        $contacto = new ClienteContactoFrecuente();
        $contacto->Cliente = $request->Cliente;
        $contacto->Nombre = $request->Nombre;
        $contacto->Cargo = $request->Cargo;
        $contacto->Telefono = $request->Telefono;
        $contacto->Email = $request->Email;
        $contacto->LugarTrabajo = $request->LugarTrabajo;
        $contacto->save();
        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function delete_contacto(Request $request)
    {
        $contacto = ClienteContactoFrecuente::findOrFail($request->Id);       
        $contacto->delete();
        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }


    public function add_tarjeta(Request $request)
    {
        $contacto = new ClienteTarjetaCredito();
        $contacto->Cliente = $request->Cliente;
        $contacto->NumeroTarjeta = $request->NumeroTarjeta;
        $contacto->FechaVencimiento = $request->FechaVencimiento;
        $contacto->PolizaVinculada = $request->PolizaVinculada;
        $contacto->save();
        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function delete_tarjeta(Request $request)
    {
        $tarjeta = ClienteTarjetaCredito::findOrFail($request->Id);     
        //dd($tarjeta) ;
        $tarjeta->delete();
        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }




    public function add_habito(Request $request)
    {
        $habito = new ClienteHabitoConsumo();
        $habito->Cliente = $request->Cliente;
        $habito->ActividadEconomica = $request->ActividadEconomica;
        $habito->IngresoPromedio = $request->IngresoPromedio;
        $habito->GastoMensualSeguro = $request->GastoMensualSeguro;
        $habito->NivelEducativo = $request->NivelEducativo;
        $habito->save();
        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function delete_habito(Request $request)
    {
        $habito = ClienteHabitoConsumo::findOrFail($request->Id);     
        //dd($tarjeta) ;
        $habito->delete();
        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }

    public function add_retroalimentacion(Request $request)
    {
        $retroalimentacion = new ClienteRetroalimentacion();
        $retroalimentacion->Cliente = $request->Cliente;
        $retroalimentacion->Producto = $request->Producto;
        $retroalimentacion->ValoresAgregados = $request->ValoresAgregados;
        $retroalimentacion->Competidores = $request->Competidores;
        $retroalimentacion->Referidos = $request->Referidos;
        $retroalimentacion->QueQuisiera = $request->QueQuisiera;
        $retroalimentacion->save();
        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function delete_retroalimentacion(Request $request)
    {
        $retroalimentacion = ClienteRetroalimentacion::findOrFail($request->Id);     
        //dd($tarjeta) ;
        $retroalimentacion->delete();
        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }
    

    public function destroy($id)
    {
        Cliente::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
