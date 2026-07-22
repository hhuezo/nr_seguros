@extends ('welcome')
@section('contenido')
@can('seguro read')
    @php
        $esEdicion = $modo === 'edit';
        $action = $esEdicion
            ? url('poliza/seguro/certificado_update/' . $certificado->Id)
            : url('poliza/seguro/certificado_store/' . $poliza->Id);

        $fecha = function ($valor) {
            return $valor ? \Illuminate\Support\Carbon::parse($valor)->format('Y-m-d') : '';
        };

        $numero = function ($valor, $decimales = 2) {
            return $valor !== null && $valor !== '' ? number_format((float) $valor, $decimales, '.', '') : '';
        };

        $estadoCertificadoActual = old('EstadoCertificado', $certificado->EstadoCertificado);
        if (!$estadoCertificadoActual && $certificado->Estado) {
            $estadoPorNombre = $estados_certificado->first(function ($estado) use ($certificado) {
                return mb_strtoupper($estado->Nombre) === mb_strtoupper($certificado->Estado);
            });
            $estadoCertificadoActual = $estadoPorNombre->Id ?? null;
        }
        $motivoCancelacionActual = old('MotivoCancelacion', $certificado->MotivoCancelacion);
        $usuarioModificaNombre = optional($certificado->usuarioModifica)->name ?: optional(auth()->user())->name;
        $fechaModificacion = $certificado->FechaModificacion
            ? \Illuminate\Support\Carbon::parse($certificado->FechaModificacion)->format('Y-m-d')
            : now()->format('Y-m-d');
        $aplicaIvaProducto = (int) ($poliza->producto->CalcularIva ?? 0) === 1;
        $planSumasActual = old('Plan', $planCertificadoActual);
        $coberturasSumasIniciales = collect(old('coberturas', $certificadoCoberturas->toArray()))->values();
        $normalizarCampo = function ($texto) {
            $texto = \Illuminate\Support\Str::ascii((string) $texto);
            return preg_replace('/[^a-z0-9]/', '', mb_strtolower($texto));
        };
        $valorCampoDinamico = function ($valores, $candidatos, $fallback = '') use ($certificado_campos, $normalizarCampo) {
            $candidatos = collect((array) $candidatos)->map($normalizarCampo)->all();

            foreach ($certificado_campos as $campo) {
                $nombre = $normalizarCampo($campo->NombreCampo);
                $etiqueta = $normalizarCampo($campo->Etiqueta);

                if (in_array($nombre, $candidatos, true) || in_array($etiqueta, $candidatos, true)) {
                    return $valores[$campo->Id] ?? $fallback;
                }
            }

            return $fallback;
        };
        $formatearValorDetalle = function ($campo, $valor) use (&$catalogosOpcionesCertificado) {
            if ($valor === null || $valor === '') {
                return '';
            }

            if (($campo->OrigenOpciones ?? 'manual') === 'catalogo' && $campo->CatalogoOrigen === 'parentesco_beneficiario') {
                $opcion = collect($catalogosOpcionesCertificado['parentesco_beneficiario'] ?? [])
                    ->first(function ($item) use ($valor) {
                        return (string) ($item['Id'] ?? '') === (string) $valor;
                    });

                return $opcion['Nombre'] ?? $valor;
            }

            if (($campo->TipoCampo ?? null) === 'number') {
                $numero = preg_replace('/[^0-9.\-]/', '', (string) $valor);

                if ($numero === '' || !is_numeric($numero)) {
                    return $valor;
                }

                return '$' . number_format((float) $numero, 2, '.', ',');
            }

            return $valor;
        };
    @endphp

    @if (session('success'))
        <script>toastr.success("{{ session('success') }}");</script>
    @endif
    @if (session('error'))
        <script>toastr.error("{{ session('error') }}");</script>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .cert-header {
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            gap: 16px;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
        }
        .cert-title { color: #1e3a8a; font-size: 24px; font-weight: 700; margin: 0; text-align: center; }
        .cert-context { color: #475569; font-size: 12px; margin-top: 4px; text-align: center; }
        .cert-section { border-top: 1px solid #e5e7eb; padding: 14px 0 4px; }
        .cert-section:first-child { border-top: 0; }
        .cert-field { margin-bottom: 10px; }
        .cert-main-right .cert-section { border-top: 0; padding-top: 0; }
        .cert-field label { color: #334155; display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; }
        .cert-field .form-control[readonly] { background: #f8fafc; color: #334155; }
        .money-field { text-align: right; }
        .cert-money-table { border: 1px solid #d1d5db; margin-bottom: 12px; width: 100%; }
        .cert-money-table th {
            background: #f3f4f6;
            color: #475569;
            font-size: 14px;
            font-weight: 700;
            padding: 9px 10px;
        }
        .cert-money-table td {
            border-top: 1px solid #d1d5db;
            color: #587398;
            font-size: 14px;
            padding: 7px 10px;
            vertical-align: middle;
        }
        .cert-money-table td:first-child { width: 68%; }
        .cert-money-table .form-control {
            border: 0;
            box-shadow: none;
            color: #334155;
            height: 28px;
            padding: 3px 0;
            text-align: right;
        }
        .cert-money-table .form-control:focus {
            background: #f8fafc;
            box-shadow: inset 0 -1px 0 #3b82f6;
        }
        .cert-sumas-table th {
            background: #f8fafc;
            color: #334155;
            font-size: 12px;
            white-space: nowrap;
        }
        .cert-sumas-table td {
            font-size: 12px;
            vertical-align: middle !important;
        }
        .cert-sumas-table .form-control {
            font-size: 12px;
            height: 30px;
            padding: 4px 6px;
        }
        .cert-sumas-table .coverage-name { min-width: 260px; }
        .cert-sumas-table tfoot th { background: #eef2f7; font-weight: 700; }
        .cert-tarificacion-label {
            display: inline-block;
            margin-top: 4px;
            padding: 3px 7px;
            border-radius: 10px;
            background: #eef2ff;
            color: #1e3a8a;
            font-size: 11px;
            font-weight: 600;
        }
        .cert-config-warning {
            display: block;
            margin-top: 4px;
            color: #b45309;
            font-size: 11px;
        }
        .cert-row-disabled {
            background: #fff7ed;
        }
        .cert-detail-table td { font-size: 12px; vertical-align: top !important; }
        .cert-detail-table th {
            background: #4f93cf;
            color: #fff;
            font-size: 12px;
            white-space: nowrap;
        }
        .cert-detail-table .fila-dependiente td { background: #f8fbff; }
        .cert-detail-table .fila-dependiente:nth-child(even) td { background: #dcebf8; }
        .cert-detail-table .money-cell { text-align: right; white-space: nowrap; }
        .cert-detail-table .btn-inline-group {
            display: flex;
            gap: 6px;
            justify-content: center;
            white-space: nowrap;
        }
        .cert-detail-table .btn-inline-group .btn,
        .cert-detail-table .btn-inline-group form .btn {
            align-items: center;
            display: inline-flex;
            height: 30px;
            justify-content: center;
            padding: 0;
            width: 30px;
        }
        .cert-detail-table .btn-inline-group form { margin: 0; }
        .cert-actions {
            align-items: center;
            border-top: 1px solid #e5e7eb;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 14px;
            padding-top: 14px;
        }
        .cert-tabs { margin-top: 8px; }
        .cert-tabs li.disabled a {
            background: #f1f5f9 !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>

    <div class="x_panel">
        <div class="x_title">
            <div class="cert-header">
                <div>
                    <a href="{{ url('poliza/seguro/' . $poliza->Id . '/edit?tab=4') }}" class="btn btn-info">
                        <i class="fa fa-arrow-left"></i> Atras
                    </a>
                </div>
                <div>
                    <h3 class="cert-title">Certificados</h3>
                    <div class="cert-context">
                        Poliza {{ $poliza->NumeroPoliza ?: ('#' . $poliza->Id) }}
                        @if ($poliza->producto)
                            / {{ $poliza->producto->Nombre }}
                        @endif
                    </div>
                </div>
                <div>
                    <a href="{{ url('poliza/seguro/' . $poliza->Id . '/certificado/create') }}" class="btn btn-default">
                        <i class="fa fa-file"></i> Nuevo
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        @if (!$esEdicion)
            <div class="alert alert-info" style="margin-bottom:12px;">
                Guarde el certificado para habilitar sumas aseguradas, detalle del asegurado, beneficiarios, cesiones y datos tecnicos.
            </div>
        @endif

        <ul class="nav nav-tabs bar_tabs cert-tabs" role="tablist">
            <li class="active"><a href="#certificado" data-toggle="tab">Certificado</a></li>
            <li class="{{ !$esEdicion ? 'disabled' : '' }}"><a href="#sumas" data-toggle="tab" aria-disabled="{{ !$esEdicion ? 'true' : 'false' }}">Coberturas</a></li>
            <li class="{{ !$esEdicion ? 'disabled' : '' }}"><a href="#detalle" data-toggle="tab" aria-disabled="{{ !$esEdicion ? 'true' : 'false' }}">Detalle del Asegurado</a></li>
            <li class="{{ !$esEdicion ? 'disabled' : '' }}"><a href="#beneficiarios" data-toggle="tab" aria-disabled="{{ !$esEdicion ? 'true' : 'false' }}">Beneficiarios</a></li>
            <li class="{{ !$esEdicion ? 'disabled' : '' }}"><a href="#cesiones" data-toggle="tab" aria-disabled="{{ !$esEdicion ? 'true' : 'false' }}">Cesiones de Beneficios</a></li>
            <li class="{{ !$esEdicion ? 'disabled' : '' }}"><a href="#datos_tecnicos_certificado" data-toggle="tab" aria-disabled="{{ !$esEdicion ? 'true' : 'false' }}">Datos Tecnicos</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active in" id="certificado">
                <form id="formCertificado" method="POST" action="{{ $action }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-sm-12 cert-main-left">
                    <div class="cert-section">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 cert-field">
                                <label>Poliza</label>
                                <input type="text" class="form-control" value="{{ $poliza->NumeroPoliza ?: $poliza->Id }}" readonly>
                            </div>
                            <div class="col-md-4 col-sm-4 cert-field">
                                <label>Certificado interno</label>
                                <input type="text" class="form-control" value="{{ $certificado->NumeroCertificado }}" readonly>
                            </div>
                            <div class="col-md-4 col-sm-4 cert-field">
                                <label>Certificado aseguradora</label>
                                <input type="text" name="CertificadoAseguradora" class="form-control" value="{{ old('CertificadoAseguradora', $certificado->CertificadoAseguradora) }}">
                            </div>
                            <div class="col-md-5 cert-field">
                                <label>Vigencia desde *</label>
                                <input type="date" name="VigenciaDesde" id="VigenciaDesde" class="form-control" value="{{ old('VigenciaDesde', $fecha($certificado->VigenciaDesde)) }}" required>
                            </div>
                            <div class="col-md-5 cert-field">
                                <label>Vigencia hasta *</label>
                                <input type="date" name="VigenciaHasta" id="VigenciaHasta" class="form-control" value="{{ old('VigenciaHasta', $fecha($certificado->VigenciaHasta)) }}" required>
                            </div>
                            <div class="col-md-2 cert-field">
                                <label>Dias</label>
                                <input type="text" id="DiasVigencia" class="form-control money-field" value="{{ $certificado->DiasVigencia }}" readonly>
                            </div>
                            <div class="col-md-3 cert-field">
                                <label>Fecha inclusion *</label>
                                <input type="date" name="FechaInclusion" class="form-control" value="{{ old('FechaInclusion', $fecha($certificado->FechaInclusion)) }}" required>
                            </div>
                            <div class="col-md-9 cert-field">
                                <label>Estado certificado *</label>
                                <select name="EstadoCertificado" class="form-control select2" style="width:100%;" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($estados_certificado as $estado)
                                        <option value="{{ $estado->Id }}" {{ $estadoCertificadoActual == $estado->Id ? 'selected' : '' }}>{{ $estado->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 cert-field">
                                <label>DUI/ Numero Documento. *</label>
                                <input type="text" name="CodAsegurado" class="form-control" value="{{ old('CodAsegurado', $certificado->CodAsegurado) }}" required>
                            </div>
                            <div class="col-md-8 cert-field">
                                <label>Nombre asegurado / Bien Asegurado  *</label>
                                <input type="text" name="Asegurado" class="form-control" value="{{ old('Asegurado', $certificado->Asegurado) }}" required>
                            </div>
                            <div class="col-md-6 cert-field">
                                <label>Fecha nacimiento</label>
                                <input type="date" name="FechaNacimiento" class="form-control" value="{{ old('FechaNacimiento', $fecha($certificado->FechaNacimiento)) }}">
                            </div>
                            <div class="col-md-6 cert-field">
                                <label>Sexo</label>
                                <select name="Sexo" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    <option value="M" {{ old('Sexo', $certificado->Sexo) === 'M' ? 'selected' : '' }}>MASCULINO</option>
                                    <option value="F" {{ old('Sexo', $certificado->Sexo) === 'F' ? 'selected' : '' }}>FEMENINO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 cert-field">
                                <label>Producto</label>
                                <input type="text" class="form-control" value="{{ $poliza->producto->Nombre ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6 cert-field">
                                <label>Plan del certificado *</label>
                                <select name="Plan" id="PlanCertificado" class="form-control select2" style="width:100%;" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($planesCertificado as $plan)
                                        <option value="{{ $plan->Id }}" {{ $planSumasActual == $plan->Id ? 'selected' : '' }}>{{ $plan->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="cert-section">
                        <div class="row">

{{--                            incluir este campo nuevo de fecha de exclusion--}}
                            <div class="col-md-4 cert-field">
                                <label>Fecha exclusión </label>
                                <input type="date" name="FechaExclusion" class="form-control" value="{{ old('FechaExclusion', $fecha($certificado->FechaExclusion)) }}">
                            </div>
{{--                            agregar este nuevo campo tambien--}}
                            <div class="col-md-8 cert-field">
                                <label>Motivo Exclusión</label>
                                <select name="MotivoCancelacion" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($motivos_cancelacion as $motivo)
                                        <option value="{{ $motivo->Id }}" {{ $motivoCancelacionActual == $motivo->Id ? 'selected' : '' }}>{{ $motivo->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                                <div class="col-md-12 cert-field">
                                <label>Notas exclusión </label>
                                <input type="text" name="MotivoExclusion" class="form-control" value="{{ old('MotivoExclusion', $certificado->MotivoExclusion) }}">
                            </div>


                        </div>
                    </div>

                    <div class="cert-section">
                        <div class="row">
                            <div class="col-md-12 cert-field">
                                <label>Observacion / comentarios</label>
                                <textarea name="Observacion" class="form-control" rows="5">{{ old('Observacion', $certificado->Observacion) }}</textarea>
                            </div>
                        </div>
                    </div>
                        </div>
                        <div class="col-md-6 col-sm-12 cert-main-right">
                            <div class="cert-section">
                                <fieldset {{ !$esEdicion ? 'disabled' : '' }}>
                                    <table class="cert-money-table">
                                        <thead>
                                            <tr>
                                                <th>Detalle general de cobro</th>
                                                <th class="text-right">USD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Suma asegurada</td>
                                                <td><input type="text" name="ValorAsegurado" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('ValorAsegurado', $numero($certificado->ValorAsegurado)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Prima total</td>
                                                <td><input type="text" name="PrimaTotal" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('PrimaTotal', $numero($certificado->PrimaTotal)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>% Descuento rentabilidad</td>
                                                <td><input type="number" name="PorcentajeDescuentoRentabilidad" class="form-control money-field" step="0.0001" value="{{ old('PorcentajeDescuentoRentabilidad', $numero($certificado->PorcentajeDescuentoRentabilidad ?? $poliza->PorcentajeDescuentoRentabilidad, 4)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Valor descuento Rentabilidad</td>
                                                <td><input type="text" name="ValorDescuento" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('ValorDescuento', $numero($certificado->ValorDescuento)) }}" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>% Descuento Buena experiencia</td>
                                                <td><input type="number" name="PorcentajeDescuentoBuenaExperiencia" class="form-control money-field" step="0.0001" value="{{ old('PorcentajeDescuentoBuenaExperiencia', $numero($certificado->PorcentajeDescuentoBuenaExperiencia ?? $poliza->PorcentajeDescuentoBuenaExperiencia, 4)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Valor descuento Buena experiencia</td>
                                                <td><input type="text" name="ValorDescuentoBuenaExperiencia" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('ValorDescuentoBuenaExperiencia', $numero($certificado->ValorDescuentoBuenaExperiencia)) }}" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>% Otros Descuentos</td>
                                                <td><input type="number" name="PorcentajeOtrosDescuentos" class="form-control money-field" step="0.0001" value="{{ old('PorcentajeOtrosDescuentos', $numero($certificado->PorcentajeOtrosDescuentos ?? $poliza->PorcentajeOtrosDescuentos, 4)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Valor otros descuentos</td>
                                                <td><input type="text" name="ValorOtrosDescuentos" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('ValorOtrosDescuentos', $numero($certificado->ValorOtrosDescuentos)) }}" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Prima neta</td>
                                                <td><input type="text" name="PrimaNeta" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('PrimaNeta', $numero($certificado->PrimaNeta)) }}" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Prima exenta</td>
                                                <td><input type="text" name="PrimaExenta" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('PrimaExenta', $numero($certificado->PrimaExenta)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Gastos emision</td>
                                                <td><input type="text" name="GastosEmision" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('GastosEmision', $numero($certificado->GastosEmision)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Gastos fraccionamiento</td>
                                                <td><input type="text" name="GastosFraccionamiento" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('GastosFraccionamiento', $numero($certificado->GastosFraccionamiento)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Gastos de bomberos</td>
                                                <td><input type="text" name="GastosBomberos" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('GastosBomberos', $numero($certificado->GastosBomberos)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>Otros gastos</td>
                                                <td><input type="text" name="OtrosGastos" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('OtrosGastos', $numero($certificado->OtrosGastos)) }}"></td>
                                            </tr>
                                            <tr>
                                                <td>IVA 13%</td>
                                                <td><input type="text" name="Impuestos" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('Impuestos', $numero($certificado->Impuestos)) }}" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Total certificado</td>
                                                <td><input type="text" name="TotalCertificado" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('TotalCertificado', $numero($certificado->TotalCertificado)) }}" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="cert-actions">
                        <a href="{{ url('poliza/seguro/' . $poliza->Id . '/certificado/create') }}" class="btn btn-default">
                            <i class="fa fa-file"></i> Nuevo
                        </a>
                        <button type="submit" id="btnGuardarCertificado" class="btn btn-primary">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                        <a href="{{ url('poliza/seguro/' . $poliza->Id . '/edit?tab=4') }}" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> Atras
                        </a>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="sumas">
                @if (!$esEdicion)
                    <div class="alert alert-info">Guarde el certificado para configurar las sumas aseguradas.</div>
                @else
                    <form id="formSumasAseguradas" method="POST" action="{{ url('poliza/seguro/certificado_sumas_save/' . $certificado->Id) }}">
                        @csrf
                        {{-- El tab de coberturas tiene su propio form; se replica el plan seleccionado para que viaje en este POST. --}}
                        <input type="hidden" name="Plan" id="PlanSumas" value="{{ $planSumasActual }}">
                        <div class="cert-section">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped cert-sumas-table" id="tabla-sumas-aseguradas">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th class="coverage-name">Cobertura</th>
                                            <th>Suma asegurada</th>
                                            <th>% Suma</th>
                                            <th>Tasa</th>
                                            <th>Dias prorrata</th>
                                            <th>Prima anual</th>
                                            <th>Prima prorrata</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-right">Totales</th>
                                            <th class="text-right" id="TotalSumaAsegurada">$0.00</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right" id="TotalPrimaAnual">$0.00</th>
                                            <th class="text-right" id="TotalPrima">$0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="cert-actions">
                                <button type="submit" id="btnGuardarSumas" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Guardar Coberturas
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
            <div class="tab-pane fade" id="detalle">
                @if (!$esEdicion)
                    <div class="alert alert-info">Guarde el certificado para registrar dependientes.</div>
                @else
                    <div class="cert-section">
                        <div class="cert-header" style="border-bottom:0; margin-bottom:0;">
                            <div>
                                <h4 style="margin:0;">Dependientes del certificado</h4>
                                <div class="cert-context">La caratula del certificado contiene al asegurado principal; aqui se registran solo dependientes.</div>
                            </div>
                            @if ($permite_dependientes && $certificado_campos->count() > 0)
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDependienteCreate">
                                    <i class="fa fa-plus"></i> Nuevo dependiente
                                </button>
                            @endif
                        </div>

                        @if (!$permite_dependientes)
                            <div class="alert alert-warning">El producto asociado al plan del certificado no permite dependientes.</div>
                        @elseif ($certificado_campos->count() === 0)
                            <div class="alert alert-warning">El producto no tiene campos dinamicos configurados para capturar dependientes.</div>
                        @else
                            <div class="table-responsive">
                                <table id="tabla-detalle-asegurados" class="table table-bordered cert-detail-table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Dependiente</th>
                                            <th>Certificado</th>
                                            @foreach ($certificado_campos as $campo)
                                                <th>{{ $campo->Etiqueta }}</th>
                                            @endforeach
                                            <th>Observacion</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($certificado->dependientes as $dependiente)
                                            @php
                                                $datosDependiente = json_decode($dependiente->DatosJson ?: '[]', true);
                                                $datosDependiente = is_array($datosDependiente) ? $datosDependiente : [];
                                                $valoresDependiente = collect($datosDependiente)->mapWithKeys(function ($dato) {
                                                    return [$dato['CampoId'] => $dato['ValorId'] ?? $dato['Valor'] ?? null];
                                                })->all();
                                            @endphp
                                            <tr class="fila-dependiente">
                                                <td>{{ $dependiente->NumeroDependiente }}</td>
                                                <td>{{ $certificado->NumeroCertificado }}</td>
                                                @foreach ($certificado_campos as $campo)
                                                    <td>{{ $formatearValorDetalle($campo, $valoresDependiente[$campo->Id] ?? '') }}</td>
                                                @endforeach
                                                <td>{{ $dependiente->Observacion }}</td>
                                                <td class="text-center">
                                                    <div class="btn-inline-group">
                                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalDependienteEdit{{ $dependiente->Id }}">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <form method="POST" action="{{ url('poliza/seguro/dependiente_delete/' . $dependiente->Id) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirme si desea eliminar este dependiente.');">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $certificado_campos->count() + 4 }}" class="text-center text-muted">No hay dependientes registrados para este certificado.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="tab-pane fade" id="beneficiarios">
                @if (!$esEdicion)
                    <div class="alert alert-info">Guarde el certificado para registrar beneficiarios.</div>
                @else
                    @php
                        $beneficiariosTabla = $certificado->beneficiariosTodos ?? collect();
                        $totalBeneficiariosCertificado = (float) $beneficiariosTabla->where('Activo', 1)->sum('Porcentaje');
                    @endphp
                    <div class="cert-section">
                        <div class="cert-header" style="border-bottom:0; margin-bottom:0;">
                            <div>
                                <h4 style="margin:0;">Beneficiarios</h4>
                                <div class="cert-context">La suma de porcentajes debe cerrar exactamente en 100%.</div>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBeneficiarioCreate">
                                <i class="fa fa-plus"></i> Nuevo beneficiario
                            </button>
                        </div>

                        <div id="alerta-beneficiarios-cuadre" class="alert {{ abs($totalBeneficiariosCertificado - 100) < 0.001 ? 'alert-success' : 'alert-warning' }}">
                            Total asignado: <strong id="beneficiarios-total-label">{{ number_format($totalBeneficiariosCertificado, 2) }}%</strong>.
                            Debe ser 100.00% para continuar.
                        </div>

                        <div class="table-responsive">
                            <table id="tabla-certificado-beneficiarios" class="table table-striped table-bordered cert-detail-table" style="width:100%;" data-total="{{ number_format($totalBeneficiariosCertificado, 2, '.', '') }}">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>DUI</th>
                                        <th>Parentesco</th>
                                        <th>Fecha nacimiento</th>
                                        <th>Porcentaje</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($beneficiariosTabla as $beneficiario)
                                        <tr>
                                            <td>{{ $beneficiario->Nombre }}</td>
                                            <td>{{ $beneficiario->Dui }}</td>
                                            <td>{{ $beneficiario->parentesco->Nombre ?? '' }}</td>
                                            <td>{{ $beneficiario->FechaNacimiento }}</td>
                                            <td class="text-right {{ (int) $beneficiario->Activo === 1 ? 'js-beneficiario-porcentaje-activo' : '' }}">{{ number_format((float) $beneficiario->Porcentaje, 2, '.', '') }}%</td>
                                            <td class="text-center">
                                                <span class="label {{ (int) $beneficiario->Activo === 1 ? 'label-success' : 'label-danger' }}">
                                                    {{ (int) $beneficiario->Activo === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-inline-group">
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalBeneficiarioEdit{{ $beneficiario->Id }}" data-tooltip="tooltip" data-placement="top" title="Editar beneficiario">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <form method="POST" action="{{ url('poliza/seguro/certificado_beneficiario_toggle/' . $beneficiario->Id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn {{ (int) $beneficiario->Activo === 1 ? 'btn-danger' : 'btn-success' }} btn-sm" data-tooltip="tooltip" data-placement="top" title="{{ (int) $beneficiario->Activo === 1 ? 'Inactivar beneficiario' : 'Activar beneficiario' }}" onclick="return confirm('Confirme si desea {{ (int) $beneficiario->Activo === 1 ? 'inactivar' : 'activar' }} este beneficiario.');">
                                                            <i class="fa {{ (int) $beneficiario->Activo === 1 ? 'fa-ban' : 'fa-check' }}"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">Total activos</th>
                                        <th class="text-right" id="beneficiarios-total-footer">{{ number_format($totalBeneficiariosCertificado, 2) }}%</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="tab-pane fade" id="cesiones">
                @if (!$esEdicion)
                    <div class="alert alert-info">Guarde el certificado para registrar cesiones de beneficios.</div>
                @else
                    @php
                        $cesionesTabla = $certificado->cesionBeneficiosTodos ?? collect();
                        $totalCesionActiva = (float) $cesionesTabla->where('Activo', 1)->sum('SumaCedida');
                    @endphp
                    <div class="cert-section">
                        <div class="cert-header" style="border-bottom:0; margin-bottom:0;">
                            <div>
                                <h4 style="margin:0;">Cesiones de beneficios</h4>
                                <div class="cert-context">Cesiones registradas para este certificado.</div>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCesionCreate">
                                <i class="fa fa-plus"></i> Agregar cesion
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table id="tabla-certificado-cesiones" class="table table-striped table-bordered cert-detail-table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Correlativo</th>
                                        <th>Cesionario</th>
                                        <th>Fec. Vigencia</th>
                                        <th>Fec. Cancelacion</th>
                                        <th>Suma Cedida</th>
                                        <th>Observaciones</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cesionesTabla as $cesion)
                                        <tr>
                                            <td>{{ $cesion->CodigoSesion }}</td>
                                            <td>{{ $cesion->cesionario->Nombre ?? '' }}</td>
                                            <td>{{ $cesion->FechaVigencia }}</td>
                                            <td>{{ $cesion->FechaCancelacion }}</td>
                                            <td class="text-right">{{ $cesion->SumaCedida !== null ? '$ ' . number_format((float) $cesion->SumaCedida, 2, '.', ',') : '' }}</td>
                                            <td>{{ $cesion->Observaciones }}</td>
                                            <td class="text-center">
                                                <span class="label {{ (int) $cesion->Activo === 1 ? 'label-success' : 'label-danger' }}">
                                                    {{ (int) $cesion->Activo === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-inline-group">
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalCesionEdit{{ $cesion->Id }}" data-tooltip="tooltip" data-placement="top" title="Editar cesion">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <form method="POST" action="{{ url('poliza/seguro/certificado_cesion_beneficios_toggle/' . $cesion->Id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn {{ (int) $cesion->Activo === 1 ? 'btn-danger' : 'btn-success' }} btn-sm" data-tooltip="tooltip" data-placement="top" title="{{ (int) $cesion->Activo === 1 ? 'Inactivar cesion' : 'Activar cesion' }}" onclick="return confirm('Confirme si desea {{ (int) $cesion->Activo === 1 ? 'inactivar' : 'activar' }} esta cesion de beneficios.');">
                                                            <i class="fa {{ (int) $cesion->Activo === 1 ? 'fa-ban' : 'fa-check' }}"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total suma cedida</th>
                                        <th class="text-right">$ {{ number_format($totalCesionActiva, 2, '.', ',') }}</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="tab-pane fade" id="datos_tecnicos_certificado">
                @if (!$esEdicion)
                    <div class="alert alert-info">Guarde el certificado para completar los datos tecnicos.</div>
                @else
                    <form id="formDatosTecnicosCertificado" method="POST" action="{{ url('poliza/seguro/certificado_datos_tecnicos_save/' . $certificado->Id) }}">
                        @csrf
                        <div class="cert-section">
                            <div class="cert-header" style="border-bottom:0; margin-bottom:0;">
                                <div>
                                    <h4 style="margin:0;">Datos tecnicos</h4>
                                    <div class="cert-context">Campos configurados para el producto del plan seleccionado en este certificado.</div>
                                </div>
                            </div>

                            @if ($datosTecnicosCertificado->count() > 0)
                                <div class="row">
                                    @foreach ($datosTecnicosCertificado as $datoTecnico)
                                        @php
                                            $valorTecnico = optional($valoresDatosTecnicosCertificado->get($datoTecnico->Id))->Valor;
                                        @endphp
                                        <div class="col-md-6 col-sm-12 cert-field">
                                            <label>{{ $datoTecnico->Nombre }}</label>
                                            <textarea name="DatosTecnicos[{{ $datoTecnico->Id }}]" class="form-control" rows="3">{{ old('DatosTecnicos.' . $datoTecnico->Id, $valorTecnico) }}</textarea>
                                            @if ($datoTecnico->Descripcion)
                                                <small class="text-muted">{{ $datoTecnico->Descripcion }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="cert-actions">
                                    <button type="submit" id="btnGuardarDatosTecnicosCertificado" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Guardar datos tecnicos
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning" style="margin-bottom:0;">
                                    El producto del plan seleccionado no tiene datos tecnicos configurados.
                                </div>
                            @endif
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if ($esEdicion && $permite_dependientes)
        <div class="modal fade" id="modalDependienteCreate" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('poliza/seguro/dependiente_store/' . $certificado->Id) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Nuevo dependiente</h4>
                    </div>
                    <div class="modal-body">
                        @if ($certificado_campos->count() > 0)
                            <div class="row">
                                @php $campoValores = []; @endphp
                                @include('polizas.seguro.partials.certificado_campos_form')
                                <div class="col-md-12 cert-field">
                                    <label>Observacion</label>
                                    <textarea name="Observacion" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">No hay campos configurados para capturar dependientes.</div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        @if ($certificado_campos->count() > 0)
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Guardar dependiente
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @foreach ($certificado->dependientes as $dependiente)
            @php
                $datosDependiente = json_decode($dependiente->DatosJson ?: '[]', true);
                $datosDependiente = is_array($datosDependiente) ? $datosDependiente : [];
                $campoValoresDependiente = collect($datosDependiente)->mapWithKeys(function ($dato) {
                    return [$dato['CampoId'] => $dato['ValorId'] ?? $dato['Valor'] ?? null];
                })->all();
            @endphp
            <div class="modal fade" id="modalDependienteEdit{{ $dependiente->Id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('poliza/seguro/dependiente_update/' . $dependiente->Id) }}" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Editar dependiente #{{ $dependiente->NumeroDependiente }}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @php $campoValores = $campoValoresDependiente; @endphp
                                @include('polizas.seguro.partials.certificado_campos_form')
                                <div class="col-md-12 cert-field">
                                    <label>Observacion</label>
                                    <textarea name="Observacion" class="form-control" rows="2">{{ $dependiente->Observacion }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

    @if ($esEdicion)
        <div class="modal fade" id="modalBeneficiarioCreate" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('poliza/seguro/certificado_beneficiario_store/' . $certificado->Id) }}" class="modal-content js-form-beneficiario">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Nuevo beneficiario</h4>
                    </div>
                    <div class="modal-body">
                            <div class="row">
                            <div class="col-md-6 cert-field">
                                <label>Nombre *</label>
                                <input type="text" name="Nombre" class="form-control" value="{{ old('Nombre') }}" required>
                            </div>
                            <div class="col-md-6 cert-field">
                                <label>DUI</label>
                                <input type="text" name="Dui" class="form-control" value="{{ old('Dui') }}" data-inputmask="'mask': '99999999-9'" data-mask>
                            </div>
                            <div class="col-md-4 cert-field">
                                <label>Parentesco</label>
                                <select name="Parentesco" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @forelse ($parentescos as $parentesco)
                                        <option value="{{ $parentesco->Id }}" {{ old('Parentesco') == $parentesco->Id ? 'selected' : '' }}>{{ $parentesco->Nombre }}</option>
                                    @empty
                                        <option value="" disabled>No hay parentescos configurados</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-4 cert-field">
                                <label>Fecha nacimiento</label>
                                <input type="date" name="FechaNacimiento" class="form-control" value="{{ old('FechaNacimiento') }}">
                            </div>
                            <div class="col-md-4 cert-field">
                                <label>Porcentaje *</label>
                                <input type="number" name="Porcentaje" class="form-control js-beneficiario-input-porcentaje" step="0.01" min="0.01" max="100" value="{{ old('Porcentaje') }}" data-current="0" required>
                            </div>
                        </div>
                        <div class="alert alert-info" style="margin-bottom:0;">
                            El total de beneficiarios del certificado debe quedar en 100.00%.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($beneficiariosTabla as $beneficiario)
            <div class="modal fade" id="modalBeneficiarioEdit{{ $beneficiario->Id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('poliza/seguro/certificado_beneficiario_update/' . $beneficiario->Id) }}" class="modal-content js-form-beneficiario">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Editar beneficiario</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-3 cert-field">
                                    <label>Nombre *</label>
                                    <input type="text" name="Nombre" class="form-control" value="{{ $beneficiario->Nombre }}" required>
                                </div>
                                <div class="col-md-2 cert-field">
                                    <label>DUI</label>
                                    <input type="text" name="Dui" class="form-control" value="{{ $beneficiario->Dui }}" data-inputmask="'mask': '99999999-9'" data-mask>
                                </div>
                                <div class="col-md-3 cert-field">
                                    <label>Parentesco</label>
                                    <select name="Parentesco" class="form-control select2" style="width:100%;">
                                        <option value="">Seleccione...</option>
                                        @forelse ($parentescos as $parentesco)
                                            <option value="{{ $parentesco->Id }}" {{ $beneficiario->Parentesco == $parentesco->Id ? 'selected' : '' }}>{{ $parentesco->Nombre }}</option>
                                        @empty
                                            <option value="" disabled>No hay parentescos configurados</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-2 cert-field">
                                    <label>Fecha nacimiento</label>
                                    <input type="date" name="FechaNacimiento" class="form-control" value="{{ $beneficiario->FechaNacimiento }}">
                                </div>
                                <div class="col-md-2 cert-field">
                                    <label>Porcentaje *</label>
                                    <input type="number" name="Porcentaje" class="form-control js-beneficiario-input-porcentaje" step="0.01" min="0.01" max="100" value="{{ number_format((float) $beneficiario->Porcentaje, 2, '.', '') }}" data-current="{{ (int) $beneficiario->Activo === 1 ? number_format((float) $beneficiario->Porcentaje, 2, '.', '') : '0' }}" required>
                                </div>
                            </div>
                            <div class="alert alert-info" style="margin-bottom:0;">
                                El total de beneficiarios del certificado debe quedar en 100.00%.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

    @if ($esEdicion)
        <div class="modal fade" id="modalCesionCreate" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('poliza/seguro/certificado_cesion_beneficios_store/' . $certificado->Id) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Nueva cesion de beneficios</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 cert-field">
                                <label>Correlativo</label>
                                <input type="text" name="CodigoSesion" class="form-control" value="{{ old('CodigoSesion', $siguienteCodigoCesion) }}" readonly>
                            </div>
                            <div class="col-md-8 cert-field">
                                <label>Cesionario *</label>
                                <select name="CesionarioId" class="form-control select2" style="width:100%;" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($cesionarios as $cesionario)
                                        <option value="{{ $cesionario->Id }}" {{ old('CesionarioId') == $cesionario->Id ? 'selected' : '' }}>{{ $cesionario->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 cert-field">
                                <label>Fecha Inicio Vigencia</label>
                                <input type="date" name="FechaVigencia" class="form-control" value="{{ old('FechaVigencia') }}">
                            </div>
                            <div class="col-md-4 cert-field">
                                <label>Fec. Cancelación</label>
                                <input type="date" name="FechaCancelacion" class="form-control" value="{{ old('FechaCancelacion') }}">
                            </div>
                            <div class="col-md-4 cert-field">
                                <label>Suma cedida</label>
                                <input type="text" name="SumaCedida" class="form-control money-field currency-field" inputmode="decimal" value="{{ old('SumaCedida') }}">
                            </div>
                            <div class="col-md-12 cert-field">
                                <label>Observaciones</label>
                                <textarea name="Observaciones" class="form-control" rows="4">{{ old('Observaciones') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($cesionesTabla as $cesion)
            <div class="modal fade" id="modalCesionEdit{{ $cesion->Id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('poliza/seguro/certificado_cesion_beneficios_update/' . $cesion->Id) }}" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Editar cesion de beneficios</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 cert-field">
                                    <label>Correlativo</label>
                                    <input type="text" name="CodigoSesion" class="form-control" value="{{ $cesion->CodigoSesion }}" readonly>
                                </div>
                                <div class="col-md-8 cert-field">
                                    <label>Cesionario *</label>
                                    <select name="CesionarioId" class="form-control select2" style="width:100%;" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($cesionarios as $cesionario)
                                            <option value="{{ $cesionario->Id }}" {{ (int) old('CesionarioId', $cesion->CesionarioId) === (int) $cesionario->Id ? 'selected' : '' }}>{{ $cesionario->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 cert-field">
                                    <label>Fecha Inicio Vigencia</label>
                                    <input type="date" name="FechaVigencia" class="form-control" value="{{ $cesion->FechaVigencia }}">
                                </div>
                                <div class="col-md-4 cert-field">
                                    <label>Fec. Cancelación</label>
                                    <input type="date" name="FechaCancelacion" class="form-control" value="{{ $cesion->FechaCancelacion }}">
                                </div>
                                <div class="col-md-4 cert-field">
                                    <label>Suma cedida</label>
                                    <input type="text" name="SumaCedida" class="form-control money-field currency-field" inputmode="decimal" value="{{ $cesion->SumaCedida }}">
                                </div>
                                <div class="col-md-8 cert-field">
                                    <label>Observaciones</label>
                                    <textarea name="Observaciones" class="form-control" rows="2">{{ $cesion->Observaciones }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        const coberturasPorPlan = @json($planCoberturasCatalogo->toArray());
        const coberturasSumasIniciales = @json($coberturasSumasIniciales);

        function calcularDiasCertificado() {
            const desde = document.getElementById('VigenciaDesde').value;
            const hasta = document.getElementById('VigenciaHasta').value;
            const dias = document.getElementById('DiasVigencia');

            if (!desde || !hasta) {
                dias.value = '';
                return;
            }

            const fechaDesde = new Date(desde + 'T00:00:00');
            const fechaHasta = new Date(hasta + 'T00:00:00');
            if (fechaHasta < fechaDesde) {
                dias.value = '';
                return;
            }

            dias.value = Math.round((fechaHasta - fechaDesde) / 86400000);
        }

        function numeroDecimal(valor, decimales = 2) {
            const numero = parseCurrency(valor);
            return Number.isFinite(numero) ? numero.toFixed(decimales) : '';
        }

        function parseCurrency(valor) {
            if (typeof valor === 'number') {
                return Number.isFinite(valor) ? valor : 0;
            }

            let texto = String(valor || '').trim();

            if (texto.includes(',') && !texto.includes('.')) {
                const partes = texto.split(',');
                const ultimaParte = partes[partes.length - 1] || '';

                if (partes.length === 2 && ultimaParte.length !== 3) {
                    texto = texto.replace(',', '.');
                } else {
                    texto = texto.replace(/,/g, '');
                }
            }

            const limpio = texto.replace(/,/g, '').replace(/[^0-9.\-]/g, '');
            const numero = parseFloat(limpio);
            return Number.isFinite(numero) ? numero : 0;
        }

        function moneda(valor) {
            const numero = parseCurrency(valor);
            return '$' + numero.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function valorMonedaPlano(valor, decimales = 2) {
            const numero = parseCurrency(valor);
            return Number.isFinite(numero) ? numero.toFixed(decimales) : '';
        }

        function aplicarFormatoMoneda(contexto) {
            const raiz = $(contexto || document);
            const inputs = raiz.is('.currency-field') ? raiz : raiz.find('.currency-field');

            inputs.each(function() {
                const input = $(this);
                const decimales = parseInt(input.data('currency-decimals') || 2, 10);
                const valor = input.val();

                if (valor === '') {
                    return;
                }

                input.val(moneda(valorMonedaPlano(valor, decimales)));
            });
        }

        function setCurrencyValue(input, valor, decimales = 2) {
            const elemento = $(input);
            elemento.val(moneda(valorMonedaPlano(valor, decimales)));
        }

        function aplicarMayusculasFormulario(selector) {
            const formulario = $(selector);

            if (!formulario.length) {
                return;
            }

            const objetivo = 'input[type="text"]:not([readonly]):not(.currency-field), textarea';

            function sanitizarTextoEncabezado(valor) {
                return String(valor || '')
                    .replace(/ñ/g, '__enie_lower__')
                    .replace(/Ñ/g, '__enie_upper__')
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/__enie_lower__/g, 'ñ')
                    .replace(/__enie_upper__/g, 'Ñ')
                    .toLocaleUpperCase('es-SV');
            }

            formulario.on('input blur', objetivo, function() {
                this.value = sanitizarTextoEncabezado(this.value);
            });

            formulario.on('submit', function() {
                formulario.find(objetivo).each(function() {
                    this.value = sanitizarTextoEncabezado(this.value);
                });
            });
        }

        function inputTabla(nombre, indice, campo, valor, decimales = 2, extraClass = '', extraAttrs = '', esMoneda = false) {
            const step = decimales === 0 ? '1' : (decimales === 6 ? '0.000001' : (decimales === 4 ? '0.0001' : '0.01'));
            const tipo = esMoneda ? 'text' : 'number';
            const claseMoneda = esMoneda ? ' currency-field' : '';
            const atributosMoneda = esMoneda ? ' inputmode="decimal" data-currency-decimals="' + decimales + '"' : '';
            return '<input type="' + tipo + '" class="form-control money-field suma-certificado-input ' + extraClass + claseMoneda + '" ' +
                'name="' + nombre + '[' + indice + '][' + campo + ']" step="' + step + '" ' +
                'value="' + numeroDecimal(valor, decimales) + '"' + atributosMoneda + ' ' + extraAttrs + '>';
        }

        function escapeAttr(valor) {
            return String(valor ?? '')
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function normalizarTextoCalculo(valor) {
            return String(valor || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim();
        }

        function calcularPrimaAnualCobertura(suma, tasa, primaBase, tarificacionNombre) {
            const tipo = normalizarTextoCalculo(tarificacionNombre);

            if (tipo.includes('sin cobro')) {
                return 0;
            }

            if (tipo.includes('prima')) {
                return primaBase;
            }

            if (tipo.includes('millar')) {
                return suma * (tasa / 1000);
            }

            if (tipo.includes('porcentual')) {
                return suma * (tasa / 100);
            }

            return tasa > 0 ? (suma * (tasa / 100)) : primaBase;
        }

        function textoTipoTarificacion(tarificacionNombre) {
            const tipo = normalizarTextoCalculo(tarificacionNombre);

            if (tipo.includes('sin cobro')) {
                return 'Sin cobro de prima';
            }

            if (tipo.includes('millar')) {
                return 'Tasa por millar';
            }

            if (tipo.includes('porcentual')) {
                return 'Tasa porcentual';
            }

            if (tipo.includes('prima')) {
                return 'Prima fija';
            }

            return tarificacionNombre || 'Tarificacion no definida';
        }

        function diasProrrataExcel() {
            const desde = document.getElementById('VigenciaDesde').value;
            const hasta = document.getElementById('VigenciaHasta').value;

            if (!desde || !hasta) {
                return 0;
            }

            const fechaDesde = new Date(desde + 'T00:00:00');
            const fechaHasta = new Date(hasta + 'T00:00:00');

            if (fechaHasta < fechaDesde) {
                return 0;
            }

            return Math.round((fechaHasta - fechaDesde) / 86400000);
        }

        function renderCoberturasSumas(coberturas) {
            const tbody = $('#tabla-sumas-aseguradas tbody');
            tbody.empty();

            if (!coberturas || coberturas.length === 0) {
                tbody.append('<tr><td colspan="8" class="text-center text-muted">El plan seleccionado no tiene coberturas configuradas.</td></tr>');
                calcularTotalesSumas();
                return;
            }

            coberturas.forEach(function(cobertura, index) {
                const nombre = cobertura.Nombre || '';
                const coberturaId = cobertura.Cobertura || '';
                const tarificacion = cobertura.Tarificacion || '';
                const tarificacionNombre = cobertura.TarificacionNombre || '';
                const tipoTarificacion = normalizarTextoCalculo(tarificacionNombre);
                const configuradaEnPlan = parseInt(cobertura.ConfiguradaEnPlan ?? 1, 10) === 1;
                const sinCobro = tipoTarificacion.includes('sin cobro');
                const configurablePorTarificacion = tipoTarificacion.includes('porcentual') ||
                    tipoTarificacion.includes('millar') ||
                    tipoTarificacion.includes('prima');
                const capturaPermitida = configuradaEnPlan || configurablePorTarificacion;
                const filaBloqueada = !capturaPermitida || sinCobro;
                const camposBloqueados = filaBloqueada ? 'readonly' : '';
                const camposNoConfigurados = (!capturaPermitida || sinCobro) ? 'disabled' : camposBloqueados;
                const camposCalculados = (!capturaPermitida || sinCobro) ? 'disabled' : 'readonly';
                const metadataInputs = capturaPermitida && !sinCobro
                    ? '<input type="hidden" name="coberturas[' + index + '][Cobertura]" value="' + coberturaId + '">' +
                        '<input type="hidden" name="coberturas[' + index + '][Tarificacion]" value="' + escapeAttr(tarificacion) + '">' +
                        '<input type="hidden" name="coberturas[' + index + '][TarificacionNombre]" value="' + escapeAttr(tarificacionNombre) + '">' +
                        '<input type="hidden" name="coberturas[' + index + '][Nombre]" value="' + escapeAttr(nombre) + '">'
                    : '';
                const sumaAsegurada = capturaPermitida ? cobertura.SumaAsegurada : 0;
                const porcentajeSuma = capturaPermitida ? cobertura.PorcentajeSuma : 0;
                const tasa = capturaPermitida ? cobertura.Tasa : 0;
                const diasProrrata = capturaPermitida ? cobertura.DiasProrrata : 0;
                const primaAnual = capturaPermitida
                    ? (cobertura.PrimaAnual !== null && cobertura.PrimaAnual !== undefined ? cobertura.PrimaAnual : cobertura.Prima)
                    : 0;
                const prima = capturaPermitida ? cobertura.Prima : 0;
                const row = '<tr' + (filaBloqueada ? ' class="cert-row-disabled"' : '') + '>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' +
                        metadataInputs +
                        escapeAttr(nombre) +
                        '<br><span class="cert-tarificacion-label">' + escapeAttr(textoTipoTarificacion(tarificacionNombre)) + '</span>' +
                    '</td>' +
                    '<td>' + inputTabla('coberturas', index, 'SumaAsegurada', sumaAsegurada, 2, 'js-suma-asegurada', camposNoConfigurados, true) + '</td>' +
                    '<td>' + inputTabla('coberturas', index, 'PorcentajeSuma', porcentajeSuma, 6, '', camposNoConfigurados) + '</td>' +
                    '<td>' + inputTabla('coberturas', index, 'Tasa', tasa, 6, 'js-tasa', camposNoConfigurados) + '</td>' +
                    '<td>' + inputTabla('coberturas', index, 'DiasProrrata', diasProrrata, 0, 'js-dias-prorrata', camposCalculados) + '</td>' +
                    '<td>' + inputTabla('coberturas', index, 'PrimaAnual', primaAnual, 2, 'js-prima-anual', camposCalculados + ' data-base-prima-anual="' + numeroDecimal(primaAnual, 2) + '"', true) + '</td>' +
                    '<td>' + inputTabla('coberturas', index, 'Prima', prima, 2, 'js-prima', camposNoConfigurados, true) + '</td>' +
                    '</tr>';
                tbody.append(row);
            });

            aplicarFormatoMoneda(tbody);
            recalcularProrrataSumas(true);
        }

        function coberturasDelPlan(planId) {
            const llave = String(planId || '');
            return coberturasPorPlan[llave] || coberturasPorPlan[parseInt(llave, 10)] || [];
        }

        function recalcularProrrataFila(row, forzarPrima = false) {
            const fila = $(row);

            if (fila.find('input[name$="[Cobertura]"]').length === 0) {
                return;
            }

            const dias = diasProrrataExcel();
            const suma = parseCurrency(fila.find('.js-suma-asegurada').val()) || 0;
            const tasa = parseCurrency(fila.find('.js-tasa').val()) || 0;
            const primaAnualInput = fila.find('.js-prima-anual');
            const primaInput = fila.find('.js-prima');
            const tarificacionNombre = fila.find('input[name$="[TarificacionNombre]"]').val() || '';
            const primaAnualBase = parseCurrency(primaAnualInput.data('base-prima-anual')) || 0;
            const primaAnual = calcularPrimaAnualCobertura(suma, tasa, primaAnualBase, tarificacionNombre);
            const primaProrrata = (primaAnual / 365) * dias;

            fila.find('.js-dias-prorrata').val(dias);
            setCurrencyValue(primaAnualInput, primaAnual);

            if (forzarPrima || !primaInput.data('editado-manual')) {
                setCurrencyValue(primaInput, primaProrrata);
            }
        }

        function recalcularProrrataSumas(forzarPrima = false) {
            $('#tabla-sumas-aseguradas tbody tr').each(function() {
                if ($(this).find('.js-suma-asegurada').length) {
                    recalcularProrrataFila(this, forzarPrima);
                }
            });

            calcularTotalesSumas();
        }

        function sincronizarDetalleCobro(totalSuma, totalPrima) {
            const valorAsegurado = $('input[name="ValorAsegurado"]');
            const primaTotal = $('input[name="PrimaTotal"]');

            if (valorAsegurado.length) {
                setCurrencyValue(valorAsegurado, totalSuma);
            }

            if (primaTotal.length) {
                setCurrencyValue(primaTotal, totalPrima);
            }

            calcularDetalleGeneralCobro();
        }

        function calcularDetalleGeneralCobro() {
            const primaTotal = parseCurrency($('input[name="PrimaTotal"]').val()) || 0;
            const porcentajeRentabilidad = parseFloat($('input[name="PorcentajeDescuentoRentabilidad"]').val()) || 0;
            const porcentajeBuenaExperiencia = parseFloat($('input[name="PorcentajeDescuentoBuenaExperiencia"]').val()) || 0;
            const porcentajeOtrosDescuentos = parseFloat($('input[name="PorcentajeOtrosDescuentos"]').val()) || 0;
            const primaExenta = parseCurrency($('input[name="PrimaExenta"]').val()) || 0;
            const gastosEmision = parseCurrency($('input[name="GastosEmision"]').val()) || 0;
            const gastosFraccionamiento = parseCurrency($('input[name="GastosFraccionamiento"]').val()) || 0;
            const gastosBomberos = parseCurrency($('input[name="GastosBomberos"]').val()) || 0;
            const otrosGastos = parseCurrency($('input[name="OtrosGastos"]').val()) || 0;
            const aplicaIva = @json($aplicaIvaProducto);

            const valorDescuentoRentabilidad = primaTotal * (porcentajeRentabilidad / 100);
            const baseBuenaExperiencia = Math.max(primaTotal - valorDescuentoRentabilidad, 0);
            const valorDescuentoBuenaExperiencia = baseBuenaExperiencia * (porcentajeBuenaExperiencia / 100);
            const baseOtrosDescuentos = Math.max(baseBuenaExperiencia - valorDescuentoBuenaExperiencia, 0);
            const valorOtrosDescuentos = baseOtrosDescuentos * (porcentajeOtrosDescuentos / 100);
            const primaNeta = Math.max(baseOtrosDescuentos - valorOtrosDescuentos, 0);
            const baseImponible = primaNeta;
            const impuestos = aplicaIva ? (baseImponible * 0.13) : 0;
            const totalCertificado = primaNeta + gastosEmision + gastosFraccionamiento + gastosBomberos + otrosGastos + impuestos;

            setCurrencyValue('input[name="ValorDescuento"]', valorDescuentoRentabilidad);
            setCurrencyValue('input[name="ValorDescuentoBuenaExperiencia"]', valorDescuentoBuenaExperiencia);
            setCurrencyValue('input[name="ValorOtrosDescuentos"]', valorOtrosDescuentos);
            setCurrencyValue('input[name="PrimaNeta"]', primaNeta);
            setCurrencyValue('input[name="Impuestos"]', impuestos);
            setCurrencyValue('input[name="TotalCertificado"]', totalCertificado);
        }

        function totalBeneficiariosActual() {
            const tabla = $('#tabla-certificado-beneficiarios');

            if (!tabla.length) {
                return 0;
            }

            const totalData = parseFloat(tabla.data('total'));
            return Number.isFinite(totalData) ? totalData : 0;
        }

        function cantidadBeneficiariosActual() {
            return $('#tabla-certificado-beneficiarios .js-beneficiario-porcentaje-activo').length;
        }

        function beneficiariosCuadrados() {
            if (!$('#tabla-certificado-beneficiarios').length) {
                return true;
            }

            if (cantidadBeneficiariosActual() === 0) {
                return true;
            }

            return Math.abs(totalBeneficiariosActual() - 100) < 0.001;
        }

        function pintarCuadreBeneficiarios() {
            const total = totalBeneficiariosActual();
            const cuadrado = beneficiariosCuadrados();
            $('#beneficiarios-total-label').text(total.toFixed(2) + '%');
            $('#beneficiarios-total-footer').text(total.toFixed(2) + '%');
            $('#alerta-beneficiarios-cuadre')
                .toggleClass('alert-success', cuadrado)
                .toggleClass('alert-warning', !cuadrado);
        }

        function validarSubmitBeneficiario(form) {
            const inputPorcentaje = $(form).find('.js-beneficiario-input-porcentaje');
            const actual = parseFloat(inputPorcentaje.data('current')) || 0;
            const nuevo = parseFloat(inputPorcentaje.val()) || 0;
            const total = totalBeneficiariosActual() - actual + nuevo;

            if (total > 100.0001) {
                toastr.error('La suma de porcentajes de beneficiarios no puede superar el 100%.');
                return false;
            }

            return true;
        }

        function calcularTotalesSumas() {
            let totalSuma = 0;
            let totalPrimaAnual = 0;
            let totalPrima = 0;

            $('.js-suma-asegurada').each(function() {
                totalSuma += parseCurrency($(this).val()) || 0;
            });

            $('.js-prima-anual').each(function() {
                totalPrimaAnual += parseCurrency($(this).val()) || 0;
            });

            $('.js-prima').each(function() {
                totalPrima += parseCurrency($(this).val()) || 0;
            });

            $('#TotalSumaAsegurada').text(moneda(totalSuma));
            $('#TotalPrimaAnual').text(moneda(totalPrimaAnual));
            $('#TotalPrima').text(moneda(totalPrima));
            sincronizarDetalleCobro(totalSuma, totalPrima);
        }

        $(function() {
            document.getElementById('VigenciaDesde').addEventListener('change', function() {
                calcularDiasCertificado();
                $('.js-prima').data('editado-manual', false);
                recalcularProrrataSumas(true);
            });
            document.getElementById('VigenciaHasta').addEventListener('change', function() {
                calcularDiasCertificado();
                $('.js-prima').data('editado-manual', false);
                recalcularProrrataSumas(true);
            });
            calcularDiasCertificado();

            if ($.fn.select2) {
                $('.select2').select2({
                    width: '100%'
                });
            }

            if ($.fn.inputmask) {
                $('[data-inputmask]').inputmask();
                $('[data-mask]').inputmask();
            }

            $(document).on('focus', '.currency-field:not([readonly])', function() {
                const input = $(this);
                const decimales = parseInt(input.data('currency-decimals') || 2, 10);
                input.val(valorMonedaPlano(input.val(), decimales));
                this.select();
            });

            $(document).on('blur', '.currency-field', function() {
                aplicarFormatoMoneda(this);
            });

            $('form').on('submit', function() {
                $(this).find('.currency-field').each(function() {
                    const input = $(this);
                    const decimales = parseInt(input.data('currency-decimals') || 2, 10);
                    const valor = input.val();

                    if (valor === '') {
                        return;
                    }

                    input.val(valorMonedaPlano(valor, decimales));
                });
            });

            let tablaCertificadoBeneficiarios = null;
            let tablaCertificadoCesiones = null;

            if ($('#tabla-certificado-beneficiarios').length && $.fn.DataTable) {
                tablaCertificadoBeneficiarios = $('#tabla-certificado-beneficiarios').DataTable({
                    pageLength: 10,
                    scrollX: true,
                    autoWidth: false,
                    initComplete: function() {
                        const tabla = this.api();
                        setTimeout(function() {
                            tabla.columns.adjust().draw(false);
                        }, 120);
                    },
                    columnDefs: [
                        { orderable: false, searchable: false, targets: [-1] }
                    ],
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Sin registros',
                        emptyTable: 'Sin beneficiarios registrados.',
                        zeroRecords: 'No se encontraron beneficiarios',
                        paginate: {
                            first: 'Primero',
                            last: 'Ultimo',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });
            }

            if ($('#tabla-certificado-cesiones').length && $.fn.DataTable) {
                tablaCertificadoCesiones = $('#tabla-certificado-cesiones').DataTable({
                    pageLength: 10,
                    scrollX: true,
                    autoWidth: false,
                    columnDefs: [
                        { orderable: false, searchable: false, targets: [-1] }
                    ],
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Sin registros',
                        emptyTable: 'Sin cesiones registradas.',
                        zeroRecords: 'No se encontraron cesiones',
                        paginate: {
                            first: 'Primero',
                            last: 'Ultimo',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });
            }

            function ajustarTablaCertificado(tabla) {
                if (!tabla) {
                    return;
                }

                setTimeout(function() {
                    tabla.columns.adjust().draw(false);
                }, 80);
            }

            function inicializarTooltips(contexto) {
                if (!$.fn.tooltip) {
                    return;
                }

                const elementos = (contexto ? $(contexto) : $(document)).find('[data-tooltip="tooltip"]');
                elementos.tooltip('destroy');
                elementos.tooltip({
                    container: 'body',
                    trigger: 'hover'
                });
            }

            $('#PlanCertificado').on('change select2:select', function() {
                $('#PlanSumas').val($(this).val());
                renderCoberturasSumas(coberturasDelPlan($(this).val()));
            });

            $(document).on('input', '.js-suma-asegurada, .js-tasa', function() {
                $(this).closest('tr').find('.js-prima').data('editado-manual', false);
                recalcularProrrataSumas(false);
            });

            $(document).on('input', '.js-prima', function() {
                $(this).data('editado-manual', true);
                calcularTotalesSumas();
            });

            $(document).on('input', '.suma-certificado-input:not(.js-suma-asegurada):not(.js-tasa):not(.js-prima)', calcularTotalesSumas);

            $(document).on('input', 'input[name="PrimaTotal"], input[name="PorcentajeDescuentoRentabilidad"], input[name="PorcentajeDescuentoBuenaExperiencia"], input[name="PorcentajeOtrosDescuentos"], input[name="PrimaExenta"], input[name="GastosEmision"], input[name="GastosFraccionamiento"], input[name="GastosBomberos"], input[name="OtrosGastos"]', calcularDetalleGeneralCobro);

            if ($('#tabla-sumas-aseguradas').length) {
                renderCoberturasSumas(coberturasSumasIniciales);
            } else {
                calcularDetalleGeneralCobro();
            }

            aplicarFormatoMoneda(document);
            aplicarMayusculasFormulario('#formCertificado');
            inicializarTooltips(document);
            ajustarTablaCertificado(tablaCertificadoBeneficiarios);
            ajustarTablaCertificado(tablaCertificadoCesiones);

            if (window.location.hash) {
                const tab = $('a[data-toggle="tab"][href="' + window.location.hash + '"]');
                if (tab.length && !tab.closest('li').hasClass('disabled')) {
                    tab.tab('show');
                }
            }

            $('#formCertificado').on('submit', function() {
                $('#btnGuardarCertificado').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
            });

            $('#formSumasAseguradas').on('submit', function() {
                $('#PlanSumas').val($('#PlanCertificado').val());
                $('#btnGuardarSumas').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
            });

            $('#formDatosTecnicosCertificado').on('submit', function() {
                $('#btnGuardarDatosTecnicosCertificado').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
            });

            $('.js-form-beneficiario').on('submit', function(e) {
                if (!validarSubmitBeneficiario(this)) {
                    e.preventDefault();
                    return false;
                }
            });

            $('.cert-tabs a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                if ($(e.target).closest('li').hasClass('disabled')) {
                    e.preventDefault();
                    return false;
                }

                const tabActual = $('.cert-tabs li.active a').attr('href');
                const tabDestino = $(e.target).attr('href');

                if (tabActual === '#beneficiarios' && tabDestino !== '#beneficiarios' && !beneficiariosCuadrados()) {
                    e.preventDefault();
                    toastr.error('debes de asignar el 100% del porcentaje entre los beneficiarios, para poder finalizar este apartado');
                    return false;
                }
            });

            $('.cert-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                const tabDestino = $(e.target).attr('href');

                if (tabDestino === '#beneficiarios') {
                    ajustarTablaCertificado(tablaCertificadoBeneficiarios);
                }

                if (tabDestino === '#cesiones') {
                    ajustarTablaCertificado(tablaCertificadoCesiones);
                }
            });

            $('.modal').on('shown.bs.modal', function() {
                ajustarTablaCertificado(tablaCertificadoBeneficiarios);
                ajustarTablaCertificado(tablaCertificadoCesiones);
                inicializarTooltips(this);
            });

            $(window).on('load', function() {
                ajustarTablaCertificado(tablaCertificadoBeneficiarios);
                ajustarTablaCertificado(tablaCertificadoCesiones);
            });

            pintarCuadreBeneficiarios();
        });

        $(document).on('input', '.campo-validacion-solo-numeros', function() {
            this.value = this.value.replace(/\D+/g, '');
        });

        $(document).on('input', '.campo-validacion-solo-numeros-letras', function() {
            this.value = this.value.replace(/[^A-Za-z0-9]/g, '');
        });

        $(document).on('input', '.campo-validacion-solo-texto', function() {
            this.value = this.value.replace(/[^\p{L}\s\.,#\-\/()&@'":;]/gu, '');
        });

    </script>
@else
    <p class="text-center text-danger">No tiene permiso para ver.</p>
@endcan
@endsection
