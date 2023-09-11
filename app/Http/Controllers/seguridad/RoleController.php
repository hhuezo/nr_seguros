<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
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
        $role = new Role;
        $role->name = $request->get('name');
        $role->guard_name = "web";
        $role->save();
        alert()->success('El registro ha sido agregado correctamente');
        return back();
    }
    public function show($id)
    {
        return view("seguridad.role.show", ["role" => Role::findOrFail($id)]);
    }
    public function edit($id)
    {
        $rol =  Role::findOrFail($id);
        $permisos = Permission::get();
        $permisos_actuales = $rol->role_has_permissions;
        //  dd($permisos_actuales);
        return view("seguridad.role.edit", ["role" => Role::findOrFail($id), "permisos" => $permisos, "permisos_actuales" => $permisos_actuales]);
    }
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->get('name');
        $role->update();
        alert()->info('El registro ha sido modificado correctamente');
        return back();
    }
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }

    public function permission_link(Request $request)
    {
        $role = ModelsRole::findOrFail($request->get('Rol'));
        $permiso = Permission::findOrFail($request->get('Permiso'));
        $role->givePermissionTo($permiso->name);

        alert()->success('El registro ha sido agregado correctamente');
        return back();
    }

    public function permission_unlink(Request $request)
    {
        $role = ModelsRole::findOrFail($request->get('Rol'));
        $permiso = Permission::findOrFail($request->get('Permiso'));
        $role->revokePermissionTo($permiso->name);

        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }
}
