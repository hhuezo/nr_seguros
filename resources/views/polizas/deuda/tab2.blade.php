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
        <div class="btn btn-info float-right" data-toggle="modal" data-target="#modal_pago">
            Subir Archivo Excel</div>
    </ul>
    <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="FormArchivo" action="{{ url('polizas/residencia/create_pago') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Linea de Credito</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="LineaCredito" class="form-control">
                                    @foreach($creditos as $obj)
                                    <option value="{{ $obj->id }}">{{ $obj->tipoCarteras->Nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Axo" class="form-control">
                                    @for ($i = date('Y'); $i >= 2022; $i--)
                                    <option value="{{ $i }}">
                                        {{ $i }}
                                    </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Mes" class="form-control">
                                    @for ($i = 1; $i < 12; $i++) @if (date('m')==$i) <option value="{{ $i }}" selected>
                                        {{ $meses[$i] }}
                                        </option>
                                        @else
                                        <option value="{{ $i }}">
                                            {{ $meses[$i] }}
                                        </option>
                                        @endif
                                        @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                inicio</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Id" value="{{ $deuda->Id }}" type="hidden" required>
                                <input class="form-control" type="date" name="FechaInicio" value="{{$ultimo_pago ?  date('Y-m-d', strtotime($ultimo_pago->FechaFinal)) : '' }}" {{$ultimo_pago ? 'readonly':''}} required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                final</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="FechaFinal" value="{{$ultimo_pago_fecha_final ? $ultimo_pago_fecha_final:''}}" type="date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Archivo" type="file" required>
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Subir Cartera</button>
                    </div>
                </form>

                <div id="loading-indicator" style="text-align: center; display:none">
                    <img src="{{ asset('img/ajax-loader.gif') }}">
                    <br>
                </div>


            </div>
        </div>
    </div>
    <div>
        <form action="{{ url('polizas/residencia/agregar_pago') }}" method="POST">
            <div class="modal-header">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body row">
                    <input type="hidden" name="ExcelURL" id="ExcelURL" value="{{ session('ExcelURL') }}" class="form-control">
                    <input type="hidden" name="Deuda" id="Deuda" value="{{ $deuda->Id }}" class="form-control">
                    @csrf
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 ">

                        &nbsp;

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <label class="control-label" align="right">Fecha Inicio</label>
                        <div class="form-group row">
                            <input class="form-control" name="FechaInicio" id="FechaInicio" type="date" value="{{ session('FechaInicio') }}" required>
                        </div>

                        <div class="form-group row" style="margin-top:-3%;">
                            <label class="control-label" align="right">Monto Cartera </label>

                            <div class="form-group has-feedback">
                                <input class="form-control" name="MontoCartera" onblur="show_MontoCartera()" id="MontoCartera" type="number" step="any" style="text-align: right; display: none;" value="{{ session('MontoCartera', 0) }}" required>
                                <input class="form-control" id="MontoCarteraView" type="text" step="any" style="text-align: right;" value="{{ number_format(session('MontoCartera', 0), 2, '.', ',') }}" required>
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="control-label" align="right">Tasa %</label>
                            <div class="form-group has-feedback">
                                <input type="number" step="any" style="padding-left: 25%;" name="Tasa" id="Tasa" value="{{ $deuda->Tasa }}" class="form-control" readonly>
                                <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row" style="margin-top:-4%;">
                            <label class="control-label " align="right">Tasa por millar
                            </label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" style="text-align: right;" id="tasaFinal" class="form-control" readonly>
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row" style="margin-top:-4%;">
                            <label class="control-label " align="right">Prueba
                                Decimales</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" readonly id="PruebaDecimales" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row" style="margin-top:-4%;">
                            <label class="control-label " align="right">Prima
                                Calculada</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="PrimaCalculada" id="PrimaCalculada" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row ">
                            <label class="control-label " align="right">Extra
                                Prima</label>


                            <div class="form-group has-feedback">
                                <input class="form-control" name="ExtraPrima" type="number" step="any" id="ExtPrima" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>

                        <div class="form-group row ">
                            <label class="control-label" align="right">
                                Prima Total</label>
                            <div class="form-group has-feedback">
                                <input class="form-control" name="PrimaTotal" type="number" step="any" id="PrimaTotal" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                        <div class="form-group row ocultar ">
                            <label class="control-label" align="right">Tasa de Descuento %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="form-group has-feedback">
                                    <input class="form-control" name="TasaDescuento" type="number" step="any" id="TasaDescuento" style="padding-left: 25%;" value="{{ $deuda->TasaDescuento }}" readonly>
                                    <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">(-) Descuento
                                Rentabilidad {{ $deuda->TasaDescuento }} %</label>


                            <div class="form-group has-feedback">
                                <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">
                                (=) Prima Descontada</label>
                            <div class="form-group has-feedback">
                                <input class="form-control" name="PrimaDescontada" type="number" step="any" id="PrimaDescontada" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>
                        <input type="hidden" name="Bomberos" id="Bomberos" value="{{ $bomberos }}">
                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">(+) Impuestos
                                Bomberos</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="ImpuestoBomberos" id="ImpuestoBomberos" class="form-control" readonly style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">Gastos emisión</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="GastosEmision" id="GastosEmision" value="0" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>

                            </div>
                        </div>
                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">Otros</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="Otros" id="Otros" value="0" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">Sub Total</label>
                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="SubTotal" id="SubTotal" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">13% IVA</label>
                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="Iva" id="Iva" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>


                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">Menos valor CCF de
                                comision</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="ValorCCF" id="ValorCCF" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                            <!-- <a href="" data-target="#modal-calculator" data-toggle="modal" class="col-md-1 control-label" style="text-align: center;"><span class="fa fa-calculator fa-lg"></span></a> -->

                        </div>

                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">A pagar</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="APagar" id="APagar" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                        <div class="form-group row" style="margin-top:-5%;">
                            <label class="control-label" align="right">Total Factura</label>


                            <div class="form-group has-feedback">
                                <input type="number" step="any" name="Facturar" id="Facturar" class="form-control" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 ">

                        &nbsp;

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                        <div class="form-group row">
                            <label class="control-label" align="right">Fecha Final</label>
                            <input class="form-control" name="FechaFinal" id="FechaFinal" type="date" value="{{ session('FechaFinal') }}" required>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                        </div>
                        <div class="form-group row">
                            <label class="control-label" align="right">% Comisión</label>


                            <div class="form-group has-feedback">
                                <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" style="padding-left: 25%;" value="{{ $deuda->Comision }}" readonly>
                                <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="control-label" align="right" style="margin-top:-4%;">% Comisión</label>


                            <div class="form-group has-feedback">
                                <input class="form-control" name="Comision" id="Comision" type="number" step="any" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group row" style="margin-top:-4%;">
                            <label class="control-label" align="right">(+) 13% IVA </label>


                            <div class="form-group has-feedback">
                                <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="any" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>

                        <div class="form-group row" style="margin-top:-4%;">
                            <label class="control-label" align="right">Menos 1% Ret</label>


                            <div class="form-group has-feedback">
                                <input class="form-control" name="Retencion" id="Retencion" type="number" step="any" style="text-align: right;" @if ($deuda->clientes->TipoContribuyente == 1 || $deuda->clientes->TipoContribuyente == 4) readonly @endif>
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>

                        <div class="form-group row" style="margin-top:-4%;">
                            <label class="control-label" align="right">Valor CCF
                                Comisión</label>


                            <div class="form-group has-feedback">
                                <input class="form-control" id="ValorCCFE" type="number" step="any" style="text-align: right;">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>

                        </div>
                        <div class="form-group" style="margin-top:-4%;">
                            <div class="col-sm-12">
                                <label class="control-label">Comentario</label>
                                <textarea name="Comentario" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-aplicar">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Aplicación de pago</h4>
                            </div>
                            <div class="modal-body">
                                <p>¿Esta seguro/a que desea aplicar el pago?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button id="boton_pago" class="btn btn-primary">Confirmar
                                    Pago</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div align="center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" data-target="#modal-aplicar" data-toggle="modal" onclick="aplicarpago()">Generar Cobro</a>
                </div>


            </div>
        </form>
    </div>
</div>