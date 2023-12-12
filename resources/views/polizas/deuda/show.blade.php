@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nuevo Poliza de Deuda &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VIDE - Seguro por Deuda<small></small>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    @if($deuda->Configuracion == 0)
                    <a href="" data-target="#modal-finalizar" data-toggle="modal" class="btn btn-success">Finalizar <br> Configuración</a>
                    @else
                    <a href="" data-target="#modal-finalizar" data-toggle="modal" class="btn btn-primary">Apertura <br> Configuración</a>
                    @endif
                </ul>
                <div class="clearfix"></div>
                <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-finalizar">

                    <form method="POST" action="{{ url('finalizar_configuracion') }}">
                        @method('POST')
                        @csrf
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <input type="hidden" name="deuda" value="{{$deuda->Id}}">
                                    <h4 class="modal-title">Finalizar Configuración</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Confirme si desea finalizar la configuración de la poliza</p>
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
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="{{$tab == 1 ? 'active':''}}"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                    </li>
                    <li role="presentation" class="{{$tab == 2 ? 'active':''}} "><a href="#tab_content2" id="lineas-tab" role="tab" data-toggle="tab" aria-expanded="true">Tasa diferencia</a>
                    </li>
                    <li role="presentation" class="{{$tab == 3 ? 'active':''}}"><a href="#tab_content3" id="asegurabilidad-tab" role="tab" data-toggle="tab" aria-expanded="true">Requisitos Minimos de Asegurabilidad </a>
                    </li>


                </ul>

                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{$tab == 1 ? 'active in':''}}" id="tab_content1" aria-labelledby="home-tab">
                        <form action="{{ url('polizas/deuda/actualizar') }}" method="POST">
                            @csrf

                            <div class="x_content" style="font-size: 12px;">
                                <div class="col-sm-4">
                                    <input type="hidden" value="{{$deuda->Id}}" name="Deuda">
                                    <label class="control-label" align="right">Número de Póliza</label>
                                    <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ $deuda->NumeroPoliza }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Nit</label>
                                    <input class="form-control" name="Nit" id="Nit" type="text" value="{{ $deuda->Nit }}" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Código</label>
                                    <input class="form-control" name="Codigo" type="text" value="{{ $deuda->Codigo}}" readonly>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" align="right">Aseguradora</label>
                                    <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($aseguradora as $obj)
                                        @if($obj->Id == $deuda->Aseguradora)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Productos</label>
                                    <select name="Productos" id="Productos" class="form-control select2" style="width: 100%">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($productos as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Planes</label>
                                    <select name="Planes" id="Planes" class="form-control select2" style="width: 100%">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($planes as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" align="right">Asegurado</label>
                                    <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($cliente as $obj)
                                        @if($obj->Id == $deuda->Asegurado)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Desde</label>
                                    <input class="form-control" name="VigenciaDesde" type="date" value="{{ $deuda->VigenciaDesde }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Hasta</label>
                                    <input class="form-control" name="VigenciaHasta" type="date" value="{{ $deuda->VigenciaHasta }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Estatus</label>
                                    <select name="EstadoPoliza" class="form-control select2" style="width: 100%">
                                        @foreach ($estadoPoliza as $obj)
                                        @if ($obj->Id == 1)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Ejecutivo</label>
                                    <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($ejecutivo as $obj)
                                        @if ($obj->Id == $deuda->Ejecutivo)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Descuento de Rentabilidad %</label>
                                    <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ $deuda->Descuento }}" required>
                                </div>
                                <div class="col-sm-4">
                                    &nbsp;
                                </div>
                                <div class="col-md-12">
                                    &nbsp;
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label " align="right">Clausulas Especiales</label>
                                    <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4">{{ $deuda->ClausulasEspeciales }} </textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Beneficios Adicionales</label>
                                    <textarea class="form-control" name="Beneficios" row="3" col="4">{{ $deuda->Beneficios }} </textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Concepto</label>
                                    <textarea class="form-control" name="Concepto" row="3" col="4" required>{{ $deuda->Concepto}}</textarea>
                                </div>
                                <div class="col-sm-4">
                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Mensual" value="1" {{$deuda->Mensual == 1 ? 'checked': ''}}>
                                        <label class="control-label">Tasa ‰ Millar Mensual</label>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Anual" value="0" {{$deuda->Mensual == 0 ? 'checked': ''}}>
                                        <label class="control-label">Tasa ‰ Millar Anual</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Tasa ‰ </label>
                                    <input class="form-control" name="Tasa" type="number" id="Tasa" step="any" value="{{ $deuda->Tasa }}" required>
                                </div>
                                <div class="col-sm-4" align="center">
                                    <br>
                                    <label class="control-label" align="center">Vida</label>
                                    <input id="Vida" type="checkbox" class="js-switch" />
                                </div>
                                <div class="col-sm-4" align="center">
                                    <br>
                                    <label class="control-label" align="center">Desempleo</label>
                                    <input id="Desempleo" type="checkbox" class="js-switch" />
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Tasa de Comision %</label>
                                    <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{ $deuda->TasaComision }}">
                                </div>
                                <div class="col-sm-4">
                                    <div id="poliza_vida" style="display: none;">
                                        <label class="control-label">Numero de Poliza Vida</label>
                                        <input name="Vida" type="text" class="form-control" value="{{$deuda->Vida}}" />
                                    </div>

                                </div>
                                <div class="col-sm-4">
                                    <div id="poliza_desempleo" style="display: none;">
                                        <label class="control-label">Numero de Poliza Desempleo</label>
                                        <input name="Desempleo" type="text" class="form-control" value="{{$deuda->Desempleo}}" />
                                    </div>

                                </div>

                            </div>


                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>

                            <br>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success" {{$deuda->Configuracion == 1 ? 'disabled':''}}>Guardar y Continuar</button>
                                    <a href="{{ url('poliza/vida') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{$tab == 2 ? 'active in':''}}" id="tab_content2" aria-labelledby="lineas-tab">
                        <div class="x_title"> &nbsp;
                        </div>
                        <form action="{{url('agregar_credito')}}" method="post">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <h4>&nbsp;&nbsp; Tasa Diferenciada<small></small>
                                </h4>
                                <label style="font-size: 12px;">* Se pueden agregar n número de tasa diferenciada</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="control-label" align="center">Linea de Credito</label> <br>
                                <select name="TipoCartera" id="TipoCartera" class="form-control" required>
                                    @foreach($tipoCartera as $obj)
                                    <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="control-label" align="center">Saldos y Montos</label> <br>
                                <select name="Saldos" id="Saldos" class="form-control" required>
                                    @foreach($saldos as $obj)
                                    <option value="{{$obj->Id}}">{{$obj->Abreviatura}} - {{$obj->Descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <h4>&nbsp;&nbsp;
                                </h4>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">

                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">

                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <input type="hidden" name="Deuda" value="{{$deuda->Id}}">
                                    <label class="control-label" align="center"> Rangos por Fechas de otorgamiento</label> <br>
                                    <input id="fechas" type="checkbox" class="js-switch" style="margin-left: 25%;" />
                                </div>
                                <div id="fecha_otorgamiento" style="display: none;">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Fecha Desde</label>
                                        <input type="date" class="form-control" name="FechaDesde" id="FechaDesde" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Fecha Hasta</label>
                                        <input type="date" class="form-control" name="FechaHasta" id="FechaHasta" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Tasa Fecha</label>
                                        <input type="number" step="0.01" class="form-control" name="TasaFecha" id="TasaFecha" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <hr>&nbsp;
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label class="control-label" align="center"> Rangos por montos</label> <br>
                                    <input id="montos" type="checkbox" class="js-switch" style="margin-left: 25%;" />
                                </div>
                                <div id="monto_otorgamiento" style="display: none;">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Monto Desde</label>
                                        <input type="number" step="0.01" class="form-control" name="MontoDesde" id="MontoDesde" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Monto Hasta</label>
                                        <input type="number" step="0.01" class="form-control" name="MontoHasta" id="MontoHasta" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Tasa Monto</label>
                                        <input type="number" step="0.01" class="form-control" name="TasaMonto" id="TasaMonto" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <hr>&nbsp;
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label class="control-label" align="center"> Rangos por Edad</label> <br>
                                    <input id="edad" type="checkbox" class="js-switch" style="margin-left: 25%;" />
                                </div>
                                <div id="edad_otorgamiento" style="display: none;">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Edad Desde</label>
                                        <input type="number" class="form-control" name="EdadDesde" id="EdadDesde" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Edad Hasta</label>
                                        <input type="number" class="form-control" name="EdadHasta" id="EdadHasta" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label" align="center">Tasa Edad</label>
                                        <input type="number" step="0.01" class="form-control" name="TasaEdad" id="TasaEdad" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br><br>
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success" {{$deuda->Configuracion == 1 ? 'disabled':''}}>Guardar y Continuar</button>
                                    <a href="{{ url('poliza/vida') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>
                        </form>
                        <br>
                        <hr>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table width="100%" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Linea Carteras</th>
                                            <th>Saldos y Montos</th>
                                            <th>Fecha Desde</th>
                                            <th>Fecha Hasta</th>
                                            <th>Tasa Fechas</th>
                                            <th>Monto Desde</th>
                                            <th>Monto Hasta</th>
                                            <th>Tasa Monto</th>
                                            <th>Edad Desde</th>
                                            <th>Edad Hasta</th>
                                            <th>Tasa por Edad</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($creditos as $obj)
                                        <tr>
                                            @if($obj->TipoCartera)
                                            <td>{{$obj->tipoCarteras->Nombre}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            @if($obj->Saldos)
                                            <td>{{$obj->saldos->Abreviatura}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            @isset($obj->FechaDesde)
                                            <td>{{date('d/m/Y', strtotime($obj->FechaDesde))}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            @isset($obj->FechaHasta)
                                            <td>{{date('d/m/Y', strtotime($obj->FechaHasta))}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            <td>{{$obj->TasaFecha}}</td>
                                            <td>{{$obj->MontoDesde}}</td>
                                            <td>{{$obj->MontoHasta}}</td>
                                            <td>{{$obj->TasaMonto}}</td>
                                            <td>{{$obj->EdadDesde}}</td>
                                            <td>{{$obj->EdadHasta}}</td>
                                            <td>{{$obj->TasaEdad}}</td>
                                            <td><a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a></td>
                                        </tr>
                                        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{ $obj->Id }}">

                                            <form method="POST" action="{{ url('eliminar_credito', $obj->Id) }}">
                                                @method('POST')
                                                @csrf
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                            <h4 class="modal-title">Eliminar Registro</h4>
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

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{$tab == 3 ? 'active in':''}}" id="tab_content3" aria-labelledby="asegurabilidad-tab">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form action="{{ url('datos_asegurabilidad') }}" method="POST">
                                <div class="modal-header">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <h5 class="modal-title" id="exampleModalLabel">Tabla de requisitos </h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="box-body">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group row">
                                                <input type="hidden" name="Deuda" value="{{$deuda->Deuda}}">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Perfiles</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <select name="Perfiles" id="Perfiles" class="form-control">
                                                        @foreach($perfil as $obj)
                                                        <option value="{{$obj->Id}}">{{$obj->Descripcion}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Edad
                                                    inicial</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="EdadInicial" value="1" type="text" readonly name="EdadInicial">
                                                </div>
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Edad
                                                    final</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="EdadFinal" name="EdadFinal" type="number">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                                    inicial</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="MontoInicial" step="0.01" type="number" name="MontoInicial">
                                                </div>
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                                    final</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="MontoFinal" step="0.01" type="number" name="MontoFinal">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <br>
                                <br>
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success" {{$deuda->Configuracion == 1 ? 'disabled':''}}>Guardar y Continuar</button>
                                    <a href="{{ url('poliza/vida') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if(isset($creditos))
                            <table>
                                <thead>
                                    @foreach($creditos as $obj)
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    @endforeach
                                </thead>
                                <tbody>
                                @foreach($creditos as $obj)
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



@include('sweetalert::alert')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#fechas").change(function() {
            if (document.getElementById('fechas').checked == true) {
                $('#fecha_otorgamiento').show();

            } else {
                $('#fecha_otorgamiento').hide();
            }
        })

        $("#montos").change(function() {
            if (document.getElementById('montos').checked == true) {
                $('#monto_otorgamiento').show();

            } else {
                $('#monto_otorgamiento').hide();
            }
        })
        $("#edad").change(function() {
            if (document.getElementById('edad').checked == true) {
                $('#edad_otorgamiento').show();

            } else {
                $('#edad_otorgamiento').hide();
            }
        })

        $("#montos").change(function() {
            if (document.getElementById('montos').checked == true) {
                $('#monto_otorgamiento').show();

            } else {
                $('#monto_otorgamiento').hide();
            }
        })


        $("#Asegurado").change(function() {
            // alert(document.getElementById('Asegurado').value);
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            var parametros = {
                "Cliente": document.getElementById('Asegurado').value
            };
            $.ajax({
                type: "get",
                //ruta para obtener el horario del doctor
                url: "{{ url('get_cliente') }}",
                data: parametros,
                success: function(data) {
                    console.log(data);
                    document.getElementById('Nit').value = data.Nit;
                    if (data.TipoContribuyente == 1) {
                        document.getElementById('Retencion').setAttribute("readonly", true);
                        document.getElementById('Retencion').value = 0;

                    }


                }
            });
        });

    });

    function add_rango() {
        $("#modal_rango").modal('show');
    }

    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }

    function modal_requisitos() {
        $('#modal_requisitos').modal('show');
    }

    function modal_creditos() {
        $('#modal_creditos').modal('show');
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#EdadInicial2").prop("disabled", true);
        $("#EdadFinal2").prop("disabled", true);
        $("#MontoInicial2").prop("disabled", true);
        $("#MontoFinal2").prop("disabled", true);

        $("#EdadInicial3").prop("disabled", true);
        $("#EdadFinal3").prop("disabled", true);
        $("#MontoInicial3").prop("disabled", true);
        $("#MontoFinal3").prop("disabled", true);
        $("#Vida").change(function() {
            if (document.getElementById('Vida').checked == true) {
                $('#poliza_vida').show();
            } else {
                $('#poliza_vida').hide();
            }
        })

        $("#Desempleo").change(function() {
            if (document.getElementById('Desempleo').checked == true) {
                $('#poliza_desempleo').show();
            } else {
                $('#poliza_desempleo').hide();
            }
        })




        $("#Aseguradora").change(function() {
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            // var para la Departamento
            var Aseguradora = $(this).val();

            //funcionpara las distritos
            $.get("{{ url('get_producto') }}" + '/' + Aseguradora, function(data) {
                //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                console.log(data);
                var _select = '<option value=""> Seleccione </option>';
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                    '</option>';
                $("#Productos").html(_select);
            });
        })

        $("#Productos").change(function() {
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            // var para la Departamento
            var Productos = $(this).val();

            //funcionpara las distritos
            $.get("{{ url('get_plan') }}" + '/' + Productos, function(data) {
                //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                console.log(data);
                var _select = '<option value=""> Seleccione </option>';
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                    '</option>';
                $("#Planes").html(_select);
            });
        })
    });

    $("#Activar1").change(function() {
        if (document.getElementById('Activar1').checked == true && document.getElementById('EdadFinal').value ==
            "" && document.getElementById('MontoFinal').value == "") {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar los datos anteriores',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            });
            document.getElementById('Activar1').checked = false;
        } else {
            document.getElementById('EdadInicial2').value = parseInt(document.getElementById('EdadFinal')
                .value) + 1;
            document.getElementById('MontoInicial2').value = parseFloat(document.getElementById('MontoFinal')
                .value) + 0.01;
            if (document.getElementById('Activar1').checked == true) {
                $("#EdadInicial2").prop("disabled", false);
                $("#EdadFinal2").prop("disabled", false);
                $("#MontoInicial2").prop("disabled", false);
                $("#MontoFinal2").prop("disabled", false);
            } else {
                $("#EdadInicial2").prop("disabled", true);
                $("#EdadFinal2").prop("disabled", true);
                $("#MontoInicial2").prop("disabled", true);
                $("#MontoFinal2").prop("disabled", true);

                document.getElementById('EdadInicial2').value = "";
                document.getElementById('EdadFinal2').value = "";
                document.getElementById('MontoInicial2').value = "";
                document.getElementById('MontoFinal2').value = "";

                document.getElementById('EdadInicial3').value = "";
                document.getElementById('EdadFinal3').value = "";
                document.getElementById('MontoInicial3').value = "";
                document.getElementById('MontoFinal3').value = "";
            }
        }

    });

    $("#Activar2").change(function() {
        if (document.getElementById('Activar2').checked == true && document.getElementById(
                'EdadFinal2').value == "" &&
            document.getElementById('MontoFinal2').value == "") {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar los datos anteriores',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            });
            document.getElementById('Activar2').checked = false;
        } else {
            document.getElementById('EdadInicial3').value = parseInt(document.getElementById('EdadFinal2')
                .value) + 1;
            document.getElementById('MontoInicial3').value = parseFloat(document.getElementById('MontoFinal2')
                .value) + 0.01;
            if (document.getElementById('Activar2').checked == true) {
                $("#EdadInicial3").prop("disabled", false);
                $("#EdadFinal3").prop("disabled", false);
                $("#MontoInicial3").prop("disabled", false);
                $("#MontoFinal3").prop("disabled", false);
            }
        }
    });

    $("#btn_modal_guardar").click(function() {
        validar();
    });

    function validar() {
        if (document.getElementById('Requisito').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo requisitos es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('EdadFinal').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo edad final es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('MontoInicial').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo monto inicial es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('MontoFinal').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo monto final es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('Activar1').checked == true &&
            (document.getElementById('EdadInicial2').value.trim() == "" || document.getElementById('EdadFinal2').value
                .trim() == "" ||
                document.getElementById('MontoInicial2').value.trim() == "" || document.getElementById('MontoFinal2')
                .value.trim() == "")

        ) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('Activar2').checked == true &&
            (document.getElementById('EdadInicial3').value.trim() == "" || document.getElementById('EdadFinal3').value
                .trim() == "" ||
                document.getElementById('MontoInicial3').value.trim() == "" || document.getElementById('MontoFinal3')
                .value.trim() == "")

        ) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else {
            guardar();
        }
    }

    function guardar() {
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Requisito": document.getElementById('Requisito').value,
            "EdadInicial": document.getElementById('EdadInicial').value,
            "EdadFinal": document.getElementById('EdadFinal').value,
            "MontoInicial": document.getElementById('MontoInicial').value,
            "MontoFinal": document.getElementById('MontoFinal').value,
            "EdadInicial2": document.getElementById('EdadInicial2').value,
            "EdadFinal2": document.getElementById('EdadFinal2').value,
            "MontoInicial2": document.getElementById('MontoInicial2').value,
            "MontoFinal2": document.getElementById('MontoFinal2').value,
            "EdadInicial3": document.getElementById('EdadInicial3').value,
            "EdadFinal3": document.getElementById('EdadFinal3').value,
            "MontoInicial3": document.getElementById('MontoInicial3').value,
            "MontoFinal3": document.getElementById('MontoFinal3').value
        };
        $.ajax({
            type: "post",
            url: "{{ url('polizas/deuda/store_requisitos') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                if (document.getElementById('DataRequisitos').value == "") {
                    document.getElementById('DataRequisitos').value = data;
                } else {
                    document.getElementById('DataRequisitos').value = document.getElementById(
                        'DataRequisitos').value + "," + data;
                }
                $('#modal_requisitos').modal('hide');
                get_requisitos();
            }
        });
    }

    function get_requisitos() {
        var parametros = {
            "Requisitos": document.getElementById('DataRequisitos').value,
        };
        $.ajax({
            type: "get",
            url: "{{ url('polizas/deuda/get_requisitos') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#divRequisitos').html(data);
            }
        });
    }
</script>
@endsection