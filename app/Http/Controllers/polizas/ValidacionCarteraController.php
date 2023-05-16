<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\CarteraImport;
use App\Models\temp\TempCartera;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ValidacionCarteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('polizas.validacion_cartera.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $archivo = $request->Archivo;
        TempCartera::where('Usuario','=',auth()->user()->id)->delete();
        Excel::import(new  CarteraImport, $archivo);

        $datos = \DB::select("select dateFormat(".auth()->user()->id.")");

       // $temp_cartera = TempCartera::where('Usuario', '=', auth()->user()->id)->get();


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
