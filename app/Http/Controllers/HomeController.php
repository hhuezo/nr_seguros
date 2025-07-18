<?php

namespace App\Http\Controllers;

use App\Models\polizas\ViewControlPrimasGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function redirectToLogin()
    {
        return redirect('login');
    }

    public function index()
    {
        if (!Auth::user()) {
            return view('login.index');
        } else {
            $user = Auth::user();

            if ($user->activo == 0) {
                Auth::logout();
                alert()->error('Usuario no valido');
                return view('auth.login');
            }else{
                return view('home');
            }
        }

        //return view('home');
    }


}
