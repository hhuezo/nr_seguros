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


<ul class="nav navbar-right panel_toolbox">
    <a href="{{ url('polizas/desempleo/subir_cartera') }}/{{ $desempleo->Id }}" class="btn btn-info float-right">
        Subir Archivo Excel</a>
</ul>

<br>

<div class="modal-body">
    <div class="box-body row">
        <br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="excel-like-table">
                <tr>
                    <td>Fecha Inicio:
                        {{ $fechas != null ? date('d/m/Y', strtotime($fechas->FechaInicio)) : '' }}
                    </td>
                    <td>Fecha Final:
                        {{ $fechas != null ? date('d/m/Y', strtotime($fechas->FechaFinal)) : '' }}
                    </td>
                    <td>Mes: {{ $fechas != null ? $meses[$fechas->Mes] : '' }}</td>
                </tr>
            </table>
            <br>



            <input type="hidden" id="Tasa" value="{{ $desempleo->Tasa }}">
            @if ($desempleo->ComisionIva == 1)
                @php($var = $desempleo->TasaComision / 1.13)
                <input type="hidden" id="TasaComisionDetalle" value="{{ $var }}">
            @else
                <input type="hidden" id="TasaComisionDetalle" value="{{ $desempleo->TasaComision }}">
            @endif
            <input type="hidden" id="ExtraPrima" value="0">
            <input type="hidden" id="TipoContribuyente" value="{{ $desempleo->cliente->TipoContribuyente }}">
            <input type="hidden" id="ComisionIva" value="{{ $desempleo->ComisionIva }}">
            <input type="hidden" id="DescuentoRentabilidad" value="{{ $desempleo->Descuento }}">

            <table class="excel-like-table">
                <thead>

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
                            <th>Prima calculada</th>
                        </tr>
                    </thead>
                </thead>
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
                        <td>{{ $item['DescripcionLineaCredito'] }} ({{ $item['AbreviaturaLineaCredito'] }})
                        </td>
                        <td>{{ $item['Tasa'] }}%</td>
                        <td>{{ $item['Edad'] }}</td>
                        <td>{{ $item['Fecha'] }}</td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="monto_otorgado_{{ $item['Id'] }}">
                            {{ number_format($item['MontoOtorgado'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="saldo_capital_{{ $item['Id'] }}">
                            {{ number_format($item['SaldoCapital'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="interes_{{ $item['Id'] }}">
                            {{ number_format($item['Intereses'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="interes_covid_{{ $item['Id'] }}">
                            {{ number_format($item['InteresesCovid'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="interes_moratorio_{{ $item['Id'] }}">
                            {{ number_format($item['InteresesMoratorios'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="monto_nominal_{{ $item['Id'] }}">
                            {{ number_format($item['MontoNominal'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="suma_asegurada_{{ $item['Id'] }}">
                            {{ number_format($item['TotalCredito'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" contenteditable="true"
                            id="prima_calculada_{{ $item['Id'] }}">
                            {{ number_format($item['PrimaCalculada'], 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="4">Totales</th>
                    <td class="numeric"><span
                            id="total_monto_otorgado">{{ number_format($totalMontoOtorgado, 2, '.', ',') }}</span>
                    </td>
                    <td class="numeric"><span
                            id="total_saldo_capital">{{ number_format($totalSaldoCapital, 2, '.', ',') }}</span>
                    </td>
                    <td class="numeric"><span id="total_interes">{{ number_format($totalInteres, 2, '.', ',') }}</span>
                    </td>
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
                    <td class="numeric" contenteditable="true" id="total_prima_calculada"
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
                        <td>Porcentaje de Comisión </td>
                        <td class="numeric editable"><span>{{ $data['tasaComision'] ?? 0.0 }} %</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Prima a cobrar</td>
                        <td class="numeric editable"><span
                                id="prima_a_cobrar_ccf">{{ number_format((float) ($data['valorComision'] ?? 0), 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Valor de comisión</td>
                        <td class="numeric editable"><span
                                id="valor_comision">{{ number_format($data['valorComision'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(+) 13% IVA</td>
                        <td class="numeric editable"><span
                                id="iva_comision">{{ number_format($data['ivaComision'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Sub Total Comision</td>
                        <td class="numeric editable"><span
                                id="sub_total_ccf">{{ number_format($data['subTotalCcf'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(-) 1% Retención</td>
                        <td class="numeric editable"><span
                                id="retencion_comision">{{ number_format($data['retencionComision'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(=) Valor CCF Comisión</td>
                        <td class="numeric editable"><span
                                id="comision_ccf">{{ number_format($data['comisionCcf'], 2, '.', ',') }}</span>
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
                        <td class="numeric editable"><span
                                id="monto_total_cartera">{{ number_format($data['total'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Prima calculada</td>
                        <td class="numeric editable"><span
                                id="sub_total">{{ number_format($data['primaPorPagar'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Extra Prima</td>
                        <td class="numeric editable"><span
                                id="sub_total_extra_prima">{{ number_format($data['extra_prima'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(-) Descuento rentabilidad (%)
                        </td>
                        <td class="numeric editable"><span
                                id="descuento_rentabilidad">{{ number_format($data['descuento'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(=) Prima descontada</td>
                        <td class="numeric editable"><span
                                id="prima_a_cobrar">{{ number_format($data['primaCobrar'], 2, '.', ',') }}</span>
                        </td>
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
                        <td>(-) Estructura CCF de Comisión (%) </td>
                        <td class="numeric editable"><span
                                id="comision">{{ number_format($data['comisionCcf'], 2, '.', ',') }}</span></td>
                    </tr>
                    <tr>
                        <td>Total a pagar</td>
                        <td class="numeric total" contenteditable="true" id="liquido_pagar" onblur="total()">
                            {{ number_format($data['liquidoApagar'], 2, '.', ',') }} </td>
                    </tr>
                </tbody>
            </table>
            <br><br><br>
        </div>

        <div>
            <form action="{{ url('polizas/desempleo/agregar_pago') }}" method="POST">
                @csrf


                <input type="hidden" class="form-control" name="FechaInicio"
                    value="{{ isset($fechas) ? $fechas->FechaInicio : '' }}">







                <input type="hidden" class="form-control" name="FechaFinal"
                    value="{{ isset($fechas) ? $fechas->FechaFinal : '' }}">
                <input type="hidden" class="form-control" name="MontoCartera" id="MontoCarteraDetalle"
                    value="{{ $data['total'] }}">
                <input type="hidden" class="form-control" name="Desempleo" value="{{ $desempleo->Id }}">
                <input type="hidden" class="form-control" name="Tasa" value="{{ $desempleo->Tasa }}">
                <input type="hidden" class="form-control" name="PrimaCalculada" id="PrimaCalculadaDetalle"
                    value="{{ $data['primaPorPagar'] }}">
                <input type="hidden" class="form-control" name="PrimaDescontada" id="PrimaDescontadaDetalle"
                    value="{{ $data['primaDescontada'] }}">
                <input type="hidden" class="form-control" name="SubTotal" id="SubTotalDetalle">
                <input type="hidden" class="form-control" name="Iva" id="IvaDetalle">
                <input type="hidden" class="form-control" name="TasaComision"
                    value="{{ $desempleo->TasaComision ?? 0 }}">
                <input type="hidden" class="form-control" name="Comision" id="ComisionDetalle"
                    value="{{ $data['valorComision'] ?? 0 }}">
                <input type="hidden" class="form-control" name="IvaSobreComision" id="IvaComisionDetalle"
                    value="{{ $data['ivaComision'] }}">
                <input type="hidden" class="form-control" name="Descuento" id="DescuentoDetalle"
                    value="{{ $data['descuento'] }}">
                <input type="hidden" class="form-control" name="Retencion" id="RetencionDetalle"
                    value="{{ $data['retencionComision'] }}">
                <input type="hidden" class="form-control" name="ValorCCF" id="ValorCCFDetalle"
                    value="{{ $data['comisionCcf'] }}">
                <input type="hidden" class="form-control" name="APagar" id="APagarDetalle"
                    value="{{ $data['liquidoApagar'] }}">
                <input type="hidden" class="form-control" name="ExtraPrima" value="{{ $data['extra_prima'] }}">

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
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                <form action="{{ url('polizas/desempleo/cancelar_pago') }}" method="POST">
                    @method('POST')
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Cancelar Cobro</h4>

                            <input type="hidden" name="Desempleo" value="{{ $desempleo->Id }}">
                            <input type="hidden" name="MesCancelar" value="{{ isset($fecha) ? $fecha->Mes : '' }}">
                            <input type="hidden" name="AxoCancelar" value="{{ isset($fecha) ? $fecha->Axo : '' }}">
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

        // --- Helper seguro ---
        function toNumber(value) {
            let n = parseFloat(value?.toString().replace(/,/g, ''));
            return isNaN(n) ? 0 : n;
        }

        function getTextValue(id) {
            const el = document.getElementById(id);
            return el ? toNumber(el.textContent || el.innerText || el.value) : 0;
        }

        function formatearCantidad(valor) {
            return toNumber(valor).toLocaleString('es-SV', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // ✅ En lugar de recorrer filas, tomamos directamente los totales ya generados en la tabla
        let total_suma_asegurada = getTextValue('total_suma_asegurada');
        let total_prima_calculada = getTextValue('total_prima_calculada');
        let total_interes = getTextValue('total_interes');
        let total_interes_covid = getTextValue('total_interes_covid');
        let total_interes_moratorio = getTextValue('total_interes_moratorio');
        let total_saldo_capital = getTextValue('total_saldo_capital');
        let total_monto_otorgado = getTextValue('total_monto_otorgado');

        // Usamos el total de prima calculada como sub_total
        let sub_total = total_prima_calculada;

        // --- Datos auxiliares ---
        let tasa_comision = toNumber(document.getElementById('TasaComisionDetalle')?.value);
        let tipo_contribuyente = {{ $deuda->clientes->TipoContribuyente ?? 0 }};
        let extra_prima = toNumber(document.getElementById('ExtraPrima')?.value);
        let descuento_rentabilidad = toNumber(document.getElementById('DescuentoRentabilidad')?.value);

        // --- Totales base (solo asignación, sin recalcular) ---
        document.getElementById("monto_total_cartera").textContent = formatearCantidad(
            total_suma_asegurada);
        document.getElementById('MontoCarteraDetalle').value = total_suma_asegurada;
        document.getElementById('PrimaCalculadaDetalle').value = sub_total;

        document.getElementById("sub_total").textContent = formatearCantidad(sub_total);
        document.getElementById('SubTotalDetalle').value = sub_total;
        document.getElementById("sub_total_extra_prima").textContent = formatearCantidad(extra_prima);

        // --- Descuento ---
        let descuento = (sub_total + extra_prima) * (descuento_rentabilidad / 100);
        descuento = toNumber(descuento);

        document.getElementById('descuento_rentabilidad').textContent = formatearCantidad(descuento);
        document.getElementById('DescuentoDetalle').value = descuento;

        // --- Prima a cobrar ---
        let prima_a_cobrar = (sub_total + extra_prima) - descuento;
        prima_a_cobrar = toNumber(prima_a_cobrar);

        document.getElementById("prima_a_cobrar").textContent = formatearCantidad(prima_a_cobrar);
        document.getElementById("prima_a_cobrar_ccf").textContent = formatearCantidad(prima_a_cobrar);
        document.getElementById('PrimaDescontadaDetalle').value = prima_a_cobrar;

        // --- IVA (no contribuyente no paga) ---
        let iva = tipo_contribuyente !== 4 ? 0 : 0;
        document.getElementById('IvaDetalle').value = iva;

        // --- Comisión ---
        let valor_comision = prima_a_cobrar * (tasa_comision / 100);
        valor_comision = toNumber(valor_comision);

        document.getElementById('valor_comision').textContent = formatearCantidad(valor_comision);
        document.getElementById('ComisionDetalle').value = valor_comision;

        // --- IVA sobre comisión ---
        let iva_comision = tipo_contribuyente !== 4 ? valor_comision * 0.13 : 0;
        iva_comision = toNumber(iva_comision);

        document.getElementById('iva_comision').textContent = formatearCantidad(iva_comision);
        document.getElementById('IvaComisionDetalle').value = iva_comision;

        // --- Subtotal CCF ---
        let sub_total_ccf = valor_comision + iva_comision;
        document.getElementById('sub_total_ccf').textContent = formatearCantidad(sub_total_ccf);

        // --- Retención ---
        let retencion_comision = (tipo_contribuyente !== 1 && valor_comision >= 100) ?
            valor_comision * 0.01 : 0;

        document.getElementById('retencion_comision').textContent = formatearCantidad(retencion_comision);

        // --- Comisión CCF ---
        let comision_ccf = sub_total_ccf - retencion_comision;
        document.getElementById('comision_ccf').textContent = formatearCantidad(comision_ccf);
        document.getElementById('comision').textContent = formatearCantidad(comision_ccf);

        // --- Líquido a pagar ---
        let liquido_pagar = prima_a_cobrar - comision_ccf;
        document.getElementById("liquido_pagar").textContent = formatearCantidad(liquido_pagar);

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

        // ✅ Actualiza también el campo visible "sub_total"
        document.getElementById('sub_total').textContent = fmt(primaCalculada);

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
