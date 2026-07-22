@extends ('welcome')
@section('contenido')
@can('seguro read')
    @php
        $tab = (int) ($tab ?? 1);
        $tab = in_array($tab, [1, 4, 6]) ? $tab : 1;
        $modoRenovacion = old('EsRenovacion') === '1';
        $productoActual = $poliza_seguro->producto;
        $ramoActual = old('Ramo', $productoActual->NecesidadProteccion ?? '');
        $aseguradoraActual = old('Aseguradora', $productoActual->Aseguradora ?? '');
        $productoActualId = old('Productos', $poliza_seguro->Productos);
        $planActualId = old('Planes', $poliza_seguro->Planes);
        $planTitulo = $poliza_seguro->plan->Nombre ?? '';
        $totalCertificados = $poliza_seguro->certificados->count();
        $totalValorAsegurado = (float) $poliza_seguro->certificados->sum('ValorAsegurado');
        $totalPrimaNeta = (float) $poliza_seguro->certificados->sum(function ($certificado) {
            return $certificado->TotalCertificado ?? $certificado->PrimaNeta ?? 0;
        });
        $resumenSumaAsegurada = old('SumaAsegurada', $totalCertificados > 0 ? $totalValorAsegurado : $poliza_seguro->SumaAsegurada);
        $resumenPrimaNetaAnual = old('PrimaNetaAnual', $totalCertificados > 0 ? $totalPrimaNeta : $poliza_seguro->PrimaNetaAnual);
        $vigenciaHastaCarbon = $poliza_seguro->VigenciaHasta ? \Illuminate\Support\Carbon::parse($poliza_seguro->VigenciaHasta) : null;
        $hoy = \Illuminate\Support\Carbon::today();
        $polizaVencida = $vigenciaHastaCarbon ? $vigenciaHastaCarbon->lt($hoy) : false;
        $polizaVenceHoy = $vigenciaHastaCarbon ? $vigenciaHastaCarbon->isSameDay($hoy) : false;
        $productosCatalogo = $productos->map(function ($producto) {
            return [
                'id' => $producto->Id,
                'nombre' => $producto->Nombre,
                'ramo' => $producto->NecesidadProteccion,
                'aseguradora' => $producto->Aseguradora,
                'comision' => $producto->PorcentajeComisionNoDeclarativa,
            ];
        })->values();
        $planesCatalogo = $planes->map(function ($plan) {
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

            return [
                'id' => $plan->Id,
                'nombre' => $plan->Nombre,
                'producto' => $plan->Producto,
                'tarifa_label' => $tarifas->implode(' / '),
            ];
        })->values();
        $aseguradorasCatalogo = $aseguradora->map(function ($item) {
            return [
                'id' => $item->Id,
                'nombre' => $item->Nombre,
            ];
        })->values();
        $ramosCatalogo = $ramos->map(function ($item) {
            return [
                'id' => $item->Id,
                'nombre' => $item->Nombre,
                'comision' => $item->PorcentajeComisionNoDeclarativa,
            ];
        })->values();
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
        .poliza-section { border-top: 1px solid #e5e7eb; padding: 16px 0 4px; }
        .poliza-section:first-child { border-top: 0; }
        .poliza-section h5 { color: #334155; font-weight: 700; margin: 0 0 12px; }
        .poliza-field { margin-bottom: 12px; }
        .poliza-field label { color: #475569; font-size: 12px; font-weight: 700; margin-bottom: 5px; }
        .poliza-actions { border-top: 1px solid #e5e7eb; margin-top: 12px; padding-top: 14px; text-align: right; }
        .btn-inline-group { align-items: center; display: inline-flex; gap: 6px; justify-content: center; }
        .btn-inline-group form { margin: 0; }
        .poliza-renovacion-banner { margin: 12px 0 16px; }
        .certificado-toggle { width: 30px; }
        .certificado-child-wrapper { padding: 8px 10px 10px; background: #f8fafc; overflow-x: auto; }
        .certificado-child-wrapper h5 { margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #334155; }
        .certificado-child-table { margin-bottom: 0; width: 100%; }
        .certificado-child-table th { background: #eef2f7; color: #475569; font-size: 12px; white-space: nowrap; }
        .certificado-child-table td { font-size: 12px; white-space: nowrap; }
        #tabla-certificados,
        #tabla-certificados_wrapper { font-size: 12px; }
        #tabla-certificados tfoot th { background: #f8fafc; font-weight: 700; white-space: nowrap; }
        .cert-money-cell { text-align: right; }
        .certificado-owner-row td { white-space: nowrap; }
        .certificado-search-text { display: none; }
        .modal-dependientes .modal-dialog { width: 94%; max-width: 1180px; }
        .dependiente-edit-row { background: #f8fafc; }
        .dependiente-edit-box { border: 1px solid #e5e7eb; padding: 12px; }
    </style>

    <div class="x_panel">
        <div class="x_title">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <h4>Poliza {{ $poliza_seguro->NumeroPoliza }}{{ $planTitulo ? ' - ' . $planTitulo : '' }}</h4>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12" align="right">
                <a href="{{ url('poliza/seguro') }}" class="btn btn-info">
                    <i class="fa fa-arrow-left"></i> Atras
                </a>
            </div>
            <div class="clearfix"></div>
        </div>

        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
            <li class="{{ $tab == 1 ? 'active' : '' }}"><a href="#home" data-toggle="tab">Poliza</a></li>
            <li class="{{ $tab == 6 ? 'active' : '' }}"><a href="#renovaciones" data-toggle="tab">Renovaciones</a></li>
            <li class="{{ $tab == 4 ? 'active' : '' }}"><a href="#certificados" data-toggle="tab">Certificados</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade {{ $tab == 1 ? 'active in' : '' }}" id="home">
                <form id="formPolizaSeguroEdit" action="{{ url('poliza/seguro/save') }}/{{ $poliza_seguro->Id }}" method="post">
                    @csrf
                    <input type="hidden" name="Oferta" id="OfertaHidden" value="{{ $poliza_seguro->Oferta }}">
                    <input type="hidden" name="Cliente" id="ClienteHidden" value="{{ old('Cliente', $poliza_seguro->Cliente) }}">
                    <input type="hidden" name="NumeroPoliza" id="NumeroPolizaHidden" value="{{ old('NumeroPoliza', $poliza_seguro->NumeroPoliza) }}">
                    <input type="hidden" name="Aseguradora" id="AseguradoraHidden" value="{{ old('Aseguradora', $aseguradoraActual) }}">
                    <input type="hidden" name="Ramo" id="RamoHidden" value="{{ old('Ramo', $ramoActual) }}">
                    <input type="hidden" name="Productos" id="ProductosHidden" value="{{ old('Productos', $productoActualId) }}">
                    <input type="hidden" name="Planes" id="Planes" value="{{ $planActualId }}">
                    <input type="hidden" name="EsRenovacion" id="EsRenovacion" value="{{ $modoRenovacion ? '1' : '0' }}">

                    @if ($polizaVencida)
                        <div class="alert alert-danger">
                            La poliza ya esta vencida. Su vigencia termino el {{ $vigenciaHastaCarbon->format('d/m/Y') }}.
                        </div>
                    @elseif ($polizaVenceHoy)
                        <div class="alert alert-warning">
                            La poliza vence hoy: {{ $vigenciaHastaCarbon->format('d/m/Y') }}.
                        </div>
                    @endif

                    <div id="renovacion-mode-alert" class="alert alert-info poliza-renovacion-banner" style="{{ $modoRenovacion ? '' : 'display:none;' }}">
                        Modo renovacion activo. Los cambios que guarde quedaran registrados en el historial de renovaciones.
                    </div>

                    <div class="poliza-section">
                        <h5>Origen de la poliza</h5>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label># Oferta aceptada</label>
                                <select id="Oferta" class="form-control select2" style="width:100%;" disabled>
                                    <option value="">Sin oferta aceptada...</option>
                                    @foreach ($ofertas as $off)
                                        @php
                                            $cotizacionAceptada = $off->cotizaciones->first();
                                        @endphp
                                        <option value="{{ $off->Id }}" {{ old('Oferta', $poliza_seguro->Oferta) == $off->Id ? 'selected' : '' }}>
                                            #{{ $off->Id }} - {{ $off->clientes->Nombre ?? 'Cliente no definido' }}
                                            @if ($cotizacionAceptada)
                                                / {{ $cotizacionAceptada->planes->productos->Nombre ?? 'Producto N/A' }}
                                                - {{ $cotizacionAceptada->planes->Nombre ?? 'Plan N/A' }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Num. Vigencia</label>
                                <input type="number" name="NumeroVigencia" id="NumeroVigencia" class="form-control" min="1" value="{{ old('NumeroVigencia', $poliza_seguro->NumeroVigencia ?? 1) }}">
                            </div>

                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Numero poliza *</label>
                                <input type="text" id="NumeroPoliza" class="form-control" required value="{{ old('NumeroPoliza', $poliza_seguro->NumeroPoliza) }}" disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Estado de poliza *</label>
                                <select name="EstadoPoliza" id="EstadoPoliza" class="form-control select2" style="width:100%;" required>
                                    @foreach ($estado_poliza as $estado)
                                        <option value="{{ $estado->Id }}" {{ old('EstadoPoliza', $poliza_seguro->EstadoPoliza) == $estado->Id ? 'selected' : '' }}>{{ $estado->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 poliza-field">
                                <label>Nombre cliente *</label>
                                <select id="Cliente" class="form-control select2" style="width:100%;" {{ $modoRenovacion ? '' : 'disabled' }}>
                                    <option value="">Seleccione...</option>
                                    @foreach ($clientes as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('Cliente', $poliza_seguro->Cliente) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-5 poliza-field">
                                <label>Numero documento *</label>
                                <input type="text" name="NumeroDocumento" id="NumeroDocumento" class="form-control" readonly value="{{ old('NumeroDocumento', $poliza_seguro->clientes->Dui ?? $poliza_seguro->clientes->Nit ?? '') }}">
                            </div>
                            <div class="col-md-3 poliza-field">
                                <label>Fecha Vinculacion</label>
                                <input type="date" name="FechaVinculacion" class="form-control" value="{{ $poliza_seguro->FechaVinculacion }}">
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-6 poliza-field">
                                    <label>Vigencia Desde *</label>
                                    <input class="form-control" id="vigencia_desde" name="VigenciaDesde" type="date" value="{{ $poliza_seguro->VigenciaDesde }}" required>
                                </div>
                                <div class="col-md-2 col-sm-6 poliza-field">
                                    <label>Vigencia Hasta *</label>
                                    <input class="form-control" id="vigencia_hasta" name="VigenciaHasta" type="date" value="{{ $poliza_seguro->VigenciaHasta }}" required>
                                </div>
                                <div class="col-md-1 col-sm-6 poliza-field">
                                    <label>Dias</label>
                                    <input type="number" name="DiasVigencia" id="dias_vigencia" class="form-control" readonly value="{{ $poliza_seguro->DiasVigencia }}">
                                </div>
                                <div class="col-md-4 col-sm-6 poliza-field">
                                    <label>Forma de pago *</label>
                                    <select name="FormaPago" id="FormaPago" class="form-control select2" style="width:100%;" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($forma_pago as $pago)
                                            <option value="{{ $pago->Id }}" {{ old('FormaPago', $poliza_seguro->FormaPago) == $pago->Id ? 'selected' : '' }}>{{ $pago->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="NumCuotas" id="NumCuotas" value="{{ old('NumCuotas', $poliza_seguro->NumCuotas) }}">
                                <div class="col-md-2 col-sm-6 poliza-field">
                                    <label>% Comision NR</label>
                                    <input type="number" step="0.0001" name="PorcentajeComisionNR" id="PorcentajeComisionNR" class="form-control" value="{{ old('PorcentajeComisionNR', $poliza_seguro->PorcentajeComisionNR) }}">
                                </div>
                            </div>
{{--                        DIV DONDE SE PONDRA EL RAMO, ASEGURADORA, PRODUCTO Y PLAN--}}
                        <div class="row">
                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Aseguradora *</label>
                                <select id="Aseguradora" class="form-control select2" style="width:100%;" required {{ $modoRenovacion ? '' : 'disabled' }}>
                                    <option value="">Seleccione...</option>
                                    @foreach ($aseguradora as $obj)
                                        <option value="{{ $obj->Id }}" {{ $obj->Id == $aseguradoraActual ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Ramo *</label>
                                <select id="Ramo" class="form-control select2" style="width:100%;" required {{ $modoRenovacion ? '' : 'disabled' }}>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Productos *</label>
                                <select id="Productos" class="form-control select2" style="width:100%;" required {{ $modoRenovacion ? '' : 'disabled' }}>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Planes *</label>
                                <select id="PlanesVisible" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>

                        </div>
                        {{--                        DIV PARA MOSTRAR EL ORIGEN DE POLIZA--}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Origen Poliza</label>
                                <select name="OrigenPoliza" id="OrigenPoliza" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($origen_poliza as $obj)
                                        <option value="{{ $obj->Id }}" {{ $obj->Id == $poliza_seguro->OrigenPoliza ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 poliza-field">
                                <label>Sustituye póliza</label>
                                {{--                                cambiaremos este input por text para que se ingresen datos--}}
                                <input type="text" name="SustituidaPoliza" class="form-control" value="{{ old('SustituidaPoliza', $poliza_seguro->SustituidaPoliza) }}">
                            </div>
                        </div>

{{--                        div para mostrar los resumenes de la prima, la tasa y las sumas--}}
                    <div class="row">
                        <div class="col-md-3 col-sm-6 poliza-field">
{{--                            Este input mostrará la sumatoria de las sumas aseguradas del certificado.--}}
                            <label>Suma Asegurada</label>
                            <input id="SumaAsegurada" name="SumaAsegurada" type="hidden" value="{{ $resumenSumaAsegurada }}">
                            <input class="form-control" id="SumaAseguradaDisplay" type="text" value="{{ is_numeric($resumenSumaAsegurada) ? '$' . number_format((float) $resumenSumaAsegurada, 2) : '' }}" readonly>
                        </div>
                        <div class="col-md-3 col-sm-6 poliza-field">
{{--                            Este input la tarifa que tenga el plan--}}
                            <label>Tarifa</label>
                            <input class="form-control" id="TarifaPlan" type="text" value="" readonly>
                        </div>
                        <div class="col-md-3 col-sm-6 poliza-field">
{{--                            Este mostrará el total de las sumas de las primas que se tengan en el certificado --}}
                            <label>Prima Neta Anual</label>
                            <input id="PrimaNetaAnual" name="PrimaNetaAnual" type="hidden" value="{{ $resumenPrimaNetaAnual }}">
                            <input class="form-control" id="PrimaNetaAnualDisplay" type="text" value="{{ is_numeric($resumenPrimaNetaAnual) ? '$' . number_format((float) $resumenPrimaNetaAnual, 2) : '' }}" readonly>
                        </div>
                        <div class="col-md-3 col-sm-6 poliza-field">
{{--                            Este mostrará el total de las sumas de las primas que se tengan en el certificado --}}
                            <label>Iva incluido</label>
                            <input type="hidden" name="IvaIncluido" id="IvaIncluido" value="{{ old('IvaIncluido', $poliza_seguro->IvaIncluido ?: 'N') }}">
                            <div style="padding-top:6px;">
                                <input type="checkbox" id="IvaIncluidoSwitch" class="js-switch" {{ old('IvaIncluido', $poliza_seguro->IvaIncluido ?: 'N') === 'S' ? 'checked' : '' }}>
                                <span id="IvaIncluidoLabel" style="margin-left:8px;">{{ old('IvaIncluido', $poliza_seguro->IvaIncluido ?: 'N') === 'S' ? 'Si' : 'No' }}</span>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 poliza-field">
                                {{--El valor que se tenga aca en porcentaje, lo utilizaremos en los certificados
                                 para  tomarlo como descuento de rentabildad--}}
                                <label>% de Descuento de rentabilidad</label>
                                <input class="form-control" id="PorcentajeDescuentoRentabilidad" name="PorcentajeDescuentoRentabilidad" type="number" step="0.0001" value="{{ old('PorcentajeDescuentoRentabilidad', $poliza_seguro->PorcentajeDescuentoRentabilidad) }}">
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                {{-- El porcentaje que se tendra aca, se utilizara en los certificados en el descuento de buena experiencia  --}}
                                <label>% Descuento de buena experiencia</label>
                                <input class="form-control" id="PorcentajeDescuentoBuenaExperiencia" name="PorcentajeDescuentoBuenaExperiencia" type="number" step="0.0001" value="{{ old('PorcentajeDescuentoBuenaExperiencia', $poliza_seguro->PorcentajeDescuentoBuenaExperiencia) }}">
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                {{-- El porcentaje que se tendra aca, se utilizara en los certificados en el descuento de buena experiencia  --}}
                                <label>% Otros descuentos</label>
                                <input class="form-control" id="PorcentajeOtrosDescuentos" name="PorcentajeOtrosDescuentos" type="number" step="0.0001" value="{{ old('PorcentajeOtrosDescuentos', $poliza_seguro->PorcentajeOtrosDescuentos) }}">
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                {{-- El porcentaje que se tendra aca, se utilizara en los certificados en el descuento de buena experiencia  --}}
                                <label>% Comisión Cliente</label>
                                <input class="form-control" id="PorcentajeComsionCliente" name="PorcentajeComsionCliente" type="number" step="0.0001" value="{{ old('PorcentajeComsionCliente', $poliza_seguro->PorcentajeComsionCliente) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 poliza-field">
                                {{-- Crear este nuevo campo de text  --}}
                                <label>Clausulas Especiales</label>
                                <textarea class="form-control" id="ClausulasEspeciales"
                                          name="ClausulasEspeciales" rows="4">{{ old('ClausulasEspeciales', $poliza_seguro->ClausulasEspeciales) }}</textarea>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                {{-- Nuevo campo de Text --}}
                                <label>Beneficios Adicionales</label>
                                <textarea class="form-control" id="BeneficiosAdicionales"
                                          name="BeneficiosAdicionales" rows="4">{{ old('BeneficiosAdicionales', $poliza_seguro->BeneficiosAdicionales) }}</textarea>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                {{-- Nuevo campo de Text --}}
                                <label>Comentarios</label>
                                <textarea class="form-control" id="Comentarios"
                                          name="Comentarios" rows="4">{{ old('Comentarios', $poliza_seguro->Comentarios) }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Tipo de Cartera</label>
{{--                                obtener los datos de la tabla tipo_cartera_nr, y cambiarlos por los departamentos revisar si ya estan los endpoints.--}}
                                <select name="TipoCarteraNR" id="TipoCarteraNR" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipo_cartera_nr as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('TipoCarteraNR', $poliza_seguro->TipoCarteraNR) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Ejecutivo que atendera</label>
                                <select name="EjecutivoCia" id="EjecutivoCia" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($ejecutivos as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('EjecutivoCia', $poliza_seguro->EjecutivoCia) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field" style="display:none!important;">
                                <label>Tipo de deducible</label>
                                <select name="Deducible" id="Deducible" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipo_deducible as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('Deducible', $poliza_seguro->Deducible) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        </div>


                    <div class="poliza-section">
                        <h5>Cancelación Poliza</h5>
                        <div class="row">
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Fecha Cancelacion</label>
                                <input type="date" name="FechaCancelacion" class="form-control" value="{{ $poliza_seguro->FechaCancelacion }}">
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Motivos de cancelacion</label>
                                <select name="CodCancelacion" id="CodCancelacion" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($motivos_cancelacion as $obj)
                                        <option value="{{ $obj->Id }}" {{ $obj->Id == $poliza_seguro->CodCancelacion ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-7 col-sm-6 poliza-field">
                                <label>Observaciones Cancelación</label>
                                <input type="text" name="MotivoCancelacion" id="MotivoCancelacion" class="form-control" value="{{ $poliza_seguro->MotivoCancelacion }}">
                            </div>
                        </div>
                    </div>

                    <div class="poliza-actions">
                        <button id="btnCancelarRenovacion" type="button" class="btn btn-default" style="{{ $modoRenovacion ? '' : 'display:none;' }}">
                            <i class="fa fa-undo"></i> Cancelar renovacion
                        </button>
                        <a href="{{ url('poliza/seguro') }}" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</a>
                        <button id="btnGuardarPoliza" class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{ $modoRenovacion ? 'Guardar renovacion' : 'Guardar' }}</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade {{ $tab == 6 ? 'active in' : '' }}" id="renovaciones">
                <div class="poliza-section">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <h5>Historico de renovaciones</h5>
                            <p class="text-muted" style="margin-bottom:0;">
                                Cada renovacion guarda la vigencia, el numero de vigencia, la tarifa del plan y la bitacora de cambios aplicados.
                            </p>
                        </div>
                        <div class="col-md-4 col-sm-12 text-right">
                            <button type="button" class="btn btn-primary" id="btnActivarRenovacion">
                                <i class="fa fa-refresh"></i> Iniciar renovacion
                            </button>
                        </div>
                    </div>
                </div>

                <table id="tabla-renovaciones" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Vigencia Desde</th>
                            <th>Vigencia Hasta</th>
                            <th>Num. Vigencia</th>
                            <th>Tarifa</th>
                            <th>Suma Asegurada</th>
                            <th>Prima Neta Anual</th>
                            <th>Registrado por</th>
                            <th>Fecha registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($renovaciones_historial as $renovacion)
                            @php
                                $usuarioRenovacion = optional($renovacion->usuario)->name ?? optional($renovacion->usuario)->email ?? '';
                                $estadoRenovacion = optional($renovacion->estadoPolizaRelacion)->Nombre ?? '';
                                $fechaRegistroRenovacion = $renovacion->FechaRegistro ? \Illuminate\Support\Carbon::parse($renovacion->FechaRegistro)->format('d/m/Y H:i') : '';
                                $cambiosRenovacion = json_decode($renovacion->CambiosJson ?? '[]', true);
                                $cambiosRenovacion = is_array($cambiosRenovacion) ? $cambiosRenovacion : [];
                            @endphp
                            <tr>
                                <td>{{ $renovacion->TipoRenovacion }}</td>
                                <td>{{ $estadoRenovacion }}</td>
                                <td>{{ $renovacion->VigenciaDesde }}</td>
                                <td>{{ $renovacion->VigenciaHasta }}</td>
                                <td>{{ $renovacion->NumeroVigencia }}</td>
                                <td>{{ $renovacion->TarifaPlan }}</td>
                                <td>{{ is_numeric($renovacion->SumaAsegurada ?? null) ? '$' . number_format((float) ($renovacion->SumaAsegurada ?? 0), 2) : '-' }}</td>
                                <td>{{ is_numeric($renovacion->PrimaNetaAnual ?? null) ? '$' . number_format((float) ($renovacion->PrimaNetaAnual ?? 0), 2) : '-' }}</td>
                                <td>{{ $usuarioRenovacion }}</td>
                                <td>{{ $fechaRegistroRenovacion }}</td>
                                <td class="text-center">
                                    @if ($renovacion->Id)
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-renovacion-{{ $renovacion->Id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade {{ $tab == 4 ? 'active in' : '' }}" id="certificados">
                <div style="text-align: right">
                    <a class="btn btn-default" href="{{ url('poliza/seguro/' . $poliza_seguro->Id . '/export_certificados') }}">
                        <i class="fa fa-download"></i> Descargar certificados
                    </a>
                    <a class="btn btn-primary" href="{{ url('poliza/seguro/' . $poliza_seguro->Id . '/certificado/create') }}">
                        <i class="fa fa-plus"></i> Agregar certificado
                    </a>
                </div>
                @if ($certificado_campos->count() == 0)
                    <br><div class="alert alert-info">Los detalles del asegurado se completaran dentro del certificado.</div>
                @endif
                <br>
                @php
                    $totalCertificados = $poliza_seguro->certificados->count();
                    $totalValorAsegurado = $poliza_seguro->certificados->sum('ValorAsegurado');
                    $totalPrimaNeta = $poliza_seguro->certificados->sum(function ($certificado) {
                        return $certificado->TotalCertificado ?? $certificado->PrimaNeta ?? 0;
                    });
                @endphp
                <table id="tabla-certificados" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th># Certificado interno.</th>
                            <th># Certificado CIA.</th>
                            <th>Asegurado /Ubicaciones Aseguradas</th>
                            <th>Vigencia Desde</th>
                            <th>Vigencia Hasta</th>
                            <th>Suma Asegurada</th>
                            <th>Prima Neta</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poliza_seguro->certificados as $certificado)
                            @php
                                $primaNetaSnapshot = $certificado->TotalCertificado ?? $certificado->PrimaNeta;
                                $cantidadDependientes = $certificado->dependientes->count();
                                $textoDependientes = $certificado->dependientes->map(function ($dependiente) {
                                    $datos = json_decode($dependiente->DatosJson ?: '[]', true);
                                    $datos = is_array($datos) ? $datos : [];

                                    return collect($datos)->pluck('Valor')->filter()->implode(' ');
                                })->filter()->implode(' ');
                            @endphp
                            <tr class="certificado-owner-row" data-certificado="{{ $certificado->Id }}">
                                <td data-order="{{ (int) $certificado->NumeroCertificado }}">
                                    <div class="btn-inline-group" style="justify-content:flex-start;">
                                        <button type="button"
                                            class="btn btn-default btn-xs certificado-toggle {{ $cantidadDependientes > 0 ? 'js-toggle-cert-dependientes' : '' }}"
                                            data-certificado="{{ $certificado->Id }}"
                                            title="{{ $cantidadDependientes > 0 ? 'Ver dependientes' : 'Sin dependientes' }}"
                                            {{ $cantidadDependientes === 0 ? 'disabled' : '' }}>
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <span>{{ $certificado->NumeroCertificado }}</span>
                                        @if ($textoDependientes !== '')
                                            <span class="certificado-search-text">{{ $textoDependientes }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $certificado->CertificadoAseguradora }}</td>
                                <td>{{ $certificado->Asegurado }}</td>
                                <td>{{ $certificado->VigenciaDesde }}</td>
                                <td>{{ $certificado->VigenciaHasta }}</td>
                                <td class="cert-money-cell">{{ $certificado->ValorAsegurado !== null ? '$' . number_format($certificado->ValorAsegurado, 2, '.', ',') : '' }}</td>
                                <td class="cert-money-cell">{{ $primaNetaSnapshot !== null ? '$' . number_format($primaNetaSnapshot, 2, '.', ',') : '' }}</td>
                                <td>{{ $certificado->Estado }}</td>
                                <td style="text-align:center;">
                                    <div class="btn-inline-group">
                                        <a class="btn btn-warning btn-sm" href="{{ url('poliza/seguro/certificado/' . $certificado->Id . '/edit') }}">
                                            <i class="fa fa-pencil"></i> Editar
                                        </a>
                                        <form method="POST" action="{{ url('poliza/seguro/certificado_delete') }}/{{ $certificado->Id }}">
                                            @csrf
                                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Confirme si desea eliminar este certificado.');">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Totales</th>
                            <th class="cert-money-cell">${{ number_format($totalValorAsegurado, 2, '.', ',') }}</th>
                            <th class="cert-money-cell">${{ number_format($totalPrimaNeta, 2, '.', ',') }}</th>
                            <th>{{ number_format($totalCertificados, 0, '.', ',') }} certificados</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                <div id="certificado-dependientes-templates" style="display:none;">
                    @foreach ($poliza_seguro->certificados as $certificado)
                        @if ($certificado->dependientes->count() > 0)
                            @php
                                $camposDependientes = $certificado->dependientes->flatMap(function ($dependiente) {
                                    $datos = json_decode($dependiente->DatosJson ?: '[]', true);
                                    $datos = is_array($datos) ? $datos : [];

                                    return collect($datos)->map(function ($campo) {
                                        $id = (string) ($campo['CampoId'] ?? $campo['NombreCampo'] ?? $campo['Etiqueta'] ?? '');

                                        return [
                                            'id' => $id,
                                            'etiqueta' => $campo['Etiqueta'] ?? $campo['NombreCampo'] ?? 'Campo',
                                        ];
                                    })->filter(function ($campo) {
                                        return $campo['id'] !== '';
                                    });
                                })->unique('id')->values();
                            @endphp
                            <div id="certificado-dependientes-{{ $certificado->Id }}">
                                <div class="certificado-child-wrapper">
                                    <h5>Dependientes del certificado {{ $certificado->NumeroCertificado }}</h5>
                                    <table class="table table-bordered certificado-child-table">
                                        <thead>
                                            <tr>
                                                <th># Dependiente</th>
                                                @foreach ($camposDependientes as $campoDependiente)
                                                    <th>{{ $campoDependiente['etiqueta'] }}</th>
                                                @endforeach
                                                <th>Observacion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($certificado->dependientes as $dependiente)
                                                @php
                                                    $datosDependiente = json_decode($dependiente->DatosJson ?: '[]', true);
                                                    $datosDependiente = is_array($datosDependiente) ? $datosDependiente : [];
                                                    $valoresDependiente = collect($datosDependiente)->mapWithKeys(function ($campo) {
                                                        $id = (string) ($campo['CampoId'] ?? $campo['NombreCampo'] ?? $campo['Etiqueta'] ?? '');

                                                        return [$id => $campo['Valor'] ?? ''];
                                                    })->all();
                                                @endphp
                                                <tr>
                                                    <td>{{ $dependiente->NumeroDependiente }}</td>
                                                    @foreach ($camposDependientes as $campoDependiente)
                                                        <td>{{ $valoresDependiente[$campoDependiente['id']] ?? '' }}</td>
                                                    @endforeach
                                                    <td>{{ $dependiente->Observacion }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    @foreach ($renovaciones_historial as $renovacion)
        @if ($renovacion->Id)
            @php
                $cambiosRenovacion = json_decode($renovacion->CambiosJson ?? '[]', true);
                $cambiosRenovacion = is_array($cambiosRenovacion) ? $cambiosRenovacion : [];
                $usuarioRenovacionModal = optional($renovacion->usuario)->name ?? optional($renovacion->usuario)->email ?? 'No definido';
                $fechaRegistroRenovacionModal = $renovacion->FechaRegistro
                    ? \Illuminate\Support\Carbon::parse($renovacion->FechaRegistro)->format('d/m/Y H:i')
                    : 'No definida';
            @endphp
            <div class="modal fade" id="modal-renovacion-{{ $renovacion->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Cambios de la renovacion</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin-bottom:12px;">
                                <div class="col-md-3"><strong>Tipo:</strong> {{ $renovacion->TipoRenovacion }}</div>
                                <div class="col-md-3"><strong>Num. Vigencia:</strong> {{ $renovacion->NumeroVigencia }}</div>
                                <div class="col-md-3"><strong>Desde:</strong> {{ $renovacion->VigenciaDesde }}</div>
                                <div class="col-md-3"><strong>Hasta:</strong> {{ $renovacion->VigenciaHasta }}</div>
                            </div>
                            <div class="row" style="margin-bottom:12px;">
                                <div class="col-md-3"><strong>Suma asegurada:</strong> {{ is_numeric($renovacion->SumaAsegurada ?? null) ? '$' . number_format((float) ($renovacion->SumaAsegurada ?? 0), 2) : '-' }}</div>
                                <div class="col-md-3"><strong>Prima neta anual:</strong> {{ is_numeric($renovacion->PrimaNetaAnual ?? null) ? '$' . number_format((float) ($renovacion->PrimaNetaAnual ?? 0), 2) : '-' }}</div>
                                <div class="col-md-3"><strong>Usuario:</strong> {{ $usuarioRenovacionModal }}</div>
                                <div class="col-md-3"><strong>Fecha y hora:</strong> {{ $fechaRegistroRenovacionModal }}</div>
                            </div>

                            @if (count($cambiosRenovacion) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" style="margin-bottom:0;">
                                        <thead>
                                            <tr>
                                                <th>Campo</th>
                                                <th>Valor anterior</th>
                                                <th>Valor nuevo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cambiosRenovacion as $cambio)
                                                <tr>
                                                    <td>{{ $cambio['campo'] ?? '' }}</td>
                                                    <td>{{ $cambio['anterior'] ?? '-' }}</td>
                                                    <td>{{ $cambio['nuevo'] ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info" style="margin-bottom:0;">
                                    Este registro corresponde al estado inicial de la poliza y no tiene bitacora de cambios.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <script>
        const desdeInput = document.getElementById('vigencia_desde');
        const hastaInput = document.getElementById('vigencia_hasta');
        const diasInput = document.getElementById('dias_vigencia');
        const productosCatalogo = @json($productosCatalogo);
        const planesCatalogo = @json($planesCatalogo);
        const aseguradorasCatalogo = @json($aseguradorasCatalogo);
        const ramosCatalogo = @json($ramosCatalogo);
        const aseguradoraActualId = "{{ $aseguradoraActual }}";
        const ramoActualId = "{{ old('Ramo', $ramoActual) }}";
        const clienteActualId = "{{ old('Cliente', $poliza_seguro->Cliente) }}";
        const productoActualId = "{{ $productoActualId }}";
        const planActualId = "{{ $planActualId }}";
        const numeroVigenciaActual = parseInt("{{ old('NumeroVigencia', $poliza_seguro->NumeroVigencia ?? 1) }}", 10) || 1;
        const modoRenovacionInicial = {{ $modoRenovacion ? 'true' : 'false' }};
        const cancelacionRenovacionUrl = "{{ url('poliza/seguro/' . $poliza_seguro->Id . '/edit?tab=6') }}";

        function reiniciarSelect(selector, texto) {
            const select = $(selector);
            select.empty();
            select.append(new Option(texto, '', true, false));
            return select;
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

        function sincronizarCamposOcultos() {
            $('#ClienteHidden').val($('#Cliente').val() || '');
            $('#AseguradoraHidden').val($('#Aseguradora').val() || '');
            $('#RamoHidden').val($('#Ramo').val() || '');
            $('#ProductosHidden').val($('#Productos').val() || '');
            $('#NumeroPolizaHidden').val($('#NumeroPoliza').val() || '');
        }

        function actualizarTextoBotonGuardar() {
            const modo = $('#EsRenovacion').val() === '1';
            $('#btnGuardarPoliza').html(modo
                ? '<i class="fa fa-save"></i> Guardar renovacion'
                : '<i class="fa fa-save"></i> Guardar');
        }

        function actualizarEstadoModoRenovacion(modo) {
            $('#EsRenovacion').val(modo ? '1' : '0');
            $('#renovacion-mode-alert').toggle(modo);
            $('#btnCancelarRenovacion').toggle(modo);
            $('#Cliente').prop('disabled', !modo).trigger('change.select2');
            $('#Aseguradora').prop('disabled', !modo).trigger('change.select2');
            $('#Ramo').prop('disabled', !modo).trigger('change.select2');
            $('#Productos').prop('disabled', !modo).trigger('change.select2');
            actualizarTextoBotonGuardar();
            sincronizarCamposOcultos();
        }

        function activarModoRenovacion() {
            if ($('#NumeroVigencia').val() === String(numeroVigenciaActual)) {
                $('#NumeroVigencia').val(numeroVigenciaActual + 1);
            }

            actualizarEstadoModoRenovacion(true);
            $('a[data-toggle="tab"][href="#home"]').tab('show');
        }

        function cargarComisionRamo() {
            const option = $('#Ramo option:selected');
            const comision = option.data('comision');
            $('#PorcentajeComisionNR').val(comision || '');
        }

        function cargarComisionProducto() {
            const productoId = $('#Productos').val();
            const producto = productosCatalogo.find(function(item) {
                return String(item.id) === String(productoId);
            });

            if (producto && producto.comision !== null && producto.comision !== '') {
                $('#PorcentajeComisionNR').val(producto.comision);
            } else {
                cargarComisionRamo();
            }
        }

        function poblarRamos(ramoSeleccionado = '') {
            const aseguradora = $('#Aseguradora').val();
            const select = reiniciarSelect('#Ramo', 'Seleccione...');
            const ramosPermitidos = [];

            productosCatalogo
                .filter(function(producto) {
                    return aseguradora && String(producto.aseguradora) === String(aseguradora);
                })
                .forEach(function(producto) {
                    const ramoId = String(producto.ramo);
                    if (ramoId && ramosPermitidos.indexOf(ramoId) === -1) {
                        ramosPermitidos.push(ramoId);
                    }
                });

            ramosCatalogo
                .filter(function(ramo) {
                    return ramosPermitidos.indexOf(String(ramo.id)) !== -1;
                })
                .forEach(function(ramo) {
                    const option = new Option(ramo.nombre, ramo.id, false, String(ramo.id) === String(ramoSeleccionado));
                    $(option).attr('data-comision', ramo.comision ?? '');
                    select.append(option);
                });

            if (ramoSeleccionado && ramosPermitidos.indexOf(String(ramoSeleccionado)) !== -1) {
                select.val(String(ramoSeleccionado));
            } else {
                select.val('');
            }

            select.prop('disabled', !aseguradora || !($('#EsRenovacion').val() === '1')).trigger('change.select2');
        }

        function poblarProductos(productoSeleccionado = '', planSeleccionado = '') {
            const ramo = $('#Ramo').val();
            const aseguradora = $('#Aseguradora').val();
            const select = reiniciarSelect('#Productos', 'Seleccione...');

            limpiarPlan();

            if (!ramo || !aseguradora) {
                select.val('').trigger('change.select2');
                sincronizarCamposOcultos();
                return;
            }

            productosCatalogo
                .filter(function(producto) {
                    return String(producto.ramo) === String(ramo) && String(producto.aseguradora) === String(aseguradora);
                })
                .forEach(function(producto) {
                    select.append(new Option(producto.nombre, producto.id, false, String(producto.id) === String(productoSeleccionado)));
                });

            select.val(productoSeleccionado || '').trigger('change.select2');
            poblarPlanes(productoSeleccionado || $('#Productos').val(), planSeleccionado);
            sincronizarCamposOcultos();
        }

        function poblarPlanes(productoId = '', planSeleccionado = '') {
            const select = reiniciarSelect('#PlanesVisible', 'Seleccione...');
            const planesProducto = planesCatalogo.filter(function(plan) {
                return productoId && String(plan.producto) === String(productoId);
            });
            const plan = planesProducto.find(function(item) {
                return String(item.id) === String(planSeleccionado);
            }) || (!planSeleccionado ? planesProducto[0] : null);

            planesProducto.forEach(function(item) {
                select.append(new Option(item.nombre, item.id, false, plan && String(item.id) === String(plan.id)));
            });

            if (plan) {
                $('#Planes').val(plan.id);
                select.val(plan.id).trigger('change.select2');
            } else {
                limpiarPlan();
                select.val('').trigger('change.select2');
            }

            actualizarTarifaPlan();
        }

        function limpiarPlan() {
            $('#Planes').val('');
            $('#PlanesVisible').val('').trigger('change.select2');
            actualizarTarifaPlan();
        }

        function actualizarTarifaPlan() {
            const planId = $('#Planes').val();
            const plan = planesCatalogo.find(function(item) {
                return String(item.id) === String(planId);
            });

            $('#TarifaPlan').val(plan && plan.tarifa_label ? plan.tarifa_label : '');
        }

        function normalizarNumeroMoneda(valor) {
            if (valor === null || valor === undefined || valor === '') {
                return '';
            }

            const texto = String(valor).replace(/[^0-9.-]/g, '');
            const numero = parseFloat(texto);
            return isNaN(numero) ? '' : numero;
        }

        function formatoMonedaUsd(valor) {
            const numero = normalizarNumeroMoneda(valor);

            if (numero === '') {
                return '';
            }

            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(numero);
        }

        function sincronizarMonedaReadonly(inputId, displayId) {
            $('#' + displayId).val(formatoMonedaUsd($('#' + inputId).val()));
        }

        function calcularDias() {
            const desde = new Date(desdeInput.value);
            const hasta = new Date(hastaInput.value);

            if (!isNaN(desde.getTime()) && !isNaN(hasta.getTime())) {
                const diferencia = Math.ceil((hasta - desde) / (1000 * 60 * 60 * 24));
                diasInput.value = diferencia >= 0 ? diferencia : 0;
            } else {
                diasInput.value = '';
            }
        }

        desdeInput.addEventListener('change', calcularDias);
        hastaInput.addEventListener('change', calcularDias);

        $('#btnActivarRenovacion').on('click', activarModoRenovacion);
        $('#btnCancelarRenovacion').on('click', function() {
            window.location.href = cancelacionRenovacionUrl;
        });

        $('#Aseguradora').on('change', function() {
            poblarRamos();
            cargarComisionRamo();
            poblarProductos();
            sincronizarCamposOcultos();
        });

        $('#Ramo').on('change', function() {
            cargarComisionRamo();
            poblarProductos();
            sincronizarCamposOcultos();
        });

        $('#Productos').on('change', function() {
            poblarPlanes($(this).val(), '');
            cargarComisionProducto();
            sincronizarCamposOcultos();
        });

        $('#PlanesVisible').on('change', function() {
            $('#Planes').val($(this).val() || '');
            actualizarTarifaPlan();
        });

        $('#Cliente').on('change', function() {
            const cliente = $('#Cliente').val();
            sincronizarCamposOcultos();

            if (!cliente) {
                $('#NumeroDocumento').val('');
                return;
            }

            $.ajax({
                type: 'get',
                url: "{{ url('get_cliente') }}",
                data: { Cliente: cliente },
                success: function(data) {
                    $('#NumeroDocumento').val(data.Nit || data.Dui || '');
                },
                error: function() {
                    toastr.error('Error al obtener los datos del cliente.');
                }
            });
        });

        $('#formPolizaSeguroEdit').on('submit', function() {
            sincronizarCamposOcultos();
            $('#btnGuardarPoliza').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        });

        aplicarMayusculasFormulario('#formPolizaSeguroEdit');

        $('#IvaIncluidoSwitch').on('change', function() {
            const valor = $(this).is(':checked') ? 'S' : 'N';
            $('#IvaIncluido').val(valor);
            $('#IvaIncluidoLabel').text(valor === 'S' ? 'Si' : 'No');
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

        $(function() {
            if ($.fn.inputmask) {
                $('[data-inputmask]').inputmask();
            }

            const tablaCertificados = $('#tabla-certificados').DataTable({
                pageLength: 25,
                scrollX: true,
                autoWidth: false,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: [-1] }
                ],
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Sin registros',
                    emptyTable: 'Sin certificados registrados.',
                    zeroRecords: 'No se encontraron certificados',
                    paginate: {
                        first: 'Primero',
                        last: 'Ultimo',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });

            $('#tabla-certificados tbody').on('click', '.js-toggle-cert-dependientes', function() {
                const button = $(this);
                const tr = button.closest('tr');
                const row = tablaCertificados.row(tr);
                const certificadoId = button.data('certificado');
                const template = $('#certificado-dependientes-' + certificadoId).html();

                if (!template) {
                    return;
                }

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    button.find('i').removeClass('fa-minus').addClass('fa-plus');
                    button.attr('title', 'Ver dependientes');
                } else {
                    row.child(template).show();
                    tr.addClass('shown');
                    button.find('i').removeClass('fa-plus').addClass('fa-minus');
                    button.attr('title', 'Ocultar dependientes');
                }

                tablaCertificados.columns.adjust();
            });

            if (!$.fn.DataTable.isDataTable('#tabla-renovaciones')) {
                $('#tabla-renovaciones').DataTable({
                    pageLength: 10,
                    order: [[9, 'desc']],
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Sin registros',
                        zeroRecords: 'No se encontraron renovaciones',
                        paginate: {
                            first: 'Primero',
                            last: 'Ultimo',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });
            }

            $('a[data-toggle="tab"][href="#certificados"]').on('shown.bs.tab', function() {
                tablaCertificados.columns.adjust();
            });
        });

        $('#Aseguradora').val(aseguradoraActualId).trigger('change.select2');
        poblarRamos(ramoActualId);
        $('#Cliente').val(clienteActualId).trigger('change.select2');
        poblarProductos(productoActualId, planActualId);
        actualizarEstadoModoRenovacion(modoRenovacionInicial);
        sincronizarCamposOcultos();
        sincronizarMonedaReadonly('SumaAsegurada', 'SumaAseguradaDisplay');
        sincronizarMonedaReadonly('PrimaNetaAnual', 'PrimaNetaAnualDisplay');
        calcularDias();
    </script>
@else
    <p class="text-center text-danger">No tiene permiso para ver.</p>
@endcan
@endsection
