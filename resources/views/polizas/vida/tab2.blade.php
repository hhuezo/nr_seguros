<!-- ==================== OVERLAY OPCIONAL ==================== -->
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

<br>

<!-- ==================== CAMPOS PRINCIPALES OCULTOS ==================== -->
<input type="hidden" id="Tasa" value="{{ $poliza_vida->Tasa }}">
@php
    $tasaComision = $poliza_vida->ComisionIva == 1 ? $poliza_vida->TasaComision / 1.13 : $poliza_vida->TasaComision;
@endphp

<input type="hidden" id="TasaComisionDetalle" value="{{ $tasaComision }}">
<input type="hidden" id="ExtraPrima" value="{{ $total_extrapima }}">
<input type="hidden" id="ComisionIva" value="{{ $poliza_vida->ComisionIva }}">
<input type="hidden" id="DescuentoRentabilidad" value="{{ $poliza_vida->TasaDescuento ?? 0.0 }}">
<input type="hidden" id="TipoContribuyente" value="{{ $poliza_vida->cliente->TipoContribuyente }}">

<div class="modal-body">
    <div class="box-body row">
         <div class="col-md-6">

            <h4 class="title" id="exampleModalLabel">
                {{$val > 0 ? 'Nuevo pago':'Ultimo periodo facturado'}}</h4>
        </div>
        <br>

        <!-- ==================== TABLA PRINCIPAL ==================== -->
        <div class="col-lg-12">
            <table class="excel-like-table">
                <tr>
                    <td>Fecha Inicio:
                        {{ !empty($fechas->FechaInicio ?? null) ? date('d/m/Y', strtotime($fechas->FechaInicio)) : '' }}
                    </td>
                    <td>Fecha Final:
                        {{ !empty($fechas->FechaFinal ?? null) ? date('d/m/Y', strtotime($fechas->FechaFinal)) : '' }}
                    </td>
                    <td>Mes:
                        {{ isset($fechas->Mes) && isset($meses[$fechas->Mes]) ? $meses[$fechas->Mes] : '' }}
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
                    $totalSumaAsegurada = 0;
                    $totalPrimaCalculada = 0;
                @endphp

                @foreach ($dataPago as $item)
                    @php
                        $sumaAsegurada = str_replace(',', '', $item['SumaAsegurada']);
                        $primaCalculada = str_replace(',', '', $item['PrimaCalculada']);
                        $totalSumaAsegurada += floatval($sumaAsegurada);
                        $totalPrimaCalculada += floatval($primaCalculada);
                    @endphp
                    <tr>
                        <td>{{ $item['TipoCartera'] }}</td>
                        <td>{{ $item['Tasa'] }}</td>
                        <td>{{ $item['Monto'] }}</td>
                        <td>{{ $item['Fecha'] }}</td>
                        <td id="suma_asegurada_{{ $item['Id'] }}" class="numeric editable" contenteditable="true">
                            {{ number_format($item['SumaAsegurada'], 2, '.', ',') }}
                        </td>
                        <td id="prima_calculada_{{ $item['Id'] }}" class="numeric editable" contenteditable="true">
                            {{ number_format($item['PrimaCalculada'], 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4">Totales</th>
                    <td class="numeric" id="total_suma_asegurada">{{ number_format($totalSumaAsegurada, 2, '.', ',') }}
                    </td>
                    <td class="numeric editable" id="total_prima_calculada" contenteditable="true">
                        {{ number_format($totalPrimaCalculada, 2, '.', ',') }}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-12">&nbsp;</div>

        <!-- ==================== ESTRUCTURA CCF ==================== -->
        <div class="col-lg-6">
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
                        <td class="numeric editable"><span>{{ number_format($tasaComision, 2, '.', ',') }} %</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Prima a cobrar</td>
                        <td class="numeric editable"><span
                                id="prima_a_cobrar_ccf">{{ number_format($data['primaCobrar'] ?? 0, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Valor de comisión</td>
                        <td class="numeric editable"><span
                                id="valor_comision">{{ number_format($data['valorComision'] ?? 0, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(+) 13% IVA</td>
                        <td class="numeric editable"><span
                                id="iva_comision">{{ number_format($data['ivaComision'] ?? 0, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Sub Total Comisión</td>
                        <td class="numeric editable" id="sub_total_ccf" contenteditable="true">
                            {{ number_format($data['subTotalCcf'] ?? 0, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td>(-) 1% Retención</td>
                        <td class="numeric editable"><span
                                id="retencion_comision">{{ number_format($data['retencionComision'] ?? 0, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(=) Valor CCF Comisión</td>
                        <td class="numeric editable" id="comision_ccf" contenteditable="true">
                            {{ number_format($data['comisionCcf'] ?? 0, 2, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ==================== DETALLE GENERAL ==================== -->
        <div class="col-lg-6">
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
                                id="monto_total_cartera">{{ number_format($totalSumaAsegurada, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Prima calculada</td>
                        <td class="numeric editable"><span
                                id="sub_total">{{ number_format($totalPrimaCalculada, 2, '.', ',') }}</span></td>
                    </tr>
                    <tr>
                        <td>Extra Prima</td>
                        <td class="numeric editable"><span
                                id="sub_total_extra_prima">{{ number_format($total_extrapima, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(-) Descuento rentabilidad (%)</td>
                        <td class="numeric editable"><span
                                id="descuento_rentabilidad">{{ number_format($data['descuento'] ?? 0, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(=) Prima descontada</td>
                        <td class="numeric editable"><span
                                id="prima_a_cobrar">{{ number_format($data['primaCobrar'] ?? 0, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>(-) Estructura CCF de Comisión (%)</td>
                        <td class="numeric editable"><span
                                id="comision">{{ number_format($data['comisionCcf'] ?? 0, 2, '.', ',') }}</span></td>
                    </tr>
                    <tr>
                        <td>Total a pagar</td>
                        <td class="numeric total editable" contenteditable="true" id="liquido_pagar">
                            {{ number_format($data['liquidoApagar'] ?? 0, 2, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>
            <br><br>
        </div>





        <!-- ... (todo el contenido anterior, incluyendo las tablas y el bloque de depuración) ... -->

        <!-- ==================== FORMULARIO DE ACCIONES ==================== -->
        <div>

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


                    @if($totalPrimaCalculada > 0)
                    <a class="btn btn-success" href="{{ url('vida/exportar_excel') }}/{{ $poliza_vida->Id }}">Exportar Cartera</a>
                    @endif
                    <a class="btn btn-default" data-target="#modal-cancelar" data-toggle="modal">Cancelar Cobro</a>
                    <a class="btn btn-primary" data-target="#modal-aplicar" data-toggle="modal">Generar Cobro</a>
                </div>

            </div>

        </div>



        <!-- 🔹 MODAL: Generar Cobro -->
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
                    <form action="{{ url('polizas/vida/agregar_pago') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div style="display: none">
                                <div class="form-group">
                                    <label>Fecha inicio</label>
                                    <input type="text" class="form-control" name="FechaInicio"
                                        value="{{ isset($fechas) ? $fechas->FechaInicio : '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Fecha final</label>
                                    <input type="text" class="form-control" name="FechaFinal"
                                        value="{{ isset($fechas) ? $fechas->FechaFinal : '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Año</label>
                                    <input type="text" class="form-control" name="Axo"
                                        value="{{ isset($fechas) ? $fechas->Axo : '' }}">
                                </div>


                                <div class="form-group">
                                    <label>Mes</label>
                                    <input type="text" class="form-control" name="Mes"
                                        value="{{ isset($fechas) ? $fechas->Mes : '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Monto cartera</label>
                                    <input type="text" class="form-control" name="MontoCartera"
                                        id="MontoCarteraDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Poliza vida</label>
                                    <input type="text" class="form-control" name="PolizaVida"
                                        value="{{ $poliza_vida->Id }}">
                                </div>

                                <div class="form-group">
                                    <label>Tasa</label>
                                    <input type="text" class="form-control" name="Tasa"
                                        value="{{ $poliza_vida->Tasa }}">
                                </div>

                                <div class="form-group">
                                    <label>Prima calculada</label>
                                    <input type="text" class="form-control" name="PrimaCalculada"
                                        id="PrimaCalculadaDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Prima descontada</label>
                                    <input type="text" class="form-control" name="PrimaDescontada"
                                        id="PrimaDescontadaDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="text" class="form-control" name="SubTotal" id="SubTotalDetalle">
                                </div>

                                <div class="form-group">
                                    <label>IVA</label>
                                    <input type="text" class="form-control" name="Iva" id="IvaDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Tasa comisión</label>
                                    <input type="text" class="form-control" name="TasaComision"
                                        value="{{ $poliza_vida->TasaComision }}">
                                </div>

                                <div class="form-group">
                                    <label>Comisión</label>
                                    <input type="text" class="form-control" name="Comision" id="ComisionDetalle">
                                </div>

                                <div class="form-group">
                                    <label>IVA sobre comisión</label>
                                    <input type="text" class="form-control" name="IvaSobreComision"
                                        id="IvaComisionDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="text" class="form-control" name="Descuento"
                                        id="DescuentoDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Retención</label>
                                    <input type="text" class="form-control" name="Retencion"
                                        id="RetencionDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Valor CCF</label>
                                    <input type="text" class="form-control" name="ValorCCF" id="ValorCCFDetalle">
                                </div>

                                <div class="form-group">
                                    <label>A pagar</label>
                                    <input type="text" class="form-control" name="APagar" id="APagarDetalle">
                                </div>

                                <div class="form-group">
                                    <label>Extra prima</label>
                                    <input type="text" class="form-control" name="ExtraPrima"
                                        value="{{ $total_extrapima }}">
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

        <!-- 🔹 MODAL: Cancelar Cobro -->
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
                            <input type="hidden" name="MesCancelar"
                                value="{{ isset($fechas) ? $fechas->Mes : '' }}">
                            <input type="hidden" name="AxoCancelar"
                                value="{{ isset($fechas) ? $fechas->Axo : '' }}">
                        </div>
                        <div class="modal-body">
                            <p>¿Está seguro/a que desea cancelar el cobro?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button class="btn btn-danger">Cancelar Cobro</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="modal fade" id="modal-reiniciar" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
            <div class="modal-dialog">
                <form action="{{ url('poliza/vida/reiniciar_carga') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Reiniciar carga</h4>
                            <input type="hidden" name="PolizaVida" value="{{ $poliza_vida->Id }}">
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



    </div>
</div>

<!-- ==================== JAVASCRIPT (MISMO DE DESEMPLEO ADAPTADO) ==================== -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        inicializarEventosEditables();
        recalcularTotales();
    });

    function inicializarEventosEditables() {
        document.querySelectorAll(".editable").forEach(cell => {
            cell.addEventListener("blur", actualizarCampoEditable);
        });
    }

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

        if (id.startsWith("prima_calculada_")) return recalcularTotales();
        if (id === "total_prima_calculada") return recalcularDesdeTotalPrima(valorNuevo);
        if (id === "sub_total_ccf") return recalcularDesdeSubTotalCCF(valorNuevo);
        if (id === "comision_ccf") return recalcularDesdeComisionCCF(valorNuevo);
        if (id === "liquido_pagar") {
            document.getElementById('APagarDetalle').value = valorNuevo;
            return;
        }
    }

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

        document.getElementById("total_prima_calculada").textContent = fmt(totalPrima);
        document.getElementById("PrimaCalculadaDetalle").value = totalPrima;
        document.getElementById("SubTotalDetalle").value = totalPrima;
        document.getElementById("sub_total").textContent = fmt(totalPrima);

        recalcularEstructuraCcf(totalPrima);
    }

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

        let descuento = (sub_total + extra_prima) * (descuento_rentabilidad / 100);
        document.getElementById('descuento_rentabilidad').textContent = fmt(descuento);
        document.getElementById('DescuentoDetalle').value = descuento;

        let prima_a_cobrar = (sub_total + extra_prima) - descuento;
        document.getElementById("prima_a_cobrar").textContent = fmt(prima_a_cobrar);
        document.getElementById("prima_a_cobrar_ccf").textContent = fmt(prima_a_cobrar);
        document.getElementById('PrimaDescontadaDetalle').value = prima_a_cobrar;

        let valor_comision = prima_a_cobrar * (tasa_comision / 100);
        document.getElementById('valor_comision').textContent = fmt(valor_comision);
        document.getElementById('ComisionDetalle').value = valor_comision;

        let iva_comision = tipo_contribuyente !== 4 ? valor_comision * 0.13 : 0;
        document.getElementById('iva_comision').textContent = fmt(iva_comision);
        document.getElementById('IvaComisionDetalle').value = iva_comision;

        let sub_total_ccf = valor_comision + iva_comision;
        document.getElementById('sub_total_ccf').textContent = fmt(sub_total_ccf);

        let retencion_comision = (tipo_contribuyente !== 1 && valor_comision >= 100) ? valor_comision * 0.01 : 0;
        document.getElementById('retencion_comision').textContent = fmt(retencion_comision);
        document.getElementById('RetencionDetalle').value = retencion_comision;

        let comision_ccf = sub_total_ccf - retencion_comision;
        document.getElementById('comision_ccf').textContent = fmt(comision_ccf);
        document.getElementById('comision').textContent = fmt(comision_ccf);
        document.getElementById('ValorCCFDetalle').value = comision_ccf;

        let total_suma_asegurada = toNumber(document.getElementById('total_suma_asegurada')?.textContent);
        document.getElementById('monto_total_cartera').textContent = fmt(total_suma_asegurada);
        document.getElementById('MontoCarteraDetalle').value = total_suma_asegurada;

        let liquido = prima_a_cobrar - comision_ccf;
        document.getElementById('liquido_pagar').textContent = fmt(liquido);
        document.getElementById('APagarDetalle').value = liquido;
    }

    function recalcularDesdeTotalPrima(total) {
        document.getElementById("PrimaCalculadaDetalle").value = total;
        document.getElementById("SubTotalDetalle").value = total;
        document.getElementById("sub_total").textContent = formatearNumero(total);
        recalcularEstructuraCcf(total);
    }

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
