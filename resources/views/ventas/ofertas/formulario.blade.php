@extends ('welcome')
@section('contenido')
    <style>
        .ventas-page {
            font-size: 12px;
        }

        .ventas-section {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 14px;
            margin-bottom: 14px;
            background: #fff;
        }

        .ventas-tab-content {
            padding-top: 15px;
        }

        .ventas-table-compact th,
        .ventas-table-compact td {
            vertical-align: middle !important;
            font-size: 12px;
        }

        .comparativo-wrap {
            overflow-x: auto;
            border: 1px solid #d9dee4;
            border-radius: 4px;
        }

        .tabla-comparativo {
            min-width: 1120px;
            margin-bottom: 0;
            font-size: 12px;
        }

        .tabla-comparativo th {
            background: #337ab7;
            color: #fff;
            text-align: center;
            vertical-align: middle !important;
        }

        .tabla-comparativo td {
            text-align: center;
            vertical-align: middle !important;
        }

        .tabla-comparativo .concepto {
            text-align: left;
            font-weight: 600;
            background: #f7f7f7;
            width: 180px;
        }

        .tabla-comparativo .fila-total td {
            background: #d9edf7;
            font-weight: 700;
        }

        .tabla-comparativo .seccion td {
            background: #337ab7;
            color: #fff;
            font-weight: 700;
            text-align: center;
        }

        .plan-check {
            width: 18px;
            height: 18px;
        }

        .planes-resumen {
            background: #f7f9fb;
            border: 1px solid #e1e6ea;
            border-radius: 4px;
            margin-bottom: 10px;
            padding: 8px 10px;
        }

        .planes-resumen span {
            display: inline-block;
            margin-right: 18px;
        }

        .plan-elegido-radio {
            margin: 0 4px 0 0 !important;
        }
    </style>

    <div class="ventas-page">
        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <h3>Formulario de oferta</h3>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12" align="right">
                    <a href="{{ url('ventas/ofertas') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="ventas-section">
                <ul class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab-oferta" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i> Oferta</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab-planes" role="tab" data-toggle="tab"><i class="fa fa-table"></i> Planes ofertados</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab-seguimientos" role="tab" data-toggle="tab"><i class="fa fa-history"></i> Seguimientos</a>
                    </li>
                </ul>

                <div class="tab-content ventas-tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab-oferta">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Gestor</label>
                                <select class="form-control select2" style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($ejecutivos as $ejecutivo)
                                        <option value="{{ $ejecutivo->Id }}">{{ $ejecutivo->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Fecha de ingreso</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Inicio de vigencia</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Buscar cliente</label>
                                <div class="input-group">
                                    <select id="ClienteBusqueda" name="Cliente" class="form-control select2" style="width: 100%;">
                                        <option value="" selected disabled>Buscar por DUI, NIT, pasaporte, telefono o nombre...</option>
                                        @foreach($clientes as $cliente)
                                            @php
                                                $documentoCliente = $cliente->Dui ?: ($cliente->Nit ?: $cliente->Pasaporte);
                                                $telefonoCliente = $cliente->TelefonoCelular ?: ($cliente->TelefonoCelular2 ?: ($cliente->TelefonoResidencia ?: $cliente->TelefonoOficina));
                                                $correoCliente = $cliente->CorreoPrincipal ?: $cliente->CorreoSecundario;
                                                $textoCliente = trim($cliente->Nombre . ($documentoCliente ? ' / ' . $documentoCliente : '') . ($telefonoCliente ? ' / ' . $telefonoCliente : ''));
                                            @endphp
                                            <option value="{{ $cliente->Id }}"
                                                    data-nombre="{{ $cliente->Nombre }}"
                                                    data-telefono="{{ $telefonoCliente }}"
                                                    data-correo="{{ $correoCliente }}">
                                                {{ $textoCliente }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button id="CopiarDatosCliente" class="btn btn-default" type="button" title="Copiar datos del cliente">
                                            <i class="fa fa-copy"></i>
                                        </button>
                                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modal-cliente-express" title="Agregar cliente">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" id="NombreCliente" name="NombreCliente">
                            <div class="col-md-3 form-group">
                                <label>Telefono</label>
                                <input type="text" id="TelefonoCliente" name="TelefonoCliente" class="form-control" placeholder="0000-0000">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Correo electronico</label>
                                <input type="email" id="CorreoCliente" name="CorreoCliente" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Tipo de cliente</label>
                                <select name="TipoCarteraNr" class="form-control select2" style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($tiposCarteraNr as $tipoCartera)
                                        <option value="{{ $tipoCartera->Id }}">{{ $tipoCartera->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Canal de origen</label>
                                <select class="form-control">
                                    <option>REFERIDO</option>
                                    <option>REDES SOCIALES</option>
                                    <option>CARTERA PROPIA</option>
                                    <option>TELEFONO DIRECTO</option>
                                    <option>PAGINA WEB</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Tipo de seguro / ramo</label>
                                <select id="NecesidadProteccionOferta" name="NecesidadProteccion" class="form-control select2" style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($ramos as $ramo)
                                        <option value="{{ $ramo->Id }}">{{ $ramo->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Prima neta estimada</label>
                                <input type="text" class="form-control" placeholder="$0.00">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Aseguradora referencial</label>
                                <select class="form-control select2" style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($aseguradoras as $aseguradora)
                                        <option value="{{ $aseguradora->Id }}">{{ $aseguradora->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Etapa</label>
                                <select class="form-control select2" style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($estadosVenta as $estadoVenta)
                                        <option value="{{ $estadoVenta->Id }}">{{ $estadoVenta->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Estado</label>
                                <select class="form-control">
                                    <option>EN SEGUIMIENTO</option>
                                    <option>EN SELECCION</option>
                                    <option>ACEPTADO</option>
                                    <option>CERRADO</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Cotizacion enviada</label>
                                <div>
                                    <label class="radio-inline"><input type="radio" name="cotizacion_demo" checked> Si</label>
                                    <label class="radio-inline"><input type="radio" name="cotizacion_demo"> No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Ultimo contacto</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Motivo perdida</label>
                                <select class="form-control">
                                    <option>Seleccione...</option>
                                    <option>FALTA DE RESPUESTA</option>
                                    <option>PRECIO</option>
                                    <option>POSPONE DECISION</option>
                                    <option>NO CUMPLE REQUISITOS</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Postventa realizada</label>
                                <div>
                                    <label class="radio-inline"><input type="radio" name="postventa_demo"> Si</label>
                                    <label class="radio-inline"><input type="radio" name="postventa_demo" checked> No</label>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Encuesta realizada</label>
                                <div>
                                    <label class="radio-inline"><input type="radio" name="encuesta_demo"> Si</label>
                                    <label class="radio-inline"><input type="radio" name="encuesta_demo" checked> No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Comentario general</label>
                                <textarea class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Detalle motivo perdida</label>
                                <textarea class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="tab-planes">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-8">
                                <select id="PlanComercialOferta" class="form-control select2" style="width: 100%;">
                                    <option value="">Seleccione primero un ramo...</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" id="SeleccionarPlanesOferta" class="btn btn-default">Seleccionar todo</button>
                                <button type="button" id="DeseleccionarPlanesOferta" class="btn btn-default">Deseleccionar todo</button>
                                <button type="button" id="AgregarPlanOferta" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar plan</button>
                            </div>
                        </div>

                        <div id="ResumenPlanesOferta" class="planes-resumen">
                            <span><strong>Planes agregados:</strong> <em id="TotalPlanesOferta">0</em></span>
                            <span><strong>Plan elegido:</strong> <em id="PlanElegidoOfertaTexto">Sin seleccionar</em></span>
                            <input type="hidden" id="PlanElegidoOferta" name="PlanElegidoOferta" value="">
                        </div>

                        <div class="comparativo-wrap">
                            <table id="TablaPlanesOferta" class="table table-bordered tabla-comparativo">
                                <thead>
                                    <tr>
                                        <th>Concepto / Plan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">Seleccione el ramo de la oferta y agregue planes comerciales.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="tab-seguimientos">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Fecha seguimiento</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-7 form-group">
                                <label>Descripcion</label>
                                <input type="text" class="form-control" placeholder="Detalle de gestion realizada">
                            </div>
                            <div class="col-md-2 form-group" style="padding-top: 24px;">
                                <button type="button" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Agregar</button>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered ventas-table-compact">
                            <thead>
                                <tr>
                                    <th width="15%">Fecha</th>
                                    <th width="15%">Usuario</th>
                                    <th width="60%">Seguimiento</th>
                                    <th width="10%">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>30/06/2026</td>
                                    <td>MVIL</td>
                                    <td>SE ENVIA COTIZACION Y SE PROGRAMA RECORDATORIO.</td>
                                    <td>
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-right" style="margin-top: 15px;">
                    <a href="{{ url('ventas/ofertas') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                    <button type="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar oferta</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-cliente-express" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cliente express</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tipo cliente</label>
                                <select class="form-control">
                                    <option>NATURAL</option>
                                    <option>JURIDICO</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>DUI / NIT</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Telefono</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Correo</label>
                                <input type="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Usar cliente</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(function () {
                var planesComercialesOferta = @json($planesComerciales);
                var camposComparativosOferta = @json($camposComparativos);
                var planesSeleccionadosOferta = [];
                var planElegidoOferta = '';

                $('#ClienteBusqueda').on('change', function () {
                    copiarDatosClienteSeleccionado();
                });

                $('#CopiarDatosCliente').on('click', function () {
                    copiarDatosClienteSeleccionado();
                });

                $('#NecesidadProteccionOferta').on('change', function () {
                    planesSeleccionadosOferta = [];
                    planElegidoOferta = '';
                    cargarPlanesComercialesPorRamo();
                    renderizarComparativoPlanes();
                    actualizarResumenPlanesOferta();
                });

                $('#AgregarPlanOferta').on('click', function () {
                    var planId = $('#PlanComercialOferta').val();

                    if (!$('#NecesidadProteccionOferta').val()) {
                        toastr.warning('Seleccione el ramo de la oferta.');
                        return;
                    }

                    if (!planId) {
                        toastr.warning('Seleccione un plan comercial.');
                        return;
                    }

                    var plan = buscarPlanComercial(planId);
                    if (!plan) {
                        toastr.warning('El plan comercial seleccionado no esta disponible.');
                        return;
                    }

                    if (planesSeleccionadosOferta.some(function (item) { return String(item.id) === String(plan.id); })) {
                        toastr.info('El plan ya fue agregado.');
                        return;
                    }

                    planesSeleccionadosOferta.push(plan);
                    cargarPlanesComercialesPorRamo();
                    renderizarComparativoPlanes();
                    actualizarResumenPlanesOferta();
                });

                $('#SeleccionarPlanesOferta').on('click', function () {
                    $('.plan-oferta-check').prop('checked', true);
                });

                $('#DeseleccionarPlanesOferta').on('click', function () {
                    $('.plan-oferta-check').prop('checked', false);
                });

                $('#TablaPlanesOferta').on('click', '.quitar-plan-oferta', function () {
                    var planId = $(this).data('plan-id');

                    planesSeleccionadosOferta = planesSeleccionadosOferta.filter(function (plan) {
                        return String(plan.id) !== String(planId);
                    });

                    if (String(planElegidoOferta) === String(planId)) {
                        planElegidoOferta = '';
                    }

                    cargarPlanesComercialesPorRamo();
                    renderizarComparativoPlanes();
                    actualizarResumenPlanesOferta();
                });

                $('#TablaPlanesOferta').on('change', '.plan-elegido-radio', function () {
                    planElegidoOferta = $(this).val() || '';
                    actualizarResumenPlanesOferta();
                });

                function limpiarDatosCliente() {
                    $('#NombreCliente').val('');
                    $('#TelefonoCliente').val('');
                    $('#CorreoCliente').val('');
                }

                function copiarDatosClienteSeleccionado() {
                    var clienteId = $('#ClienteBusqueda').val();

                    if (!clienteId) {
                        limpiarDatosCliente();
                        return;
                    }

                    var $option = $('#ClienteBusqueda').find('option:selected');

                    $('#NombreCliente').val($option.data('nombre') || '');
                    $('#TelefonoCliente').val($option.data('telefono') || '');
                    $('#CorreoCliente').val($option.data('correo') || '');
                }

                function cargarPlanesComercialesPorRamo() {
                    var ramo = $('#NecesidadProteccionOferta').val();
                    var $select = $('#PlanComercialOferta');

                    $select.empty();

                    if (!ramo) {
                        $select.append(new Option('Seleccione primero un ramo...', '', true, true));
                        $select.trigger('change.select2');
                        return;
                    }

                    var seleccionados = planesSeleccionadosOferta.map(function (plan) {
                        return String(plan.id);
                    });

                    var planesRamo = planesComercialesOferta.filter(function (plan) {
                        return String(plan.ramo) === String(ramo) && seleccionados.indexOf(String(plan.id)) === -1;
                    });

                    $select.append(new Option('Seleccione plan comercial configurado...', '', true, true));

                    planesRamo.forEach(function (plan) {
                        $select.append(new Option(plan.texto || plan.nombre_comercial || ('Plan #' + plan.id), plan.id, false, false));
                    });

                    if (planesRamo.length === 0) {
                        $select.append(new Option('No hay mas planes configurados para este ramo', '', false, false));
                    }

                    $select.val('');
                    $select.trigger('change.select2');
                }

                function renderizarComparativoPlanes() {
                    var ramo = $('#NecesidadProteccionOferta').val();
                    var camposRamo = camposComparativosOferta.filter(function (campo) {
                        return String(campo.ramo) === String(ramo);
                    });
                    var $theadRow = $('#TablaPlanesOferta thead tr');
                    var $tbody = $('#TablaPlanesOferta tbody');

                    $theadRow.html('<th>Concepto / Plan</th>');
                    $tbody.empty();

                    if (!ramo) {
                        $tbody.append('<tr><td class="text-center">Seleccione el ramo de la oferta y agregue planes comerciales.</td></tr>');
                        actualizarResumenPlanesOferta();
                        return;
                    }

                    if (planesSeleccionadosOferta.length === 0) {
                        $tbody.append('<tr><td class="text-center">Agregue uno o mas planes comerciales para armar el comparativo.</td></tr>');
                        actualizarResumenPlanesOferta();
                        return;
                    }

                    planesSeleccionadosOferta.forEach(function (plan) {
                        var checkedElegido = String(planElegidoOferta) === String(plan.id) ? ' checked' : '';
                        $theadRow.append(
                            '<th>' +
                                '<input type="checkbox" class="plan-check plan-oferta-check" data-plan-id="' + escaparHtml(plan.id) + '" checked><br>' +
                                '<strong>' + escaparHtml(plan.texto || plan.nombre_comercial || ('Plan #' + plan.id)) + '</strong><br>' +
                                '<label style="font-weight: normal; margin-top: 4px;">' +
                                    '<input type="radio" name="PlanElegidoOfertaRadio" class="plan-elegido-radio" value="' + escaparHtml(plan.id) + '"' + checkedElegido + '> Elegido' +
                                '</label><br>' +
                                '<button type="button" class="btn btn-danger btn-xs quitar-plan-oferta" data-plan-id="' + escaparHtml(plan.id) + '" title="Quitar plan">' +
                                    '<i class="fa fa-trash"></i>' +
                                '</button>' +
                            '</th>'
                        );
                    });

                    if (camposRamo.length === 0) {
                        $tbody.append('<tr><td class="text-center" colspan="' + (planesSeleccionadosOferta.length + 1) + '">Este ramo no tiene plantilla comparativa configurada.</td></tr>');
                        actualizarResumenPlanesOferta();
                        return;
                    }

                    $tbody.append('<tr class="seccion"><td colspan="' + (planesSeleccionadosOferta.length + 1) + '">Beneficios del Plan</td></tr>');

                    camposRamo.forEach(function (campo) {
                        var fila = '<tr><td class="concepto">' + escaparHtml(campo.etiqueta) + '</td>';

                        planesSeleccionadosOferta.forEach(function (plan) {
                            var valor = plan.valores && plan.valores[campo.id] ? plan.valores[campo.id] : '-';
                            fila += '<td>' + escaparHtml(valor) + '</td>';
                        });

                        fila += '</tr>';
                        $tbody.append(fila);
                    });

                    actualizarResumenPlanesOferta();
                }

                function buscarPlanComercial(planId) {
                    return planesComercialesOferta.find(function (plan) {
                        return String(plan.id) === String(planId);
                    });
                }

                function escaparHtml(valor) {
                    return String(valor || '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function actualizarResumenPlanesOferta() {
                    var planElegido = buscarPlanSeleccionado(planElegidoOferta);

                    $('#TotalPlanesOferta').text(planesSeleccionadosOferta.length);
                    $('#PlanElegidoOferta').val(planElegido ? planElegido.id : '');
                    $('#PlanElegidoOfertaTexto').text(planElegido ? (planElegido.texto || planElegido.nombre_comercial || ('Plan #' + planElegido.id)) : 'Sin seleccionar');
                }

                function buscarPlanSeleccionado(planId) {
                    return planesSeleccionadosOferta.find(function (plan) {
                        return String(plan.id) === String(planId);
                    });
                }

                cargarPlanesComercialesPorRamo();
                renderizarComparativoPlanes();
                actualizarResumenPlanesOferta();
            });
        </script>
    </div>
@endsection
