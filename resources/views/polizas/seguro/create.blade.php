@extends ('welcome')
@section('contenido')
@can('seguro create')
    @php
        $oldRamo = old('Ramo');
        $oldAseguradoraId = old('Aseguradora');
        $oldProductoId = old('Productos');
        $oldPlanId = old('Planes');
        $oldIvaIncluido = old('IvaIncluido', 'N');

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

    <style>
        #loading-overlay-modal {
            align-items: center;
            background: rgba(255, 255, 255, 0.78);
            display: none;
            height: 100%;
            justify-content: center;
            left: 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
        }

        #loading-overlay-modal img {
            height: 50px;
            width: 50px;
        }

        .poliza-form {
            margin-top: 8px;
        }

        .poliza-section {
            border-top: 1px solid #e5e7eb;
            padding: 18px 0 6px;
        }

        .poliza-section:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .poliza-section-title {
            color: #334155;
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 12px;
        }

        .poliza-field {
            margin-bottom: 14px;
        }

        .poliza-field label {
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .poliza-actions {
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 12px;
            padding-top: 16px;
        }
    </style>

    <div class="x_panel">
        <div id="loading-overlay-modal">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
        </div>

        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endif

        @if (session('error'))
            <script>
                toastr.error("{{ session('error') }}");
            </script>
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

        <div class="x_title">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <h4>Nueva poliza de seguro</h4>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12" align="right">
                <a href="{{ url('poliza/seguro') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Atras
                </a>
            </div>
            <div class="clearfix"></div>
        </div>

        <form id="formPolizaSeguroCreate" class="poliza-form" action="{{ url('poliza/seguro') }}" method="post">
            @csrf

            <input type="hidden" name="Planes" id="Planes" value="{{ $oldPlanId }}">
            <input type="hidden" name="NumCuotas" id="NumCuotas" value="{{ old('NumCuotas') }}">
            <input type="hidden" name="IvaIncluido" id="IvaIncluido" value="{{ $oldIvaIncluido }}">

            <div class="poliza-section">
                <h5 class="poliza-section-title">Origen de la poliza</h5>
                <div class="row">
                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="Oferta"># Oferta aceptada</label>
                        <select name="Oferta" id="Oferta" class="form-control select2" style="width: 100%">
                            <option value="">Sin oferta aceptada...</option>
                            @foreach ($ofertas as $off)
                                @php
                                    $cotizacionAceptada = $off->cotizaciones->first();
                                @endphp
                                <option value="{{ $off->Id }}" {{ old('Oferta') == $off->Id ? 'selected' : '' }}>
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
                        <label class="control-label" for="NumeroVigencia">Num. Vigencia</label>
                        <input type="number" name="NumeroVigencia" id="NumeroVigencia" class="form-control" min="1" value="{{ old('NumeroVigencia', 1) }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="NumeroPoliza">Numero poliza *</label>
                        <input type="text" name="NumeroPoliza" id="NumeroPoliza" class="form-control" value="{{ old('NumeroPoliza') }}" required>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="EstadoPoliza">Estado de poliza *</label>
                        <select name="EstadoPoliza" id="EstadoPoliza" class="form-control select2" style="width: 100%" required>
                            @foreach ($estado_poliza as $estado)
                                <option value="{{ $estado->Id }}" {{ old('EstadoPoliza') == $estado->Id ? 'selected' : '' }}>
                                    {{ $estado->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-sm-6 poliza-field">
                        <label class="control-label" for="Cliente">Nombre cliente *</label>
                        <select name="Cliente" id="Cliente" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($clientes as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Cliente') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-5 poliza-field">
                        <label class="control-label" for="NumeroDocumento">Numero documento *</label>
                        <input type="text" name="NumeroDocumento" id="NumeroDocumento" class="form-control" value="{{ old('NumeroDocumento') }}" readonly>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="FechaVinculacion">Fecha vinculacion</label>
                        <input type="date" name="FechaVinculacion" id="FechaVinculacion" class="form-control" value="{{ old('FechaVinculacion') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="vigencia_desde">Vigencia desde *</label>
                        <input class="form-control" id="vigencia_desde" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}" required>
                    </div>

                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="vigencia_hasta">Vigencia hasta *</label>
                        <input class="form-control" id="vigencia_hasta" name="VigenciaHasta" type="date" value="{{ old('VigenciaHasta') }}" required>
                    </div>

                    <div class="col-md-1 col-sm-6 poliza-field">
                        <label class="control-label" for="dias_vigencia">Dias</label>
                        <input type="number" name="DiasVigencia" id="dias_vigencia" class="form-control" value="{{ old('DiasVigencia') }}" readonly>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="FormaPago">Forma de pago *</label>
                        <select name="FormaPago" id="FormaPago" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                            @foreach ($forma_pago as $pago)
                                <option value="{{ $pago->Id }}" {{ old('FormaPago') == $pago->Id ? 'selected' : '' }}>
                                    {{ $pago->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="PorcentajeComisionNR">% Comision NR</label>
                        <input type="number" name="PorcentajeComisionNR" id="PorcentajeComisionNR" class="form-control" step="0.0001" value="{{ old('PorcentajeComisionNR') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="Aseguradora">Aseguradora *</label>
                        <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                            @foreach ($aseguradora as $obj)
                                <option value="{{ $obj->Id }}" {{ $oldAseguradoraId == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="Ramo">Ramo *</label>
                        <select name="Ramo" id="Ramo" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="Productos">Productos *</label>
                        <select name="Productos" id="Productos" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="PlanesVisible">Planes *</label>
                        <select id="PlanesVisible" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="OrigenPoliza">Origen poliza</label>
                        <select name="OrigenPoliza" id="OrigenPoliza" class="form-control select2" style="width: 100%">
                            <option value="">Seleccione...</option>
                            @foreach ($origen_poliza as $obj)
                                <option value="{{ $obj->Id }}" {{ old('OrigenPoliza') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="SustituidaPoliza">Sustituye poliza</label>
                        <input type="text" name="SustituidaPoliza" id="SustituidaPoliza" class="form-control" value="{{ old('SustituidaPoliza') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="SumaAsegurada">Suma asegurada</label>
                        <input id="SumaAsegurada" name="SumaAsegurada" type="hidden" value="{{ old('SumaAsegurada') }}">
                        <input class="form-control" id="SumaAseguradaDisplay" type="text" value="{{ is_numeric(old('SumaAsegurada')) ? '$' . number_format((float) old('SumaAsegurada'), 2) : '' }}" readonly>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="TarifaPlan">Tarifa</label>
                        <input class="form-control" id="TarifaPlan" type="text" value="" readonly>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="PrimaNetaAnual">Prima neta anual</label>
                        <input id="PrimaNetaAnual" name="PrimaNetaAnual" type="hidden" value="{{ old('PrimaNetaAnual') }}">
                        <input class="form-control" id="PrimaNetaAnualDisplay" type="text" value="{{ is_numeric(old('PrimaNetaAnual')) ? '$' . number_format((float) old('PrimaNetaAnual'), 2) : '' }}" readonly>
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label">Iva incluido</label>
                        <div style="padding-top: 6px;">
                            <input type="checkbox" id="IvaIncluidoSwitch" class="js-switch" {{ $oldIvaIncluido === 'S' ? 'checked' : '' }}>
                            <span id="IvaIncluidoLabel" style="margin-left: 8px;">{{ $oldIvaIncluido === 'S' ? 'Si' : 'No' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="PorcentajeDescuentoRentabilidad">% Descuento rentabilidad</label>
                        <input class="form-control" id="PorcentajeDescuentoRentabilidad" name="PorcentajeDescuentoRentabilidad" type="number" step="0.0001" value="{{ old('PorcentajeDescuentoRentabilidad') }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="PorcentajeDescuentoBuenaExperiencia">% Descuento buena experiencia</label>
                        <input class="form-control" id="PorcentajeDescuentoBuenaExperiencia" name="PorcentajeDescuentoBuenaExperiencia" type="number" step="0.0001" value="{{ old('PorcentajeDescuentoBuenaExperiencia') }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="PorcentajeOtrosDescuentos">% Otros descuentos</label>
                        <input class="form-control" id="PorcentajeOtrosDescuentos" name="PorcentajeOtrosDescuentos" type="number" step="0.0001" value="{{ old('PorcentajeOtrosDescuentos') }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="PorcentajeComsionCliente">% Comision cliente</label>
                        <input class="form-control" id="PorcentajeComsionCliente" name="PorcentajeComsionCliente" type="number" step="0.0001" value="{{ old('PorcentajeComsionCliente') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="ClausulasEspeciales">Clausulas especiales</label>
                        <textarea class="form-control" id="ClausulasEspeciales" name="ClausulasEspeciales" rows="4">{{ old('ClausulasEspeciales') }}</textarea>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="BeneficiosAdicionales">Beneficios adicionales</label>
                        <textarea class="form-control" id="BeneficiosAdicionales" name="BeneficiosAdicionales" rows="4">{{ old('BeneficiosAdicionales') }}</textarea>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="Comentarios">Comentarios</label>
                        <textarea class="form-control" id="Comentarios" name="Comentarios" rows="4">{{ old('Comentarios') }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="TipoCarteraNR">Tipo de cartera</label>
                        <select name="TipoCarteraNR" id="TipoCarteraNR" class="form-control select2" style="width: 100%">
                            <option value="">Seleccione...</option>
                            @foreach ($tipo_cartera_nr as $obj)
                                <option value="{{ $obj->Id }}" {{ old('TipoCarteraNR') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="EjecutivoCia">Ejecutivo que atendera</label>
                        <select name="EjecutivoCia" id="EjecutivoCia" class="form-control select2" style="width: 100%">
                            <option value="">Seleccione...</option>
                            @foreach ($ejecutivos as $obj)
                                <option value="{{ $obj->Id }}" {{ old('EjecutivoCia') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-6 poliza-field" style="display: none !important;">
                        <label class="control-label" for="Deducible">Tipo de deducible</label>
                        <select name="Deducible" id="Deducible" class="form-control select2" style="width: 100%">
                            <option value="">Seleccione...</option>
                            @foreach ($tipo_deducible as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Deducible') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="poliza-section">
                <h5 class="poliza-section-title">Cancelacion poliza</h5>
                <div class="row">
                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="FechaCancelacion">Fecha cancelacion</label>
                        <input type="date" name="FechaCancelacion" id="FechaCancelacion" class="form-control" value="{{ old('FechaCancelacion') }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="CodCancelacion">Motivos de cancelacion</label>
                        <select name="CodCancelacion" id="CodCancelacion" class="form-control select2" style="width: 100%">
                            <option value="">Seleccione...</option>
                            @foreach ($motivos_cancelacion as $obj)
                                <option value="{{ $obj->Id }}" {{ old('CodCancelacion') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-7 col-sm-6 poliza-field">
                        <label class="control-label" for="MotivoCancelacion">Observaciones cancelacion</label>
                        <input type="text" name="MotivoCancelacion" id="MotivoCancelacion" class="form-control" value="{{ old('MotivoCancelacion') }}">
                    </div>
                </div>
            </div>

            <div class="poliza-actions">
                <a href="{{ url('poliza/seguro') }}" class="btn btn-danger">
                    <i class="fa fa-times"></i> Cancelar
                </a>
                <button type="submit" id="btnGuardarPoliza" class="btn btn-primary">
                    <i class="fa fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>

    <script>
        const desdeInput = document.getElementById('vigencia_desde');
        const hastaInput = document.getElementById('vigencia_hasta');
        const diasInput = document.getElementById('dias_vigencia');
        let cargandoOferta = false;
        const productosCatalogo = @json($productosCatalogo);
        const planesCatalogo = @json($planesCatalogo);
        const aseguradorasCatalogo = @json($aseguradorasCatalogo);
        const ramosCatalogo = @json($ramosCatalogo);
        const oldRamo = "{{ $oldRamo }}";
        const oldProducto = "{{ $oldProductoId }}";
        const oldPlan = "{{ $oldPlanId }}";

        function formatoDecimal2(valor) {
            const numero = parseFloat(valor);
            return isNaN(numero) ? '' : numero.toFixed(2);
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

        function setReadonlyCurrency(inputId, displayId, valor) {
            const valorNormalizado = valor === '' ? '' : formatoDecimal2(valor);
            $('#' + inputId).val(valorNormalizado);
            $('#' + displayId).val(formatoMonedaUsd(valorNormalizado));
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

        function reiniciarSelect(selector, texto) {
            const select = $(selector);
            select.empty();
            select.append(new Option(texto, '', true, false));
            return select;
        }

        function actualizarIvaIncluidoLabel() {
            const activo = $('#IvaIncluido').val() === 'S';
            $('#IvaIncluidoLabel').text(activo ? 'Si' : 'No');
        }

        function actualizarTarifaPlan() {
            const planId = $('#Planes').val();
            const plan = planesCatalogo.find(function(item) {
                return String(item.id) === String(planId);
            });

            $('#TarifaPlan').val(plan && plan.tarifa_label ? plan.tarifa_label : '');
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

            select.prop('disabled', !aseguradora).trigger('change.select2');
        }

        function poblarProductos(productoSeleccionado = '', planSeleccionado = '') {
            const ramo = $('#Ramo').val();
            const aseguradora = $('#Aseguradora').val();
            const select = reiniciarSelect('#Productos', 'Seleccione...');

            limpiarPlan();

            if (!ramo || !aseguradora) {
                select.val('').trigger('change.select2');
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
        }

        function poblarPlanes(productoId = '', planSeleccionado = '') {
            const select = reiniciarSelect('#PlanesVisible', 'Seleccione...');
            const planesProducto = planesCatalogo.filter(function(plan) {
                return productoId && String(plan.producto) === String(productoId);
            });

            planesProducto.forEach(function(plan) {
                select.append(new Option(plan.nombre, plan.id, false, String(plan.id) === String(planSeleccionado)));
            });

            if (planSeleccionado && planesProducto.some(function(plan) {
                return String(plan.id) === String(planSeleccionado);
            })) {
                select.val(String(planSeleccionado));
            } else {
                select.val('');
            }

            $('#Planes').val(select.val() || '');
            actualizarTarifaPlan();
            select.trigger('change.select2');
        }

        function limpiarPlan() {
            $('#Planes').val('');
            $('#PlanesVisible').val('').trigger('change.select2');
            actualizarTarifaPlan();
        }

        function mostrarCargando(mostrar) {
            document.getElementById('loading-overlay-modal').style.display = mostrar ? 'flex' : 'none';
        }

        function bloquearCliente(bloquear) {
            $('#Cliente').prop('disabled', bloquear).trigger('change.select2');
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

        function limpiarDatosOferta() {
            bloquearCliente(false);
            $('#Cliente').val('').trigger('change');
            $('#NumeroDocumento').val('');
            $('#NumeroPoliza').val('');
            $('#NumeroVigencia').val('1');
            $('#Aseguradora').val('').trigger('change.select2');
            poblarRamos();
            poblarProductos();
            $('#FormaPago').val('').trigger('change');
            $('#NumCuotas').val('');
            setReadonlyCurrency('SumaAsegurada', 'SumaAseguradaDisplay', '');
            setReadonlyCurrency('PrimaNetaAnual', 'PrimaNetaAnualDisplay', '');
            $('#PorcentajeComisionNR').val('');
            $('#vigencia_desde').val('');
            $('#TipoCarteraNR').val('').trigger('change');
            $('#EjecutivoCia').val('').trigger('change');
            $('#IvaIncluido').val('N');
            $('#IvaIncluidoSwitch').prop('checked', false);
            actualizarIvaIncluidoLabel();
            calcularDias();
        }

        function select_oferta() {
            const oferta = $('#Oferta').val();

            if (!oferta) {
                limpiarDatosOferta();
                return;
            }

            mostrarCargando(true);

            $.ajax({
                type: "get",
                url: "{{ url('poliza/seguro/get_oferta') }}",
                data: { Oferta: oferta },
                success: function(data) {
                    mostrarCargando(false);

                    if (!data.success) {
                        limpiarDatosOferta();
                        toastr.warning(data.message || 'No se pudo obtener la oferta seleccionada.');
                        return;
                    }

                    cargandoOferta = true;
                    $('#Aseguradora').val(data.oferta.aseguradora ?? '').trigger('change.select2');
                    poblarRamos(data.oferta.ramo ?? '');
                    poblarProductos(data.oferta.productos ?? '', data.oferta.planes ?? '');
                    $('#FormaPago').val(data.oferta.forma_pago ?? '').trigger('change');
                    $('#Cliente').val(data.oferta.id_cliente ?? '').trigger('change');
                    $('#TipoCarteraNR').val(data.oferta.tipo_cartera_nr ?? '').trigger('change');
                    $('#EjecutivoCia').val(data.oferta.ejecutivo ?? '').trigger('change');
                    bloquearCliente(true);
                    cargandoOferta = false;

                    $('#NumeroPoliza').val(data.oferta.numero_poliza ?? '');
                    $('#NumeroDocumento').val(data.oferta.numero_documento ?? '');
                    $('#NumCuotas').val(data.oferta.num_cuotas ?? '');
                    setReadonlyCurrency('SumaAsegurada', 'SumaAseguradaDisplay', data.oferta.cotizacion ? data.oferta.cotizacion.suma_asegurada : '');
                    setReadonlyCurrency('PrimaNetaAnual', 'PrimaNetaAnualDisplay', data.oferta.cotizacion ? data.oferta.cotizacion.prima_neta_anual : '');
                    $('#PorcentajeComisionNR').val(data.oferta.porcentaje_comision_nr ?? '');
                    $('#vigencia_desde').val(data.oferta.vigencia_desde ?? '');
                    actualizarTarifaPlan();
                    calcularDias();
                },
                error: function() {
                    mostrarCargando(false);
                    limpiarDatosOferta();
                    toastr.error('Ocurrio un error al obtener la oferta.');
                }
            });
        }

        desdeInput.addEventListener('change', calcularDias);
        hastaInput.addEventListener('change', calcularDias);

        $('#Oferta').on('change', select_oferta);
        $('#Aseguradora').on('change', function() {
            if (!cargandoOferta) {
                poblarRamos();
                cargarComisionRamo();
                poblarProductos();
            }
        });
        $('#Ramo').on('change', function() {
            if (!cargandoOferta) {
                cargarComisionRamo();
                poblarProductos();
            }
        });
        $('#Productos').on('change', function() {
            if (!cargandoOferta) {
                poblarPlanes($(this).val(), '');
                cargarComisionProducto();
            }
        });
        $('#PlanesVisible').on('change', function() {
            $('#Planes').val($(this).val() || '');
            actualizarTarifaPlan();
        });
        $('#IvaIncluidoSwitch').on('change', function() {
            $('#IvaIncluido').val($(this).is(':checked') ? 'S' : 'N');
            actualizarIvaIncluidoLabel();
        });

        $('#Cliente').on('change', function() {
            if (cargandoOferta) {
                return;
            }

            const cliente = $('#Cliente').val();
            if (!cliente) {
                $('#NumeroDocumento').val('');
                return;
            }

            mostrarCargando(true);

            $.ajax({
                type: "get",
                url: "{{ url('get_cliente') }}",
                data: { Cliente: cliente },
                success: function(data) {
                    $('#NumeroDocumento').val(data.Nit || data.Dui || '');
                    mostrarCargando(false);
                },
                error: function() {
                    mostrarCargando(false);
                    toastr.error('Error al obtener los datos del cliente.');
                }
            });
        });

        $('#formPolizaSeguroCreate').on('submit', function() {
            const btn = $('#btnGuardarPoliza');
            bloquearCliente(false);
            btn.prop('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        });

        aplicarMayusculasFormulario('#formPolizaSeguroCreate');

        if ($('#Oferta').val()) {
            bloquearCliente(true);
            select_oferta();
        } else {
            poblarRamos(oldRamo);
            poblarProductos(oldProducto, oldPlan);
            actualizarTarifaPlan();
            actualizarIvaIncluidoLabel();

            if ($('#Ramo').val() && !$('#PorcentajeComisionNR').val()) {
                cargarComisionProducto();
            }
        }

        setReadonlyCurrency('SumaAsegurada', 'SumaAseguradaDisplay', $('#SumaAsegurada').val());
        setReadonlyCurrency('PrimaNetaAnual', 'PrimaNetaAnualDisplay', $('#PrimaNetaAnual').val());
        calcularDias();
    </script>
@else
    <p class="text-center text-danger">No tiene permiso para crear.</p>
@endcan
@endsection
