<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoCarteraFormRequest;
use App\Models\catalogo\TipoCartera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TipoCarteraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;

        $tipo_cartera = TipoCartera::where('Activo', 1)->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $tipo_cartera->search(function ($t) use ($idRegistro) {
                return $t->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('catalogo.tipo_cartera.index', compact('tipo_cartera', 'posicion'));
    }

    public function create()
    {
        return view('catalogo.tipo_cartera.create');
    }


    public function store(TipoCarteraFormRequest $request)
    {
        $tipo_cartera = new TipoCartera();
        $tipo_cartera->Nombre = $request->Nombre;
        $tipo_cartera->Activo = 1;
        $tipo_cartera->Poliza = $request->Poliza;
        $tipo_cartera->save();

        alert()->success('El registro ha sido agregado correctamente');
        return Redirect::to('catalogo/tipo_cartera?idRegistro=' . $tipo_cartera->Id);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $tipo_cartera = TipoCartera::findOrFail($id);
        return view('catalogo.tipo_cartera.edit', compact('tipo_cartera'));
    }


    public function update(TipoCarteraFormRequest $request, $id)
    {
        $tipo_cartera = TipoCartera::findOrFail($id);
        $tipo_cartera->Nombre = $request->Nombre;
        $tipo_cartera->Poliza = $request->Poliza;
        $tipo_cartera->update();


        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/tipo_cartera?idRegistro=' . $tipo_cartera->Id);
    }

    public function destroy($id)
    {
        $tipo_cartera = TipoCartera::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
