<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\PermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $permissionTypes = PermissionType::get();
            return view('seguridad.permission_type.index', compact('permissionTypes'));
        }
    }

    public function create()
    {
        return view("seguridad.permission_type.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permission_types,name',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique' => 'El tipo de permiso ya existe.',
        ]);

        try {
            $permissionType = new PermissionType();
            $permissionType->name = $request->get('name');
            $permissionType->active = 1; // Default active
            $permissionType->save();

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar tipo de permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function edit($id)
    {
        return view("seguridad.permission_type.edit", ["permissionType" => PermissionType::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permission_types,name,' . $id,
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique' => 'El tipo de permiso ya existe.',
        ]);

        try {
            $permissionType = PermissionType::findOrFail($id);
            $permissionType->name = $request->get('name');
            $permissionType->update();

            return back()->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar tipo de permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Ocurrió un error al actualizar el registro. Por favor intente nuevamente.');
        }
    }

    public function destroy($id)
    {
        try {
            $permissionType = PermissionType::findOrFail($id);
            $permissionType->delete();

            return back()->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar tipo de permiso: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()
                ->with('error', 'Ocurrió un error al eliminar el registro. Por favor intente nuevamente.');
        }
    }
}
