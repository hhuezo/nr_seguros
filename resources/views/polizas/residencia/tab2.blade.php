<div role="tabpanel" class="tab-pane fade {{ ($tab ?? session('tab')) == 2 ? 'active in' : '' }}" id="tab_content2"
    aria-labelledby="profile-tab">
    @php
        ini_set('max_execution_time', 30000);
        set_time_limit(30000);
    @endphp
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
    <div class="x_title">

        <ul class="nav navbar-right panel_toolbox">
            <div class="btn btn-info float-right" data-toggle="modal" data-target="#modal_pago">
                Subir Archivo Excel</div>
        </ul>
        <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel--
                            </h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="FormArchivo" action="{{ url('polizas/residencia/create_pago') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Axo" id="Axo" class="form-control">
                                        @for ($i = date('Y'); $i >= 2022; $i--)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Mes" id="Mes" class="form-control">
                                        @for ($i = 1; $i <= 12; $i++)
                                            @if (date('m') == $i)
                                                <option value="{{ $i }}" selected>
                                                    {{ $meses[$i] }}
                                                </option>
                                            @else
                                                <option value="{{ $i }}">
                                                    {{ $meses[$i] }}
                                                </option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    inicio</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Id" value="{{ $residencia->Id }}"
                                        type="hidden" required>
                                    <input class="form-control" type="date" name="FechaInicio" id="FechaInicio"
                                        value="{{ $ultimo_pago && $ultimo_pago != null ? date('Y-m-d', strtotime($ultimo_pago->FechaFinal)) : '' }}"
                                        required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    final</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="FechaFinal" id="FechaFinal"
                                        value="{{ $ultimo_pago_fecha_final && $ultimo_pago_fecha_final != null ? $ultimo_pago_fecha_final : '' }}"
                                        type="date" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Archivo" type="file" required>
                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Subir Cartera</button>
                        </div>
                    </form>

                    <div id="loading-indicator" style="text-align: center; display:none">
                        <img src="{{ asset('img/ajax-loader.gif') }}">
                        <br>
                    </div>


                </div>
            </div>
        </div>
        <div>

            <br>

            <div class="modal-body">
                <div class="box-body row">
                    <br>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="excel-like-table">
                            <tr>
                                <td>Fecha Inicio:
                                    {{ $fechas != null ? date('d/m/Y', strtotime($fechas->FechaInicio)) : '' }}</td>
                                <td>Fecha Final:
                                    {{ $fechas != null ? date('d/m/Y', strtotime($fechas->FechaFinal)) : '' }}</td>
                                <td>Mes: {{ $fechas != null ? $meses[$fechas->Mes] : '' }}</td>
                            </tr>
                        </table>
                        <br>
                        <table class="excel-like-table">
                            <thead>
                                <tr>
                                    <th>Tasa Millar</th>
                                    <th>Monto Cartera</th>
                                    <th>Prueba Decimales</th>
                                    <th>Prima Calculada</th>
                                    <th>Prima Mensual</th>
                                    <th>Descuento Rentabilidad
                                        {{ $residencia->TasaDescuento ? $residencia->TasaDescuento : '0' }} %</th>
                                    <th>Prima Descontada</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    use Carbon\Carbon;
                                    $monto_cartera = session('MontoCartera', 0);
                                    // $monto_cartera = 3844478.89;
                                    // Determinar la tasa por millar
                                    if ($residencia->Aseguradora == 3) {
                                        $tasa_millar = number_format($residencia->Tasa / 1000, 6, '.', ',');
                                    } else {
                                        $tasa_millar = number_format($residencia->Tasa / 1000 / 12, 6, '.', ',');
                                    }

                                    // Calcular los días entre las fechas de vigencia
                                    $dias_axo = Carbon::parse($residencia->VigenciaDesde)->diffInDays(
                                        Carbon::parse($residencia->VigenciaHasta),
                                    );

                                    // Calcular los días entre las fechas especificadas, si existen
                                    if (isset($fechas)) {
                                        $dias_mes = Carbon::parse($fechas->FechaInicio)->diffInDays(
                                            Carbon::parse($fechas->FechaFinal),
                                        );
                                    } else {
                                        $dias_mes = 31;
                                    }
                                    $decimales = $monto_cartera * $tasa_millar;
                                    // Calcular los decimales dependiendo si la aseguradora tiene la opción Diario activa
                                    $prima_mensual = 0;
                                    if ($residencia->aseguradoras->Diario == 1) {
                                        $prima_mensual = ($decimales / $dias_axo) * $dias_mes;
                                    }

                                    // Formatear el monto otorgado
                                    $prima_calculada = $decimales;

                                    if ($residencia->TasaDescuento < 0) {
                                        $descuento = $residencia->TasaDescuento * $prima_calculada;
                                    } else {
                                        $descuento = ($residencia->TasaDescuento / 100) * $prima_calculada;
                                    }
                                    $total_prima_descontada = $prima_calculada - $descuento;
                                @endphp

                                <tr>
                                    <td contenteditable="true" id="tasa_millar">
                                        {{ $tasa_millar != 0 ? $tasa_millar : 0 }}
                                    </td>
                                    <td class="numeric editable" contenteditable="true" id="monto_cartera"
                                        onblur="actualizarCalculos()">
                                        {{ $monto_cartera != 0 ? number_format($monto_cartera, 2, '.', ',') : 0 }}
                                    </td>
                                    <td class="numeric editable" contenteditable="false" id="prueba_decimales">
                                        {{ $decimales != 0 ? $decimales : 0 }}
                                    </td>
                                    <td class="numeric editable" contenteditable="true" id="prima_calculada"
                                        onblur="actualizarCalculos()">
                                        {{ $prima_calculada != 0 ? number_format($prima_calculada, 2, '.', ',') : 0 }}
                                    </td>
                                    <td class="numeric editable" contenteditable="true" id="prima_mensual"
                                        onblur="actualizarCalculos()">
                                        {{ $prima_mensual != 0 ? number_format($prima_mensual, 2, '.', ',') : 0 }}
                                    </td>
                                    <td class="numeric editable" contenteditable="true" id="descuento"
                                        onblur="actualizarCalculos()">
                                        {{ $descuento != 0 ? number_format($descuento, 2, '.', ',') : 0 }}
                                    </td>
                                    <td class="numeric total" contenteditable="true" id="prima_descontada"
                                        onblur="actualizarCalculos()">
                                        {{ $total_prima_descontada != 0 ? number_format($total_prima_descontada, 2, '.', ',') : 0 }}
                                    </td>
                                </tr>


                                <!-- <tr>
                                        <th>Totales</th>
                                        <td class="numeric"><span id="total_monto_otorgado"></span></td>
                                        <td class="numeric"><span id="total_saldo_capital"></span></td>
                                        <td class="numeric"><span id="total_interes"></span></td>
                                        <td class="numeric"><span id="total_interes_covid"></span></td>
                                        <td class="numeric"><span id="total_suma_asegurada"></span></td>
                                    </tr> -->
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
                                    @if ($residencia->ComisionIva == 1)
                                        @php($var = $residencia->Comision / 1.13)
                                    @else
                                        @php($var = $residencia->Comision)
                                    @endif
                                    <td>Porcentaje de Comisión
                                        {{ $residencia->ComisionIva == 1 ? 'Iva Incluido' : '' }}
                                    </td>
                                    <td class="numeric editable">
                                        <span>{{ $residencia->Comision ? number_format($var, 2, '.', ',') : '' }}</span>
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
                                    <td>Prima Descontada</td>
                                    <td class="numeric editable"><span id="total_prima_descontada"></span></td>
                                </tr>

                                <td>(+) Impuesto Bomberos</td>
                                <td class="numeric "><span id="impuestos_bomberos"></span></td>
                                </tr>
                                <tr>
                                    <td>Gastos Emisión</td>
                                    <td class="numeric editable" contenteditable="true"><span id="gastos_emision"
                                            onblur="actualizarCalculos()"></span></td>
                                </tr>
                                <tr>
                                    <td>Otros</td>
                                    <td class="numeric editable" contenteditable="true"><span id="otros"
                                            onblur="actualizarCalculos()"></span></td>
                                </tr>
                                <tr>
                                    <td>Sub Total</td>
                                    <td id="sub_total" class="numeric editable" contenteditable="true" onblur="actualizarCalculos()"></td>
                                </tr>
                                <tr>
                                    <td>13% Iva</td>
                                    <td class="numeric total" contenteditable="true" id="iva"
                                        onblur="actualizarIva()"></td>
                                </tr>
                                <!--  <tr>
                            <td>Total Factura</td>
                            <td class="numeric editable"><span id="total_factura"></span></td>
                        </tr> -->
                                <tr>
                                    <td>(-) Estructura CCF de Comisión
                                        ({{ $residencia->Comision ? number_format($var, 2, '.', ',') : '' }}%)</td>
                                    <td class="numeric editable"><span id="comision"></span></td>
                                </tr>
                                <tr>
                                    <td>A pagar</td>
                                    <td class="numeric total" contenteditable="true" id="liquido_pagar"
                                        onblur="actualizarTotal()"></td>
                                </tr>
                                <tr>
                                    <td>Total factura</td>
                                    <td class="numeric editable"><span id="total_factura"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br><br>
                    </div>

                    <div>
                        <form action="{{ url('polizas/residencia/agregar_pago') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ExcelURL" id="ExcelURL" value="{{ session('ExcelURL') }}"
                                class="form-control">
                            <input type="hidden" name="Residencia" id="Residencia" value="{{ $residencia->Id }}"
                                class="form-control">
                            <input type="hidden" name="Tasa" value="{{ $residencia->Tasa }}">
                            <input type="hidden" name="FechaInicio"
                                value="{{ isset($fecha) ? $fecha->FechaInicio : '' }}">
                            <input type="hidden" name="FechaFinal"
                                value="{{ isset($fecha) ? $fecha->FechaFinal : '' }}">
                            <input type="hidden" name="MontoCartera" id="MontoCarteraDetalle">
                            <input type="hidden" name="PrimaCalculada" id="PrimaCalculadaDetalle">
                            <input type="hidden" name="PrimaDescontada" id="PrimaDescontadaDetalle">
                            <input type="hidden" name="Iva" id="IvaDetalle">
                            <input type="hidden" name="SubTotal" id="SubTotalDetalle">
                            <input type="hidden" name="TasaComision" value="{{ $residencia->TasaComision }}">
                            <input type="hidden" name="Comision" id="ComisionDetalle">
                            <input type="hidden" name="IvaSobreComision" id="IvaComisionDetalle">
                            <input type="hidden" name="Retencion" id="RetencionDetalle">
                            <input type="hidden" name="ValorCCF" id="ValorCCFDetalle">
                            <input type="hidden" name="APagar" id="APagarDetalle">
                            <input type="hidden" name="Descuento" id="DescuentoDetalle">
                            <input type="hidden" name="ImpuestoBomberos" value="{{ $bomberos }}">
                            <input type="hidden" name="GastosEmision" id="GastosEmisionDetalle">
                            <input type="hidden" name="Otros" id="OtrosDetalle">
                            <input type="hidden" name="PrimaTotal" id="PrimaTotalDetalle">
                            <input type="hidden" name="ExtraPrima" value="0">


                            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"
                                tabindex="-1" id="modal-aplicar">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <h4 class="modal-title">Aviso de cobro</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Desea generar el aviso de cobro</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Cerrar</button>
                                            <button id="boton_pago" class="btn btn-primary">Generar aviso de
                                                cobro</button>
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
                            <form action="{{ url('polizas/residencia/cancelar_pago') }}" method="POST">
                                @method('POST')
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">Cancelar Cobro</h4>

                                        <input type="hidden" name="Residencia" value="{{ $residencia->Id }}">
                                        <input type="hidden" name="MesCancelar"
                                            value="{{ isset($fecha) ? $fecha->Mes : '' }}">
                                        <input type="hidden" name="AxoCancelar"
                                            value="{{ isset($fecha) ? $fecha->Axo : '' }}">
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Esta seguro/a que desea cancelar el cobro?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Cerrar</button>
                                        <button class="btn btn-danger">Cancelar
                                            Cobro</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    function actualizarCalculos() {
        //alert(document.getElementById('monto_cartera').innerText);
        let monto = convertirANumero(document.getElementById('monto_cartera').innerText);
        console.log(monto);
        let aseguradora = {{ $residencia->Aseguradora }};
        let tasa = {{ $residencia->Tasa }};
        let millar = 0;
        let dias_mes = {{ $dias_mes }};
        let decimales = 0;
        let diario = {{ $residencia->aseguradoras->Diario ? 1 : 0 }};
        let dias_axo =
            {{ $residencia->aseguradoras->Diario == 1 && $residencia->aseguradoras->Dias365 == 1 ? 365 : $dias_axo }};
        let descuento = 0;
        let tasadescuento = {{ $residencia->TasaDescuento }};
        let sub_total = 0;
        let bomberos = {{ $bomberos }};
        let gastos = document.getElementById('gastos_emision').innerText;
        let otros = document.getElementById('otros').innerText;
        let iva = 0;
        let ccf = 0;
        let comision_iva = {{ $residencia->ComisionIva ?? 0 }};
        let total = 0;
        let tasa_comision = 0;
        let var_com = {{ $residencia->Comision }};
        let prima_mensual = 0;
        if (comision_iva == 1) {
            tasa_comision = var_com / 1.13;
        } else {
            tasa_comision = var_com;

        }
        let tipo_contribuyente = {{ $residencia->clientes->TipoContribuyente ?? 0 }};
        console.log("tipo_contribuyente ", tipo_contribuyente);
        if (aseguradora == 3) {
            //fede
            millar = tasa / 1000;
        } else {
            //sisa
            millar = (tasa / 1000) / 12;
        }
        //dias_mes = 31;
        decimales = (monto * millar);
        let prima_descontada = 0;
        if (diario == 1) {
            prima_mensual = (parseFloat(decimales) / parseFloat(dias_axo)) * parseFloat(dias_mes);
            if (tasadescuento < 0) {
                descuento = tasadescuento * prima_mensual;
            } else {
                descuento = (tasadescuento / 100) * prima_mensual;
            }
            prima_descontada = prima_mensual - descuento;
        } else {
            if (tasadescuento < 0) {
                descuento = tasadescuento * decimales;
            } else {
                descuento = (tasadescuento / 100) * decimales;
            }
            prima_descontada = decimales - descuento;

        }



        document.getElementById('prueba_decimales').innerText = parseFloat(decimales);
        document.getElementById('prima_calculada').innerText = formatearCantidad(decimales);
        document.getElementById('prima_mensual').innerText = formatearCantidad(prima_mensual);
        document.getElementById('prima_descontada').innerText = formatearCantidad(prima_descontada);
        document.getElementById('descuento').innerText = formatearCantidad(descuento);
        document.getElementById('total_prima_descontada').innerText = formatearCantidad(prima_descontada);

        //funcion para los calculos totales


        document.getElementById('impuestos_bomberos').innerText = formatearCantidad(bomberos);

        if (gastos == 0) {
            document.getElementById('gastos_emision').innerText = formatearCantidad(0);

        } else {
            document.getElementById('gastos_emision').innerText = formatearCantidad(gastos);

        }
        if (otros == 0) {
            document.getElementById('otros').innerText = formatearCantidad(0);

        } else {
            document.getElementById('otros').innerText = formatearCantidad(otros);

        }
        gastos = document.getElementById('gastos_emision').innerText;
        otros = document.getElementById('otros').innerText;

        sub_total = (parseFloat(prima_descontada) + parseFloat(bomberos) + parseFloat(gastos) + parseFloat(otros));

        document.getElementById('sub_total').innerText = formatearCantidad(sub_total);
        let iva_form = 0;
        if (tipo_contribuyente != 4) {
            iva_form = 0.13;
        } else {
            iva_form = 0;
        }
        iva = parseFloat(sub_total) * parseFloat(iva_form);

        document.getElementById('iva').innerText = formatearCantidad(iva);


        //calculo ccf
        let prima_cobrar = sub_total;
        document.getElementById('prima_a_cobrar_ccf').textContent = formatearCantidad(sub_total);
        let valor_comision = (parseFloat(tasa_comision) / 100) * parseFloat(prima_cobrar);
        document.getElementById('valor_comision').textContent = formatearCantidad(valor_comision);
        let iva_comision = 0;
        //el cliente no contribuyente, no paga iva

        if (tipo_contribuyente != 4) {
            iva_comision = (parseFloat(valor_comision) * 0.13);
        } else {
            iva_comision = 0;
        }
        document.getElementById('iva_comision').textContent = formatearCantidad(iva_comision);
        let sub_total_ccf = (parseFloat(iva_comision) + parseFloat(valor_comision));
        document.getElementById('sub_total_ccf').textContent = formatearCantidad(sub_total_ccf);
        let comision = 0;
        let retencion = 0;
        if (tipo_contribuyente == 1) {
            retencion = (parseFloat(valor_comision) * 0.01);
        }

        document.getElementById('retencion_comision').textContent = formatearCantidad(retencion);
        let comision_ccf = parseFloat(sub_total_ccf) - parseFloat(retencion);
        document.getElementById('comision_ccf').textContent = formatearCantidad(comision_ccf);
        document.getElementById('comision').textContent = formatearCantidad(comision_ccf);

        console.log();
        let liquido_pagar = (parseFloat(sub_total) + parseFloat(iva) - parseFloat(comision_ccf));
        document.getElementById('liquido_pagar').textContent = formatearCantidad(liquido_pagar);
        let total_factura = (parseFloat(sub_total) + parseFloat(iva));
        document.getElementById('total_factura').textContent = formatearCantidad(total_factura);

        //llenado de form
        document.getElementById('MontoCarteraDetalle').value = parseFloat(monto);
        document.getElementById('PrimaCalculadaDetalle').value = parseFloat(decimales);
        document.getElementById('PrimaDescontadaDetalle').value = parseFloat(prima_descontada);
        document.getElementById('IvaDetalle').value = parseFloat(iva);
        document.getElementById('SubTotalDetalle').value = parseFloat(sub_total);
        document.getElementById('ComisionDetalle').value = parseFloat(valor_comision);
        document.getElementById('IvaComisionDetalle').value = parseFloat(iva_comision);
        document.getElementById('RetencionDetalle').value = parseFloat(retencion);
        document.getElementById('ValorCCFDetalle').value = parseFloat(comision_ccf);
        document.getElementById('APagarDetalle').value = parseFloat(liquido_pagar);
        document.getElementById('DescuentoDetalle').value = parseFloat(descuento);
        document.getElementById('GastosEmisionDetalle').value = parseFloat(gastos);
        document.getElementById('OtrosDetalle').value = parseFloat(otros);
        document.getElementById('PrimaTotalDetalle').value = parseFloat(prima_descontada);

    }

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

    function actualizarIva() {
        let iva = document.getElementById('iva').innerText;
        let sub_total = document.getElementById('SubTotalDetalle').value;
        let comision_ccf = document.getElementById('comision').innerText;
        let liquido_pagar = (parseFloat(sub_total) + parseFloat(iva) - parseFloat(comision_ccf));
        document.getElementById('liquido_pagar').textContent = formatearCantidad(liquido_pagar);

        let total_factura = (parseFloat(sub_total) + parseFloat(iva));
        document.getElementById('total_factura').textContent = formatearCantidad(total_factura);
        document.getElementById('ValorCCFDetalle').value = parseFloat(comision_ccf);
        document.getElementById('APagarDetalle').value = parseFloat(liquido_pagar);
        document.getElementById('IvaDetalle').value = parseFloat(iva);
        document.getElementById('SubTotalDetalle').value = parseFloat(sub_total);

    }

    function actualizarTotal() {
        let iva = document.getElementById('IvaDetalle').value;
        let sub_total = document.getElementById('SubTotalDetalle').value;
        let liquido_pagar = document.getElementById('liquido_pagar').innerText;

        let total_factura = (parseFloat(sub_total) + parseFloat(iva));
        document.getElementById('total_factura').textContent = formatearCantidad(total_factura);

        // quitar comas y luego convertir
        let numero = parseFloat(liquido_pagar.replace(/,/g, ''));

        document.getElementById('APagarDetalle').value = numero;


    }

    $(document).ready(function() {


        //console.log(lineas);
        actualizarCalculos();
        // Calcula la suma de los valores de las columnas numéricas y muestra el resultado en la columna total
        $('.editable').on('input', function() {
            calculoTotales();
            actualizarCalculos();
        });



        function calculoTotales() {

            let prima_descontada = convertirANumero(document.getElementById('prima_descontada').innerText);
            let total_prima_descontada = formatearCantidad(prima_descontada);
            document.getElementById('total_prima_descontada').innerText = total_prima_descontada;


            //   console.log(comision);
        }


        // para fechas
        // Obtener referencias a los elementos del formulario
        const añoSelect = document.getElementById('Axo');
        const mesSelect = document.getElementById('Mes');
        const fechaInicioInput = document.getElementById('FechaInicio');
        const fechaFinalInput = document.getElementById('FechaFinal');

        // Función para actualizar las fechas
        function actualizarFechas() {
            // Obtener el año y mes seleccionados
            const año = añoSelect.value;
            const mes = mesSelect.value;

            // Validar que ambos campos tengan valores
            if (año && mes) {
                // Formatear el primer día del mes seleccionado
                const primerDiaMes = `${año}-${mes.padStart(2, '0')}-01`;
                fechaInicioInput.value = primerDiaMes;

                // Calcular el primer día del mes siguiente
                const fecha = new Date(año, mes - 1, 1); // Mes en JavaScript es 0-indexado
                fecha.setMonth(fecha.getMonth() + 1); // Sumar un mes
                const primerDiaMesSiguiente = fecha.toISOString().split('T')[0];
                fechaFinalInput.value = primerDiaMesSiguiente;
            }
        }

        // Asignar la función al evento onchange de los selectores
        añoSelect.addEventListener('change', actualizarFechas);
        mesSelect.addEventListener('change', actualizarFechas);

    });
</script>
