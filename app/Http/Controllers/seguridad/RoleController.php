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
        try {
            // Validar antes de crear
            $request->validate([
                'name' => 'required|unique:roles,name,' . $id,
            ], [
                'name.required' => 'El campo rol es obligatorio.',
                'name.unique' => 'El rol ya existe en el sistema.',
            ]);

            $role = Role::findOrFail($id);
            $role->name = $request->get('name');
            $role->update();

            // Si la petición espera JSON, devolver JSON
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'El registro ha sido modificado correctamente'
                ]);
            }

            return back()->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error al guardar rol: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.'
                ], 500);
            }

            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function getPermissions($id)
    {
        try {
            $role = ModelsRole::findOrFail($id);
            // Obtener todos los permisos y cargar su relación con PermissionType
            $permissions = Permission::with('type')->get()->map(function($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'type_name' => $permission->type ? $permission->type->name : 'Otros' // Asignar 'Otros' si no tiene tipo
                ];
            });

            // Agrupar por nombre del tipo
            $groupedPermissions = $permissions->groupBy('type_name');

            $rolePermissions = $role->permissions->pluck('id')->toArray();

            return response()->json([
                'success' => true,
                'permissions' => $groupedPermissions,
                'rolePermissions' => $rolePermissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los permisos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Devuelve el HTML del body del drawer de permisos (vista Blade).
     * Usado por el drawer para cargar el listado sin armar HTML en JS.
     */
    public function getPermissionsHtml($id)
    {
        try {
            $role = ModelsRole::findOrFail($id);
            $permissions = Permission::with('type')->get()->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'type_name' => $permission->type ? $permission->type->name : 'Otros',
                ];
            });
            $groupedPermissions = $permissions->groupBy('type_name');
            $rolePermissions = $role->permissions->pluck('id')->toArray();

            $html = view('seguridad.role.permissions_drawer_body', [
                'groupedPermissions' => $groupedPermissions,
                'rolePermissions' => $rolePermissions,
                'roleId' => (int) $id,
            ])->render();

            return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
        } catch (\Exception $e) {
            return response('<p class="text-center text-danger">Error al cargar los permisos</p>', 500)
                ->header('Content-Type', 'text/html; charset=UTF-8');
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
                    'message' => 'Permiso removido correctamente',
                    'action' => 'removed'
                ]);
            }

            $role->givePermissionTo($permission->name);
            return response()->json([
                'success' => true,
                'message' => 'Permiso asignado correctamente',
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
