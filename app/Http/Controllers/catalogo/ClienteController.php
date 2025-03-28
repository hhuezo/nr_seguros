<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ClienteContactoCargo;
use App\Models\catalogo\ClienteContactoFrecuente;
use App\Models\catalogo\ClienteDocumento;
use App\Models\catalogo\ClienteEstado;
use App\Models\catalogo\ClienteHabitoConsumo;
use App\Models\catalogo\ClienteInformarse;
use App\Models\catalogo\ClienteMetodoPago;
use App\Models\catalogo\ClienteMotivoEleccion;
use App\Models\catalogo\ClienteNecesidadProteccion;
use App\Models\catalogo\ClientePrefereciaCompra;
use App\Models\catalogo\ClienteRetroalimentacion;
use App\Models\catalogo\ClienteTarjetaCredito;
use App\Models\catalogo\Departamento;
use App\Models\catalogo\Distrito;
use App\Models\catalogo\FormaPago;
use App\Models\catalogo\Municipio;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;


class ClienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        session(['tab2' => '1']);
        $clientes = Cliente::where('Activo', 1)->get();
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
        $distritos = Distrito::get();

        return view('catalogo.cliente.create', compact(
            'tipos_contribuyente',
            'formas_pago',
            'ubicaciones_cobro',
            'cliente_estados',
            'departamentos',
            'municipios',
            'distritos'
        ));
    }

    public function get_municipio($id)
    {
        return Municipio::where('Departamento', '=', $id)->get();
    }

    public function get_distrito($id)
    {
        return Distrito::where('Municipio', '=', $id)->get();
    }

    public function string_replace($string)
    {
        return str_replace("_", "", $string);
    }

    public function validar(Request $request)
    {
        $messages = [
            'Dui.min' => 'El formato de DUI es incorrecto',
            'Dui.unique' => 'El DUI ya existe en la base de datos',
            'TelefonoCelular.required' => 'El teléfono principal es obligatorio',
            'TelefonoCelular.size' => 'El teléfono Principal es incorrecto',
        ];

        $request->merge(['Dui' => $this->string_replace($request->get('Dui'))]);
        $request->merge(['Nit' => $this->string_replace($request->get('Nit'))]);
        $request->merge(['TelefonoCelular' => $this->string_replace($request->get('TelefonoCelular'))]);
        //dd($request->get('Dui'));
        $rules = [
            'Nombre' => 'required',
            'DireccionCorrespondencia' => 'required|max:255',
            'TelefonoCelular' => 'required|size:9',
            //'CorreoPrincipal' => 'required|email|max:255',
            'FechaVinculacion' => 'required|date',
            'Estado' => 'required|integer',
            'Genero' => 'required|integer',
            'TipoContribuyente' => 'required|integer',
            'UbicacionCobro' => 'required|integer',
            //'Departamento' => 'required|integer',
            //'Municipio' => 'required|integer',
            //'Distrito' => 'required|integer',
        ];

        if ($request->get('TipoPersona') == 1) {
            $rules['Dui'] = 'required';
            $rules['FechaNacimiento'] = [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $eighteenYearsAgo = now()->subYears(18)->format('Y-m-d');
                    if ($value > $eighteenYearsAgo) {
                        $fail('El cliente debe tener al menos 18 años.');
                    }
                }
            ];
        }

        if ($request->get('Dui') != null) {
            if ($request->get('ClienteId') != null) {
                $rules['Dui'] = [
                    'required',
                    'min:10',
                    Rule::unique('cliente')->ignore($request->get('ClienteId')),
                ];
            } else {
                $rules['Dui'] = 'min:10|unique:cliente';
            }
        }

        if ($request->TipoPersona <> 1) {
            if ($request->get('Nit') != null) {

                if ($request->get('ClienteId') != null) {
                    $rules['Nit'] = [
                        'required',
                        'min:17',
                        Rule::unique('cliente')->ignore($request->get('ClienteId')),
                    ];
                } else {
                    $rules['Nit'] = 'min:17|unique:cliente';
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Si todas las validaciones pasan
        return response()->json(['success' => true, 'message' => 'Validación exitosa']);
    }


    public function store(Request $request)
    {
        $messages = [
            'Dui.min' => 'El formato de DUI es incorrecto',
            'Dui.unique' => 'El DUI ya existe en la base de datos',
            // 'Nit.min' => 'El formato de NIT es incorrecto',
            // 'Nit.unique' => 'El NIT ya existe en la base de datos',
        ];

        $request->merge(['Dui' => $this->string_replace($request->get('Dui'))]);
        $request->merge(['Nit' => $this->string_replace($request->get('Nit'))]);

        $request->validate([
            'Nombre' => 'required',
            'FechaNacimiento' => 'required|date',
        ], $messages);

        if ($request->get('TipoPersona') == 1) {
            $request->validate([
                'Dui' => 'required',
            ], $messages);
        }

        if ($request->get('Dui') != null) {
            $request->validate([
                'Dui' => 'min:10|unique:cliente',
            ], $messages);
        }
        if ($request->TipoPersona <> 1) {
            if ($request->get('Nit') != null) {
                $request->validate([
                    'Nit' => 'min:17|unique:cliente',
                ], $messages);
            }
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
        //$cliente->FormaPago = $request->get('FormaPago');
        $cliente->Estado = $request->get('Estado');
        $cliente->TipoPersona = $request->get('TipoPersona');
        $cliente->Genero = $request->get('Genero');
        $cliente->TipoContribuyente = $request->get('TipoContribuyente');
        $cliente->Referencia = $request->get('Referencia');
        if ($request->filled('Distrito')) {
            $cliente->Distrito = $request->get('Distrito');
        }
        $cliente->Comentarios = $request->get('Comentarios');
        $cliente->FechaIngreso = $time->toDateTimeString();
        $cliente->UsuarioIngreso = auth()->user()->id;
        $cliente->TelefonoCelular2 = $request->get('TelefonoCelular2');
        $cliente->BancoPrefencia = $request->get('BancoPrefencia');
        $cliente->CuentasDevolucionPrimas = $request->get('CuentasDevolucionPrimas');
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
        $cliente->TelefonoCelular2 = $request->get('TelefonoCelular2');
        $cliente->BancoPrefencia = $request->get('BancoPrefencia');
        $cliente->CuentasDevolucionPrimas = $request->get('CuentasDevolucionPrimas');
        $cliente->save();

        return Cliente::where('Activo', '=', 1)->get();
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        //  dd($cliente->Smartphone);
        if ($cliente->FechaNacimiento) {
            $cliente->Edad = $this->getAge($cliente->FechaNacimiento);
        } else {
            $cliente->Edad = "";
        }

        $documentos = ClienteDocumento::where('Cliente', $id)->where('Activo', 1)->get();

        $tipos_contribuyente = TipoContribuyente::get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $formas_pago = FormaPago::where('Activo', '=', 1)->get();
        $cliente_estados = ClienteEstado::get();

        //contactos
        $contactos = ClienteContactoFrecuente::where('Cliente', '=', $id)->get();
        $tarjetas = ClienteTarjetaCredito::where('Cliente', '=', $id)->get();
        $habitos = ClienteHabitoConsumo::where('Cliente', '=', $id)->get();
        $retroalimentacion = ClienteRetroalimentacion::where('Cliente', '=', $id)->get();
        $necesidades = NecesidadProteccion::get();
        $informarse = ClienteInformarse::get();
        $motivo_eleccion = ClienteMotivoEleccion::get();
        $preferencia_compra = ClientePrefereciaCompra::get();
        $cliente_contacto_cargos = ClienteContactoCargo::get();
        $metodos_pago = ClienteMetodoPago::where('Activo', '=', 1)->get();

        $departamentos = Departamento::get();
        $municipios = Municipio::get();
        $municipio_actual = 0;
        $departamento_actual = 0;
        //  dd($cliente->Distrito);
        if ($cliente->Distrito) {
            $distritos = Distrito::where('Municipio', '=', $cliente->distrito->Municipio)->get();
            $municipio_actual = $cliente->distrito->Municipio;
            $departamento_actual = $cliente->distrito->municipio->Departamento;
        } else {
            $distritos = Distrito::get();
        }
        $aseguradoras = Aseguradora::where('Activo', 1)->get();



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
            'municipio_actual',
            'departamentos',
            'municipios',
            'distritos',
            'departamento_actual',
            'metodos_pago',
            'aseguradoras',
            'documentos'

        ));
    }

    public function getMetodoPago(Request $request)
    {
        $datosRecibidos = ClienteTarjetaCredito::where('Id', '=', $request->id_registro_metodo_pago)->first();
        return response()->json(['datosRecibidos' => $datosRecibidos]);
    }

    public function verificarCredenciales(Request $request)
    {
        $credenciales = $request->only('email', 'password');

        if (auth()->attempt($credenciales)) {
            // Las credenciales son válidas
            return response()->json(['mensaje' => '1'], 200);
        } else {
            // Las credenciales son incorrectas
            return response()->json(['mensaje' => '0'], 401);
        }
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

        $count_dui = Cliente::where('Dui', '=', $request->get('Dui'))->where('Id', '<>', $id)->count();
        $count_nit = Cliente::where('Nit', '=', $request->get('Nit'))->where('Id', '<>', $id)->count();

        if ($request->get('TipoPersona') == 1) {
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
        //$cliente->FormaPago = $request->get('FormaPago');
        $cliente->Estado = $request->get('Estado');
        $cliente->TipoPersona = $request->get('TipoPersona');
        $cliente->Genero = $request->get('Genero');
        $cliente->Comentarios = $request->get('Comentarios');
        $cliente->TipoContribuyente = $request->get('TipoContribuyente');
        $cliente->Referencia = $request->get('Referencia');
        $cliente->Distrito = $request->get('Distrito');
        $cliente->TelefonoCelular2 = $request->get('TelefonoCelular2');
        $cliente->BancoPrefencia = $request->get('BancoPrefencia');
        $cliente->CuentasDevolucionPrimas = $request->get('CuentasDevolucionPrimas');
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
        $cliente->Smartphone = $request->get('Smartphone');
        $cliente->SmartWatch = $request->get('SmartWatch');
        $cliente->DispositivosOtros = $request->get('DispositivosOtros');
        $cliente->Informarse = $request->get('Informarse');
        $cliente->EnvioInformacion = $request->get('EnvioInformacion');
        $cliente->Instagram = $request->get('Instagram');
        $cliente->TieneMascota = $request->get('TieneMascota');
        $cliente->MotivoEleccion = $request->get('MotivoEleccion');
        $cliente->PreferenciaCompra = $request->get('PreferenciaCompra');
        $cliente->AseguradoraPreferencia = $request->get('AseguradoraPreferencia');
        $cliente->Efectivo = $request->get('Efectivo');
        $cliente->TarjetaCredito = $request->get('TarjetaCredito');
        $cliente->App = $request->get('App');
        $cliente->MonederoEletronico = $request->get('MonederoEletronico');
        $cliente->CompraOtros = $request->get('CompraOtros');
        $cliente->Informacion = $request->get('Informacion');
        $cliente->update();

        return redirect('catalogo/cliente/' . $request->Id . '/edit?tab=4');

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

        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=3');
        //return back();
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

        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=3');
    }

    public function delete_contacto(Request $request)
    {
        $contacto = ClienteContactoFrecuente::findOrFail($request->Id);
        $Id = $contacto->Cliente;
        $contacto->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        return redirect('catalogo/cliente/' .  $Id . '/edit?tab=3');
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

        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=2');
    }

    public function delete_tarjeta(Request $request)
    {
        $tarjeta = ClienteTarjetaCredito::findOrFail($request->Id);
        $Id =  $tarjeta->Cliente;
        $tarjeta->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        return redirect('catalogo/cliente/' . $Id . '/edit?tab=2');
    }

    public function edit_tarjeta(Request $request)
    {
        $tarjeta = ClienteTarjetaCredito::findOrFail($request->Id);
        //$tarjeta->Cliente = $request->Cliente;
        //$tarjeta->NumeroTarjeta = $request->NumeroTarjeta;
        //$tarjeta->FechaVencimiento = $request->FechaVencimiento;
        $tarjeta->PolizaVinculada = $request->PolizaVinculada;
        //    $tarjeta->MetodoPago = $request->MetodoPago;
        $tarjeta->save();
        alert()->success('El registro ha sido creado correctamente');

        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=2');
    }


    public function agregar_documento(Request $request)
    {

        $archivo = $request->file('Archivo');

        $id = uniqid();
        $filePath =  $id . $archivo->getClientOriginalName();
        $archivo->move(public_path("documentos/cliente/"), $filePath);

        $documento = new ClienteDocumento();
        $documento->Cliente = $request->input('Cliente');
        $documento->Nombre = $filePath;
        $documento->NombreOriginal = $archivo->getClientOriginalName();
        $documento->Activo = 1;
        $documento->save();

        // Storage::disk('public')->put($filePath, file_get_contents($archivo));
        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=7');
    }


    public function eliminar_documento($id)
    {
        $documento = ClienteDocumento::findOrFail($id);
        $Id = $documento->Cliente;
        $documento->Activo = 0;
        $documento->save();

        alert()->success('El registro ha sido eliminado correctamente');
        return redirect('catalogo/cliente/' . $Id . '/edit?tab=7');

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

        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=5');

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

        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=5');
    }



    public function delete_habito(Request $request)
    {
        $habito = ClienteHabitoConsumo::findOrFail($request->Id);
        $Id = $habito->Cliente;
        $habito->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        return redirect('catalogo/cliente/' . $Id. '/edit?tab=5');
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

        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=6');
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
        alert()->success('El registro ha sido modificado correctamente');
        return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=6');
        return back();
    }

    public function delete_retroalimentacion(Request $request)
    {
        $retroalimentacion = ClienteRetroalimentacion::findOrFail($request->Id);
        $Id = $retroalimentacion->Cliente;
        $retroalimentacion->delete();
        alert()->error('El registro ha sido eliminado correctamente');
        return redirect('catalogo/cliente/' . $Id  . '/edit?tab=6');
    }


    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->update([
            'Activo' => 0,
            'Nit' => $cliente->Nit . '-',
            'Dui' => $cliente->Dui . '-',
        ]);
        alert()->info('El registro ha sido desactivado correctamente');

        return back();
    }

    public function active($id)
    {
        Cliente::findOrFail($id)->update(['Activo' => 1]);
        alert()->success('El registro ha sido activado correctamente');

        return back();
    }

    public function addCargo(Request $request)
    {
        $cargo = new ClienteContactoCargo();
        $cargo->Nombre = $request->get('Nombre');
        $cargo->Activo = '1';
        $cargo->save();
        return ClienteContactoCargo::where('Activo', '=', 1)->get();
    }

    public function addMotivo(Request $request)
    {
        $motivo = new ClienteMotivoEleccion();
        $motivo->Nombre = $request->get('Nombre');
        $motivo->Activo = '1';
        $motivo->save();
        return ClienteMotivoEleccion::where('Activo', '=', 1)->get();
    }

    public function addPreferencia(Request $request)
    {
        $preferencia = new ClientePrefereciaCompra();
        $preferencia->Nombre = $request->get('Nombre');
        $preferencia->Activo = '1';
        $preferencia->save();
        return ClientePrefereciaCompra::where('Activo', '=', 1)->get();
    }
}
