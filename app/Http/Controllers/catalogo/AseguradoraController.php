<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\AseguradoraCargo;
use App\Models\catalogo\AseguradoraContacto;
use App\Models\catalogo\AseguradoraDocumento;
use App\Models\catalogo\Departamento;
use App\Models\catalogo\Distrito;
use App\Models\catalogo\Municipio;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\TipoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AseguradoraController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aseguradora = Aseguradora::where('Activo', '=', 1)->get();
        return view('catalogo.aseguradora.index', compact('aseguradora'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        session(['tab1' => '1']);
        $departamentos = Departamento::get();
        $municipios = Municipio::get();
        $distritos = Distrito::get();
        $ultimoId = Aseguradora::where('Activo', '=', 1)->orderByDesc('Id')->first();
        if (!$ultimoId) {
            $ultimoId = 1;
        }
        $tipo_contribuyente = TipoContribuyente::get();
        return view('catalogo.aseguradora.create', compact('ultimoId', 'tipo_contribuyente', 'departamentos', 'municipios', 'distritos'));
    }


    public function agregar_documento(Request $request)
    {
        $archivo = $request->file('Archivo'); 
        
        $id = uniqid();
        $filePath =  $id . $archivo->getClientOriginalName();
        $archivo->move(public_path("documentos/aseguradoras/"), $filePath);


        $documento = new AseguradoraDocumento();
        $documento->Aseguradora = $request->input('Aseguradora');
        $documento->Nombre = $filePath;
        $documento->NombreOriginal = $archivo->getClientOriginalName();
        $documento->Activo = 1;
        $documento->save();

        $filePath = 'documentos/aseguradoras/' . $archivo->getClientOriginalName();

        alert()->success('El registro ha sido creado correctamente');
        session(['tab1' => '4']);
        return back();
    }


    public function eliminar_documento($id)
    {
        $documento = AseguradoraDocumento::findOrFail($id);
        $documento->Activo = 0;
        $documento->save();

        alert()->success('El registro ha sido eliminado correctamente');
        session(['tab1' => '4']);
        return back();
    }




    public function store(Request $request)
    {
        $nombre = Aseguradora::where('Nombre','=',$request->Nombre)->where('Activo','=',1)->first();
        if($nombre){
            $messages = [
                'Nombre.required' => 'El campo nombre es requerido',
                'Nit.required' => 'El campo NIT es requerido',
                'Nit.unique' => 'El Nit ya existe',
            ];
    
            $request->validate([
                'Nombre' => 'required:aseguradora',
                'Nit' => 'required|unique:aseguradora',
            ], $messages);
        }else{

            $messages = [
                'Nombre.required' => 'El campo nombre es requerido',
                'Nombre.unique' => 'El nombre ya existe',
                'Nit.required' => 'El campo NIT es requerido',
                'Nit.unique' => 'El Nit ya existe',
            ];
    
            $request->validate([
                'Nombre' => 'required|unique:aseguradora',
                'Nit' => 'required|unique:aseguradora',
            ], $messages);
        }
        

        $max = Aseguradora::max('Codigo');

        $aseguradora = new Aseguradora();
        $aseguradora->Nombre = $request->Nombre;
        $aseguradora->Codigo = $max + 1;
        $aseguradora->Nit = $request->Nit;
        $aseguradora->RegistroFiscal = $request->RegistroFiscal;
        $aseguradora->Abreviatura = $request->Abreviatura;
        $aseguradora->FechaVinculacion = $request->FechaVinculacion;
        $aseguradora->TipoContribuyente = $request->TipoContribuyente;
        $aseguradora->PaginaWeb = $request->PaginaWeb;
        $aseguradora->FechaConstitucion = $request->FechaConstitucion;
        $aseguradora->Direccion = $request->Direccion;
        $aseguradora->TelefonoFijo = $request->TelefonoFijo;
        $aseguradora->TelefonoWhatsapp = $request->TelefonoWhatsapp;
        $aseguradora->Distrito = $request->Distrito;
        $aseguradora->Activo = 1;
        $aseguradora->save();

        session(['tab1' => '1']);

        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/aseguradoras/' . $aseguradora->Id . '/edit');
        //return Redirect::to('catalogo/aseguradoras/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $aseguradora = Aseguradora::findOrFail($id);
        $tipo_contribuyente = TipoContribuyente::get();
        $contactos = AseguradoraContacto::where('Aseguradora', '=', $id)->get();
        $cargos = AseguradoraCargo::where('Activo', '=', 1)->get();
        $tipos_poliza = TipoPoliza::get();
        $departamentos = Departamento::get();
        $municipios = Municipio::get();
        $municipio_actual = 0;
        $departamento_actual = 0;
        $documentos = AseguradoraDocumento::where('Aseguradora', $id)->where('Activo',1)->get();
        //  dd($cliente->Distrito);
        if ($aseguradora->Distrito) {
            $distritos = Distrito::where('Municipio', '=', $aseguradora->distrito->Municipio)->get();
            $municipio_actual = $aseguradora->distrito->Municipio;
            $departamento_actual = $aseguradora->distrito->municipio->Departamento;
        } else {
            $distritos = Distrito::get();
        }
        $necesidades_proteccion = NecesidadProteccion::where('TipoPoliza', '=', 1)->get();
        $necesidades_proteccion_actual =  $aseguradora->aseguradora_has_necesidad;
        if (!session('tab1')) {
            session(['tab1' => '1']);
        }
        return view('catalogo/aseguradora/edit', compact(
            'municipio_actual',
            'departamentos',
            'municipios',
            'distritos',
            'departamento_actual',
            'aseguradora',
            'tipo_contribuyente',
            'contactos',
            'cargos',
            'tipos_poliza',
            'necesidades_proteccion_actual',
            'necesidades_proteccion',
            'documentos'
        ));
    }

    public function update(Request $request, $id)
    {

        $count_nombre = Aseguradora::where('Nombre', '=', $request->Nombre)->where('Id', '<>', $id)->count();
        $count_nit = Aseguradora::where('Nit', '=', $request->Nit)->where('Id', '<>', $id)->count();

        $messages = [
            'Nombre.required' => 'El campo nombre es requerido',
            'Nombre.unique' => 'El nombre ya existe',
            'Nit.required' => 'El campo NIT es requerido',
            'Nit.unique' => 'El Nit ya existe',
        ];

        if ($count_nombre > 0) {
            $request->validate([
                'Nombre' => 'required|unique:aseguradora',
            ], $messages);
        }

        if ($count_nit > 0) {
            $request->validate([
                'Nit' => 'required|unique:aseguradora',
            ], $messages);
        }


        $aseguradora = Aseguradora::findOrFail($id);
        $aseguradora->Nombre = $request->Nombre;
        $aseguradora->Nit = $request->Nit;
        $aseguradora->RegistroFiscal = $request->RegistroFiscal;
        $aseguradora->Abreviatura = $request->Abreviatura;
        $aseguradora->FechaVinculacion = $request->FechaVinculacion;
        $aseguradora->TipoContribuyente = $request->TipoContribuyente;
        $aseguradora->PaginaWeb = $request->PaginaWeb;
        $aseguradora->FechaConstitucion = $request->FechaConstitucion;
        $aseguradora->Direccion = $request->Direccion;
        $aseguradora->TelefonoFijo = $request->TelefonoFijo;
        $aseguradora->TelefonoWhatsapp = $request->TelefonoWhatsapp;
        $aseguradora->Distrito = $request->Distrito;
        $aseguradora->update();
        session(['tab1' => '1']);
        alert()->success('El registro ha sido creado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras/' . $id . 'edit');
    }

    public function destroy($id)
    {
        $aseguradora = Aseguradora::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras');
    }

    public function addCargo(Request $request)
    {
        $cargo = new AseguradoraCargo();
        $cargo->Nombre = $request->get('Nombre');
        $cargo->Activo = '1';
        $cargo->save();
        return AseguradoraCargo::where('Activo', '=', 1)->get();
    }


    public function add_contacto(Request $request)
    {
        $contacto = new AseguradoraContacto();
        $contacto->Aseguradora = $request->Aseguradora;
        $contacto->Nombre = $request->Nombre;
        $contacto->Cargo = $request->Cargo;
        $contacto->Telefono = $request->Telefono;
        $contacto->Email = $request->Email;
        $contacto->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab1' => '2']);
        return back();
    }

    public function edit_contacto(Request $request)
    {
        $contacto = AseguradoraContacto::findOrFail($request->Id);
        $contacto->Aseguradora = $request->Aseguradora;
        $contacto->Nombre = $request->Nombre;
        $contacto->Cargo = $request->Cargo;
        $contacto->Telefono = $request->Telefono;
        $contacto->Email = $request->Email;
        $contacto->save();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab1' => '2']);
        return back();
    }

    public function delete_contacto(Request $request)
    {
        $contacto = AseguradoraContacto::findOrFail($request->Id);
        $contacto->delete();
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab1' => '2']);
        return back();
    }

    public function attach_necesidad_proteccion(Request $request)
    {
        $aseguradora = Aseguradora::findOrFail($request->aseguradora_id);
        $aseguradora->aseguradora_has_necesidad()->attach($request->necesidad_proteccion_id);
        alert()->success('El registro ha sido agregado correctamente');

        session(['tab1' => '3']);
        return back();
    }

    public function detach_necesidad_proteccion(Request $request)
    {
        $aseguradora = Aseguradora::findOrFail($request->aseguradora_id);
        $aseguradora->aseguradora_has_necesidad()->detach($request->necesidad_proteccion_id);
        alert()->info('El registro ha sido eliminado correctamente');

        session(['tab1' => '3']);
        return back();
    }

    public function get_necesidad($id)
    {
        return NecesidadProteccion::where('TipoPoliza', '=', $id)->get();
    }
}
