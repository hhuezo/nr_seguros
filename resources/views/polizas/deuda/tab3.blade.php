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
        <table class="table table-striped jambo_table bulk_action" style="font-size: 12px;">
            <tr>
                <td colspan="4">
                    <center><strong>Base Cálculo de la Prima </strong></center>
                </td>
            </tr>
            @if ($totalUltimoPago && $totalUltimoPago->isNotEmpty())
                @foreach ($totalUltimoPago as $pago)
                    <tr>
                        <td>{{ $pago->TipoCarteraNombre }} {{ $pago->LineaCreditoAbreviatura }}</td>
                        <td style="text-align: right; "><span class="fa fa-dollar " aria-hidden="true"></span></td>
                        <td style="text-align: right; ">
                            <label type="text"
                                class="label-control has-feedback-left">{{ number_format($pago->TotalCredito, 2, '.', ',') }}
                            </label>
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            @endif
            <tr>

                <td> Monto Cartera</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left">
                        {{ $ultimo_pago ? number_format($ultimo_pago->MontoCartera, 2, '.', ',') : 0 }} </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Tasa mensual por millar</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left" style="text-align: right;">{{ $deuda->Tasa }}
                    </label>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>Prima Calculada </td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->PrimaCalculada, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Extra Prima </td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left" style="text-align: right;">
                        {{ $ultimo_pago ? number_format($ultimo_pago->ExtraPrima, 2, '.', ',') : 0 }} </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>(-) Descuento Rentabilidad {{ $deuda->TasaDescuento }}%</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->Descuento, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>(=) Prima Descontada</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left" style="text-align: right;">
                        {{ $ultimo_pago ? number_format($ultimo_pago->PrimaDescontada, 2, '.', ',') : 0 }} </label>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>(-) Estructura CCF de Comisión</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->ValorCCF, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
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
                <td style="text-align: right; vertical-align: middle; "><span class="fa fa-dollar "
                        aria-hidden="true"></span></td>
                <td style="text-align: right; vertical-align: middle;">
                    <label class="label-control has-feedback-left" style="text-align: right; font-size: 15px;">
                        {{ $ultimo_pago ? number_format($ultimo_pago->APagar, 2, '.', ',') : 0 }} </label>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">


        <table class="table table-striped jambo_table bulk_action" style="font-size: 12px;">
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td>Comisión</td>
                <td style="text-align: left;" colspan="2"><span class="fa fa-percent " aria-hidden="true"></span>
                    <label class="label-control has-feedback-left"
                        style="padding-left: 25%;">{{ $ultimo_pago ? $ultimo_pago->TasaComision : 0 }} </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>(=) Prima a cobrar</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->PrimaDescontada, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Valor por Comisión</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->Comision, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Más 13% IVA sobre comisión</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->IvaSobreComision, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Sub Total Comisión</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->Comision + $ultimo_pago->IvaSobreComision, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Menos 1% Retención</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->Retencion, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Valor CCF por Comisión</td>
                <td style="text-align: right;"><span class="fa fa-dollar " aria-hidden="true"></span></td>
                <td style="text-align: right;">
                    <label class="label-control has-feedback-left"
                        style="text-align: right;">{{ $ultimo_pago ? number_format($ultimo_pago->ValorCCF, 2, '.', ',') : 0 }}
                    </label>
                </td>
                <td></td>
            </tr>

        </table>
    </div>
</div>
