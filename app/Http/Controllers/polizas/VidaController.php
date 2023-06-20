<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\CarteraImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoCobro;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\CarteraMensual;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaDetalle;
use App\Models\polizas\VidaUsuario;
use App\Models\temp\TempCartera;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Throwable;

class VidaController extends Controller
{
    public function index()
    {
        $vida = Vida::all();
        return view('polizas.vida.index', compact('vida'));
    }


    public function create()
    {
        $tipos_contribuyente =  TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro =  UbicacionCobro::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = $bombero->Valor;
        }
        $usuario_vidas = array();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->where('Poliza', 1)->get();  //vida
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        return view('polizas.vida.create', compact(
            'aseguradora',
            'cliente',
            'tipoCartera',
            'estadoPoliza',
            'tipoCobro',
            'ejecutivo',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'bomberos',
            'usuario_vidas'
        ));
    }

    public function get_cliente(Request $request)
    {
        $cliente = Cliente::findOrFail($request->Cliente);
        return $cliente;
    }

    public function store(Request $request)
    {
        $vida_codigo = Vida::where('Codigo', $request->Codigo)->first();
        if ($vida_codigo) {
            alert()->error('El Codigo ya fue utilizado');
            return back();
        } else {

            $vida = new Vida();
            $vida->NumeroPoliza = $request->NumeroPoliza;
            $vida->Nit = $request->Nit;
            $vida->Codigo = $request->Codigo;
            $vida->Aseguradora = $request->Aseguradora;
            $vida->Asegurado = $request->Asegurado;
            $vida->GrupoAsegurado = $request->GrupoAsegurado;
            $vida->VigenciaDesde = $request->VigenciaDesde;
            $vida->VigenciaHasta = $request->VigenciaHasta;
            $vida->BeneficiosAdicionales = $request->BeneficiosAdicionales;
            $vida->ClausulasEspeciales = $request->ClausulasEspeciales;
            $vida->Concepto = $request->Concepto;
            $vida->Ejecutivo = $request->Ejecutivo;
            $vida->TipoCartera = $request->TipoCartera;
            $vida->EstadoPoliza = $request->EstadoPoliza;
            $vida->TipoCobro = $request->TipoCobro;
            $vida->Tasa = $request->Tasa;
            $vida->MontoCartera = $request->MontoCartera;
            $vida->Mensual = $request->tipoTasa;
            $vida->TasaComision = $request->TasaComision;
            $vida->EdadTerminacion = $request->EdadTerminacion;
            $vida->EdadMaxTerminacion = $request->EdadMaxTerminacion;
            $vida->EdadIntermedia = $request->EdadIntermedia;
            $vida->LimiteMaxDeclaracion = $request->LimiteMaxDeclaracion;
            $vida->LimiteIntermedioDeclaracion = $request->LimiteIntermedioDeclaracion;
            $vida->LimiteGrupo = $request->LimiteGrupo;
            $vida->LimiteIndividual = $request->LimiteIndividual;
            $vida->Bomberos = $request->Bomberos;
            $vida->LimiteMenDeclaracion = $request->LimiteMenDeclaracion;
            $vida->TasaDescuento = $request->TasaDescuento;
            $vida->Activo = 1;
            $vida->save();

            //   $usuario_vidas = VidaUsuario::where('Poliza', $request->Poliza)->update(['Vida' => $vida->Id]);

            alert()->success('El registro ha sido creado correctamente');
            if ($request->TipoCobro == 1) {
                return Redirect::to('polizas/vida/' . $vida->Id . '/edit');
            } else {
                return Redirect::to('polizas/vida/create');
            }
        }
    }

    public function getUsuario($id)
    {
        $usuario_vidas = VidaUsuario::where('Poliza', $id)->get();
        return view('polizas.vida.tabla_usuario', compact('usuario_vidas'));
    }

    public function agregarUsuario(Request $request)
    {
        //agregar form de agregar usuario

        $usuario_vida = new VidaUsuario();
        $usuario_vida->Vida = $request->Vida;
        $usuario_vida->NumeroUsuario = $request->NumeroUsuario;
        $usuario_vida->SumaAsegurada = $request->SumaAsegurada;
        $usuario_vida->SubTotalAsegurado = $request->SubTotalAsegurado;
        $usuario_vida->Tasa = $request->Tasa;
        $usuario_vida->TotalAsegurado = $request->TotalAsegurado;
        $usuario_vida->save();


        $usuario_vidas = VidaUsuario::where('Vida', $request->Vida)->get();
        return view('polizas.vida.tabla_usuario', compact('usuario_vidas'));
    }

    public function eliminarUsuario(Request $request)
    {
        $usuario_vida = VidaUsuario::findOrFail($request->Id)->delete();
        return 'ok';
    }

    public function editarUsuario(Request $request)
    {
        $usuario_vida = VidaUsuario::findOrFail($request->Id);
        $usuario_vida->Poliza = $request->Poliza;
        $usuario_vida->Vida = $request->Vida;
        $usuario_vida->NumeroUsuario = $request->NumeroUsuario;
        $usuario_vida->SumaAsegurada = $request->SumaAsegurada;
        $usuario_vida->SubTotalAsegurado = $request->SubTotalAsegurado;
        $usuario_vida->Tasa = $request->Tasa;
        $usuario_vida->TotalAsegurado = $request->TotalAsegurado;
        $usuario_vida->update();

        return 'ok';
        /*$usuario_vidas = VidaUsuario::where('Poliza', $request->Poliza)->get();
        alert()->success('Usuario Modificado');
        return view('polizas.vida.tabla_usuario', compact('usuario_vidas'));*/
    }

    public function edit($id)
    {
        $vida = Vida::findOrFail($id);
        $detalle = VidaDetalle::where('Vida', $vida->Id)->get();
        $detalle_last = VidaDetalle::where('Vida', $vida->Id)->orderByDesc('PagoAplicado')->first();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();
        $tipoCartera = TipoCartera::where('Activo', 1)->get();
        $estadoPoliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = $bombero->Valor;
        }
        $usuario_vidas = VidaUsuario::where('Vida', $id)->get();
        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return view('polizas.vida.edit', compact('bomberos', 'vida', 'detalle', 'detalle_last', 'aseguradora', 'cliente', 'tipoCartera', 'estadoPoliza', 'tipoCobro', 'ejecutivo', 'usuario_vidas', 'meses'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->tipoTasa);
        $vida = Vida::findOrFail($id);
        $vida->NumeroPoliza = $request->NumeroPoliza;
        $vida->Nit = $request->Nit;
        $vida->Codigo = $request->Codigo;
        $vida->Aseguradora = $request->Aseguradora;
        $vida->Asegurado = $request->Asegurado;
        $vida->GrupoAsegurado = $request->GrupoAsegurado;
        $vida->VigenciaDesde = $request->VigenciaDesde;
        $vida->VigenciaHasta = $request->VigenciaHasta;
        $vida->BeneficiosAdicionales = $request->BeneficiosAdicionales;
        $vida->ClausulasEspeciales = $request->ClausulasEspeciales;
        $vida->Concepto = $request->Concepto;
        $vida->Ejecutivo = $request->Ejecutivo;
        $vida->TipoCartera = $request->TipoCartera;
        $vida->EstadoPoliza = $request->EstadoPoliza;
        $vida->TipoCobro = $request->TipoCobro;
        $vida->Tasa = $request->Tasa;
        $vida->MontoCartera = $request->MontoCartera;
        $vida->Mensual = $request->tipoTasa;
        $vida->TasaComision = $request->TasaComision;
        $vida->EdadTerminacion = $request->EdadTerminacion;
        $vida->EdadMaxTerminacion = $request->EdadMaxTerminacion;
        $vida->EdadIntermedia = $request->EdadIntermedia;
        $vida->LimiteMaxDeclaracion = $request->LimiteMaxDeclaracion;
        $vida->LimiteIntermedioDeclaracion = $request->LimiteIntermedioDeclaracion;
        $vida->LimiteGrupo = $request->LimiteGrupo;
        $vida->LimiteIndividual = $request->LimiteIndividual;
        $vida->Bomberos = $request->Bomberos;
        $vida->LimiteMenDeclaracion = $request->LimiteMenDeclaracion;
        $vida->TasaDescuento = $request->TasaDescuento;
        $vida->Activo = 1;
        $vida->update();

        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }

    public function destroy($id)
    {
        //
    }


    /*


DELIMITER $$

CREATE PROCEDURE `dateFormat`(`usuario` INT)
BEGIN
        update temp_cartera set FechaNacimientoDate = CONCAT(SUBSTRING(FechaNacimiento, 7,4), '-',SUBSTRING(FechaNacimiento, 4,2), '-',SUBSTRING(FechaNacimiento, 4,2)),
        FechaOtorgamientoDate = CONCAT(SUBSTRING(FechaOtorgamiento, 7,4), '-',SUBSTRING(FechaOtorgamiento, 4,2), '-',SUBSTRING(FechaOtorgamiento, 4,2)),
        FechaVencimientoDate = CONCAT(SUBSTRING(FechaVencimiento, 7,4), '-',SUBSTRING(FechaVencimiento, 4,2), '-',SUBSTRING(FechaVencimiento, 4,2)),
        Edad = TIMESTAMPDIFF(YEAR, FechaNacimientoDate , CURDATE()),
        EdadTerminacion = TIMESTAMPDIFF(YEAR, FechaNacimientoDate , FechaVencimientoDate),
        EdadOtorgamiento = TIMESTAMPDIFF(YEAR, FechaNacimientoDate , FechaOtorgamientoDate)
        where temp_cartera.Usuario = usuario;

END$$


DELIMITER $$
CREATE  PROCEDURE `create_cartera_mensual`(`usuario` INT, `fecha_inicio` DATE, `fecha_final` DATE)
BEGIN
	insert into cartera_mensual (
   Id
  ,Nit
  ,Dui
  ,Pasaporte
  ,Nacionalidad
  ,FechaNacimiento
  ,TipoPersona
  ,PrimerApellido
  ,SegundoApellido
  ,CasadaApellido
  ,PrimerNombre
  ,SegundoNombre
  ,SociedadNombre
  ,Sexo
  ,FechaOtorgamiento
  ,FechaVencimiento
  ,Ocupacion
  ,NoRefereciaCredito
  ,MontoOtorgado
  ,SaldoVigenteCapital
  ,Interes
  ,InteresMoratorio
  ,SaldoTotal
  ,TarifaMensual
  ,PrimaMensual
  ,TipoDeuda
  ,PorcentajeExtraprima
  ,Usuario
  ,FechaNacimientoDate
  ,FechaOtorgamientoDate
  ,FechaVencimientoDate
  ,Edad
  ,EdadTerminacion
  ,Vida
  ,Mes
  ,Axo
  ,FechaInicio
  ,FechaFinal,
  EdadOtorgamiento
) SELECT
   Id
  ,Nit
  ,Dui
  ,Pasaporte
  ,Nacionalidad
  ,FechaNacimiento
  ,TipoPersona
  ,PrimerApellido
  ,SegundoApellido
  ,CasadaApellido
  ,PrimerNombre
  ,SegundoNombre
  ,SociedadNombre
  ,Sexo
  ,FechaOtorgamiento
  ,FechaVencimiento
  ,Ocupacion
  ,NoRefereciaCredito
  ,MontoOtorgado
  ,SaldoVigenteCapital
  ,Interes
  ,InteresMoratorio
  ,SaldoTotal
  ,TarifaMensual
  ,PrimaMensual
  ,TipoDeuda
  ,PorcentajeExtraprima
  ,Usuario
  ,FechaNacimientoDate
  ,FechaOtorgamientoDate
  ,FechaVencimientoDate
  ,Edad
  ,EdadTerminacion
  ,1
  ,Mes
  ,Axo
  ,fecha_inicio
  ,fecha_final,
  EdadOtorgamiento
FROM temp_cartera where Usuario = usuario;

END$$


    */

    public function create_pago(Request $request)
    {



        $vida = Vida::findOrFail($request->Id);

        if ($request->Mes == 1) {
            $mes_evaluar = 12;
            $axo = $request->Axo - 1;
        } else {
            $mes_evaluar = $request->Mes - 1;
            $axo = $request->Axo;
        }

        try {
            $archivo = $request->Archivo;
            TempCartera::where('Usuario', '=', auth()->user()->id)->delete();
            Excel::import(new CarteraImport, $archivo);

            $datos = DB::select("call dateFormat(" . auth()->user()->id . ")");

            //$temp = TempCartera::where('Usuario', '=', auth()->user()->id)->get();

            $calculo_saldo = $temp = TempCartera::where('Usuario', '=', auth()->user()->id)->sum('SaldoVigenteCapital');


            //si hay validaciones
            if ($request->Validar == "on") {
                if ($calculo_saldo > $vida->LimiteGrupo) {
                    alert()->error('Error, el saldo supera el limite de grupo');
                    return back();
                }

                $calculo_limite_individual  = TempCartera::where('Usuario', '=', auth()->user()->id)
                    ->select(DB::raw('*,SUM(SaldoVigenteCapital) as suma'))
                    ->groupBy('Dui')
                    ->having('suma', '>', $vida->LimiteIndividual)
                    ->get();

                if ($calculo_limite_individual->count()>0) {
                    return view('polizas.validacion_cartera.resultado', compact('calculo_limite_individual'));
                }


                $nuevos = TempCartera::select('Id', 'Dui', 'Nit', 'PrimerApellido', 'SegundoApellido', 'CasadaApellido', 'PrimerNombre', 'SegundoNombre', 'SociedadNombre', 'NoRefereciaCredito', 'Edad', DB::raw('(select count(*) from cartera_mensual where
        (cartera_mensual.Dui = temp_cartera.Dui or cartera_mensual.Nit = temp_cartera.Nit) and temp_cartera.NoRefereciaCredito = cartera_mensual.NoRefereciaCredito
         and cartera_mensual.Mes = ' . $mes_evaluar . ' and  cartera_mensual.Axo = ' . $axo . ') as conteo'))
                    ->where('Usuario', '=', auth()->user()->id)
                    ->having('conteo', '=', 0)
                    ->get();

                return view('polizas.validacion_cartera.resultado', compact('nuevos'));
            }
        } catch (Throwable $e) {
            print($e);

            return false;
        }

        //dd(auth()->user()->id,$request->FechaInicio,$request->FechaFinal );
        $insert = DB::select("call create_cartera_mensual(" . auth()->user()->id . ",'$request->FechaInicio','$request->FechaFinal')");

        $monto_cartera = CarteraMensual::where('Mes', '=', $mes_evaluar)->where('Axo', '=', $axo)->where('Vida', '=', $vida->Id)->sum('SaldoTotal');

        //74126861.7

        if ($vida->Mesual == 0) {
            $tasaFinal = ($vida->Tasa / 1000) / 12;
        } else {
            $tasaFinal = $vida->Tasa / 1000;
        }

        $sub_total = $monto_cartera * $tasaFinal;

        $prima_total = $sub_total;
        $prima_descontada = $sub_total * 2;

        $time = Carbon::now('America/El_Salvador');

        $detalle = new VidaDetalle();
        $detalle->SaldoA = $request->SaldoA;
        $detalle->Vida = $vida->Id;
        //$detalle->Comentario = $request->Comentario;
        $detalle->Tasa = $tasaFinal;
        //$detalle->Comision = $request->Comision;
        $detalle->PrimaTotal = $prima_total;
        //$detalle->Descuento = $request->Descuento;
        //$detalle->ExtraPrima = $request->ExtraPrima;
        //$detalle->ValorCCF = $request->ValorCCF;
        $detalle->APagar = $sub_total;
        //$detalle->TasaComision = $request->TasaComision;
        $detalle->MontoCartera = $monto_cartera;
        $detalle->PrimaDescontada = $prima_descontada;
        //$detalle->ValorDescuento = $request->ValorDescuento;
        //$detalle->Retencion = $request->Retencion;
        //$detalle->IvaSobreComision = $request->IvaSobreComision;
        //$detalle->ImpresionRecibo = $time->toDateTimeString();
        $detalle->save();
        /*$detalle->EnvioCartera = $request->EnvioCartera;
        $detalle->EnvioPago = $request->EnvioPago;
        $detalle->PagoAplicado = $request->PagoAplicado;*/


        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function edit_pago(Request $request)
    {
        $detalle = VidaDetalle::findOrFail($request->Id);
        //dd($request->EnvioCartera .' 00:00:00');
        if ($request->EnvioCartera) {
            $detalle->EnvioCartera = $request->EnvioCartera;
        }
        if ($request->EnvioPago) {
            $detalle->EnvioPago = $request->EnvioPago;
        }
        if ($request->PagoAplicado) {
            $detalle->PagoAplicado = $request->PagoAplicado;
        }
        $detalle->Comentario = $request->Comentario;

        /*$detalle->EnvioPago = $request->EnvioPago;
        $detalle->PagoAplicado = $request->PagoAplicado;*/
        $detalle->update();
        alert()->success('El registro ha sido ingresado correctamente');
        return back();
    }

    public function get_pago($id)
    {
        return VidaDetalle::findOrFail($id);
    }

    public function renovar($id)
    {
        $vida = Vida::findOrFail($id);
        $estados_poliza = EstadoPoliza::where('Activo', 1)->get();
        $tipoCobro = TipoCobro::where('Activo', 1)->get();
        return view('polizas.vida.renovar', compact('vida', 'tipoCobro', 'estados_poliza'));
    }
    public function renovarPoliza(Request $request, $id)
    {
        $vida = Vida::findOrFail($id);
        $vida->Mensual = $request->Mensual; //valor de radio button
        $vida->EstadoPoliza = $request->EstadoPoliza;
        $vida->VigenciaDesde = $request->VigenciaDesde;
        $vida->VigenciaHasta = $request->VigenciaHasta;
        $vida->MontoCartera = $request->MontoCartera;
        $vida->Tasa = $request->Tasa;
        $vida->update();

        alert()->success('La poliza fue renovada correctamente');
        return back();
    }
}
