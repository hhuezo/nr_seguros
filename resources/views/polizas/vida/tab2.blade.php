    <style>
        .excel-like-table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #cccccc;
        }

        .excel-like-table th,
        .excel-like-table td {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: left;
        }

        .excel-like-table th {
            background-color: #f2f2f2;
        }

        .excel-like-table tr:hover {
            background-color: #f5f5f5;
        }

        .excel-like-table td[contenteditable="true"]:hover {
            background-color: #e8f0fe;
            outline: none;
        }

        .excel-like-table td[contenteditable="true"]:focus {
            background-color: #e2effd;
            outline: 2px solid #4d90fe;
        }

        .numeric {
            text-align: right !important;
        }
    </style>


    <br>

    <div class="modal-body">
        <div class="box-body row">
            <br>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="excel-like-table">
                    <tr>
                        <td>Fecha Inicio:
                            {{ !empty($fechas->FechaInicio ?? null) ? date('d/m/Y', strtotime($fechas->FechaInicio)) : '' }}
                        </td>
                        <td>Fecha Final:
                            {{ !empty($fechas->FechaFinal ?? null) ? date('d/m/Y', strtotime($fechas->FechaFinal)) : '' }}
                        </td>
                        <td>Mes: {{ isset($fechas->Mes) && isset($meses[$fechas->Mes]) ? $meses[$fechas->Mes] : '' }}
                        </td>
                    </tr>
                </table>
                <br>
                <table class="excel-like-table">
                    <thead>
                        <tr>
                            <th>Tipo Cartera</th>
                            <th>Tasa millar</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Suma Asegurada</th>
                            <th>Prima Calculada</th>
                        </tr>
                    </thead>

                    @php
                        // Inicializar variables para totales
                        $totalSumaAsegurada = 0;
                        $totalPrimaCalculada = 0;
                    @endphp

                    @foreach ($dataPago as $item)
                        @php
                            // Convertir los valores formateados a números para sumar
                            $sumaAsegurada = str_replace(',', '', $item['SumaAsegurada']);
                            $primaCalculada = str_replace(',', '', $item['PrimaCalculada']);

                            // Acumular totales
                            $totalSumaAsegurada += floatval($sumaAsegurada);
                            $totalPrimaCalculada += floatval($primaCalculada);
                        @endphp
                        <tr>
                            <td contenteditable="true">{{ $item['TipoCartera'] }}</td>
                            <td contenteditable="true">{{ $item['Tasa'] }}</td>
                            <td contenteditable="true">{{ $item['Monto'] }}</td>
                            <td contenteditable="true">{{ $item['Fecha'] }}</td>
                            <td contenteditable="true" style="text-align: right;">{{ number_format($item['SumaAsegurada'], 2, '.', ',') }}</td>
                            <td contenteditable="true" style="text-align: right;">{{ number_format($item['PrimaCalculada'], 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                    <tr class="text-end">
                        <th colspan="4">Totales</th>
                        <td style="text-align: right;">{{ number_format($totalSumaAsegurada, 2, '.', ',') }}</td>
                        <td style="text-align: right;">{{ number_format($totalPrimaCalculada, 2, '.', ',') }}</td>
                    </tr>

                    {{-- <tr>
                        <td contenteditable="true" id="tasa_millar">
                            {{ $poliza_vida->Tasa }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="suma_asegurada"
                            onblur="actualizarCalculos()">
                            {{ number_format($cartera->SumaAsegurada, 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="prima_por_pagar"
                            onblur="actualizarCalculos()">
                            {{ number_format($cartera->SumaAsegurada * $poliza_vida->Tasa, 2, '.', ',') }}
                        </td>



                        <td class="numeric editable" contenteditable="true" id="saldo_capital"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['saldoCapital'], 2, '.', ',') }}
                        </td>
                        <td class="numeric total" contenteditable="true" id="intereses" onblur="actualizarCalculos()">
                            {{ number_format($data['intereses'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="intereses_covid"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['interesesCovid'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="intereses_moratorios"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['interesesMoratorios'], 2, '.', ',') }}
                        </td>
                        <td class="numeric editable" contenteditable="true" id="monto_nominal"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['montoNominal'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="monto_cartera"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['total'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="prima_por_pagar"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['primaPorPagar'], 2, '.', ',') }}
                        </td>
                    </tr>


                    <tr>
                        <th>Totales</th>
                        <td class="numeric editable" contenteditable="true">
                            <span
                                id="total_suma_asegurada">{{ number_format($cartera->SumaAsegurada, 2, '.', ',') }}</span>
                        </td>

                        <td class="numeric editable" contenteditable="true">
                            <span
                                id="total_prima_por_pagar">{{ number_format($cartera->SumaAsegurada * $poliza_vida->Tasa, 2, '.', ',') }}</span>
                        </td>
                       <td class="numeric"><span
                                id="total_saldo_capital">{{ number_format($data['saldoCapital'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_interes">{{ number_format($data['intereses'], 2, '.', ',') }}</span></td>
                        <td class="numeric"><span
                                id="total_interes_covid">{{ number_format($data['interesesCovid'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_interes_moratorios">{{ number_format($data['interesesMoratorios'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_monto_nominal">{{ number_format($data['montoNominal'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_monto_cartera">{{ number_format($data['total'], 2, '.', ',') }}</span></td>
                        <td class="numeric"><span
                                id="total_prima_por_pagar">{{ number_format($data['primaPorPagar'], 2, '.', ',') }}</span>
                        </td>
                    </tr> --}}
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">&nbsp;


            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <table class="excel-like-table">
                    <thead>
                        <tr>
                            <th colspan="2">Estructura CCF de comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Detalle</td>
                            <td>USD</td>
                        </tr>
                        <tr>
                            <td>Porcentaje de Comisión</td>
                            <td class="numeric editable">
                                <span>{{ isset($data['tasaComision']) ? number_format($data['tasaComision'], 2, '.', '') : '0.00' }}
                                    %</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Prima a cobrar</td>
                            <td class="numeric editable">
                                <span id="prima_a_cobrar_ccf">
                                    {{ isset($data['primaCobrar']) ? number_format($data['primaCobrar'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Valor de comisión</td>
                            <td class="numeric editable">
                                <span id="valor_comision">
                                    {{ isset($data['valorComision']) ? number_format($data['valorComision'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>(+) 13% IVA</td>
                            <td class="numeric editable">
                                <span id="iva_comision">
                                    {{ isset($data['ivaComision']) ? number_format($data['ivaComision'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Sub Total Comision</td>
                            <td class="numeric editable">
                                <span id="sub_total_ccf">
                                    {{ isset($data['subTotalCcf']) ? number_format($data['subTotalCcf'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>(-) 1% Retención</td>
                            <td class="numeric editable">
                                <span id="retencion_comision">
                                    {{ isset($data['retencionComision']) ? number_format($data['retencionComision'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>(=) Valor CCF Comisión</td>
                            <td class="numeric editable">
                                <span id="comision_ccf">
                                    {{ isset($data['comisionCcf']) ? number_format($data['comisionCcf'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <table class="excel-like-table">

                    <thead>
                        <tr>
                            <th colspan="2">Detalle general de cobro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Detalle</td>
                            <td>USD</td>
                        </tr>
                        <tr>
                            <td>Monto total cartera</td>
                            <td class="numeric editable">
                                <span id="monto_total_cartera">
                                    {{ isset($totalSumaAsegurada) ? number_format($totalSumaAsegurada, 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Prima calculada</td>
                            <td class="numeric editable">
                                <span id="sub_total">
                                    {{ isset($totalPrimaCalculada) ? number_format($totalPrimaCalculada, 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Extra Prima</td>
                            <td class="numeric editable">
                                <span id="sub_total_extra_prima">
                                    {{ isset($data['extra_prima']) ? number_format($data['extra_prima'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>(-) Descuento rentabilidad (%)</td>
                            <td class="numeric editable">
                                <span id="descuento_rentabilidad">
                                    {{ isset($data['descuento']) ? number_format($data['descuento'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>(=) Prima descontada</td>
                            <td class="numeric editable">
                                <span id="prima_a_cobrar">
                                    {{ isset($data['primaCobrar']) ? number_format($data['primaCobrar'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>(-) Estructura CCF de Comisión (%)</td>
                            <td class="numeric editable">
                                <span id="comision">
                                    {{ isset($data['comisionCcf']) ? number_format($data['comisionCcf'], 2, '.', ',') : '0.00' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Total a pagar</td>
                            <td class="numeric total" contenteditable="true" id="liquido_pagar" onblur="total()">
                                {{ isset($data['liquidoApagar']) ? number_format($data['liquidoApagar'], 2, '.', ',') : '0.00' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br><br><br>
            </div>

            <div>
                {{-- <form action="{{ url('polizas/desempleo/agregar_pago') }}" method="POST">
                    @csrf
                    <input type="hidden" name="FechaInicio" value="{{ isset($fechas) ? $fechas->FechaInicio : '' }}">
                    <input type="hidden" name="FechaFinal" value="{{ isset($fechas) ? $fechas->FechaFinal : '' }}">
                    <input type="hidden" name="MontoCartera" id="MontoCarteraDetalle"
                        value="{{ $data['total'] }}">
                    <input type="hidden" name="Desempleo" value="{{ $poliza_vida->Id }}">
                    <input type="hidden" name="Tasa" value="{{ $poliza_vida->Tasa }}">
                    <input type="hidden" name="PrimaCalculada" id="PrimaCalculadaDetalle"
                        value="{{ $data['primaPorPagar'] }}">
                    <input type="hidden" name="PrimaDescontada" id="PrimaDescontadaDetalle"
                        value="{{ $data['primaDescontada'] }}">
                    <!-- <input type="hidden" name="SubTotal" id="SubTotalDetalle" value="{{ $data['primaPorPagar'] }}" > -->
                    <!-- <input type="hidden" name="Iva" id="IvaDetalle"> -->
                    <input type="hidden" name="TasaComision" value="{{ $poliza_vida->TasaComision }}">
                    <input type="hidden" name="Comision" id="ComisionDetalle"
                        value="{{ $data['valorComision'] }}">
                    <input type="hidden" name="IvaSobreComision" id="IvaComisionDetalle"
                        value="{{ $data['ivaComision'] }}">
                    <input type="hidden" name="Descuento" id="DescuentoDetalle" value="{{ $data['descuento'] }}">
                    <input type="hidden" name="Retencion" id="RetencionDetalle"
                        value="{{ $data['retencionComision'] }}">
                    <input type="hidden" name="ValorCCF" id="ValorCCFDetalle" value="{{ $data['comisionCcf'] }}">
                    <input type="hidden" name="APagar" id="APagarDetalle" value="{{ $data['liquidoApagar'] }}">
                    <input type="hidden" name="ExtraPrima" value="{{ $data['extra_prima'] }}">
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                        id="modal-aplicar">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title">Aviso de cobro</h4>
                                </div>
                                <div class="modal-body">
                                    <p>¿Desea generar el aviso de cobro?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Cerrar</button>
                                    <button id="boton_pago" class="btn btn-primary">Generar Aviso de cobro</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>

                    <div align="center">
                        <br><br><br>
                        <a class="btn btn-default" data-target="#modal-cancelar" data-toggle="modal"
                            onclick="cancelarpago()">Cancelar Cobro</a>
                        <a class="btn btn-primary" data-target="#modal-aplicar" data-toggle="modal"
                            onclick="aplicarpago()">Generar Cobro</a>
                    </div>

                </form> --}}
            </div>

            <div class="modal fade" id="modal-cancelar" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                <div class="modal-dialog">
                    <form action="{{ url('polizas/residencia/cancelar_pago') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Cancelar Cobro</h4>

                                <input type="hidden" name="Residencia" value="{{ $poliza_vida->Id }}">
                                <input type="hidden" name="MesCancelar"
                                    value="{{ isset($fecha) ? $fecha->Mes : '' }}">
                                <input type="hidden" name="AxoCancelar"
                                    value="{{ isset($fecha) ? $fecha->Axo : '' }}">
                            </div>
                            <div class="modal-body">
                                <p>¿Esta seguro/a que desea cancelar el cobro?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button class="btn btn-danger">Cancelar
                                    Cobro</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
