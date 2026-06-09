<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Cobertura;
use App\Models\catalogo\DatosTecnicos;
use App\Models\catalogo\Deducible;
use App\Models\catalogo\DepartamentoNR;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\MotivoCancelacion;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\NecesidadProteccionCampo;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\OrigenPoliza;
use App\Models\catalogo\Parentesco;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\ProductoCertificadoCampo;
use App\Models\catalogo\TipoCartera;
use App\Models\polizas\PolizaSeguroBeneficiario;
use App\Models\polizas\PolizaSeguro;
use App\Models\polizas\PolizaSeguroCertificado;
use App\Models\polizas\PolizaSeguroCertificadoDependiente;
use App\Models\polizas\PolizaSeguroCesionBeneficio;
use App\Models\polizas\PolizaSeguroCobertura;
use App\Models\polizas\PolizaSeguroDatosTecnicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PolizaSeguroController extends Controller
{
    private function ofertasAceptadas($ofertaActual = null)
    {
        return Negocio::with(['clientes', 'cotizaciones' => function ($query) {
            $query->where('Activo', 1)
                ->where('Aceptado', 1)
                ->with('planes.productos.ramos');
        }])
            ->where(function ($query) use ($ofertaActual) {
                $query->whereHas('cotizaciones', function ($cotizacion) {
                    $cotizacion->where('Activo', 1)->where('Aceptado', 1);
                });

                if ($ofertaActual) {
                    $query->orWhere('Id', $ofertaActual);
                }
            })
            ->orderByDesc('Id')
            ->get();
    }

    private function certificadoCampos($productoId)
    {
        return ProductoCertificadoCampo::where('Activo', 1)
            ->where('Producto', $productoId)
            ->orderBy('Orden', 'asc')
            ->orderBy('Id', 'asc')
            ->get();
    }

    private function camposRamoPoliza(PolizaSeguro $poliza)
    {
        $ramoId = optional($poliza->producto)->NecesidadProteccion
            ?: optional($poliza->oferta)->NecesidadProteccion;

        if (!$ramoId) {
            return collect();
        }

        return NecesidadProteccionCampo::where('Activo', 1)
            ->where('NecesidadProteccion', $ramoId)
            ->orderBy('Id', 'asc')
            ->get();
    }

    private function decodeDatosRamo($json): array
    {
        if ($json === null || trim((string) $json) === '') {
            return [];
        }

        $datos = json_decode($json, true);

        return is_array($datos) ? $datos : [];
    }

    private function valoresDatosRamoPoliza(PolizaSeguro $poliza): array
    {
        if ($poliza->DatosRamo !== null && trim((string) $poliza->DatosRamo) !== '') {
            return $this->decodeDatosRamo($poliza->DatosRamo);
        }

        return $poliza->oferta ? $this->decodeDatosRamo($poliza->oferta->DatosRamo) : [];
    }

    private function reglasCamposRamo($campos): array
    {
        $reglas = [];

        foreach ($campos as $campo) {
            $rule = ((int) $campo->Requerido === 1) ? 'required' : 'nullable';
            $validacion = $campo->ValidacionCampo ?? 'ninguna';

            if ($validacion === 'correo' || $campo->TipoCampo === 'email') {
                $rule .= '|email';
            } elseif ($validacion === 'dui') {
                $rule .= '|regex:/^\d{8}-\d$/';
            } elseif ($validacion === 'solo_numeros') {
                $rule .= '|regex:/^\d+$/';
            } elseif ($validacion === 'solo_numeros_letras') {
                $rule .= '|regex:/^[A-Za-z0-9]+$/';
            } elseif ($validacion === 'solo_texto') {
                $rule .= '|regex:/^[\pL\s\.,#\-\/()&@\'":;]+$/u';
            } elseif ($campo->TipoCampo === 'number') {
                $rule .= '|numeric';
            } elseif ($campo->TipoCampo === 'date') {
                $rule .= '|date';
            }

            $reglas['DatosRamo.' . $campo->Id] = $rule;
        }

        return $reglas;
    }

    private function buildDatosRamo(Request $request, $campos): string
    {
        if ($campos->isEmpty()) {
            return '{}';
        }

        $valores = $request->input('DatosRamo', []);
        $datos = [];

        foreach ($campos as $campo) {
            $datos[$campo->Id] = array_key_exists($campo->Id, $valores) ? $valores[$campo->Id] : null;
        }

        return json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    private function validarProductoPlan(Request $request)
    {
        $producto = Producto::where('Activo', 1)->find($request->Productos);

        if (!$producto) {
            return ['Productos' => 'Debe seleccionar un producto activo.'];
        }

        if ($request->filled('Ramo') && (int) $producto->NecesidadProteccion !== (int) $request->Ramo) {
            return ['Productos' => 'El producto seleccionado no pertenece al ramo indicado.'];
        }

        if ($request->filled('Aseguradora') && (int) $producto->Aseguradora !== (int) $request->Aseguradora) {
            return ['Productos' => 'El producto seleccionado no pertenece a la aseguradora indicada.'];
        }

        $planExiste = Plan::where('Activo', 1)
            ->where('Producto', $producto->Id)
            ->where('Id', $request->Planes)
            ->exists();

        if (!$planExiste) {
            return ['Planes' => 'El plan seleccionado no pertenece al producto indicado.'];
        }

        return [];
    }

    private function reglasCamposCertificado($campos)
    {
        $rules = [];

        foreach ($campos as $campo) {
            $rule = ((int) $campo->Requerido === 1) ? 'required' : 'nullable';

            $reglaValidacion = $campo->ValidacionCampo ?? 'ninguna';

            if ($reglaValidacion === 'correo' || $campo->TipoCampo === 'email') {
                $rule .= '|email';
            } elseif ($reglaValidacion === 'dui') {
                $rule .= '|regex:/^\d{8}-\d$/';
            } elseif ($reglaValidacion === 'solo_numeros') {
                $rule .= '|regex:/^\d+$/';
            } elseif ($reglaValidacion === 'solo_numeros_letras') {
                $rule .= '|regex:/^[A-Za-z0-9]+$/';
            } elseif ($reglaValidacion === 'solo_texto') {
                $rule .= '|regex:/^[\pL\s\.,#\-\/()&@\'":;]+$/u';
            } elseif ($campo->TipoCampo === 'number') {
                $rule .= '|numeric';
            } elseif ($campo->TipoCampo === 'date') {
                $rule .= '|date';
            }

            $rules['campos.' . $campo->Id] = $rule;
        }

        return $rules;
    }

    private function buildDatosCertificado(Request $request, $campos)
    {
        $valores = $request->input('campos', []);

        return $campos->map(function ($campo) use ($valores) {
            return [
                'CampoId' => $campo->Id,
                'NombreCampo' => $campo->NombreCampo,
                'Etiqueta' => $campo->Etiqueta,
                'TipoCampo' => $campo->TipoCampo,
                'ValidacionCampo' => $campo->ValidacionCampo ?? 'ninguna',
                'MostrarEnReporte' => (int) ($campo->MostrarEnReporte ?? 0),
                'Valor' => $valores[$campo->Id] ?? null,
            ];
        })->values()->all();
    }

    private function siguienteNumeroCertificado($polizaId)
    {
        $ultimo = PolizaSeguroCertificado::where('PolizaSeguroId', $polizaId)->max('NumeroCertificado');
        return ((int) $ultimo) + 1;
    }

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
        $ramos = NecesidadProteccion::where('Activo', 1)->get();
        $clientes = Cliente::where('Activo', 1)->get();
        $ofertas = $this->ofertasAceptadas();
        $tipo_cartera_nr = TipoCartera::get();
        $forma_pago = [0 => '', 1 => 'Anual', 2 => 'Semestral', 3 => 'Trimestral', 4 => 'Mensual'];
        $estado_poliza = EstadoPoliza::get();
        $motivos_cancelacion = MotivoCancelacion::where('Activo', 1)->get();
        $origen_poliza = OrigenPoliza::where('Activo', 1)->get();
        $tipo_deducible = Deducible::where('Activo', 1)->get();
        $departamento_nr = DepartamentoNR::where('Activo', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', 1)->get();
        //dd($ofertas);

        return view('polizas.seguro.create', compact('motivos_cancelacion', 'origen_poliza', 'tipo_deducible', 'departamento_nr', 'estado_poliza', 'forma_pago', 'tipo_cartera_nr', 'ofertas', 'productos', 'planes', 'aseguradora', 'ramos', 'clientes', 'ejecutivos'));
    }

    public function get_oferta(Request $request)
    {
        try {
            $negocio = Negocio::with(['clientes', 'cotizaciones' => function ($query) {
                $query->where('Activo', 1)
                    ->where('Aceptado', 1)
                    ->with('planes.productos.ramos');
            }])->findOrFail($request->Oferta);

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
                'numero_documento' => $negocio->clientes->Dui ?? $negocio->clientes->Nit ?? '',
                'nombre_cliente' => $negocio->clientes->Nombre ?? '',
                'forma_pago' => $negocio->PeriodoPago,
                'num_cuotas' => $negocio->NumCoutas,
                'numero_poliza' => $negocio->NumeroPoliza,
                'vigencia_desde' => $negocio->InicioVigencia,
                'departamento' => $negocio->DepartamentoNr,
                'ejecutivo' => $negocio->Ejecutivo,
                'observacion' => $negocio->Observacion,
                'ramo' => $cotizacion->planes->productos->NecesidadProteccion,
                'aseguradora' => $cotizacion->planes->productos->Aseguradora,
                'productos' => $cotizacion->planes->productos->Id,
                'planes' => $cotizacion->planes->Id,
                'porcentaje_comision_nr' => optional($cotizacion->planes->productos->ramos)->PorcentajeComisionNoDeclarativa,
                'cotizacion' => [
                    'id' => $cotizacion->Id,
                    'suma_asegurada' => $cotizacion->SumaAsegurada,
                    'prima_neta_anual' => $cotizacion->PrimaNetaAnual,
                    'observaciones' => $cotizacion->Observaciones,
                ],
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
            'Oferta' => 'nullable|integer',
            'Ramo' => 'required|integer',
            'Aseguradora' => 'required|integer',
            'FormaPago' => 'required|integer',
            'NumCuotas' => 'nullable|integer',
            'NumeroPoliza' => 'required|string|max:100',
            'EstadoPoliza' => 'required|integer',
            'Productos' => 'required|integer',
            'Planes' => 'required|integer',
            'SumaAsegurada' => 'nullable|numeric',
            'PrimaNetaAnual' => 'nullable|numeric',
            'PorcentajeComisionNR' => 'nullable|numeric',
            'ValorDeducible' => 'nullable|numeric',
            //'IdCliente' => 'required',
            'Cliente' => 'required|integer',
            'VigenciaDesde' => 'required|date',
            'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
            'DiasVigencia' => 'nullable|integer',
            // agrega más validaciones según el campo si es necesario
        ]);

        $ofertaAceptada = null;
        if ($request->filled('Oferta')) {
            $ofertaAceptada = Negocio::where('Id', $request->Oferta)
                ->whereHas('cotizaciones', function ($query) {
                    $query->where('Activo', 1)->where('Aceptado', 1);
                })
                ->first();

            if (!$ofertaAceptada) {
                return redirect()->back()
                    ->withErrors(['Oferta' => 'Debe seleccionar una oferta con cotizacion aceptada.'])
                    ->withInput();
            }
        }

        $erroresProductoPlan = $this->validarProductoPlan($request);
        if (!empty($erroresProductoPlan)) {
            return redirect()->back()
                ->withErrors($erroresProductoPlan)
                ->withInput();
        }

        $poliza_seguro = new PolizaSeguro();
        $poliza_seguro->Oferta = $request->filled('Oferta') ? $request->Oferta : null;
        $poliza_seguro->FormaPago = $request->FormaPago;
        $poliza_seguro->NumCuotas = $request->NumCuotas;
        $poliza_seguro->NumeroPoliza = $request->NumeroPoliza;
        $poliza_seguro->EstadoPoliza = $request->EstadoPoliza;
        $poliza_seguro->Productos = $request->Productos;
        $poliza_seguro->Planes = $request->Planes;
        $poliza_seguro->SumaAsegurada = $request->SumaAsegurada;
        $poliza_seguro->PrimaNetaAnual = $request->PrimaNetaAnual;
        $poliza_seguro->PorcentajeComisionNR = $request->PorcentajeComisionNR;
        $poliza_seguro->Cliente = $request->Cliente;
        $poliza_seguro->VigenciaDesde = $request->VigenciaDesde;
        $poliza_seguro->VigenciaHasta = $request->VigenciaHasta;
        $poliza_seguro->DiasVigencia = $request->DiasVigencia;
        $poliza_seguro->MotivoCancelacion = $request->MotivoCancelacion;
        $poliza_seguro->FechaCancelacion = $request->FechaCancelacion;
        $poliza_seguro->CodCancelacion = $request->CodCancelacion;
        $poliza_seguro->FechaEnvioAnexo = $request->FechaEnvioAnexo;
        $poliza_seguro->Observacion = $request->Observacion;
        $poliza_seguro->DatosRamo = $ofertaAceptada ? $ofertaAceptada->DatosRamo : null;
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
        $poliza_seguro->ValorDeducible = $request->ValorDeducible;
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

        return redirect('poliza/seguro/' . $poliza_seguro->Id . '/edit?tab=4')->with('success', 'Se creo la poliza de seguro');
    }


    public function show($id, Request $request)
    {
        $tab = $request->tab ?? 1;
        $poliza_seguro = PolizaSeguro::with([
            'oferta',
            'certificados.dependientes',
            'beneficiarios.parentesco',
            'cesionBeneficios',
            'producto.certificadoCampos',
        ])->findOrFail($id);
        //$coberturas = PolizaSeguroCobertura::where('PolizaSeguro', $id)->get();
        //$datos_tecnicos = PolizaSeguroDatosTecnicos::where('PolizaSeguro', $id)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', 1)->get();
        $clientes = Cliente::where('Activo', 1)->get();
        $ofertas = $this->ofertasAceptadas($poliza_seguro->Oferta);
        $tipo_cartera_nr = TipoCartera::get();
        $forma_pago = [0 => '', 1 => 'Anual', 2 => 'Semestral', 3 => 'Trimestral', 4 => 'Mensual'];
        $estado_poliza = EstadoPoliza::get();
        $motivos_cancelacion = MotivoCancelacion::where('Activo', 1)->get();
        $origen_poliza = OrigenPoliza::where('Activo', 1)->get();
        $tipo_deducible = Deducible::where('Activo', 1)->get();
        $departamento_nr = DepartamentoNR::where('Activo', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', 1)->get();
        $parentescos = Parentesco::where('Activo', 1)->get();
        $certificado_campos = $this->certificadoCampos($poliza_seguro->Productos);
        $permite_dependientes = (int) ($poliza_seguro->producto->PermiteDependientesCertificado ?? 0);
        $siguiente_certificado = $this->siguienteNumeroCertificado($poliza_seguro->Id);
        $cesion_beneficios = $poliza_seguro->cesionBeneficios;
        $total_beneficiarios = $poliza_seguro->beneficiarios->sum('Porcentaje');
        $campos_ramo = $this->camposRamoPoliza($poliza_seguro);
        $datos_ramo = $this->valoresDatosRamoPoliza($poliza_seguro);

        //dd($poliza_seguro->oferta->clientes->Nombre);
        return view('polizas.seguro.show', compact('tab', 'poliza_seguro', 'motivos_cancelacion', 'origen_poliza', 'tipo_deducible', 'departamento_nr', 'estado_poliza', 'forma_pago', 'tipo_cartera_nr', 'ofertas', 'productos', 'planes', 'aseguradora', 'ramos', 'clientes', 'ejecutivos', 'parentescos', 'certificado_campos', 'permite_dependientes', 'siguiente_certificado', 'cesion_beneficios', 'total_beneficiarios', 'campos_ramo', 'datos_ramo'));
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
            'valor' => 'nullable|string|max:1000',
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

        $request->validate([
            'Oferta' => 'nullable|integer',
            'Ramo' => 'required|integer',
            'Aseguradora' => 'required|integer',
            'FormaPago' => 'required|integer',
            'NumCuotas' => 'nullable|integer',
            'NumeroPoliza' => 'required|string|max:100',
            'EstadoPoliza' => 'required|integer',
            'Productos' => 'required|integer',
            'Planes' => 'required|integer',
            'SumaAsegurada' => 'nullable|numeric',
            'PrimaNetaAnual' => 'nullable|numeric',
            'PorcentajeComisionNR' => 'nullable|numeric',
            'ValorDeducible' => 'nullable|numeric',
            'Cliente' => 'required|integer',
            'VigenciaDesde' => 'required|date',
            'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
            'DiasVigencia' => 'nullable|integer',
        ]);

        $erroresProductoPlan = $this->validarProductoPlan($request);
        if (!empty($erroresProductoPlan)) {
            return redirect()->back()
                ->withErrors($erroresProductoPlan)
                ->withInput();
        }

        $poliza_seguro->FormaPago = $request->FormaPago;
        $poliza_seguro->NumCuotas = $request->NumCuotas;
        $poliza_seguro->NumeroPoliza = $request->NumeroPoliza;
        $poliza_seguro->EstadoPoliza = $request->EstadoPoliza;
        $poliza_seguro->Productos = $request->Productos;
        $poliza_seguro->Planes = $request->Planes;
        $poliza_seguro->SumaAsegurada = $request->SumaAsegurada;
        $poliza_seguro->PrimaNetaAnual = $request->PrimaNetaAnual;
        $poliza_seguro->PorcentajeComisionNR = $request->PorcentajeComisionNR;
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
        $poliza_seguro->ValorDeducible = $request->ValorDeducible;
        $poliza_seguro->Activo = $request->Activo;
        $poliza_seguro->Usuario = $request->Usuario;
        $poliza_seguro->update();

        alert()->success('Se creo la poliza de seguro');
        return back();
    }

    public function certificado_store($id, Request $request)
    {
        $poliza = PolizaSeguro::findOrFail($id);
        $campos = $this->certificadoCampos($poliza->Productos);
        $numeroCertificado = $this->siguienteNumeroCertificado($poliza->Id);

        $request->validate(array_merge([
            'Observacion' => 'nullable|string|max:500',
        ], $this->reglasCamposCertificado($campos)));

        if (PolizaSeguroCertificado::where('PolizaSeguroId', $poliza->Id)
            ->where('NumeroCertificado', $numeroCertificado)
            ->exists()) {
            return redirect()->back()
                ->withErrors(['NumeroCertificado' => 'El numero de certificado ya existe para esta poliza.'])
                ->withInput();
        }

        $certificado = new PolizaSeguroCertificado();
        $certificado->PolizaSeguroId = $poliza->Id;
        $certificado->NumeroCertificado = $numeroCertificado;
        $certificado->DatosJson = json_encode($this->buildDatosCertificado($request, $campos), JSON_UNESCAPED_UNICODE);
        $certificado->Observacion = $request->Observacion;
        $certificado->Activo = 1;
        $certificado->save();

        return redirect('poliza/seguro/' . $poliza->Id . '/edit?tab=4')
            ->with('success', 'Certificado creado correctamente');
    }

    public function certificado_delete($id)
    {
        $certificado = PolizaSeguroCertificado::findOrFail($id);
        $certificado->Activo = 0;
        $certificado->update();

        return redirect('poliza/seguro/' . $certificado->PolizaSeguroId . '/edit?tab=4')
            ->with('success', 'Certificado eliminado correctamente');
    }

    public function certificado_update($id, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza.producto')->findOrFail($id);
        $campos = $this->certificadoCampos($certificado->poliza->Productos);

        $request->validate(array_merge([
            'Observacion' => 'nullable|string|max:500',
        ], $this->reglasCamposCertificado($campos)));

        $certificado->DatosJson = json_encode($this->buildDatosCertificado($request, $campos), JSON_UNESCAPED_UNICODE);
        $certificado->Observacion = $request->Observacion;
        $certificado->update();

        return redirect('poliza/seguro/' . $certificado->PolizaSeguroId . '/edit?tab=4')
            ->with('success', 'Certificado actualizado correctamente');
    }

    public function dependiente_store($certificadoId, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza.producto')->findOrFail($certificadoId);

        if ((int) ($certificado->poliza->producto->PermiteDependientesCertificado ?? 0) !== 1) {
            return redirect()->back()
                ->withErrors(['Dependientes' => 'El producto de esta poliza no permite dependientes.']);
        }

        $campos = $this->certificadoCampos($certificado->poliza->Productos);
        $request->validate(array_merge([
            'Observacion' => 'nullable|string|max:500',
        ], $this->reglasCamposCertificado($campos)));

        $ultimo = PolizaSeguroCertificadoDependiente::where('PolizaSeguroCertificadoId', $certificado->Id)
            ->max('NumeroDependiente');

        $dependiente = new PolizaSeguroCertificadoDependiente();
        $dependiente->PolizaSeguroCertificadoId = $certificado->Id;
        $dependiente->NumeroDependiente = ((int) $ultimo) + 1;
        $dependiente->DatosJson = json_encode($this->buildDatosCertificado($request, $campos), JSON_UNESCAPED_UNICODE);
        $dependiente->Observacion = $request->Observacion;
        $dependiente->Activo = 1;
        $dependiente->save();

        return redirect('poliza/seguro/' . $certificado->PolizaSeguroId . '/edit?tab=4')
            ->with('success', 'Dependiente agregado correctamente');
    }

    public function dependiente_delete($id)
    {
        $dependiente = PolizaSeguroCertificadoDependiente::with('certificado')->findOrFail($id);
        $dependiente->Activo = 0;
        $dependiente->update();

        return redirect('poliza/seguro/' . $dependiente->certificado->PolizaSeguroId . '/edit?tab=4')
            ->with('success', 'Dependiente eliminado correctamente');
    }

    public function dependiente_update($id, Request $request)
    {
        $dependiente = PolizaSeguroCertificadoDependiente::with('certificado.poliza.producto')->findOrFail($id);
        $certificado = $dependiente->certificado;
        $campos = $this->certificadoCampos($certificado->poliza->Productos);

        $request->validate(array_merge([
            'Observacion' => 'nullable|string|max:500',
        ], $this->reglasCamposCertificado($campos)));

        $dependiente->DatosJson = json_encode($this->buildDatosCertificado($request, $campos), JSON_UNESCAPED_UNICODE);
        $dependiente->Observacion = $request->Observacion;
        $dependiente->update();

        return redirect('poliza/seguro/' . $certificado->PolizaSeguroId . '/edit?tab=4')
            ->with('success', 'Dependiente actualizado correctamente');
    }

    public function beneficiario_store($id, Request $request)
    {
        $poliza = PolizaSeguro::findOrFail($id);

        $request->validate([
            'Nombre' => 'required|string|max:200',
            'Parentesco' => 'nullable|integer|exists:parentesco,Id',
            'FechaNacimiento' => 'nullable|date',
            'Porcentaje' => 'required|numeric|min:0.01|max:100',
        ]);

        $totalActual = PolizaSeguroBeneficiario::where('PolizaSeguroId', $poliza->Id)
            ->where('Activo', 1)
            ->sum('Porcentaje');

        if (($totalActual + (float) $request->Porcentaje) > 100) {
            return redirect()->back()
                ->withErrors(['Porcentaje' => 'La suma de porcentajes de beneficiarios no puede superar el 100%.'])
                ->withInput();
        }

        $beneficiario = new PolizaSeguroBeneficiario();
        $beneficiario->PolizaSeguroId = $poliza->Id;
        $beneficiario->Nombre = $request->Nombre;
        $beneficiario->Parentesco = $request->Parentesco;
        $beneficiario->FechaNacimiento = $request->FechaNacimiento;
        $beneficiario->Porcentaje = $request->Porcentaje;
        $beneficiario->Activo = 1;
        $beneficiario->save();

        return redirect('poliza/seguro/' . $poliza->Id . '/edit?tab=6')
            ->with('success', 'Beneficiario agregado correctamente');
    }

    public function beneficiario_delete($id)
    {
        $beneficiario = PolizaSeguroBeneficiario::findOrFail($id);
        $beneficiario->Activo = 0;
        $beneficiario->update();

        return redirect('poliza/seguro/' . $beneficiario->PolizaSeguroId . '/edit?tab=6')
            ->with('success', 'Beneficiario eliminado correctamente');
    }

    public function cesion_beneficios_store($id, Request $request)
    {
        $poliza = PolizaSeguro::findOrFail($id);

        $request->validate([
            'CodigoSesion' => 'required|string|max:100',
            'Beneficiario' => 'required|string|max:200',
            'FechaVigencia' => 'nullable|date',
            'FechaCancelacion' => 'nullable|date|after_or_equal:FechaVigencia',
            'SumaCedida' => 'nullable|numeric|min:0',
            'Observaciones' => 'nullable|string|max:1000',
            'Propietario' => 'nullable|string|max:200',
        ]);

        $cesion = new PolizaSeguroCesionBeneficio();
        $cesion->PolizaSeguroId = $poliza->Id;
        $cesion->CodigoSesion = $request->CodigoSesion;
        $cesion->Beneficiario = $request->Beneficiario;
        $cesion->FechaVigencia = $request->FechaVigencia;
        $cesion->FechaCancelacion = $request->FechaCancelacion;
        $cesion->SumaCedida = $request->SumaCedida;
        $cesion->Observaciones = $request->Observaciones;
        $cesion->Propietario = $request->Propietario;
        $cesion->Activo = 1;
        $cesion->save();

        return redirect('poliza/seguro/' . $poliza->Id . '/edit?tab=5')
            ->with('success', 'Cesion de beneficios agregada correctamente');
    }

    public function cesion_beneficios_update($id, Request $request)
    {
        $cesion = PolizaSeguroCesionBeneficio::findOrFail($id);

        $request->validate([
            'CodigoSesion' => 'required|string|max:100',
            'Beneficiario' => 'required|string|max:200',
            'FechaVigencia' => 'nullable|date',
            'FechaCancelacion' => 'nullable|date|after_or_equal:FechaVigencia',
            'SumaCedida' => 'nullable|numeric|min:0',
            'Observaciones' => 'nullable|string|max:1000',
            'Propietario' => 'nullable|string|max:200',
        ]);

        $cesion->CodigoSesion = $request->CodigoSesion;
        $cesion->Beneficiario = $request->Beneficiario;
        $cesion->FechaVigencia = $request->FechaVigencia;
        $cesion->FechaCancelacion = $request->FechaCancelacion;
        $cesion->SumaCedida = $request->SumaCedida;
        $cesion->Observaciones = $request->Observaciones;
        $cesion->Propietario = $request->Propietario;
        $cesion->update();

        return redirect('poliza/seguro/' . $cesion->PolizaSeguroId . '/edit?tab=5')
            ->with('success', 'Cesion de beneficios actualizada correctamente');
    }

    public function cesion_beneficios_delete($id)
    {
        $cesion = PolizaSeguroCesionBeneficio::findOrFail($id);
        $cesion->Activo = 0;
        $cesion->update();

        return redirect('poliza/seguro/' . $cesion->PolizaSeguroId . '/edit?tab=5')
            ->with('success', 'Cesion de beneficios eliminada correctamente');
    }

    public function datos_tecnicos_save($id, Request $request)
    {
        $poliza = PolizaSeguro::with(['producto', 'oferta'])->findOrFail($id);
        $camposRamo = $this->camposRamoPoliza($poliza);

        $request->validate($this->reglasCamposRamo($camposRamo));

        $poliza->DatosRamo = $this->buildDatosRamo($request, $camposRamo);
        $poliza->update();

        $valores = $request->input('DatosTecnicos', []);

        foreach ($poliza->datosTecnicos as $dato) {
            $dato->Valor = $valores[$dato->Id] ?? null;
            $dato->update();
        }

        return redirect('poliza/seguro/' . $poliza->Id . '/edit?tab=3')
            ->with('success', 'Datos tecnicos actualizados correctamente');
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






    public function edit($id, Request $request)
    {
        return $this->show($id, $request);
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
