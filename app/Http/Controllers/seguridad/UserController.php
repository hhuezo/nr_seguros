<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('seguridad.user.index', ['usuarios' => User::get()]);
    }


    public function create()
    {
        $roles = Role::get();
        return view('seguridad.user.create', ['roles' => $roles]);
    }

    public function link(Request $request)
    {
        $user = User::findOrFail($request->get('user'));
        $role = Role::findOrFail($request->get('role'));
        $user->assignRole($role->id);
        Alert::success('', 'Record saved');
        return back();
    }

    public function unlink(Request $request)
    {
        $user = User::findOrFail($request->get('user'));
        $role = Role::findOrFail($request->get('role'));
        $user->removeRole($role->id);
        Alert::success('', 'Record saved');
        return back();
    }

    public function store(Request $request)
    {
        $count = User::where('email', '=', $request->get('email'))->count();

        if ($count > 0) {
            Alert::error('', 'the mail already exists');
        } else if (Str::length($request->get('password')) < 8) {
            Alert::error('', 'password must be at least 8 characters');
        } else {
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->password);
            $user->save();
        }
        return back();
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $current_roles = $user->user_has_role;
        $roles = Role::get();
        return view('seguridad.user.edit', ['user' => $user,'roles' => $roles,'current_roles' => $current_roles]);
    }


    public function update(Request $request, $id)
    {
        $count = User::where('email', '=', $request->get('email'))->where('id','<>',$id)->count();

        if ($count > 0) {
            Alert::error('', 'the mail already exists');
        } else if (Str::length($request->get('password')) < 8) {
            Alert::error('', 'password must be at least 8 characters');
        } else {
            $user = User::findOrFail($id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            if ($request->get('password') != "") {
                $user->password = Hash::make($request->password);
            }
            $user->update();
            Alert::success('', 'Record saved');
        }
        return back();
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Alert::error('', 'Record delete');
        return back();
    }
}
