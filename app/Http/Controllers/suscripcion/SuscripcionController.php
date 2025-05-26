<?php

namespace App\Http\Controllers\suscripcion;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuscripcionController extends Controller
{

    public function index()
    {
        $suscripciones = Suscripcion::get();


        return view('suscripciones.suscripcion.index', compact('suscripciones'));
    }

    public function create()
    {
        $companias = Compania::get();
        $tipo_clientes = TipoCliente::get();
        $tipo_orden = OrdenMedica::get();
        $estados = EstadoCaso::get();

        $ejecutivos = User::role('ejecutivo')->where('activo', 1)->get();
        $clientes = Cliente::where('activo', 1)->get();
        $polizas_deuda = Deuda::where('activo', 1)->get();
        $polizas_vida = Vida::where('activo', 1)->get();

        $tipos_imc = TipoImc::get();
        $resumen_gestion = ResumenGestion::get();

        //observaciones 22-5-25
        $aseguradoras = Aseguradora::where('activo', 1)->get();


        return view('suscripciones.suscripcion.create', compact(
            'aseguradoras',
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

    public function store(Request $request)
    {

        $request->validate([
            'FechaIngreso'         => 'required|date',
            'Gestor'               => 'nullable|integer|exists:users,id',
            'CompaniaId'           => 'nullable|integer|exists:aseguradora,Id',
            'ContratanteId'        => 'nullable|integer|exists:cliente,Id',
            'PolizaDeuda'          => 'nullable|integer|exists:poliza_deuda,Id',
            'PolizaVida'           => 'nullable|integer|exists:poliza_vida,Id',
            'Asegurado'            => 'nullable|string|max:100',
            'Dui'                  => 'nullable|string|regex:/^\d{8}-\d{1}$/',
            'Edad'                 => 'nullable|integer|min:0|max:120',
            'Genero'               => 'nullable|in:1,2',
            'SumaAseguradaDeuda'   => 'nullable|numeric|min:0',
            'SumaAseguradaVida'    => 'nullable|numeric|min:0',
            'TipoClienteId'        => 'nullable|integer|exists:sus_tipo_cliente,Id',
            'Peso'                 => 'nullable|numeric|min:0',
            'Estatura'             => 'nullable|numeric|min:0',
            'Imc'                  => 'nullable|numeric|min:0',
            'TipoIMCId'            => 'nullable|integer|exists:sus_tipo_imc,Id',
            'Padecimiento'         => 'nullable|string|max:500',
            'TipoOrdenMedicaId'    => 'nullable|integer|exists:sus_orden_medica,Id',
            'EstadoId'             => 'nullable|integer|exists:sus_estado_caso,Id',
            'ResumenGestion'       => 'nullable|integer|exists:sus_resumen_gestion,Id',
            'FechaReportadoCia'    => 'nullable|date',
            'TareasEvaSisa'        => 'nullable|string|max:255',
            'FechaResolucion'      => 'nullable|date',
            'ResolucionFinal'      => 'nullable|string|max:1000',
            'ValorExtraPrima'      => 'nullable|numeric|min:0',
            'Comentarios'          => 'nullable|string|max:1000',
        ], [
            'required'             => 'El campo :attribute es obligatorio.',
            'date'                 => 'El campo :attribute debe ser una fecha válida.',
            'integer'              => 'El campo :attribute debe ser un número entero.',
            'numeric'              => 'El campo :attribute debe ser numérico.',
            'max'                  => 'El campo :attribute no debe ser mayor a :max caracteres.',
            'min'                  => 'El campo :attribute debe ser al menos :min.',
            'in'                   => 'El valor seleccionado en :attribute no es válido.',
            'exists'               => 'El valor seleccionado en :attribute no existe.',
            'regex'                => 'El formato de :attribute no es válido.'
        ]);

        // try {
        //     DB::beginTransaction();

        $suscripcion = new Suscripcion();
        $suscripcion->FechaIngreso = $request->FechaIngreso;
        $suscripcion->GestorId = $request->Gestor;
        $suscripcion->CompaniaId = $request->CompaniaId;
        $suscripcion->ContratanteId = $request->ContratanteId;
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

        if ($request->Comentarios != "") {
            $comentario = new Comentarios();
            $comentario->SuscripcionId = $suscripcion->Id;
            $comentario->Usuario = $request->Gestor;
            $comentario->FechaCreacion = Carbon::now();
            $comentario->Activo = $request->Activo;
            $comentario->Comentario = $request->Comentarios;
            $comentario->save();
        }

        return redirect('suscripciones/' . $suscripcion->Id . '/edit?tab=1')->with('success', 'El registro ha sido creado correctamente');


        //  DB::commit();
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     report($e); // Puedes también usar Log::error($e->getMessage());
        //     alert()->error('Ha ocurrido un error al guardar la suscripción.');
        //     return redirect()->back()->withInput();
        // }
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
        $comentario->Usuario = auth()->user()->id ?? null;
        $comentario->FechaCreacion = Carbon::now();
        $comentario->Activo = 1;
        $comentario->Comentario = $request->Comentario;
        $comentario->save();
        return redirect('suscripciones/' . $request->SuscripcionId . '/edit?tab=2')->with('success', 'El registro ha sido creado correctamente');
    }


    public function edit(Request $request, $id)
    {
        $tab = $request->tab ?? 1;
        $suscripcion = Suscripcion::findOrFail($id);
        $companias = Compania::get();
        $tipo_clientes = TipoCliente::get();
        $tipo_orden = OrdenMedica::get();
        $estados = EstadoCaso::get();
        $ejecutivos = User::role('ejecutivo')->where('activo', 1)->get();
        $clientes = Cliente::where('activo', 1)->get();
        $polizas_deuda = Deuda::where('activo', 1)->get();
        $polizas_vida = Vida::where('activo', 1)->get();
        $tipos_imc = TipoImc::get();
        $resumen_gestion = ResumenGestion::get();

        //observaciones 22-5-25
        $aseguradoras = Aseguradora::where('activo', 1)->get();

        return view('suscripciones.suscripcion.edit', compact('aseguradoras', 'tipos_imc', 'resumen_gestion', 'polizas_vida', 'polizas_deuda', 'clientes', 'ejecutivos', 'companias', 'tipo_clientes', 'tipo_orden', 'suscripcion', 'estados', 'tab'));
    }


    public function update(Request $request)
    {
        $suscripcion = Suscripcion::findOrFail($request->Id);
        $suscripcion->FechaIngreso = $request->FechaIngreso;
        $suscripcion->GestorId = $request->Gestor;
        $suscripcion->CompaniaId = $request->CompaniaId;
        $suscripcion->ContratanteId = $request->ContratanteId;
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
        return redirect('suscripciones/' . $request->Id . '/edit?tab=1')->with('success', 'El registro ha sido modificado correctamente');
    }

    public function destroy($id)
    {
        //
        Comentarios::where('SuscripcionId', $id)->delete();
        Suscripcion::findOrFail($id)->delete();
        alert()->success('El registro ha sido eliminado correctamente');
        return back();
    }


    public function comentarios_update(Request $request, $id)
    {
        $comentario = Comentarios::findOrFail($id);
        $comentario->Comentario = $request->Comentario;
        $comentario->save();
        return redirect('suscripciones/' . $comentario->SuscripcionId . '/edit?tab=2')->with('success', 'El registro ha sido modificado correctamente');
    }


    public function comentarios_delete($id)
    {
        $comentario = Comentarios::findOrFail($id);
        $comentario->delete();
        return redirect('suscripciones/' . $comentario->SuscripcionId . '/edit?tab=2')->with('success', 'El registro ha sido eliminado correctamente');
    }
}
