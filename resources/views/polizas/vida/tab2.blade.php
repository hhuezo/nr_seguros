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


    <input type="hidden" id="Tasa" value="{{ $poliza_vida->Tasa }}">
    @php
        $tasaComision = $poliza_vida->ComisionIva == 1 ? $poliza_vida->TasaComision / 1.13 : $poliza_vida->TasaComision;
    @endphp

    <input type="hidden" id="TasaComisionDetalle" value="{{ $poliza_vida->ComisionIva }}">
    <input type="hidden" id="ExtraPrima" value="{{ $total_extrapima }}">
    <input type="hidden" id="ComisionIva" value="{{ $poliza_vida->ComisionIva }}">
    <input type="hidden" id="DescuentoRentabilidad" value="{{ $poliza_vida->TasaDescuento ?? 0.0 }}">
    <input type="hidden" id="TipoContribuyente" value="{{ $poliza_vida->cliente->TipoContribuyente }}">

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
                            <td>{{ $item['TipoCartera'] }}</td>
                            <td>{{ $item['Tasa'] }}</td>
                            <td>{{ $item['Monto'] }}</td>
                            <td>{{ $item['Fecha'] }} </td>
                            <td id="suma_asegurada_{{ $item['Id'] }}" contenteditable="true"
                                style="text-align: right;">
                                {{ number_format($item['SumaAsegurada'], 2, '.', ',') }}</td>
                            <td id="prima_calculada_{{ $item['Id'] }}" contenteditable="true"
                                style="text-align: right;">{{ number_format($item['PrimaCalculada'], 2, '.', ',') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="text-end">
                        <th colspan="4">Totales</th>
                        <td id="total_suma_asegurada" style="text-align: right;">
                            {{ number_format($totalSumaAsegurada, 2, '.', ',') }}
                        </td>
                        <td id="total_prima_calculada" style="text-align: right;" class="numeric editable"
                            contenteditable="true" onblur="actualizarTotalPrimaCalculadaVida(this)">
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
                                    {{ number_format($total_extrapima, 2, '.', ',') }}
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
                <form action="{{ url('polizas/vida/agregar_pago') }}" method="POST">
                    @csrf
                    <input type="hidden" name="FechaInicio" value="{{ isset($fechas) ? $fechas->FechaInicio : '' }}">
                    <input type="hidden" name="FechaFinal" value="{{ isset($fechas) ? $fechas->FechaFinal : '' }}">
                    <input type="hidden" name="MontoCartera" id="MontoCarteraDetalle">
                    <input type="hidden" name="PolizaVida" value="{{ $poliza_vida->Id }}">
                    <input type="hidden" name="Tasa" value="{{ $poliza_vida->Tasa }}">
                    <input type="hidden" name="PrimaCalculada" id="PrimaCalculadaDetalle">
                    <input type="hidden" name="PrimaDescontada" id="PrimaDescontadaDetalle">
                    <input type="hidden" name="SubTotal" id="SubTotalDetalle">
                    <input type="hidden" name="Iva" id="IvaDetalle">
                    <input type="hidden" name="TasaComision" value="{{ $poliza_vida->TasaComision }}">
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
                    <form action="{{ url('polizas/vida/cancelar_pago') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Cancelar Cobro</h4>

                                <input type="hidden" name="PolizaVida" value="{{ $poliza_vida->Id }}">

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
            const toNumber = v => {
                const n = parseFloat(String(v ?? '').replace(/,/g, ''));
                return isNaN(n) ? 0 : n;
            };
            const fmt = v => toNumber(v).toLocaleString('es-SV', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Obtenemos los totales directamente del DOM
            let total_suma_asegurada = toNumber(document.getElementById("total_suma_asegurada")?.textContent);
            let total_prima_calculada = toNumber(document.getElementById("total_prima_calculada")?.textContent);
            let sub_total = total_prima_calculada;

            // Variables auxiliares
            let tasa_comision = toNumber(document.getElementById('TasaComisionDetalle')?.value);
            let tipo_contribuyente = toNumber(document.getElementById('TipoContribuyente')?.value || 0);
            let extra_prima = toNumber(document.getElementById('ExtraPrima')?.value);
            let descuento_rentabilidad = toNumber(document.getElementById('DescuentoRentabilidad')?.value);

            // --- Actualiza campos principales ---
            document.getElementById("monto_total_cartera").textContent = fmt(total_suma_asegurada);
            document.getElementById('MontoCarteraDetalle').value = total_suma_asegurada;

            document.getElementById("sub_total").textContent = fmt(sub_total);
            document.getElementById('PrimaCalculadaDetalle').value = sub_total;
            document.getElementById('SubTotalDetalle').value = sub_total;

            document.getElementById("sub_total_extra_prima").textContent = fmt(extra_prima);

            // --- Descuento ---
            let descuento = (sub_total + extra_prima) * (descuento_rentabilidad / 100);
            document.getElementById('descuento_rentabilidad').textContent = fmt(descuento);
            document.getElementById('DescuentoDetalle').value = descuento;

            // --- Prima a cobrar ---
            let prima_a_cobrar = (sub_total + extra_prima) - descuento;
            document.getElementById('prima_a_cobrar').textContent = fmt(prima_a_cobrar);
            document.getElementById('prima_a_cobrar_ccf').textContent = fmt(prima_a_cobrar);
            document.getElementById('PrimaDescontadaDetalle').value = prima_a_cobrar;

            // --- Comisión ---
            let valor_comision = prima_a_cobrar * (tasa_comision / 100);
            document.getElementById('valor_comision').textContent = fmt(valor_comision);
            document.getElementById('ComisionDetalle').value = valor_comision;

            // --- IVA sobre comisión ---
            let iva_comision = tipo_contribuyente !== 4 ? valor_comision * 0.13 : 0;
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

            // --- Actualiza inputs ocultos ---
            document.getElementById('RetencionDetalle').value = retencion_comision;
            document.getElementById('ValorCCFDetalle').value = comision_ccf;
            document.getElementById('APagarDetalle').value = liquido_pagar;
        }



        function actualizarTotalPrimaCalculadaVida(element) {
            // --- Helpers ---
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

            // --- Actualiza inputs ocultos ---
            const inputPrima = document.getElementById('PrimaCalculadaDetalle');
            if (inputPrima) inputPrima.value = primaCalculada;
            const subTotalInput = document.getElementById('SubTotalDetalle');
            if (subTotalInput) subTotalInput.value = primaCalculada;

            // ✅ Actualiza también el campo visible "sub_total"
            document.getElementById('sub_total').textContent = fmt(primaCalculada);

            // --- Variables auxiliares ---
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
            if (typeof cadena !== 'string') {

                if (typeof cadena === 'number') {
                    return cadena;
                }
                return NaN; // or return 0; depending on your needs
            }

            // Remove commas and convert to number
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
