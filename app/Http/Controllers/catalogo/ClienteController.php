<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ClienteContactoCargo;
use App\Models\catalogo\ClienteContactoFrecuente;
use App\Models\catalogo\ClienteEstado;
use App\Models\catalogo\ClienteHabitoConsumo;
use App\Models\catalogo\ClienteInformarse;
use App\Models\catalogo\ClienteMotivoEleccion;
use App\Models\catalogo\ClienteNecesidadProteccion;
use App\Models\catalogo\ClientePrefereciaCompra;
use App\Models\catalogo\ClienteRetroalimentacion;
use App\Models\catalogo\ClienteTarjetaCredito;
use App\Models\catalogo\Departamento;
use App\Models\catalogo\FormaPago;
use App\Models\catalogo\Municipio;
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
        session(['tab1' => '1']);
        session(['tab2' => '1']);
        $clientes = Cliente::get();
        return view('catalogo.cliente.index', compact('clientes'));
    }

    public function create()
    {
        //alert()->success('El registro ha sido agregado correctamente');
        $tipos_contribuyente = TipoContribuyente::get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $formas_pago = FormaPago::where('Activo', '=', 1)->get();
        $cliente_estados = ClienteEstado::get();
        $departamentos = Departamento::get();
        $municipios = Municipio::get();

        return view('catalogo.cliente.create', compact(
            'tipos_contribuyente',
            'formas_pago',
            'ubicaciones_cobro',
            'cliente_estados',
            'departamentos',
            'municipios'
        ));
    }

    public function get_municipio($id)
    {
        return Municipio::where('Departamento','=',$id)->get();
    }
    
    public function string_replace($string)
    {
        return str_replace("_", "", $string);
    }

    public function store(Request $request)
    {
        $messages = [
            'Dui.min' => 'El formato de DUI es incorrecto',
            'Dui.unique' => 'El DUI ya existe en la base de datos',
            'Nit.min' => 'El formato de NIT es incorrecto',
            'Nit.unique' => 'El NIT ya existe en la base de datos',
        ];

        $request->merge(['Dui' => $this->string_replace($request->get('Dui'))]);
        $request->merge(['Nit' => $this->string_replace($request->get('Nit'))]);

        $request->validate([
            'Nombre' => 'required',
        ], $messages);

        if ($request->get('TipoPersona') ==1) {
            $request->validate([
                'Dui' => 'required',
            ], $messages);
        }

        if ($request->get('Dui') != null) {
            $request->validate([
                'Dui' => 'min:10|unique:cliente',
            ], $messages);
        }

        if ($request->get('Nit') != null) {
            $request->validate([
                'Nit' => 'min:17|unique:cliente',
            ], $messages);
        }


    


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
        $cliente->Municipio = $request->get('Municipio');
        $cliente->FechaIngreso = $time->toDateTimeString();
        $cliente->UsuarioIngreso = auth()->user()->id;
        $cliente->save();

        session(['tab1' => '1']);
        session(['tab2' => '1']);

        alert()->success('El registro ha sido creado correctamente');

        return redirect('catalogo/cliente/' . $cliente->Id . '/edit');

       // return back();
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

        return Cliente::where('Activo', '=', 1)->get();
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        if ($cliente->FechaNacimiento) {
            $cliente->Edad = $this->getAge($cliente->FechaNacimiento);
        } else {
            $cliente->Edad = "";
        }

        $tipos_contribuyente = TipoContribuyente::get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $formas_pago = FormaPago::where('Activo', '=', 1)->get();
        $cliente_estados = ClienteEstado::get();

        //contactos
        $contactos = ClienteContactoFrecuente::where('Cliente', '=', $id)->get();
        $tarjetas = ClienteTarjetaCredito::where('Cliente', '=', $id)->get();
        $habitos = ClienteHabitoConsumo::where('Cliente', '=', $id)->get();
        $retroalimentacion = ClienteRetroalimentacion::where('Cliente', '=', $id)->get();
        $necesidades = ClienteNecesidadProteccion::get();
        $informarse = ClienteInformarse::get();
        $motivo_eleccion = ClienteMotivoEleccion::get();
        $preferencia_compra = ClientePrefereciaCompra::get();
        $cliente_contacto_cargos = ClienteContactoCargo::get();

        $departamentos = Departamento::get();
        $departamento_actual = 0;
        if($cliente->Municipio)
        {
            $municipios = Municipio::where('Departamento','=',$cliente->municipio->Departamento)->get();
            $departamento_actual = $cliente->municipio->Departamento;
        }
        else{
            $municipios = Municipio::get();
        }
        


        return view('catalogo.cliente.edit', compact(
            'cliente',
            'tipos_contribuyente',
            'formas_pago',
            'ubicaciones_cobro',
            'cliente_estados',
            'contactos',
            'tarjetas',
            'habitos',
            'retroalimentacion',
            'necesidades',
            'informarse',
            'motivo_eleccion',
            'preferencia_compra',
            'cliente_contacto_cargos',
            'departamentos',
            'municipios',
            'departamento_actual'

        ));
    }

    public function getAge($date)
    {
        $now = Carbon::now();
        $age = Carbon::parse($date)->age;
        return $age;
    }

    public function update(Request $request, $id)
    {

        $messages = [
            'Dui.min' => 'El formato de DUI es incorrecto',
            'Dui.unique' => 'El DUI ya existe en la base de datos',
            'Nit.min' => 'El formato de NIT es incorrecto',
            'Nit.unique' => 'El NIT ya existe en la base de datos',
        ];

        $request->merge(['Dui' => $this->string_replace($request->get('Dui'))]);
        $request->merge(['Nit' => $this->string_replace($request->get('Nit'))]);

        $request->validate([
            'Nombre' => 'required',
        ], $messages);

        $count_dui = Cliente::where('Dui','=',$request->get('Dui'))->where('Id','<>',$id)->count();
        $count_nit = Cliente::where('Nit','=',$request->get('Nit'))->where('Id','<>',$id)->count();

        if ($request->get('TipoPersona') ==1) {
            $request->validate([
                'Dui' => 'required',
            ], $messages);
        }

        if ($request->get('Dui') != null && $count_dui > 0) {
            $request->validate([
                'Dui' => 'min:10|unique:cliente',
            ], $messages);
        }

        if ($request->get('Nit') != null && $count_nit > 0) {
            $request->validate([
                'Nit' => 'min:17|unique:cliente',
            ], $messages);
        }


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
        $cliente->Municipio = $request->get('Municipio');
        $cliente->update();

        session(['tab1' => '1']);

        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }


    public function red_social(Request $request)
    {
        $cliente = Cliente::findOrFail($request->Id);
        $cliente->Facebook = $request->get('Facebook');
        $cliente->ActividadesCreativas = $request->get('ActividadesCreativas');
        $cliente->EstiloVida = $request->get('EstiloVida');
        $cliente->SitioWeb = $request->get('SitioWeb');
        $cliente->NecesidadProteccion = $request->get('NecesidadProteccion');
        $cliente->Laptop = $request->get('Laptop');
        $cliente->PC = $request->get('PC');
        $cliente->Tablet = $request->get('Tablet');
        $cliente->SmartWatch = $request->get('SmartWatch');
        $cliente->DispositivosOtros = $request->get('DispositivosOtros');
        $cliente->Informarse = $request->get('Informarse');
        $cliente->Instagram = $request->get('Instagram');
        $cliente->TieneMascota = $request->get('TieneMascota');
        $cliente->MotivoEleccion = $request->get('MotivoEleccion');
        $cliente->PreferenciaCompra = $request->get('PreferenciaCompra');
        $cliente->Efectivo = $request->get('Efectivo');
        $cliente->TarjetaCredito = $request->get('TarjetaCredito');
        $cliente->App = $request->get('App');
        $cliente->MonederoEletronico = $request->get('MonederoEletronico');
        $cliente->CompraOtros = $request->get('CompraOtros');
        $cliente->Informacion = $request->get('Informacion');
        $cliente->update();

        session(['tab1' => '2']);

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

        session(['tab2' => '1']);
        return back();
    }

    public function edit_contacto(Request $request)
    {
        $contacto = ClienteContactoFrecuente::findOrFail($request->Id);
        $contacto->Cliente = $request->Cliente;
        $contacto->Nombre = $request->Nombre;
        $contacto->Cargo = $request->Cargo;
        $contacto->Telefono = $request->Telefono;
        $contacto->Email = $request->Email;
        $contacto->LugarTrabajo = $request->LugarTrabajo;
        $contacto->save();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '1']);
        return back();
    }
    
    public function delete_contacto(Request $request)
    {
        $contacto = ClienteContactoFrecuente::findOrFail($request->Id);
        $contacto->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '1']);
        return back();
    }


    public function add_tarjeta(Request $request)
    {
        $tarjeta = new ClienteTarjetaCredito();
        $tarjeta->Cliente = $request->Cliente;
        $tarjeta->NumeroTarjeta = $request->NumeroTarjeta;
        $tarjeta->FechaVencimiento = $request->FechaVencimiento;
        $tarjeta->PolizaVinculada = $request->PolizaVinculada;
        $tarjeta->MetodoPago = $request->MetodoPago;
        $tarjeta->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab1' => '3']);
        return back();
    }

    public function delete_tarjeta(Request $request)
    {
        $tarjeta = ClienteTarjetaCredito::findOrFail($request->Id);
        //dd($tarjeta) ;
        $tarjeta->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab1' => '3']);
        return back();
    }

    public function edit_tarjeta(Request $request)
    {
        $tarjeta = ClienteTarjetaCredito::findOrFail($request->Id);
        $tarjeta->Cliente = $request->Cliente;
        $tarjeta->NumeroTarjeta = $request->NumeroTarjeta;
        $tarjeta->FechaVencimiento = $request->FechaVencimiento;
        $tarjeta->PolizaVinculada = $request->PolizaVinculada;
        $tarjeta->MetodoPago = $request->MetodoPago;
        $tarjeta->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab1' => '3']);
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

        session(['tab2' => '2']);
        return back();
    }

    public function edit_habito(Request $request)
    {
        $habito = ClienteHabitoConsumo::findOrFail($request->Id);
        $habito->Cliente = $request->Cliente;
        $habito->ActividadEconomica = $request->ActividadEconomica;
        $habito->IngresoPromedio = $request->IngresoPromedio;
        $habito->GastoMensualSeguro = $request->GastoMensualSeguro;
        $habito->NivelEducativo = $request->NivelEducativo;
        $habito->save();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    

    public function delete_habito(Request $request)
    {
        $habito = ClienteHabitoConsumo::findOrFail($request->Id);
        //dd($tarjeta) ;
        $habito->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '2']);
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
        $retroalimentacion->ServicioCliente = $request->ServicioCliente;        
        $retroalimentacion->save();
        session(['tab2' => '3']);
        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function edit_retroalimentacion(Request $request)
    {
        $retroalimentacion = ClienteRetroalimentacion::findOrFail($request->Id);
        $retroalimentacion->Cliente = $request->Cliente;
        $retroalimentacion->Producto = $request->Producto;
        $retroalimentacion->ValoresAgregados = $request->ValoresAgregados;
        $retroalimentacion->Competidores = $request->Competidores;
        $retroalimentacion->Referidos = $request->Referidos;
        $retroalimentacion->QueQuisiera = $request->QueQuisiera;
        $retroalimentacion->ServicioCliente = $request->ServicioCliente;        
        $retroalimentacion->update();
        session(['tab2' => '3']);
        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }

    public function delete_retroalimentacion(Request $request)
    {
        $retroalimentacion = ClienteRetroalimentacion::findOrFail($request->Id);
        //dd($tarjeta) ;
        $retroalimentacion->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '3']);
        return back();
    }


    public function destroy($id)
    {
        Cliente::findOrFail($id)->update(['Activo' => 0]);
        alert()->info('El registro ha sido desactivado correctamente');

        return back();
    }

    public function active($id)
    {
        Cliente::findOrFail($id)->update(['Activo' => 1]);
        alert()->success('El registro ha sido activado correctamente');

        return back();
    }
}
