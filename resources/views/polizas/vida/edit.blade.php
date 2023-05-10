@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

    <style>
        @media screen and (min-width: 992px) {
            .modal-lg {
                width: 1100px !important;
            }
        }
    </style>

    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Editar Poliza Vida &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VICO - Vida Colectivo Seguros<small></small>
                    </h2>
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

                <div class="x_content">
                    <br />

                    <form method="POST" action="{{ route('vida.update', $vida->Id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-horizontal" style="font-size: 12px;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                            style="margin-top: -3%;">Número de Póliza</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="NumeroPoliza" type="text"
                                                value="{{ $vida->NumeroPoliza }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Código</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Codigo" type="text"
                                                value="{{ $vida->Codigo }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Aseguradora</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Aseguradora" type="text"
                                                value="{{ $vida->aseguradoras->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Asegurado</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Asegurado" type="text"
                                                value="{{ $vida->clientes->Nombre }}" readonly>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Nit" id="Nit" type="text"
                                                value="{{ $vida->Nit }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Grupo
                                            Asegurado</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="GrupoAsegurado" row="3" col="4" value="" readonly>{{ $vida->GrupoAsegurado }} </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                            Especiales</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" value="" readonly>{{ $vida->ClausulasEspeciales }} </textarea>
                                        </div>
                                    </div>




                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                            Desde</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="VigenciaDesde" type="text"
                                                value="{{ \Carbon\Carbon::parse($vida->VigenciaDesde)->format('d/m/Y') }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                            Cartera</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TipoCartera" type="text"
                                                value="{{ $vida->tipoCarteras->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Vendedor</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Ejecutivo" type="text"
                                                value="{{ $vida->ejecutivos->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                                        </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            @if ($vida->Mensual == 1)
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                                        checked disabled>
                                                    <label class="control-label">Tasa ‰ Millar Mensual</label>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <input type="radio" name="tipoTasa" id="Anual" value="0"
                                                        disabled>
                                                    <label class="control-label">Tasa ‰ Millar Anual</label>
                                                </div>
                                            @else
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                                        disabled>
                                                    <label class="control-label">Tasa ‰ Millar Mensual</label>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <input type="radio" name="tipoTasa" id="Anual" value="0"
                                                        checked disabled>
                                                    <label class="control-label">Tasa ‰ Millar Anual</label>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                            Cartera
                                        </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="MontoCartera" type="number"
                                                step="any" value="{{ $vida->MontoCartera }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Tasa" id="Tasa" type="number"
                                                step="any" value="{{ $vida->Tasa }}" readonly>
                                        </div>
                                    </div>


                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                            Hasta</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="VigenciaHasta" type="text"
                                                value="{{ \Carbon\Carbon::parse($vida->VigenciaHasta)->format('d/m/Y') }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Estatus</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="EstadoPoliza" type="text"
                                                value="{{ $vida->estadoPolizas->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de
                                            Cobro</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TipoCobro" type="text"
                                                value="{{ $vida->tipoCobros->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Concepto</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="Concepto" row="3" col="4" readonly> {{ $vida->Concepto }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Beneficios
                                            Adicionales</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4" value="" readonly>{{ $vida->BeneficiosAdicionales }} </textarea>
                                        </div>
                                    </div>

                                    <br>

                                </div>
                                <br><br>
                                <div class="x_title">
                                    <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <br>
                            </div>
                            @if ($vida->TipoCobro == 1)
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <br><br>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                                Usuario 1 *</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroUsuario1" type="number"
                                                    value="{{ $vida->NumeroUsuario1 }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                                style="margin-top: -3%;">Suma Asegurada 1 *</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="SumaAsegurada1" type="number"
                                                    step="0.01" value="{{ $vida->SumaAsegurada1 }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                                1
                                                *</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Prima1" type="number" step="0.01"
                                                    value="{{ $vida->Prima1 }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                                Usuario 2</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroUsuario2" type="number"
                                                    value="{{ old('NumeroUsuario2') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                                style="margin-top: -3%;">Suma Asegurada 2</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="SumaAsegurada2" type="number"
                                                    step="0.01" value="{{ old('SumaAsegurada2') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                                2</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Prima2" type="number" step="0.01"
                                                    value="{{ old('Prima2') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                                Usuario 3</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroUsuario3" type="number"
                                                    value="{{ old('NumeroUsuario3') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                                style="margin-top: -3%;">Suma Asegurada 3</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="SumaAsegurada3" type="number"
                                                    step="0.01" value="{{ old('SumaAsegurada3') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                                3</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Prima3" type="number" step="0.01"
                                                    value="{{ old('Prima3') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                                Usuario 4</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroUsuario4" type="number"
                                                    value="{{ old('NumeroUsuario4') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                                style="margin-top: -3%;">Suma Asegurada 4</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="SumaAsegurada4" type="number"
                                                    step="0.01" value="{{ old('SumaAsegurada4') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                                4</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Prima4" type="number" step="0.01"
                                                    value="{{ old('Prima4') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                                Usuario 5</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroUsuario5" type="number"
                                                    value="{{ old('NumeroUsuario5') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                                style="margin-top: -3%;">Suma Asegurada 5</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="SumaAsegurada5" type="number"
                                                    step="0.01" value="{{ old('SumaAsegurada5') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                                5</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Prima5" type="number" step="0.01"
                                                    value="{{ old('Prima5') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                                Usuario 6</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroUsuario6" type="number"
                                                    value="{{ old('NumeroUsuario6') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                                style="margin-top: -3%;">Suma Asegurada 6</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="SumaAsegurada6" type="number"
                                                    step="0.01" value="{{ old('SumaAsegurada6') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                                6</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Prima6" type="number" step="0.01"
                                                    value="{{ old('Prima6') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            @endif


                            <br><br>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 1px solid;">
                                <br>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Impresión
                                            de Recibo</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ImpresionRecibo" id="ImpresionRecibo"
                                                type="text"
                                                value="{{ \Carbon\Carbon::parse($detalle_last->ImpresionRecibo)->format('d/m/Y') }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                            Cartera</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="EnvioCartera" id="EnvioCartera"
                                                type="text" value="{{ $detalle_last->EnvioCartera }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                            Pago</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="EnvioPago" id="EnvioPago" type="text"
                                                value="{{ $detalle_last->EnvioPago }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Saldo
                                            A</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="SaldoA" id="SaldoA" type="text"
                                                style="background-color: yellow; "
                                                value="{{ \Carbon\Carbon::parse($detalle_last->ImpresionRecibo)->format('d/m/Y') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Pago
                                            Aplicado</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="PagoAplicado" id="PagoAplicado"
                                                type="text" value="{{ $detalle_last->PagoAplicado }}">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="x_title">
                                <br>&nbsp;
                                <br>&nbsp;
                                <br>&nbsp;
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <h2>Pagos<small></small>
                                </h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <div class="btn btn-info float-right" data-toggle="modal"
                                        data-target=".bs-example-modal-lg">Nuevo</div>
                                </ul>
                                <div class="clearfix"></div>
                            </div>

                            <div>
                                <br>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th><br><i class="fa fa-pencil"></i></th>
                                        <th>Tasa</th>
                                        <th>Descuento</th>
                                        <th>A Pagar</th>
                                        <th>Impresion de Recibo</th>
                                        <th>Envio de Cartera</th>
                                        <th>Envio de Pago</th>
                                        <th>Pago Aplicado</th>
                                    </tr>
                                    @foreach ($detalle as $obj)
                                        <tr>
                                            <td><i class="fa fa-pencil" onclick="modal_edit({{ $obj->Id }})"></i>
                                            </td>
                                            <td>{{ $obj->Tasa }}</td>
                                            <td>{{ $obj->Descuento }}</td>
                                            <td>{{ $obj->APagar }}</td>
                                            <td>{{ $obj->ImpresionRecibo }}</td>
                                            <td>{{ $obj->EnvioCartera }}</td>
                                            <td>{{ $obj->EnvioPago }}</td>
                                            <td>{{ $obj->PagoAplicado }}</td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>



                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <span id="habilitar" class="btn btn-warning">Habilitar</span>
                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                    <a href="{{ url('poliza/depsoito_plazo') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>






        <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ url('polizas/vida/create_pago') }}" method="POST">
                        <div class="modal-header">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="box-body row">
                                <input type="hidden" name="Id" id="Id" value="{{ $vida->Id }}"
                                    class="form-control">
                                @csrf

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                            Inicio</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="FechaInicio" id="FechaInicio"
                                                type="date" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                            Final</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="FechaFinal" id="FechaFinal" type="date"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                            Cartera
                                        </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="MontoCartera" id="MontoCartera"
                                                type="number" step="any" value="{{ $vida->MontoCartera }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Tasa" id="Tasa" type="number"
                                                step="any" value="{{ $vida->Tasa }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Sub
                                            Total</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="SubTotal" type="number" id="SubTotal"
                                                step="any" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Extra
                                            Prima</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ExtraPrima" id="ExtPrima" type="number"
                                                step="any">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                            Total</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="PrimaTotal" id="PrimaTotal" type="number"
                                                step="any" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Descuento" id="Descuento" type="number"
                                                step="any">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                            Prima Descontada</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="PrimaDescontada" type="number"
                                                step="any" id="PrimaDescontada">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Menos
                                            Valor CCF Comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorCCF" id="ValorCCF" type="number"
                                                step="any">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">A
                                            Pagar</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="APagar" type="number" id="APagar"
                                                step="any">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">

                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-sm-12 col-xs-12"
                                            style="text-align: center;">Estructura CCF de comisión</label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TasaComision" id="TasaComision"
                                                type="number" step="any">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                            Desc</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorDescuento" id="ValorDescuento"
                                                type="number" step="any">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                            IVA sobre comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="IvaSobreComision" id="IvaSobreComision"
                                                type="number" step="any">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                            Retención</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            @if ($vida->clientes->TipoContribuyente < 2)
                                                <input class="form-control" name="Retencion" id="Retencion"
                                                    type="number" step="any" disabled>
                                            @else
                                                <input class="form-control" name="Retencion" id="Retencion"
                                                    type="number" step="any">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF
                                            Comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorCCF" id="ValorCCFE" type="number"
                                                step="any">
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Aceptar</button>
                            </div>
                    </form>
                </div>


            </div>
        </div>


    </div>


    <div class="modal fade " id="modal_editar_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
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
                                    <input type="date" name="ImpresionRecibo" id="ModalImpresionRecibo"
                                        class="form-control" readonly>
                                </div>
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
                                    <input type="date" name="EnvioPago" id="ModalEnvioPago" class="form-control">
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
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @include('sweetalert::alert')
    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $("#MontoCartera").change(function() {
                calculoSubTotal();
                calculoPrimaTotal();
                calculoPrimaDescontada();
                calculoCCF();
            })

            function calculoSubTotal() {
                var monto = document.getElementById('MontoCartera').value;
                var tasa = document.getElementById('Tasa').value;
                if (document.getElementById('Anual').checked == true) {
                    var tasaFinal = (tasa / 1000) / 12;
                } else {
                    var tasaFinal = tasa / 1000;
                }
                var sub = Number(monto) * Number(tasaFinal);
                document.getElementById('SubTotal').value = sub;
            }
            $('#ExtPrima').change(function() {
                calculoPrimaTotal();
                calculoPrimaDescontada();
                calculoCCF();
            })

            function calculoPrimaTotal() {
                var sub = document.getElementById('SubTotal').value;
                var extra = document.getElementById('ExtPrima').value;
                var prima = Number(sub) + Number(extra);
                document.getElementById('PrimaTotal').value = Number(prima);
            }
            $("#Descuento").change(function() {
                calculoPrimaDescontada();
                calculoCCF();
            })

            function calculoPrimaDescontada() {
                var prima = document.getElementById('PrimaTotal').value;
                var descuento = document.getElementById('Descuento').value;
                if (descuento == 0) {
                    var total = Number(prima);
                } else {
                    var total = Number(prima * (descuento / 100));
                }
                document.getElementById('PrimaDescontada').value = total + Number(document.getElementById(
                    'PrimaTotal').value);

            }

            $("#TasaComision").change(function() {
                calculoCCF();
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
                var PrimaTotal = document.getElementById('PrimaTotal').value;
                var APagar = Number(PrimaTotal) - Number(ValorCCF);
                document.getElementById('APagar').value = APagar;
            }

            $("#habilitar").click(function() {
                //  $("#btn_guardar").click(function() {
                //  document.getElementById('ImpresionRecibo').removeAttribute('readonly');
                document.getElementById('ImpresionRecibo').value = '';
                document.getElementById('EnvioCartera').type = 'date';
                document.getElementById('EnvioPago').type = 'date';
                document.getElementById('PagoAplicado').type = 'date';
                document.getElementById('SaldoA').type = 'date';
                document.getElementById('ValorDescuento').value = 0;
                document.getElementById('IvaSobreComision').value = 0;
                document.getElementById('Retencion').value = 0;
                document.getElementById('ValorCCFE').value = 0;

            })
            $('#SaldoA').change(function() {
                var hoy = new Date().toLocaleDateString();
                //alert(hoy);
                document.getElementById('ImpresionRecibo').value = hoy;
                document.getElementById('ImpresionRecibo').setAttribute("readonly", true);
            })
            $('#EnvioCartera').change(function() {
                var hoy = new Date();
                // alert(hoy);
                if (document.getElementById('ImpresionRecibo').value <= document.getElementById(
                        'EnvioCartera')) {
                    alert('debe seleccionar una fecha mayor o igual a la impresion recibo');
                }

            })
            $('#EnvioPago').change(function() {
                var hoy = new Date();
                // alert(hoy);
                if (document.getElementById('EnvioCartera').value <= document.getElementById('EnvioPago')) {
                    alert('debe seleccionar una fecha mayor o igual a la envio de cartera');
                }

            })
            $('#PagoAplicado').change(function() {
                var hoy = new Date();
                // alert(hoy);
                if (document.getElementById('EnvioPago').value <= document.getElementById('PagoAplicado')) {
                    alert('debe seleccionar una fecha mayor o igual a la envio de pago');
                }

            })


        });

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
