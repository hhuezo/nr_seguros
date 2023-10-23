<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ClienteEstado;
use App\Models\catalogo\ClienteTarjetaCredito;
use App\Models\catalogo\Cotizacion;
use App\Models\catalogo\DepartamentoNR;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoVenta;
use App\Models\catalogo\FormaPago;
use App\Models\catalogo\Genero;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\NegocioAccidente;
use App\Models\catalogo\NegocioAuto;
use App\Models\catalogo\NegocioContacto;
use App\Models\catalogo\NegocioDineroValores;
use App\Models\catalogo\NegocioEquipoElectronico;
use App\Models\catalogo\NegocioGastosMedicos;
use App\Models\catalogo\NegocioIncendio;
use App\Models\catalogo\NegocioOtros;
use App\Models\catalogo\NegocioRoboHurto;
use App\Models\catalogo\NegocioVida;
use App\Models\catalogo\NegocioVideDeuda;
use App\Models\catalogo\NegocioVideDeudaCobertura;
use App\Models\catalogo\NrCartera;
use App\Models\catalogo\Parentesco;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoNegocio;
use App\Models\catalogo\TipoPoliza;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NegocioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $negocios = Negocio::where('Activo', 1)->get();
        return view('catalogo.negocio.index', compact('negocios'));
    }

    public function create()
    {
        $carteras =NrCartera::where('Activo', '=', 1)->get();
        $tipos_negocio = TipoNegocio::where('Activo', '=', 1)->get();
        $estados_venta = EstadoVenta::where('Activo', '=', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', '=', 1)->get();
        $necesidad_proteccion = NecesidadProteccion::where('Activo', 1)->get();
        $cliente_estado = ClienteEstado::get();
        $departamentosnr= DepartamentoNR::where('Activo', 1)->get();

        return view('catalogo.negocio.create', compact('departamentosnr','carteras','cliente_estado', 'tipos_negocio', 'estados_venta', 'ejecutivos', 'necesidad_proteccion'));
    }

    public function store(Request $request)
    {

        $time = Carbon::now();
        //diferenciar al tipo de cliente
        if ($request->TipoPersona == 1) { //cliente natural
            $cliente = Cliente::where('Dui', $request->Dui)->first();
        } else {
            $cliente = Cliente::where('Nit', $request->NitEmpresa)->first();
        }
        if (!$cliente) {
            $cliente = new Cliente();
            $cliente->TipoPersona = $request->TipoPersona;
            if ($request->TipoPersona == 1) {
                $cliente->Dui = $request->Dui;
            } else {
                $cliente->Nit = $request->NitEmpresas;
            }
            $cliente->Nombre = $request->NombreCliente;
            $cliente->FormaPago = $request->FormaPago;
            $cliente->Estado = 1;
            //$cliente->CorreoPrincipal=$request->Email;

            $cliente->save();
        }else{
            if ($cliente->Estado==2) {
                $cliente->Estado=3;
                $cliente->update();
            }
        }
        $negocio = new Negocio();
        $negocio->TipoCarteraNr = $request->TipoCarteraNr;
        $negocio->NumCoutas = $request->NumCoutas;
        $negocio->PeriodoPago = $request->FormaPago;
        $negocio->NecesidadProteccion = $request->NecesidadProteccion;
        $negocio->InicioVigencia = $request->InicioVigencia;
        $negocio->Cliente = $cliente->Id;
        $negocio->FechaVenta = $request->FechaVenta;
        $negocio->Observacion = $request->Observacion;
        $negocio->TipoNegocio = $request->TipoNegocio;
        $negocio->EstadoVenta = $request->EstadoVenta;
        $negocio->Ejecutivo = $request->Ejecutivo;
        $negocio->FechaIngreso = $time->toDateTimeString();
        $negocio->UsuarioIngreso = auth()->user()->id;
        $negocio->NumeroPoliza=$request->NumeroPoliza;
        $negocio->DepartamentoNr=$request->DepartamentoNr;
        $negocio->Activo=1;
        $negocio->save();

        session(['tab1' => 1]);
        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/negocio/' . $negocio->Id . '/edit');

    }

    public function show($id)
    {

        dd("holi show");
        // $ejecutivo = Ejecutivo::where('Activo', '1')->get();
        // return view('catalogo.negocio.show', compact('ejecutivo'));

    }

    public function edit($id)
    {
        if (session()->has('tab2')) {
            session(['tab1' => session('tab2')]);
            session(['tab2' => '1']);
        } else {
            session(['tab1' => '1']);
        }

        $negocio = Negocio::findOrFail($id);
        $carteras =NrCartera::where('Activo', '=', 1)->get();
        $tipos_negocio = TipoNegocio::where('Activo', '=', 1)->get();
        $estados_venta = EstadoVenta::where('Activo', '=', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', '=', 1)->get();
        $necesidad_proteccion = NecesidadProteccion::where('Activo', 1)->get();
        $cliente_estado = ClienteEstado::get();
        $departamentosnr= DepartamentoNR::where('Activo', 1)->get();
        $cotizaciones= Cotizacion::where('Negocio', $negocio->Id)->where('Activo', 1)->get();

        return view('catalogo.negocio.edit', compact('cotizaciones','negocio','departamentosnr','carteras','cliente_estado', 'tipos_negocio', 'estados_venta', 'ejecutivos', 'necesidad_proteccion'));
    }

    public function update(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        //diferenciar al tipo de cliente
        if ($request->TipoPersona == 1) { //cliente natural
            $cliente = Cliente::where('Dui', $request->Dui)->first();
        } else {
            $cliente = Cliente::where('Nit', $request->NitEmpresa)->first();
        }
        if (!$cliente) {
            $cliente = new Cliente();
            $cliente->TipoPersona = $request->TipoPersona;
            if ($request->TipoPersona == 1) {
                $cliente->Dui = $request->Dui;
            } else {
                $cliente->Nit = $request->NitEmpresas;
            }
            $cliente->Nombre = $request->NombreCliente;
            $cliente->FormaPago = $request->FormaPago;
            $cliente->Estado = 1;
            //$cliente->CorreoPrincipal=$request->Email;

            $cliente->save();
        }else{
            if ($cliente->Estado==2) {
                $cliente->Estado=3;
                $cliente->update();
            }
        }
        $negocio->TipoCarteraNr = $request->TipoCarteraNr;
        $negocio->NumCoutas = $request->NumCoutas;
        $negocio->PeriodoPago = $request->FormaPago;
        $negocio->NecesidadProteccion = $request->NecesidadProteccion;
        $negocio->InicioVigencia = $request->InicioVigencia;
        $negocio->Cliente = $cliente->Id;
        $negocio->FechaVenta = $request->FechaVenta;
        $negocio->Observacion = $request->Observacion;
        $negocio->TipoNegocio = $request->TipoNegocio;
        $negocio->EstadoVenta = $request->EstadoVenta;
        $negocio->Ejecutivo = $request->Ejecutivo;
        //$negocio->UsuarioIngreso = auth()->user()->id;
        $negocio->NumeroPoliza=$request->NumeroPoliza;
        $negocio->DepartamentoNr=$request->DepartamentoNr;
        $negocio->update();

        session(['tab1' => '1']);
        alert()->success('El registro ha sido modificado correctamente');
        return redirect('catalogo/negocio/' . $negocio->Id . '/edit');

    }

    public function destroy($id)
    {
        Negocio::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }

    function censorCreditCard($cardNumber,$visibleDigits) {
        //$visibleDigits = 4; // Number of visible digits at the end
        $cleanedCardNumber = preg_replace('/[^0-9]/', '', $cardNumber);
        $censoredCardNumber = '****-****-****-' . substr($cleanedCardNumber, -$visibleDigits);
        return $censoredCardNumber;
    }


    public function getCliente(Request $request)
    {
        //obtener el cliente
        if($request->IdCliente!=null){
            $cliente = Cliente::where('Id', $request->IdCliente)->first();
        }else{
            if ($request->tipoPersona == 1) {
                $cliente = Cliente::where('Dui', $request->Dui)->first();
            } else {
                $cliente = Cliente::where('Nit', $request->Nit)->first();
            }
        }

        if($cliente){
            $metodo_pago=ClienteTarjetaCredito::where('Cliente',$cliente->Id)->get();
            foreach ($metodo_pago as $tarjetas) {
                $censoredCardNumber = self::censorCreditCard($tarjetas->NumeroTarjeta,4);
                $tarjetas->NumeroTarjeta = $censoredCardNumber;
            }
        }else{
            $metodo_pago=null;
        }
        return response()->json(['cliente' => $cliente,'metodo_pago'=>$metodo_pago]);
    }

}
