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
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                            style="margin-top: -3%;">Número de Póliza</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="NumeroPoliza" type="text"
                                                value="{{ old('NumeroPoliza') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Código</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Codigo" type="text"
                                                value="{{ old('Codigo') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Aseguradora</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <select name="Aseguradora" class="form-control select2" style="width: 100%"
                                                required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($aseguradora as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Asegurado</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                            <select name="Asegurado" id="Asegurado" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($cliente as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12"><i onclick="modal_cliente();"
                                                class="fa fa-plus fa-lg" style="padding-top: 60%;"></i></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Nit" id="Nit" type="text"
                                                value="{{ old('Nit') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Grupo
                                            Asegurado</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="GrupoAsegurado" row="3" col="4" value="{{ old('GrupoAsegurado') }}"
                                                required> </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                            Especiales</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4"
                                                value="{{ old('ClausulasEspeciales') }}"> </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Beneficios
                                            Adicionales</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4"
                                                value="{{ old('BeneficiosAdicionales') }}"> </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Concepto</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="Concepto" row="3" col="4" value="{{ old('Concepto') }}" required> </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Comentarios del Cobro</label>
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
                                            <input class="form-control" name="VigenciaDesde" type="date"
                                                value="{{ old('VigenciaDesde') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                            Cartera</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <select name="TipoCartera" class="form-control select2" style="width: 100%"
                                                required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($tipoCartera as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Vendedor</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <select name="Ejecutivo" class="form-control select2" style="width: 100%"
                                                required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($ejecutivo as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Tasa" type="number" step="0.00000001"
                                                value="{{ old('Tasa') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                            Total</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="PrimaTotal" type="number"
                                                step="0.00000001" value="{{ old('PrimaTotal') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Descuento" type="number"
                                                step="0.00000001" value="{{ old('Descuento') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Extra
                                            Prima</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ExtraPrima" type="number"
                                                step="0.00000001" value="{{ old('ExtraPrima') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Menos
                                            Valor CCF Comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorCCF" type="number" step="0.00000001"
                                                value="{{ old('ValorCCF') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">A
                                            Pagar</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="APagar" type="number" step="0.00000001"
                                                value="{{ old('Apagar') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                            Hasta</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="VigenciaHasta" type="date"
                                                value="{{ old('VigenciaHasta') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Estatus</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <select name="EstadoPoliza" class="form-control select2" style="width: 100%"
                                                required>
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
                                            <select name="TipoCobro" class="form-control select2" style="width: 100%"
                                                required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($tipoCobro as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-sm-12 col-xs-12"
                                            style="text-align: center;">Estructura CCF de comisión</label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                            Desc</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorDescuento" type="number"
                                                step="0.00000001">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                            IVA sobre comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="IvaSobreComision" type="number"
                                                step="0.00000001">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                            Retención</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Retencion" id="Retencion" type="number"
                                                step="0.00000001">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF
                                            Comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorCCF" type="number"
                                                step="0.00000001">
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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 1px solid;">
                                <br>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Impresión
                                            de Recibo</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ImpresionRecibo" type="date"
                                                value="{{ date('Y-m-d') }}" readonly>
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
                                            <input class="form-control" name="SaldoA" type="date"
                                                style="background-color: yellow;">
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
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br><br>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No
                                            Usuario 1 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="NumeroUsuario1" type="number"
                                                value="{{ old('NumeroUsuario1') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                            style="margin-top: -3%;">Suma Asegurada 1 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="SumaAsegurada1" type="number"
                                                step="0.01" value="{{ old('SumaAsegurada1') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 1
                                            *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Prima1" type="number" step="0.01"
                                                value="{{ old('Prima1') }}" required>
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



                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
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
    </div>



    <div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-tipo="1">
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
                                        <input class="form-control" name="Nit" id="ModalNit"
                                            data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text"
                                            autofocus="true">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dui</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Dui" id="ModalDui"
                                            data-inputmask="'mask': ['99999999-9']" data-mask type="text"
                                            autofocus="true">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Nombre</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Nombre"  id="ModalNombre"type="text">
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
                                        <input class="form-control" name="TelefonoResidencia" id="ModalTelefonoResidencia"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                        oficina</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TelefonoOficina" id="ModalTelefonoOficina"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                        celular</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TelefonoCelular" id="ModalTelefonoCelular"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
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
                                        <select name="Ruta" class="form-control select2" style="width: 100%">
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
                                        <input class="form-control" name="ResponsablePago" type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        contribuyente</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoContribuyente" class="form-control" style="width: 100%">
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
                                        <select name="UbicacionCobro" class="form-control" style="width: 100%">
                                            @foreach ($ubicaciones_cobro as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Contacto</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Contacto" type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Referencia</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Referencia" type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número
                                        tarjeta</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroTarjeta"
                                            data-inputmask="'mask': ['9999-9999-9999-9999']" data-mask type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                        vencimiento</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="FechaVencimiento"
                                            data-inputmask="'mask': ['99/99']" data-mask type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Género</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="Genero" class="form-control">
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        persona</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoPersona" class="form-control">
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
                        if (data.TipoContribuyente < 2) {
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
                "TelefonoResidencia": document.getElementById('ModalTelefonoResidencia').value,
                "TelefonoOficina": document.getElementById('ModalTelefonoOficina').value,
                "TelefonoCelular": document.getElementById('ModalTelefonoCelular').value,
                "Correo": document.getElementById('ModalCorreo').value,
            };
            $.ajax({
                type: "post",
                url: "{{ url('catalogo/cliente_create') }}",
                data: parametros,
                success: function(data) {
                    console.log(data);
                    //$('#response').html(data);
                }
            })
        });


        function modal_cliente() {
            $('#modal_cliente').modal('show');
        }
    </script>
@endsection
