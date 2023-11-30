<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cobertura;
use App\Models\catalogo\DatosTecnicos;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $productos = Producto::where('Activo', '=', 1)->get();
        return view('catalogo.producto.index', compact('productos'));
    }

    public function create()
    {

        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', '=', 1)->get();

        return view('catalogo.producto.create', compact('aseguradoras', 'ramos'));
    }


    public function store(Request $request)
    {
        $producto = new Producto();
        $producto->Nombre = $request->Nombre;
        $producto->Aseguradora = $request->Aseguradora;
        $producto->NecesidadProteccion = $request->NecesidadProteccion;
        $producto->Descripcion = $request->Descripcion;
        $producto->Activo = 1;
        $producto->save();

        session(['tab1' => '1']);

        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/producto/' . $producto->Id . '/edit');
        //return Redirect::to('catalogo/producto/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (session()->has('tab2')) {
            session(['tab1' => session('tab2')]);
            session(['tab2' => '1']);
        } else {
            session(['tab1' => '1']);
        }

        $producto = Producto::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', '=', 1)->get();
        $coberturas = Cobertura::where('Activo', '=', 1)->where('Producto', '=', $producto->Id)->get();
        $datos_tecnicos = DatosTecnicos::where('Activo', '=', 1)->where('Producto', '=', $producto->Id)->get();
        return view('catalogo/producto/edit', compact(
            'producto',
            'aseguradoras',
            'ramos',
            'coberturas',
            'datos_tecnicos'
        ));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->Nombre = $request->Nombre;
        $producto->Aseguradora = $request->Aseguradora;
        $producto->NecesidadProteccion = $request->NecesidadProteccion;
        $producto->Descripcion = $request->Descripcion;
        $producto->update();
        session(['tab1' => '1']);
        alert()->success('El registro ha sido modificado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras/' . $id . 'edit');
    }

    public function destroy($id)
    {
        Producto::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras');
    }

    public function add_cobertura(Request $request){
        $cobertura = new Cobertura();
        $cobertura->Nombre = $request->Nombre;
        $cobertura->Tarificacion = $request->Tarificacion;
        $cobertura->Descuento = $request->Descuento;
        $cobertura->Iva = $request->Iva;
        $cobertura->Producto  = $request->Producto ;
        $cobertura->Activo  = 1 ;

        $cobertura->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function edit_cobertura(Request $request){
        $cobertura = Cobertura::findOrFail($request->Id);

        $cobertura->Nombre = $request->Nombre;
        $cobertura->Tarificacion = $request->Tarificacion;
        $cobertura->Descuento = $request->Descuento;
        $cobertura->Iva = $request->Iva;
        $cobertura->Producto  = $request->Producto ;
        $cobertura->update();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function delete_cobertura(Request $request){
        Cobertura::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function add_dato_tecnico(Request $request){
        $dato_tecnico = new DatosTecnicos();
        $dato_tecnico->Nombre = $request->Nombre;
        $dato_tecnico->Descripcion = $request->Descripcion;
        $dato_tecnico->Producto  = $request->Producto ;
        $dato_tecnico->Activo  = 1 ;

        $dato_tecnico->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab2' => '3']);
        return back();
    }

    public function edit_dato_tecnico(Request $request){
        $dato_tecnico = DatosTecnicos::findOrFail($request->Id);

        $dato_tecnico->Nombre = $request->Nombre;
        $dato_tecnico->Descripcion = $request->Descripcion;
        $dato_tecnico->Producto  = $request->Producto ;
        $dato_tecnico->update();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '3']);
        return back();
    }

    public function delete_dato_tecnico(Request $request){
        DatosTecnicos::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '3']);
        return back();
    }

}
