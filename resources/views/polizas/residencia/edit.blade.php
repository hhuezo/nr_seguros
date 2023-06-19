@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>RESI - Poliza de Residencia <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">

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


            <form method="POST" action="{{ route('residencia.update', $residencia->Id) }}">
                @method('PUT')
                @csrf
                <div class="x_content" style="font-size: 12px;">
                    <br />
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" >Número de Póliza</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="NumeroPoliza" type="text" value="{{ $residencia->NumeroPoliza }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->aseguradoras->Nombre}}" class="form-control" readonly>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->clientes->Nombre}}" class="form-control" readonly>

                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Nit" id="Nit" type="text" value="{{$residencia->Nit }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Codigo" type="text" value="{{ $residencia->Codigo}}" readonly>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                            </label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                @if ($residencia->Mensual == 1)
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Mensual" value="1" checked disabled>
                                    <label class="control-label">Tasa ‰ Millar Mensual</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Anual" value="0" disabled>
                                    <label class="control-label">Tasa ‰ Millar Anual</label>
                                </div>
                                @else
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Mensual" value="1" disabled>
                                    <label class="control-label">Tasa ‰ Millar Mensual</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Anual" value="0" checked disabled>
                                    <label class="control-label">Tasa ‰ Millar Anual</label>
                                </div>
                                @endif

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite grupo</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="LimiteGrupo" id="LimiteGrupo" value="{{ $residencia->LimiteGrupo }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite individual</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="LimiteIndividual" value="{{ $residencia->LimiteIndividual }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Tasa" value="{{$residencia->Tasa }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Descuento %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="TasaDescuento" value="{{$residencia->TasaDescuento }}" class="form-control" readonly>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                    <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa de Comision %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Comision" value="{{$residencia->Comision }}" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Desde</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaDesde" type="text" value="{{ \Carbon\Carbon::parse($residencia->VigenciaDesde)->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Hasta</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaHasta" type="text" value="{{ \Carbon\Carbon::parse($residencia->VigenciaHasta)->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->ejecutivos->Nombre}}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Estatus</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->estadoPolizas->Nombre}}" class="form-control" readonly>
                            </div>
                        </div>


                    </div>

                    <br><br>
                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>

                </div>
            </form>



        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Estado de Pagos</a>
                    </li>
                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Generar Pago</a>
                    </li>
                </ul>

                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        <div class="x_title">
                            <h2>Estado de Pagos<small></small>
                            </h2>
                            <div class="clearfix"></div>
                        </div>

                        <div>
                            <br>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th><br><i class="fa fa-pencil"></i></th>
                                    <th>Tasa</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Final</th>
                                    <th>Descuento</th>
                                    <th>A Pagar</th>
                                    <th>Impresion de Recibo</th>
                                    <th>Envio de Cartera</th>
                                    <th>Envio de Pago</th>
                                    <th>Pago Aplicado</th>
                                </tr>
                                @foreach ($detalle as $obj)
                                @if(!$obj->ImpresionRecibo)
                                <tr class="danger">
                                    <td><i class="fa fa-pencil" onclick="modal_edit({{ $obj->Id }})"></i>
                                    </td>
                                    <td>{{ $obj->Tasa }}%</td>
                                    <td>{{$obj->FechaInicio}}</td>
                                    <td>{{$obj->FechaFinal}}</td>
                                    <td>{{ $obj->Descuento }}</td>
                                    <td>{{ $obj->APagar }}</td>
                                    <td>{{ $obj->ImpresionRecibo }}</td>
                                    <td>{{ $obj->EnvioCartera }}</td>
                                    <td>{{ $obj->EnvioPago }}</td>
                                    <td>{{ $obj->PagoAplicado }}</td>
                                </tr>
                                @elseif(!$obj->EnvioCartera)
                                <tr class="warning">
                                    <td><i class="fa fa-pencil" onclick="modal_edit({{ $obj->Id }})"></i>
                                    </td>
                                    <td>{{ $obj->Tasa }}%</td>
                                    <td>{{$obj->FechaInicio}}</td>
                                    <td>{{$obj->FechaFinal}}</td>
                                    <td>{{ $obj->Descuento }}</td>
                                    <td>{{ $obj->APagar }}</td>
                                    <td>{{ $obj->ImpresionRecibo }}</td>
                                    <td>{{ $obj->EnvioCartera }}</td>
                                    <td>{{ $obj->EnvioPago }}</td>
                                    <td>{{ $obj->PagoAplicado }}</td>
                                </tr>
                                @elseif(!$obj->EnvioPago)
                                <tr class="btn-info">
                                    <td><i class="fa fa-pencil" onclick="modal_edit({{ $obj->Id }})"></i>
                                    </td>
                                    <td>{{ $obj->Tasa }}%</td>
                                    <td>{{$obj->FechaInicio}}</td>
                                    <td>{{$obj->FechaFinal}}</td>
                                    <td>{{ $obj->Descuento }}</td>
                                    <td>{{ $obj->APagar }}</td>
                                    <td>{{ $obj->ImpresionRecibo }}</td>
                                    <td>{{ $obj->EnvioCartera }}</td>
                                    <td>{{ $obj->EnvioPago }}</td>
                                    <td>{{ $obj->PagoAplicado }}</td>
                                </tr>
                                @elseif(!$obj->PagoAplicado)
                                <tr class="btn-danger">
                                    <td><i class="fa fa-pencil" onclick="modal_edit({{ $obj->Id }})"></i>
                                    </td>
                                    <td>{{ $obj->Tasa }}%</td>
                                    <td>{{$obj->FechaInicio}}</td>
                                    <td>{{$obj->FechaFinal}}</td>
                                    <td>{{ $obj->Descuento }}</td>
                                    <td>{{ $obj->APagar }}</td>
                                    <td>{{ $obj->ImpresionRecibo }}</td>
                                    <td>{{ $obj->EnvioCartera }}</td>
                                    <td>{{ $obj->EnvioPago }}</td>
                                    <td>{{ $obj->PagoAplicado }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </table>

                        </div>


                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        <div class="x_title">
                            <!-- <h2>Pagos<small></small>
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <div class="btn btn-info float-right" data-toggle="modal" data-target=".bs-example-modal-lg">Nuevo</div>
                            </ul>
                            <div class="clearfix"></div>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>-->
                            <ul class="nav navbar-right panel_toolbox">
                                <div class="btn btn-info float-right" data-toggle="modal" data-target="#modal_pago">Nuevo</div>
                            </ul>
                            <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
                                            </div>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ url('polizas/residencia/create_pago') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <select name="Axo" class="form-control">
                                                            @for ($i = date('Y'); $i >= 2022; $i--)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <select name="Mes" class="form-control">
                                                            @for ($i = 1; $i < 12; $i++) @if (date('m')==$i) <option value="{{ $i }}" selected>{{ $meses[$i] }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $i }}">{{ $meses[$i] }}</option>
                                                                @endif
                                                                @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                        inicio</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="Id" value="{{ $residencia->Id }}" type="hidden" required>
                                                        <input class="form-control" name="FechaInicio" type="date" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                        final</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="FechaFinal" type="date" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="Archivo" type="file" required>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Validar</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input name="Validar" type="checkbox" checked class="js-switch" />
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Aceptar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <form action="{{ url('polizas/residencia/create_pago') }}" method="POST">
                                    <div class="modal-header">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="box-body row">
                                            <input type="hidden" name="Residencia" id="Residencia" value="{{ $residencia->Id }}" class="form-control">
                                            @csrf
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha Inicio</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="FechaInicio" id="FechaInicio" type="date" required>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                                        Cartera
                                                    </label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="MontoCartera" id="MontoCartera" type="number" step="any" value="{{ $residencia->MontoCartera }}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa %</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="Tasa" id="Tasa" value="{{$residencia->Tasa}}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima Calculada</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="PrimaCalculada" id="PrimaCalculada" class="form-control">
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa de Descuento %</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="TasaDescuento" type="number" step="any" id="TasaDescuento" value="{{$residencia->TasaDescuento}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ old('Descuento') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                                        Prima Descontada</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="PrimaDescontada" type="number" step="any" id="PrimaDescontada" value="{{ old('PrimaDescontada') }}">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha Final</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="FechaFinal" id="FechaFinal" type="date" required>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="Bomberos" id="Bomberos" value="{{$bomberos}}">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Impuestos bomberos</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="ImpuestoBomberos" id="ImpuestoBomberos" value="{{ old('ImpuestosBomberos') }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Gastos emisión</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="GastosEmision" id="GastosEmision" value="0" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Otros</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="Otros" id="Otros" value="0" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Sub Total</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="SubTotal" id="SubTotal" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">IVA</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="Iva" id="Iva" value="{{ old('Iva') }}" class="form-control">
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Menos valor CCF de comision</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="Comision" id="ValorCCF" value="{{ old('Comision') }}" class="form-control">
                                                    </div>
                                                    <!-- <a href="" data-target="#modal-calculator" data-toggle="modal" class="col-md-1 control-label" style="text-align: center;"><span class="fa fa-calculator fa-lg"></span></a> -->
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">A pagar</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input type="number" step="any" name="APagar" id="APagar" value="{{ old('APagar') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                                                <div class="form-group row">
                                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision %</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{$residencia->Comision}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                                        Desc</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="ValorDescuento" id="ValorDescuento" type="number" step="any">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                                        IVA sobre comisión</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="any">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                                        Retención</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="Retencion" id="Retencion" type="number" step="any" @if($residencia->clientes->TipoContribuyente == 1) readonly @endif>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF
                                                        Comisión</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <input class="form-control" name="ValorCCF" id="ValorCCFE" type="number" step="any">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <label class="control-label">Comentario</label>
                                                        <textarea name="Comentario" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="modal-footer" align="center">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Aceptar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form action="{{ url('polizas/residencia/create_pago') }}" method="POST">
                                <div class="modal-header">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="box-body row">
                                        <input type="hidden" name="Residencia" id="Residencia" value="{{ $residencia->Id }}" class="form-control">
                                        @csrf
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha Inicio</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="FechaInicio" id="FechaInicio" type="date" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                                    Cartera
                                                </label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="MontoCartera" id="MontoCartera" type="number" step="any" value="{{ $residencia->MontoCartera }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa %</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="Tasa" id="Tasa" value="{{$residencia->Tasa}}" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima Calculada</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="PrimaCalculada" id="PrimaCalculada" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Extra
                                                    Prima</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="ExtraPrima" type="number" step="any" id="ExtPrima">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                                    Prima Total</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="PrimaTotal" type="number" step="any" id="PrimaTotal" value="{{ old('PrimaToal') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa de Descuento %</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="TasaDescuento" type="number" step="any" id="TasaDescuento">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ old('Descuento') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                                    Prima Descontada</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="PrimaDescontada" type="number" step="any" id="PrimaDescontada" value="{{ old('PrimaDescontada') }}">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha Final</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="FechaFinal" id="FechaFinal" type="date" required>
                                                </div>
                                            </div>
                                            <input type="hidden" name="Bomberos" id="Bomberos" value="{{$bomberos}}">
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Impuestos bomberos</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="ImpuestoBomberos" id="ImpuestoBomberos" value="{{ old('ImpuestosBomberos') }}" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Gastos emisión</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="GastosEmision" id="GastosEmision" value="0" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Otros</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="Otros" id="Otros" value="0" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Sub Total</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="SubTotal" id="SubTotal" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">IVA</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="Iva" id="Iva" value="{{ old('Iva') }}" class="form-control">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Menos valor CCF de comision</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="Comision" id="ValorCCF" value="{{ old('Comision') }}" class="form-control">
                                                </div>
                                                <!-- <a href="" data-target="#modal-calculator" data-toggle="modal" class="col-md-1 control-label" style="text-align: center;"><span class="fa fa-calculator fa-lg"></span></a> -->
                                            </div>

                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">A pagar</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input type="number" step="any" name="APagar" id="APagar" value="{{ old('APagar') }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                                            <div class="form-group row">
                                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision %</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{$residencia->TasaComison}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                                    Desc</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="ValorDescuento" id="ValorDescuento" type="number" step="any">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                                    IVA sobre comisión</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="any">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                                    Retención</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="Retencion" id="Retencion" type="number" step="any" @if($residencia->clientes->TipoContribuyente == 1) readonly @endif>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF
                                                    Comisión</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <input class="form-control" name="ValorCCF" id="ValorCCFE" type="number" step="any">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Comentario</label>
                                                    <textarea name="Comentario" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="modal-footer" align="center">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Aceptar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
                <div class="modal fade " id="modal_editar_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form method="POST" action="{{ url('polizas/vida/edit_pago') }}">
                                <div class="modal-header">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <h5 class="modal-title" id="exampleModalLabel">Pago</h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="box-body">
                                        @csrf
                                        <input type="hidden" name="Id" id="ModalId" class="form-control">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label">Saldo a</label>
                                                <input type="date" name="SaldoA" id="ModalSaldoA" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label">Impresión de Recibo</label>
                                                <input type="date" name="ImpresionRecibo" id="ModalImpresionRecibo" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label">Envio cartera</label>
                                                <input type="date" name="EnvioCartera" id="ModalEnvioCartera" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label">Envio pago</label>
                                                <input type="date" name="EnvioPago" id="ModalEnvioPago" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label">Pago aplicado</label>
                                                <input type="date" name="PagoAplicado" id="ModalPagoAplicado" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label">Comentario</label>
                                                <textarea class="form-control" rows="4" name="Comentario" id="ModalComentario"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Aceptar</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        @include('sweetalert::alert')
        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#MontoCartera').change(function() {
                    if (document.getElementById('LimiteGrupo').value < document.getElementById('MontoCartera').value) {
                        swal('Su monto de cartera a superado al techo establecido en la poliza');
                    } else {
                        calculoPrimaCalculada();
                        calculoPrimaTotal();
                        calculoDescuento();
                        calculoSubTotal();
                        calculoCCF();
                    }


                })
                $("#PrimaCalculada").change(function() {
                    //  calculoPrimaCalculada();
                    calculoPrimaTotal();
                    calculoDescuento();
                    calculoSubTotal();
                    calculoCCF();
                })


                function calculoPrimaCalculada() {
                    var monto = document.getElementById('MontoCartera').value;
                    var tasa = document.getElementById('Tasa').value;
                    if (document.getElementById('Anual').checked == true) {
                        var tasaFinal = (tasa / 1000) / 12;
                    } else {
                        var tasaFinal = tasa / 1000;
                    }
                    var sub = Number(monto) * Number(tasaFinal);
                    document.getElementById('PrimaCalculada').value = sub;
                    //  var bomberos = (monto * (0.04 / 12) / 1000); //valor de impuesto varia por gobierno
                    // document.getElementById('ImpuestoBomberos').value = bomberos;

                }

                $("#ExtPrima").change(function() {
                    calculoPrimaTotal();
                    calculoDescuento();
                    calculoSubTotal();
                    calculoCCF();
                })

                function calculoPrimaTotal() {
                    var sub = document.getElementById('PrimaCalculada').value;
                    var extra = document.getElementById('ExtPrima').value;
                    var prima = Number(sub) + Number(extra);
                    document.getElementById('PrimaTotal').value = Number(prima);
                }
                $("#PrimaTotal").change(function() {
                    calculoDescuento();
                    calculoSubTotal();
                    calculoCCF();
                })
                $("#TasaDescuento").change(function() {
                    calculoDescuento();
                    calculoSubTotal();
                    calculoCCF();
                })

                function calculoDescuento() {
                    var tasa = document.getElementById('TasaDescuento').value;
                    var primaTotal = document.getElementById('PrimaTotal').value;
                    if (tasa < 1) {
                        document.getElementById('Descuento').value = tasa * primaTotal;
                    } else {
                        document.getElementById('Descuento').value = (tasa / 100) * primaTotal;
                    }
                    document.getElementById('PrimaDescontada').value = primaTotal - document.getElementById('Descuento').value;
                    //  var bomberos = (monto * (0.04 / 12) / 1000); //valor de impuesto varia por gobierno
                    if (document.getElementById('Bomberos').value == 0) {
                        document.getElementById('ImpuestoBomberos').value = 0;
                    } else {
                        document.getElementById('ImpuestoBomberos').value = (document.getElementById('MontoCartera').value * ((document.getElementById('Bomberos').value / 100) / 12) / 1000);
                    }

                }
                $('#GastosEmision').change(function() {
                    calculoSubTotal();
                    calculoCCF();
                })
                $('#Otros').change(function() {
                    calculoSubTotal();
                    calculoCCF();
                })

                function calculoSubTotal() {
                    var bomberos = document.getElementById('ImpuestoBomberos').value;
                    var primaDescontada = document.getElementById('PrimaDescontada').value;
                    var gastos = document.getElementById('GastosEmision').value;
                    var otros = document.getElementById('Otros').value;
                    document.getElementById('SubTotal').value = Number(bomberos) + Number(primaDescontada) + Number(gastos) + Number(otros);
                    document.getElementById('Iva').value = document.getElementById('SubTotal').value * 0.13;
                }

                $('#TasaComision').change(function() {
                    calculoCCF();
                    document.getElementById('APagar').style.backgroundColor = 'yellow';
                })

                function calculoCCF() {
                    var comision = document.getElementById('TasaComision').value;
                    var total = document.getElementById('PrimaDescontada').value;
                    var valorDes = total * (comision / 100);
                    document.getElementById('ValorDescuento').value = Number(valorDes);
                    var IvaSobreComision = Number(valorDes) * 0.13;
                    document.getElementById('IvaSobreComision').value = Number(IvaSobreComision);
                    if (document.getElementById('Retencion').hasAttribute('readonly')) {
                        var Retencion = 0;
                    } else {
                        var Retencion = valorDes * 0.01;
                        document.getElementById('Retencion').value = Retencion;
                    }
                    var ValorCCF = Number(valorDes) + Number(IvaSobreComision) - Number(Retencion);
                    // alert(ValorCCF);
                    document.getElementById('ValorCCFE').value = Number(ValorCCF);
                    document.getElementById('ValorCCF').value = Number(ValorCCF);
                    var PrimaTotal = document.getElementById('SubTotal').value;
                    var iva = document.getElementById('Iva').value;
                    var APagar = Number(PrimaTotal) - Number(ValorCCF) + Number(iva);
                    document.getElementById('APagar').value = APagar;

                }


                $("#habilitar").click(function() {
                    //  $("#btn_guardar").click(function() {
                    document.getElementById('ImpresionRecibo').removeAttribute('readonly');
                    document.getElementById('ImpresionRecibo').type = 'date';
                    document.getElementById('EnvioCartera').type = 'date';
                    document.getElementById('EnvioPago').type = 'date';
                    document.getElementById('PagoAplicado').type = 'date';
                    document.getElementById('SaldoA').type = 'date';
                    document.getElementById('ValorDescuento').value = 0;
                    document.getElementById('IvaSobreComision').value = 0;
                    document.getElementById('Retencion').value = 0;
                    document.getElementById('ValorCCFE').value = 0;

                })
            })

            function modal_edit(id) {
                document.getElementById('ModalSaldoA').value = "";
                document.getElementById('ModalImpresionRecibo').value = "";
                document.getElementById('ModalComentario').value = "";
                document.getElementById('ModalEnvioCartera').value = "";
                document.getElementById('ModalEnvioPago').value = "";
                document.getElementById('ModalPagoAplicado').value = "";
                document.getElementById('ModalId').value = id;



                $.get("{{ url('polizas/vida/get_pago') }}" + '/' + id, function(data) {
                    console.log(data);
                    document.getElementById('ModalSaldoA').value = data.SaldoA.substring(0, 10);
                    document.getElementById('ModalImpresionRecibo').value = data.ImpresionRecibo.substring(0, 10);
                    document.getElementById('ModalComentario').value = data.Comentario;
                    if (data.EnvioCartera) {
                        document.getElementById('ModalEnvioCartera').value = data.EnvioCartera.substring(0, 10);
                    } else {
                        $("#ModalEnvioPago").prop("readonly", true);
                        $("#ModalPagoAplicado").prop("readonly", true);
                    }

                    if (data.EnvioPago) {
                        document.getElementById('ModalEnvioPago').value = data.EnvioPago.substring(0, 10);
                    } else {
                        $("#ModalEnvioCartera").prop("readonly", true);
                        $("#ModalPagoAplicado").prop("readonly", true);
                    }

                    if (data.PagoAplicado) {
                        document.getElementById('ModalPagoAplicado').value = data.PagoAplicado.substring(0, 10);
                        $("#ModalEnvioCartera").prop("readonly", true);
                        $("#ModalEnvioPago").prop("readonly", true);
                        $("#ModalPagoAplicado").prop("readonly", true);
                    } else {
                        $("#ModalEnvioCartera").prop("readonly", true);
                        $("#ModalEnvioPago").prop("readonly", true);
                    }



                });
                $('#modal_editar_pago').modal('show');

            }
        </script>
        @endsection
