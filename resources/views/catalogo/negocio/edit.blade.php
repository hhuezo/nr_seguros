@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Modificar Negocio <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{url('catalogo/negocio')}}" class="btn btn-info fa fa-undo " style="color: white">
                        Atrás</a>
                </ul>
                <div class="clearfix"></div>
            </div>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#negocio"
                            id="negocio-tab" role="tab" data-toggle="tab" aria-expanded="true">Negocio</a>

                    </li>
                    <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a
                            href="#informacion_negocio" role="tab" id="informacion_negocio-tab" data-toggle="tab"
                            aria-expanded="false">Información del Negocio</a>
                    </li>

                    <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}"><a href="#archivos"
                            role="tab" id="archivos-tab" data-toggle="tab" aria-expanded="false">Archivos</a>
                    </li>

                    <li role="presentation" class="{{ session('tab1') == 4 ? 'active' : '' }}"><a href="#gestiones"
                            role="tab" id="gestiones-tab" data-toggle="tab" aria-expanded="false">Gestiones</a>
                    </li>

                </ul>


                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} "
                        id="negocio" aria-labelledby="home-tab">

                        <form method="POST" action="{{ route('negocio.update', $negocio->Id) }}">
                            @method('PUT')
                            @csrf

                            <div class="x_content">
                                <br />
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="TipoCarteraNr" class="form-label">Cartera</label>
                                            <select name="TipoCarteraNr" id="TipoCarteraNr" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($carteras as $obj)
                                                <option value="{{ $obj->Id }}" {{ $negocio->TipoCarteraNr == $obj->Id ?
                                                    'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="EstadoVenta" class="form-label">Estado Venta</label>
                                            <select name="EstadoVenta" id="EstadoVenta" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($estados_venta as $obj)
                                                <option value="{{ $obj->Id }}" {{ $negocio->EstadoVenta == $obj->Id ?
                                                    'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="TipoPersona" class="form-label">Tipo Cliente</label>
                                            <select name="TipoPersona" id="TipoPersona" class="form-control"
                                                onchange="identificadorCliente();">
                                                <option value="" selected disabled>Seleccione ...</option>
                                                <option value="1" {{ $negocio->clientes->TipoPersona == 1 ? 'selected' :
                                                    '' }}>Natural</option>
                                                <option value="2" {{ $negocio->clientes->TipoPersona == 2 ? 'selected' :
                                                    '' }}>Juridico</option>
                                            </select>
                                        </div>
                                        <div id="divDui" class="col-md-6">
                                            <label for="Dui" class="form-label">DUI </label>
                                            <input type="text" name="Dui" id="Dui" value="{{$negocio->clientes->Dui}}"
                                                data-inputmask="'mask': ['99999999-9']" onkeyup="mostrar();"
                                                class="form-control">
                                            <span id="helpBlockDuiNit" class="help-block">Este cliente ya
                                                existe.</span>
                                        </div>
                                        <div id="divNit" class="col-md-6">
                                            <label for="NitEmpresa" class="form-label">NIT Empresa </label>
                                            <input type="text" name="NitEmpresa" id="NitEmpresa"
                                                value="{{$negocio->clientes->Nit}}"
                                                data-inputmask="'mask': ['9999-999999-999-9']" onkeyup="mostrar();"
                                                class="form-control">
                                            <span id="helpBlockDuiNit2" class="help-block">Este cliente ya
                                                existe.</span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="NombreCliente" class="form-label">Nombre del cliente O
                                                Prospecto</label>
                                            <input class="form-control validarCredenciales" type="text"
                                                value="{{$negocio->clientes->Nombre}}" name="NombreCliente"
                                                id="NombreCliente">
                                        </div>

                                        <div style="display: none;" class="col-md-4">
                                            <label for="Email" class="form-label">Email</label>
                                            <input type="email" class="form-control validarCredenciales" name="Email"
                                                id="Email" value="{{$negocio->clientes->CorreoPrincipal}}">
                                        </div>
                                        <div style="display: none;" class="col-md-4">
                                            <label for="EstadoCliente" class="form-label">Estado Cliente</label>
                                            <select disabled name="EstadoCliente" id="EstadoCliente"
                                                class="form-control select2">
                                                <option value="" selected disabled> Seleccione...</option>
                                                @foreach ($cliente_estado as $obj)
                                                <option value="{{ $obj->Id }}" {{ $negocio->clientes->Estado == $obj->Id
                                                    ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--  <div class="col-md-4">
                                            <label for="MetodoPago" class="form-label">Método de
                                                pago</label>
                                            <select name="MetodoPago" id="MetodoPago"
                                                class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>

                                            </select>
                                        </div>-->
                                        <div class="col-md-6">
                                            <label for="NecesidadProteccion" class="form-label">Ramo</label>
                                            <select @if($cotizaciones->count()>0) disabled @endif
                                                name="NecesidadProteccion" id="NecesidadProteccion"
                                                class="form-control select2" style="width: 100%;" required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($necesidad_proteccion as $obj)
                                                <option value="{{ $obj->Id }}" {{ $negocio->NecesidadProteccion ==
                                                    $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="Ejecutivo" class="form-label">Ejecutivo</label>
                                            <select name="Ejecutivo" id="Ejecutivo" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($ejecutivos as $obj)
                                                <option value="{{ $obj->Id }}" {{ $negocio->Ejecutivo == $obj->Id ?
                                                    'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="TipoNegocio" class="form-label">Tipo de negocio</label>
                                            <select name="TipoNegocio" id="TipoNegocio" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($tipos_negocio as $obj)
                                                <option value="{{ $obj->Id }}" {{ $negocio->TipoNegocio == $obj->Id ?
                                                    'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="FechaVenta" class="form-label">Fecha Venta</label>
                                            <input type="date" pattern="\d{2}\s\w+\s\d{4}" class="form-control"
                                                name="FechaVenta" id="FechaVenta" value="{{$negocio->FechaVenta}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="NumeroPoliza" class="form-label">Número de póliza</label>
                                            <input class="form-control" type="text" value="{{$negocio->NumeroPoliza}}"
                                                name="NumeroPoliza" id="NumeroPoliza">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="InicioVigencia" class="form-label">Fecha inicio Vigencia</label>
                                            <input type="date" pattern="\d{2}\s\w+\s\d{4}" class="form-control"
                                                name="InicioVigencia" id="InicioVigencia"
                                                value="{{$negocio->InicioVigencia}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="FormaPago" class="form-label">Periodo de Pago (Forma de
                                                Pago)</label>
                                            <select name="FormaPago" id="FormaPago" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                <option value="1" {{ $negocio->PeriodoPago == 1 ? 'selected' : ''
                                                    }}>Anual</option>
                                                <option value="2" {{ $negocio->PeriodoPago == 2 ? 'selected' : ''
                                                    }}>Semestral</option>
                                                <option value="3" {{ $negocio->PeriodoPago == 3 ? 'selected' : ''
                                                    }}>Trimestral</option>
                                                <option value="4" {{ $negocio->PeriodoPago == 4 ? 'selected' : ''
                                                    }}>Mensual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="DepartamentoNr" class="form-label">Departamento que
                                                atenderá</label>
                                            <select name="DepartamentoNr" id="DepartamentoNr"
                                                class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($departamentosnr as $obj)
                                                <option value="{{ $obj->Id }}" {{ $negocio->DepartamentoNr == $obj->Id ?
                                                    'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="NumCoutas" class="form-label">Cuotas</label>
                                            <input class="form-control" type="number" step="1" name="NumCoutas"
                                                id="NumCoutas" value="{{$negocio->NumCoutas}}">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px !important;">
                                        <div class="col-md-12">
                                            <label for="Observacion" class="form-label">Observaciones o
                                                anotaciones</label>
                                            <textarea name="Observacion" id="Observacion" rows="3"
                                                class="form-control"> {{$negocio->Observacion}}</textarea>

                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="form-group" align="center">
                                <button class="btn btn-success" type="submit">Modificar</button>
                                <a href="{{ url('catalogo/negocio/') }}"><button class="btn btn-primary"
                                        type="button">Cancelar</button></a>
                            </div>

                        </form>
                        <div>
                            <h4>Cotizaciones</h4>
                            <hr>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target=".bs-modal-nuevo-cotizacion"><i class="fa fa-plus fa-lg"></i>
                                Nueva Cotización</button>
                        </div>
                        @if ($cotizaciones->count() > 0)
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Producto</th>
                                    <th>Plan</th>
                                    <th>Suma Asegurada</th>
                                    <th>Prima Neta Anual</th>
                                    <th>Aceptado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cotizaciones as $obj)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $obj->planes->productos->Nombre ?? 'N/A' }}</td>
                                    <td>{{ $obj->planes->Nombre ?? 'N/A'}}</td>
                                    <td>${{ number_format($obj->SumaAsegurada, 2, '.', ',') }}</td>
                                    <td>${{ number_format($obj->PrimaNetaAnual, 2, '.', ',') }}</td>
                                    <td align="center"> <input
                                            onclick="cotizacionAprobada({{ $obj->Id }},{{$negocio->Id}})"
                                            class="grupoCheckBoxAceptado" type="checkbox" @if ($obj->Aceptado) checked
                                        @endif></td>
                                    <td>
                                        <i class="fa fa-pencil fa-lg"
                                            onclick="modal_edit_cotizacion({{ $obj->Id }},'{{  $nombreProducto=$obj->planes->productos->Nombre ?? 'N/A' }}','{{ $obj->planes->Nombre ?? 'N/A' }}','{{ $obj->SumaAsegurada }}','{{ $obj->PrimaNetaAnual }}','{{ $obj->Observaciones }}','{{ $obj->DatosTecnicos }}','{{$obj->planes->productos->datosTecnicos ?? ''}}')"
                                            data-target="#modal-edit-cotizacion" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_cotizacion({{ $obj->Id }})"
                                            data-target="#modal-delete-cotizacion" data-toggle="modal"></i>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}"
                        id="informacion_negocio" aria-labelledby="home-tab">
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target=".bs-modal-nuevo-informacion_negocio"><i class="fa fa-plus fa-lg"></i>
                                Nuevo Contacto</button>
                        </div>
                        @if ($contactosNegocio->count() > 0)
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Contacto</th>
                                    <th>Descripción de la Operación</th>
                                    <th>Teléfonos Contacto</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contactosNegocio as $obj)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $obj->Contacto }}</td>
                                    <td>{{ $obj->DescripcionOperacion }}</td>
                                    <td>{{ $obj->TelefonoContacto }}</td>
                                    <td>{{ $obj->ObservacionContacto }}</td>
                                    <td>
                                        <i class="fa fa-pencil fa-lg"
                                            onclick="modal_edit_informacion_negocio({{ $obj->id }},'{{ $obj->Contacto }}','{{ $obj->DescripcionOperacion }}','{{ $obj->TelefonoContacto }}','{{ $obj->ObservacionContacto }}')"
                                            data-target=".bs-modal-edit-informacion_negocio" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg"
                                            onclick="modal_delete_informacion_negocio({{ $obj->id }})"
                                            data-target="#modal-delete-informacion_negocio" data-toggle="modal"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}"
                        id="archivos" aria-labelledby="home-tab">

                        <form id="FormArchivo" action="{{ url('catalogo/negocio/documento') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$negocio->Id}}" name="Negocio">
                            <div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Archivo</label>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Archivo" type="file" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="clearfix"></div>
                            <div align="center">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Aceptar</button>
                            </div>
                        </form>
                        <br>
                        <div class="col-md-12">
                            <div class="col-md-2"> &nbsp;</div>
                            <div class="col-md-8">
                                <table class=" table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th style="width: 25%;" style="text-align: center;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documentos as $obj)

                                        <tr>
                                            <td><a href="{{ asset('documentos/negocios') }}/{{$obj->Nombre}}"
                                                    class="btn btn-default" align="center" target="_blank"><i
                                                        class="fa fa-download"></i>&nbsp; {{$obj->NombreOriginal}}</a>
                                            </td>
                                            <td style="text-align: center;" valign="center">
                                                <i class="fa fa-trash fa-lg"
                                                    data-target="#modal-delete-documento-{{ $obj->Id }}"
                                                    data-toggle="modal"></i>
                                            </td>
                                        </tr>
                                        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"
                                            tabindex="-1" id="modal-delete-documento-{{ $obj->Id }}">

                                            <form method="POST"
                                                action="{{ url('catalogo/negocio/documento_eliminar', $obj->Id) }}">
                                                @method('post')
                                                @csrf
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                            <h4 class="modal-title">Eliminar Registros {{$obj->NombreOriginal}}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Confirme si desea Eliminar el Registro</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Cerrar</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Confirmar</button>
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

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 4 ? 'active in' : '' }}" id="gestiones"
                    aria-labelledby="home-tab">

                    <div class="col-12" style="text-align: right;">
                        <button class="btn btn-primary" data-toggle="modal"
                            data-target=".bs-modal-nuevo-gestion"><i class="fa fa-plus fa-lg"></i>
                            Nueva Gestión</button>
                    </div>
                    @if ($gestiones->count() > 0)
                    <br>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Descripción de la actividad</th>
                                <th>Usuario</th>
                                <th>Fecha y Hora</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gestiones as $obj)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $obj->DescripcionActividad }}</td>
                                <td>{{ $obj->usuarios->name }}</td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($obj->FechaHora))}}</td>
                                <td>
                                    <i class="fa fa-pencil fa-lg"
                                        onclick="modal_edit_gestion({{ $obj->Id }},'{{ $obj->DescripcionActividad }}')"
                                        data-target=".bs-modal-edit-gestion" data-toggle="modal"></i>
                                    &nbsp;&nbsp;
                                    <i class="fa fa-trash fa-lg"
                                        onclick="modal_delete_gestion({{ $obj->Id }})"
                                        data-target="#modal-delete-gestion" data-toggle="modal"></i>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div style="height: 200px">
                        <br>
                        <div class="alert alert-danger alert-dismissible " role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button>
                            <strong>Sin datos que mostrar.</strong>
                        </div>
                    </div>
                    @endif

                </div>

                </div>



            </div>



        </div>
    </div>

</div>

{{-- modales cotizaciones --}}

{{-- modal ingreso de cotización --}}

<div class="col-12">
    <div class="modal fade bs-modal-nuevo-cotizacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/negocio/add_cotizacion') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nueva cotización</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Negocio" value="{{$negocio->Id}}" class="form-control">

                        <div class="col-md-6">
                            <label for="Producto" class="form-label">Producto</label>
                            <select name="Producto" id="Producto" class="form-control select2" style="width: 100%"
                                required>
                                <option value="" disabled selected>Seleccione...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="Plan" class="form-label">Plan</label>
                            <select name="Plan" id="Plan" class="form-control select2" style="width: 100%" required>
                                <option value="" disabled selected>Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 12px!important;">
                            <label for="SumaAsegurada" class="form-label">Suma Asegurada</label>
                            <input class="form-control" type="number" value="" name="SumaAsegurada" step="0.01"
                                id="SumaAsegurada">
                        </div>
                        <div class="col-md-6" style="margin-top: 12px!important;">
                            <label for="PrimaNetaAnual" class="form-label">Prima Neta Anual</label>
                            <input class="form-control" step="0.01" type="number" value="" name="PrimaNetaAnual"
                                id="PrimaNetaAnual">
                        </div>
                        <br>
                        <div class="col-md-12" style="margin-top: 12px!important;">
                            <label for="Observacion" class="form-label">Observaciones o
                                comentarios</label>
                            <textarea name="Observaciones" id="Observaciones" rows="3" class="form-control"></textarea>

                        </div>
                        <div>&nbsp; </div>
                        <div>
                            <h4>Datos Técnicos requeridos para el plan</h4>
                            <hr>
                        </div>
                        <div id="datosTecnicosForm">

                        </div>
                    </div>
                    <div>&nbsp; </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>


{{-- modal de modificación de cotización --}}
<div class="col-12">
    <div class="modal fade bs-modal-edit-cotizacion" tabindex="-1" role="dialog" aria-hidden="true"
        id="modal-edit-cotizacion">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/negocio/edit_cotizacion') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Modificación cotización</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Negocio" value="{{$negocio->Id}}" class="form-control">
                        <input type="hidden" name="Id" id="ModalCotizacionId" class="form-control" required>


                        <div class="col-md-6">
                            <label for="Producto" class="form-label">Producto</label>
                            <select disabled name="Producto" id="ModalCotizacionProducto" class="form-control select2"
                                style="width: 100%" required>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label disabled for="Plan" class="form-label">Plan</label>
                            <select disabled name="Plan" id="ModalCotizacionPlan" class="form-control select2"
                                style="width: 100%" required>
                            </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 12px!important;">
                            <label for="SumaAsegurada" class="form-label">Suma Asegurada</label>
                            <input class="form-control" type="number" value="" name="SumaAsegurada" step="0.01"
                                id="ModalCotizacionSumaAsegurada">
                        </div>
                        <div class="col-md-6" style="margin-top: 12px!important;">
                            <label for="PrimaNetaAnual" class="form-label">Prima Neta Anual</label>
                            <input class="form-control" step="0.01" type="number" value="" name="PrimaNetaAnual"
                                id="ModalCotizacionPrimaNetaAnual">
                        </div>
                        <br>
                        <div class="col-md-12" style="margin-top: 12px!important;">
                            <label for="Observacion" class="form-label">Observaciones o
                                comentarios</label>
                            <textarea name="Observaciones" id="ModalCotizacionObservaciones" rows="3"
                                class="form-control"></textarea>

                        </div>
                        <div>&nbsp; </div>
                        <div>
                            <h4>Datos Técnicos requeridos para el plan</h4>
                            <hr>
                        </div>
                        <div id="ModalCotizaciondatosTecnicosForm">

                        </div>
                    </div>
                    <div>&nbsp; </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>

{{-- eliminar cotización --}}

<div class="col-12">
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
        id="modal-delete-cotizacion">

        <form method="POST" action="{{ url('catalogo/negocio/delete_cotizacion') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <input type="hidden" name="Id" id="IdCotizacion">
                        <h4 class="modal-title">Eliminar Cotización</h4>
                    </div>
                    <div class="modal-body">
                        <p>Confirme si desea Eliminar el Registro</p>
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

{{-- modales informacion_negocio --}}
{{-- ingresar informacion_negocio --}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-informacion_negocio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/negocio/add_informacion_negocio') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nuevo Contacto</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Negocio" value="{{$negocio->Id}}" class="form-control">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="Contacto" class="form-label">Contacto</label>
                                <input type="text" name="Contacto" id="Contacto" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="DescripcionOperacion" class="form-label">Descripción de la
                                    Operación</label>
                                <input type="text" name="DescripcionOperacion" id="DescripcionOperacion"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-6" style="margin-top: 12px!important;">
                                <label for="Contacto" class="form-label">Teléfono de Contacto</label>
                                <input type="text" name="TelefonoContacto" id="TelefonoContacto" class="form-control"
                                    data-inputmask="'mask': ['9999-9999']" required>
                            </div>
                            <div class="col-md-6" style="margin-top: 12px!important;">
                                <label for="Contacto" class="form-label">Observación del Contacto</label>
                                <textarea class="form-control" name="ObservacionContacto"
                                    id="ObservacionContacto"></textarea>
                            </div>
                        </div>

                    </div>
                    <div>&nbsp; </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>

{{-- edit informacion_negocio --}}
<div class="col-12">
    <div class="modal fade bs-modal-edit-informacion_negocio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/negocio/edit_informacion_negocio') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar Contacto</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Negocio" value="{{$negocio->Id}}" class="form-control">
                        <input type="hidden" name="Id" id="ModalContactoId" class="form-control" required>

                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="Contacto" class="form-label">Contacto</label>
                                <input type="text" name="Contacto" id="ModalContactoContacto" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="DescripcionOperacion" class="form-label">Descripción de la
                                    Operación</label>
                                <input type="text" name="DescripcionOperacion" id="ModalContactoDescripcionOperacion"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-6" style="margin-top: 12px!important;">
                                <label for="Contacto" class="form-label">Teléfono de Contacto</label>
                                <input type="text" name="TelefonoContacto" id="ModalContactoTelefonoContacto"
                                    class="form-control" data-inputmask="'mask': ['9999-9999']" required>
                            </div>
                            <div class="col-md-6" style="margin-top: 12px!important;">
                                <label for="Contacto" class="form-label">Observación del Contacto</label>
                                <textarea class="form-control" name="ObservacionContacto"
                                    id="ModalContactoObservacionContacto"></textarea>
                            </div>
                        </div>

                    </div>
                    <div>&nbsp; </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>
{{-- delete informacion_negocio --}}
<div class="col-12">
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
        id="modal-delete-informacion_negocio">

        <form method="POST" action="{{ url('catalogo/negocio/delete_informacion_negocio') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <input type="hidden" name="Id" id="IdContacto">
                        <h4 class="modal-title">Eliminar Contacto</h4>
                    </div>
                    <div class="modal-body">
                        <p>Confirme si desea Eliminar el Registro</p>
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

{{-- modales gestion --}}
{{-- ingresar gestion --}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-gestion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/negocio/add_gestion') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nueva gestión</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Negocio" value="{{$negocio->Id}}" class="form-control">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="DescripcionActividad" class="form-label">Descripción de la actividad</label>
                                <textarea class="form-control" name="DescripcionActividad"
                                    id="DescripcionActividad"></textarea>
                            </div>
                        </div>

                    </div>
                    <div>&nbsp; </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>

{{-- editar gestion --}}
<div class="col-12">
    <div class="modal fade bs-modal-edit-gestion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/negocio/edit_gestion') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Modificar gestión</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Negocio" value="{{$negocio->Id}}" class="form-control">
                        <input type="hidden" name="Id" id="ModalGestionId" class="form-control" required>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="DescripcionActividad" class="form-label">Descripción de la actividad</label>
                                <textarea class="form-control" name="DescripcionActividad"
                                    id="ModalGestionDescripcionActividad"></textarea>
                            </div>
                        </div>

                    </div>
                    <div>&nbsp; </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>

{{-- delete gestion --}}
<div class="col-12">
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
        id="modal-delete-gestion">

        <form method="POST" action="{{ url('catalogo/negocio/delete_gestion') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <input type="hidden" name="Id" id="IdGestion">
                        <h4 class="modal-title">Eliminar Gestión</h4>
                    </div>
                    <div class="modal-body">
                        <p>Confirme si desea Eliminar el Registro</p>
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

</div>


@include('sweetalert::alert')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

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
                    text: 'Debe de rellenar el campo de ' + campo +
                        ' antes de continuar con los demás campos',
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
        //$("#FormaPago").val('').trigger("change");
        $("#divDui").removeClass("has-error");
        $("#divNit").removeClass("has-error");
        //$("#MetodoPago").find("option:not(:first-child)").remove();
        //$("#MetodoPago").val(null).trigger("change"); // Clear and update the Select2 element
        $('#helpBlockDuiNit').hide();
        $('#helpBlockDuiNit2').hide();


    }

    function identificadorCliente() {
        $('#Dui').val('');
        $('#NitEmpresa').val('');
        $('#IdCliente').val('');
        borrarDatosCliente();
        if ($('#TipoPersona').val() == 1) {
            $('#divDui').show();
            $('#divNit').hide();
            $('#Dui').prop('required', true);
            $('#NitEmpresa').removeAttr('required');

        } else {
            $('#divDui').hide();
            $('#divNit').show();
            $('#Dui').removeAttr('required');
            $('#NitEmpresa').prop('required', true);

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
            $('#Dui').val('');
            $('#NitEmpresa').val('');
        } else {
            let Dui = $('#Dui').val();
            let Nit = $('#NitEmpresa').val();
            let tipoPersona = $('#TipoPersona').val();
            let parametros = {
                "IdCliente": null,
                "Dui": Dui,
                "Nit": Nit,
                "tipoPersona": tipoPersona
            };

            $.ajax({
                type: "get",
                url: "{{ URL::to('negocio/getCliente') }}",
                data: parametros,
                success: function(data) {
                    //console.log(data /*data.metodo_pago[0]*/ );
                    $('#IdCliente').val('');
                    borrarDatosCliente();
                    if (data.cliente !== null) {
                        $('#IdCliente').val(data.cliente.Id);
                        $('#NombreCliente').val(data.cliente.Nombre);
                        $('#Email').val(data.cliente.CorreoPrincipal);
                        //este funciona sin select2//$("#EstadoCliente option[value='"+data.cliente.Estado+"']").prop("selected", true);
                        if (data.cliente.Estado===2) {
                            $("#EstadoCliente").val(3).trigger("change");
                        }else{
                            $("#EstadoCliente").val(data.cliente.Estado).trigger("change");
                        }

                        //$("#FormaPago").val(data.cliente.FormaPago).trigger("change");
                        $("#divDui").addClass("has-error");
                        $("#divNit").addClass("has-error");
                        $('#helpBlockDuiNit').show();
                        $('#helpBlockDuiNit2').show();

                      /*  $.each(data.metodo_pago, function(index, datos) {
                            $("#MetodoPago").append(new Option(datos.NumeroTarjeta, datos.Id, false,
                                false));
                        });
                        $("#MetodoPago").trigger("change"); // Trigger change event to refresh Select2 */
                    }
                }
            });

        }

    }

    function borrarProductos() {
        //$("#FormaPago").val('').trigger("change");

        $("#Producto").find("option:not(:first-child)").remove();
        $("#Producto").val(null).trigger("change"); // Clear and update the Select2 element
        $("#Plan").find("option:not(:first-child)").remove();
        $("#Plan").val(null).trigger("change");
    }

    function borrarPlanes() {
        //$("#FormaPago").val('').trigger("change");
        $("#Plan").find("option:not(:first-child)").remove();
        $("#Plan").val(null).trigger("change");
    }

    function getProducto() {

            let Ramo = $('#NecesidadProteccion').val();
            let parametros = {
                "Ramo": Ramo
            };

            $.ajax({
                type: "get",
                url: "{{ URL::to('negocio/getProducto') }}",
                data: parametros,
                success: function(response) {
                    //console.log(response /*data.metodo_pago[0]*/ );
                    borrarProductos();
                    if (response.datosRecibidos !== null) {

                        $.each(response.datosRecibidos, function(index, datos) {
                            $("#Producto").append(new Option(datos.Nombre, datos.Id, false,
                                false));
                        });
                        $("#Producto").trigger("change"); // Trigger change event to refresh Select2

                    }
                }
            });
    }


    function getPlan() {

        let Producto = $('#Producto').val();
        let parametros = {
            "Producto": Producto
        };

        $.ajax({
            type: "get",
            url: "{{ URL::to('negocio/getPlan') }}",
            data: parametros,
            success: function(response) {
                //console.log(response /*data.metodo_pago[0]*/ );
                borrarPlanes();
                if (response.datosRecibidos !== null) {

                    $.each(response.datosRecibidos, function(index, datos) {
                        $("#Plan").append(new Option(datos.Nombre, datos.Id, false,
                            false));
                    });
                    $("#Plan").trigger("change"); // Trigger change event to refresh Select2
                    // Selecciona el contenedor donde deseas agregar los inputs
                    let contenedor = $("#datosTecnicosForm");
                    contenedor.empty();
                        // Recorre el array de datos
                        $.each(response.datos_tecnicos, function(index, dato) {
                            // Crea un div con las clases y estilos necesarios
                            let div = $("<div>", { class: "col-md-6", style: "margin-top: 12px!important;" });

                            // Crea una etiqueta <label> y un input <input>
                                let label = $("<label>", { for: dato.Id, class: "form-label", text: dato.Nombre });
                                let input = $("<input>", { class: "form-control", type: "text", value: "", name: dato.Id, id: dato.Id ,placeholder: dato.Descripcion/*,required: true*/});

                            // Agrega la etiqueta y el input al div
                            div.append(label, input);

                            // Agrega el div al contenedor
                            contenedor.append(div);
                        });
                }
            }
        });
    }
    function modal_edit_cotizacion(Id, NombreProducto, NombrePlan,SumaAsegurada,PrimaNetaAnual,Observaciones,DatosTecnicos,productoDatosTecnicos) {

        $("#ModalCotizacionProducto option").remove();
        $("#ModalCotizacionPlan option").remove();
        $("#ModalCotizacionProducto").val(null).trigger("change");
        $("#ModalCotizacionPlan").val(null).trigger("change");

        $("#ModalCotizacionProducto").append(new Option(NombreProducto, '', false,false));
        $("#ModalCotizacionProducto").trigger("change");
        $("#ModalCotizacionPlan").append(new Option(NombrePlan, '', false,false));
        $("#ModalCotizacionPlan").trigger("change");

        $('#ModalCotizacionId').val(Id);
        $('#ModalCotizacionSumaAsegurada').val(Number(SumaAsegurada).toFixed(2));
        $('#ModalCotizacionPrimaNetaAnual').val(Number(PrimaNetaAnual).toFixed(2));
        $('#ModalCotizacionObservaciones').val(Observaciones);

        DatosTecnicos=JSON.parse(DatosTecnicos);
        productoDatosTecnicos=JSON.parse(productoDatosTecnicos);

        //console.log(DatosTecnicos);
        //console.log(productoDatosTecnicos);

        let contenedor = $("#ModalCotizaciondatosTecnicosForm");
                    contenedor.empty();
                        // Recorre el array de datos
                        $.each(productoDatosTecnicos, function(index, dato) {
                            // Crea un div con las clases y estilos necesarios
                            let div = $("<div>", { class: "col-md-6", style: "margin-top: 12px!important;" });

                            // Crea una etiqueta <label> y un input <input>
                                let label = $("<label>", { for: dato.Id, class: "form-label", text: dato.Nombre });
                                let input = $("<input>", { class: "form-control", type: "text", value: DatosTecnicos[dato.Id], name: dato.Id, id: dato.Id ,placeholder: dato.Descripcion,/*required: true*/});

                            // Agrega la etiqueta y el input al div
                            div.append(label, input);

                            // Agrega el div al contenedor
                            contenedor.append(div);
                        });
    }

    function cotizacionAprobada(Id,Negocio) {
        //console.log(Id);
            let parametros = {
                "CotizacionId": Id,
                "Negocio": Negocio
            };

            $.ajax({
                type: "get",
                url: "{{ URL::to('negocio/elegirCotizacion') }}",
                data: parametros,
                success: function(response) {
                    //console.log(response /*data.metodo_pago[0]*/ );

                }
            });
    }

    function modal_delete_cotizacion(id) {
        $('#IdCotizacion').val(id);
    }

    function modal_edit_informacion_negocio(Id, Contacto, DescripcionOperacion, TelefonoContacto,ObservacionContacto) {
        $('#ModalContactoId').val(Id);
        $('#ModalContactoContacto').val(Contacto);
        $('#ModalContactoDescripcionOperacion').val(DescripcionOperacion);
        $('#ModalContactoTelefonoContacto').val(TelefonoContacto);
        $('#ModalContactoObservacionContacto').val(ObservacionContacto);
    }

    function modal_delete_informacion_negocio(Id) {
        $('#IdContacto').val(Id);
    }

    function modal_edit_gestion(Id, DescripcionActividad) {
        $('#ModalGestionId').val(Id);
        $('#ModalGestionDescripcionActividad').val(DescripcionActividad);
    }

    function modal_delete_gestion(Id) {
        $('#IdGestion').val(Id);
    }

    function tipoPersona() {

        if ($('#TipoPersona').val() == 1) {
            $('#divDui').show();
            $('#divNit').hide();
            $('#Dui').prop('required', true);
            $('#NitEmpresa').removeAttr('required');

        } else {
            $('#divDui').hide();
            $('#divNit').show();
            $('#Dui').removeAttr('required');
            $('#NitEmpresa').prop('required', true);

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
        $("#NecesidadProteccion").change(function() {
            getProducto();
        });

        $("#Producto").change(function() {
            getPlan();
        });

        tipoPersona();

        $("#TipoPersona").change(function() {
            tipoPersona();
        });

        $('.grupoCheckBoxAceptado').change(function() {
      if ($(this).is(':checked')) {
        $('.grupoCheckBoxAceptado').not(this).prop('checked', false);
      }
     });
    })

</script>
</div>
@include('sweetalert::alert')

@endsection
