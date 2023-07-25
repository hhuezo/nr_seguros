<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\AseguradoraCargo;
use App\Models\catalogo\AseguradoraContacto;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\TipoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AseguradoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aseguradora = Aseguradora::all();
        return view('catalogo.aseguradora.index', compact('aseguradora'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo_contribuyente = TipoContribuyente::get();
        return view('catalogo.aseguradora.create', compact('tipo_contribuyente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $contactos = AseguradoraContacto::where('Aseguradora','=',$id)->get();
        $cargos = AseguradoraCargo::where('Activo','=',1)->get();
        $tipos_poliza = TipoPoliza::get();
        $tipos_poliza_actual =  $aseguradora->aseguradora_has_tipo_poliza;
        if(!session('tab1'))
        {
            session(['tab1' => '1']);
        }
        return view('catalogo/aseguradora/edit', compact('aseguradora','tipo_contribuyente','contactos',
        'cargos','tipos_poliza','tipos_poliza_actual'));
    }

    public function update(Request $request, $id)
    {

        $count_nombre = Aseguradora::where('Nombre','=',$request->Nombre)->where('Id','<>',$id)->count();
        $count_nit = Aseguradora::where('Nit','=',$request->Nit)->where('Id','<>',$id)->count();

        $messages = [
            'Nombre.required' => 'El campo nombre es requerido',
            'Nombre.unique' => 'El nombre ya existe',
            'Nit.required' => 'El campo NIT es requerido',
            'Nit.unique' => 'El Nit ya existe',
        ];

        if($count_nombre > 0)
        {
            $request->validate([   
                'Nombre' => 'required|unique:aseguradora',               
            ], $messages);
        }

        if($count_nit > 0)
        {
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

    public function attach_tipo_poliza(Request $request)
    {
        $aseguradora = Aseguradora::findOrFail($request->aseguradora_id);
        $aseguradora->aseguradora_has_tipo_poliza()->attach($request->tipo_poliza_id);
        alert()->success('El registro ha sido agregado correctamente');

        session(['tab1' => '3']);
        return back();
    }

    public function detach_tipo_poliza(Request $request)
    {
        $aseguradora = Aseguradora::findOrFail($request->aseguradora_id);
        $aseguradora->aseguradora_has_tipo_poliza()->detach($request->tipo_poliza_id);
        alert()->info('El registro ha sido eliminado correctamente');

        session(['tab1' => '3']);
        return back();
    }

    
}
