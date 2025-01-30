@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <div class="x_panel">
        <style>
            .ocultar {
                display: none;
            }
        </style>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Pólizas / Residencia / Póliza de Residencia / {{$residencia->NumeroPoliza}} <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('polizas/residencia') }}" class="btn btn-info fa fa-undo " style="color: white">
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






            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ session('tab') == 1 ? 'active' : '' }}"><a href="#tab_content4"
                                id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                        </li>
                        <li role="presentation" class="{{ session('tab') == 2 ? 'active' : '' }} "><a href="#tab_content2"
                                role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Generar Cartera</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="creditos-tab"
                                data-toggle="tab" aria-expanded="false">Hoja de Cálculo
                                {{ $residencia->NumeroPoliza }}</a>
                        </li>
                        <li role="presentation" class="{{ session('tab') == 4 ? 'active' : '' }}"><a href="#tab_content1"
                                id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Estados de Cobro</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content5" role="tab" id="recibos-tab"
                                data-toggle="tab" aria-expanded="false">Ver Avisos</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content6" role="tab" id="comen-tab"
                                data-toggle="tab" aria-expanded="false">Comentarios</a>
                        </li>
                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ session('tab') == 1 ? 'active in' : '' }}"
                            id="tab_content4" aria-labelledby="home-tab">
                            <form method="POST" action="{{ route('residencia.update', $residencia->Id) }}">
                                @method('PUT')
                                @csrf
                                <div class="x_content" style="font-size: 12px;">
                                    <br />
                                    <div class="col-sm-4">
                                        <label class="control-label">Número de Póliza</label>
                                        <input class="form-control" name="NumeroPoliza" type="text"
                                            value="{{ $residencia->NumeroPoliza }}" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Nit</label>
                                        <input class="form-control" name="Nit" id="Nit" type="text"
                                            value="{{ $residencia->Nit }}" readonly>
                                    </div>
                                    <div class="col-sm-4 ocultar">
                                        <label class="control-label">Código</label>
                                        <input class="form-control" name="Codigo" id="Codigo" type="text"
                                            value="{{ $residencia->Id }}" readonly>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label">Aseguradora</label>
                                        <input type="text" value="{{ $residencia->aseguradoras->Nombre }}"
                                            class="form-control" id="NombreAseguradora" readonly>
                                        <input type="hidden" value="{{ $residencia->aseguradoras->Id }}"
                                            id="IdAseguradora" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label">Producto</label>
                                        <input type="text" value="{{ $residencia->planes->productos->Nombre }}"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label">Plan</label>
                                        <input type="text" value="{{ $residencia->planes->Nombre }}"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="col-md-4 ocultar">
                                        <label class="control-label">Cálculo Diario</label>
                                        <input type="checkbox" id="Diario" class="form-control" readonly
                                            @if ($residencia->aseguradoras->Diario == 1) checked @endif disabled>

                                    </div>
                                    <div class="col-md-4 ocultar">
                                        <label class="control-label">Cálculo Diario</label>
                                        <input type="checkbox" id="Dias365" class="form-control" readonly
                                            @if ($residencia->aseguradoras->Dias365 == 1) checked @endif disabled>

                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label">Asegurado</label>
                                        <input type="text" value="{{ $residencia->clientes->Nombre }}"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Vigencia Desde</label>
                                        <input class="form-control" name="VigenciaDesde" type="text"
                                            value="{{ date('d/m/Y', strtotime($residencia->VigenciaDesde)) }}" readonly>
                                        <input type="hidden" id="VigenciaDesde"
                                            value="{{ $residencia->VigenciaDesde }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Vigencia Hasta</label>
                                        <input class="form-control" name="VigenciaHasta" type="text"
                                            value="{{ date('d/m/Y', strtotime($residencia->VigenciaHasta)) }}" readonly>
                                        <input type="hidden" id="VigenciaHasta"
                                            value="{{ $residencia->VigenciaHasta }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Estatus</label>
                                        <input type="text" value="{{ $residencia->estadoPolizas->Nombre }}"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Vendedor</label>
                                        <input type="text" value="{{ $residencia->ejecutivos->Nombre }}"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Descuento de Rentabilidad %</label>
                                        <div class="form-group has-feedback">
                                            <input type="number" step="any" name="TasaDescuento"
                                                value="{{ $residencia->TasaDescuento }}" class="form-control"
                                                style="padding-left: 15%;"
                                                @if ($residencia->Modificar == 0) readonly @endif>
                                            <span class="fa fa-percent form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                    <!-- <div class="col-sm-4">
                                            <label class="control-label">Descuento de IVA</label>
                                            <input class="form-control" name="DescuentoIva" type="checkbox" id="DescuentoIva" @if ($residencia->Modificar == 0) disabled @endif
                                            @if ($residencia->DescuentoIva == 1) checked @endif>
                                        </div> -->
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="hidden" name="Bomberos" id="Bomberos"
                                            value="{{ $bomberos }}">
                                        <label class="control-label">Límite grupo $</label>
                                        <div class="form-group has-feedback">
                                            @if ($residencia->Modificar == 1)

                                                    <input type="number" style="display: none"   name="LimiteGrupo" id="LimiteGrupo" value="{{ $residencia->LimiteGrupo }}" class="form-control"  onblur="changeGrupo(0)">
                                                    <input type="text" style="text-align: right;"  id="LimiteGrupoDisplay" class="form-control" oninput="formatLimiteGrupo()" onblur="updateLimiteGrupo()" value="{{ number_format($residencia->LimiteGrupo, 2, '.', ',') }}">

                                            @else
                                                <input type="text" step="any" style="text-align: right;"
                                                    name="LimiteGrupo" id="LimiteGrupo"
                                                    value="{{ number_format($residencia->LimiteGrupo, 2, '.', ',') }}"
                                                    class="form-control" readonly>
                                            @endif
                                            <span class="fa fa-dollar form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Límite Individual $</label>
                                        <div class="form-group has-feedback">
                                            @if ($residencia->Modificar == 1)
                                            <input type="number" style="display: none" step="any" name="LimiteIndividual" id="LimiteIndividual" value="{{ $residencia->LimiteIndividual }}" class="form-control" >

                                            <input type="text" step="any" style="text-align: right;" id="LimiteIndividualDisplay" value="{{ number_format($residencia->LimiteIndividual, 2, '.', ',') }}" class="form-control" onchange="changeIndividual()" oninput="validateLimiteIndividual()">

                                            @else
                                                <input type="text" step="any" style="text-align: right;"
                                                    name="LimiteIndividual" id="LimiteIndividual"
                                                    value="{{ number_format($residencia->LimiteIndividual, 2, '.', ',') }}"
                                                    class="form-control" readonly>
                                            @endif
                                            <span class="fa fa-dollar form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Tasa Anual %</label>
                                        <div class="form-group has-feedback">
                                            <input type="number" style="padding-left: 15%;" step="any"
                                                name="Tasa" value="{{ $residencia->Tasa }}" class="form-control"
                                                @if ($residencia->Modificar == 0) readonly @endif>
                                            <span class="fa fa-percent form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Porcentaje de Comisión</label>
                                        <div class="form-group has-feedback">
                                            <input type="number" style="padding-left: 15%;" step="any"
                                                name="Comision" value="{{ $residencia->Comision }}" class="form-control"
                                                @if ($residencia->Modificar == 0) readonly @endif>
                                            <span class="fa fa-percent form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">&nbsp;
                                            </label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                @if ($residencia->Mensual == 1)
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <input type="radio" name="tipoTasa" id="Mensual"
                                                            value="1" checked
                                                            @if ($residencia->Modificar == 0) disabled @endif>
                                                        <label class="control-label">Tasa Millar Mensual</label>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <input type="radio" name="tipoTasa" id="Anual"
                                                            value="0"
                                                            @if ($residencia->Modificar == 0) disabled @endif>
                                                        <label class="control-label">Tasa ‰ Millar Anual</label>
                                                    </div>
                                                @else
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <input type="radio" name="tipoTasa" id="Mensual"
                                                            value="1"
                                                            @if ($residencia->Modificar == 0) disabled @endif>
                                                        <label class="control-label">Tasa Millar Mensual</label>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <input type="radio" name="tipoTasa" id="Anual"
                                                            value="0" checked
                                                            @if ($residencia->Modificar == 0) disabled @endif>
                                                        <label class="control-label">Tasa ‰ Millar Anual</label>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    &nbsp;
                                    <br>
                                    <!-- agregar rol de fatima -->
                                    <div class="form-group col-md-12" align="center">

                                        <button class="btn btn-success" type="submit"
                                            @if ($residencia->Modificar == 0) disabled @endif>Modificar</button>
                                        <a href="{{ url('polizas/residencia') }}"><button class="btn btn-primary"
                                                type="button">Cancelar</button></a>
                                    </div>
                                    <!-- fin -->
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ session('tab') == 4 ? 'active in' : '' }}"
                            id="tab_content1" aria-labelledby="home-tab">
                            <div class="x_title">
                                <h2>Estado de Pagos<small></small>
                                </h2>
                                <div class="clearfix"></div>
                            </div>

                            <div>
                                <br>
                                <table id="tblCobros" width="100%" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Póliza</th>
                                            <th style="text-align: center;">Fecha Inicio <br> Vigencia</th>
                                            <th style="text-align: center;">Fecha Final <br> Vigencia</th>
                                            <th style="text-align: center;">Fecha de Creación</th>
                                            <th style="text-align: center;">Nro de Aviso Cobro</th>
                                            <th style="text-align: center;">Cuota</th>
                                            <th style="text-align: center;">Nro de Documento</th>
                                            <th style="text-align: center;">Fecha de <br> Vencimiento</th>
                                            <th style="text-align: center;">Fecha de <br> Aplicación de pago</th>
                                            <th style="text-align: center;">Valor (US$)</th>
                                            <th style="text-align: center;">Estatus</th>
                                            <th style="text-align: center;">Opciones</th>

                                        </tr>
                                    </thead>
                                    @php
                                        $total = 0;
                                    @endphp
                                    <tbody>
                                        @foreach ($detalle as $obj)
                                            <tr>
                                                @php
                                                    $fileUrl = asset($obj->ExcelURL);
                                                @endphp
                                                <td style="text-align: center;">{{ $residencia->NumeroPoliza }}</td>
                                                <td style="text-align: center;">{{ $obj->FechaInicio ? \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') : '' }}</td>
                                                <td style="text-align: center;">{{ $obj->FechaFinal ?  \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') : '' }}</td>
                                                <td style="text-align: center;">{{ $obj->ImpresionRecibo ?  \Carbon\Carbon::parse($obj->ImpresionRecibo)->format('d/m/Y') : '' }}</td>
                                                <td style="text-align: center;">{{$obj->NumeroRecibo ? 'AC'.str_pad($obj->NumeroRecibo, 6, '0', STR_PAD_LEFT).' '.date('y'):'' }} </td>
                                                <td style="text-align: center;">01/01</td>
                                                <td style="text-align: center;">{{$obj->NumeroCorrelativo ? $obj->NumeroCorrelativo : ''}}</td>
                                                <td style="text-align: center;">{{ $obj->FechaInicio ? \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') : ''}}</td>
                                                <td style="text-align: center;">{{ $obj->PagoAplicado ?  \Carbon\Carbon::parse($obj->PagoAplicado)->format('d/m/Y') : ''}}</td>
                                                <td style="text-align: center;">{{ $obj->APagar ? number_format($obj->APagar, 2, '.', ',') :''}} 
                                                        @php
                                                            $total += $obj->APagar;
                                                        @endphp
                                                </td>
                                                <td style="text-align: center;">
                                                @if ($obj->Activo == 0)
                                                    Anulado
                                                @elseif(!$obj->PagoAplicado)
                                                    Pendiente
                                                @elseif($obj->PagoAplicado)
                                                    Pagado
                                                @else
                                                    
                                                @endif

                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($obj->Activo == 0)
                                                    @elseif(!$obj->ImpresionRecibo)
                                                        <a href="" target="_blank" class="btn btn-primary"
                                                            data-target="#modal-recibo-{{ $obj->Id }}"
                                                            title="Generar Aviso de Cobro" data-toggle="modal"><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                                    @else
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-pencil fa-lg"
                                                            onclick="modal_edit({{ $obj->Id }})"
                                                            title="Actualizar Fechas de Cobro"></i>
                                                    </button>
                                                        
                                                    @endif
                                                    
                                                    <a href="{{ $fileUrl }}" class=" btn btn-success fa fa-file-excel-o"
                                                        align="center" title="Descargar Cartera Excel"></a>
                                                        <button class="btn btn-warning"><i data-target="#modal-view-{{ $obj->Id }}" data-toggle="modal"
                                                        class="fa fa-eye" align="center"
                                                        title="Ver Detalles"></i></button>
                                                    
                                                    @if ($obj->Activo == 1)
                                                        <a href="" class="btn btn-danger" data-target="#modal-delete-{{ $obj->Id }}"
                                                            data-toggle="modal" title="Anular Cobro"><i
                                                                class="fa fa-trash fa-lg"></i></a>
                                                    @endif



                                                </td>

                                            </tr>
                                            @include('polizas.residencia.modal_edit')
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td colspan="3" style="text-align: right;"><b>Total de Poliza:</b> </td>
                                        <td colspan="5" style="text-align: right;">
                                            <b>${{ number_format($total, 2, '.', ',') }}</b>
                                        </td>
                                        <td colspan="2"></td>
                                    </tfoot>
                                </table>

                            </div>


                        </div>
                       @include('polizas.residencia.tab2')
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="creditos-tab">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="x_title">
                                    <h4>&nbsp;&nbsp; Cálculo de Cartera
                                        {{ $residencia->clientes->Nombre }}<small></small>
                                    </h4>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                                    &nbsp;
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                    <table class="table table-striped jambo_table bulk_action" style="font-size: 13px;">
                                        <thead>
                                            <tr>
                                                <th>{{ $residencia->aseguradoras->Nombre }} <br>
                                                    {{ $residencia->clientes->Nombre }} <br>
                                                    N° Póliza: {{ $residencia->NumeroPoliza }} <br>
                                                    Vigencia:
                                                    {{ \Carbon\Carbon::parse($residencia->VigenciaDesde)->format('d/m/Y') }}
                                                    al
                                                    {{ \Carbon\Carbon::parse($residencia->VigenciaHasta)->format('d/m/Y') }}
                                                    <br>
                                                    Cálculo para el periodo de: <br>
                                                    @if ($ultimo_pago)
                                                        {{ \Carbon\Carbon::parse($ultimo_pago->FechaInicio)->format('d/m/Y') }}
                                                        al
                                                        {{ \Carbon\Carbon::parse($ultimo_pago->FechaFinal)->format('d/m/Y') }}
                                                    @endif
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    &nbsp;
                                </div>
                                <br>
                                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                                    &nbsp;
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <table class="table table-striped jambo_table bulk_action" style="font-size: 13px;">
                                        <tr>
                                            <td>
                                                <!-- Tasa @if ($residencia->Mensual == 1)
    Mensual
@else
    Anual
    @endif Millar : -->


                                                Tasa Anual %.
                                            </td>
                                            <td>
                                                <div class="col-md-9 col-sm-9 form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        value="@if ($ultimo_pago) {{ number_format($residencia->Tasa, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-percent form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <center><strong>Base Cálculo de la Prima </strong></center>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td> Monto Cartera</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;" id="MontoCartera2"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->MontoCartera, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tasa por millar</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;" id="TasaMillar2"
                                                        value="@if ($ultimo_pago) {{ $valorTasa }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                                                    <td>Resultado 1</td>
                                                                    <td><input type="text" id="Resultado2" value="@if ($ultimo_pago) {{ $ultimo_pago->MontoCartera }} @else 0 @endif"   class="form-group"></td>
                                                                </tr> -->
                                        <tr>
                                            <td>Prima Calculada </td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;" id="PrimaCalculada2"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->PrimaCalculada, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>(-) Descuento Rentabilidad {{ $residencia->TasaDescuento }}%</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;" id="DescuentoRentabilidad2"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Descuento, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>(=) Prima Descontada</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->PrimaDescontada, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>(+)Impuesto Bomberos</td>
                                            <td>

                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->ImpuestoBomberos, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SubTotal</td>
                                            <td>

                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->SubTotal, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>13% IVA</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Iva, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Factura</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->SubTotal + $ultimo_pago->Iva, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>(-) Estructura CCF de Comisión</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->ValorCCF, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Prima total a Pagar @if ($ultimo_pago)
                                                    <br>
                                                    {{ \Carbon\Carbon::parse($ultimo_pago->FechaInicio)->format('d/m/Y') }}
                                                    al
                                                    {{ \Carbon\Carbon::parse($ultimo_pago->FechaFinal)->format('d/m/Y') }}
                                                    <br>
                                                @endif
                                            </td>
                                            <td>

                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->APagar, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">


                                    <table class="table table-striped jambo_table bulk_action" style="font-size: 13px;">
                                        <tr>
                                            <td>Comisión</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="padding-left: 25%;"
                                                        value="@if ($ultimo_pago) {{ $residencia->Comision }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-percent form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Valor por Comisión</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Comision, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Más 13% IVA sobre comisión</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->IvaSobreComision, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Menos 1% Retención</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Retencion, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Valor CCF por Comisión</td>
                                            <td>
                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        style="text-align: right;"
                                                        value="@if ($ultimo_pago) {{ number_format($ultimo_pago->ValorCCF, 2, '.', ',') }} @else 0 @endif"
                                                        readonly>
                                                    <span class="fa fa-dollar form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="recibos-tab">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                    <div class="x_title">
                                        <h4>&nbsp;&nbsp; Avisos de Cobro<small></small>
                                        </h4>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table width="100%" class="table table-striped" id="avisos">
                                            <thead>
                                                <tr>
                                                    <th>N° Aviso</th>
                                                    <th>N° Documento</th>
                                                    <th>Fecha Impresión Aviso</th>
                                                    <th>Fecha Inicio</th>
                                                    <th>Fecha Final</th>
                                                    <th>Estados</th>
                                                    <th><i class="fa fa-filef"></i>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($detalle as $obj)
                                                    @if ($obj->ImpresionRecibo != null)
                                                        <tr>
                                                            <td>{{$obj->NumeroRecibo ? 'AC'.str_pad($obj->NumeroRecibo, 6, '0', STR_PAD_LEFT).' '.date('y'):'' }}</td>
                                                            <td>{{$obj->NumeroCorrelativo ? $obj->NumeroCorrelativo : '' }} </td>
                                                            <td>{{ \Carbon\Carbon::parse($obj->ImpresionRecibo)->format('d/m/Y') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') }}
                                                            </td>
                                                            <td> {{ \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') }}
                                                            </td>
                                                            @if ($obj->Activo == 0)
                                                                <td>Anulado</td>
                                                            @elseif($obj->ImpresionRecibo)
                                                                <td>Emitido</td>
                                                            @elseif($obj->PagoAplicado)
                                                                <td>Pagado</td>
                                                            @else
                                                                <td></td>
                                                            @endif
                                                            <td>
                                                                @if ($obj->Activo != 0)
                                                                    <a href="{{ url('poliza/residencia/get_recibo') }}/{{ $obj->Id }}"
                                                                        target="_blank"
                                                                        class="btn btn-info">Reimprimir</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content6" aria-labelledby="comen-tab">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                    <div class="x_title">
                                        <h4>&nbsp;&nbsp; Comentarios<small></small>
                                        </h4>
                                        <div class="clearfix" align="right"><button class="btn btn-primary"
                                                onclick="add_comment();"><i class="fa fa-plus"></i> Agregar
                                                Comentario</button></div>
                                    </div>
                                    <br>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table width="100%" class="table table-striped" id="comentarios">
                                            <thead>
                                                <tr>
                                                    <th>Comentario</th>
                                                    <th>Tipo de <br> Comentario</th>
                                                    <th>Usuario</th>
                                                    <th>Fecha Ingreso</th>
                                                    <th><i class="fa fa-filef"></i>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($comentarios as $obj)
                                                    <tr>
                                                        <td>{{ $obj->Comentario }}</td>
                                                        @if ($obj->DetalleResidencia)
                                                            <td>Detalle del Cobro del
                                                                {{ \Carbon\Carbon::parse($obj->FechaIngreso)->format('d/m/Y') }}
                                                            </td>
                                                        @else
                                                            <td>Póliza</td>
                                                        @endif
                                                        <td> {{ $obj->usuarios->name }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($obj->FechaIngreso)->format('d/m/Y') }}
                                                        </td>
                                                        <td><a href=""
                                                                data-target="#modal-delete-comentario-{{ $obj->Id }}"
                                                                data-toggle="modal"><i
                                                                    class="fa fa-trash fa-lg"></i></a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade modal-slide-in-right" aria-hidden="true"
                                                        role="dialog" tabindex="-1"
                                                        id="modal-delete-comentario-{{ $obj->Id }}">

                                                        <form method="POST"
                                                            action="{{ url('polizas/residencia/eliminar_comentario') }}">
                                                            @method('POST')
                                                            @csrf
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                        <h4 class="modal-title">Eliminar Registro</h4>
                                                                        <input type="hidden" name="IdComment"
                                                                            value="{{ $obj->Id }}">
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
                        </div>
                    </div>
                    <div class="modal fade " id="modal_editar_pago" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ url('polizas/residencia/edit_pago') }}">
                                    <div class="modal-header">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <h5 class="modal-title" id="exampleModalLabel">Pago</h5>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="box-body">
                                            @csrf
                                            <input type="hidden" name="Id" id="ModalId"
                                                class="form-control">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Saldo a</label>
                                                    <input type="date" name="SaldoA" id="ModalSaldoA"
                                                        class="form-control" value="{{ date('Y-m-d') }}" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Impresión de Recibo</label>
                                                    <input type="date" name="ImpresionRecibo"
                                                        id="ModalImpresionRecibo" value="{{ date('Y-m-d') }}"
                                                        class="form-control" readonly>
                                                </div>
                                                <!-- <div class="col-sm-3">
                                                                        <label class="control-label">&nbsp;</label>
                                                                        <i class="btn btn-default fa fa-print form-control" id="btn_impresion"></i>
                                                                    </div> -->
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Envio cartera</label>
                                                    <input type="date" name="EnvioCartera" id="ModalEnvioCartera"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Envio pago</label>
                                                    <input type="date" name="EnvioPago" id="ModalEnvioPago"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Pago aplicado</label>
                                                    <input type="date" name="PagoAplicado" id="ModalPagoAplicado"
                                                        class="form-control">
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
                                        <button type="button" class="btn btn-warning"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Aceptar</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade " id="modal_agregar_comentario" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ url('polizas/residencia/agregar_comentario') }}">
                                    <div class="modal-header">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Comentario</h5>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="box-body">
                                            @csrf
                                            <input type="hidden" name="ResidenciaComment"
                                                value="{{ $residencia->Id }}" class="form-control">

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Tipo de Comentario</label>
                                                    <select name="TipoComentario" id="TipoComentario"
                                                        class="form-control">
                                                        <option value="">Sobre Póliza</option>
                                                        @foreach ($detalle as $det)
                                                            <option value="{{ $det->Id }}">Sobre Cobro de
                                                                {{ \Carbon\Carbon::parse($det->FechaInicio)->format('d/m/Y') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">Comentario</label>
                                                    <textarea class="form-control" rows="4" name="Comentario"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Aceptar</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
    @include('sweetalert::alert')
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-residencia");

            $('#comentarios').DataTable();
            $('#avisos').DataTable();
            //    $('#cobros').DataTable();

            $('#cobros').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
            });
            $("#tblCobros").DataTable();
        });

        function formatearNumero(numero) {
            // Verificar si el número es válido
            if (isNaN(numero)) {
                console.error("El valor ingresado no es un número válido");
                return null;
            }

            // Formatear el número con separador de miles y punto como separador decimal
            var numeroFormateado = numero.toLocaleString('en-US', {
                style: 'decimal',
                maximumFractionDigits: 2
            });

            return numeroFormateado;
        }

        function add_comment() {

            $("#modal_agregar_comentario").modal('show');
        }


        function show_MontoCartera() {
            var montoCartera = parseFloat(document.getElementById("MontoCartera").value);

            var numeroFormateado = formatearNumero(montoCartera);
            document.getElementById('MontoCarteraView').value = numeroFormateado;

            $("#MontoCarteraView").show();
            $("#MontoCartera").hide();

        }

        function aplicarpago() {
            document.getElementById('boton_pago').type = "submit";
        }
        $(document).ready(function() {

           /* $("#MontoCarteraView").on('focus', function() {
                $("#MontoCarteraView").hide();
                $("#MontoCartera").show();
            })*/



            // $("#MontoCartera").on('blur', function() {
            //     alert('');
            //     $("#MontoCartera").show();
            //     $("#MontoCarteraView").hide();
            // })


            $('#PrimaDescontada2').val($('#PrimaCalculada2').val() - $('#DescuentoRentabilidad2').val());

            $('#Validar').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#FormArchivo').prop('target', '_blank');
                } else {
                    $('#FormArchivo').removeAttr('target')
                }
            });

            $("#btn_confirmar_recibo").click(function() {
                window.location.reload();

            })

           // calculoPrimaCalculada();
           /// calculoPrimaTotal();
           // calculoDescuento();
           // calculoSubTotal();
           // calculoCCF();

          /*  $('#MontoCartera').change(function() {
                var monto = Number(document.getElementById('MontoCartera').value);
                var grupal = Number(document.getElementById('LimiteGrupo').value);
                if (grupal < monto) {

                    swal('Su monto de cartera a superado al techo establecido en la póliza');
                } else {
                 //   calculoPrimaCalculada();
                 //   calculoPrimaTotal();
                  //  calculoDescuento();
                   // calculoSubTotal();
                  //  calculoCCF();
                }


            })
            $("#PrimaCalculada").change(function() {
                //  calculoPrimaCalculada();
                calculoPrimaTotal();
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();
            })*/


            function calculoPrimaCalculada() {
                var monto = document.getElementById('MontoCartera').value;
                //console.log(document.getElementById('VigenciaDesde').value);
                var desde = new Date(document.getElementById('VigenciaDesde').value);
                var hasta = new Date(document.getElementById('VigenciaHasta').value);
                var hoy = new Date();
                console.log(hoy);

                var aseguradora = document.getElementById('IdAseguradora').value;
                // Determine the time difference between two dates
                var millisBetween = hasta.getTime() - desde.getTime();

                // Determine the number of days between two dates
                var dias_axo = (millisBetween / (1000 * 3600 * 24));
                console.log(desde, hasta);
                console.log("dias del año: " + dias_axo)

                // var inicio = new Date(document.getElementById('FechaInicio').value += 'T00:00:00');
                // var final = new Date(document.getElementById('FechaFinal').value += 'T23:59:59' );
                var inicio = new Date(document.getElementById('FechaInicio').value);
                var final = new Date(document.getElementById('FechaFinal').value);
                inicio.setHours(0, 0, 0, 0);
                final.setHours(0, 0, 0, 0);
                console.log("inicio" + inicio)
                console.log("final" + final)

                var millisBetween = final.getTime() - inicio.getTime();

                // Determine the number of days between two dates
                var dias_mes = Math.round(millisBetween / (1000 * 3600 * 24));

                // alert(dias_axo);
                //alert(dias_mes);
                var tasa = document.getElementById('Tasa').value;
                //  alert(aseguradora);
                if (aseguradora == 3) { // busca la aseguradora de fedecredito, revisar el id de fedecredito

                    if (document.getElementById('Anual').checked == true) { //pendiente de confirmacion
                        var tasaFinal = (tasa / 1000); /// 12
                    } else {
                        var tasaFinal = (tasa / 1000);
                    }

                } else { // sisa
                    if (document.getElementById('Anual').checked == true) {
                        var tasaFinal = (tasa / 1000) / 12;
                    } else {
                        var tasaFinal = (tasa / 1000) / 12;
                    }

                }

                console.log('tasa Final:', tasaFinal);

                var sub = parseFloat(monto) * parseFloat(tasaFinal);
                if (document.getElementById('Diario').checked == true) {
                    if (document.getElementById('Dias365').checked == true) {
                        var sub = ((parseFloat(monto) * parseFloat(tasaFinal)) / 365) * dias_mes;
                    }else{
                        var sub = ((parseFloat(monto) * parseFloat(tasaFinal)) / dias_axo) * dias_mes;
                    }

                    
                } else {
                    var sub = parseFloat(monto) * parseFloat(tasaFinal);
                }
                // alert(sub);
                document.getElementById('PruebaDecimales').value = sub;
                document.getElementById('PrimaCalculada').value = sub.toLocaleString('sv-SV', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).replace(',', '.').replace(/[^\d,.-]/g, '');
                document.getElementById('tasaFinal').value = tasaFinal
                //  var bomberos = (monto * (0.04 / 12) / 1000); //valor de impuesto varia por gobierno
                // document.getElementById('ImpuestoBomberos').value = bomberos;

            }

           /*  $("#ExtPrima").change(function() {
               calculoPrimaTotal();
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();
            })*/

            function calculoPrimaTotal() {
                var sub = document.getElementById('PrimaCalculada').value;
                var extra = document.getElementById('ExtPrima').value;
                var prima = Number(sub) + Number(extra);
                document.getElementById('PrimaTotal').value = Number(prima);
            }
            // $("#PrimaTotal").change(function() {
            //     calculoDescuento();
            //     calculoSubTotal();
            //     calculoCCF();
            // })
            // $("#TasaDescuento").change(function() {
            //     calculoDescuento();
            //     calculoSubTotal();
            //     calculoCCF();
            // })

            function calculoDescuento() {
                var tasa = document.getElementById('TasaDescuento').value;
                var primaTotal = document.getElementById('PrimaTotal').value;
                if (tasa < 0) {
                    document.getElementById('Descuento').value = (tasa * primaTotal).toFixed(2);
                } else {
                    document.getElementById('Descuento').value = ((tasa / 100) * primaTotal).toFixed(2);
                }
                document.getElementById('PrimaDescontada').value = (primaTotal - document.getElementById(
                    'Descuento').value).toFixed(2);
                //  var bomberos = (monto * (0.04 / 12) / 1000); //valor de impuesto varia por gobierno
                if (document.getElementById('Bomberos').value == 0) {
                    document.getElementById('ImpuestoBomberos').value = 0;
                } else {
                    document.getElementById('ImpuestoBomberos').value = (document.getElementById('MontoCartera')
                        .value * ((document.getElementById('Bomberos').value / 100) / 12) / 1000);
                }

            }
            // $('#GastosEmision').change(function() {
            //     calculoSubTotal();
            //     calculoCCF();
            // })
            // $('#Otros').change(function() {
            //     calculoSubTotal();
            //     calculoCCF();
            // })

            function calculoSubTotal() {
                var bomberos = document.getElementById('ImpuestoBomberos').value;
                var primaDescontada = document.getElementById('PrimaDescontada').value;
                var gastos = document.getElementById('GastosEmision').value;
                var otros = document.getElementById('Otros').value;
                document.getElementById('SubTotal').value = Number(bomberos) + Number(primaDescontada) + Number(
                    gastos) + Number(otros);
                document.getElementById('Iva').value = (document.getElementById('SubTotal').value * 0.13).toFixed(
                    2);
            }

            // $('#TasaComision').change(function() {
            //     calculoCCF();
            //     document.getElementById('APagar').style.backgroundColor = 'yellow';
            // })
            $('#ValorCCFE').change(function() {
                var ccfe = document.getElementById('ValorCCFE').value
                document.getElementById('ValorCCF').value = Number(ccfe);
                var PrimaTotal = document.getElementById('SubTotal').value;
                var iva = document.getElementById('Iva').value;
                var APagar = Number(PrimaTotal) - Number(ccfe) + Number(iva);
                document.getElementById('APagar').value = APagar.toFixed(2);
                document.getElementById('APagar').style.backgroundColor = 'yellow';
                document.getElementById('Facturar').value = (Number(PrimaTotal) + Number(iva)).toFixed(2);
            })

            $('#ValorCCF').change(function() {
                var ccf = document.getElementById('ValorCCF').value
                document.getElementById('ValorCCFE').value = Number(ccf);
                var PrimaTotal = document.getElementById('SubTotal').value;
                var iva = document.getElementById('Iva').value;
                var APagar = Number(PrimaTotal) - Number(ccf) + Number(iva);
                document.getElementById('APagar').value = APagar.toFixed(2);
                document.getElementById('APagar').style.backgroundColor = 'yellow';
                document.getElementById('Facturar').value = (Number(PrimaTotal) + Number(iva)).toFixed(2);
            })


            function calculoCCF() {
                var comision = document.getElementById('TasaComision').value;
                var total = document.getElementById('PrimaDescontada').value;
                var valorDes = total * (comision / 100);
                document.getElementById('Comision').value = Number(valorDes).toFixed(2);
                var IvaSobreComision = Number(valorDes) * 0.13;
                document.getElementById('IvaSobreComision').value = Number(IvaSobreComision).toFixed(2);
                if (document.getElementById('Retencion').hasAttribute('readonly')) {
                    var Retencion = 0;
                } else {
                    var Retencion = valorDes * 0.01;
                    document.getElementById('Retencion').value = Retencion;
                }
                var ValorCCF = Number(valorDes) + Number(IvaSobreComision) - Number(Retencion);
                // alert(ValorCCF);
                document.getElementById('ValorCCFE').value = Number(ValorCCF).toFixed(2);
                document.getElementById('ValorCCF').value = Number(ValorCCF).toFixed(2);
                var PrimaTotal = document.getElementById('SubTotal').value;
                var iva = document.getElementById('Iva').value;
                var APagar = Number(PrimaTotal) - Number(ValorCCF) + Number(iva);
                document.getElementById('APagar').value = APagar.toFixed(2);
                document.getElementById('Facturar').value = (Number(PrimaTotal) + Number(iva)).toFixed(2);

            }



        })

        function modal_edit(id) {

            // document.getElementById('ModalSaldoA').value = "";
            // document.getElementById('ModalImpresionRecibo').value = "";
            document.getElementById('ModalComentario').value = "";
            document.getElementById('ModalEnvioCartera').value = "";
            document.getElementById('ModalEnvioPago').value = "";
            document.getElementById('ModalPagoAplicado').value = "";
            document.getElementById('ModalId').value = id;



            $.get("{{ url('polizas/residencia/get_pago') }}" + '/' + id, function(data) {


                console.log(data);
                if (data.SaldoA != null) {
                    document.getElementById('ModalSaldoA').value = data.SaldoA.substring(0, 10);
                }

                if (data.ImpresionRecibo != null) {
                    document.getElementById('ModalImpresionRecibo').value = data.ImpresionRecibo.substring(0, 10);
                    $("#ModalEnvioCartera").removeAttr("readonly");
                }



                document.getElementById('ModalComentario').value = data.Comentario;
                if (data.EnvioCartera) {
                    document.getElementById('ModalEnvioCartera').value = data.EnvioCartera.substring(0, 10);
                    $("#ModalEnvioCartera").prop("readonly", true);
                } else {
                    $("#ModalEnvioPago").prop("readonly", true);
                    $("#ModalPagoAplicado").prop("readonly", true);
                }


                if (data.EnvioPago) {
                    document.getElementById('ModalEnvioPago').value = data.EnvioPago.substring(0, 10);
                    $("#ModalEnvioPago").prop("readonly", true);
                } else {
                    //  $("#ModalEnvioCartera").prop("readonly", true);
                    $("#ModalPagoAplicado").prop("readonly", true);
                }

                if (data.PagoAplicado) {
                    document.getElementById('ModalPagoAplicado').value = data.PagoAplicado.substring(0, 10);

                    $("#ModalEnvioCartera").prop("readonly", true);
                    $("#ModalEnvioPago").prop("readonly", true);
                    $("#ModalPagoAplicado").prop("readonly", true);
                }
                // // else {
                //     $("#ModalEnvioCartera").prop("readonly", true);
                //     $("#ModalEnvioPago").prop("readonly", true);
                // }



            });
            $('#modal_editar_pago').modal('show');

        }
    </script>

    <script>
        document.getElementById('FormArchivo').addEventListener('submit', function() {
            // Show loading indicator
            document.getElementById('loading-indicator').style.display = 'block';
        });
    </script>

<script>
    function formatLimiteGrupo() {
        let input = document.getElementById('LimiteGrupoDisplay');
        input.value = input.value.replace(/[^\d.,]/g, ''); // Solo permite números, coma y punto
    }

    function updateLimiteGrupo() {
        let inputDisplay = document.getElementById('LimiteGrupoDisplay');
        let inputReal = document.getElementById('LimiteGrupo');
        let value = inputDisplay.value.replace(/,/g, ''); // Elimina las comas
        inputReal.value = parseFloat(value).toFixed(2); // Actualiza el valor en formato numérico
        inputDisplay.value = parseFloat(value).toLocaleString().replace(/\./g, ','); // Formatea el valor con coma para separación de miles
    }
</script>

<script>
    function validateLimiteIndividual() {
        let input = document.getElementById('LimiteIndividualDisplay');
        input.value = input.value.replace(/[^\d.,]/g, ''); // Solo permite números, coma y punto
    }

    function changeIndividual() {
        let inputDisplay = document.getElementById('LimiteIndividualDisplay');
        let inputReal = document.getElementById('LimiteIndividual');
        let value = inputDisplay.value.replace(/,/g, ''); // Elimina las comas
        inputReal.value = parseFloat(value); // Actualiza el valor en formato numérico
        inputDisplay.value = parseFloat(value).toLocaleString().replace(/\./g, ','); // Formatea el valor con coma para separación de miles
    }
</script>


@endsection
