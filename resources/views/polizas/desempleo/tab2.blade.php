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
                            <th>Monto</th>
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
                        <td>{{ $item['Monto'] }}</td>
                        <td>{{ $item['Fecha'] }}</td>
                        <td style="text-align: right;" class="numeric editable"
                            id="monto_otorgado_{{ $item['Id'] }}">
                            {{ number_format($item['MontoOtorgado'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" id="saldo_capital_{{ $item['Id'] }}">
                            {{ number_format($item['SaldoCapital'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" id="interes_{{ $item['Id'] }}">
                            {{ number_format($item['Intereses'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" id="interes_covid_{{ $item['Id'] }}">
                            {{ number_format($item['InteresesCovid'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable"
                            id="interes_moratorio_{{ $item['Id'] }}">
                            {{ number_format($item['InteresesMoratorios'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable" id="monto_nominal_{{ $item['Id'] }}">
                            {{ number_format($item['MontoNominal'], 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;" class="numeric editable"
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
                    <td class="numeric editable" contenteditable="true" id="total_prima_calculada">
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
                        <td class="numeric editable" contenteditable="true" id="sub_total_ccf">
                            {{ number_format($data['subTotalCcf'], 2, '.', ',') }}
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
                        <td class="numeric editable" contenteditable="true" id="comision_ccf">
                            {{ number_format($data['comisionCcf'], 2, '.', ',') }}
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
                    <tr>
                        <td>(-) Estructura CCF de Comisión (%) </td>
                        <td class="numeric editable"><span
                                id="comision">{{ number_format($data['comisionCcf'], 2, '.', ',') }}</span></td>
                    </tr>
                    <tr>
                        <td>Total a pagar</td>
                        <td class="numeric total editable" contenteditable="true" id="liquido_pagar">
                            {{ number_format($data['liquidoApagar'], 2, '.', ',') }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br><br><br>
        </div>

        <div>












            <br><br>

            <div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    @if ($totalPrimaCalculada > 0)
                        <a class="btn btn-warning" data-target="#modal-reiniciar" data-toggle="modal">Reiniciar
                            carga</a>
                    @else
                        <a class="btn btn-warning" disabled>Reiniciar
                            carga</a>
                    @endif
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="text-align: right">



                    <a class="btn btn-default" data-target="#modal-cancelar" data-toggle="modal">Cancelar
                        Cobro</a>
                    <a class="btn btn-primary" data-target="#modal-aplicar" data-toggle="modal">Generar Cobro</a>
                </div>

            </div>
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

        <div class="modal fade" id="modal-reiniciar" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
            <div class="modal-dialog">
                <form action="{{ url('poliza/desempleo/reiniciar_carga') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Reiniciar carga</h4>
                            <input type="hidden" name="PolizaDesempleo" value="{{ $desempleo->Id }}">
                            <input type="hidden" name="Mes" value="{{ isset($fechas) ? $fechas->Mes : '' }}">
                            <input type="hidden" name="Axo" value="{{ isset($fechas) ? $fechas->Axo : '' }}">
                        </div>
                        <div class="modal-body">
                            <p>¿Está seguro/a que desea reinicar el proceso?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button class="btn btn-danger">Reiniciar carga</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


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
                    <form action="{{ url('polizas/desempleo/agregar_pago') }}" method="POST">
                        @csrf



                        <div class="modal-body">
                            <div class="card mt-4 p-3 bg-light" style="display: none">
                                <h5>🔍 Depuración de valores (inputs visibles)</h5>
                                <div class="row g-2">


                                    <div class="col-md-3">
                                        <label>Fecha inicio</label>
                                        <input type="text" class="form-control" name="FechaInicio"
                                            value="{{ isset($fechas) ? $fechas->FechaInicio : '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Fecha Final</label>
                                        <input type="text" class="form-control" name="FechaFinal"
                                            value="{{ isset($fechas) ? $fechas->FechaFinal : '' }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Monto Cartera</label>
                                        <input type="text" class="form-control" name="MontoCartera"
                                            id="MontoCarteraDetalle" value="{{ $data['total'] }}">
                                    </div>


                                    <div class="col-md-3">
                                        <label>Desempleo (ID)</label>
                                        <input type="text" class="form-control" name="Desempleo"
                                            value="{{ $desempleo->Id }}">
                                    </div>


                                    <div class="col-md-3">
                                        <label>Año</label>
                                        <input type="text" class="form-control" name="Axo"
                                            value="{{ isset($fechas) ? $fechas->Axo : '' }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Mes</label>
                                        <input type="text" class="form-control" name="Mes"
                                            value="{{ isset($fechas) ? $fechas->Mes : '' }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Tasa</label>
                                        <input type="text" class="form-control" name="Tasa"
                                            value="{{ $desempleo->Tasa }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Prima Calculada</label>
                                        <input type="text" class="form-control" name="PrimaCalculada"
                                            id="PrimaCalculadaDetalle" value="{{ $data['primaPorPagar'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Prima Descontada</label>
                                        <input type="text" class="form-control" name="PrimaDescontada"
                                            id="PrimaDescontadaDetalle" value="{{ $data['primaDescontada'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Sub Total</label>
                                        <input type="text" class="form-control" name="SubTotal"
                                            id="SubTotalDetalle" value="">
                                    </div>

                                    <div class="col-md-3">
                                        <label>IVA</label>
                                        <input type="text" class="form-control" name="Iva" id="IvaDetalle"
                                            value="">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Tasa Comisión</label>
                                        <input type="text" class="form-control" name="TasaComision"
                                            value="{{ $desempleo->TasaComision ?? 0 }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Comisión</label>
                                        <input type="text" class="form-control" name="Comision"
                                            id="ComisionDetalle" value="{{ $data['valorComision'] ?? 0 }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>IVA Comisión</label>
                                        <input type="text" class="form-control" name="IvaSobreComision"
                                            id="IvaComisionDetalle" value="{{ $data['ivaComision'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Descuento</label>
                                        <input type="text" class="form-control" name="Descuento"
                                            id="DescuentoDetalle" value="{{ $data['descuento'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Retención</label>
                                        <input type="text" class="form-control" name="Retencion"
                                            id="RetencionDetalle" value="{{ $data['retencionComision'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Valor CCF</label>
                                        <input type="text" class="form-control" name="ValorCCF"
                                            id="ValorCCFDetalle" value="{{ $data['comisionCcf'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>A Pagar</label>
                                        <input type="text" class="form-control" name="APagar" id="APagarDetalle"
                                            value="{{ $data['liquidoApagar'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Extra Prima</label>
                                        <input type="text" class="form-control" name="ExtraPrima"
                                            value="{{ $data['extra_prima'] }}">
                                    </div>
                                </div>
                            </div>
                            <p>¿Desea generar el aviso de cobro?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button id="boton_pago" class="btn btn-primary">Generar Aviso de cobro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        inicializarEventosEditables();
        recalcularTotales(); // cálculo inicial
    });

    // ========================
    // 🎯 Inicializar eventos
    // ========================
    function inicializarEventosEditables() {
        document.querySelectorAll(".editable").forEach(cell => {
            cell.addEventListener("blur", actualizarCampoEditable);
        });
    }

    // ========================
    // ✏️ Cuando se edita una celda
    // ========================
    function actualizarCampoEditable(event) {
        const el = event.target;
        const id = el.id || "";
        const valorNuevo = convertirANumero(el.innerText);

        if (isNaN(valorNuevo)) {
            el.style.backgroundColor = "#ffe0e0";
            console.warn(`⚠️ Valor inválido en ${id}`);
            return;
        }
        el.style.backgroundColor = "";
        el.textContent = formatearNumero(valorNuevo);

        // === 1️⃣ Prima individual ===
        if (id.startsWith("prima_calculada_")) {
            console.log(`🟢 Prima individual modificada`);
            recalcularTotales();
            return;
        }

        // === 2️⃣ Total prima calculada ===
        if (id === "total_prima_calculada") {
            console.log(`🟢 Total prima calculada modificado → recalculo posterior`);
            recalcularDesdeTotalPrima(valorNuevo);
            return;
        }

        // === 3️⃣ Subtotal CCF ===
        if (id === "sub_total_ccf") {
            console.log(`🟢 Subtotal CCF modificado → recalculo posterior`);
            recalcularDesdeSubTotalCCF(valorNuevo);
            return;
        }

        // === 4️⃣ Comisión CCF ===
        if (id === "comision_ccf") {
            console.log(`🟢 Comisión CCF modificada → recalculo líquido`);
            recalcularDesdeComisionCCF(valorNuevo);
            return;
        }

        // === 5️⃣ Líquido a pagar ===
        if (id === "liquido_pagar") {
            console.log(`🟢 Líquido modificado manualmente`);
            document.getElementById('APagarDetalle').value = valorNuevo;
            return;
        }
    }

    // ========================
    // 🔢 Cálculo general (inicio o prima editada)
    // ========================
    function recalcularTotales() {
        const toNumber = v => parseFloat(String(v ?? '').replace(/,/g, '')) || 0;
        const fmt = v => v.toLocaleString('es-SV', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        let totalPrima = 0;
        document.querySelectorAll('[id^="prima_calculada_"]').forEach(td => {
            totalPrima += toNumber(td.textContent);
        });

        // Actualiza total de primas
        document.getElementById("total_prima_calculada").textContent = fmt(totalPrima);
        document.getElementById("PrimaCalculadaDetalle").value = totalPrima;
        document.getElementById("SubTotalDetalle").value = totalPrima;
        document.getElementById("sub_total").textContent = fmt(totalPrima);

        recalcularEstructuraCcf(totalPrima);
    }

    // ========================
    // 💰 Cálculo completo desde subtotal de primas
    // ========================
    function recalcularEstructuraCcf(sub_total) {
        const toNumber = v => parseFloat(String(v ?? '').replace(/,/g, '')) || 0;
        const fmt = v => v.toLocaleString('es-SV', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        let extra_prima = toNumber(document.getElementById('ExtraPrima')?.value);
        let descuento_rentabilidad = toNumber(document.getElementById('DescuentoRentabilidad')?.value);
        let tasa_comision = toNumber(document.getElementById('TasaComisionDetalle')?.value);
        let tipo_contribuyente = toNumber(document.getElementById('TipoContribuyente')?.value);

        // Descuento
        let descuento = (sub_total + extra_prima) * (descuento_rentabilidad / 100);
        document.getElementById('descuento_rentabilidad').textContent = fmt(descuento);
        document.getElementById('DescuentoDetalle').value = descuento;

        // Prima a cobrar
        let prima_a_cobrar = (sub_total + extra_prima) - descuento;
        document.getElementById("prima_a_cobrar").textContent = fmt(prima_a_cobrar);
        document.getElementById("prima_a_cobrar_ccf").textContent = fmt(prima_a_cobrar);
        document.getElementById('PrimaDescontadaDetalle').value = prima_a_cobrar;

        // Comisión
        let valor_comision = prima_a_cobrar * (tasa_comision / 100);
        document.getElementById('valor_comision').textContent = fmt(valor_comision);
        document.getElementById('ComisionDetalle').value = valor_comision;

        // IVA comisión
        let iva_comision = tipo_contribuyente !== 4 ? valor_comision * 0.13 : 0;
        document.getElementById('iva_comision').textContent = fmt(iva_comision);
        document.getElementById('IvaComisionDetalle').value = iva_comision;

        // Subtotal CCF
        let sub_total_ccf = valor_comision + iva_comision;
        document.getElementById('sub_total_ccf').textContent = fmt(sub_total_ccf);

        // Retención
        let retencion_comision = (tipo_contribuyente !== 1 && valor_comision >= 100) ?
            valor_comision * 0.01 : 0;
        document.getElementById('retencion_comision').textContent = fmt(retencion_comision);
        document.getElementById('RetencionDetalle').value = retencion_comision;

        // Comisión CCF
        let comision_ccf = sub_total_ccf - retencion_comision;
        document.getElementById('comision_ccf').textContent = fmt(comision_ccf);
        document.getElementById('comision').textContent = fmt(comision_ccf);
        document.getElementById('ValorCCFDetalle').value = comision_ccf;

        // ✅ Monto total cartera (CORREGIDO)
        let total_suma_asegurada = toNumber(document.getElementById('total_suma_asegurada')?.textContent);
        document.getElementById('monto_total_cartera').textContent = fmt(total_suma_asegurada);
        document.getElementById('MontoCarteraDetalle').value = total_suma_asegurada;

        // Líquido
        let liquido = prima_a_cobrar - comision_ccf;
        document.getElementById('liquido_pagar').textContent = fmt(liquido);
        document.getElementById('APagarDetalle').value = liquido;
    }


    // ========================
    // ⚙️ Recalcular solo desde total_prima_calculada
    // ========================
    function recalcularDesdeTotalPrima(total) {
        document.getElementById("PrimaCalculadaDetalle").value = total;
        document.getElementById("SubTotalDetalle").value = total;
        document.getElementById("sub_total").textContent = formatearNumero(total);
        recalcularEstructuraCcf(total);
    }

    // ========================
    // ⚙️ Recalcular desde sub_total_ccf
    // ========================
    function recalcularDesdeSubTotalCCF(sub_total_ccf) {
        const toNumber = v => parseFloat(String(v ?? '').replace(/,/g, '')) || 0;
        const fmt = v => v.toLocaleString('es-SV', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        const retencion = toNumber(document.getElementById('retencion_comision')?.textContent);
        const comision_ccf = sub_total_ccf - retencion;
        document.getElementById('comision_ccf').textContent = fmt(comision_ccf);
        document.getElementById('ValorCCFDetalle').value = comision_ccf;

        const prima_a_cobrar = toNumber(document.getElementById('prima_a_cobrar')?.textContent);
        const liquido = prima_a_cobrar - comision_ccf;
        document.getElementById('liquido_pagar').textContent = fmt(liquido);
        document.getElementById('APagarDetalle').value = liquido;
    }

    // ========================
    // ⚙️ Recalcular desde comision_ccf
    // ========================
    function recalcularDesdeComisionCCF(comision_ccf) {
        const toNumber = v => parseFloat(String(v ?? '').replace(/,/g, '')) || 0;
        const fmt = v => v.toLocaleString('es-SV', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        const prima_a_cobrar = toNumber(document.getElementById('prima_a_cobrar')?.textContent);
        const liquido = prima_a_cobrar - comision_ccf;
        document.getElementById('liquido_pagar').textContent = fmt(liquido);
        document.getElementById('APagarDetalle').value = liquido;
    }

    // ========================
    // 🔧 Utilidades
    // ========================
    function convertirANumero(cadena) {
        if (!cadena) return 0;
        const limpio = cadena.toString().replace(/[^0-9.-]+/g, "");
        return parseFloat(limpio) || 0;
    }

    function formatearNumero(num) {
        return Number(num).toLocaleString('es-SV', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
</script>
