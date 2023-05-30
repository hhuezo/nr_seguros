<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\CarteraImport;
use App\Models\polizas\CarteraMensual;
use App\Models\polizas\Vida;
use App\Models\temp\TempCartera;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ValidacionCarteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polizas = Vida::get();
        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        return view('polizas.validacion_cartera.index', compact('polizas', 'meses'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        if ($request->Mes == 1) {
            $mes_evaluar = 12;
            $axo = $request->Axo - 1;
        } else {
            $mes_evaluar = $request->Mes - 1;
            $axo = $request->Axo;
        }

        $archivo = $request->Archivo;
        TempCartera::where('Usuario', '=', auth()->user()->id)->delete();
        Excel::import(new  CarteraImport, $archivo);

        $datos = \DB::select("call dateFormat(" . auth()->user()->id . ",$mes_evaluar,$axo)");


        $nuevos = TempCartera::select('Id','Dui','Nit', 'PrimerApellido','SegundoApellido','CasadaApellido','PrimerNombre','SegundoNombre','SociedadNombre','NoRefereciaCredito','Edad',DB::raw('(select count(*) from cartera_mensual where
        (cartera_mensual.Dui = temp_cartera.Dui or cartera_mensual.Nit = temp_cartera.Nit) and temp_cartera.NoRefereciaCredito = cartera_mensual.NoRefereciaCredito
         and cartera_mensual.Mes = ' . $mes_evaluar . ' and  cartera_mensual.Axo = ' . $axo . ') as conteo'))
            ->where('Usuario', '=', auth()->user()->id)
            ->having('conteo', '=', 0)
            ->get();

            return view('polizas.validacion_cartera.resultado', compact('nuevos'));
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
