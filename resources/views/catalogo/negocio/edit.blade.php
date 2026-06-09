@extends ('welcome')
@section('contenido')
    @can('negocio edit')
        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

        <style>
            .negocio-section {
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                padding: 18px;
                margin-bottom: 20px;
                background: #fff;
            }
            .negocio-section h4 {
                margin: 0 0 15px 0;
                font-weight: bold;
                color: #2A3F54;
                border-bottom: 1px solid #edf2f7;
                padding-bottom: 8px;
            }
            .table-modern th {
                background-color: #f5f7fa !important;
                color: #333;
            }
            .help-block-custom {
                display: none;
                color: #a94442;
                font-size: 11px;
                margin-top: 4px;
            }
        </style>

        <div class="x_panel">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="x_title">
                        <h2>Modificar Negocio <small></small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <button class="btn btn-success" type="submit" form="formNegocioEdit" id="btnGuardarNegocio">
                                <i class="fa fa-floppy-o"></i> Guardar cambios
                            </button>
                            <a href="{{ url('catalogo/negocio') }}" class="btn btn-info" style="color: white">
                                <i class="fa fa-arrow-left"></i> Atrás
                            </a>
                            <a href="{{ url('catalogo/negocio/') }}" class="btn btn-primary">
                                <i class="fa fa-times"></i> Cancelar
                            </a>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="{{ session('tab1') == 1 || !session('tab1') ? 'active' : '' }}">
                                <a href="#negocio" id="negocio-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-briefcase"></i> Negocio</a>
                            </li>
                            <li role="presentation" class="{{ session('tab1') == 5 ? 'active' : '' }}">
                                <a href="#cotizaciones" id="cotizaciones-tab" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-calculator"></i> Cotizaciones y Ramo</a>
                            </li>
                            <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}">
                                <a href="#informacion_negocio" role="tab" id="informacion_negocio-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-users"></i> Información del Negocio</a>
                            </li>
                            <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}">
                                <a href="#archivos" role="tab" id="archivos-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-paperclip"></i> Archivos</a>
                            </li>
                            <li role="presentation" class="{{ session('tab1') == 4 ? 'active' : '' }}">
                                <a href="#gestiones" role="tab" id="gestiones-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-history"></i> Gestiones</a>
                            </li>
                        </ul>

                        <div id="myTabContent" class="tab-content" style="padding-top: 10px;">

                            <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 || !session('tab1') ? 'active in' : '' }}" id="negocio">
                                <form id="formNegocioEdit" method="POST" action="{{ route('negocio.update', $negocio->Id) }}">
                                    @method('PUT')
                                    @csrf
                                    <input type="hidden" name="IdCliente" id="IdCliente" value="{{ $negocio->Cliente }}">

                                    <div class="negocio-section">
                                        <h4><i class="fa fa-user"></i> Cliente o prospecto</h4>
                                        <div class="row">
                                            <div class="col-md-2 col-sm-6 form-group">
                                                <label for="IdNegocio" class="control-label">ID negocio</label>
                                                <input type="text" class="form-control" id="IdNegocio" name="IdNegocio" value="{{ $negocio->Id }}" readonly>
                                            </div>
                                            <div class="col-md-3 col-sm-6 form-group">
                                                <label for="TipoPersona" class="control-label">Tipo cliente <span class="text-danger">*</span></label>
                                                <select name="TipoPersona" id="TipoPersona" class="form-control" onchange="identificadorCliente();" required>
                                                    <option value="" selected disabled>Seleccione...</option>
                                                    <option value="1" {{ $negocio->clientes->TipoPersona == 1 ? 'selected' : '' }}>NATURAL</option>
                                                    <option value="2" {{ $negocio->clientes->TipoPersona == 2 ? 'selected' : '' }}>JURIDICO</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="NombreCliente" class="control-label">Nombre del cliente o prospecto</label>
                                                <input class="form-control" readonly type="text" name="NombreCliente" id="NombreCliente" value="{{ $negocio->clientes->Nombre }}">
                                            </div>
                                            <div class="col-md-3 col-sm-12 form-group">
                                                <div id="divDui">
                                                    <label for="Dui" class="control-label">DUI</label>
                                                    <input type="text" name="Dui" id="Dui" value="{{ $negocio->clientes->Dui }}" data-inputmask="'mask': ['99999999-9']" onkeyup="mostrar();" class="form-control">
                                                    <span id="helpBlockDuiNit" class="help-block-custom"><i class="fa fa-info-circle"></i> Este cliente ya existe.</span>
                                                </div>
                                                <div id="divNit">
                                                    <label for="NitEmpresa" class="control-label">NIT empresa</label>
                                                    <input type="text" name="NitEmpresa" id="NitEmpresa" value="{{ $negocio->clientes->Nit }}" data-inputmask="'mask': ['9999-999999-999-9']" onkeyup="mostrar();" class="form-control">
                                                    <span id="helpBlockDuiNit2" class="help-block-custom"><i class="fa fa-info-circle"></i> Este cliente ya existe.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div style="display: none;" class="col-md-4 form-group">
                                                <label for="Email" class="control-label">Email</label>
                                                <input type="email" class="form-control validarCredenciales" name="Email" id="Email" value="{{ $negocio->clientes->CorreoPrincipal }}">
                                            </div>
                                            <div style="display: none;" class="col-md-4 form-group">
                                                <label for="EstadoCliente" class="control-label">Estado cliente</label>
                                                <select disabled name="EstadoCliente" id="EstadoCliente" class="form-control select2">
                                                    <option value="" selected disabled>Seleccione...</option>
                                                    @foreach ($cliente_estado as $obj)
                                                        <option value="{{ $obj->Id }}" {{ $negocio->clientes->Estado == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="negocio-section">
                                        <h4><i class="fa fa-tag"></i> Datos comerciales</h4>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="TipoCarteraNr" class="control-label">Cartera <span class="text-danger">*</span></label>
                                                <select name="TipoCarteraNr" id="TipoCarteraNr" class="form-control select2" style="width: 100%" required>
                                                    <option value="" selected disabled>Seleccione...</option>
                                                    @foreach ($carteras as $obj)
                                                        <option value="{{ $obj->Id }}" {{ $negocio->TipoCarteraNr == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="EstadoVenta" class="control-label">Estado venta <span class="text-danger">*</span></label>
                                                <select name="EstadoVenta" id="EstadoVenta" class="form-control select2" style="width: 100%" required>
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    @foreach ($estados_venta as $obj)
                                                        <option value="{{ $obj->Id }}" {{ $negocio->EstadoVenta == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="NecesidadProteccion" class="control-label">Ramo <span class="text-danger">*</span></label>
                                                <select @if($cotizaciones->count()>0) disabled @endif name="NecesidadProteccion" id="NecesidadProteccion" class="form-control select2" style="width: 100%;" required>
                                                    <option value="">Seleccione...</option>
                                                    @foreach ($necesidad_proteccion as $obj)
                                                        <option value="{{ $obj->Id }}" {{ $negocio->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px;">
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="Ejecutivo" class="control-label">Ejecutivo <span class="text-danger">*</span></label>
                                                <select name="Ejecutivo" id="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                                    <option value="" selected disabled>Seleccione...</option>
                                                    @foreach ($ejecutivos as $obj)
                                                        <option value="{{ $obj->Id }}" {{ $negocio->Ejecutivo == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="TipoNegocio" class="control-label">Tipo de negocio <span class="text-danger">*</span></label>
                                                <select name="TipoNegocio" id="TipoNegocio" class="form-control select2" style="width: 100%" required>
                                                    <option value="" selected disabled>Seleccione...</option>
                                                    @foreach ($tipos_negocio as $obj)
                                                        <option value="{{ $obj->Id }}" {{ $negocio->TipoNegocio == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="DepartamentoNr" class="control-label">Departamento que atenderá <span class="text-danger">*</span></label>
                                                <select name="DepartamentoNr" id="DepartamentoNr" class="form-control select2" style="width: 100%" required>
                                                    <option value="" selected disabled>Seleccione...</option>
                                                    @foreach ($departamentosnr as $obj)
                                                        <option value="{{ $obj->Id }}" {{ $negocio->DepartamentoNr == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="negocio-section">
                                        <h4><i class="fa fa-calendar"></i> Vigencia y pago</h4>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 form-group">
                                                <label for="FechaVenta" class="control-label">Fecha venta</label>
                                                <input type="date" class="form-control" name="FechaVenta" id="FechaVenta" value="{{ $negocio->FechaVenta }}">
                                            </div>
                                            <div class="col-md-3 col-sm-6 form-group">
                                                <label for="InicioVigencia" class="control-label">Inicio vigencia</label>
                                                <input type="date" class="form-control" name="InicioVigencia" id="InicioVigencia" value="{{ $negocio->InicioVigencia }}">
                                            </div>
                                            <div class="col-md-3 col-sm-6 form-group">
                                                <label for="FormaPago" class="control-label">Periodo de pago <span class="text-danger">*</span></label>
                                                <select name="FormaPago" id="FormaPago" class="form-control select2" style="width: 100%" required>
                                                    <option value="" selected disabled>Seleccione...</option>
                                                    <option value="1" {{ $negocio->PeriodoPago == 1 ? 'selected' : '' }}>ANUAL</option>
                                                    <option value="2" {{ $negocio->PeriodoPago == 2 ? 'selected' : '' }}>SEMESTRAL</option>
                                                    <option value="3" {{ $negocio->PeriodoPago == 3 ? 'selected' : '' }}>TRIMESTRAL</option>
                                                    <option value="4" {{ $negocio->PeriodoPago == 4 ? 'selected' : '' }}>MENSUAL</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-6 form-group">
                                                <label for="NumCoutas" class="control-label">Cuotas</label>
                                                <input class="form-control" type="number" step="1" min="0" name="NumCoutas" id="NumCoutas" value="{{ $negocio->NumCoutas }}">
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px;">
                                            <div class="col-md-4 col-sm-12 form-group">
                                                <label for="NumeroPoliza" class="control-label">Número de póliza</label>
                                                <input class="form-control" type="text" value="{{ strtoupper($negocio->NumeroPoliza) }}" name="NumeroPoliza" id="NumeroPoliza"
                                                       oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                            </div>
                                            <div class="col-md-8 col-sm-12 form-group">
                                                <label for="Observacion" class="control-label">Observaciones</label>
                                                <textarea name="Observacion" id="Observacion" rows="2" class="form-control"
                                                          oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">{{ strtoupper($negocio->Observacion) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 5 ? 'active in' : '' }}" id="cotizaciones">

                                <div class="negocio-section">
                                    <h4><i class="fa fa-cogs"></i> Datos generales del ramo</h4>
                                    <p class="text-muted" style="margin-bottom: 15px; font-size: 12px;">
                                        Esta información se captura una sola vez para el negocio y aplica a todas las cotizaciones del ramo.
                                    </p>

                                    <form method="POST" action="{{ url('catalogo/negocio/' . $negocio->Id . '/datos_ramo') }}" id="formDatosRamo">
                                        @csrf
                                        <div class="row" id="camposRamoContainer">
                                            @if ($camposRamo->count() > 0)
                                                @foreach ($camposRamo as $campo)
                                                    @php
                                                        $nombreInputRamo = 'ramo_' . $campo->Id;
                                                        $valorCampoRamo = old($nombreInputRamo, $datosRamo[$campo->Id] ?? '');
                                                        $tipoCampoRamo = $campo->TipoCampo === 'textarea' ? 'textarea' : $campo->TipoCampo;
                                                        $clasesCampoRamo = 'form-control campo-ramo';
                                                        $atributosCampoRamo = '';

                                                        if ($campo->ValidacionCampo === 'dui') {
                                                            $clasesCampoRamo .= ' campo-validacion-dui';
                                                            $atributosCampoRamo .= ' data-inputmask="\'mask\': [\'99999999-9\']" maxlength="10"';
                                                        } elseif ($campo->ValidacionCampo === 'solo_numeros') {
                                                            $clasesCampoRamo .= ' campo-validacion-solo-numeros';
                                                            $atributosCampoRamo .= ' inputmode="numeric"';
                                                        } elseif ($campo->ValidacionCampo === 'solo_numeros_letras') {
                                                            $clasesCampoRamo .= ' campo-validacion-solo-numeros-letras';
                                                        } elseif ($campo->ValidacionCampo === 'solo_texto') {
                                                            $clasesCampoRamo .= ' campo-validacion-solo-texto';
                                                        } elseif ($campo->ValidacionCampo === 'correo') {
                                                            $clasesCampoRamo .= ' campo-validacion-correo';
                                                        }
                                                    @endphp
                                                    <div class="col-md-6 form-group">
                                                        <label for="{{ $nombreInputRamo }}" class="control-label">
                                                            {{ $campo->Etiqueta }}
                                                            @if ($campo->Requerido == 1) <span class="text-danger">*</span> @endif
                                                        </label>
                                                        @if ($tipoCampoRamo === 'textarea')
                                                            <textarea class="{{ $clasesCampoRamo }}" name="{{ $nombreInputRamo }}" id="{{ $nombreInputRamo }}" placeholder="{{ $campo->Placeholder }}" data-validacion="{{ $campo->ValidacionCampo ?? 'ninguna' }}" @if ($campo->Requerido == 1) required @endif>{!! e($valorCampoRamo) !!}</textarea>
                                                        @else
                                                            <input class="{{ $clasesCampoRamo }}" type="{{ $tipoCampoRamo === 'email' ? 'email' : ($tipoCampoRamo === 'number' ? 'number' : ($tipoCampoRamo === 'date' ? 'date' : 'text')) }}" name="{{ $nombreInputRamo }}" id="{{ $nombreInputRamo }}" value="{{ $valorCampoRamo }}" placeholder="{{ $campo->Placeholder }}" data-validacion="{{ $campo->ValidacionCampo ?? 'ninguna' }}" {!! $atributosCampoRamo !!} @if ($campo->TipoCampo === 'number') step="any" @endif @if ($campo->Requerido == 1) required @endif>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-12" id="sinCamposRamoMensaje">
                                                    <div class="alert alert-info" style="margin-bottom: 0;">
                                                        El ramo seleccionado no tiene campos dinámicos configurados.
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right" style="margin-top: 15px; border-top: 1px solid #edf2f7; padding-top: 15px;">
                                            <button type="button" class="btn btn-default btn-sm" id="btnLimpiarDatosRamo"><i class="fa fa-eraser"></i> Limpiar</button>
                                            <button type="submit" class="btn btn-success btn-sm" id="btnGuardarDatosRamo"><i class="fa fa-floppy-o"></i> Guardar datos del ramo</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="negocio-section">
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-md-6"><h4><i class="fa fa-calculator"></i> Cotizaciones</h4></div>
                                        <div class="col-md-6 text-right">
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-modal-nuevo-cotizacion">
                                                <i class="fa fa-plus"></i> Nueva Cotización
                                            </button>
                                        </div>
                                    </div>

                                    @if ($cotizaciones->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-modern table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">N°</th>
                                                    <th>Producto</th>
                                                    <th>Plan</th>
                                                    <th>Suma Asegurada</th>
                                                    <th>Prima Neta Anual</th>
                                                    <th class="text-center" style="width: 90px;">Aceptado</th>
                                                    <th class="text-center" style="width: 100px;">Acciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($cotizaciones as $obj)
                                                    <tr>
                                                        <td class="text-center">{{$loop->iteration}}</td>
                                                        <td><strong>{{ $obj->planes->productos->Nombre ?? 'N/A' }}</strong></td>
                                                        <td>{{ $obj->planes->Nombre ?? 'N/A'}}</td>
                                                        <td>${{ number_format($obj->SumaAsegurada, 2, '.', ',') }}</td>
                                                        <td>${{ number_format($obj->PrimaNetaAnual, 2, '.', ',') }}</td>
                                                        <td class="text-center">
                                                            <input onclick="cotizacionAprobada({{ $obj->Id }},{{$negocio->Id}})" class="grupoCheckBoxAceptado" type="checkbox" @if ($obj->Aceptado) checked @endif style="cursor: pointer;">
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group">
                                                                <button class="btn btn-default" onclick='modal_edit_cotizacion(@json($obj->Id), @json($obj->planes->productos->Nombre ?? "N/A"), @json($obj->planes->Nombre ?? "N/A"), @json($obj->SumaAsegurada), @json($obj->PrimaNetaAnual), @json($obj->Observaciones))' data-target="#modal-edit-cotizacion" data-toggle="modal"><i class="fa fa-pencil text-primary"></i></button>
                                                                <button class="btn btn-default" onclick="modal_delete_cotizacion({{ $obj->Id }})" data-target="#modal-delete-cotizacion" data-toggle="modal"><i class="fa fa-trash text-danger"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" style="margin-top: 10px; margin-bottom: 0;">
                                            <strong>Sin datos que mostrar.</strong> No hay cotizaciones cargadas todavía.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}" id="informacion_negocio">
                                <div class="negocio-section">
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-md-6"><h4><i class="fa fa-users"></i> Contactos Operativos</h4></div>
                                        <div class="col-md-6 text-right">
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-modal-nuevo-informacion_negocio">
                                                <i class="fa fa-plus"></i> Nuevo Contacto
                                            </button>
                                        </div>
                                    </div>

                                    @if ($contactosNegocio->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-modern table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">N°</th>
                                                    <th>Contacto</th>
                                                    <th>Descripción de la Operación</th>
                                                    <th>Teléfonos Contacto</th>
                                                    <th>Observaciones</th>
                                                    <th class="text-center" style="width: 100px;">Acciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($contactosNegocio as $obj)
                                                    <tr>
                                                        <td class="text-center">{{$loop->iteration}}</td>
                                                        <td><strong>{{ $obj->Contacto }}</strong></td>
                                                        <td>{{ $obj->DescripcionOperacion }}</td>
                                                        <td>{{ $obj->TelefonoContacto }}</td>
                                                        <td>{{ $obj->ObservacionContacto }}</td>
                                                        <td class="text-center">
                                                            <div class="btn-group">
                                                                <button class="btn btn-default btn-sm" onclick="modal_edit_informacion_negocio({{ $obj->id }},'{{ $obj->Contacto }}','{{ $obj->DescripcionOperacion }}','{{ $obj->TelefonoContacto }}','{{ $obj->ObservacionContacto }}')" data-target=".bs-modal-edit-informacion_negocio" data-toggle="modal"><i class="fa fa-pencil text-primary"></i></button>
                                                                <button class="btn btn-default btn-sm" onclick="modal_delete_informacion_negocio({{ $obj->id }})" data-target="#modal-delete-informacion_negocio" data-toggle="modal"><i class="fa fa-trash text-danger"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" style="margin-top: 10px; margin-bottom: 0;">
                                            <strong>Sin datos que mostrar.</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}" id="archivos">
                                <div class="negocio-section">
                                    <h4><i class="fa fa-upload"></i> Subir Documentos</h4>
                                    <form id="FormArchivo" action="{{ url('catalogo/negocio/documento') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                        @csrf
                                        <input type="hidden" value="{{$negocio->Id}}" name="Negocio">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Archivo digital:</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control" name="Archivo" type="file" required style="height: auto;">
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> Adjuntar</button>
                                            </div>
                                        </div>
                                    </form>

                                    <hr>

                                    <h4><i class="fa fa-folder-open"></i> Archivos cargados</h4>
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <table class="table table-striped table-bordered table-modern">
                                                <thead>
                                                <tr>
                                                    <th>Nombre del Documento</th>
                                                    <th class="text-center" style="width: 100px;">Opciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($documentos as $obj)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ asset('documentos/negocios') }}/{{$obj->Nombre}}" class="btn btn-default btn-xs" target="_blank" style="text-align: left; display: block;">
                                                                <i class="fa fa-download text-success"></i> &nbsp; {{$obj->NombreOriginal}}
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-default btn-xs" data-target="#modal-delete-documento-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash text-danger"></i></button>
                                                        </td>
                                                    </tr>

                                                    <div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-documento-{{ $obj->Id }}">
                                                        <form method="POST" action="{{ url('catalogo/negocio/documento_eliminar', $obj->Id) }}">
                                                            @csrf
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Eliminar Archivo</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Confirme si desea eliminar el archivo <strong>{{$obj->NombreOriginal}}</strong></p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 4 ? 'active in' : '' }}" id="gestiones">
                                <div class="negocio-section">
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-md-6"><h4><i class="fa fa-history"></i> Historial de Gestiones</h4></div>
                                        <div class="col-md-6 text-right">
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-modal-nuevo-gestion">
                                                <i class="fa fa-plus"></i> Nueva Gestión
                                            </button>
                                        </div>
                                    </div>

                                    @if ($gestiones->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-modern table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">N°</th>
                                                    <th>Descripción de la actividad</th>
                                                    <th>Usuario</th>
                                                    <th class="text-center" style="width: 160px;">Fecha y Hora</th>
                                                    <th class="text-center" style="width: 100px;">Acciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($gestiones as $obj)
                                                    <tr>
                                                        <td class="text-center">{{$loop->iteration}}</td>
                                                        <td>{{ $obj->DescripcionActividad }}</td>
                                                        <td><span class="label label-info">{{ $obj->usuarios->name }}</span></td>
                                                        <td class="text-center">{{ date('d/m/Y H:i:s', strtotime($obj->FechaHora))}}</td>
                                                        <td class="text-center">
                                                            <div class="btn-group">
                                                                <button class="btn btn-default btn-xs" onclick="modal_edit_gestion({{ $obj->Id }},'{{ $obj->DescripcionActividad }}')" data-target=".bs-modal-edit-gestion" data-toggle="modal"><i class="fa fa-pencil text-primary"></i></button>
                                                                <button class="btn btn-default btn-xs" onclick="modal_delete_gestion({{ $obj->Id }})" data-target="#modal-delete-gestion" data-toggle="modal"><i class="fa fa-trash text-danger"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" style="margin-top: 10px; margin-bottom: 0;">
                                            <strong>Sin datos que mostrar.</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade bs-modal-nuevo-cotizacion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/negocio/add_cotizacion') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Nueva cotización</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Negocio" value="{{$negocio->Id}}">
                            <div class="alert alert-info">
                                Los datos generales del ramo se capturan en la pestaña <strong>Negocio</strong> y aplican a todas las cotizaciones.
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Producto">Producto <span class="text-danger">*</span></label>
                                    <select name="Producto" id="Producto" class="form-control select2" style="width: 100%" required>
                                        <option value="" disabled selected>Seleccione...</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Plan">Plan <span class="text-danger">*</span></label>
                                    <select name="Plan" id="Plan" class="form-control select2" style="width: 100%" required>
                                        <option value="" disabled selected>Seleccione...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="SumaAsegurada">Suma Asegurada</label>
                                    <input class="form-control" type="number" name="SumaAsegurada" step="0.01" id="SumaAsegurada">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="PrimaNetaAnual">Prima Neta Anual</label>
                                    <input class="form-control" step="0.01" type="number" name="PrimaNetaAnual" id="PrimaNetaAnual">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="Observaciones">Observaciones o comentarios</label>
                                    <textarea name="Observaciones" id="Observaciones" rows="3" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade bs-modal-edit-cotizacion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" id="modal-edit-cotizacion">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/negocio/edit_cotizacion') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modificación cotización</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Negocio" value="{{$negocio->Id}}">
                            <input type="hidden" name="Id" id="ModalCotizacionId" required>
                            <div class="alert alert-info">
                                Los datos generales del ramo se capturan en la pestaña <strong>Negocio</strong> y aplican a todas las cotizaciones.
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="ModalCotizacionProducto">Producto</label>
                                    <select disabled id="ModalCotizacionProducto" class="form-control" style="width: 100%"></select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ModalCotizacionPlan">Plan</label>
                                    <select disabled id="ModalCotizacionPlan" class="form-control" style="width: 100%"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="ModalCotizacionSumaAsegurada">Suma Asegurada</label>
                                    <input class="form-control" type="number" name="SumaAsegurada" step="0.01" id="ModalCotizacionSumaAsegurada">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ModalCotizacionPrimaNetaAnual">Prima Neta Anual</label>
                                    <input class="form-control" step="0.01" type="number" name="PrimaNetaAnual" id="ModalCotizacionPrimaNetaAnual">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="ModalCotizacionObservaciones">Observaciones o comentarios</label>
                                    <textarea name="Observaciones" id="ModalCotizacionObservaciones" rows="3" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-cotizacion">
            <form method="POST" action="{{ url('catalogo/negocio/delete_cotizacion') }}">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Eliminar Cotización</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Id" id="IdCotizacion">
                            <p>¿Confirme si desea eliminar el registro de cotización?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger">Confirmar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade bs-modal-nuevo-informacion_negocio" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/negocio/add_informacion_negocio') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Nuevo Contacto</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Negocio" value="{{$negocio->Id}}">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Contacto">Contacto <span class="text-danger">*</span></label>
                                    <input type="text" name="Contacto" id="Contacto" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="DescripcionOperacion">Descripción de la Operación <span class="text-danger">*</span></label>
                                    <input type="text" name="DescripcionOperacion" id="DescripcionOperacion" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="TelefonoContacto">Teléfono de Contacto <span class="text-danger">*</span></label>
                                    <input type="text" name="TelefonoContacto" id="TelefonoContacto" class="form-control" data-inputmask="'mask': ['9999-9999']" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ObservacionContacto">Observación del Contacto</label>
                                    <textarea class="form-control" name="ObservacionContacto" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" id="ObservacionContacto" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade bs-modal-edit-informacion_negocio" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/negocio/edit_informacion_negocio') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Editar Contacto</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Negocio" value="{{$negocio->Id}}">
                            <input type="hidden" name="Id" id="ModalContactoId" required>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="ModalContactoContacto">Contacto <span class="text-danger">*</span></label>
                                    <input type="text" name="Contacto" id="ModalContactoContacto" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ModalContactoDescripcionOperacion">Descripción de la Operación <span class="text-danger">*</span></label>
                                    <input type="text" name="DescripcionOperacion" id="ModalContactoDescripcionOperacion" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="ModalContactoTelefonoContacto">Teléfono de Contacto <span class="text-danger">*</span></label>
                                    <input type="text" name="TelefonoContacto" id="ModalContactoTelefonoContacto" class="form-control" data-inputmask="'mask': ['9999-9999']" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ModalContactoObservacionContacto">Observación del Contacto</label>
                                    <textarea class="form-control" name="ObservacionContacto" id="ModalContactoObservacionContacto" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-informacion_negocio">
            <form method="POST" action="{{ url('catalogo/negocio/delete_informacion_negocio') }}">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Eliminar Contacto</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Id" id="IdContacto">
                            <p>¿Confirme si desea eliminar el registro de contacto?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade bs-modal-nuevo-gestion" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/negocio/add_gestion') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Nueva gestión</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Negocio" value="{{$negocio->Id}}">
                            <div class="form-group">
                                <label for="DescripcionActividad">Descripción de la actividad <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="DescripcionActividad" id="DescripcionActividad" rows="4" required oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade bs-modal-edit-gestion" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/negocio/edit_gestion') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modificar gestión</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Negocio" value="{{$negocio->Id}}">
                            <input type="hidden" name="Id" id="ModalGestionId" required>
                            <div class="form-group">
                                <label for="ModalGestionDescripcionActividad">Descripción de la actividad <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="DescripcionActividad" id="ModalGestionDescripcionActividad" rows="4" required oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-gestion">
            <form method="POST" action="{{ url('catalogo/negocio/delete_gestion') }}">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Eliminar Gestión</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Id" id="IdGestion">
                            <p>¿Confirme si desea eliminar el registro de gestión?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        </div>

        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script type="text/javascript">
            $(".validarCredenciales").on("input", function() {
                let Dui = $('#Dui').val().replace(/[^\d]/g, '');
                let NitEmpresa = $('#NitEmpresa').val().replace(/[^\d]/g, '');
                let campo = '';
                if ($('#TipoPersona').val() !== null) {
                    if ($('#TipoPersona').val() === '1') {
                        campo = 'DUI';
                    } else {
                        campo = 'NIT Empresa';
                    }

                    if (Dui.length !== 9 && NitEmpresa.length !== 14) {
                        Swal.fire({
                            title: 'Advertencia',
                            text: 'Debe de rellenar el campo de ' + campo + ' antes de continuar con los demás campos',
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        })
                        $('#NombreCliente').val('');
                        $('#Email').val('');
                    }
                } else {
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'Debe seleccionar el Tipo Persona',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    })
                    $('#NombreCliente').val('');
                    $('#Email').val('');
                }
            });

            function borrarDatosCliente() {
                $('#NombreCliente').val('');
                $('#Email').val('');
                $("#EstadoCliente").val(3).trigger("change");
                $("#divDui").removeClass("has-error");
                $("#divNit").removeClass("has-error");
                $('#helpBlockDuiNit').hide();
                $('#helpBlockDuiNit2').hide();
            }

            function identificadorCliente() {
                $('#Dui').val('');
                $('#NitEmpresa').val('');
                $('#IdCliente').val('');
                borrarDatosCliente();
                if ($('#TipoPersona').val() == 1) {
                    $('#divDui').show(); $('#divNit').hide();
                    $('#Dui').prop('required', true); $('#NitEmpresa').removeAttr('required');
                } else {
                    $('#divDui').hide(); $('#divNit').show();
                    $('#Dui').removeAttr('required'); $('#NitEmpresa').prop('required', true);
                }
            }

            function mostrar() {
                if ($('#TipoPersona').val() === null) {
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'Debe seleccionar el Tipo Persona',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar',
                        timer: 3500
                    })
                    $('#Dui').val(''); $('#NitEmpresa').val('');
                } else {
                    let Dui = $('#Dui').val();
                    let Nit = $('#NitEmpresa').val();
                    let tipoPersona = $('#TipoPersona').val();
                    let parametros = { "IdCliente": null, "Dui": Dui, "Nit": Nit, "tipoPersona": tipoPersona };

                    $.ajax({
                        type: "get",
                        url: "{{ URL::to('negocio/getCliente') }}",
                        data: parametros,
                        success: function(data) {
                            $('#IdCliente').val('');
                            borrarDatosCliente();
                            if (data.cliente !== null) {
                                $('#IdCliente').val(data.cliente.Id);
                                $('#NombreCliente').val(data.cliente.Nombre);
                                $('#Email').val(data.cliente.CorreoPrincipal);
                                if (data.cliente.Estado===2) {
                                    $("#EstadoCliente").val(3).trigger("change");
                                }else{
                                    $("#EstadoCliente").val(data.cliente.Estado).trigger("change");
                                }

                                $("#divDui").addClass("has-error");
                                $("#divNit").addClass("has-error");
                                $('#helpBlockDuiNit').show();
                                $('#helpBlockDuiNit2').show();
                            }
                        }
                    });
                }
            }

            function borrarProductos() {
                $("#Producto").find("option:not(:first-child)").remove();
                $("#Producto").val(null).trigger("change");
                $("#Plan").find("option:not(:first-child)").remove();
                $("#Plan").val(null).trigger("change");
            }

            function borrarPlanes() {
                $("#Plan").find("option:not(:first-child)").remove();
                $("#Plan").val(null).trigger("change");
            }

            function aplicarMascaraCamposRamo() {
                if ($.fn.inputmask) { $('.campo-validacion-dui').inputmask(); }
            }

            function getCamposRamo(valores) {
                let ramo = $('#NecesidadProteccion').val();

                $.ajax({
                    type: "get",
                    url: "{{ URL::to('negocio/getCamposRamo') }}",
                    data: { "Ramo": ramo },
                    success: function(response) {
                        let contenedor = $("#camposRamoContainer");
                        contenedor.empty();

                        if (!response.campos || response.campos.length === 0) {
                            contenedor.append('<div class="col-md-12" id="sinCamposRamoMensaje"><div class="alert alert-info" style="margin-bottom:0;">El ramo seleccionado no tiene campos dinamicos configurados.</div></div>');
                            return;
                        }

                        $.each(response.campos, function(index, campo) {
                            let tipo = campo.TipoCampo || 'text';
                            let nombre = 'ramo_' + campo.Id;
                            let valorSeguro = valores[campo.Id] || '';
                            let div = $("<div>", { class: "col-md-6 form-group" });
                            let label = $("<label>", { for: nombre, class: "control-label" }).text(campo.Etiqueta);
                            let input;

                            if (campo.Requerido == 1) {
                                label.append(' <span class="text-danger">*</span>');
                            }

                            if (tipo === 'textarea') {
                                input = $("<textarea>", { class: "form-control campo-ramo", name: nombre, id: nombre, placeholder: campo.Placeholder || '', required: campo.Requerido == 1 }).val(valorSeguro);
                            } else {
                                let inputType = tipo === 'email' ? 'email' : (tipo === 'number' ? 'number' : (tipo === 'date' ? 'date' : 'text'));
                                input = $("<input>", { class: "form-control campo-ramo", type: inputType, name: nombre, id: nombre, placeholder: campo.Placeholder || '', required: campo.Requerido == 1 }).val(valorSeguro);
                                if (tipo === 'number') { input.attr('step', 'any'); }
                            }

                            div.append(label, input);
                            contenedor.append(div);
                        });
                        aplicarMascaraCamposRamo();
                    }
                });
            }

            function getProducto() {
                let Ramo = $('#NecesidadProteccion').val();
                $.ajax({
                    type: "get",
                    url: "{{ URL::to('negocio/getProducto') }}",
                    data: { "Ramo": Ramo },
                    success: function(response) {
                        borrarProductos();
                        if (response.datosRecibidos !== null) {
                            $.each(response.datosRecibidos, function(index, datos) {
                                $("#Producto").append(new Option(datos.Nombre, datos.Id, false, false));
                            });
                            $("#Producto").trigger("change");
                        }
                    }
                });
            }

            function getPlan() {
                let Producto = $('#Producto').val();
                $.ajax({
                    type: "get",
                    url: "{{ URL::to('negocio/getPlan') }}",
                    data: { "Producto": Producto },
                    success: function(response) {
                        borrarPlanes();
                        if (response.datosRecibidos !== null) {
                            $.each(response.datosRecibidos, function(index, datos) {
                                $("#Plan").append(new Option(datos.Nombre, datos.Id, false, false));
                            });
                            $("#Plan").trigger("change");
                        }
                    }
                });
            }

            function modal_edit_cotizacion(Id, NombreProducto, NombrePlan, SumaAsegurada, PrimaNetaAnual, Observaciones) {
                $("#ModalCotizacionProducto option").remove();
                $("#ModalCotizacionPlan option").remove();
                $("#ModalCotizacionProducto").append(new Option(NombreProducto, '', false, false)).trigger("change");
                $("#ModalCotizacionPlan").append(new Option(NombrePlan, '', false, false)).trigger("change");

                $('#ModalCotizacionId').val(Id);
                $('#ModalCotizacionSumaAsegurada').val(Number(SumaAsegurada).toFixed(2));
                $('#ModalCotizacionPrimaNetaAnual').val(Number(PrimaNetaAnual).toFixed(2));
                $('#ModalCotizacionObservaciones').val((Observaciones || '').toString().toUpperCase());
            }

            function cotizacionAprobada(Id, Negocio) {
                $.ajax({
                    type: "get",
                    url: "{{ URL::to('negocio/elegirCotizacion') }}",
                    data: { "CotizacionId": Id, "Negocio": Negocio }
                });
            }

            function modal_delete_cotizacion(id) { $('#IdCotizacion').val(id); }
            function modal_edit_informacion_negocio(Id, Contacto, DescripcionOperacion, TelefonoContacto, ObservacionContacto) {
                $('#ModalContactoId').val(Id);
                $('#ModalContactoContacto').val(Contacto.toUpperCase());
                $('#ModalContactoDescripcionOperacion').val(DescripcionOperacion.toUpperCase());
                $('#ModalContactoTelefonoContacto').val(TelefonoContacto);
                $('#ModalContactoObservacionContacto').val(ObservacionContacto.toUpperCase());
            }
            function modal_delete_informacion_negocio(Id) { $('#IdContacto').val(Id); }
            function modal_edit_gestion(Id, DescripcionActividad) {
                $('#ModalGestionId').val(Id);
                $('#ModalGestionDescripcionActividad').val(DescripcionActividad.toUpperCase());
            }
            function modal_delete_gestion(Id) { $('#IdGestion').val(Id); }

            function tipoPersona() {
                if ($('#TipoPersona').val() == 1) {
                    $('#divDui').show(); $('#divNit').hide();
                    $('#Dui').prop('required', true); $('#NitEmpresa').removeAttr('required');
                } else {
                    $('#divDui').hide(); $('#divNit').show();
                    $('#Dui').removeAttr('required'); $('#NitEmpresa').prop('required', true);
                }
            }

            $(document).ready(function() {
                $("#opcionNegocio").addClass("current-page");
                $("#botonMenuNegocio").addClass("active");
                $("#menuNegocio").css("display", "block");

                $('#divNit').hide();
                $('#helpBlockDuiNit').hide();
                $('#helpBlockDuiNit2').hide();
                $("#EstadoCliente").val(3).trigger("change");

                getProducto();
                tipoPersona();

                $("#NecesidadProteccion").change(function() { getProducto(); getCamposRamo({}); });
                $("#Producto").change(function() { getPlan(); });
                $("#TipoPersona").change(function() { tipoPersona(); });

                $('#formNegocioEdit').on('submit', function() {
                    $('#btnGuardarNegocio').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
                });

                $('#formDatosRamo').on('submit', function() {
                    $('#btnGuardarDatosRamo').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
                });

                $('#btnLimpiarDatosRamo').on('click', function() {
                    $('#formDatosRamo').find('input.campo-ramo, textarea.campo-ramo').val('');
                });

                $('.grupoCheckBoxAceptado').change(function() {
                    if ($(this).is(':checked')) { $('.grupoCheckBoxAceptado').not(this).prop('checked', false); }
                });
            });
        </script>
    @else
        <p class="text-center text-danger" style="margin-top: 30px;">No tiene permiso para editar.</p>
    @endcan
@endsection
