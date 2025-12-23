<?php

namespace App\Http\Controllers;

use App\Models\polizas\DeudaDetalle;
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
        if (!Auth::check()) {
            return view('auth.login');
        }

        $user = Auth::user();

        if (!$user->activo) {
            Auth::logout();
            alert()->error('Usuario no válido');
            return view('auth.login');
        }

        /*SELECT
    SUM(
        COALESCE(
            (
                SELECT SUM(d.PrimaCalculada)
                FROM poliza_deuda_detalle d
                WHERE d.Deuda = pd.Id
                  AND d.Axo = 2025
                  AND d.Mes = 8
            ),
            (
                SELECT SUM(c.TotalCredito * c.Tasa)
                FROM poliza_deuda_cartera c
                WHERE c.PolizaDeuda = pd.Id
                  AND c.Axo = 2025
                  AND c.Mes = 8
            ),
            0
        )
    ) AS PrimaTotalAgosto2025
FROM poliza_deuda pd;*/


        return view('home');
    }

}
