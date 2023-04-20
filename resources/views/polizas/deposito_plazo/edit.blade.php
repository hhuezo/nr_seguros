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

                    <form method="POST" action="{{ route('deposito_plazo.update', $depositoPlazo->Id) }}">
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
                                                value="{{ $depositoPlazo->NumeroPoliza }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Código</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Codigo" type="text"
                                                value="{{ $depositoPlazo->Codigo }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Aseguradora</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Aseguradora" type="text"
                                                value="{{ $depositoPlazo->aseguradoras->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Asegurado</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Asegurado" type="text"
                                                value="{{ $depositoPlazo->clientes->Nombre }}" readonly>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Nit" id="Nit" type="text"
                                                value="{{ $depositoPlazo->Nit }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Grupo
                                            Asegurado</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="GrupoAsegurado" row="3" col="4" value="" readonly>{{ $depositoPlazo->GrupoAsegurado }} </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                            Especiales</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" value="" readonly>{{ $depositoPlazo->ClausulasEspeciales }} </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Beneficios
                                            Adicionales</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4" value="" readonly>{{ $depositoPlazo->BeneficiosAdicionales }} </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Concepto</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <textarea class="form-control" name="Concepto" row="3" col="4" readonly> {{ $depositoPlazo->Concepto }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comentarios
                                            del Cobro</label>
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
                                            <input class="form-control" name="VigenciaDesde" type="text"
                                                value="{{ \Carbon\Carbon::parse($depositoPlazo->VigenciaDesde)->format('d/m/Y') }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                            Cartera</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TipoCartera" type="text"
                                                value="{{ $depositoPlazo->tipoCarteras->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Vendedor</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Ejecutivo" type="text"
                                                value="{{ $depositoPlazo->ejecutivos->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                                        </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            @if ($depositoPlazo->Mensual == 1)
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <input type="radio" name="tipoTasa" id="Mensual" value="1" checked>
                                                <label class="control-label">Tasa ‰ Millar Mensual</label>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <input type="radio" name="tipoTasa" id="Mensual" value="0">
                                                <label class="control-label">Tasa ‰ Millar Anual</label>
                                            </div>
                                            @else
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <input type="radio" name="tipoTasa" id="Mensual" value="1" >
                                                <label class="control-label">Tasa ‰ Millar Mensual</label>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <input type="radio" name="tipoTasa" id="Mensual" value="0" checked>
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
                                            <input class="form-control" name="MontoCartera" id="MontoCartera"
                                                type="number" step="any" value="{{ $detalle_last->MontoCartera }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Tasa" id="Tasa" type="number"
                                                step="any" value="{{ $depositoPlazo->Tasa }}" readonly>
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
                                            <input class="form-control" name="ExtraPrima" id="ExtraPrima" type="number"
                                                step="any" value="{{ $detalle_last->ExtraPrima }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima
                                            Total</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="PrimaTotal" id="PrimaTotal" type="number"
                                                step="any" value="{{ $detalle_last->PrimaTotal }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Descuento" id="Descuento" type="number"
                                                step="any" value="{{ $detalle_last->Descuento }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                            Prima Descontada</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="PrimaDescontada" type="number"
                                                step="any" id="PrimaDescontada"
                                                value="$detalle_last->PrimaDescontada">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Menos
                                            Valor CCF Comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorCCF" id="ValorCCF" type="number"
                                                step="any" value="{{ $detalle_last->ValorCCF }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">A
                                            Pagar</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="APagar" type="number" id="APagar"
                                                step="any" value="{{ $detalle_last->APagar }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                            Hasta</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="VigenciaHasta" type="text"
                                                value="{{ \Carbon\Carbon::parse($depositoPlazo->VigenciaHasta)->format('d/m/Y') }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Estatus</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="EstadoPoliza" type="text"
                                                value="{{ $depositoPlazo->estadoPolizas->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de
                                            Cobro</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TipoCobro" type="text"
                                                value="{{ $depositoPlazo->tipoCobros->Nombre }}" readonly>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-sm-12 col-xs-12"
                                            style="text-align: center;">Estructura CCF de comisión</label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision
                                            %</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TasaComision" id="TasaComision"
                                                type="number" step="any" value="{{ $detalle_last->TasaComision }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                            Desc</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="ValorDescuento" id="ValorDescuento"
                                                type="number" step="any"
                                                value="{{ $detalle_last->ValorDescuento }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                            IVA sobre comisión</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="IvaSobreComision" id="IvaSobreComision"
                                                type="number" step="any"
                                                value="{{ $detalle_last->IvaSobreComision }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                            Retención</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            @if ($depositoPlazo->clientes->TipoContribuyente < 2)
                                                <input class="form-control" name="Retencion" id="Retencion"
                                                    type="number" step="any" value="{{ $detalle_last->Retencion }}"
                                                    disabled>
                                            @else
                                                <input class="form-control" name="Retencion" id="Retencion"
                                                    type="number" step="any"
                                                    value="{{ $detalle_last->Retencion }}">
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
                                                value="{{ $depositoPlazo->NumeroUsuario1 }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right"
                                            style="margin-top: -3%;">Suma Asegurada 1 *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="SumaAsegurada1" type="number"
                                                step="0.01" value="{{ $depositoPlazo->SumaAsegurada1 }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 1
                                            *</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Prima1" type="number" step="0.01"
                                                value="{{ $depositoPlazo->Prima1 }}" required>
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

                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div>
                                <br>
                                <table class="table table-striped table-bordered">
                                    <tr>
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
    </div>
    @include('sweetalert::alert')
    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
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

            $("#MontoCartera").change(function() {
                if (document.getElementById('Anual').checked == false && document.getElementById('Mensual')
                    .checked == false) {
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


        })
    </script>
@endsection
