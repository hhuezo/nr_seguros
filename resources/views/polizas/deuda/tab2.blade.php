<div>
    @php
        ini_set('max_execution_time', 30000);
        set_time_limit(30000);
    @endphp

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>


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
                            <th>Tipo cartera</th>
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
                            <th>Prima calculada</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php($totalMontoOtorgado = 0)
                        @php($totalSaldoCapital = 0)
                        @php($totalInteres = 0)
                        @php($totalInteresCovid = 0)
                        @php($totalInteresMoratorio = 0)
                        @php($totalMontoNominal = 0)
                        @php($totalTotalCredito = 0)
                        @php($totalPrimaCalculada = 0)


                        @foreach ($dataPago as $item)
                            @php($totalMontoOtorgado += $item['MontoOtorgado'])
                            @php($totalSaldoCapital += $item['SaldoCapital'])
                            @php($totalInteres += $item['Intereses'])
                            @php($totalInteresCovid += $item['InteresesCovid'])
                            @php($totalInteresMoratorio += $item['InteresesMoratorios'])
                            @php($totalMontoNominal += $item['MontoNominal'])
                            @php($totalTotalCredito += $item['TotalCredito'])
                            @php($totalPrimaCalculada += $item['PrimaCalculada'])

                            <tr>
                                <td>{{ $item['TipoCarteraNombre'] }}</td>
                                <td>{{ $item['DescripcionLineaCredito'] }} ({{ $item['AbreviaturaLineaCredito'] }})
                                </td>
                                <td>{{ $item['Tasa'] }}%</td>
                                <td>{{ $item['Edad'] }}</td>
                                <td>{{ $item['Fecha'] }}</td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="monto_otorgado_{{ $item['Id'] }}">
                                    {{ number_format($item['MontoOtorgado'], 2, '.', ',') }}
                                </td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="saldo_capital_{{ $item['Id'] }}">
                                    {{ number_format($item['SaldoCapital'], 2, '.', ',') }}
                                </td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="interes_{{ $item['Id'] }}">
                                    {{ number_format($item['Intereses'], 2, '.', ',') }}
                                </td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="interes_covid_{{ $item['Id'] }}">
                                    {{ number_format($item['InteresesCovid'], 2, '.', ',') }}
                                </td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="interes_moratorio_{{ $item['Id'] }}">
                                    {{ number_format($item['InteresesMoratorios'], 2, '.', ',') }}
                                </td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="monto_nominal_{{ $item['Id'] }}">
                                    {{ number_format($item['MontoNominal'], 2, '.', ',') }}
                                </td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="suma_asegurada_{{ $item['Id'] }}">
                                    {{ number_format($item['TotalCredito'], 2, '.', ',') }}
                                </td>
                                <td style="text-align: right;" class="numeric editable"
                                    id="prima_calculada_{{ $item['Id'] }}">
                                    {{ number_format($item['PrimaCalculada'], 2, '.', ',') }}
                                </td>

                            </tr>
                        @endforeach

                        <tr>
                            <th colspan="5">Totales</th>
                            <td class="numeric"><span
                                    id="total_monto_otorgado">{{ number_format($totalMontoOtorgado, 2, '.', ',') }}</span>
                            </td>
                            <td class="numeric"><span
                                    id="total_saldo_capital">{{ number_format($totalSaldoCapital, 2, '.', ',') }}</span>
                            </td>
                            <td class="numeric"><span
                                    id="total_interes">{{ number_format($totalInteres, 2, '.', ',') }}</span></td>
                            <td class="numeric"><span
                                    id="total_interes_covid">{{ number_format($totalInteresCovid, 2, '.', ',') }}</span>
                            </td>
                            <td class="numeric"><span
                                    id="total_interes_moratorio">{{ number_format($totalInteresMoratorio, 2, '.', ',') }}</span>
                            </td>
                            <td class="numeric"><span
                                    id="total_monto_nominal">{{ number_format($totalMontoNominal, 2, '.', ',') }}</span>
                            </td>
                            <td class="numeric"><span
                                    id="total_suma_asegurada">{{ number_format($totalTotalCredito, 2, '.', ',') }}</span>
                            </td>
                            <td class="numeric editable" contenteditable="true" id="total_prima_calculada"
                                onblur="actualizarTotalPrimaCalculada(this)">
                                {{ number_format($totalPrimaCalculada, 2, '.', ',') }}
                            </td>

                        </tr>
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

                    <input type="hidden" name="FechaInicio"
                        value="{{ isset($ultimaCartera) ? $ultimaCartera->FechaInicio : '' }}">
                    <input type="hidden" name="FechaFinal"
                        value="{{ isset($ultimaCartera) ? $ultimaCartera->FechaFinal : '' }}">
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
            let idRegistroArray = @json($dataPagoId);

            if (Array.isArray(idRegistroArray) && idRegistroArray.length > 0) {
                calculoTotales();
            }

        });


        function calculoTotales() {
            // --- Helpers ---
            const toNumber = v => {
                const n = parseFloat(String(v ?? '').replace(/,/g, ''));
                return isNaN(n) ? 0 : n;
            };
            const fmt = v => toNumber(v).toLocaleString('es-SV', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // --- Obtenemos los totales directamente de la tabla ---
            let total_saldo_capital = toNumber(document.getElementById("total_saldo_capital")?.textContent);
            let total_monto_nominal = toNumber(document.getElementById("total_monto_nominal")?.textContent);
            let total_monto_otorgado = toNumber(document.getElementById("total_monto_otorgado")?.textContent);
            let total_interes = toNumber(document.getElementById("total_interes")?.textContent);
            let total_interes_covid = toNumber(document.getElementById("total_interes_covid")?.textContent);
            let total_interes_moratorio = toNumber(document.getElementById("total_interes_moratorio")?.textContent);
            let total_suma_asegurada = toNumber(document.getElementById("total_suma_asegurada")?.textContent);
            let total_prima_calculada = toNumber(document.getElementById("total_prima_calculada")?.textContent);

            // --- Subtotal ---
            let sub_total = total_prima_calculada;

            // --- Datos auxiliares ---
            let tasa_comision = toNumber(document.getElementById('TasaComisionDetalle')?.value);
            let tipo_contribuyente = parseInt(document.getElementById('TipoContribuyente')?.value || 0);
            let extra_prima = toNumber(document.getElementById('ExtraPrima')?.value);
            let descuento_rentabilidad = toNumber(document.getElementById('DescuentoRentabilidad')?.value);

            // --- Totales base ---
            document.getElementById("monto_total_cartera").textContent = fmt(total_suma_asegurada);
            document.getElementById('MontoCarteraDetalle').value = total_suma_asegurada;

            document.getElementById('PrimaCalculadaDetalle').value = sub_total;
            document.getElementById("sub_total").textContent = fmt(sub_total);
            document.getElementById('SubTotalDetalle').value = sub_total;

            document.getElementById("sub_total_extra_prima").textContent = fmt(extra_prima);

            // --- Descuento ---
            let descuento = (sub_total + extra_prima) * (descuento_rentabilidad / 100);
            descuento = toNumber(descuento);
            document.getElementById('descuento_rentabilidad').textContent = fmt(descuento);
            document.getElementById('DescuentoDetalle').value = descuento;

            // --- Prima a cobrar ---
            let prima_a_cobrar = (sub_total + extra_prima) - descuento;
            prima_a_cobrar = toNumber(prima_a_cobrar);
            document.getElementById("prima_a_cobrar").textContent = fmt(prima_a_cobrar);
            document.getElementById("prima_a_cobrar_ccf").textContent = fmt(prima_a_cobrar);
            document.getElementById('PrimaDescontadaDetalle').value = prima_a_cobrar;

            // --- IVA (solo si aplica) ---
            let iva = tipo_contribuyente !== 4 ? 0 : 0;
            document.getElementById('IvaDetalle').value = iva;

            // --- Comisión ---
            let valor_comision = prima_a_cobrar * (tasa_comision / 100);
            valor_comision = toNumber(valor_comision);
            document.getElementById('valor_comision').textContent = fmt(valor_comision);
            document.getElementById('ComisionDetalle').value = valor_comision;

            // --- IVA sobre comisión ---
            let iva_comision = tipo_contribuyente !== 4 ? valor_comision * 0.13 : 0;
            iva_comision = toNumber(iva_comision);
            document.getElementById('iva_comision').textContent = fmt(iva_comision);
            document.getElementById('IvaComisionDetalle').value = iva_comision;

            // --- Subtotal CCF ---
            let sub_total_ccf = valor_comision + iva_comision;
            document.getElementById('sub_total_ccf').textContent = fmt(sub_total_ccf);

            // --- Retención ---
            let retencion_comision = (tipo_contribuyente !== 1 && valor_comision >= 100) ? valor_comision * 0.01 : 0;
            document.getElementById('retencion_comision').textContent = fmt(retencion_comision);

            // --- Comisión CCF ---
            let comision_ccf = sub_total_ccf - retencion_comision;
            document.getElementById('comision_ccf').textContent = fmt(comision_ccf);
            document.getElementById('comision').textContent = fmt(comision_ccf);

            // --- Líquido a pagar ---
            let liquido_pagar = prima_a_cobrar - comision_ccf;
            document.getElementById("liquido_pagar").textContent = fmt(liquido_pagar);

            // --- Asignar valores finales ---
            document.getElementById('RetencionDetalle').value = retencion_comision;
            document.getElementById('ValorCCFDetalle').value = comision_ccf;
            document.getElementById('APagarDetalle').value = liquido_pagar;
        }




        function actualizarTotalPrimaCalculada(element) {
            // --- Helper para asegurar que no haya NaN ---
            const toNumber = v => {
                const n = parseFloat(String(v ?? '').replace(/,/g, ''));
                return isNaN(n) ? 0 : n;
            };
            const fmt = v => toNumber(v).toLocaleString('es-SV', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // --- Valor editado ---
            let primaCalculada = toNumber(element.innerText);
            element.textContent = fmt(primaCalculada);

            // --- Actualizamos los inputs ocultos relacionados ---
            const inputPrima = document.getElementById('PrimaCalculadaDetalle');
            if (inputPrima) inputPrima.value = primaCalculada;

            const subTotalInput = document.getElementById('SubTotalDetalle');
            if (subTotalInput) subTotalInput.value = primaCalculada;

            // --- Variables auxiliares del documento ---
            let extra_prima = toNumber(document.getElementById('ExtraPrima')?.value);
            let descuento_rentabilidad = toNumber(document.getElementById('DescuentoRentabilidad')?.value);
            let tasa_comision = toNumber(document.getElementById('TasaComisionDetalle')?.value);
            let tipo_contribuyente = toNumber(document.getElementById('TipoContribuyente')?.value || 0);

            // --- Descuento ---
            let descuento = (primaCalculada + extra_prima) * (descuento_rentabilidad / 100);
            descuento = toNumber(descuento);
            document.getElementById('descuento_rentabilidad').textContent = fmt(descuento);
            document.getElementById('DescuentoDetalle').value = descuento;

            // --- Prima a cobrar ---
            let prima_a_cobrar = (primaCalculada + extra_prima) - descuento;
            prima_a_cobrar = toNumber(prima_a_cobrar);
            document.getElementById('prima_a_cobrar').textContent = fmt(prima_a_cobrar);
            document.getElementById('prima_a_cobrar_ccf').textContent = fmt(prima_a_cobrar);
            document.getElementById('PrimaDescontadaDetalle').value = prima_a_cobrar;

            // --- Comisión ---
            let valor_comision = prima_a_cobrar * (tasa_comision / 100);
            valor_comision = toNumber(valor_comision);
            document.getElementById('valor_comision').textContent = fmt(valor_comision);
            document.getElementById('ComisionDetalle').value = valor_comision;

            // --- IVA sobre comisión ---
            let iva_comision = tipo_contribuyente !== 4 ? valor_comision * 0.13 : 0;
            iva_comision = toNumber(iva_comision);
            document.getElementById('iva_comision').textContent = fmt(iva_comision);
            document.getElementById('IvaComisionDetalle').value = iva_comision;

            // --- Subtotal CCF ---
            let sub_total_ccf = valor_comision + iva_comision;
            sub_total_ccf = toNumber(sub_total_ccf);
            document.getElementById('sub_total_ccf').textContent = fmt(sub_total_ccf);

            // --- Retención ---
            let retencion_comision = (tipo_contribuyente !== 1 && valor_comision >= 100) ?
                valor_comision * 0.01 :
                0;
            retencion_comision = toNumber(retencion_comision);
            document.getElementById('retencion_comision').textContent = fmt(retencion_comision);
            document.getElementById('RetencionDetalle').value = retencion_comision;

            // --- Comisión CCF ---
            let comision_ccf = sub_total_ccf - retencion_comision;
            comision_ccf = toNumber(comision_ccf);
            document.getElementById('comision_ccf').textContent = fmt(comision_ccf);
            document.getElementById('comision').textContent = fmt(comision_ccf);
            document.getElementById('ValorCCFDetalle').value = comision_ccf;

            // --- Líquido a pagar ---
            let liquido_pagar = prima_a_cobrar - comision_ccf;
            liquido_pagar = toNumber(liquido_pagar);
            document.getElementById('liquido_pagar').textContent = fmt(liquido_pagar);
            document.getElementById('APagarDetalle').value = liquido_pagar;
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
    </script>


</div>
