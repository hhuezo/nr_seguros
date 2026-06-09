<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ClienteEstado;
use App\Models\catalogo\ClienteTarjetaCredito;
use App\Models\catalogo\Cotizacion;
use App\Models\catalogo\DatosTecnicos;
use App\Models\catalogo\DepartamentoNR;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoVenta;
use App\Models\catalogo\FormaPago;
use App\Models\catalogo\Genero;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\NecesidadProteccionCampo;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\NegocioAccidente;
use App\Models\catalogo\NegocioAuto;
use App\Models\catalogo\NegocioContacto;
use App\Models\catalogo\NegocioDineroValores;
use App\Models\catalogo\NegocioDocumento;
use App\Models\catalogo\NegocioEquipoElectronico;
use App\Models\catalogo\NegocioGastosMedicos;
use App\Models\catalogo\NegocioGestiones;
use App\Models\catalogo\NegocioIncendio;
use App\Models\catalogo\NegocioOtros;
use App\Models\catalogo\NegocioRoboHurto;
use App\Models\catalogo\NegocioVida;
use App\Models\catalogo\NegocioVideDeuda;
use App\Models\catalogo\NegocioVideDeudaCobertura;
use App\Models\catalogo\NrCartera;
use App\Models\catalogo\Parentesco;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
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
                $cliente->Nit = $request->NitEmpresa;
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

       // dd("holi show");
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
        $cotizacionAprobada = $cotizaciones->firstWhere('Aceptado', 1) ?? $cotizaciones->first();
        $contactosNegocio= NegocioContacto::where('negocio', $negocio->Id)->where('Activo', 1)->get();
        $documentos = NegocioDocumento::where('Negocio', $negocio->Id)->where('Activo',1)->get();
        $gestiones=NegocioGestiones::where('Negocio', $negocio->Id)->where('Activo',1)->get();
        $ramoFormulario = $cotizaciones->count() == 0
            ? old('NecesidadProteccion', $negocio->NecesidadProteccion)
            : $negocio->NecesidadProteccion;
        $camposRamo = $this->camposRamoActivos($ramoFormulario);
        $datosRamo = json_decode($negocio->DatosRamo ?: '{}', true) ?: [];

        return view('catalogo.negocio.edit', compact('gestiones','documentos','contactosNegocio','cotizaciones','cotizacionAprobada','negocio','departamentosnr','carteras','cliente_estado', 'tipos_negocio', 'estados_venta', 'ejecutivos', 'necesidad_proteccion', 'camposRamo', 'datosRamo'));
    }

    public function update(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        $cotizaciones= Cotizacion::where('Negocio', $id)->where('Activo', 1)->get();

        try {
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
                $cliente->Nit = $request->NitEmpresa;
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
        if ($cotizaciones->count()==0) {
            $negocio->NecesidadProteccion = $request->NecesidadProteccion;
        }
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
        return redirect('catalogo/negocio/' . $negocio->Id . '/edit')
            ->with('success', 'El negocio se guardo correctamente.');
        } catch (\Throwable $e) {
            report($e);

            session(['tab1' => '1']);
            alert()->error('No se pudo guardar el negocio. Revise la informacion e intente nuevamente');

            return back()
                ->withInput()
                ->with('error', 'No se pudo guardar el negocio. Revise la informacion e intente nuevamente.');
        }

    }

    public function update_datos_ramo(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        $camposRamo = $this->camposRamoActivos($negocio->NecesidadProteccion);

        $request->validate($this->reglasCamposRamo($camposRamo));

        $negocio->DatosRamo = $this->buildDatosRamo($request->all(), $camposRamo);
        $negocio->update();

        session(['tab2' => '5']);
        alert()->success('Los datos generales del ramo se guardaron correctamente');

        return back()->with('success', 'Los datos generales del ramo se guardaron correctamente.');
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
                $cliente = Cliente::where('Dui', $request->Dui)->where('TipoPersona', $request->tipoPersona)->first();
            } else {
                $cliente = Cliente::where('Nit', $request->Nit)->where('TipoPersona', $request->tipoPersona)->first();
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

    public function getProducto(Request $request)
    {
        //obtener el producto
        $productos= Producto::where('NecesidadProteccion',$request->Ramo)->get();

        return response()->json(['datosRecibidos' => $productos]);
    }

    public function getPlan(Request $request)
    {
        //obtener el producto
        $planes= Plan::where('Producto',$request->Producto)->get();
        return response()->json([
            'datosRecibidos' => $planes,
        ]);
    }

    public function getCamposRamo(Request $request)
    {
        $campos = $this->camposRamoActivos($request->Ramo);

        return response()->json([
            'campos' => $campos,
        ]);
    }

    public function add_cotizacion(Request $request){
        $cotizacion = new Cotizacion();
        $cotizacion->Negocio = $request->Negocio;
        $cotizacion->Plan = $request->Plan;
        $cotizacion->SumaAsegurada = $request->SumaAsegurada;
        $cotizacion->PrimaNetaAnual = $request->PrimaNetaAnual;
        $cotizacion->Observaciones = $request->Observaciones;
        $cotizacion->DatosTecnicos = '{}';
        $cotizacion->Aceptado = 0;
        $cotizacion->Activo  = 1 ;
        $cotizacion->save();

        $negocio = Negocio::findOrFail($request->Negocio);

        if($negocio->NecesidadProteccion!= $cotizacion->planes->productos->NecesidadProteccion){
            $negocio->update(['NecesidadProteccion' => $cotizacion->planes->productos->NecesidadProteccion]);
        }

        alert()->success('El registro ha sido creado correctamente');

        session(['tab2' => '5']);
        return back();
    }

    public function edit_cotizacion(Request $request){
        $cotizacion = Cotizacion::findOrFail($request->Id);

        $cotizacion->SumaAsegurada = $request->SumaAsegurada;
        $cotizacion->PrimaNetaAnual = $request->PrimaNetaAnual;
        $cotizacion->Observaciones = $request->Observaciones;


        $cotizacion->update();


        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '5']);
        return back();
    }

    public function elegirCotizacion(Request $request){
        $cotizaciones= Cotizacion::where('Negocio',$request->Negocio)->where('Aceptado',1)->first();
        if ($cotizaciones) {
            $cotizaciones->update(['Aceptado' => 0]);
        }
        Cotizacion::findOrFail($request->CotizacionId)->update(['Aceptado' => 1]);

        return response()->json(['exito' => 1]);
    }

    public function delete_cotizacion(Request $request){
        Cotizacion::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '5']);
        return back();
    }

    public function add_informacion_negocio(Request $request){
        $informacion_negocio = new NegocioContacto();
        $informacion_negocio->negocio = $request->Negocio;
        $informacion_negocio->Contacto = $request->Contacto;
        $informacion_negocio->DescripcionOperacion  = $request->DescripcionOperacion ;
        $informacion_negocio->TelefonoContacto  = $request->TelefonoContacto ;
        $informacion_negocio->ObservacionContacto  = $request->ObservacionContacto ;
        $informacion_negocio->Activo  = 1 ;

        $informacion_negocio->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function edit_informacion_negocio(Request $request){
        $informacion_negocio = NegocioContacto::findOrFail($request->Id);

        $informacion_negocio->Contacto = $request->Contacto;
        $informacion_negocio->DescripcionOperacion  = $request->DescripcionOperacion ;
        $informacion_negocio->TelefonoContacto  = $request->TelefonoContacto ;
        $informacion_negocio->ObservacionContacto  = $request->ObservacionContacto ;
        $informacion_negocio->update();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function delete_informacion_negocio(Request $request){
        NegocioContacto::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function agregar_documento(Request $request)
    {
        $archivo = $request->file('Archivo');

        $id = uniqid();
        $filePath =  $id . $archivo->getClientOriginalName();
        $archivo->move(public_path("documentos/negocios/"), $filePath);


        $documento = new NegocioDocumento();
        $documento->Negocio = $request->input('Negocio');
        $documento->Nombre = $filePath;
        $documento->NombreOriginal = $archivo->getClientOriginalName();
        $documento->Activo = 1;
        $documento->save();

        $filePath = 'documentos/negocios/' . $archivo->getClientOriginalName();

        alert()->success('El registro ha sido creado correctamente');
        session(['tab2' => '3']);
        return back();
    }


    public function eliminar_documento($id)
    {
        $documento = NegocioDocumento::findOrFail($id);
        $documento->Activo = 0;
        $documento->save();

        alert()->success('El registro ha sido eliminado correctamente');
        session(['tab2' => '3']);
        return back();
    }

    public function add_gestion(Request $request){
        $gestion = new NegocioGestiones();
        $gestion->Negocio = $request->Negocio;
        $gestion->DescripcionActividad = $request->DescripcionActividad;
        $gestion->Usuario  = auth()->user()->id;
        $gestion->FechaHora  = Carbon::now();
        $gestion->Activo  = 1 ;

        $gestion->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab2' => '4']);
        return back();
    }

    public function edit_gestion(Request $request){
        $gestion = NegocioGestiones::findOrFail($request->Id);

        $gestion->DescripcionActividad = $request->DescripcionActividad;
        $gestion->Usuario  = auth()->user()->id;
        $gestion->FechaHora  = Carbon::now();
        $gestion->update();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '4']);
        return back();
    }

    public function delete_gestion(Request $request){
        NegocioGestiones::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '4']);
        return back();
    }

    private function buildDatosVariables(array $data): string
    {
        $jsonObject = [];

        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $jsonObject[$key] = $value;
            }
        }

        return json_encode($jsonObject);
    }

    private function camposRamoActivos($ramoId)
    {
        if (empty($ramoId)) {
            return collect();
        }

        return NecesidadProteccionCampo::where('NecesidadProteccion', $ramoId)
            ->where('Activo', 1)
            ->orderBy('Id', 'asc')
            ->get();
    }

    private function reglasCamposRamo($campos, string $prefix = 'ramo_'): array
    {
        $reglas = [];

        foreach ($campos as $campo) {
            $nombre = $prefix . $campo->Id;
            $campoReglas = [$campo->Requerido ? 'required' : 'nullable'];

            switch ($campo->TipoCampo) {
                case 'number':
                    $campoReglas[] = 'numeric';
                    break;
                case 'date':
                    $campoReglas[] = 'date';
                    break;
                case 'email':
                    $campoReglas[] = 'email';
                    break;
                case 'textarea':
                case 'text':
                default:
                    $campoReglas[] = 'string';
                    break;
            }

            switch ($campo->ValidacionCampo) {
                case 'dui':
                    $campoReglas[] = 'regex:/^\d{8}-\d$/';
                    break;
                case 'solo_numeros':
                    $campoReglas[] = 'regex:/^\d+$/';
                    break;
                case 'solo_numeros_letras':
                    $campoReglas[] = 'regex:/^[A-Za-z0-9]+$/';
                    break;
                case 'solo_texto':
                    $campoReglas[] = 'regex:/^[\pL\s\.,#\-\/()&@\'\":;]+$/u';
                    break;
                case 'correo':
                    if (!in_array('email', $campoReglas, true)) {
                        $campoReglas[] = 'email';
                    }
                    break;
            }

            $reglas[$nombre] = $campoReglas;
        }

        return $reglas;
    }

    private function buildDatosRamo(array $data, $campos, string $prefix = 'ramo_'): string
    {
        if ($campos->isEmpty()) {
            return '{}';
        }

        $jsonObject = [];

        foreach ($campos as $campo) {
            $nombre = $prefix . $campo->Id;
            $jsonObject[$campo->Id] = array_key_exists($nombre, $data) ? $data[$nombre] : null;
        }

        return json_encode($jsonObject, JSON_UNESCAPED_UNICODE);
    }

}
