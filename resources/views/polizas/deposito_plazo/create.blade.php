@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nuevo Deposito de Plazo &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VICO - Vida Colectivo Seguros<small></small>
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

                <form action="{{ url('polizas/deposito_plazo') }}" method="POST">
                    @csrf
                    <div class="form-horizontal" style="font-size: 12px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Número de Póliza</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroPoliza" type="text" value="{{ old('NumeroPoliza') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Codigo" type="text" value="{{ old('Codigo') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($aseguradora as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($cliente as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12"><i onclick="modal_cliente();" class="fa fa-plus fa-lg" style="padding-top: 60%;"></i></div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Nit" id="Nit" type="text" value="{{ old('Nit') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Grupo
                                        Asegurado</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="GrupoAsegurado" row="3" col="4" value="{{ old('GrupoAsegurado') }}" required> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                        Especiales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" value="{{ old('ClausulasEspeciales') }}"> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Beneficios
                                        Adicionales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4" value="{{ old('BeneficiosAdicionales') }}"> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Concepto</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="Concepto" row="3" col="4" value="{{ old('Concepto') }}" required> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comentarios del Cobro</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="Comentario" row="3" col="4" value="{{ old('Comentario') }}"> </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Desde</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        Cartera</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoCartera" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($tipoCartera as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($ejecutivo as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <!-- radio button -->
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-check-input" type="checkbox" name="tipoTasa" id="Mensual" value="1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Tasa ‰ Millar Mensual
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                                        <input class="form-check-input" type="checkbox" name="tipoTasa" id="Anual" value="2">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Tasa ‰ Millar Anual
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto Cartera
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="MontoCartera" id="MontoCartera" type="number" step="any" value="{{ old('MontoCartera') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa ‰
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa" type="number" id="Tasa" step="any" value="{{ old('Tasa') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Sub
                                        Total</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SubTotal" type="number" id="SubTotal" step="any" value="{{ old('PrimaTotal') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Extra
                                        Prima</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ExtraPrima" type="number" step="any" id="ExtPrima" value="{{ old('ExtraPrima') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                        Prima Total</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="PrimaTotal" type="number" step="any" id="PrimaTotal" value="{{ old('ExtraPrima') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ old('Descuento') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                        Prima Descontada</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="PrimaDescontada" type="number" step="any" id="PrimaDescontada" value="{{ old('ExtraPrima') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Menos
                                        Valor CCF Comisión</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ValorCCF" id="ValorCCF" type="number" step="any" value="{{ old('ValorCCF') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">A
                                        Pagar</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="APagar" type="number" id="APagar" step="any" value="{{ old('APagar') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Hasta</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaHasta" type="date" value="{{ old('VigenciaHasta') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estatus</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($estadoPoliza as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de
                                        Cobro</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoCobro" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($tipoCobro as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <div class="form-group row">
                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{ old('TasaComision')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor Desc</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ValorDescuento" id="ValorDescuento" type="number" step="any" value="{{ old('ValorDescuento')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13% IVA sobre comisión</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="any" value="{{ old('IvaSobreComision')}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1% Retención</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                                        <input class="form-control" name="Retencion" id="Retencion" type="number" step="any" value="{{ old('Retencion')}}">

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF Comisión</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ValorCCF" id="ValorCCFE" type="number" step="any">
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <br>
                        </div>

                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 1px solid;">
                            <br>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Impresión
                                        de Recibo</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ImpresionRecibo" type="date" value="{{ date('Y-m-d') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                        Cartera</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="EnvioCartera" type="date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                        Pago</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="EnvioPago" type="date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Saldo
                                        A</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SaldoA" type="date" style="background-color: yellow;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Pago
                                        Aplicado</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="PagoAplicado" type="date">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br><br>
                        <div class="x_title">
                            <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                            <div class="clearfix"></div>
                        </div> -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br><br>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                        Usuario 1 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario1" type="number" value="{{ old('NumeroUsuario1') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 1 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada1" type="number" step="0.01" value="{{ old('SumaAsegurada1') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 1
                                        *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima1" type="number" step="0.01" value="{{ old('Prima1') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                        Usuario 2</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario2" type="number" value="{{ old('NumeroUsuario2') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 2</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada2" type="number" step="0.01" value="{{ old('SumaAsegurada2') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                        2</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima2" type="number" step="0.01" value="{{ old('Prima2') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                        Usuario 3</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario3" type="number" value="{{ old('NumeroUsuario3') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 3</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada3" type="number" step="0.01" value="{{ old('SumaAsegurada3') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                        3</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima3" type="number" step="0.01" value="{{ old('Prima3') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                        Usuario 4</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario4" type="number" value="{{ old('NumeroUsuario4') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 4</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada4" type="number" step="0.01" value="{{ old('SumaAsegurada4') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                        4</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima4" type="number" step="0.01" value="{{ old('Prima4') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                        Usuario 5</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario5" type="number" value="{{ old('NumeroUsuario5') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 5</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada5" type="number" step="0.01" value="{{ old('SumaAsegurada5') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                        5</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima5" type="number" step="0.01" value="{{ old('Prima5') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                        Usuario 6</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario6" type="number" value="{{ old('NumeroUsuario6') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 6</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada6" type="number" step="0.01" value="{{ old('SumaAsegurada6') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                        6</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima6" type="number" step="0.01" value="{{ old('Prima6') }}">
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('poliza/depsoito_plazo') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>

                    </div>
                </form>



            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('catalogo/cliente_create') }}" method="POST">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo cliente</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">NIT</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nit" id="ModalNit" data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dui</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Dui" id="ModalDui" data-inputmask="'mask': ['99999999-9']" data-mask type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nombre" id="ModalNombre" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionResidencia" id="ModalDireccionResidencia"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    correspondecia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionCorrespondencia" id="ModalDireccionCorrespondencia"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoResidencia" id="ModalTelefonoResidencia" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    oficina</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoOficina" id="ModalTelefonoOficina" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    celular</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoCelular" id="ModalTelefonoCelular" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo
                                    electrónico</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Correo" id="ModalCorreo" type="email">
                                </div>
                            </div>

                        </div>








                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ruta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Ruta" id="ModalRuta" class="form-control select2" style="width: 100%">
                                        @foreach ($rutas as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Responsable
                                    pago</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="ResponsablePago" id="ModalResponsablePago" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    contribuyente</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoContribuyente" id="ModalTipoContribuyente" class="form-control" style="width: 100%">
                                        @foreach ($tipos_contribuyente as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ubicación de
                                    cobro</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="UbicacionCobro" id="ModalUbicacionCobro" class="form-control" style="width: 100%">
                                        @foreach ($ubicaciones_cobro as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Contacto</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Contacto" id="ModalContacto" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Referencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Referencia" id="ModalReferencia" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número
                                    tarjeta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="NumeroTarjeta" id="ModalNumeroTarjeta" data-inputmask="'mask': ['9999-9999-9999-9999']" data-mask type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    vencimiento</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="FechaVencimiento" id="ModalFechaVencimiento" data-inputmask="'mask': ['99/99']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Género</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Genero" id="ModalGenero" class="form-control">
                                        <option value="1">Masculino</option>
                                        <option value="2">Femenino</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    persona</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoPersona" id="ModalTipoPersona" class="form-control">
                                        <option value="1">Natural</option>
                                        <option value="2">Jurídica</option>
                                    </select>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn_guardar" class="btn btn-primary">Aceptar</button>
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
        $("#Anual").change(function() {
            if (document.getElementById('Anual').checked == true) {
                document.getElementById('Mensual').setAttribute('disabled', true);

            } else {
                document.getElementById('Mensual').removeAttribute('disabled');
            }
        })

        $("#Mensual").change(function() {
            if (document.getElementById('Mensual').checked == true) {
                document.getElementById('Anual').setAttribute('disabled', true);

            } else {
                document.getElementById('Anual').removeAttribute('disabled');
            }
        })

        $("#Tasa").change(function() {
            if (document.getElementById('Anual').checked == false && document.getElementById('Mensual').checked == false) {
                alert('Debe seleccionar el tipo de tasa');
            } else {
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

        })
        $('#ExtPrima').change(function() {
            var sub = document.getElementById('SubTotal').value;
            var extra = document.getElementById('ExtPrima').value;
            var prima = Number(sub) + Number(extra);
            document.getElementById('PrimaTotal').value = Number(prima);
        })
        $('#Descuento').change(function() {
            var prima = document.getElementById('PrimaTotal').value;
            var descuento = document.getElementById('Descuento').value;
            if (descuento == 0) {
                var total = Number(prima);
            } else {
                var total = Number(prima * (descuento / 100));
            }
            document.getElementById('PrimaDescontada').value = total;
        })
        $('#TasaComision').change(function() {
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
                    }


                }
            });
        });


    });


    $("#btn_guardar").click(function() {

        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nit": document.getElementById('ModalNit').value,
            "Dui": document.getElementById('ModalDui').value,
            "Nombre": document.getElementById('ModalNombre').value,
            "DireccionResidencia": document.getElementById('ModalDireccionResidencia').value,
            "DireccionCorrespondencia": document.getElementById('ModalDireccionCorrespondencia').value,
            "TelefonoResidencia": document.getElementById('ModalTelefonoResidencia').value,
            "TelefonoOficina": document.getElementById('ModalTelefonoOficina').value,
            "TelefonoCelular": document.getElementById('ModalTelefonoCelular').value,
            "Correo": document.getElementById('ModalCorreo').value,

            "Ruta": document.getElementById('ModalRuta').value,
            "ResponsablePago": document.getElementById('ModalResponsablePago').value,
            "TipoContribuyente": document.getElementById('ModalTipoContribuyente').value,
            "UbicacionCobro": document.getElementById('ModalUbicacionCobro').value,
            "Contacto": document.getElementById('ModalContacto').value,
            "Referencia": document.getElementById('ModalReferencia').value,
            "NumeroTarjeta": document.getElementById('ModalNumeroTarjeta').value,
            "FechaVencimiento": document.getElementById('ModalFechaVencimiento').value,
            "Genero": document.getElementById('ModalGenero').value,
            "TipoPersona": document.getElementById('ModalTipoPersona').value,
        };
        $.ajax({
            type: "post",
            url: "{{ url('catalogo/cliente_create') }}",
            data: parametros,
            success: function(data) {
                //console.log(data);
                //$('#response').html(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '" selected >' + data[i].Nombre +
                    '</option>';
                $("#Asegurado").html(_select);
                $('#modal_cliente').modal('hide');
            }
        })
    });


    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }
</script>
@endsection