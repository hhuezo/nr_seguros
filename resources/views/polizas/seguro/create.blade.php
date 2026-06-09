@extends ('welcome')
@section('contenido')
@can('seguro create')
    @php
        $productosCatalogo = $productos->map(function ($producto) {
            return [
                'id' => $producto->Id,
                'nombre' => $producto->Nombre,
                'ramo' => $producto->NecesidadProteccion,
                'aseguradora' => $producto->Aseguradora,
            ];
        })->values();

        $planesCatalogo = $planes->map(function ($plan) {
            return [
                'id' => $plan->Id,
                'nombre' => $plan->Nombre,
                'producto' => $plan->Producto,
            ];
        })->values();

        $aseguradorasCatalogo = $aseguradora->map(function ($item) {
            return [
                'id' => $item->Id,
                'nombre' => $item->Nombre,
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

                    <div class="col-md-2 col-sm-5 poliza-field">
                        <label class="control-label" for="NumeroDocumento">Numero documento *</label>
                        <input type="text" name="NumeroDocumento" id="NumeroDocumento" class="form-control"
                               value="{{ old('NumeroDocumento') }}" readonly>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="NumeroPoliza">Numero poliza *</label>
                        <input type="text" name="NumeroPoliza" id="NumeroPoliza" class="form-control"
                               value="{{ old('NumeroPoliza') }}" required>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="Ramo">Ramo *</label>
                        <select name="Ramo" id="Ramo" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($ramos as $obj)
                                <option value="{{ $obj->Id }}"
                                    data-comision="{{ $obj->PorcentajeComisionNoDeclarativa }}"
                                    {{ old('Ramo') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="Aseguradora">Aseguradora *</label>
                        <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($aseguradora as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Aseguradora') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="Productos">Productos *</label>
                        <select name="Productos" id="Productos" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="Planes">Planes *</label>
                        <select name="Planes" id="Planes" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                        </select>
                    </div>





                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="DepartamentoNr">Departamento NR</label>
                        <select name="DepartamentoNr" id="DepartamentoNr" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($departamento_nr as $obj)
                                <option value="{{ $obj->Id }}" {{ old('DepartamentoNr') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="EjecutivoCia">Ejecutivo que atenderá</label>
                        <select name="EjecutivoCia" id="EjecutivoCia" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($ejecutivos as $obj)
                                <option value="{{ $obj->Id }}" {{ old('EjecutivoCia') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="EstadoPoliza">Estado de poliza *</label>
                        <select name="EstadoPoliza" id="EstadoPoliza" class="form-control select2" style="width: 100%" required>
                            @foreach ($estado_poliza as $estado)
                                <option value="{{ $estado->Id }}" {{ old('EstadoPoliza') == $estado->Id ? 'selected' : '' }}>
                                    {{ $estado->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="Deducible">Tipo de deducible</label>
                        <select name="Deducible" id="Deducible" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($tipo_deducible as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Deducible') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-5 poliza-field">
                        <label class="control-label" for="ValorDeducible">Valor deducible</label>
                        <input type="number" name="ValorDeducible" id="ValorDeducible" class="form-control"
                               step="0.01" value="{{ old('ValorDeducible') }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="FormaPago">Forma de pago *</label>
                        <select name="FormaPago" id="FormaPago" class="form-control select2" style="width: 100%" required>
                            @foreach ($forma_pago as $pago)
                                <option value="{{ $loop->index }}" {{ old('FormaPago') == $loop->index ? 'selected' : '' }}>
                                    {{ $pago == '' ? 'Seleccione...' : $pago }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 col-sm-6 poliza-field">
                        <label class="control-label" for="NumCuotas">Cuotas</label>
                        <input type="number" name="NumCuotas" id="NumCuotas" class="form-control"
                               value="{{ old('NumCuotas') }}" >
                    </div>
                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="SumaAsegurada">Suma asegurada</label>
                        <input type="number" name="SumaAsegurada" id="SumaAsegurada" class="form-control"
                               step="0.01" value="{{ old('SumaAsegurada') }}" >
                    </div>

                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="PrimaNetaAnual">Prima neta anual</label>
                        <input type="number" name="PrimaNetaAnual" id="PrimaNetaAnual" class="form-control"
                               step="0.01" value="{{ old('PrimaNetaAnual') }}" >
                    </div>
                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="PorcentajeComisionNR">% Comisión NR</label>
                        <input type="number" name="PorcentajeComisionNR" id="PorcentajeComisionNR" class="form-control"
                               step="0.0001" value="{{ old('PorcentajeComisionNR') }}">
                    </div>
                </div>

            </div>

            <div class="poliza-section">
                <h5 class="poliza-section-title">Vigencia y cancelación</h5>
                <div class="row">
                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="vigencia_desde">Vigencia desde *</label>
                        <input class="form-control" id="vigencia_desde" name="VigenciaDesde" type="date"
                            value="{{ old('VigenciaDesde') }}" required>
                    </div>

                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="vigencia_hasta">Vigencia hasta *</label>
                        <input class="form-control" id="vigencia_hasta" name="VigenciaHasta" type="date"
                            value="{{ old('VigenciaHasta') }}" required>
                    </div>

                    <div class="col-md-1 col-sm-6 poliza-field">
                        <label class="control-label" for="dias_vigencia">Dias</label>
                        <input type="number" name="DiasVigencia" id="dias_vigencia" class="form-control"
                            value="{{ old('DiasVigencia') }}" readonly>
                    </div>
                    <div class="col-md-5 col-sm-6 poliza-field">
                        <label class="control-label" for="CodCancelacion">Motivos de cancelacion</label>
                        <select name="CodCancelacion" id="CodCancelacion" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($motivos_cancelacion as $obj)
                                <option value="{{ $obj->Id }}" {{ old('CodCancelacion') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="FechaCancelacion">Fecha cancelacion</label>
                        <input type="date" name="FechaCancelacion" id="FechaCancelacion" class="form-control"
                               value="{{ old('FechaCancelacion') }}">
                    </div>

                    <div class="col-md-10 col-sm-6 poliza-field">
                        <label class="control-label" for="MotivoCancelacion">Motivo cancelacion</label>
                        <input type="text" name="MotivoCancelacion" id="MotivoCancelacion" class="form-control"
                            value="{{ old('MotivoCancelacion') }}">
                    </div>

                    <div class="col-md-2 col-sm-6 poliza-field">
                        <label class="control-label" for="FechaEnvioAnexo">Fecha envio anexo</label>
                        <input type="date" name="FechaEnvioAnexo" id="FechaEnvioAnexo" class="form-control"
                            value="{{ old('FechaEnvioAnexo') }}">
                    </div>
                </div>
            </div>

            <div class="poliza-section">
                <h5 class="poliza-section-title">Administracion y seguimiento</h5>
                <div class="row">
                    <div class="col-md-6 col-sm-6 poliza-field">
                        <label class="control-label" for="Observacion">Observacion Ren.</label>
                        <input type="text" name="Observacion" id="Observacion" class="form-control"
                            value="{{ old('Observacion') }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="SolicitudRenovacion">Solicitud renovacion</label>
                        <input type="date" name="SolicitudRenovacion" id="SolicitudRenovacion" class="form-control"
                            value="{{ old('SolicitudRenovacion') }}">
                    </div>

                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="FechaVinculacion">Fecha vinculacion</label>
                        <input type="date" name="FechaVinculacion" id="FechaVinculacion" class="form-control"
                            value="{{ old('FechaVinculacion') }}">
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="OrigenPoliza">Origen poliza</label>
                        <select name="OrigenPoliza" id="OrigenPoliza" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($origen_poliza as $obj)
                                <option value="{{ $obj->Id }}" {{ old('OrigenPoliza') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="FechaRecepcion">Fecha recepcion</label>
                        <input type="date" name="FechaRecepcion" id="FechaRecepcion" class="form-control"
                            value="{{ old('FechaRecepcion') }}">
                    </div>
                </div>
            </div>

            <div class="poliza-section">
                <h5 class="poliza-section-title">Datos complementarios</h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 poliza-field">
                        <label class="control-label" for="SustituidaPoliza">Sustituida por poliza</label>
                        <input type="date" name="SustituidaPoliza" id="SustituidaPoliza" class="form-control"
                            value="{{ old('SustituidaPoliza') }}">
                    </div>

                    <div class="col-md-5 col-sm-6 poliza-field">
                        <label class="control-label" for="ObservacionSiniestro">Observacion siniestro</label>
                        <input type="text" name="ObservacionSiniestro" id="ObservacionSiniestro" class="form-control"
                            value="{{ old('ObservacionSiniestro') }}">
                    </div>

                    <div class="col-md-4 col-sm-6 poliza-field">
                        <label class="control-label" for="GrupoCliente">Grupo cliente</label>
                        <input type="text" name="GrupoCliente" id="GrupoCliente" class="form-control"
                            value="{{ old('GrupoCliente') }}">
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
        const oldAseguradora = "{{ old('Aseguradora') }}";
        const oldProducto = "{{ old('Productos') }}";
        const oldPlan = "{{ old('Planes') }}";

        function formatoDecimal2(valor) {
            const numero = parseFloat(valor);
            return isNaN(numero) ? '' : numero.toFixed(2);
        }

        function reiniciarSelect(selector, texto) {
            const select = $(selector);
            select.empty();
            select.append(new Option(texto, '', true, false));
            return select;
        }

        function cargarComisionRamo() {
            const option = $('#Ramo option:selected');
            const comision = option.data('comision');
            $('#PorcentajeComisionNR').val(comision || '');
        }

        function poblarAseguradoras(aseguradoraSeleccionada = '') {
            const ramo = $('#Ramo').val();
            const select = reiniciarSelect('#Aseguradora', 'Seleccione...');
            const aseguradorasPermitidas = [];

            productosCatalogo
                .filter(function(producto) {
                    return ramo && producto.ramo == ramo;
                })
                .forEach(function(producto) {
                    const aseguradoraId = String(producto.aseguradora);
                    if (aseguradoraId && aseguradorasPermitidas.indexOf(aseguradoraId) === -1) {
                        aseguradorasPermitidas.push(aseguradoraId);
                    }
                });

            aseguradorasCatalogo
                .filter(function(aseguradora) {
                    return aseguradorasPermitidas.indexOf(String(aseguradora.id)) !== -1;
                })
                .forEach(function(aseguradora) {
                    select.append(new Option(aseguradora.nombre, aseguradora.id, false, aseguradora.id == aseguradoraSeleccionada));
                });

            if (aseguradoraSeleccionada && aseguradorasPermitidas.indexOf(String(aseguradoraSeleccionada)) !== -1) {
                select.val(aseguradoraSeleccionada);
            } else {
                select.val('');
            }

            select.prop('disabled', !ramo).trigger('change.select2');
        }

        function poblarProductos(productoSeleccionado = '', planSeleccionado = '') {
            const ramo = $('#Ramo').val();
            const aseguradora = $('#Aseguradora').val();
            const select = reiniciarSelect('#Productos', 'Seleccione...');

            reiniciarSelect('#Planes', 'Seleccione...').trigger('change.select2');

            if (!ramo || !aseguradora) {
                select.val('').trigger('change.select2');
                return;
            }

            productosCatalogo
                .filter(function(producto) {
                    return producto.ramo == ramo && producto.aseguradora == aseguradora;
                })
                .forEach(function(producto) {
                    select.append(new Option(producto.nombre, producto.id, false, producto.id == productoSeleccionado));
                });

            select.val(productoSeleccionado || '').trigger('change.select2');
            poblarPlanes(productoSeleccionado || $('#Productos').val(), planSeleccionado);
        }

        function poblarPlanes(productoId = '', planSeleccionado = '') {
            const select = reiniciarSelect('#Planes', 'Seleccione...');

            planesCatalogo
                .filter(function(plan) {
                    return productoId && plan.producto == productoId;
                })
                .forEach(function(plan) {
                    select.append(new Option(plan.nombre, plan.id, false, plan.id == planSeleccionado));
                });

            select.val(planSeleccionado || '').trigger('change.select2');
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
            $('#FormaPago').val('').trigger('change');
            $('#Cliente').val('').trigger('change');
            $('#Ramo').val('').trigger('change.select2');
            poblarAseguradoras();
            poblarProductos();
            $('#DepartamentoNr').val('').trigger('change');
            $('#EjecutivoCia').val('').trigger('change');
            $('#NumeroPoliza').val('');
            $('#NumeroDocumento').val('');
            $('#NumCuotas').val('');
            $('#Deducible').val('').trigger('change');
            $('#ValorDeducible').val('');
            $('#SumaAsegurada').val('');
            $('#PrimaNetaAnual').val('');
            $('#PorcentajeComisionNR').val('');
            $('#vigencia_desde').val('');
            $('#Observacion').val('');
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

                    if (data.success) {
                        cargandoOferta = true;
                        $('#Ramo').val(data.oferta.ramo ?? '').trigger('change.select2');
                        poblarAseguradoras(data.oferta.aseguradora ?? '');
                        poblarProductos(data.oferta.productos ?? '', data.oferta.planes ?? '');
                        $('#FormaPago').val(data.oferta.forma_pago ?? '').trigger('change');
                        $('#Cliente').val(data.oferta.id_cliente ?? '').trigger('change');
                        $('#DepartamentoNr').val(data.oferta.departamento ?? '').trigger('change');
                        $('#EjecutivoCia').val(data.oferta.ejecutivo ?? '').trigger('change');
                        bloquearCliente(true);
                        cargandoOferta = false;

                        $('#NumeroPoliza').val(data.oferta.numero_poliza ?? '');
                        $('#NumeroDocumento').val(data.oferta.numero_documento ?? '');
                        $('#NumCuotas').val(data.oferta.num_cuotas ?? '');
                        $('#SumaAsegurada').val(data.oferta.cotizacion ? formatoDecimal2(data.oferta.cotizacion.suma_asegurada) : '');
                        $('#PrimaNetaAnual').val(data.oferta.cotizacion ? formatoDecimal2(data.oferta.cotizacion.prima_neta_anual) : '');
                        $('#PorcentajeComisionNR').val(data.oferta.porcentaje_comision_nr ?? '');
                        $('#vigencia_desde').val(data.oferta.vigencia_desde ?? '');
                        $('#Observacion').val(data.oferta.observacion ?? '');
                        calcularDias();
                    } else {
                        limpiarDatosOferta();
                        toastr.warning(data.message || 'No se pudo obtener la oferta seleccionada.');
                    }
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
        $('#Ramo').on('change', function() {
            if (!cargandoOferta) {
                cargarComisionRamo();
                poblarAseguradoras();
                poblarProductos();
            }
        });
        $('#Aseguradora').on('change', function() {
            if (!cargandoOferta) {
                poblarProductos();
            }
        });
        $('#Productos').on('change', function() {
            if (!cargandoOferta) {
                poblarPlanes($(this).val(), '');
            }
        });

        $('#Cliente').change(function() {
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

        if ($('#Oferta').val()) {
            bloquearCliente(true);
            select_oferta();
        } else {
            poblarAseguradoras(oldAseguradora);
            poblarProductos(oldProducto, oldPlan);
            if ($('#Ramo').val() && !$('#PorcentajeComisionNR').val()) {
                cargarComisionRamo();
            }
        }
        calcularDias();
    </script>
@else
    <p class="text-center text-danger">No tiene permiso para crear.</p>
@endcan
@endsection
