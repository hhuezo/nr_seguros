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

    <input type="hidden" id="TasaComisionDetalle" value="{{ $tasaComision }}">
    <input type="hidden" id="ExtraPrima" value="{{ $total_extrapima }}">
   <input type="hidden" id="ComisionIva" value="{{ $poliza_vida->ComisionIva }}">
    <input type="hidden" id="DescuentoRentabilidad" value="{{ $poliza_vida->Descuento ?? 0.00}}">
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
                            <td>{{ $item['Fecha'] }}</td>
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
                            {{ number_format($totalSumaAsegurada, 2, '.', ',') }}</td>
                        <td id="total_prima_calculada" style="text-align: right;">
                            {{ number_format($totalPrimaCalculada, 2, '.', ',') }}</td>
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

            function calculoTotales() {

                let total_suma_asegurada = 0;
                let total_prima_calculada = 0;

                let sub_total = 0;

                for (let i = 0; i < idRegistroArray.length; i++) {

                    let idRegistro = idRegistroArray[i];


                    let elemento_suma_asegurada = document.getElementById("suma_asegurada_" + idRegistro);
                    let suma_asegurada = elemento_suma_asegurada ? (elemento_suma_asegurada.innerText ||
                        elemento_suma_asegurada.textContent) : 0;




                    let elemento_prima_calculada = document.getElementById("prima_calculada_" + idRegistro);
                    let prima_calculada = elemento_prima_calculada ? (elemento_prima_calculada.innerText ||
                        elemento_prima_calculada.textContent) : 0;


                    total_suma_asegurada += convertirANumero(suma_asegurada);
                    total_prima_calculada += convertirANumero(prima_calculada);

                }

                sub_total = total_prima_calculada;


                //escribiendo totales
                let total_suma_asegurada_formateada = formatearCantidad(total_suma_asegurada);
                //console.log("elemento_suma_asegurada ", total_suma_asegurada_formateada);
                document.getElementById("total_suma_asegurada").textContent = total_suma_asegurada_formateada;

                let total_prima_calculada_formateada = formatearCantidad(total_prima_calculada);
                document.getElementById("total_prima_calculada").textContent = total_prima_calculada_formateada;

                let tasa = document.getElementById('Tasa').value;
                let comision_iva = document.getElementById('ComisionIva').value;

                let tasa_comision = parseFloat(document.getElementById('TasaComisionDetalle')?.value) || 0;
                let tipo_contribuyente = {{ $poliza_vida->cliente->TipoContribuyente }};
               //console.log("tasa_comision: " + tasa_comision);


                //modificando valores de cuadros
                document.getElementById("monto_total_cartera").textContent = total_suma_asegurada_formateada;
                document.getElementById('MontoCarteraDetalle').value = parseFloat(total_suma_asegurada);
                document.getElementById('PrimaCalculadaDetalle').value = sub_total;


                document.getElementById("sub_total").textContent = formatearCantidad(sub_total);
                document.getElementById('SubTotalDetalle').value = sub_total;
                let extra_prima = document.getElementById('ExtraPrima').value;
                document.getElementById("sub_total_extra_prima").textContent = formatearCantidad(extra_prima);


                let descuento = (parseFloat(sub_total) + parseFloat(extra_prima)) * parseFloat(parseFloat(document.getElementById('DescuentoRentabilidad').value) / 100);



                document.getElementById('descuento_rentabilidad').textContent = formatearCantidad(descuento);
                document.getElementById('DescuentoDetalle').value = parseFloat(descuento);
                prima_a_cobrar = (parseFloat(sub_total) + parseFloat(extra_prima)) - parseFloat(descuento);
                document.getElementById("prima_a_cobrar").textContent = formatearCantidad(prima_a_cobrar);
                document.getElementById("prima_a_cobrar_ccf").textContent = formatearCantidad(prima_a_cobrar);

                // no contribuyente no paga iva
                iva = tipo_contribuyente !== 4 ? 0 : 0;
                document.getElementById('PrimaDescontadaDetalle').value = parseFloat(prima_a_cobrar);



                // document.getElementById('iva').textContent = formatearCantidad(iva);
                document.getElementById('IvaDetalle').value = parseFloat(iva);
                let total_factura = parseFloat(iva) + parseFloat(prima_a_cobrar);


                let valor_comision = parseFloat(prima_a_cobrar) * (parseFloat(tasa_comision) / 100);
                document.getElementById('valor_comision').textContent = formatearCantidad(valor_comision);
                console.log(valor_comision);
                document.getElementById('ComisionDetalle').value = parseFloat(valor_comision);
                let iva_comision = tipo_contribuyente !== 4 ? parseFloat(valor_comision) * 0.13 : 0;
                document.getElementById('iva_comision').textContent = formatearCantidad(iva_comision);
                document.getElementById('IvaComisionDetalle').value = parseFloat(iva_comision);

                let sub_total_ccf = parseFloat(valor_comision) + parseFloat(iva_comision);
                document.getElementById('sub_total_ccf').textContent = formatearCantidad(sub_total_ccf);

                let retencion_comision = tipo_contribuyente !== 1 ? parseFloat(valor_comision) * 0.01 : 0;

                console.log(tipo_contribuyente);
                document.getElementById('retencion_comision').textContent = formatearCantidad(retencion_comision);
                let comision_ccf = parseFloat(sub_total_ccf) - parseFloat(retencion_comision);
                document.getElementById('comision_ccf').textContent = formatearCantidad(comision_ccf);
                document.getElementById('comision').textContent = formatearCantidad(comision_ccf);
                let liquido_pagar = parseFloat(prima_a_cobrar) - parseFloat(comision_ccf);
                document.getElementById("liquido_pagar").textContent = formatearCantidad(liquido_pagar);
                document.getElementById('RetencionDetalle').value = parseFloat(retencion_comision);
                document.getElementById('ValorCCFDetalle').value = parseFloat(comision_ccf);
                document.getElementById('APagarDetalle').value = parseFloat(liquido_pagar);

            }

        });


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
