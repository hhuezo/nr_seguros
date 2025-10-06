<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaComercialFormRequest;
use App\Models\catalogo\AreaComercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AreaComercialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;
        $area_comercial = AreaComercial::where('Activo', 1)->orderBy('Id', 'asc')->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $area_comercial->search(function ($a) use ($idRegistro) {
                return $a->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('catalogo.area_comercial.index', compact('area_comercial', 'posicion'));
    }


    public function create()
    {
        return view('catalogo.area_comercial.create');
    }

    public function store(AreaComercialFormRequest $request)
    {
        $area_comercial = new AreaComercial();
        $area_comercial->Nombre = $request->Nombre;
        $area_comercial->Activo = 1;
        $area_comercial->save();


        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/area_comercial?idRegistro=' . $area_comercial->Id);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $area_comercial = AreaComercial::findOrFail($id);
        return view('catalogo.area_comercial.edit', compact('area_comercial'));
    }


    public function update(AreaComercialFormRequest $request, $id)
    {
        $area_comercial = AreaComercial::findOrFail($id);
        $area_comercial->Nombre = $request->Nombre;
        $area_comercial->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/area_comercial?idRegistro=' . $area_comercial->Id);
    }

    public function destroy($id)
    {
        $area_comercial = AreaComercial::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
