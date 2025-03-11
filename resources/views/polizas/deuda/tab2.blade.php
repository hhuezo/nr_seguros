<div>
    @php
        ini_set('max_execution_time', 30000);
        set_time_limit(30000);
    @endphp

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>


    <style>
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loading-overlay img {
            width: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
            height: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
        }

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            /* Ajustar el ancho según sea necesario */
            height: 20px;
            /* Ajustar la altura según sea necesario */
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 10px;
            /* Ajustar el radio de borde para que sea más pequeño */
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            /* Ajustar la altura según sea necesario */
            width: 16px;
            /* Ajustar el ancho según sea necesario */
            left: 2px;
            /* Ajustar la posición según sea necesario */
            bottom: 2px;
            /* Ajustar la posición según sea necesario */
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
            /* Hacer el selector redondo */
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 20px;
            /* Ajustar el radio de borde para que sea más pequeño */
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>


    <!-- Agrega este div al final de tu archivo blade -->
    <div id="loading-overlay">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>





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






    <div class="modal-header">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <h4 class="title" id="exampleModalLabel">Nuevo pago</h4>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-flex justify-content-start" style="text-align: right;">
            <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_pago">
                Subir Archivo Excel
            </button> -->
            <a href="{{ url('polizas/deuda/recibo_complementario') }}/{{ $deuda->Id }}" class="btn btn-warning">
                Recibo complementario</a>
            <a href="{{ url('polizas/deuda/subir_cartera') }}/{{ $deuda->Id }}" class="btn btn-info"> Subir Archivos
                Excel</a>
        </div>

    </div>

    <input type="hidden" id="Tasa" value="{{ $deuda->Tasa }}">
    @if ($deuda->ComisionIva == 1)
        @php($var = $deuda->TasaComision / 1.13)
        <input type="hidden" id="TasaComisionDetalle" value="{{ $var }}">
    @else
        <input type="hidden" id="TasaComisionDetalle" value="{{ $deuda->TasaComision }}">
    @endif
    <input type="hidden" id="ExtraPrima" value="{{ $total_extrapima }}">
    <input type="hidden" id="TipoContribuyente" value="{{ $deuda->clientes->TipoContribuyente }}">
    <input type="hidden" id="ComisionIva" value="{{ $deuda->ComisionIva }}">
    <input type="hidden" id="DescuentoRentabilidad" value="{{ $deuda->Descuento }}">



    <div class="modal-body">
        <div class="box-body row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="excel-like-table">
                    <thead>
                        <tr>
                            <th>Lineas de crédito</th>
                            <th>Tasa interés</th>
                            <th>Edad</th>
                            <th>Fecha</th>
                            <th>Monto Otorgado</th>
                            <th>Saldo capital</th>
                            <th>Intereses corrientes</th>
                            <th>Interes COVID</th>
                            <th>Intereses Moratorios</th>
                            <th>Monto nominal</th>
                            <th>Suma asegurada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lineas_credito as $lineas)
                            @php($total = $lineas->SaldoCapital)
                            @php($saldo_capital = 0.0)
                            @php($monto_nominal = 0.0)
                            @php($intereses = 0.0)
                            @php($intereses_covid = 0.0)
                            @php($intereses_moratorios = 0.0)
                            @php($monto_otorgado = 0)
                            @if ($lineas->Abrev == 'INS1' . $lineas->LineaCredito)
                                @php($total = $lineas->SaldoCapital)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0.0)
                                @php($intereses = 0.0)
                                @php($intereses_covid = 0.0)
                                @php($intereses_moratorios = 0.0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abrev == 'INS2')
                                @php($total = $lineas->SaldoCapital + $lineas->Intereses)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0)
                                @php($intereses = $lineas->Intereses)
                                @php($intereses_covid = 0)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abrev == 'INS3')
                                @php($total = $lineas->SaldoCapital + $lineas->Intereses + $lineas->InteresesCovid)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0)
                                @php($intereses = $lineas->Intereses)
                                @php($intereses_covid = $lineas->InteresesCovid)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abrev == 'INS4')
                                @php($total = $lineas->SaldoCapital + $lineas->Intereses + $lineas->InteresesCovid + $lineas->InteresesMoratorios)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0)
                                @php($intereses = $lineas->Intereses)
                                @php($intereses_covid = $lineas->InteresesCovid)
                                @php($intereses_moratorios = $lineas->InteresesMoratorios)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abrev == 'INS5')
                                @php($total = $lineas->MontoNominal)
                                @php($saldo_capital = 0)
                                @php($monto_nominal = $lineas->MontoNominal)
                                @php($intereses = 0)
                                @php($intereses_covid = 0)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abrev == 'INS6')
                                @php($total = $lineas->MontoOtorgado)
                                @php($saldo_capital = 0)
                                @php($monto_nominal = 0)
                                @php($intereses = 0)
                                @php($intereses_covid = 0)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = $lineas->MontoOtorgado)
                            @endif
                            <tr>
                                <td>{{ $lineas->tipo }} {{ $lineas->Abrev }}</td>
                                <td>{{ $lineas->EsTasaDiferenciada == 1 ? $lineas->Tasa : $deuda->Tasa }} %</td>
                                <td>{{ $lineas->EsTasaDiferenciada == 1 ? $lineas->EdadDesde . ' - ' . $lineas->EdadHasta . ' Años' : '' }}
                                </td>
                                <td>{{ $lineas->EsTasaDiferenciada == 1 ? $lineas->FechaDesde . ' - ' . $lineas->FechaHasta : '' }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_monto_otorgado">

                                    {{ isset($monto_otorgado) && $monto_otorgado != 0 ? number_format($monto_otorgado, 2, '.', ',') : 0 }}

                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_saldo_capital">
                                    {{ isset($saldo_capital) && $saldo_capital != 0 ? number_format($saldo_capital, 2, '.', ',') : 0 }}

                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes">
                                    {{ $intereses != 0 ? number_format($intereses, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes_covid">
                                    {{ $intereses_covid != 0 ? number_format($intereses_covid, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes_moratorio">
                                    {{ $intereses_moratorios != 0 ? number_format($intereses_moratorios, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_monto_nominal">
                                    {{ $monto_nominal != 0 ? number_format($monto_nominal, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric total" id="{{ $lineas->Abreviatura }}_suma_asegurada">
                                    {{ number_format($total, 2, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th colspan="4">Totales</th>
                            <td class="numeric"><span id="total_monto_otorgado"></span></td>
                            <td class="numeric"><span id="total_saldo_capital"></span></td>
                            <td class="numeric"><span id="total_interes"></span></td>
                            <td class="numeric"><span id="total_interes_covid"></span></td>
                            <td class="numeric"><span id="total_interes_moratorio"></span></td>
                            <td class="numeric"><span id="total_monto_nominal"></span></td>
                            <td class="numeric"><span id="total_suma_asegurada"></span></td>
                        </tr>
                    </tbody>
                </table>



                {{--
                <table class="excel-like-table">
                    <thead>
                        <tr>
                            <th>Lineas de crédito</th>
                            <th>Tasa interés</th>
                            <th>Monto Otorgado</th>
                            <th>Saldo capital</th>
                            <th>Intereses corrientes</th>
                            <th>Interes COVID</th>
                            <th>Intereses Moratorios</th>
                            <th>Monto nominal</th>
                            <th>Suma asegurada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lineas_credito as $lineas)
                            @if ($lineas->Abreviatura == 'INS1' . $lineas->LineaCredito)
                                @php($total = $lineas->SaldoCapital)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0.0)
                                @php($intereses = 0.0)
                                @php($intereses_covid = 0.0)
                                @php($intereses_moratorios = 0.0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abreviatura == 'INS2' . $lineas->LineaCredito)
                                @php($total = $lineas->SaldoCapital + $lineas->Intereses)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0)
                                @php($intereses = $lineas->Intereses)
                                @php($intereses_covid = 0)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abreviatura == 'INS3' . $lineas->LineaCredito)
                                @php($total = $lineas->SaldoCapital + $lineas->Intereses + $lineas->InteresesCovid)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0)
                                @php($intereses = $lineas->Intereses)
                                @php($intereses_covid = $lineas->InteresesCovid)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abreviatura == 'INS4' . $lineas->LineaCredito)
                                @php($total = $lineas->SaldoCapital + $lineas->Intereses + $lineas->InteresesCovid + $lineas->InteresesMoratorios)
                                @php($saldo_capital = $lineas->SaldoCapital)
                                @php($monto_nominal = 0)
                                @php($intereses = $lineas->Intereses)
                                @php($intereses_covid = $lineas->InteresesCovid)
                                @php($intereses_moratorios = $lineas->InteresesMoratorios)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abreviatura == 'INS5' . $lineas->LineaCredito)
                                @php($total = $lineas->MontoNominal)
                                @php($saldo_capital = 0)
                                @php($monto_nominal = $lineas->MontoNominal)
                                @php($intereses = 0)
                                @php($intereses_covid = 0)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = 0)
                            @elseif($lineas->Abreviatura == 'INS6' . $lineas->LineaCredito)
                                @php($total = $lineas->MontoOtorgado)
                                @php($saldo_capital = 0)
                                @php($monto_nominal = 0)
                                @php($intereses = 0)
                                @php($intereses_covid = 0)
                                @php($intereses_moratorios = 0)
                                @php($monto_otorgado = $lineas->MontoOtorgado)
                            @endif
                            <tr>
                                <td>{{ $lineas->tipo }} {{ $lineas->Abrev }}</td>
                                <td>{{ $deuda->Tasa }} %</td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_monto_otorgado">

                                    {{ $monto_otorgado != 0 ? number_format($monto_otorgado, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_saldo_capital">
                                    {{ $saldo_capital != 0 ? number_format($saldo_capital, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes">
                                    {{ $intereses != 0 ? number_format($intereses, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes_covid">
                                    {{ $intereses_covid != 0 ? number_format($intereses_covid, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes_moratorio">
                                    {{ $intereses_moratorios != 0 ? number_format($intereses_moratorios, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_monto_nominal">
                                    {{ $monto_nominal != 0 ? number_format($monto_nominal, 2, '.', ',') : 0 }}
                                </td>
                                <td class="numeric total" id="{{ $lineas->Abreviatura }}_suma_asegurada">
                                    {{ number_format($total, 2, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th colspan="2">Totales</th>
                            <td class="numeric"><span id="total_monto_otorgado"></span></td>
                            <td class="numeric"><span id="total_saldo_capital"></span></td>
                            <td class="numeric"><span id="total_interes"></span></td>
                            <td class="numeric"><span id="total_interes_covid"></span></td>
                            <td class="numeric"><span id="total_interes_moratorio"></span></td>
                            <td class="numeric"><span id="total_monto_nominal"></span></td>
                            <td class="numeric"><span id="total_suma_asegurada"></span></td>
                        </tr>
                    </tbody>
                </table> --}}
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
                            <td>Porcentaje de Comisión {{ $deuda->ComisionIva == 1 ? 'Iva Incluido' : '' }} </td>
                            <td class="numeric editable">
                                <span>{{ $deuda->ComisionIva == 1 ? number_format($deuda->TasaComision / 1.13, 2, '.', ',') : $deuda->TasaComision }}%</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Prima a cobrar</td>
                            <td class="numeric editable"><span id="prima_a_cobrar_ccf"></span></td>
                        </tr>
                        <tr>
                            <td>Valor de comisión</td>
                            <td class="numeric editable"><span id="valor_comision"></span></td>
                        </tr>
                        <tr>
                            <td>(+) 13% IVA</td>
                            <td class="numeric editable"><span id="iva_comision"></span></td>
                        </tr>
                        <tr>
                            <td>Sub Total Comision</td>
                            <td class="numeric editable"><span id="sub_total_ccf"></span></td>
                        </tr>
                        <tr>
                            <td>(-) 1% Retención</td>
                            <td class="numeric editable"><span id="retencion_comision"></span></td>
                        </tr>
                        <tr>
                            <td>(=) Valor CCF Comisión</td>
                            <td class="numeric editable"><span id="comision_ccf"></span></td>
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
                            <td class="numeric editable"><span id="monto_total_cartera"></span></td>
                        </tr>
                        <tr>
                            <td>Prima calculada</td>
                            <td class="numeric editable"><span id="sub_total"></span></td>
                        </tr>
                        <tr>
                            <td>Extra Prima</td>
                            <td class="numeric editable"><span id="sub_total_extra_prima"></span></td>
                        </tr>
                        <tr>
                            <td>(-) Descuento rentabilidad ({{ $deuda->Descuento == '' ? 0 : $deuda->Descuento }}%)
                            </td>
                            <td class="numeric editable"><span id="descuento_rentabilidad"></span></td>
                        </tr>
                        <tr>
                            <td>(=) Prima descontada</td>
                            <td class="numeric editable"><span id="prima_a_cobrar"></span></td>
                        </tr>
                        <!-- <tr>
                            <td>Iva</td>
                            <td class="numeric editable"><span id="iva"></span></td>
                        </tr>
                        <tr>
                            <td>Total Factura</td>
                            <td class="numeric editable"><span id="total_factura"></span></td>
                        </tr> -->
                        <tr>
                            <td>(-) Estructura CCF de Comisión
                                ({{ $deuda->ComisionIva == 1 ? number_format($deuda->TasaComision / 1.13, 2, '.', ',') : $deuda->TasaComision }}%)
                            </td>
                            <td class="numeric editable"><span id="comision"></span></td>
                        </tr>
                        <tr>
                            <td>Total a pagar</td>
                            <td class="numeric total" contenteditable="true" id="liquido_pagar" onblur="total()">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br><br><br>
            </div>

            <div>
                <form action="{{ url('polizas/deuda/agregar_pago') }}" method="POST">
                    @csrf
                    <input type="hidden" name="FechaInicio" value="{{ isset($fecha) ? $fecha->FechaInicio : '' }}">
                    <input type="hidden" name="FechaFinal" value="{{ isset($fecha) ? $fecha->FechaFinal : '' }}">
                    <input type="hidden" name="MontoCartera" id="MontoCarteraDetalle">
                    <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                    <input type="hidden" name="Tasa" value="{{ $deuda->Tasa }}">
                    <input type="hidden" name="PrimaCalculada" id="PrimaCalculadaDetalle">
                    <input type="hidden" name="PrimaDescontada" id="PrimaDescontadaDetalle">
                    <input type="hidden" name="SubTotal" id="SubTotalDetalle">
                    <input type="hidden" name="Iva" id="IvaDetalle">
                    <input type="hidden" name="TasaComision" value="{{ $deuda->TasaComision }}">
                    <input type="hidden" name="Comision" id="ComisionDetalle">
                    <input type="hidden" name="IvaSobreComision" id="IvaComisionDetalle">
                    <input type="hidden" name="Descuento" id="DescuentoDetalle">
                    <input type="hidden" name="Retencion" id="RetencionDetalle">
                    <input type="hidden" name="ValorCCF" id="ValorCCFDetalle">
                    <input type="hidden" name="APagar" id="APagarDetalle">
                    <input type="hidden" name="ExtraPrima" value="{{ $total_extrapima }}">
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

                </form>
                <!--
                <div align="center">
                    <form action="{{ url('polizas/deuda/validar_poliza') }}" method="POST">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                        <button type="submit">Validar Poliza</button>
                    </form>
                </div> -->

            </div>

            <div class="modal fade" id="modal-cancelar" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                <div class="modal-dialog">
                    <form action="{{ url('deuda/cancelar_pago') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Cancelar Cobro</h4>

                                <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
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

    <script>
        $(document).ready(function() {
            let lineas = @json($lineas_abreviatura);

            //console.log(lineas);
            calculoTotales();
            // Calcula la suma de los valores de las columnas numéricas y muestra el resultado en la columna total
            $('.editable').on('input', function() {
                calculoTotales();
                // let sum = 0;
                // $(this).closest('tr').find('.editable').each(function() {
                //     const value = parseFloat($(this).text().replace(/[^0-9.-]+/g, ''));
                //     if (!isNaN(value)) {
                //         sum += value;
                //     }
                // });
                // $(this).closest('tr').find('.total').text(sum.toFixed(2).replace(/\d(?=(\d{3})+\.)/g,
                //     '$&,'));
            });

            function calculoTotales() {
                let total_saldo_capital = 0;
                let total_monto_nominal = 0;
                let total_monto_otorgado = 0;
                let total_interes = 0;
                let total_interes_covid = 0;
                let total_interes_moratorio = 0;
                let total_suma_asegurada = 0;


                for (let i = 0; i < lineas.length; i++) {
                    let linea = lineas[i];
                    let elemento = document.getElementById(linea + "_saldo_capital");

                    let saldo_capital = elemento.innerText || elemento.textContent;
                    // console.log(linea + "_saldo_capital :", saldo_capital);

                    elemento = document.getElementById(linea + "_monto_nominal");
                    let monto_nominal = elemento.innerText || elemento.textContent;

                    elemento = document.getElementById(linea + "_monto_otorgado");
                    let monto_otorgado = elemento.innerText || elemento.textContent;
                    // console.log(linea + "_monto_otorgado: ", monto_otorgado);

                    elemento = document.getElementById(linea + "_interes");
                    let interes = elemento.innerText || elemento.textContent;
                    // console.log(linea + "_interes: ", interes);

                    elemento = document.getElementById(linea + "_interes_covid");
                    let interes_covid = elemento.innerText || elemento.textContent;
                    // console.log(linea + "_interes_covid: ", interes_covid);

                    elemento = document.getElementById(linea + "_interes_moratorio");
                    let interes_moratorio = elemento.innerText || elemento.textContent;
                    //console.log(linea + "_interes_moratorio: ", interes_moratorio);

                    elemento = document.getElementById(linea + "_suma_asegurada");
                    let suma_asegurada = convertirANumero(saldo_capital) + convertirANumero(monto_nominal) +
                        convertirANumero(monto_otorgado) +
                        convertirANumero(interes) + convertirANumero(interes_covid) + convertirANumero(
                            interes_moratorio);

                    let suma_asegurada_formateada = suma_asegurada.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    // Asigna la suma formateada al elemento
                    elemento.textContent = suma_asegurada_formateada;


                    total_saldo_capital += convertirANumero(saldo_capital);
                    total_monto_nominal += convertirANumero(monto_nominal);
                    total_monto_otorgado += convertirANumero(monto_otorgado);
                    total_interes += convertirANumero(interes);
                    total_interes_covid += convertirANumero(interes_covid);
                    total_interes_moratorio += convertirANumero(interes_moratorio);
                    total_suma_asegurada += suma_asegurada;
                }


                let total_saldo_capital_formateada = formatearCantidad(total_saldo_capital);
                document.getElementById("total_saldo_capital").textContent = total_saldo_capital_formateada;

                let total_monto_nominal_formateada = formatearCantidad(total_monto_nominal);
                document.getElementById("total_monto_nominal").textContent = total_monto_nominal_formateada;

                let total_monto_otorgado_formateada = formatearCantidad(total_monto_otorgado);
                document.getElementById("total_monto_otorgado").textContent = total_monto_otorgado_formateada;

                let total_interes_formateada = formatearCantidad(total_interes);
                document.getElementById("total_interes").textContent = total_interes_formateada;

                let total_interes_covid_formateada = formatearCantidad(total_interes_covid);
                document.getElementById("total_interes_covid").textContent = total_interes_covid_formateada;

                let total_interes_moratorio_formateada = formatearCantidad(total_interes_moratorio);
                document.getElementById("total_interes_moratorio").textContent = total_interes_moratorio_formateada;

                let total_suma_asegurada_formateada = formatearCantidad(total_suma_asegurada);
                document.getElementById("total_suma_asegurada").textContent = total_suma_asegurada_formateada;


                let tasa = document.getElementById('Tasa').value;
                let comision_iva = document.getElementById('ComisionIva').value;
                let tasa_comision = document.getElementById('TasaComisionDetalle').value;
                let tipo_contribuyente = {{ $deuda->clientes->TipoContribuyente }};
                console.log(tasa_comision);
                let extra_prima = document.getElementById('ExtraPrima').value;

                //modificando valores de cuadros
                document.getElementById("monto_total_cartera").textContent = total_suma_asegurada_formateada;
                document.getElementById('MontoCarteraDetalle').value = parseFloat(total_suma_asegurada);
                document.getElementById('PrimaCalculadaDetalle').value = parseFloat(
                    total_suma_asegurada) * parseFloat(tasa);


                let sub_total = total_suma_asegurada * tasa;

                document.getElementById("sub_total").textContent = formatearCantidad(sub_total);
                document.getElementById('SubTotalDetalle').value = sub_total;
                document.getElementById("sub_total_extra_prima").textContent = formatearCantidad(extra_prima);



                let descuento = (parseFloat(sub_total) + parseFloat(extra_prima)) * parseFloat(parseFloat(document
                    .getElementById('DescuentoRentabilidad').value) / 100);
                document.getElementById('descuento_rentabilidad').textContent = formatearCantidad(descuento);
                document.getElementById('DescuentoDetalle').value = parseFloat(descuento);
                prima_a_cobrar = (parseFloat(sub_total) + parseFloat(extra_prima)) - parseFloat(descuento);
                document.getElementById("prima_a_cobrar").textContent = formatearCantidad(prima_a_cobrar);
                document.getElementById("prima_a_cobrar_ccf").textContent = formatearCantidad(prima_a_cobrar);
                let iva = 0;
                // no contribuyente no paga iva
                if (tipo_contribuyente != 4) {
                    iva = 0; //parseFloat(prima_a_cobrar) * 0.13;
                } else {
                    iva = 0;
                }
                document.getElementById('PrimaDescontadaDetalle').value = parseFloat(prima_a_cobrar);

                // document.getElementById('iva').textContent = formatearCantidad(iva);
                document.getElementById('IvaDetalle').value = parseFloat(iva);
                let total_factura = parseFloat(iva) + parseFloat(prima_a_cobrar);
                // document.getElementById('total_factura').textContent = formatearCantidad(total_factura);

                // let comision = prima_a_cobrar * (tasa_comision / 100);

                // document.getElementById("comision").textContent = formatearCantidad(comision);

                //estructura ccf

                let valor_comision = parseFloat(prima_a_cobrar) * (parseFloat(tasa_comision) / 100);
                document.getElementById('valor_comision').textContent = formatearCantidad(valor_comision);
                console.log(valor_comision);
                document.getElementById('ComisionDetalle').value = parseFloat(valor_comision);
                let iva_comision = 0;
                if (tipo_contribuyente != 4) {
                    iva_comision = parseFloat(valor_comision) * 0.13;
                } else {
                    iva_comision = 0;
                }
                document.getElementById('iva_comision').textContent = formatearCantidad(iva_comision);
                document.getElementById('IvaComisionDetalle').value = parseFloat(iva_comision);
                let retencion_comision = 0;
                let sub_total_ccf = parseFloat(valor_comision) + parseFloat(iva_comision);
                document.getElementById('sub_total_ccf').textContent = formatearCantidad(sub_total_ccf);

                if (tipo_contribuyente != 1) {
                    retencion_comision = parseFloat(valor_comision) * 0.01;
                }
                console.log(tipo_contribuyente);
                document.getElementById('retencion_comision').textContent = formatearCantidad(
                    retencion_comision);
                let comision_ccf = parseFloat(sub_total_ccf) - parseFloat(
                    retencion_comision);
                document.getElementById('comision_ccf').textContent = formatearCantidad(comision_ccf);
                document.getElementById('comision').textContent = formatearCantidad(comision_ccf);
                let liquido_pagar = parseFloat(prima_a_cobrar) - parseFloat(comision_ccf);
                document.getElementById("liquido_pagar").textContent = formatearCantidad(liquido_pagar);
                document.getElementById('RetencionDetalle').value = parseFloat(retencion_comision);
                document.getElementById('ValorCCFDetalle').value = parseFloat(comision_ccf);
                document.getElementById('APagarDetalle').value = parseFloat(liquido_pagar);



                console.log(comision);
            }


            // Función para convertir una cadena formateada a un número flotante
            function convertirANumero(cadena) {
                return parseFloat(cadena.replace(/,/g, ''));
            }

            function formatearCantidad(cantidad) {
                let numero = Number(cantidad);
                return numero.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });

        function total() {
            let val = document.getElementById('liquido_pagar').innerText;
            //alert(val);
            document.getElementById('APagarDetalle').value = parseFloat(val);
        }
    </script>

</div>
