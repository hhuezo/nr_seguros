<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role as ModelsRole;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        if ($request) {
            $roles = Role::get();
            return view('seguridad.role.index', ["roles" => $roles]);
        }
    }
    public function create()
    {

        return view("seguridad.role.create");
    }
    public function store(Request $request)
    {
        // Validar antes de crear
        $request->validate([
            'name' => 'required|unique:roles,name',
        ], [
            'name.required' => 'El campo rol es obligatorio.',
            'name.unique' => 'El rol ya existe en el sistema.',
        ]);

        try {
            $role = new Role;
            $role->name = $request->get('name');
            $role->guard_name = "web";
            $role->save();
            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar rol: ' . $e->getMessage(), [
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
        return view("seguridad.role.show", ["role" => Role::findOrFail($id)]);
    }
    public function edit($id)
    {
        $role =  ModelsRole::findOrFail($id);
        $permisos = Permission::get();
        $permisos_actuales = $role->permissions->pluck('id')->toArray();
        //  dd($permisos_actuales);
        return view("seguridad.role.edit", ["role" => $role, "permisos" => $permisos, "permisos_actuales" => $permisos_actuales]);
    }
    public function update(Request $request, $id)
    {
        // Validar antes de crear
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ], [
            'name.required' => 'El campo rol es obligatorio.',
            'name.unique' => 'El rol ya existe en el sistema.',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->name = $request->get('name');
            $role->update();
            return back()->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar rol: ' . $e->getMessage(), [
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
            $role = Role::findOrFail($id);
            $role->delete();
            return back()->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar rol: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al eliminar el registro. Por favor intente nuevamente.');
        }
    }


    public function permission_link(Request $request)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'role_id' => 'required|exists:roles,id'
        ]);


       try {


            $permission = Permission::findOrFail($request->permission_id);
            $role = ModelsRole::findOrFail($request->role_id);


            if ($role->hasPermissionTo($permission->name)) {
                $role->revokePermissionTo($permission->name);
                return response()->json([
                    'success' => true,
                    'message' => 'Rol removido correctamente',
                    'action' => 'removed'
                ]);
            }

            $role->givePermissionTo($permission->name);
            return response()->json([
                'success' => true,
                'message' => 'Rol asignado correctamente',
                'action' => 'assigned'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function permission_link(Request $request)
    // {
    //     $role = ModelsRole::findOrFail($request->get('Rol'));
    //     $permiso = Permission::findOrFail($request->get('Permiso'));
    //     $role->givePermissionTo($permiso->name);

    //     alert()->success('El registro ha sido agregado correctamente');
    //     return back();
    // }

    public function permission_unlink(Request $request)
    {
        $role = ModelsRole::findOrFail($request->get('Rol'));
        $permiso = Permission::findOrFail($request->get('Permiso'));
        $role->revokePermissionTo($permiso->name);

        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }
}
