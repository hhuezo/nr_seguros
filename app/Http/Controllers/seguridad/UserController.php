<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;

        $roles = Role::get();
        $usuarios = User::orderBy('id', 'asc')->get();


        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $usuarios->search(function ($u) use ($idRegistro) {
                return $u->id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('seguridad.user.index', compact('usuarios', 'roles', 'posicion'));
    }



    public function create()
    {
        $roles = Role::get();
        return view('seguridad.user.create', ['roles' => $roles]);
    }

    public function rol_link(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'role_id' => 'required|exists:roles,id'
            ]);

            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);

            if ($user->hasRole($role->name)) {
                $user->removeRole($role->name);
                return response()->json([
                    'success' => true,
                    'message' => 'Rol removido correctamente',
                    'action' => 'removed'
                ]);
            }

            $user->assignRole($role->name);
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

    public function active($id)
    {
        try {
            $usuario = User::findOrFail($id);

            $usuario->activo = $usuario->activo == 1 ? 0 : 1;
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => $usuario->activo == 1 ? 'Usuario activado' : 'Usuario desactivado',
                'nuevo_estado' => $usuario->activo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $customMessages = [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no debe exceder los 255 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingrese un correo electrónico válido',
            'email.max' => 'El correo no debe exceder los 255 caracteres',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'role_id.required' => 'Debe seleccionar un rol',
            'role_id.min' => 'Seleccione un rol válido',
        ];

        // Validación fuera del try-catch para aprovechar las redirecciones automáticas
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|min:1|exists:roles,id',
        ], $customMessages);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'activo' => 1,
            ]);

            $role = Role::findOrFail($validated['role_id']);
            $user->assignRole($role->name);

            DB::commit();

            alert()->success('Usuario registrado correctamente');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();

            // if (config('app.debug')) {
            //     dd($e->getMessage(), $e->getTrace());
            // }

            alert()->error('Error al registrar el usuario', 'Ocurrió un error inesperado');
            return back()->withInput();
        }
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
