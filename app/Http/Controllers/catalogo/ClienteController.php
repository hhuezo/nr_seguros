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
use App\Models\catalogo\ClientePrefereciaCompra;
use App\Models\catalogo\ClienteRetroalimentacion;
use App\Models\catalogo\ClienteTarjetaCredito;
use App\Models\catalogo\Departamento;
use App\Models\catalogo\Distrito;
use App\Models\catalogo\FormaPago;
use App\Models\catalogo\Municipio;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ClienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;

        // Obtener clientes activos ordenados por Id
        $clientes = Cliente::where('Activo', 1)->orderBy('Id', 'asc')->get();

        $posicion = 0;

        if ($idRegistro > 0) {
            $indice = $clientes->search(function ($cliente) use ($idRegistro) {
                return $cliente->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('catalogo.cliente.index', compact('clientes', 'posicion'));
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
            'Dui.required' => 'El campo DUI es obligatorio',
            'Dui.min' => 'El formato de DUI es incorrecto',
            'Dui.unique' => 'El DUI ya existe en la base de datos',
            'TelefonoCelular.required' => 'El teléfono principal es obligatorio',
            'TelefonoCelular.size' => 'El teléfono Principal es incorrecto',
            'UbicacionCobro.required' => 'El Método de Pago es obligatorio',
            'UbicacionCobro.integer'  => 'El Método de Pago debe ser un valor válido',
            'Nit.required_without' => 'Debe ingresar al menos un NIT o un Pasaporte',
            'Pasaporte.required_without' => 'Debe ingresar al menos un NIT o un Pasaporte',
        ];

        // Limpiar campos
        $request->merge([
            'Dui' => $this->string_replace($request->get('Dui')),
            'Nit' => $this->string_replace($request->get('Nit')),
            'Pasaporte' => $this->string_replace($request->get('Pasaporte')),
            'TelefonoCelular' => $this->string_replace($request->get('TelefonoCelular')),
        ]);

        // Convertir checkbox a boolean
        $extranjero = $request->boolean('Extranjero'); // true si está marcado, false si no

        // Reglas base
        $rules = [
            'Nombre' => 'required',
            'DireccionCorrespondencia' => 'required|max:255',
            'TelefonoCelular' => 'required|size:9',
            'FechaVinculacion' => 'required|date',
            'Estado' => 'required|integer',
            'Genero' => 'required|integer',
            'TipoContribuyente' => 'required|integer',
            'UbicacionCobro' => 'required|integer',
        ];

        // Validación para NO extranjeros
        if (!$extranjero) {
            if ($request->get('TipoPersona') == 1) {
                $duiRules = ['required', 'min:10'];
                if ($request->get('ClienteId')) {
                    $duiRules[] = Rule::unique('cliente')->ignore($request->get('ClienteId'));
                } else {
                    $duiRules[] = 'unique:cliente';
                }
                $rules['Dui'] = $duiRules;

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
        }

        // Validación para Extranjero: al menos Nit o Pasaporte debe tener dato
        if ($extranjero) {
            $rules['Nit'] = 'required_without:Pasaporte|min:17';
            $rules['Pasaporte'] = 'required_without:Nit|max:100';
        } else {
            // Si no es extranjero y no es persona natural, Nit opcional pero válido si existe
            if ($request->get('TipoPersona') != 1 && $request->get('Nit')) {
                $nitRules = ['min:17'];
                if ($request->get('ClienteId')) {
                    $nitRules[] = Rule::unique('cliente')->ignore($request->get('ClienteId'));
                } else {
                    $nitRules[] = 'unique:cliente';
                }
                $rules['Nit'] = $nitRules;
            }
        }

        // Ejecutar validator
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true, 'message' => 'Validación exitosa']);
    }




    public function store(Request $request)
    {
        // Convertir checkbox a boolean: true si está marcado, false si no
        $extranjero = $request->boolean('Extranjero'); // maneja "on" o null

        $messages = [
            'Nombre.required' => 'El campo Nombre es obligatorio',
            'FechaNacimiento.required' => 'El campo Fecha de Nacimiento es obligatorio',
            'FechaNacimiento.date' => 'La Fecha de Nacimiento no es válida',
            'Dui.required' => 'El campo DUI es obligatorio para personas naturales',
            'Dui.min' => 'El formato de DUI es incorrecto',
            'Dui.unique' => 'El DUI ya existe en la base de datos',
            'Nit.min' => 'El formato de NIT es incorrecto',
            'Nit.unique' => 'El NIT ya existe en la base de datos',
            'Nit.required_without' => 'Debe ingresar al menos un NIT o un Pasaporte',
            'Pasaporte.required_without' => 'Debe ingresar al menos un NIT o un Pasaporte',
        ];

        // Limpiar DUI y NIT
        $request->merge([
            'Dui' => $this->string_replace($request->get('Dui')),
            'Nit' => $this->string_replace($request->get('Nit')),
        ]);

        // Construir reglas base
        $rules = [
            'Nombre' => 'required',
            'FechaNacimiento' => 'required|date',
        ];

        // Reglas condicionales
        if (!$extranjero) {
            // No es extranjero → DUI obligatorio si TipoPersona es 1
            if ($request->get('TipoPersona') == 1) {
                $duiRules = ['required', 'min:10'];
                if ($request->get('ClienteId')) {
                    $duiRules[] = Rule::unique('cliente')->ignore($request->get('ClienteId'));
                } else {
                    $duiRules[] = 'unique:cliente';
                }
                $rules['Dui'] = $duiRules;
            } else {
                if ($request->get('Nit')) {
                    $nitRules = ['min:17'];
                    if ($request->get('ClienteId')) {
                        $nitRules[] = Rule::unique('cliente')->ignore($request->get('ClienteId'));
                    } else {
                        $nitRules[] = 'unique:cliente';
                    }
                    $rules['Nit'] = $nitRules;
                }
            }
        } else {
            // Es extranjero → al menos Nit o Pasaporte debe tener dato
            $rules['Nit'] = 'required_without:Pasaporte|min:17';
            $rules['Pasaporte'] = 'required_without:Nit|max:100';
        }

        // Validar
        $request->validate($rules, $messages);









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
        if ($request->filled('Distrito')) {
            $cliente->Distrito = $request->get('Distrito');
        }
        $cliente->Comentarios = $request->get('Comentarios');
        $cliente->FechaIngreso = $time->toDateTimeString();
        $cliente->UsuarioIngreso = auth()->user()->id;
        $cliente->TelefonoCelular2 = $request->get('TelefonoCelular2');
        $cliente->BancoPrefencia = $request->get('BancoPrefencia');
        $cliente->CuentasDevolucionPrimas = $request->get('CuentasDevolucionPrimas');
        $cliente->NumeroExtrajero = $request->get('NumeroExtrajero');
        $cliente->Extranjero = $request->boolean('Extranjero') ? 1 : 0;
        $cliente->Pasaporte = $request->get('Pasaporte');

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

    public function edit(Request $request, $id)
    {
        $tab = $request->tab ?? 1;

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
            'tab',
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
            'Dui.required' => 'El campo DUI es obligatorio para personas naturales',
            'Dui.min' => 'El formato de DUI es incorrecto',
            'Dui.unique' => 'El DUI ya existe en la base de datos',
            'Nit.min' => 'El formato de NIT es incorrecto',
            'Nit.unique' => 'El NIT ya existe en la base de datos',
        ];

        $request->merge([
            'Dui' => $this->string_replace($request->get('Dui')),
            'Nit' => $this->string_replace($request->get('Nit')),
        ]);

        // Validaciones comunes
        $request->validate([
            'Nombre' => 'required',
            'FechaNacimiento' => 'required|date',
        ], $messages);

        // Validaciones condicionales
        if ($request->get('TipoPersona') == 1) {
            $request->validate([
                'Dui' => [
                    'required',
                    'min:10',
                    Rule::unique('cliente', 'Dui')->ignore($id),
                ],
            ], $messages);
        } else {
            if ($request->get('Nit')) {
                $request->validate([
                    'Nit' => [
                        'min:17',
                        Rule::unique('cliente', 'Nit')->ignore($id),
                    ],
                ], $messages);
            }
        }



        try {


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
            $cliente->Comentarios = $request->get('Comentarios');
            $cliente->TipoContribuyente = $request->get('TipoContribuyente');
            $cliente->Referencia = $request->get('Referencia');
            $cliente->Distrito = $request->get('Distrito');
            $cliente->TelefonoCelular2 = $request->get('TelefonoCelular2');
            $cliente->BancoPrefencia = $request->get('BancoPrefencia');
            $cliente->CuentasDevolucionPrimas = $request->get('CuentasDevolucionPrimas');
            $cliente->NumeroExtrajero = $request->get('NumeroExtrajero');
            $cliente->Extranjero = $request->boolean('Extranjero') ? 1 : 0;
            $cliente->Pasaporte = $request->get('Pasaporte');
            $cliente->update();

            return redirect('catalogo/cliente/' . $id . '/edit?tab=1')->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar cliente: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }



    //tab 2
    public function add_tarjeta(Request $request)
    {
        try {
            $tarjeta = new ClienteTarjetaCredito();
            $tarjeta->Cliente = $request->Cliente;
            $tarjeta->NumeroTarjeta = $request->NumeroTarjeta;
            $tarjeta->FechaVencimiento = $request->FechaVencimiento;
            $tarjeta->PolizaVinculada = $request->PolizaVinculada;
            $tarjeta->MetodoPago = $request->MetodoPago;
            $tarjeta->save();

            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=2')
                ->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar tarjeta: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=2')
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }


    public function edit_tarjeta(Request $request)
    {
        try {
            $tarjeta = ClienteTarjetaCredito::findOrFail($request->Id);
            $tarjeta->PolizaVinculada = $request->PolizaVinculada;
            $tarjeta->save();
            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=2')
                ->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar tarjeta: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=2')
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function delete_tarjeta(Request $request)
    {
        try {
            $tarjeta = ClienteTarjetaCredito::findOrFail($request->Id);
            $tarjeta->delete();
            return redirect('catalogo/cliente/' . $tarjeta->Cliente . '/edit?tab=2')
                ->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar tarjeta: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al eliminar el registro. Por favor intente nuevamente.');
        }
    }



    //tab 3
    public function add_contacto(Request $request)
    {
        try {
            $contacto = new ClienteContactoFrecuente();
            $contacto->Cliente = $request->Cliente;
            $contacto->Nombre = $request->Nombre;
            $contacto->Cargo = $request->Cargo;
            $contacto->Telefono = $request->Telefono;
            $contacto->Email = $request->Email;
            $contacto->LugarTrabajo = $request->LugarTrabajo;
            $contacto->save();
            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=3')
                ->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar contacto: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function edit_contacto(Request $request)
    {
        try {
            $contacto = ClienteContactoFrecuente::findOrFail($request->Id);
            $contacto->Cliente = $request->Cliente;
            $contacto->Nombre = $request->Nombre;
            $contacto->Cargo = $request->Cargo;
            $contacto->Telefono = $request->Telefono;
            $contacto->Email = $request->Email;
            $contacto->LugarTrabajo = $request->LugarTrabajo;
            $contacto->save();
            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=3')
                ->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar contacto: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function delete_contacto(Request $request)
    {
        try {
            $contacto = ClienteContactoFrecuente::findOrFail($request->Id);
            $contacto->delete();
            return redirect('catalogo/cliente/' . $contacto->Cliente . '/edit?tab=3')
                ->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al borrar contacto: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function addCargo(Request $request)
    {
        $cargo = new ClienteContactoCargo();
        $cargo->Nombre = $request->get('Nombre');
        $cargo->Activo = '1';
        $cargo->save();
        return ClienteContactoCargo::where('Activo', '=', 1)->get();
    }



    //tab 4
    public function red_social(Request $request)
    {
        try {
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

            return redirect('catalogo/cliente/' . $request->Id . '/edit?tab=4')
                ->with('success', 'El registro ha sido guardado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar red_social: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }


    public function addMotivo(Request $request)
    {
        try {
            $motivo = new ClienteMotivoEleccion();
            $motivo->Nombre = $request->get('Nombre');
            $motivo->Activo = '1';
            $motivo->save();

            return ClienteMotivoEleccion::where('Activo', '=', 1)->get();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el motivo: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], 500);
        }
    }



    public function addPreferencia(Request $request)
    {
        try {
            $preferencia = new ClientePrefereciaCompra();
            $preferencia->Nombre = $request->get('Nombre');
            $preferencia->Activo = '1';
            $preferencia->save();
            return ClientePrefereciaCompra::where('Activo', '=', 1)->get();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el motivo: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], 500);
        }
    }


    //tab 5
    public function add_habito(Request $request)
    {
        $request->validate([
            // Campos requeridos
            'Cliente'           => 'required|string',
            'ActividadEconomica'  => 'required|string',
            'NivelEducativo'      => 'required|string',

            // Campos requeridos y mayores a cero
            'IngresoPromedio'     => 'required|numeric|gt:0',
            'GastoMensualSeguro'  => 'required|numeric|gt:0',
        ], [
            // Mensajes personalizados para campos requeridos
            'Cliente.required'          => 'El campo Cliente es obligatorio.',
            'ActividadEconomica.required' => 'El campo Actividad Económica es obligatorio.',
            'NivelEducativo.required'     => 'El campo Nivel Educativo es obligatorio.',

            // Mensajes para campos numéricos
            'IngresoPromedio.required'    => 'El campo Ingreso Promedio es obligatorio.',
            'IngresoPromedio.numeric'     => 'El Ingreso Promedio debe ser un número válido.',
            'IngresoPromedio.gt'          => 'El Ingreso Promedio debe ser mayor que cero.',

            'GastoMensualSeguro.required' => 'El campo Gasto Mensual en Seguros es obligatorio.',
            'GastoMensualSeguro.numeric'  => 'El Gasto Mensual en Seguros debe ser un número válido.',
            'GastoMensualSeguro.gt'       => 'El Gasto Mensual en Seguros debe ser mayor que cero.',
        ]);

        try {


            $habito = new ClienteHabitoConsumo();
            $habito->Cliente = $request->Cliente;
            $habito->ActividadEconomica = $request->ActividadEconomica;
            $habito->IngresoPromedio = $request->IngresoPromedio;
            $habito->GastoMensualSeguro = $request->GastoMensualSeguro;
            $habito->NivelEducativo = $request->NivelEducativo;
            $habito->save();
            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=5')
                ->with('success', 'El registro ha sido guardado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar habito: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function edit_habito(Request $request)
    {
        $request->validate([
            // Campos requeridos
            'Cliente'           => 'required|string',
            'ActividadEconomica'  => 'required|string',
            'NivelEducativo'      => 'required|string',

            // Campos requeridos y mayores a cero
            'IngresoPromedio'     => 'required|numeric|gt:0',
            'GastoMensualSeguro'  => 'required|numeric|gt:0',
        ], [
            // Mensajes personalizados para campos requeridos
            'Cliente.required'          => 'El campo Cliente es obligatorio.',
            'ActividadEconomica.required' => 'El campo Actividad Económica es obligatorio.',
            'NivelEducativo.required'     => 'El campo Nivel Educativo es obligatorio.',

            // Mensajes para campos numéricos
            'IngresoPromedio.required'    => 'El campo Ingreso Promedio es obligatorio.',
            'IngresoPromedio.numeric'     => 'El Ingreso Promedio debe ser un número válido.',
            'IngresoPromedio.gt'          => 'El Ingreso Promedio debe ser mayor que cero.',

            'GastoMensualSeguro.required' => 'El campo Gasto Mensual en Seguros es obligatorio.',
            'GastoMensualSeguro.numeric'  => 'El Gasto Mensual en Seguros debe ser un número válido.',
            'GastoMensualSeguro.gt'       => 'El Gasto Mensual en Seguros debe ser mayor que cero.',
        ]);

        try {
            $habito = ClienteHabitoConsumo::findOrFail($request->Id);
            $habito->Cliente = $request->Cliente;
            $habito->ActividadEconomica = $request->ActividadEconomica;
            $habito->IngresoPromedio = $request->IngresoPromedio;
            $habito->GastoMensualSeguro = $request->GastoMensualSeguro;
            $habito->NivelEducativo = $request->NivelEducativo;
            $habito->save();
            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=5')
                ->with('success', 'El registro ha sido guardado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar habito: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }



    public function delete_habito(Request $request)
    {
        try {
            $habito = ClienteHabitoConsumo::findOrFail($request->Id);
            $habito->delete();
            return redirect('catalogo/cliente/' . $habito->Cliente . '/edit?tab=5')
                ->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar habito: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }



    //tab 6
    public function add_retroalimentacion(Request $request)
    {
        $validatedData = $request->validate([
            'Cliente' => 'required|integer',
            'Producto' => 'required|string|max:255',
            'ValoresAgregados' => 'required|string',
            'Competidores' => 'required|string',
            'Referidos' => 'required|string',
            'QueQuisiera' => 'required|string',
            'ServicioCliente' => 'required|string',
        ], [
            'Cliente.required' => 'El campo Cliente es obligatorio.',
            'Cliente.integer' => 'El ID del cliente debe ser un número entero.',

            'Producto.required' => 'El campo Producto es obligatorio.',
            'Producto.string' => 'El Producto debe ser texto.',
            'Producto.max' => 'El Producto no debe exceder los 255 caracteres.',

            'ValoresAgregados.required' => 'Los Valores Agregados son información obligatoria.',
            'ValoresAgregados.string' => 'Los Valores Agregados deben ser texto.',

            'Competidores.required' => 'El campo Competidores es obligatorio.',
            'Competidores.string' => 'Los Competidores deben ser texto.',

            'Referidos.required' => 'El campo Referidos es obligatorio.',
            'Referidos.string' => 'Los Referidos deben ser texto.',

            'QueQuisiera.required' => 'El campo "Qué quisiera" es obligatorio.',
            'QueQuisiera.string' => 'El campo "Qué quisiera" debe ser texto.',

            'ServicioCliente.required' => 'La valoración del Servicio al Cliente es obligatoria.',
            'ServicioCliente.string' => 'El Servicio al Cliente debe ser texto.',
        ]);
        try {
            $retroalimentacion = new ClienteRetroalimentacion();
            $retroalimentacion->Cliente = $request->Cliente;
            $retroalimentacion->Producto = $request->Producto;
            $retroalimentacion->ValoresAgregados = $request->ValoresAgregados;
            $retroalimentacion->Competidores = $request->Competidores;
            $retroalimentacion->Referidos = $request->Referidos;
            $retroalimentacion->QueQuisiera = $request->QueQuisiera;
            $retroalimentacion->ServicioCliente = $request->ServicioCliente;
            $retroalimentacion->save();

            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=6')
                ->with('success', 'El registro ha sido guardado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar habito: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function edit_retroalimentacion(Request $request)
    {
        $validatedData = $request->validate([
            'Cliente' => 'required|integer',
            'Producto' => 'required|string|max:255',
            'ValoresAgregados' => 'required|string',
            'Competidores' => 'required|string',
            'Referidos' => 'required|string',
            'QueQuisiera' => 'required|string',
            'ServicioCliente' => 'required|string',
        ], [
            'Cliente.required' => 'El campo Cliente es obligatorio.',
            'Cliente.integer' => 'El ID del cliente debe ser un número entero.',

            'Producto.required' => 'El campo Producto es obligatorio.',
            'Producto.string' => 'El Producto debe ser texto.',
            'Producto.max' => 'El Producto no debe exceder los 255 caracteres.',

            'ValoresAgregados.required' => 'Los Valores Agregados son información obligatoria.',
            'ValoresAgregados.string' => 'Los Valores Agregados deben ser texto.',

            'Competidores.required' => 'El campo Competidores es obligatorio.',
            'Competidores.string' => 'Los Competidores deben ser texto.',

            'Referidos.required' => 'El campo Referidos es obligatorio.',
            'Referidos.string' => 'Los Referidos deben ser texto.',

            'QueQuisiera.required' => 'El campo "Qué quisiera" es obligatorio.',
            'QueQuisiera.string' => 'El campo "Qué quisiera" debe ser texto.',

            'ServicioCliente.required' => 'La valoración del Servicio al Cliente es obligatoria.',
            'ServicioCliente.string' => 'El Servicio al Cliente debe ser texto.',
        ]);
        try {
            $retroalimentacion = ClienteRetroalimentacion::findOrFail($request->Id);
            $retroalimentacion->Cliente = $request->Cliente;
            $retroalimentacion->Producto = $request->Producto;
            $retroalimentacion->ValoresAgregados = $request->ValoresAgregados;
            $retroalimentacion->Competidores = $request->Competidores;
            $retroalimentacion->Referidos = $request->Referidos;
            $retroalimentacion->QueQuisiera = $request->QueQuisiera;
            $retroalimentacion->ServicioCliente = $request->ServicioCliente;
            $retroalimentacion->update();
            return redirect('catalogo/cliente/' . $request->Cliente . '/edit?tab=6')
                ->with('success', 'El registro ha sido guardado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar habito: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function delete_retroalimentacion(Request $request)
    {
        try {
            $retroalimentacion = ClienteRetroalimentacion::findOrFail($request->Id);
            $retroalimentacion->delete();
            return redirect('catalogo/cliente/' . $retroalimentacion->Cliente . '/edit?tab=6')
                ->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar habito: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al eliminar el registro. Por favor intente nuevamente.');
        }
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
}
