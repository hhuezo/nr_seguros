<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cobertura;
use App\Models\catalogo\CoberturaTarificacion;
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

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;

        $productos = Producto::where('Activo', 1)->orderBy('Id', 'asc')->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $productos->search(function ($p) use ($idRegistro) {
                return $p->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', '=', 1)->get();

        return view('catalogo.producto.index', compact('productos', 'posicion', 'aseguradoras', 'ramos'));
    }


    public function create()
    {

        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', '=', 1)->get();

        return view('catalogo.producto.create', compact('aseguradoras', 'ramos'));
    }


    public function store(Request $request)
    {
        // Validaciones
        $request->validate([
            'Nombre'               => 'required|string|max:150|unique:producto,Nombre',
            'Aseguradora'          => 'required|string|max:150',
            'NecesidadProteccion'  => 'required|string|max:255',
            'Descripcion'          => 'nullable|string|max:500',
        ], [
            'Nombre.required'      => 'El nombre del producto es obligatorio.',
            'Nombre.unique'        => 'Ya existe un producto con este nombre.',
            'Nombre.max'           => 'El nombre no debe exceder los 150 caracteres.',

            'Aseguradora.required' => 'El campo aseguradora es obligatorio.',
            'Aseguradora.max'      => 'La aseguradora no debe exceder los 150 caracteres.',

            'NecesidadProteccion.required' => 'La necesidad de protección es obligatoria.',
            'NecesidadProteccion.max'      => 'La necesidad de protección no debe exceder los 255 caracteres.',

            'Descripcion.max'      => 'La descripción no debe exceder los 500 caracteres.',
        ]);

        $tab = $request->tab ?? 1;

        $producto = new Producto();
        $producto->Nombre = $request->Nombre;
        $producto->Aseguradora = $request->Aseguradora;
        $producto->NecesidadProteccion = $request->NecesidadProteccion;
        $producto->Descripcion = $request->Descripcion;
        $producto->Activo = 1;
        $producto->save();

        return redirect('catalogo/producto/' . $producto->Id . '/edit?tab=' . $tab)->with('success', 'El registro ha sido creado correctamente');
    }


    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $tab = $request->tab ?? 1;

        $producto = Producto::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', '=', 1)->get();
        $coberturas = Cobertura::where('Activo', '=', 1)->where('ProductoId', '=', $producto->Id)->get();
        $datos_tecnicos = DatosTecnicos::where('Activo', '=', 1)->where('ProductoId', '=', $producto->Id)->get();
        $tarificaciones = CoberturaTarificacion::where('Activo', 1)->get();
        return view('catalogo/producto/edit', compact(
            'producto',
            'aseguradoras',
            'ramos',
            'coberturas',
            'datos_tecnicos',
            'tarificaciones',
            'tab'
        ));
    }

    public function update(Request $request, $id)
    {
        // Validaciones sin unique
        $request->validate([
            'Nombre'               => 'required|string|max:150',
            'Aseguradora'          => 'required|integer',
            'NecesidadProteccion'  => 'required|integer',
            'Descripcion'          => 'nullable|string|max:500',
        ], [
            'Nombre.required'      => 'El nombre del producto es obligatorio.',
            'Nombre.max'           => 'El nombre no debe exceder los 150 caracteres.',

            'Aseguradora.required' => 'Debe seleccionar una aseguradora.',
            'Aseguradora.integer'  => 'El campo aseguradora no es válido.',

            'NecesidadProteccion.required' => 'Debe seleccionar un ramo.',
            'NecesidadProteccion.integer'  => 'El campo ramo no es válido.',

            'Descripcion.max'      => 'La descripción no debe exceder los 500 caracteres.',
        ]);

        $tab = $request->tab ?? 1;

        $producto = Producto::findOrFail($id);
        $producto->Nombre = $request->Nombre;
        $producto->Aseguradora = $request->Aseguradora;
        $producto->NecesidadProteccion = $request->NecesidadProteccion;
        $producto->Descripcion = $request->Descripcion;
        $producto->save();

        return redirect('catalogo/producto/' . $producto->Id . '/edit?tab=' . $tab)
            ->with('success', 'El registro ha sido modificado correctamente');
    }



    public function destroy($id)
    {
        Producto::findOrFail($id)->update(['Activo' => 0]);
        return redirect('catalogo/producto/')->with('success', 'El registro ha sido eliminado correctamente');
    }

    public function add_cobertura(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'Nombre'      => 'required|string|max:150',
            'Tarificacion' => 'required|integer',
            'Descuento'   => 'required|in:0,1',
            'Iva'         => 'required|in:0,1',
            'Producto'    => 'required|integer',
        ], [
            'Nombre.required'        => 'El nombre de la cobertura es obligatorio.',
            'Nombre.max'             => 'El nombre no debe exceder los 150 caracteres.',

            'Tarificacion.required'  => 'Debe seleccionar una tarificación.',
            'Tarificacion.integer'   => 'La tarificación seleccionada no es válida.',

            'Descuento.required'     => 'Debe indicar si aplica descuento.',
            'Descuento.in'           => 'El valor de descuento no es válido.',

            'Iva.required'           => 'Debe indicar si aplica IVA.',
            'Iva.in'                 => 'El valor de IVA no es válido.',

            'Producto.required'      => 'Producto no válido.',
            'Producto.integer'       => 'El identificador de producto no es válido.',
        ]);

        // GUARDAR REGISTRO
        $cobertura = new Cobertura();
        $cobertura->Nombre        = $request->Nombre;
        $cobertura->TarificacionId = $request->Tarificacion;
        $cobertura->Descuento     = $request->Descuento;
        $cobertura->Iva           = $request->Iva;
        $cobertura->ProductoId    = $request->Producto;
        $cobertura->Activo        = 1;

        $cobertura->save();

        return redirect('catalogo/producto/' . $request->Producto . '/edit?tab=2')
            ->with('success', 'El registro ha sido agregado correctamente');
    }


    public function edit_cobertura(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'Id'          => 'required|integer|exists:producto_cobertura,Id',
            'Nombre'      => 'required|string|max:150',
            'Tarificacion' => 'required|integer',
            'Descuento'   => 'required|in:0,1',
            'Iva'         => 'required|in:0,1',
            'Producto'    => 'required|integer',
        ], [
            'Nombre.required'       => 'El nombre de la cobertura es obligatorio.',
            'Nombre.max'            => 'El nombre no debe exceder los 150 caracteres.',

            'Tarificacion.required' => 'Debe seleccionar una tarificación.',
            'Tarificacion.integer'  => 'El valor de tarificación no es válido.',

            'Descuento.required'    => 'Debe indicar si aplica descuento.',
            'Descuento.in'          => 'Valor de descuento inválido.',

            'Iva.required'          => 'Debe indicar si aplica IVA.',
            'Iva.in'                => 'Valor de IVA inválido.',

            'Producto.required'     => 'Producto no válido.',
            'Producto.integer'      => 'El identificador de producto no es válido.',
        ]);

        // ACTUALIZACIÓN
        $cobertura = Cobertura::findOrFail($request->Id);
        $cobertura->Nombre = $request->Nombre;
        $cobertura->TarificacionId = $request->Tarificacion;
        $cobertura->Descuento = $request->Descuento;
        $cobertura->Iva = $request->Iva;
        $cobertura->save();

        return redirect('catalogo/producto/' . $request->Producto . '/edit?tab=2')
            ->with('success', 'El registro ha sido modificado correctamente');
    }


    public function delete_cobertura($id)
    {
        $cobertura = Cobertura::findOrFail($id);
        $cobertura->delete();
        return redirect('catalogo/producto/' . $cobertura->ProductoId . '/edit?tab=2')
            ->with('success', 'El registro ha sido eliminado correctamente');
    }

    public function add_dato_tecnico(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'Nombre'   => 'required|string|max:150',
            'Producto' => 'required|integer',
        ], [
            'Nombre.required'  => 'El nombre del dato técnico es obligatorio.',
            'Nombre.max'       => 'El nombre no debe exceder los 150 caracteres.',
            'Producto.required' => 'Producto no válido.',
        ]);

        // GUARDAR
        $dato_tecnico = new DatosTecnicos();
        $dato_tecnico->Nombre      = $request->Nombre;
        $dato_tecnico->Descripcion = $request->Descripcion;
        $dato_tecnico->ProductoId  = $request->Producto;
        $dato_tecnico->Activo      = 1;

        $dato_tecnico->save();

        return redirect('catalogo/producto/' . $dato_tecnico->ProductoId . '/edit?tab=3')
            ->with('success', 'El registro ha sido agregado correctamente');
    }


    public function edit_dato_tecnico(Request $request, $id)
    {
        // VALIDAR CAMPOS
        $request->validate([
            'Nombre'       => 'required|string|max:150',
            'Producto'     => 'required|integer',
            'Descripcion'  => 'nullable|string|max:500',
        ], [
            'Nombre.required'    => 'El nombre del dato técnico es obligatorio.',
            'Nombre.max'         => 'El nombre no debe exceder los 150 caracteres.',

            'Producto.required'  => 'Producto no válido.',
            'Producto.integer'   => 'Identificador de producto inválido.',

            'Descripcion.max'    => 'La descripción no debe exceder los 500 caracteres.',
        ]);

        // VALIDAR QUE EL REGISTRO EXISTE
        $dato_tecnico = DatosTecnicos::findOrFail($id);

        // ACTUALIZAR
        $dato_tecnico->Nombre      = $request->Nombre;
        $dato_tecnico->Descripcion = $request->Descripcion;
        $dato_tecnico->ProductoId  = $request->Producto;
        $dato_tecnico->save();

        return redirect('catalogo/producto/' . $dato_tecnico->ProductoId . '/edit?tab=3')
            ->with('success', 'El registro ha sido modificado correctamente');
    }


    public function delete_dato_tecnico($id)
    {
        // VALIDAR QUE EL ID SEA NUMÉRICO
        if (!is_numeric($id)) {
            return back()->with('error', 'ID inválido.');
        }

        // SI NO EXISTE → findOrFail LANZA ERROR 404
        $dato_tecnico = DatosTecnicos::findOrFail($id);

        // ELIMINAR
        $productoId = $dato_tecnico->ProductoId;
        $dato_tecnico->delete();

        return redirect('catalogo/producto/' . $productoId . '/edit?tab=3')
            ->with('success', 'El registro ha sido eliminado correctamente');
    }
}
