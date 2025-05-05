<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission as ModelPermission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        if ($request) {
            $permissions = Permission::get();
            return view('seguridad.permission.index', compact('permissions'));
        }
    }
    public function create()
    {

        return view("seguridad.permission.create");
    }

    public function store(Request $request)
    {
        // Validar antes de crear
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ], [
            'name.required' => 'El campo permiso es obligatorio.',
            'name.unique' => 'El permiso ya existe en el sistema.',
        ]);
        try {

            // Si pasa la validación, se crea el permiso
            $permission = ModelPermission::create(['name' => $request->name]);

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function show($id)
    {
        return view("seguridad.permission.show", ["permission" => Permission::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("seguridad.permission.edit", ["permission" => Permission::findOrFail($id)]);
    }


    public function update(Request $request, $id)
    {
        // Validar antes de crear
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ], [
            'name.required' => 'El campo permiso es obligatorio.',
            'name.unique' => 'El permiso ya existe en el sistema.',
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->name = $request->get('name');
            $permission->update();

            return back()->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }


    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return back()->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar permiso: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al eliminar el registro. Por favor intente nuevamente.');
        }
    }
}
