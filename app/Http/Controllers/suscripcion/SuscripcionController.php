<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Cliente;
use App\Models\polizas\Comentario;
use App\Models\polizas\Deuda;
use App\Models\polizas\Vida;
use App\Models\suscripcion\Comentarios;
use App\Models\suscripcion\Compania;
use App\Models\suscripcion\EstadoCaso;
use App\Models\suscripcion\OrdenMedica;
use App\Models\suscripcion\ResumenGestion;
use App\Models\suscripcion\Suscripcion;
use App\Models\suscripcion\TipoCliente;
use App\Models\suscripcion\TipoImc;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suscripciones = Suscripcion::get();


        return view('suscripciones.suscripcion.index', compact('suscripciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companias = Compania::get();
        $tipo_clientes = TipoCliente::get();
        $tipo_orden = OrdenMedica::get();
        $estados = EstadoCaso::get();

        $ejecutivos = User::role('ejecutivo')->where('activo', 1)->get();
        $clientes = Cliente::where('activo', 1)->get();
        $polizas_deuda = Deuda::get();
        $polizas_vida = Vida::get();

        $tipos_imc = TipoImc::get();
        $resumen_gestion = ResumenGestion::get();


        return view('suscripciones.suscripcion.create', compact(
            'companias',
            'tipo_clientes',
            'tipo_orden',
            'estados',
            'ejecutivos',
            'clientes',
            'polizas_deuda',
            'polizas_vida',
            'tipos_imc',
            'resumen_gestion'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $suscripcion = new Suscripcion();
        $suscripcion->FechaIngreso = $request->FechaIngreso;
        $suscripcion->GestorId = $request->Gestor;
        $suscripcion->CompaniaId = $request->CompaniaId;
        $suscripcion->Contratante = $request->Contratante;
        $suscripcion->PolizaDeuda = $request->PolizaDeuda;
        $suscripcion->PolizaVida = $request->PolizaVida;
        $suscripcion->Asegurado = $request->Asegurado;
        $suscripcion->Dui = $request->Dui;
        $suscripcion->Edad = $request->Edad;
        $suscripcion->Genero = $request->Genero;
        $suscripcion->SumaAseguradaDeuda = $request->SumaAseguradaDeuda;
        $suscripcion->SumaAseguradaVida = $request->SumaAseguradaVida;
        $suscripcion->TipoClienteId = $request->TipoClienteId;
        $suscripcion->Peso = $request->Peso;
        $suscripcion->Estatura = $request->Estatura;
        $suscripcion->Imc = $request->Imc;
        $suscripcion->TipoIMCId = $request->TipoIMCId;
        $suscripcion->Padecimiento = $request->Padecimiento;
        $suscripcion->TipoOrdenMedicaId = $request->TipoOrdenMedicaId;
        $suscripcion->EstadoId = $request->EstadoId;
        $suscripcion->ResumenGestion = $request->ResumenGestion;
        $suscripcion->FechaReportadoCia = $request->FechaReportadoCia;
        $suscripcion->TareasEvaSisa = $request->TareasEvaSisa;
        $suscripcion->FechaResolucion = $request->FechaResolucion;
        $suscripcion->ResolucionFinal = $request->ResolucionFinal;
        $suscripcion->ValorExtraPrima = $request->ValorExtraPrima;
        $suscripcion->Activo = 1;
        $suscripcion->save();

        $comentario = new Comentarios();
        $comentario->SuscripcionId = $suscripcion->Id;
        $comentario->Usuario = $request->Gestor;
        $comentario->FechaCreacion = Carbon::now();
        $comentario->Activo = $request->Activo;
        $comentario->Comentario = $request->Comentarios;
        $comentario->save();

        alert()->success('El registro ha sido creado correctamente');
        return redirect('suscripciones');
    }

    public function get_imc(Request $request)
    {
        $peso = $request->peso;
        $estatura = $request->estatura;

        $peso_kg = $peso / 2.2;
        $imc = $peso_kg / ($estatura * 2);

        if ($imc < 18.5) {
            $tipo_imc = 1;
        } elseif ($imc >= 18.5 && $imc < 24.9) {
            $tipo_imc = 2;
        } elseif ($imc > 25 && $imc < 29.9) {
            $tipo_imc = 3;
        } elseif ($imc >= 30 && $imc < 34.9) {
            $tipo_imc = 4;
        } elseif ($imc >= 35 && $imc < 39.9) {
            $tipo_imc = 5;
        } elseif ($imc >= 40 && $imc < 49.9) {
            $tipo_imc = 6;
        } else {
            $tipo_imc = 7;
        }

        $tipo = TipoImc::findOrFail($tipo_imc);

        $data  = [
            'imc' => round($imc, 2),
            'tipo_id' => $tipo_imc,
            'desc_tipo' => $tipo->Nombre,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function agregar_comentario(Request $request)
    {
        // Validación
        $request->validate([
            'SuscripcionId' => 'required|integer|exists:suscripcion,id',
            'Comentario'    => 'required|string|max:500',
        ], [
            'SuscripcionId.required' => 'El campo Suscripción es obligatorio.',
            'SuscripcionId.integer'  => 'El campo Suscripción debe ser un número entero.',
            'SuscripcionId.exists'   => 'La suscripción seleccionada no existe en la base de datos.',
            'Comentario.required'    => 'Debe ingresar un comentario.',
            'Comentario.string'      => 'El comentario debe ser una cadena de texto.',
            'Comentario.max'         => 'El comentario no debe exceder los 500 caracteres.',
        ]);


        // Guardado
        $comentario = new Comentarios();
        $comentario->SuscripcionId = $request->SuscripcionId;
        $comentario->Usuario = auth()->user()->name ?? '';
        $comentario->FechaCreacion = Carbon::now();
        $comentario->Activo = 1;
        $comentario->Comentario = $request->Comentario;
        $comentario->save();

        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $suscripcion = Suscripcion::findOrFail($id);
        $companias = Compania::get();
        $tipo_clientes = TipoCliente::get();
        $tipo_orden = OrdenMedica::get();
        $estados = EstadoCaso::get();
        $ejecutivos = User::role('ejecutivo')->where('activo', 1)->get();
        $clientes = Cliente::where('activo', 1)->get();
        $polizas_deuda = Deuda::get();
        $polizas_vida = Vida::get();
        $tipos_imc = TipoImc::get();
        $resumen_gestion = ResumenGestion::get();
        return view('suscripciones.suscripcion.edit', compact('tipos_imc','resumen_gestion','polizas_vida','polizas_deuda','clientes', 'ejecutivos', 'companias', 'tipo_clientes', 'tipo_orden', 'suscripcion', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $suscripcion = Suscripcion::findOrFail($request->Id);
        $suscripcion->FechaIngreso = $request->FechaIngreso;
        $suscripcion->GestorId = $request->Gestor;
        $suscripcion->CompaniaId = $request->CompaniaId;
        $suscripcion->Contratante = $request->Contratante;
        $suscripcion->PolizaDeuda = $request->PolizaDeuda;
        $suscripcion->PolizaVida = $request->PolizaVida;
        $suscripcion->Asegurado = $request->Asegurado;
        $suscripcion->Dui = $request->Dui;
        $suscripcion->Edad = $request->Edad;
        $suscripcion->Genero = $request->Genero;
        $suscripcion->SumaAseguradaDeuda = $request->SumaAseguradaDeuda;
        $suscripcion->SumaAseguradaVida = $request->SumaAseguradaVida;
        $suscripcion->TipoClienteId = $request->TipoClienteId;
        $suscripcion->Peso = $request->Peso;
        $suscripcion->Estatura = $request->Estatura;
        $suscripcion->Imc = $request->Imc;
        $suscripcion->TipoIMCId = $request->TipoIMCId;
        $suscripcion->Padecimiento = $request->Padecimiento;
        $suscripcion->TipoOrdenMedicaId = $request->TipoOrdenMedicaId;
        $suscripcion->EstadoId = $request->EstadoId;
        $suscripcion->ResumenGestion = $request->ResumenGestion;
        $suscripcion->FechaReportadoCia = $request->FechaReportadoCia;
        $suscripcion->TareasEvaSisa = $request->TareasEvaSisa;
        $suscripcion->FechaResolucion = $request->FechaResolucion;
        $suscripcion->ResolucionFinal = $request->ResolucionFinal;
        $suscripcion->ValorExtraPrima = $request->ValorExtraPrima;
        // $suscripcion->Activo = 1;
        $suscripcion->update();

        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Comentarios::where('SuscripcionId', $id)->delete();
        Suscripcion::findOrFail($id)->delete();
        alert()->success('El registro ha sido eliminado correctamente');
        return back();
    }
}
