<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cancelacion;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Cobertura;
use App\Models\catalogo\DatosTecnicos;
use App\Models\catalogo\Deducible;
use App\Models\catalogo\DepartamentoNR;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\OrigenPoliza;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\TipoCartera;
use App\Models\polizas\PolizaSeguro;
use App\Models\polizas\PolizaSeguroCobertura;
use App\Models\polizas\PolizaSeguroDatosTecnicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PolizaSeguroController extends Controller
{
    public function index()
    {
        $polizas = PolizaSeguro::get();
        return view('polizas.seguro.index', compact('polizas'));
    }


    public function create()
    {
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $clientes = Cliente::where('Activo', 1)->get();
        $ofertas = Negocio::get();
        $tipo_cartera_nr = TipoCartera::get();
        $forma_pago = [0 => '', 1 => 'Anual', 2 => 'Semestral', 3 => 'Trimestral', 4 => 'Mensual'];
        $estado_poliza = EstadoPoliza::get();
        $cancelacion = Cancelacion::where('Activo', 1)->get();
        $origen_poliza = OrigenPoliza::where('Activo', 1)->get();
        $tipo_deducible = Deducible::where('Activo', 1)->get();
        $departamento_nr = DepartamentoNR::where('Activo', 1)->get();
        //dd($ofertas);

        return view('polizas.seguro.create', compact('cancelacion', 'origen_poliza', 'tipo_deducible', 'departamento_nr', 'estado_poliza', 'forma_pago', 'tipo_cartera_nr', 'ofertas', 'productos', 'planes', 'aseguradora', 'clientes'));
    }

    public function get_oferta(Request $request)
    {
        try {
            $negocio = Negocio::findOrFail($request->Oferta);

            $cotizacion = $negocio->cotizaciones->first();
            if (!$cotizacion || !$cotizacion->planes || !$cotizacion->planes->productos) {
                return response()->json([
                    'success' => false,
                    'message' => 'El negocio no tiene cotización, plan o producto asociado.'
                ], 200);
            }

            $oferta = [
                'id' => $negocio->Id,
                'id_cliente' => $negocio->clientes->Id ?? null,
                'dui_cliente' => $negocio->clientes->Dui ?? '',
                'nombre_cliente' => $negocio->clientes->Nombre ?? '',
                'forma_pago' => $negocio->PeriodoPago,
                'num_cuotas' => $negocio->NumCoutas,
                'productos' => $cotizacion->planes->productos->Id,
                'planes' => $cotizacion->planes->Id,
            ];

            return response()->json([
                'success' => true,
                'oferta' => $oferta
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la oferta: ' . $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {

        $request->validate([
            'FormaPago' => 'required|integer',
            'NumeroPoliza' => 'required|string|max:100',
            'EstadoPoliza' => 'required|integer',
            'Productos' => 'required|integer',
            'Planes' => 'required|integer',
            //'IdCliente' => 'required',
            'Cliente' => 'required|integer',
            'VigenciaDesde' => 'required|date',
            'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
            'DiasVigencia' => 'nullable|integer',
            // agrega más validaciones según el campo si es necesario
        ]);

        $poliza_seguro = new PolizaSeguro();
        $poliza_seguro->Oferta = $request->Oferta;
        $poliza_seguro->FormaPago = $request->FormaPago;
        $poliza_seguro->NumeroPoliza = $request->NumeroPoliza;
        $poliza_seguro->EstadoPoliza = $request->EstadoPoliza;
        $poliza_seguro->Productos = $request->Productos;
        $poliza_seguro->Planes = $request->Planes;
        $poliza_seguro->Cliente = $request->Cliente;
        $poliza_seguro->VigenciaDesde = $request->VigenciaDesde;
        $poliza_seguro->VigenciaHasta = $request->VigenciaHasta;
        $poliza_seguro->DiasVigencia = $request->DiasVigencia;
        $poliza_seguro->MotivoCancelacion = $request->MotivoCancelacion;
        $poliza_seguro->FechaCancelacion = $request->FechaCancelacion;
        $poliza_seguro->CodCancelacion = $request->CodCancelacion;
        $poliza_seguro->FechaEnvioAnexo = $request->FechaEnvioAnexo;
        $poliza_seguro->Observacion = $request->Observacion;
        $poliza_seguro->SolicitudRenovacion = $request->SolicitudRenovacion;
        $poliza_seguro->OrigenPoliza = $request->OrigenPoliza;
        $poliza_seguro->FechaVinculacion = $request->FechaVinculacion;
        $poliza_seguro->Departamento = $request->DepartamentoNr;
        $poliza_seguro->FechaRecepcion = $request->FechaRecepcion;
        $poliza_seguro->SustituidaPoliza = $request->SustituidaPoliza;
        $poliza_seguro->ObservacionSiniestro = $request->ObservacionSiniestro;
        $poliza_seguro->EjecutivoCia = $request->EjecutivoCia;
        $poliza_seguro->GrupoCliente = $request->GrupoCliente;
        $poliza_seguro->Deducible = $request->Deducible;
        $poliza_seguro->Activo = $request->Activo;
        $poliza_seguro->Usuario = $request->Usuario;
        $poliza_seguro->save();


        $coberturas_producto = Cobertura::where('Producto', $request->Productos)->where('Activo', 1)->get();
        $datos_tecnicos = DatosTecnicos::where('Producto', $request->Productos)->where('Activo', 1)->get();

        //agregar cobertura de producto de la poliza seguro
        foreach ($coberturas_producto as $cobertura) {
            $cobertura_poliza = new PolizaSeguroCobertura();
            $cobertura_poliza->PolizaSeguroId = $poliza_seguro->Id;
            //$cobertura_poliza->Cobertura = $cobertura->Id;
            $cobertura_poliza->Nombre = $cobertura->Nombre;
            $cobertura_poliza->Tarificacion = $cobertura->Tarificacion;
            $cobertura_poliza->Descuento = $cobertura->Descuento;
            $cobertura_poliza->Iva = $cobertura->Iva;
            $cobertura_poliza->save();
        }

        //agregar datos tecnicos de producto de la poliza seguro

        foreach ($datos_tecnicos as $datos) {
            $dato_tecnico = new PolizaSeguroDatosTecnicos();
            $dato_tecnico->PolizaSeguroId = $poliza_seguro->Id;
            $dato_tecnico->Nombre = $datos->Nombre;
            $dato_tecnico->Descripcion = $datos->Descripcion;
            $dato_tecnico->save();
        }

        //alert()->success('Se creo la poliza de seguro');
        return redirect('poliza/seguro/' . $poliza_seguro->Id . '?tab=2')->with('success', 'Se creo la poliza de seguro');
    }


    public function show($id, Request $request)
    {
        $tab = $request->tab ?? 1;
        $poliza_seguro = PolizaSeguro::findOrFail($id);
        //$coberturas = PolizaSeguroCobertura::where('PolizaSeguro', $id)->get();
        //$datos_tecnicos = PolizaSeguroDatosTecnicos::where('PolizaSeguro', $id)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $clientes = Cliente::where('Activo', 1)->get();
        $ofertas = Negocio::get();
        $tipo_cartera_nr = TipoCartera::get();
        $forma_pago = [0 => '', 1 => 'Anual', 2 => 'Semestral', 3 => 'Trimestral', 4 => 'Mensual'];
        $estado_poliza = EstadoPoliza::get();
        $cancelacion = Cancelacion::where('Activo', 1)->get();
        $origen_poliza = OrigenPoliza::where('Activo', 1)->get();
        $tipo_deducible = Deducible::where('Activo', 1)->get();
        $departamento_nr = DepartamentoNR::where('Activo', 1)->get();

        //dd($poliza_seguro->oferta->clientes->Nombre);
        return view('polizas.seguro.show', compact('tab', 'poliza_seguro', 'cancelacion', 'origen_poliza', 'tipo_deducible', 'departamento_nr', 'estado_poliza', 'forma_pago', 'tipo_cartera_nr', 'ofertas', 'productos', 'planes', 'aseguradora', 'clientes'));
    }

    public function update_cobertura(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
        ]);

        $cobertura = PolizaSeguroCobertura::find($id); // Usa el modelo correcto

        if (!$cobertura) {
            return response()->json(['success' => false, 'message' => 'No encontrado'], 404);
        }

        $cobertura->Valor = $request->input('valor');
        $cobertura->save();

        return response()->json(['success' => true]);
    }

    public function update_datos_tecnicos(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
        ]);

        $datos_tecnicos = PolizaSeguroDatosTecnicos::find($id); // Usa el modelo correcto

        if (!$datos_tecnicos) {
            return response()->json(['success' => false, 'message' => 'No encontrado'], 404);
        }

        $datos_tecnicos->Valor = $request->input('valor');
        $datos_tecnicos->save();

        return response()->json(['success' => true]);
    }

    public function save(Request $request, $id)
    {
        $poliza_seguro = PolizaSeguro::findOrFail($id);
        $poliza_seguro->Oferta = $request->Oferta;
        $poliza_seguro->FormaPago = $request->FormaPago;
        $poliza_seguro->NumeroPoliza = $request->NumeroPoliza;
        $poliza_seguro->EstadoPoliza = $request->EstadoPoliza;
        $poliza_seguro->Productos = $request->Productos;
        $poliza_seguro->Planes = $request->Planes;
        $poliza_seguro->Cliente = $request->Cliente;
        $poliza_seguro->VigenciaDesde = $request->VigenciaDesde;
        $poliza_seguro->VigenciaHasta = $request->VigenciaHasta;
        $poliza_seguro->DiasVigencia = $request->DiasVigencia;
        $poliza_seguro->MotivoCancelacion = $request->MotivoCancelacion;
        $poliza_seguro->FechaCancelacion = $request->FechaCancelacion;
        $poliza_seguro->CodCancelacion = $request->CodCancelacion;
        $poliza_seguro->FechaEnvioAnexo = $request->FechaEnvioAnexo;
        $poliza_seguro->Observacion = $request->Observacion;
        $poliza_seguro->SolicitudRenovacion = $request->SolicitudRenovacion;
        $poliza_seguro->OrigenPoliza = $request->OrigenPoliza;
        $poliza_seguro->FechaVinculacion = $request->FechaVinculacion;
        $poliza_seguro->Departamento = $request->DepartamentoNr;
        $poliza_seguro->FechaRecepcion = $request->FechaRecepcion;
        $poliza_seguro->SustituidaPoliza = $request->SustituidaPoliza;
        $poliza_seguro->ObservacionSiniestro = $request->ObservacionSiniestro;
        $poliza_seguro->EjecutivoCia = $request->EjecutivoCia;
        $poliza_seguro->GrupoCliente = $request->GrupoCliente;
        $poliza_seguro->Deducible = $request->Deducible;
        $poliza_seguro->Activo = $request->Activo;
        $poliza_seguro->Usuario = $request->Usuario;
        $poliza_seguro->update();

        alert()->success('Se creo la poliza de seguro');
        return back();
    }

    public function cobertura_store($id, Request $request)
    {
        // Validación (fuera del try)
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:150',
            'Tarificacion' => 'required|boolean',
            'Descuento' => 'required|boolean',
            'Iva' => 'required|boolean',
        ], [
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'Nombre.string' => 'El campo Nombre debe ser una cadena de texto.',
            'Nombre.max' => 'El campo Nombre no debe exceder los 150 caracteres.',
            'Tarificacion.required' => 'Debe indicar si hay tarificación.',
            'Tarificacion.boolean' => 'El valor de Tarificación debe ser verdadero o falso.',
            'Descuento.required' => 'Debe indicar si aplica descuento.',
            'Descuento.boolean' => 'El valor de Descuento debe ser verdadero o falso.',
            'Iva.required' => 'Debe indicar si aplica IVA.',
            'Iva.boolean' => 'El valor de IVA debe ser verdadero o falso.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Guardado (dentro del try)
        try {
            $poliza_cobertura = new PolizaSeguroCobertura();
            $poliza_cobertura->PolizaSeguroId = $id;
            $poliza_cobertura->Nombre = $request->Nombre;
            $poliza_cobertura->Tarificacion = $request->Tarificacion;
            $poliza_cobertura->Descuento = $request->Descuento;
            $poliza_cobertura->Iva = $request->Iva;
            $poliza_cobertura->save();

            return redirect('poliza/seguro/' . $id . '?tab=2')
                ->with('success', 'El registro ha sido guardado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocurrió un error al guardar el registro: ' . $e->getMessage());
        }
    }



    public function cobertura_delete($id)
    {
        try {
            $poliza_cobertura = PolizaSeguroCobertura::findOrFail($id);
            $poliza_cobertura->delete();

            return redirect('poliza/seguro/' . $poliza_cobertura->PolizaSeguroId . '?tab=2')
                ->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar el registro: ' . $e->getMessage());
        }
    }

    public function dato_tecnico_store($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ], [
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'Nombre.string' => 'El campo Nombre debe ser una cadena de texto.',
            'Nombre.max' => 'El campo Nombre no debe exceder los 100 caracteres.',
            'Descripcion.string' => 'El campo Descripción debe ser una cadena de texto.',
            'Descripcion.max' => 'El campo Descripción no debe exceder los 255 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $poliza_dato_tecnico = new PolizaSeguroDatosTecnicos();
            $poliza_dato_tecnico->PolizaSeguroId = $id;
            $poliza_dato_tecnico->Nombre = $request->Nombre;
            $poliza_dato_tecnico->Descripcion = $request->Descripcion;
            $poliza_dato_tecnico->save();

            return redirect('poliza/seguro/' . $id . '?tab=3')
                ->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar el registro: ' . $e->getMessage());
        }
    }

    public function dato_tecnico_delete($id)
    {
        try {
            $poliza_dato_tecnico = PolizaSeguroDatosTecnicos::findOrFail($id);
            $poliza_dato_tecnico->delete();

            return redirect('poliza/seguro/' . $poliza_dato_tecnico->PolizaSeguroId . '?tab=3')
                ->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar el registro: ' . $e->getMessage());
        }
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
