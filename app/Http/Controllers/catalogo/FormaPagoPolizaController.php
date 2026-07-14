<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\FormaPagoPoliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FormaPagoPolizaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $formas_pago_polizas = FormaPagoPoliza::where('Activo', 1)
            ->ordenado()
            ->get();
        return view('catalogo.forma_pago_polizas.index', compact('formas_pago_polizas'));
    }

    public function create()
    {
        return Redirect::to('catalogo/forma_pago_polizas');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Orden' => 'nullable|integer|min:0',
        ]);

        $formaPago = new FormaPagoPoliza();
        $formaPago->Nombre = $request->Nombre;
        $formaPago->Orden = $request->Orden;
        $formaPago->Activo = 1;
        $formaPago->save();

        alert()->success('El registro ha sido creado correctamente');
        return Redirect::to('catalogo/forma_pago_polizas');
    }

    public function edit($id)
    {
        return Redirect::to('catalogo/forma_pago_polizas');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Orden' => 'nullable|integer|min:0',
        ]);

        $formaPago = FormaPagoPoliza::findOrFail($id);
        $formaPago->Nombre = $request->Nombre;
        $formaPago->Orden = $request->Orden;
        $formaPago->update();

        alert()->success('El registro ha sido modificado correctamente');
        return Redirect::to('catalogo/forma_pago_polizas');
    }

    public function destroy($id)
    {
        FormaPagoPoliza::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }
}
