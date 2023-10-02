<?php

namespace App\Http\Controllers;

use App\Models\polizas\ViewControlPrimasGeneral;
use Illuminate\Http\Request;


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
    public function index()
    {
        return view('home');
    }

    public function getPrimaGeneral(Request $request)
    {
        $datosRecibidos = ViewControlPrimasGeneral::where('RepeticionRegistro', '=', 1)->where('FechaInicioDetalle', '>=', $request->FechaInicioDetalle)->where('FechaFinalDetalle', '<=',  $request->FechaFinalDetalle)->get();
        if ($datosRecibidos->count()>0) {
            return response()->json(['datosRecibidos' => $datosRecibidos], 200);
        } else {
            return response()->json(['datosRecibidos' => $datosRecibidos], 404);
        }
    }

}
