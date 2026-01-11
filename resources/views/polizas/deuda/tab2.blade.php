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
            <h4 class="title" id="exampleModalLabel">
                {{ data_get($dataPago->first(), 'PrimaCalculada', 0) > 0 ? 'Nuevo pago' : 'Ultimo periodo facturado' }}



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
                                <td style="text-align: right;" class="numeric editable" contenteditable="true"
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
                            <td class="numeric editable" id="sub_total_ccf" contenteditable="true"></td>
                        </tr>
                        <tr>
                            <td>(-) 1% Retención</td>
                            <td class="numeric editable"><span id="retencion_comision"></span></td>
                        </tr>
                        <tr>
                            <td>(=) Valor CCF Comisión</td>
                            <td class="numeric editable" id="comision_ccf" contenteditable="true"></td>
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
                            <td class="numeric total" contenteditable="true" id="liquido_pagar"
                                onblur="actualizarLiquidoPagar(this)">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
            </div>

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


                    @if($totalMontoOtorgado > 0)
                    <a class="btn btn-success" href="{{ url('deuda/exportar_excel') }}/{{ $deuda->Id }}">Exportar Cartera</a>
                    @endif
                    <a class="btn btn-default" data-target="#modal-cancelar" data-toggle="modal">Cancelar Cobro</a>
                    <a class="btn btn-primary" data-target="#modal-aplicar" data-toggle="modal">Generar Cobro</a>
                </div>

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



            <div class="modal fade" id="modal-reiniciar" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                <div class="modal-dialog">
                    <form action="{{ url('deuda/reiniciar_carga') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Reiniciar carga</h4>
                                <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                                <input type="hidden" name="Axo" value="{{ $ultimaCartera->Axo ?? '' }}">
                                <input type="hidden" name="Mes" value="{{ $ultimaCartera->Mes ?? '' }}">
                            </div>
                            <div class="modal-body">
                                <p>¿Esta seguro/a que desea reiniciar el proceso?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button class="btn btn-danger">Reiniciar</button>
                            </div>
                        </div>
                    </form>
                </div>
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



                    <form action="{{ url('polizas/deuda/agregar_pago') }}" method="POST">
                        @csrf

                        <div class="card mt-4 p-3 bg-light" style="display: none">
                            <h5>🔍 Depuración de valores (inputs ocultos visibles)</h5>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label>Fecha Inicio</label>
                                    <input type="text" class="form-control" id="FechaInicio" name="FechaInicio"
                                        value="{{ isset($ultimaCartera) ? $ultimaCartera->FechaInicio : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Final</label>
                                    <input type="text" class="form-control" id="FechaFinal" name="FechaFinal"
                                        value="{{ isset($ultimaCartera) ? $ultimaCartera->FechaFinal : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Mes</label>
                                    <input type="text" class="form-control"
                                        value="{{ isset($ultimaCartera->Mes) ? $ultimaCartera->Mes : '' }}"
                                        name="Mes">

                                </div>

                                <div class="col-md-3">
                                    <label>Año</label>
                                    <input type="text" class="form-control"
                                        value="{{ isset($ultimaCartera->Axo) ? $ultimaCartera->Axo : '' }}"
                                        name="Axo">

                                </div>
                                <div class="col-md-3">
                                    <label>Monto Cartera</label>
                                    <input type="text" class="form-control" id="MontoCarteraDetalle"
                                        name="MontoCartera">
                                </div>
                                <div class="col-md-3">
                                    <label>Deuda (ID)</label>
                                    <input type="text" class="form-control" id="Deuda" name="Deuda"
                                        value="{{ $deuda->Id }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Tasa</label>
                                    <input type="text" class="form-control" id="Tasa" name="Tasa"
                                        value="{{ $deuda->Tasa }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Prima Calculada</label>
                                    <input type="text" class="form-control" id="PrimaCalculadaDetalle"
                                        name="PrimaCalculada">
                                </div>
                                <div class="col-md-3">
                                    <label>Prima Descontada</label>
                                    <input type="text" class="form-control" id="PrimaDescontadaDetalle"
                                        name="PrimaDescontada">
                                </div>
                                <div class="col-md-3">
                                    <label>Sub Total</label>
                                    <input type="text" class="form-control" id="SubTotalDetalle" name="SubTotal">
                                </div>
                                <div class="col-md-3">
                                    <label>IVA</label>
                                    <input type="text" class="form-control" id="IvaDetalle" name="Iva">
                                </div>
                                <div class="col-md-3">
                                    <label>Tasa Comisión</label>
                                    <input type="text" class="form-control" id="TasaComision" name="TasaComision"
                                        value="{{ $deuda->TasaComision }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Comisión</label>
                                    <input type="text" class="form-control" id="ComisionDetalle" name="Comision">
                                </div>
                                <div class="col-md-3">
                                    <label>IVA Comisión</label>
                                    <input type="text" class="form-control" id="IvaComisionDetalle"
                                        name="IvaSobreComision">
                                </div>
                                <div class="col-md-3">
                                    <label>Descuento</label>
                                    <input type="text" class="form-control" id="DescuentoDetalle"
                                        name="Descuento">
                                </div>
                                <div class="col-md-3">
                                    <label>Retención</label>
                                    <input type="text" class="form-control" id="RetencionDetalle"
                                        name="Retencion">
                                </div>
                                <div class="col-md-3">
                                    <label>Valor CCF</label>
                                    <input type="text" class="form-control" id="ValorCCFDetalle" name="ValorCCF">
                                </div>
                                <div class="col-md-3">
                                    <label>A Pagar</label>
                                    <input type="text" class="form-control" id="APagarDetalle" name="APagar">
                                </div>
                                <div class="col-md-3">
                                    <label>Extra Prima</label>
                                    <input type="text" class="form-control" id="ExtraPrima" name="ExtraPrima"
                                        value="{{ $total_extrapima }}">
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
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






    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Calcula solo una vez al inicio
            sincronizarIniciales();
            inicializarEventosEditables();
        });

        // ========================
        // 🎯 Inicializar eventos
        // ========================
        function inicializarEventosEditables() {
            $(document).on('blur', '.editable', function(event) {
                actualizarCampoEditable(event);
            });
        }

        // ========================
        // ⚙️ Sincroniza sin recalcular
        // ========================
        function sincronizarIniciales() {
            const toNumber = v => parseFloat(String(v ?? '').replace(/,/g, '')) || 0;

            const totalPrima = toNumber(document.getElementById("total_prima_calculada")?.textContent);
            const subTotal = toNumber(document.getElementById("sub_total")?.textContent || totalPrima);
            const totalCartera = toNumber(document.getElementById("total_suma_asegurada")?.textContent);

            // Sincroniza hidden inputs
            document.getElementById("PrimaCalculadaDetalle").value = totalPrima;
            document.getElementById("SubTotalDetalle").value = subTotal;
            document.getElementById("MontoCarteraDetalle").value = totalCartera;

            // Cálculo inicial
            recalcularEstructuraCcf(totalPrima);

            document.getElementById("sub_total_extra_prima").textContent =
                Number(document.getElementById("ExtraPrima").value)
                .toLocaleString('es-SV', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

        }

        // ========================
        // ✏️ Cuando se edita una celda
        // ========================
        function actualizarCampoEditable(event) {
            const el = event.target;
            const id = el.id || "";

            // Usa textContent y también innerText como respaldo
            const rawText = el.textContent?.trim() || el.innerText?.trim() || "";
            const valorNuevo = convertirANumero(rawText);

            console.log("Evento blur → ID:", id, "Texto capturado:", rawText, "Número:", valorNuevo);

            if (isNaN(valorNuevo)) {
                el.style.backgroundColor = "#ffe0e0";
                console.warn(`⚠️ Valor inválido en ${id}`);
                return;
            }

            el.style.backgroundColor = "";
            el.textContent = formatearNumero(valorNuevo);

            if (id.startsWith("prima_calculada_")) {
                recalcularTotales();
                return;
            }

            if (id === "total_prima_calculada") {
                recalcularDesdeTotalPrima(valorNuevo);
                return;
            }

            if (id === "sub_total_ccf") {
                recalcularDesdeSubTotalCCF(valorNuevo);
                return;
            }

            if (id === "comision_ccf") {
                recalcularDesdeComisionCCF(valorNuevo);
                return;
            }

            if (id === "liquido_pagar") {
                console.log("🟢 Líquido a pagar modificado manualmente");
                const formatted = formatearNumero(valorNuevo);
                el.textContent = formatted;
                document.getElementById("APagarDetalle").value = valorNuevo;
                console.log(`Nuevo líquido a pagar: ${formatted}`);
                return;
            }
        }

        // ========================
        // 🔢 Recalcula total de primas cuando cambia una individual
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

            // Sincroniza valores
            document.getElementById("total_prima_calculada").textContent = fmt(totalPrima);
            document.getElementById("sub_total").textContent = fmt(totalPrima);
            document.getElementById("PrimaCalculadaDetalle").value = totalPrima;
            document.getElementById("SubTotalDetalle").value = totalPrima;

            // Recalcula estructura CCF
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

            // === Prima base ===
            document.getElementById("sub_total").textContent = fmt(sub_total);
            document.getElementById("PrimaCalculadaDetalle").value = sub_total;
            document.getElementById("SubTotalDetalle").value = sub_total;

            // --- Descuento ---
            let descuento = (sub_total + extra_prima) * (descuento_rentabilidad / 100);
            document.getElementById('descuento_rentabilidad').textContent = fmt(descuento);
            document.getElementById('DescuentoDetalle').value = descuento;

            // --- Prima a cobrar ---
            let prima_a_cobrar = (sub_total + extra_prima) - descuento;
            document.getElementById("prima_a_cobrar").textContent = fmt(prima_a_cobrar);
            document.getElementById("prima_a_cobrar_ccf").textContent = fmt(prima_a_cobrar);
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
            let retencion_comision = (tipo_contribuyente !== 1 && valor_comision >= 100) ?
                valor_comision * 0.01 : 0;
            document.getElementById('retencion_comision').textContent = fmt(retencion_comision);
            document.getElementById('RetencionDetalle').value = retencion_comision;

            // --- Comisión CCF ---
            let comision_ccf = sub_total_ccf - retencion_comision;
            document.getElementById('comision_ccf').textContent = fmt(comision_ccf);
            document.getElementById('comision').textContent = fmt(comision_ccf);
            document.getElementById('ValorCCFDetalle').value = comision_ccf;

            // --- Líquido ---
            let liquido = prima_a_cobrar - comision_ccf;
            document.getElementById('liquido_pagar').textContent = fmt(liquido);
            document.getElementById('APagarDetalle').value = liquido;

            // --- Monto total cartera ---
            let total_suma_asegurada = toNumber(document.getElementById('total_suma_asegurada')?.textContent);
            document.getElementById('monto_total_cartera').textContent = fmt(total_suma_asegurada);
            document.getElementById('MontoCarteraDetalle').value = total_suma_asegurada;
        }

        // ========================
        // ⚙️ Recalcular desde total_prima_calculada
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
            document.getElementById('comision').textContent = fmt(comision_ccf);
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
            document.getElementById('comision').textContent = fmt(comision_ccf);
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

    <script>
        function actualizarLiquidoPagar(el) {
            const valor = convertirANumero(el.innerText);
            if (isNaN(valor)) {
                el.style.backgroundColor = "#ffe0e0";
                console.warn("⚠️ Valor inválido en líquido a pagar");
                return;
            }

            el.style.backgroundColor = "";
            const formateado = formatearNumero(valor);
            el.textContent = formateado;
            document.getElementById("APagarDetalle").value = valor;

            console.log("🟢 Liquido a pagar actualizado manualmente:", valor);
        }
    </script>



</div>
