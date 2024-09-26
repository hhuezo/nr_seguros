<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('seguridad.user.index', ['usuarios' => User::where('activo',1)->get()]);
    }


    public function create()
    {
        $roles = Role::get();
        return view('seguridad.user.create', ['roles' => $roles]);
    }

    public function rol_link(Request $request)
    {
        $user = User::findOrFail($request->get('Usuario'));
        $role = Role::findOrFail($request->get('Rol'));
        $user->assignRole($role->id);
        alert()->success('El registro ha sido agregado correctamente');
        return back();
    }

    public function rol_unlink(Request $request)
    {
        $user = User::findOrFail($request->get('Usuario'));
        $role = Role::findOrFail($request->get('Rol'));
        $user->removeRole($role->id);
        alert()->error('El registro ha sido eliminado correctamente');
        return back();
    }

    public function store(Request $request)
    {

        $count = User::where('email', '=', $request->get('email'))->count();
        if ($count > 0) {
            alert()->error('El correo ingresado ya existe');
        } else if (Str::length($request->get('password')) < 8) {
            alert()->error('La contraseña debe tener al menos 8 caracteres');
        } else {
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->password);
            $user->activo = 1;
            $user->save();
            alert()->error('El registro ha sido eliminado correctamente');
        }

        return back();
    }




    public function edit($id)
    {
        $user = User::findOrFail($id);
        $user_has_rol = $user->user_has_role;
        $roles = Role::get();
        return view('seguridad.user.edit', ['usuario' => $user, 'roles' => $roles, 'user_has_rol' => $user_has_rol]);
    }


    public function update(Request $request, $id)
    {
        $count = User::where('email', '=', $request->get('email'))->where('id', '<>', $id)->count();

        if ($count > 0) {
            alert()->error('El correo ingresado ya existe');
        } else if ($request->get('password') != "" && Str::length($request->get('password')) < 8) {
            alert()->error('La contraseña debe tener al menos 8 caracteres');
        } else {
            $user = User::findOrFail($id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            if ($request->get('password') != "") {
                $user->password = Hash::make($request->password);
            }
            $user->update();
            alert()->success('El registro ha sido agregado correctamente');
        }
        return back();
    }


    public function destroy($id)
    {
         $user = User::findOrFail($id);
         $user->activo = 0;
         $user->update();
        // $user->delete();
        alert()->error('Eliminar', 'Record delete');
        return back();
    }
}
