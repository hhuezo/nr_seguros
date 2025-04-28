<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perfiles = Perfil::where('Activo', 1)->get();
        $aseguradoras = Aseguradora::where('Activo', 1)->get();
        return view('catalogo.perfiles.index', compact('perfiles', 'aseguradoras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aseguradoras = Aseguradora::where('Activo', 1)->get();
        return view('catalogo.perfiles.create', compact('aseguradoras'));
    }


    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'Codigo' => 'required|unique:perfiles,Codigo',
            'Descripcion' => 'required',
            'Aseguradora' => 'required',
        ], [
            'Codigo.required' => 'El campo Código es obligatorio.',
            'Descripcion.required' => 'El campo Descripción es obligatorio.',
            'Aseguradora.required' => 'El campo Aseguradora es obligatorio.',
        ]);

        try {
            // Si pasa la validación, guardamos el nuevo perfil
            $perfiles = new Perfil();
            $perfiles->Codigo = $request->Codigo;
            $perfiles->Descripcion = $request->Descripcion;
            $perfiles->Aseguradora = $request->Aseguradora;
            $perfiles->PagoAutomatico = $request->PagoAutomatico ?? 0;
            $perfiles->DeclaracionJurada = $request->DeclaracionJurada ?? 0;
            $perfiles->save();

            // Redirigir con mensaje de éxito
            return back()->with('success', 'Perfil creado exitosamente.');
        } catch (\Exception $e) {
            // Capturar el error y registrar el detalle
            Log::error('Error al crear perfil: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redirigir con mensaje de error
            return back()->with('error', 'Ocurrió un error al crear el perfil. Por favor, intente nuevamente.');
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $perfil = Perfil::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo', 1)->get();
        return view('catalogo.perfiles.edit', compact('perfil', 'aseguradoras'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'Codigo' => 'required|unique:perfiles,Codigo,' . $id,
            'Descripcion' => 'required',
            'Aseguradora' => 'required',
        ], [
            'Codigo.required' => 'El campo Código es obligatorio.',
            'Descripcion.required' => 'El campo Descripción es obligatorio.',
            'Aseguradora.required' => 'El campo Aseguradora es obligatorio.',
        ]);

        try {
            $perfiles = Perfil::findOrFail($id);
            $perfiles->Descripcion = $request->get('Descripcion');
            $perfiles->Aseguradora = $request->get('Aseguradora');
            $perfiles->PagoAutomatico = $request->get('PagoAutomatico');
            $perfiles->DeclaracionJurada = $request->get('DeclaracionJurada');
            $perfiles->update();

            // Redirigir con mensaje de éxito
            return back()->with('success', 'Perfil modificado exitosamente.');
        } catch (\Exception $e) {
            // Capturar el error y registrar el detalle
            Log::error('Error al guardar perfil: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redirigir con mensaje de error
            return back()->with('error', 'Ocurrió un error al guardar el perfil. Por favor, intente nuevamente.');
        }
    }


    public function destroy($id)
    {
        try {
            $perfiles = Perfil::findOrFail($id);
            $perfiles->Activo = 0;
            $perfiles->update();

            // Redirigir con mensaje de éxito
            return back()->with('success', 'Perfil eliminado exitosamente.');
        } catch (\Exception $e) {
            // Capturar el error y registrar el detalle
            Log::error('Error al eliminar perfil: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            // Redirigir con mensaje de error
            return back()->with('error', 'Ocurrió un error al guardar el perfil. Por favor, intente nuevamente.');
        }
    }
}
