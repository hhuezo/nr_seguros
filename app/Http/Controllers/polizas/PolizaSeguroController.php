<?php

namespace App\Http\Controllers\polizas;

use App\Exports\PolizaSeguroCertificadosExport;
use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Cesionario;
use App\Models\catalogo\DatosTecnicos;
use App\Models\catalogo\Deducible;
use App\Models\catalogo\DepartamentoNR;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoCertificado;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\FormaPagoPoliza;
use App\Models\catalogo\MotivoCancelacion;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\NrCartera;
use App\Models\catalogo\OrigenPoliza;
use App\Models\catalogo\Parentesco;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\ProductoCertificadoCampo;
use App\Models\polizas\PolizaSeguroBeneficiario;
use App\Models\polizas\PolizaSeguro;
use App\Models\polizas\PolizaSeguroCertificado;
use App\Models\polizas\PolizaSeguroCertificadoCobertura;
use App\Models\polizas\PolizaSeguroCertificadoDatoTecnico;
use App\Models\polizas\PolizaSeguroCertificadoDependiente;
use App\Models\polizas\PolizaSeguroCesionBeneficio;
use App\Models\polizas\PolizaSeguroRenovacion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PolizaSeguroController extends Controller
{
    private function textoMayuscula($value)
    {
        if ($value === null || !is_string($value)) {
            return $value;
        }

        return mb_strtoupper($value, 'UTF-8');
    }

    private function normalizarCamposMayuscula(Request $request, array $campos): void
    {
        $valores = [];

        foreach ($campos as $campo) {
            if ($request->exists($campo)) {
                $valores[$campo] = $this->textoMayuscula($request->input($campo));
            }
        }

        if (!empty($valores)) {
            $request->merge($valores);
        }
    }

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

    private function productoCertificadoIdPorPlan($planId, $productoFallback)
    {
        if ($planId) {
            $productoPlan = Plan::where('Activo', 1)
                ->where('Id', $planId)
                ->value('Producto');

            if ($productoPlan) {
                return $productoPlan;
            }
        }

        return $productoFallback;
    }

    private function productoConfigCertificado($planId, $productoFallback)
    {
        $productoId = $this->productoCertificadoIdPorPlan($planId, $productoFallback);

        return Producto::find($productoId);
    }

    private function parentescosCatalogo()
    {
        $activos = Parentesco::where('Activo', 1)->orderBy('Nombre')->get();

        return $activos->isNotEmpty()
            ? $activos
            : Parentesco::orderBy('Nombre')->get();
    }

    private function catalogoTipoCarteraNr()
    {
        return NrCartera::where('Activo', 1)
            ->orderBy('Nombre', 'asc')
            ->get();
    }

    private function sincronizarResumenPolizaDesdeCertificados(PolizaSeguro $poliza): void
    {
        $totales = PolizaSeguroCertificado::where('PolizaSeguroId', $poliza->Id)
            ->where('Activo', 1)
            ->selectRaw('COALESCE(SUM(ValorAsegurado), 0) as TotalSumaAsegurada')
            ->selectRaw('COALESCE(SUM(PrimaNeta), 0) as TotalPrimaNeta')
            ->first();

        $poliza->SumaAsegurada = $totales->TotalSumaAsegurada ?? 0;
        $poliza->PrimaNetaAnual = $totales->TotalPrimaNeta ?? 0;
        $poliza->save();
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

            if (($campo->OrigenOpciones ?? 'manual') === 'catalogo' && $campo->CatalogoOrigen === 'parentesco_beneficiario') {
                $rule .= '|integer|exists:parentesco,Id';
            } elseif ($reglaValidacion === 'correo' || $campo->TipoCampo === 'email') {
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
        $parentescos = collect();

        if ($campos->contains(function ($campo) {
            return ($campo->OrigenOpciones ?? 'manual') === 'catalogo'
                && $campo->CatalogoOrigen === 'parentesco_beneficiario';
        })) {
            $parentescos = Parentesco::where('Activo', 1)
                ->orWhereIn('Id', collect($valores)->filter(fn($valor) => is_numeric($valor))->values()->all())
                ->get()
                ->keyBy('Id');
        }

        return $campos->map(function ($campo) use ($valores, $parentescos) {
            $valor = $valores[$campo->Id] ?? null;
            $valorId = null;

            if (($campo->OrigenOpciones ?? 'manual') === 'catalogo' && $campo->CatalogoOrigen === 'parentesco_beneficiario') {
                $valorId = $valor !== null && $valor !== '' ? (int) $valor : null;
                $valor = $valorId ? ($parentescos[$valorId]->Nombre ?? null) : null;
            }

            return [
                'CampoId' => $campo->Id,
                'NombreCampo' => $campo->NombreCampo,
                'Etiqueta' => $campo->Etiqueta,
                'TipoCampo' => $campo->TipoCampo,
                'ValidacionCampo' => $campo->ValidacionCampo ?? 'ninguna',
                'OrigenOpciones' => $campo->OrigenOpciones ?? 'manual',
                'CatalogoOrigen' => $campo->CatalogoOrigen,
                'MostrarEnReporte' => (int) ($campo->MostrarEnReporte ?? 0),
                'ValorId' => $valorId,
                'Valor' => $valor,
            ];
        })->values()->all();
    }

    private function valoresDatosCertificado($json): array
    {
        if ($json === null || trim((string) $json) === '') {
            return [];
        }

        $datos = json_decode($json, true);

        return is_array($datos)
            ? collect($datos)->mapWithKeys(function ($dato) {
                return [$dato['CampoId'] => $dato['ValorId'] ?? $dato['Valor'] ?? null];
            })->all()
            : [];
    }

    private function catalogosOpcionesCertificado($campos): array
    {
        $catalogos = [];

        if ($campos->contains(function ($campo) {
            return ($campo->OrigenOpciones ?? 'manual') === 'catalogo'
                && $campo->CatalogoOrigen === 'parentesco_beneficiario';
        })) {
            $catalogos['parentesco_beneficiario'] = $this->parentescosCatalogo()
                ->map(function ($parentesco) {
                    return [
                        'Id' => $parentesco->Id,
                        'Nombre' => $parentesco->Nombre,
                    ];
                })
                ->values()
                ->all();
        }

        return $catalogos;
    }

    private function siguienteNumeroCertificado($polizaId)
    {
        $ultimo = PolizaSeguroCertificado::where('PolizaSeguroId', $polizaId)->max('NumeroCertificado');
        return ((int) $ultimo) + 1;
    }

    private function documentoContratanteCertificado($cliente): string
    {
        if (!$cliente) {
            return '';
        }

        // El certificado 1 nace con el documento del contratante; juridicos usan NIT como identificador principal.
        if ((int) ($cliente->TipoPersona ?? 0) === 2) {
            return $cliente->Nit ?: ($cliente->Dui ?: ($cliente->Pasaporte ?? ''));
        }

        return $cliente->Dui ?: ($cliente->Nit ?: ($cliente->Pasaporte ?? ''));
    }

    private function sexoContratanteCertificado($cliente): ?string
    {
        if (!$cliente) {
            return null;
        }

        return match ((int) ($cliente->Genero ?? 0)) {
            1 => 'M',
            2 => 'F',
            default => null,
        };
    }

    private function normalizarOpcionalesCertificado(Request $request): void
    {
        $request->merge([
            'FechaNacimiento' => $request->filled('FechaNacimiento') ? $request->FechaNacimiento : null,
            'Sexo' => $request->filled('Sexo') ? $request->Sexo : null,
        ]);
    }

    private function reglasCertificadoBase(): array
    {
        return [
            'CertificadoAseguradora' => 'nullable|string|max:100',
            'CodAsegurado' => 'required|string|max:100',
            'Asegurado' => 'required|string|max:200',
            'FechaNacimiento' => 'nullable|date',
            'Sexo' => 'nullable|in:M,F',
            'VigenciaDesde' => 'required|date',
            'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
            'FechaInclusion' => 'required|date',
            'EstadoCertificado' => 'required|integer|exists:estado_certificado,Id',
            'MotivoCancelacion' => 'nullable|integer|exists:motivo_cancelacion,Id',
            'MotivoExclusion' => 'nullable|string|max:200',
            'FechaExclusion' => 'nullable|date',
            'ValorAsegurado' => 'nullable|numeric|min:0',
            'PrimaTotal' => 'nullable|numeric|min:0',
            'PorcentajeDescuentoRentabilidad' => 'nullable|numeric|min:0|max:100',
            'ValorDescuento' => 'nullable|numeric|min:0',
            'PorcentajeDescuentoBuenaExperiencia' => 'nullable|numeric|min:0|max:100',
            'ValorDescuentoBuenaExperiencia' => 'nullable|numeric|min:0',
            'PorcentajeOtrosDescuentos' => 'nullable|numeric|min:0|max:100',
            'ValorOtrosDescuentos' => 'nullable|numeric|min:0',
            'PrimaNeta' => 'nullable|numeric|min:0',
            'PrimaExenta' => 'nullable|numeric|min:0',
            'GastosEmision' => 'nullable|numeric|min:0',
            'GastosFraccionamiento' => 'nullable|numeric|min:0',
            'GastosBomberos' => 'nullable|numeric|min:0',
            'OtrosGastos' => 'nullable|numeric|min:0',
            'Impuestos' => 'nullable|numeric|min:0',
            'TotalCertificado' => 'nullable|numeric|min:0',
            'Observacion' => 'nullable|string|max:500',
        ];
    }

    private function calcularDiasCertificado($desde, $hasta): ?int
    {
        if (!$desde || !$hasta) {
            return null;
        }

        return Carbon::parse($desde)->diffInDays(Carbon::parse($hasta));
    }

    private function calcularDetalleCobroCertificado(
        $primaTotal,
        $porcentajeDescuentoRentabilidad,
        $porcentajeDescuentoBuenaExperiencia = null,
        $porcentajeOtrosDescuentos = null,
        $primaExenta = null,
        $gastosEmision = null,
        $gastosFraccionamiento = null,
        $gastosBomberos = null,
        $otrosGastos = null,
        bool $aplicaIva = false
    ): array
    {
        $primaTotal = (float) ($primaTotal ?? 0);
        $porcentajeDescuentoRentabilidad = (float) ($porcentajeDescuentoRentabilidad ?? 0);
        $porcentajeDescuentoBuenaExperiencia = (float) ($porcentajeDescuentoBuenaExperiencia ?? 0);
        $porcentajeOtrosDescuentos = (float) ($porcentajeOtrosDescuentos ?? 0);
        $primaExenta = max((float) ($primaExenta ?? 0), 0);
        $gastosEmision = max((float) ($gastosEmision ?? 0), 0);
        $gastosFraccionamiento = max((float) ($gastosFraccionamiento ?? 0), 0);
        $gastosBomberos = max((float) ($gastosBomberos ?? 0), 0);
        $otrosGastos = max((float) ($otrosGastos ?? 0), 0);

        $valorDescuentoRentabilidad = round($primaTotal * ($porcentajeDescuentoRentabilidad / 100), 2);
        $baseBuenaExperiencia = max($primaTotal - $valorDescuentoRentabilidad, 0);

        $valorDescuentoBuenaExperiencia = round($baseBuenaExperiencia * ($porcentajeDescuentoBuenaExperiencia / 100), 2);
        $baseOtrosDescuentos = max($baseBuenaExperiencia - $valorDescuentoBuenaExperiencia, 0);

        $valorOtrosDescuentos = round($baseOtrosDescuentos * ($porcentajeOtrosDescuentos / 100), 2);
        $primaNeta = round(max($baseOtrosDescuentos - $valorOtrosDescuentos, 0), 2);
        $baseImponible = max($primaNeta - $primaExenta, 0);
        $impuestos = $aplicaIva ? round($baseImponible * 0.13, 2) : 0.0;
        $totalGastos = round($gastosEmision + $gastosFraccionamiento + $gastosBomberos + $otrosGastos, 2);
        $totalCertificado = round($primaNeta + $totalGastos + $impuestos, 2);

        return [
            'ValorDescuento' => $valorDescuentoRentabilidad,
            'ValorDescuentoBuenaExperiencia' => $valorDescuentoBuenaExperiencia,
            'ValorOtrosDescuentos' => $valorOtrosDescuentos,
            'PrimaNeta' => $primaNeta,
            'Impuestos' => $impuestos,
            'TotalCertificado' => $totalCertificado,
        ];
    }

    private function normalizarValorSn($valor): string
    {
        return strtoupper((string) $valor) === 'S' ? 'S' : 'N';
    }

    private function tarifaPlanTexto(?Plan $plan): ?string
    {
        if (!$plan) {
            return null;
        }

        $plan->loadMissing(['planesCoberturaDetalles' => function ($query) {
            $query->where('Activo', 1);
        }]);

        $tarifas = collect($plan->planesCoberturaDetalles ?? [])
            ->pluck('Tasa')
            ->filter(function ($tasa) {
                return $tasa !== null && $tasa !== '';
            })
            ->map(function ($tasa) {
                return rtrim(rtrim(number_format((float) $tasa, 6, '.', ''), '0'), '.');
            })
            ->unique()
            ->values();

        $texto = $tarifas->implode(' / ');

        return $texto !== '' ? $texto : null;
    }

    private function textoCatalogo($modelo, $id, string $campo = 'Nombre'): ?string
    {
        if (!$id) {
            return null;
        }

        $registro = $modelo::find($id);

        return $registro->{$campo} ?? null;
    }

    private function textoNumero($valor, int $decimales = 2): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        return number_format((float) $valor, $decimales, '.', ',');
    }

    private function snapshotRenovacionPoliza(PolizaSeguro $poliza): array
    {
        $poliza->loadMissing([
            'clientes',
            'forma_pago',
            'estado_polizas',
            'cancelacion',
            'tipoCarteraNr',
            'ejecutivoCia',
            'producto.aseguradoras',
            'producto.ramos',
            'plan.planesCoberturaDetalles' => function ($query) {
                $query->where('Activo', 1);
            },
        ]);

        return [
            'Oferta' => $poliza->Oferta ? '#' . $poliza->Oferta : null,
            'NumeroVigencia' => $poliza->NumeroVigencia,
            'EstadoPoliza' => optional($poliza->estado_polizas)->Nombre,
            'Cliente' => optional($poliza->clientes)->Nombre,
            'NumeroDocumento' => $poliza->clientes->Dui ?? $poliza->clientes->Nit ?? null,
            'FechaVinculacion' => $poliza->FechaVinculacion,
            'VigenciaDesde' => $poliza->VigenciaDesde,
            'VigenciaHasta' => $poliza->VigenciaHasta,
            'DiasVigencia' => $poliza->DiasVigencia,
            'FormaPago' => optional($poliza->forma_pago)->Nombre,
            'NumCuotas' => $poliza->NumCuotas,
            'PorcentajeComisionNR' => $this->textoNumero($poliza->PorcentajeComisionNR, 4),
            'Ramo' => optional(optional($poliza->producto)->ramos)->Nombre,
            'Aseguradora' => optional(optional($poliza->producto)->aseguradoras)->Nombre,
            'Producto' => optional($poliza->producto)->Nombre,
            'Plan' => optional($poliza->plan)->Nombre,
            'TarifaPlan' => $this->tarifaPlanTexto($poliza->plan),
            'OrigenPoliza' => $this->textoCatalogo(OrigenPoliza::class, $poliza->OrigenPoliza),
            'SustituidaPoliza' => $poliza->SustituidaPoliza,
            'SumaAsegurada' => $this->textoNumero($poliza->SumaAsegurada),
            'PrimaNetaAnual' => $this->textoNumero($poliza->PrimaNetaAnual),
            'IvaIncluido' => $this->normalizarValorSn($poliza->IvaIncluido) === 'S' ? 'Si' : 'No',
            'PorcentajeDescuentoRentabilidad' => $this->textoNumero($poliza->PorcentajeDescuentoRentabilidad, 4),
            'PorcentajeDescuentoBuenaExperiencia' => $this->textoNumero($poliza->PorcentajeDescuentoBuenaExperiencia, 4),
            'PorcentajeOtrosDescuentos' => $this->textoNumero($poliza->PorcentajeOtrosDescuentos, 4),
            'PorcentajeComsionCliente' => $this->textoNumero($poliza->PorcentajeComsionCliente, 4),
            'ClausulasEspeciales' => $poliza->ClausulasEspeciales,
            'BeneficiosAdicionales' => $poliza->BeneficiosAdicionales,
            'Comentarios' => $poliza->Comentarios,
            'TipoCarteraNR' => optional($poliza->tipoCarteraNr)->Nombre,
            'EjecutivoCia' => optional($poliza->ejecutivoCia)->Nombre,
            'Deducible' => $this->textoCatalogo(Deducible::class, $poliza->Deducible),
            'FechaCancelacion' => $poliza->FechaCancelacion,
            'CodCancelacion' => optional($poliza->cancelacion)->Nombre,
            'MotivoCancelacion' => $poliza->MotivoCancelacion,
        ];
    }

    private function cambiosRenovacionDesdeSnapshots(array $anterior, array $nuevo): array
    {
        $campos = [
            'NumeroVigencia' => 'Num. vigencia',
            'EstadoPoliza' => 'Estado poliza',
            'Cliente' => 'Cliente',
            'NumeroDocumento' => 'Numero documento',
            'FechaVinculacion' => 'Fecha vinculacion',
            'VigenciaDesde' => 'Vigencia desde',
            'VigenciaHasta' => 'Vigencia hasta',
            'DiasVigencia' => 'Dias vigencia',
            'FormaPago' => 'Forma de pago',
            'NumCuotas' => 'Num. cuotas',
            'PorcentajeComisionNR' => '% Comision NR',
            'Ramo' => 'Ramo',
            'Aseguradora' => 'Aseguradora',
            'Producto' => 'Producto',
            'Plan' => 'Plan',
            'TarifaPlan' => 'Tarifa',
            'OrigenPoliza' => 'Origen poliza',
            'SustituidaPoliza' => 'Sustituye poliza',
            'SumaAsegurada' => 'Suma asegurada',
            'PrimaNetaAnual' => 'Prima neta anual',
            'IvaIncluido' => 'Iva incluido',
            'PorcentajeDescuentoRentabilidad' => '% Descuento rentabilidad',
            'PorcentajeDescuentoBuenaExperiencia' => '% Descuento buena experiencia',
            'PorcentajeOtrosDescuentos' => '% Otros descuentos',
            'PorcentajeComsionCliente' => '% Comision cliente',
            'ClausulasEspeciales' => 'Clausulas especiales',
            'BeneficiosAdicionales' => 'Beneficios adicionales',
            'Comentarios' => 'Comentarios',
            'TipoCarteraNR' => 'Tipo de cartera',
            'EjecutivoCia' => 'Ejecutivo que atiende',
            'Deducible' => 'Deducible',
            'FechaCancelacion' => 'Fecha cancelacion',
            'CodCancelacion' => 'Motivo cancelacion',
            'MotivoCancelacion' => 'Observaciones cancelacion',
        ];

        $cambios = [];

        foreach ($campos as $clave => $etiqueta) {
            $valorAnterior = trim((string) ($anterior[$clave] ?? ''));
            $valorNuevo = trim((string) ($nuevo[$clave] ?? ''));

            if ($valorAnterior !== $valorNuevo) {
                $cambios[] = [
                    'campo' => $etiqueta,
                    'anterior' => $valorAnterior !== '' ? $valorAnterior : '-',
                    'nuevo' => $valorNuevo !== '' ? $valorNuevo : '-',
                ];
            }
        }

        return $cambios;
    }

    private function registrarRenovacionPoliza(PolizaSeguro $poliza, string $tipoRenovacion, array $snapshot, ?array $cambios = null): void
    {
        $tipoRenovacion = strtoupper($tipoRenovacion);
        $renovacion = new PolizaSeguroRenovacion();
        $renovacion->PolizaSeguroId = $poliza->Id;
        $renovacion->TipoRenovacion = $tipoRenovacion;
        $renovacion->EstadoPoliza = $poliza->EstadoPoliza;
        $renovacion->NumeroVigencia = $poliza->NumeroVigencia;
        $renovacion->VigenciaDesde = $poliza->VigenciaDesde;
        $renovacion->VigenciaHasta = $poliza->VigenciaHasta;
        $renovacion->TarifaPlan = $snapshot['TarifaPlan'] ?? null;
        $renovacion->SumaAsegurada = $tipoRenovacion === 'EMISION' ? 0 : $poliza->SumaAsegurada;
        $renovacion->PrimaNetaAnual = $tipoRenovacion === 'EMISION' ? 0 : $poliza->PrimaNetaAnual;
        $renovacion->DatosPolizaJson = json_encode($snapshot, JSON_UNESCAPED_UNICODE);
        $renovacion->CambiosJson = $cambios !== null ? json_encode($cambios, JSON_UNESCAPED_UNICODE) : null;
        $renovacion->Usuario = auth()->id();
        $renovacion->FechaRegistro = Carbon::now('America/El_Salvador')->format('Y-m-d H:i:s');
        $renovacion->Activo = 1;
        $renovacion->save();
    }

    private function asegurarRenovacionInicial(PolizaSeguro $poliza): void
    {
        if (PolizaSeguroRenovacion::where('PolizaSeguroId', $poliza->Id)->where('Activo', 1)->exists()) {
            return;
        }

        $snapshot = $this->snapshotRenovacionPoliza($poliza);
        $this->registrarRenovacionPoliza($poliza, 'EMISION', $snapshot, null);
    }

    private function asignarDatosCertificado(PolizaSeguroCertificado $certificado, Request $request, ?PolizaSeguro $poliza = null): void
    {
        $poliza = $poliza ?: $certificado->poliza;
        $aplicaIva = $poliza && $this->normalizarValorSn($poliza->IvaIncluido) === 'S';
        $certificado->CertificadoAseguradora = $request->CertificadoAseguradora;
        $certificado->CodAsegurado = $request->CodAsegurado;
        $certificado->Asegurado = $request->Asegurado;
        $certificado->FechaNacimiento = $request->filled('FechaNacimiento') ? $request->FechaNacimiento : null;
        $certificado->Sexo = $request->filled('Sexo') ? $request->Sexo : null;
        $certificado->VigenciaDesde = $request->VigenciaDesde;
        $certificado->VigenciaHasta = $request->VigenciaHasta;
        $certificado->FechaInclusion = $request->FechaInclusion;
        $certificado->DiasVigencia = $this->calcularDiasCertificado($request->VigenciaDesde, $request->VigenciaHasta);
        $estadoCertificado = EstadoCertificado::find($request->EstadoCertificado);
        $certificado->EstadoCertificado = $request->EstadoCertificado;
        $certificado->Estado = $estadoCertificado->Nombre ?? null;
        $certificado->MotivoCancelacion = $request->MotivoCancelacion;
        $certificado->MotivoExclusion = $request->MotivoExclusion;
        $certificado->FechaExclusion = $request->FechaExclusion;
        $certificado->ValorAsegurado = $request->ValorAsegurado;
        $certificado->PrimaTotal = $request->PrimaTotal;
        $certificado->PorcentajeDescuentoRentabilidad = $request->PorcentajeDescuentoRentabilidad;
        $certificado->PorcentajeDescuentoBuenaExperiencia = $request->PorcentajeDescuentoBuenaExperiencia;
        $certificado->PorcentajeOtrosDescuentos = $request->PorcentajeOtrosDescuentos;
        $certificado->PrimaExenta = $request->PrimaExenta;
        $certificado->GastosEmision = $request->GastosEmision;
        $certificado->GastosFraccionamiento = $request->GastosFraccionamiento;
        $certificado->GastosBomberos = $request->GastosBomberos;
        $certificado->OtrosGastos = $request->OtrosGastos;
        $detalleCobro = $this->calcularDetalleCobroCertificado(
            $request->PrimaTotal,
            $request->PorcentajeDescuentoRentabilidad,
            $request->PorcentajeDescuentoBuenaExperiencia,
            $request->PorcentajeOtrosDescuentos,
            $request->PrimaExenta,
            $request->GastosEmision,
            $request->GastosFraccionamiento,
            $request->GastosBomberos,
            $request->OtrosGastos,
            $aplicaIva
        );
        $certificado->ValorDescuento = $detalleCobro['ValorDescuento'];
        $certificado->ValorDescuentoBuenaExperiencia = $detalleCobro['ValorDescuentoBuenaExperiencia'];
        $certificado->ValorOtrosDescuentos = $detalleCobro['ValorOtrosDescuentos'];
        $certificado->PrimaNeta = $detalleCobro['PrimaNeta'];
        $certificado->Impuestos = $detalleCobro['Impuestos'];
        $certificado->TotalCertificado = $detalleCobro['TotalCertificado'];
        $certificado->Observacion = $request->Observacion;
        $certificado->UsuarioModifica = auth()->id();
        $certificado->FechaModificacion = now();
    }

    private function datosSumasCertificado(PolizaSeguro $poliza, PolizaSeguroCertificado $certificado): array
    {
        $planesCertificado = Plan::where('Activo', 1)
            ->where('Producto', $poliza->Productos)
            ->orderBy('Nombre', 'asc')
            ->get();

        $planCoberturasCatalogo = DB::table('plan_cobertura_detalle as detalle')
            ->leftJoin('cobertura as cobertura', 'cobertura.Id', '=', 'detalle.Cobertura')
            ->leftJoin('cobertura_tarificacion as tarificacion', 'tarificacion.Id', '=', 'cobertura.Tarificacion')
            ->whereIn('detalle.Plan', $planesCertificado->pluck('Id'))
            ->where('detalle.Activo', 1)
            ->select([
                'detalle.Plan',
                'detalle.Cobertura',
                DB::raw('COALESCE(detalle.Tarificacion, cobertura.Tarificacion) as Tarificacion'),
                DB::raw('COALESCE(detalle.TarificacionNombre, tarificacion.Nombre) as TarificacionNombre'),
                'detalle.CoberturaPrincipal',
                'cobertura.Nombre',
                'detalle.SumaAsegurada',
                'detalle.Tasa',
                'detalle.Prima',
            ])
            ->orderBy('detalle.Plan')
            ->orderBy('cobertura.Nombre')
            ->get()
            ->groupBy('Plan')
            ->map(function ($detalles) {
                return $detalles->map(function ($detalle) {
                    return [
                        'Cobertura' => $detalle->Cobertura,
                        'Tarificacion' => $detalle->Tarificacion,
                        'TarificacionNombre' => $detalle->TarificacionNombre,
                        'CoberturaPrincipal' => (int) ($detalle->CoberturaPrincipal ?? 0),
                        'Nombre' => $detalle->Nombre ?? '',
                        'SumaAsegurada' => $detalle->SumaAsegurada,
                        'PorcentajeSuma' => null,
                        'Tasa' => $detalle->Tasa,
                        'DiasProrrata' => null,
                        'PrimaAnual' => $detalle->Prima,
                        'Prima' => null,
                    ];
                })->values();
            });

        $planCertificadoActual = $certificado->Plan ?: $poliza->Planes;
        $coberturasGuardadas = $certificado->exists
            ? $certificado->coberturasCertificado
            : collect();

        $certificadoCoberturas = $coberturasGuardadas->count() > 0
            ? $coberturasGuardadas->map(function ($detalle) {
                $tarificacionCatalogo = optional(optional($detalle->cobertura)->tarificacion);

                return [
                    'Cobertura' => $detalle->Cobertura,
                    'Tarificacion' => $detalle->Tarificacion ?? optional($detalle->cobertura)->Tarificacion,
                    'TarificacionNombre' => $detalle->TarificacionNombre ?? $tarificacionCatalogo->Nombre,
                    'Nombre' => $detalle->Nombre,
                    'SumaAsegurada' => $detalle->SumaAsegurada,
                    'PorcentajeSuma' => $detalle->PorcentajeSuma,
                    'Tasa' => $detalle->Tasa,
                    'DiasProrrata' => $detalle->DiasProrrata,
                    'PrimaAnual' => $detalle->PrimaAnual,
                    'Prima' => $detalle->Prima,
                ];
            })->values()
            : collect($planCoberturasCatalogo->get($planCertificadoActual, []));

        return compact(
            'planesCertificado',
            'planCoberturasCatalogo',
            'planCertificadoActual',
            'certificadoCoberturas'
        );
    }

    private function datosTecnicosCertificado(PolizaSeguroCertificado $certificado): array
    {
        $productoCertificado = $this->productoConfigCertificado($certificado->Plan, $certificado->poliza->Productos)
            ?: $certificado->poliza->producto;

        $datosTecnicosCertificado = DatosTecnicos::where('Activo', 1)
            ->where('Producto', $productoCertificado->Id ?? $certificado->poliza->Productos)
            ->orderBy('Id', 'asc')
            ->get();

        $valoresDatosTecnicosCertificado = $certificado->datosTecnicosCertificado
            ->keyBy('DatoTecnicoId');

        return compact('datosTecnicosCertificado', 'valoresDatosTecnicosCertificado');
    }

    public function index()
    {
        $polizas = PolizaSeguro::get();
        return view('polizas.seguro.index', compact('polizas'));
    }


    public function create()
    {
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::with(['planesCoberturaDetalles' => function ($query) {
            $query->where('Activo', 1);
        }])->where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', 1)->get();
        $clientes = Cliente::where('Activo', 1)->get();
        $ofertas = $this->ofertasAceptadas();
        $tipo_cartera_nr = $this->catalogoTipoCarteraNr();
        $forma_pago = FormaPagoPoliza::where('Activo', 1)->ordenado()->get();
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
                'tipo_cartera_nr' => $negocio->TipoCarteraNr,
                'departamento' => $negocio->DepartamentoNr,
                'ejecutivo' => $negocio->Ejecutivo,
                'observacion' => $negocio->Observacion,
                'ramo' => $cotizacion->planes->productos->NecesidadProteccion,
                'aseguradora' => $cotizacion->planes->productos->Aseguradora,
                'productos' => $cotizacion->planes->productos->Id,
                'planes' => $cotizacion->planes->Id,
                'porcentaje_comision_nr' => $cotizacion->planes->productos->PorcentajeComisionNoDeclarativa !== null
                    ? $cotizacion->planes->productos->PorcentajeComisionNoDeclarativa
                    : optional($cotizacion->planes->productos->ramos)->PorcentajeComisionNoDeclarativa,
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
        $this->normalizarCamposMayuscula($request, [
            'NumeroPoliza',
            'MotivoCancelacion',
            'SustituidaPoliza',
            'ClausulasEspeciales',
            'BeneficiosAdicionales',
            'Comentarios',
            'Observacion',
            'SolicitudRenovacion',
            'ObservacionSiniestro',
            'GrupoCliente',
        ]);

        $request->validate([
            'Oferta' => 'nullable|integer',
            'NumeroVigencia' => 'nullable|integer|min:1',
            'Ramo' => 'required|integer',
            'Aseguradora' => 'required|integer',
            'FormaPago' => 'required|integer|exists:forma_pago_polizas,Id',
            'NumCuotas' => 'nullable|integer',
            'NumeroPoliza' => 'required|string|max:100',
            'EstadoPoliza' => 'required|integer',
            'Productos' => 'required|integer',
            'Planes' => 'required|integer',
            'SumaAsegurada' => 'nullable|numeric',
            'PrimaNetaAnual' => 'nullable|numeric',
            'PorcentajeComisionNR' => 'nullable|numeric',
            'IvaIncluido' => 'nullable|in:S,N',
            'PorcentajeDescuentoRentabilidad' => 'nullable|numeric|min:0|max:100',
            'PorcentajeDescuentoBuenaExperiencia' => 'nullable|numeric|min:0|max:100',
            'PorcentajeOtrosDescuentos' => 'nullable|numeric|min:0|max:100',
            'PorcentajeComsionCliente' => 'nullable|numeric|min:0|max:100',
            'ClausulasEspeciales' => 'nullable|string',
            'BeneficiosAdicionales' => 'nullable|string',
            'Comentarios' => 'nullable|string',
            'TipoCarteraNR' => 'nullable|integer|exists:tipo_cartera_nr,Id',
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
        $poliza_seguro->NumeroVigencia = $request->NumeroVigencia;
        $poliza_seguro->FormaPago = $request->FormaPago;
        $poliza_seguro->NumCuotas = $request->NumCuotas;
        $poliza_seguro->NumeroPoliza = $request->NumeroPoliza;
        $poliza_seguro->EstadoPoliza = $request->EstadoPoliza;
        $poliza_seguro->Productos = $request->Productos;
        $poliza_seguro->Planes = $request->Planes;
        if ($request->has('SumaAsegurada')) {
            $poliza_seguro->SumaAsegurada = $request->SumaAsegurada;
        }

        if ($request->has('PrimaNetaAnual')) {
            $poliza_seguro->PrimaNetaAnual = $request->PrimaNetaAnual;
        }
        $poliza_seguro->IvaIncluido = $this->normalizarValorSn($request->IvaIncluido);
        $poliza_seguro->PorcentajeComisionNR = $request->PorcentajeComisionNR;
        $poliza_seguro->PorcentajeDescuentoRentabilidad = $request->PorcentajeDescuentoRentabilidad;
        $poliza_seguro->PorcentajeDescuentoBuenaExperiencia = $request->PorcentajeDescuentoBuenaExperiencia;
        $poliza_seguro->PorcentajeOtrosDescuentos = $request->PorcentajeOtrosDescuentos;
        $poliza_seguro->PorcentajeComsionCliente = $request->PorcentajeComsionCliente;
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
        $poliza_seguro->TipoCarteraNR = $request->TipoCarteraNR;
        $poliza_seguro->FechaRecepcion = $request->FechaRecepcion;
        $poliza_seguro->SustituidaPoliza = $request->SustituidaPoliza;
        $poliza_seguro->ClausulasEspeciales = $request->ClausulasEspeciales;
        $poliza_seguro->BeneficiosAdicionales = $request->BeneficiosAdicionales;
        $poliza_seguro->Comentarios = $request->Comentarios;
        $poliza_seguro->ObservacionSiniestro = $request->ObservacionSiniestro;
        $poliza_seguro->EjecutivoCia = $request->EjecutivoCia;
        $poliza_seguro->GrupoCliente = $request->GrupoCliente;
        $poliza_seguro->Deducible = $request->Deducible;
        if ($request->has('ValorDeducible')) {
            $poliza_seguro->ValorDeducible = $request->ValorDeducible;
        }
        $poliza_seguro->Activo = $request->Activo;
        $poliza_seguro->Usuario = $request->Usuario;
        $poliza_seguro->save();

        $this->asegurarRenovacionInicial($poliza_seguro);

        return redirect('poliza/seguro/' . $poliza_seguro->Id . '/edit?tab=4')->with('success', 'Se creo la poliza de seguro');
    }


    public function show($id, Request $request)
    {
        $tab = $request->tab ?? 1;
        $poliza_seguro = PolizaSeguro::with([
            'oferta',
            'certificados.dependientes',
            'producto.certificadoCampos',
            'producto.aseguradoras',
            'producto.ramos',
            'plan.planesCoberturaDetalles' => function ($query) {
                $query->where('Activo', 1);
            },
            'forma_pago',
            'estado_polizas',
            'cancelacion',
            'tipoCarteraNr',
            'ejecutivoCia',
            'clientes',
            'renovaciones.estadoPolizaRelacion',
            'renovaciones.usuario',
        ])->findOrFail($id);
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::with(['planesCoberturaDetalles' => function ($query) {
            $query->where('Activo', 1);
        }])->where('Activo', 1)->get();
        $aseguradora = Aseguradora::where('Activo', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', 1)->get();
        $clientes = Cliente::where('Activo', 1)->get();
        $ofertas = $this->ofertasAceptadas($poliza_seguro->Oferta);
        $tipo_cartera_nr = $this->catalogoTipoCarteraNr();
        $forma_pago = FormaPagoPoliza::where('Activo', 1)->ordenado()->get();
        $estado_poliza = EstadoPoliza::get();
        $motivos_cancelacion = MotivoCancelacion::where('Activo', 1)->get();
        $origen_poliza = OrigenPoliza::where('Activo', 1)->get();
        $tipo_deducible = Deducible::where('Activo', 1)->get();
        $departamento_nr = DepartamentoNR::where('Activo', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', 1)->get();
        $certificado_campos = $this->certificadoCampos($poliza_seguro->Productos);
        $permite_dependientes = (int) ($poliza_seguro->producto->PermiteDependientesCertificado ?? 0);
        $renovaciones_historial = $poliza_seguro->renovaciones;

        if ($renovaciones_historial->isEmpty()) {
            $renovaciones_historial = collect([
                (object) [
                    'Id' => null,
                    'TipoRenovacion' => 'EMISION',
                    'EstadoPoliza' => $poliza_seguro->EstadoPoliza,
                    'NumeroVigencia' => $poliza_seguro->NumeroVigencia,
                    'VigenciaDesde' => $poliza_seguro->VigenciaDesde,
                    'VigenciaHasta' => $poliza_seguro->VigenciaHasta,
                    'TarifaPlan' => $this->tarifaPlanTexto($poliza_seguro->plan),
                    'SumaAsegurada' => 0,
                    'PrimaNetaAnual' => 0,
                    'DatosPolizaJson' => json_encode($this->snapshotRenovacionPoliza($poliza_seguro), JSON_UNESCAPED_UNICODE),
                    'CambiosJson' => null,
                    'FechaRegistro' => null,
                    'usuario' => null,
                    'estadoPolizaRelacion' => $poliza_seguro->estado_polizas,
                ],
            ]);
        }

        //dd($poliza_seguro->oferta->clientes->Nombre);
        return view('polizas.seguro.show', compact('tab', 'poliza_seguro', 'motivos_cancelacion', 'origen_poliza', 'tipo_deducible', 'departamento_nr', 'estado_poliza', 'forma_pago', 'tipo_cartera_nr', 'ofertas', 'productos', 'planes', 'aseguradora', 'ramos', 'clientes', 'ejecutivos', 'certificado_campos', 'permite_dependientes', 'renovaciones_historial'));
    }

    public function save(Request $request, $id)
    {
        $poliza_seguro = PolizaSeguro::findOrFail($id);
        $esRenovacion = $request->boolean('EsRenovacion');

        $this->normalizarCamposMayuscula($request, [
            'NumeroPoliza',
            'MotivoCancelacion',
            'SustituidaPoliza',
            'ClausulasEspeciales',
            'BeneficiosAdicionales',
            'Comentarios',
            'Observacion',
            'SolicitudRenovacion',
            'ObservacionSiniestro',
            'GrupoCliente',
        ]);

        $request->validate([
            'Oferta' => 'nullable|integer',
            'EsRenovacion' => 'nullable|boolean',
            'NumeroVigencia' => 'nullable|integer|min:1',
            'Ramo' => 'required|integer',
            'Aseguradora' => 'required|integer',
            'FormaPago' => 'required|integer|exists:forma_pago_polizas,Id',
            'NumCuotas' => 'nullable|integer',
            'NumeroPoliza' => 'required|string|max:100',
            'EstadoPoliza' => 'required|integer',
            'Productos' => 'required|integer',
            'Planes' => 'required|integer',
            'SumaAsegurada' => 'nullable|numeric',
            'PrimaNetaAnual' => 'nullable|numeric',
            'PorcentajeComisionNR' => 'nullable|numeric',
            'IvaIncluido' => 'nullable|in:S,N',
            'PorcentajeDescuentoRentabilidad' => 'nullable|numeric|min:0|max:100',
            'PorcentajeDescuentoBuenaExperiencia' => 'nullable|numeric|min:0|max:100',
            'PorcentajeOtrosDescuentos' => 'nullable|numeric|min:0|max:100',
            'PorcentajeComsionCliente' => 'nullable|numeric|min:0|max:100',
            'ClausulasEspeciales' => 'nullable|string',
            'BeneficiosAdicionales' => 'nullable|string',
            'Comentarios' => 'nullable|string',
            'TipoCarteraNR' => 'nullable|integer|exists:tipo_cartera_nr,Id',
            'ValorDeducible' => 'nullable|numeric',
            'Cliente' => 'required|integer',
            'VigenciaDesde' => 'required|date',
            'VigenciaHasta' => 'required|date|after_or_equal:VigenciaDesde',
            'DiasVigencia' => 'nullable|integer',
            'SustituidaPoliza' => 'nullable|string|max:255',
        ]);

        $erroresProductoPlan = $this->validarProductoPlan($request);
        if (!empty($erroresProductoPlan)) {
            return redirect()->back()
                ->withErrors($erroresProductoPlan)
                ->withInput();
        }

        DB::transaction(function () use ($request, $poliza_seguro, $esRenovacion) {
            $snapshotOriginal = $this->snapshotRenovacionPoliza($poliza_seguro);
            $this->asegurarRenovacionInicial($poliza_seguro);

            $poliza_seguro->Cliente = $request->Cliente;
            $poliza_seguro->FormaPago = $request->FormaPago;
            $poliza_seguro->NumeroVigencia = $request->NumeroVigencia;
            $poliza_seguro->NumCuotas = $request->NumCuotas;
            $poliza_seguro->NumeroPoliza = $request->NumeroPoliza;
            $poliza_seguro->EstadoPoliza = $request->EstadoPoliza;
            $poliza_seguro->Productos = $request->Productos;
            $poliza_seguro->Planes = $request->Planes;
            if ($request->has('SumaAsegurada')) {
                $poliza_seguro->SumaAsegurada = $request->SumaAsegurada;
            }

            if ($request->has('PrimaNetaAnual')) {
                $poliza_seguro->PrimaNetaAnual = $request->PrimaNetaAnual;
            }
            $poliza_seguro->IvaIncluido = $this->normalizarValorSn($request->IvaIncluido);
            $poliza_seguro->PorcentajeComisionNR = $request->PorcentajeComisionNR;
            $poliza_seguro->PorcentajeDescuentoRentabilidad = $request->PorcentajeDescuentoRentabilidad;
            $poliza_seguro->PorcentajeDescuentoBuenaExperiencia = $request->PorcentajeDescuentoBuenaExperiencia;
            $poliza_seguro->PorcentajeOtrosDescuentos = $request->PorcentajeOtrosDescuentos;
            $poliza_seguro->PorcentajeComsionCliente = $request->PorcentajeComsionCliente;
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
            $poliza_seguro->TipoCarteraNR = $request->TipoCarteraNR;
            $poliza_seguro->FechaRecepcion = $request->FechaRecepcion;
            $poliza_seguro->SustituidaPoliza = $request->SustituidaPoliza;
            $poliza_seguro->ClausulasEspeciales = $request->ClausulasEspeciales;
            $poliza_seguro->BeneficiosAdicionales = $request->BeneficiosAdicionales;
            $poliza_seguro->Comentarios = $request->Comentarios;
            $poliza_seguro->ObservacionSiniestro = $request->ObservacionSiniestro;
            $poliza_seguro->EjecutivoCia = $request->EjecutivoCia;
            $poliza_seguro->GrupoCliente = $request->GrupoCliente;
            $poliza_seguro->Deducible = $request->Deducible;
            if ($request->has('ValorDeducible')) {
                $poliza_seguro->ValorDeducible = $request->ValorDeducible;
            }
            $poliza_seguro->Activo = $request->Activo;
            $poliza_seguro->Usuario = $request->Usuario;
            $poliza_seguro->update();

            $poliza_seguro->refresh();
            $snapshotNuevo = $this->snapshotRenovacionPoliza($poliza_seguro);

            if ($esRenovacion) {
                $cambios = $this->cambiosRenovacionDesdeSnapshots($snapshotOriginal, $snapshotNuevo);

                if (empty($cambios)) {
                    throw ValidationException::withMessages([
                        'EsRenovacion' => 'No hay cambios para registrar en la renovacion.',
                    ]);
                }

                $this->registrarRenovacionPoliza($poliza_seguro, 'RENOVACION', $snapshotNuevo, $cambios);
            }
        });

        $mensaje = $esRenovacion
            ? 'Renovacion guardada correctamente'
            : 'Poliza actualizada correctamente';

        return redirect('poliza/seguro/' . $poliza_seguro->Id . '/edit?tab=' . ($esRenovacion ? 6 : 1))
            ->with('success', $mensaje);
    }

    public function export_certificados($id)
    {
        $poliza = PolizaSeguro::with([
            'clientes',
            'producto.certificadoCampos',
            'plan',
            'certificados.dependientes',
            'certificados.motivoCancelacion',
            'certificados.usuarioModifica',
        ])->findOrFail($id);

        $nombreArchivo = 'poliza_seguro_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $poliza->NumeroPoliza) . '_certificados.xlsx';

        return Excel::download(new PolizaSeguroCertificadosExport($poliza), $nombreArchivo);
    }

    public function certificado_create($id)
    {
        $poliza = PolizaSeguro::with(['clientes', 'producto'])->findOrFail($id);
        $numeroCertificado = $this->siguienteNumeroCertificado($poliza->Id);
        $esPrimerCertificado = $numeroCertificado === 1;
        $cliente = $poliza->clientes;
        $estadoVigente = EstadoCertificado::where('Activo', 1)
            ->get()
            ->first(function ($estado) {
                return trim(mb_strtoupper($estado->Nombre)) === 'CERTIFICADO VIGENTE';
            });

        $certificado = new PolizaSeguroCertificado([
            'PolizaSeguroId' => $poliza->Id,
            'Plan' => $poliza->Planes,
            'NumeroCertificado' => $numeroCertificado,
            'CertificadoAseguradora' => (string) $numeroCertificado,
            'CodAsegurado' => $esPrimerCertificado ? $this->documentoContratanteCertificado($cliente) : '',
            'Asegurado' => $esPrimerCertificado ? ($cliente->Nombre ?? '') : '',
            'FechaNacimiento' => null,
            'Sexo' => $esPrimerCertificado ? $this->sexoContratanteCertificado($cliente) : null,
            'VigenciaDesde' => $poliza->VigenciaDesde,
            'VigenciaHasta' => $poliza->VigenciaHasta,
            'FechaInclusion' => null,
            'DiasVigencia' => $this->calcularDiasCertificado($poliza->VigenciaDesde, $poliza->VigenciaHasta),
            'EstadoCertificado' => $estadoVigente->Id ?? null,
            'Estado' => $estadoVigente->Nombre ?? 'CERTIFICADO VIGENTE',
            'MotivoCancelacion' => null,
            'Deducible' => null,
            'Participacion' => null,
            'PorcentajeDepreciacion' => null,
            'PrimaMinima' => null,
            'MotivoExclusion' => null,
            'FechaExclusion' => null,
            'UsuarioModifica' => auth()->id(),
            'FechaModificacion' => null,
            'ValorAsegurado' => $poliza->SumaAsegurada,
            'PrimaTotal' => null,
            'PorcentajeDescuentoRentabilidad' => $poliza->PorcentajeDescuentoRentabilidad,
            'PorcentajeDescuentoBuenaExperiencia' => $poliza->PorcentajeDescuentoBuenaExperiencia,
            'PorcentajeOtrosDescuentos' => $poliza->PorcentajeOtrosDescuentos,
            'ValorDescuento' => null,
            'ValorDescuentoBuenaExperiencia' => null,
            'ValorOtrosDescuentos' => null,
            'PrimaNeta' => $poliza->PrimaNetaAnual,
            'PrimaExenta' => null,
            'GastosEmision' => null,
            'GastosFraccionamiento' => null,
            'GastosBomberos' => null,
            'OtrosGastos' => null,
            'Impuestos' => null,
            'TotalCertificado' => null,
            'DatosJson' => null,
            'Observacion' => null,
            'Activo' => 1,
        ]);
        $datosSumas = $this->datosSumasCertificado($poliza, $certificado);
        $datosTecnicos = [
            'datosTecnicosCertificado' => collect(),
            'valoresDatosTecnicosCertificado' => collect(),
        ];
        $productoCertificado = $this->productoConfigCertificado($certificado->Plan, $poliza->Productos) ?: $poliza->producto;
        $certificadoCampos = $this->certificadoCampos($productoCertificado->Id ?? $poliza->Productos);
        $catalogosOpcionesCertificado = $this->catalogosOpcionesCertificado($certificadoCampos);
        $cesionarios = Cesionario::where('Activo', 1)->orderBy('Nombre', 'asc')->get();
        $siguienteCodigoCesion = 1;

        return view('polizas.seguro.certificado', array_merge([
            'modo' => 'create',
            'poliza' => $poliza,
            'certificado' => $certificado,
            'certificado_campos' => $certificadoCampos,
            'catalogosOpcionesCertificado' => $catalogosOpcionesCertificado,
            'campoValoresCertificado' => [],
            'producto_certificado_config' => $productoCertificado,
            'permite_dependientes' => (int) ($productoCertificado->PermiteDependientesCertificado ?? 0) === 1,
            'estados_certificado' => EstadoCertificado::where('Activo', 1)->get(),
            'motivos_cancelacion' => MotivoCancelacion::where('Activo', 1)->get(),
            'cesionarios' => $cesionarios,
            'siguienteCodigoCesion' => $siguienteCodigoCesion,
        ], $datosSumas, $datosTecnicos));
    }

    public function certificado_store($id, Request $request)
    {
        $poliza = PolizaSeguro::findOrFail($id);
        $numeroCertificado = $this->siguienteNumeroCertificado($poliza->Id);
        $this->normalizarCamposMayuscula($request, [
            'CertificadoAseguradora',
            'CodAsegurado',
            'Asegurado',
            'MotivoExclusion',
            'Observacion',
        ]);
        $this->normalizarOpcionalesCertificado($request);
        $request->validate($this->reglasCertificadoBase());

        if (PolizaSeguroCertificado::where('PolizaSeguroId', $poliza->Id)
            ->where('NumeroCertificado', $numeroCertificado)
            ->exists()) {
            return redirect()->back()
                ->withErrors(['NumeroCertificado' => 'El numero de certificado ya existe para esta poliza.'])
                ->withInput();
        }

        $certificado = new PolizaSeguroCertificado();
        $certificado->PolizaSeguroId = $poliza->Id;
        $certificado->Plan = $poliza->Planes;
        $certificado->NumeroCertificado = $numeroCertificado;
        $this->asignarDatosCertificado($certificado, $request, $poliza);
        $certificado->Activo = 1;
        $certificado->save();

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit')
            ->with('success', 'Certificado creado correctamente');
    }

    public function certificado_delete($id)
    {
        $certificado = PolizaSeguroCertificado::findOrFail($id);
        $certificado->Activo = 0;
        $certificado->update();
        $this->sincronizarResumenPolizaDesdeCertificados(PolizaSeguro::findOrFail($certificado->PolizaSeguroId));

        return redirect('poliza/seguro/' . $certificado->PolizaSeguroId . '/edit?tab=4')
            ->with('success', 'Certificado eliminado correctamente');
    }

    public function certificado_edit($id)
    {
        $certificado = PolizaSeguroCertificado::with([
            'poliza.clientes',
            'poliza.producto',
            'usuarioModifica',
            'coberturasCertificado.cobertura.tarificacion',
            'datosTecnicosCertificado',
            'dependientes',
            'beneficiarios.parentesco',
            'beneficiariosTodos.parentesco',
            'cesionBeneficios.cesionario',
            'cesionBeneficiosTodos.cesionario',
        ])->findOrFail($id);
        $datosSumas = $this->datosSumasCertificado($certificado->poliza, $certificado);
        $datosTecnicos = $this->datosTecnicosCertificado($certificado);
        $productoCertificado = $this->productoConfigCertificado($certificado->Plan, $certificado->poliza->Productos) ?: $certificado->poliza->producto;
        $certificadoCampos = $this->certificadoCampos($productoCertificado->Id ?? $certificado->poliza->Productos);
        $catalogosOpcionesCertificado = $this->catalogosOpcionesCertificado($certificadoCampos);
        $cesionarios = Cesionario::where('Activo', 1)->orderBy('Nombre', 'asc')->get();
        $siguienteCodigoCesion = $this->siguienteCodigoSesionCesion($certificado->Id);

        return view('polizas.seguro.certificado', array_merge([
            'modo' => 'edit',
            'poliza' => $certificado->poliza,
            'certificado' => $certificado,
            'certificado_campos' => $certificadoCampos,
            'catalogosOpcionesCertificado' => $catalogosOpcionesCertificado,
            'campoValoresCertificado' => $this->valoresDatosCertificado($certificado->DatosJson),
            'producto_certificado_config' => $productoCertificado,
            'permite_dependientes' => (int) ($productoCertificado->PermiteDependientesCertificado ?? 0) === 1,
            'estados_certificado' => EstadoCertificado::where('Activo', 1)->get(),
            'motivos_cancelacion' => MotivoCancelacion::where('Activo', 1)->get(),
            'cesionarios' => $cesionarios,
            'siguienteCodigoCesion' => $siguienteCodigoCesion,
            'parentescos' => $this->parentescosCatalogo(),
        ], $datosSumas, $datosTecnicos));
    }

    public function certificado_update($id, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza.producto')->findOrFail($id);
        $this->normalizarCamposMayuscula($request, [
            'CertificadoAseguradora',
            'CodAsegurado',
            'Asegurado',
            'MotivoExclusion',
            'Observacion',
        ]);
        $this->normalizarOpcionalesCertificado($request);
        $request->validate($this->reglasCertificadoBase());

        $this->asignarDatosCertificado($certificado, $request, $certificado->poliza);
        $certificado->update();
        $this->sincronizarResumenPolizaDesdeCertificados($certificado->poliza);

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit')
            ->with('success', 'Certificado actualizado correctamente');
    }

    public function certificado_detalle_save($id, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza.producto')->findOrFail($id);
        $productoCertificado = $this->productoConfigCertificado($certificado->Plan, $certificado->poliza->Productos);
        $campos = $this->certificadoCampos($productoCertificado->Id ?? $certificado->poliza->Productos);

        $request->validate($this->reglasCamposCertificado($campos));

        $certificado->DatosJson = json_encode($this->buildDatosCertificado($request, $campos), JSON_UNESCAPED_UNICODE);
        $certificado->UsuarioModifica = auth()->id();
        $certificado->FechaModificacion = now();
        $certificado->update();

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#detalle')
            ->with('success', 'Detalle del asegurado actualizado correctamente');
    }

    public function certificado_sumas_save($id, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza')->findOrFail($id);

        // Coberturas se guarda desde un form independiente; si no viaja Plan, usamos el plan vigente del certificado.
        if (!$request->filled('Plan')) {
            $request->merge([
                'Plan' => $certificado->Plan ?: $certificado->poliza->Planes,
            ]);
        }

        $request->validate([
            'Plan' => 'required|integer|exists:plan,Id',
            'coberturas' => 'nullable|array',
            'coberturas.*.Cobertura' => 'nullable|integer|exists:cobertura,Id',
            'coberturas.*.Tarificacion' => 'nullable|integer|exists:cobertura_tarificacion,Id',
            'coberturas.*.TarificacionNombre' => 'nullable|string|max:100',
            'coberturas.*.Nombre' => 'required_with:coberturas|string|max:250',
            'coberturas.*.SumaAsegurada' => 'nullable|numeric|min:0',
            'coberturas.*.PorcentajeSuma' => 'nullable|numeric',
            'coberturas.*.Tasa' => 'nullable|numeric',
            'coberturas.*.DiasProrrata' => 'nullable|integer|min:0',
            'coberturas.*.PrimaAnual' => 'nullable|numeric|min:0',
            'coberturas.*.Prima' => 'nullable|numeric|min:0',
        ]);

        $planValido = Plan::where('Activo', 1)
            ->where('Producto', $certificado->poliza->Productos)
            ->where('Id', $request->Plan)
            ->exists();

        if (!$planValido) {
            return redirect()->back()
                ->withErrors(['Plan' => 'El plan seleccionado no pertenece al producto de la poliza.'])
                ->withInput();
        }

        $totalSumaAsegurada = 0;
        $totalPrima = 0;
        $coberturas = collect($request->input('coberturas', []))
            ->filter(function ($detalle) {
                return !empty($detalle['Nombre']) || !empty($detalle['Cobertura']);
            })
            ->values();
        $tarificacionesPorCobertura = DB::table('cobertura as cobertura')
            ->leftJoin('cobertura_tarificacion as tarificacion', 'tarificacion.Id', '=', 'cobertura.Tarificacion')
            ->whereIn('cobertura.Id', $coberturas->pluck('Cobertura')->filter()->unique()->values())
            ->select('cobertura.Id', 'cobertura.Tarificacion', 'tarificacion.Nombre as TarificacionNombre')
            ->get()
            ->keyBy('Id');

        DB::transaction(function () use ($certificado, $request, $coberturas, $tarificacionesPorCobertura, &$totalSumaAsegurada, &$totalPrima) {
            PolizaSeguroCertificadoCobertura::where('PolizaSeguroCertificadoId', $certificado->Id)
                ->where('Activo', 1)
                ->update(['Activo' => 0]);

            foreach ($coberturas as $detalle) {
                $sumaAsegurada = (float) ($detalle['SumaAsegurada'] ?? 0);
                $prima = (float) ($detalle['Prima'] ?? 0);
                $tarificacion = $tarificacionesPorCobertura->get($detalle['Cobertura'] ?? null);
                $totalSumaAsegurada += $sumaAsegurada;
                $totalPrima += $prima;

                PolizaSeguroCertificadoCobertura::create([
                    'PolizaSeguroCertificadoId' => $certificado->Id,
                    'Cobertura' => $detalle['Cobertura'] ?? null,
                    'Tarificacion' => $tarificacion->Tarificacion ?? $detalle['Tarificacion'] ?? null,
                    'TarificacionNombre' => $tarificacion->TarificacionNombre ?? $detalle['TarificacionNombre'] ?? null,
                    'Nombre' => $detalle['Nombre'] ?? '',
                    'SumaAsegurada' => $sumaAsegurada,
                    'PorcentajeSuma' => $detalle['PorcentajeSuma'] ?? null,
                    'Tasa' => $detalle['Tasa'] ?? null,
                    'DiasProrrata' => $detalle['DiasProrrata'] ?? null,
                    'PrimaAnual' => $detalle['PrimaAnual'] ?? null,
                    'Prima' => $prima,
                    'Activo' => 1,
                ]);
            }

            $certificado->Plan = $request->Plan;
            $certificado->ValorAsegurado = $totalSumaAsegurada;
            $certificado->PrimaTotal = $totalPrima;
            $detalleCobro = $this->calcularDetalleCobroCertificado(
                $totalPrima,
                $certificado->PorcentajeDescuentoRentabilidad,
                $certificado->PorcentajeDescuentoBuenaExperiencia,
                $certificado->PorcentajeOtrosDescuentos,
                $certificado->PrimaExenta,
                $certificado->GastosEmision,
                $certificado->GastosFraccionamiento,
                $certificado->GastosBomberos,
                $certificado->OtrosGastos,
                $this->normalizarValorSn($certificado->poliza->IvaIncluido) === 'S'
            );
            $certificado->ValorDescuento = $detalleCobro['ValorDescuento'];
            $certificado->ValorDescuentoBuenaExperiencia = $detalleCobro['ValorDescuentoBuenaExperiencia'];
            $certificado->ValorOtrosDescuentos = $detalleCobro['ValorOtrosDescuentos'];
            $certificado->PrimaNeta = $detalleCobro['PrimaNeta'];
            $certificado->Impuestos = $detalleCobro['Impuestos'];
            $certificado->TotalCertificado = $detalleCobro['TotalCertificado'];
            $certificado->UsuarioModifica = auth()->id();
            $certificado->FechaModificacion = now();
            $certificado->update();
        });
        $this->sincronizarResumenPolizaDesdeCertificados($certificado->poliza);

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#sumas')
            ->with('success', 'Sumas aseguradas actualizadas correctamente');
    }

    public function certificado_datos_tecnicos_save($id, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with(['poliza.producto', 'datosTecnicosCertificado'])->findOrFail($id);
        $productoCertificado = $this->productoConfigCertificado($certificado->Plan, $certificado->poliza->Productos)
            ?: $certificado->poliza->producto;

        $datosTecnicos = DatosTecnicos::where('Activo', 1)
            ->where('Producto', $productoCertificado->Id ?? $certificado->poliza->Productos)
            ->orderBy('Id', 'asc')
            ->get();

        $request->validate([
            'DatosTecnicos' => 'nullable|array',
            'DatosTecnicos.*' => 'nullable|string|max:5000',
        ]);

        $valores = $request->input('DatosTecnicos', []);
        $idsConfigurados = $datosTecnicos->pluck('Id')->all();

        DB::transaction(function () use ($certificado, $datosTecnicos, $valores, $idsConfigurados) {
            foreach ($datosTecnicos as $dato) {
                $datoCertificado = PolizaSeguroCertificadoDatoTecnico::where('PolizaSeguroCertificadoId', $certificado->Id)
                    ->where('DatoTecnicoId', $dato->Id)
                    ->first() ?: new PolizaSeguroCertificadoDatoTecnico();

                $datoCertificado->PolizaSeguroCertificadoId = $certificado->Id;
                $datoCertificado->DatoTecnicoId = $dato->Id;
                $datoCertificado->Nombre = $dato->Nombre;
                $datoCertificado->Descripcion = $dato->Descripcion;
                $datoCertificado->Valor = $valores[$dato->Id] ?? null;
                $datoCertificado->Activo = 1;
                $datoCertificado->save();
            }

            PolizaSeguroCertificadoDatoTecnico::where('PolizaSeguroCertificadoId', $certificado->Id)
                ->when(count($idsConfigurados) > 0, function ($query) use ($idsConfigurados) {
                    $query->whereNotIn('DatoTecnicoId', $idsConfigurados);
                })
                ->when(count($idsConfigurados) === 0, function ($query) {
                    $query->whereNotNull('DatoTecnicoId');
                })
                ->update(['Activo' => 0]);
        });

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#datos_tecnicos_certificado')
            ->with('success', 'Datos tecnicos del certificado actualizados correctamente');
    }

    public function dependiente_store($certificadoId, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza.producto')->findOrFail($certificadoId);
        $productoCertificado = $this->productoConfigCertificado($certificado->Plan, $certificado->poliza->Productos) ?: $certificado->poliza->producto;

        if ((int) ($productoCertificado->PermiteDependientesCertificado ?? 0) !== 1) {
            return redirect()->back()
                ->withErrors(['Dependientes' => 'El producto de esta poliza no permite dependientes.']);
        }

        $campos = $this->certificadoCampos($productoCertificado->Id ?? $certificado->poliza->Productos);
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

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#detalle')
            ->with('success', 'Dependiente agregado correctamente');
    }

    public function dependiente_delete($id)
    {
        $dependiente = PolizaSeguroCertificadoDependiente::with('certificado')->findOrFail($id);
        $dependiente->Activo = 0;
        $dependiente->update();

        return redirect('poliza/seguro/certificado/' . $dependiente->certificado->Id . '/edit#detalle')
            ->with('success', 'Dependiente eliminado correctamente');
    }

    public function dependiente_update($id, Request $request)
    {
        $dependiente = PolizaSeguroCertificadoDependiente::with('certificado.poliza.producto')->findOrFail($id);
        $certificado = $dependiente->certificado;
        $productoCertificado = $this->productoConfigCertificado($certificado->Plan, $certificado->poliza->Productos);
        $campos = $this->certificadoCampos($productoCertificado->Id ?? $certificado->poliza->Productos);

        $request->validate(array_merge([
            'Observacion' => 'nullable|string|max:500',
        ], $this->reglasCamposCertificado($campos)));

        $dependiente->DatosJson = json_encode($this->buildDatosCertificado($request, $campos), JSON_UNESCAPED_UNICODE);
        $dependiente->Observacion = $request->Observacion;
        $dependiente->update();

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#detalle')
            ->with('success', 'Dependiente actualizado correctamente');
    }

    private function totalBeneficiariosCertificado($certificadoId, $excluirBeneficiarioId = null): float
    {
        return (float) PolizaSeguroBeneficiario::where('PolizaSeguroCertificadoId', $certificadoId)
            ->where('Activo', 1)
            ->when($excluirBeneficiarioId, function ($query) use ($excluirBeneficiarioId) {
                $query->where('Id', '<>', $excluirBeneficiarioId);
            })
            ->sum('Porcentaje');
    }

    private function reglasCesionBeneficios(): array
    {
        return [
            'CesionarioId' => 'required|integer|exists:cesionario,Id',
            'FechaVigencia' => 'nullable|date',
            'FechaCancelacion' => 'nullable|date|after_or_equal:FechaVigencia',
            'SumaCedida' => 'nullable|numeric|min:0',
            'Observaciones' => 'nullable|string|max:1000',
        ];
    }

    private function siguienteCodigoSesionCesion($certificadoId): int
    {
        $ultimo = PolizaSeguroCesionBeneficio::where('PolizaSeguroCertificadoId', $certificadoId)
            ->selectRaw('COALESCE(MAX(CAST(CodigoSesion AS UNSIGNED)), 0) as UltimoCodigoSesion')
            ->value('UltimoCodigoSesion');

        return ((int) $ultimo) + 1;
    }

    private function asignarDatosCesion(PolizaSeguroCesionBeneficio $cesion, Request $request): void
    {
        if (!$cesion->exists || !$cesion->CodigoSesion) {
            $cesion->CodigoSesion = $this->siguienteCodigoSesionCesion($cesion->PolizaSeguroCertificadoId);
        }

        $cesion->CesionarioId = $request->CesionarioId;
        $cesion->FechaVigencia = $request->FechaVigencia;
        $cesion->FechaCancelacion = $request->FechaCancelacion;
        $cesion->SumaCedida = $request->SumaCedida;
        $cesion->Observaciones = $request->Observaciones;
    }

    public function certificado_beneficiario_store($certificadoId, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza')->findOrFail($certificadoId);

        $request->validate([
            'Nombre' => 'required|string|max:200',
            'Dui' => ['nullable', 'regex:/^\d{8}-\d$/'],
            'Parentesco' => 'nullable|integer|exists:parentesco,Id',
            'FechaNacimiento' => 'nullable|date',
            'Porcentaje' => 'required|numeric|min:0.01|max:100',
        ]);

        $totalActual = $this->totalBeneficiariosCertificado($certificado->Id);
        $nuevoTotal = $totalActual + (float) $request->Porcentaje;

        if ($nuevoTotal > 100) {
            return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#beneficiarios')
                ->withErrors(['Porcentaje' => 'La suma de porcentajes de beneficiarios no puede superar el 100%.'])
                ->withInput();
        }

        PolizaSeguroBeneficiario::create([
            'PolizaSeguroId' => $certificado->PolizaSeguroId,
            'PolizaSeguroCertificadoId' => $certificado->Id,
            'Nombre' => $request->Nombre,
            'Dui' => $request->filled('Dui') ? $request->Dui : null,
            'Parentesco' => $request->Parentesco,
            'FechaNacimiento' => $request->FechaNacimiento,
            'Porcentaje' => $request->Porcentaje,
            'Activo' => 1,
        ]);

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#beneficiarios')
            ->with('success', 'Beneficiario agregado correctamente');
    }

    public function certificado_beneficiario_update($id, Request $request)
    {
        $beneficiario = PolizaSeguroBeneficiario::with('certificado')->findOrFail($id);
        $certificado = $beneficiario->certificado;

        $request->validate([
            'Nombre' => 'required|string|max:200',
            'Dui' => ['nullable', 'regex:/^\d{8}-\d$/'],
            'Parentesco' => 'nullable|integer|exists:parentesco,Id',
            'FechaNacimiento' => 'nullable|date',
            'Porcentaje' => 'required|numeric|min:0.01|max:100',
        ]);

        $totalSinActual = $this->totalBeneficiariosCertificado($certificado->Id, $beneficiario->Id);
        $nuevoTotal = $totalSinActual + (float) $request->Porcentaje;

        if ($nuevoTotal > 100) {
            return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#beneficiarios')
                ->withErrors(['Porcentaje' => 'La suma de porcentajes de beneficiarios no puede superar el 100%.'])
                ->withInput();
        }

        $beneficiario->Nombre = $request->Nombre;
        $beneficiario->Dui = $request->filled('Dui') ? $request->Dui : null;
        $beneficiario->Parentesco = $request->Parentesco;
        $beneficiario->FechaNacimiento = $request->FechaNacimiento;
        $beneficiario->Porcentaje = $request->Porcentaje;
        $beneficiario->update();

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#beneficiarios')
            ->with('success', 'Beneficiario actualizado correctamente');
    }

    public function certificado_beneficiario_delete($id)
    {
        $beneficiario = PolizaSeguroBeneficiario::with('certificado')->findOrFail($id);
        $certificadoId = $beneficiario->PolizaSeguroCertificadoId;
        $beneficiario->Activo = 0;
        $beneficiario->update();

        return redirect('poliza/seguro/certificado/' . $certificadoId . '/edit#beneficiarios')
            ->with('success', 'Beneficiario eliminado correctamente');
    }

    public function certificado_beneficiario_toggle($id)
    {
        $beneficiario = PolizaSeguroBeneficiario::with('certificado')->findOrFail($id);
        $certificado = $beneficiario->certificado;
        $nuevoEstado = (int) $beneficiario->Activo === 1 ? 0 : 1;

        if ($nuevoEstado === 1) {
            $totalActual = $this->totalBeneficiariosCertificado($certificado->Id, $beneficiario->Id);
            $nuevoTotal = $totalActual + (float) $beneficiario->Porcentaje;

            if ($nuevoTotal > 100) {
                return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#beneficiarios')
                    ->withErrors(['Porcentaje' => 'No se puede activar este beneficiario porque la suma de porcentajes activos superaria el 100%.']);
            }
        }

        $beneficiario->Activo = $nuevoEstado;
        $beneficiario->update();

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#beneficiarios')
            ->with('success', $nuevoEstado === 1
                ? 'Beneficiario activado correctamente'
                : 'Beneficiario inactivado correctamente');
    }

    public function certificado_cesion_beneficios_store($certificadoId, Request $request)
    {
        $certificado = PolizaSeguroCertificado::with('poliza')->findOrFail($certificadoId);

        $request->validate($this->reglasCesionBeneficios());

        $cesion = new PolizaSeguroCesionBeneficio();
        $cesion->PolizaSeguroId = $certificado->PolizaSeguroId;
        $cesion->PolizaSeguroCertificadoId = $certificado->Id;
        $this->asignarDatosCesion($cesion, $request);
        $cesion->Activo = 1;
        $cesion->save();

        return redirect('poliza/seguro/certificado/' . $certificado->Id . '/edit#cesiones')
            ->with('success', 'Cesion de beneficios agregada correctamente');
    }

    public function certificado_cesion_beneficios_update($id, Request $request)
    {
        $cesion = PolizaSeguroCesionBeneficio::with('certificado')->findOrFail($id);
        $request->validate($this->reglasCesionBeneficios());

        $this->asignarDatosCesion($cesion, $request);
        $cesion->update();

        return redirect('poliza/seguro/certificado/' . $cesion->PolizaSeguroCertificadoId . '/edit#cesiones')
            ->with('success', 'Cesion de beneficios actualizada correctamente');
    }

    public function certificado_cesion_beneficios_delete($id)
    {
        $cesion = PolizaSeguroCesionBeneficio::findOrFail($id);
        $certificadoId = $cesion->PolizaSeguroCertificadoId;
        $cesion->Activo = 0;
        $cesion->update();

        return redirect('poliza/seguro/certificado/' . $certificadoId . '/edit#cesiones')
            ->with('success', 'Cesion de beneficios eliminada correctamente');
    }

    public function certificado_cesion_beneficios_toggle($id)
    {
        $cesion = PolizaSeguroCesionBeneficio::with('certificado')->findOrFail($id);
        $nuevoEstado = (int) $cesion->Activo === 1 ? 0 : 1;
        $cesion->Activo = $nuevoEstado;
        $cesion->update();

        return redirect('poliza/seguro/certificado/' . $cesion->PolizaSeguroCertificadoId . '/edit#cesiones')
            ->with('success', $nuevoEstado === 1
                ? 'Cesion de beneficios activada correctamente'
                : 'Cesion de beneficios inactivada correctamente');
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
