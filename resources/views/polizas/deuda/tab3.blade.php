<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="x_title">
        <h4>&nbsp;&nbsp; Cálculo de Cartera
            {{ $deuda->clientes->Nombre }}<small></small>
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
                    <th>{{ $deuda->aseguradoras->Nombre }} <br>
                        {{ $deuda->clientes->Nombre }} <br>
                        N° Póliza: {{ $deuda->NumeroPoliza }} <br>
                        Vigencia:
                        {{ \Carbon\Carbon::parse($deuda->VigenciaDesde)->format('d/m/Y') }}
                        al
                        {{ \Carbon\Carbon::parse($deuda->VigenciaHasta)->format('d/m/Y') }}
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
            <tr class="ocultar">
                <td>
                    <!-- Tasa @if ($deuda->Mensual == 1)
                                            Mensual
                                            @else
                                            Anual
                                            @endif Millar : -->


                    Tasa Anual %.
                </td>
                <td>
                    <div class="col-md-9 col-sm-9 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" value="{{ $deuda->Tasa }}" readonly>
                        <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <center><strong>Base Cálculo de la Prima </strong></center>
                </td>
            </tr>
            @foreach($creditos1 as $obj)
            <tr>
                <td>{{$obj->tipoCarteras->Nombre}} {{$obj->saldos->Abreviatura}}</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" id="MontoCartera2" 
                        value="{{ number_format($obj->TotalLiniaCredito, 2, '.', ',') }} " readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>

            @endforeach
            <tr>

                <td> Monto Cartera</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" id="MontoCartera2" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->MontoCartera, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Tasa mensual por millar</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" id="TasaMillar2" value="{{ $deuda->Tasa }} " readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
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
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" id="PrimaCalculada2" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->PrimaCalculada, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Extra Prima  </td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" id="ExtraPrima2" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->ExtraPrima, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>(-) Descuento Rentabilidad {{ $deuda->TasaDescuento }}%</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" id="DescuentoRentabilidad2" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Descuento, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>(=) Prima Descontada</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->PrimaDescontada, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
       
            <tr>
                <td>SubTotal</td>
                <td>

                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->SubTotal, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <!-- <tr>
                <td>13% IVA</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Iva, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr> -->
            <tr>
                <td>Total Factura</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->SubTotal + $ultimo_pago->Iva, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>(-) Estructura CCF de Comisión</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->ValorCCF, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
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
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->APagar, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
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
                        <input type="text" class="form-control has-feedback-left" style="padding-left: 25%;" value="@if ($ultimo_pago) {{ $ultimo_pago->TasaComision }} @else 0 @endif" readonly>
                        <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Valor por Comisión</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Comision, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Más 13% IVA sobre comisión</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->IvaSobreComision, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Menos 1% Retención</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->Retencion, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Valor CCF por Comisión</td>
                <td>
                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" style="text-align: right;" value="@if ($ultimo_pago) {{ number_format($ultimo_pago->ValorCCF, 2, '.', ',') }} @else 0 @endif" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </td>
            </tr>

        </table>
    </div>
</div>