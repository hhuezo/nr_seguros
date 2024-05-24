<div role="tabpanel" class="tab-pane fade {{ session('tab') == 2 ? 'active in' : '' }}" id="tab_content2" aria-labelledby="profile-tab">
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
        <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel
                            </h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="FormArchivo" action="{{ url('polizas/residencia/create_pago') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Axo" class="form-control">
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
                                    <select name="Mes" class="form-control">
                                        @for ($i = 1; $i < 12; $i++) @if (date('m')==$i) <option value="{{ $i }}" selected>
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
                                    <input class="form-control" name="Id" value="{{ $residencia->Id }}" type="hidden" required>
                                    <input class="form-control" type="date" name="FechaInicio" value="{{ $ultimo_pago ? date('Y-m-d', strtotime($ultimo_pago->FechaFinal)) : '' }}" {{ $ultimo_pago ? 'readonly' : '' }} required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    final</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="FechaFinal" value="{{ $ultimo_pago_fecha_final ? $ultimo_pago_fecha_final : '' }}" type="date" required>
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
                            <thead>
                                <tr>
                                    <th>Tasa Millar</th>
                                    <th>Monto Otorgado</th>
                                    <th>Prueba Decimales</th>
                                    <th>Prima Calculada</th>
                                    <th>Descuento Rentabilidad</th>
                                    <th>Prima Descontada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                use \Carbon\Carbon;
                                $monto_otorgado = $residencia->MontoOtorgado;
                                // Determinar la tasa por millar
                                if ($residencia->Aseguradora == 3) {
                                $tasa_millar = ($residencia->Tasa / 1000);
                                } else {
                                $tasa_millar = ($residencia->Tasa / 1000) / 12;
                                }

                                // Calcular los días entre las fechas de vigencia
                                $dias_axo = Carbon::parse($residencia->VigenciaDesde)->diffInDays(Carbon::parse($residencia->VigenciaHasta));

                                // Calcular los días entre las fechas especificadas, si existen
                                if (isset($fecha)) {
                                $dias_mes = Carbon::parse($fecha->FechaInicio)->diffInDays(Carbon::parse($fecha->FechaFinal));
                                } else {
                                $dias_mes = 1;
                                }

                                // Calcular los decimales dependiendo si la aseguradora tiene la opción Diario activa
                                if ($residencia->aseguradoras->Diario == 1) {
                                $decimales = (($residencia->MontoOtorgado * $tasa_millar) / $dias_axo) * $dias_mes;
                                } else {
                                $decimales = ($residencia->MontoOtorgado * $tasa_millar);
                                }

                                // Formatear el monto otorgado
                                $prima_calculada = number_format($residencia->MontoOtorgado, 2, '.', ',');

                                if($residencia->TasaDescuento < 0){ $descuento=number_format($residencia->TasaDescuento * $prima_calculada,2,'.',',');
                                    }else{
                                    $descuento = number_format(($residencia->TasaDescuento / 100) * $prima_calculada,2,'.',',');
                                    }
                                    $prima_descontada = number_format($prima_calculada - $descuento,2,'.',',');
                                    @endphp

                                    <tr>
                                        <td class="numeric editable" contenteditable="true" id="tasa_millar">
                                            {{ $tasa_millar != 0 ? number_format($tasa_millar, 2, '.', ',') : 0 }}
                                        </td>
                                        <td class="numeric editable" contenteditable="true" id="monto_otorgado">
                                            {{ $monto_otorgado != 0 ? number_format($monto_otorgado, 2, '.', ',') : 0 }}
                                        </td>
                                        <td class="numeric editable" contenteditable="true" id="saldo_capital">
                                            {{ $decimales != 0 ? number_format($decimales, 2, '.', ',') : 0 }}
                                        </td>
                                        <td class="numeric editable" contenteditable="true" id="prima_calculada">
                                            {{ $prima_calculada != 0 ? number_format($prima_calculada, 2, '.', ',') : 0 }}
                                        </td>
                                        <td class="numeric editable" contenteditable="true" id="descuento">
                                            {{ $descuento != 0 ? number_format($descuento, 2, '.', ',') : 0 }}
                                        </td>
                                        <td class="numeric total" contenteditable="true" id="prima_descontada">
                                            {{ $prima_descontada != 0 ? number_format($prima_descontada, 2, '.', ',') : 0 }}
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
                                    <td>Comisión {{$residencia->ComisionIva == 1 ? 'Iva Incluido': ''}} </td>
                                    <td class="numeric editable"><span>{{$residencia->ComisionIva == 1 ? number_format($residencia->TasaComision / 1.13,2,".",",") : $residencia->TasaComision}}%</span></td>
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
                                    <td>(-) Descuento rentabilidad ({{$residencia->Descuento == '' ? 0 : $residencia->Descuento}}%)</td>
                                    <td class="numeric editable"><span id="descuento_rentabilidad"></span></td>
                                </tr>
                                <tr>
                                    <td>(=) Prima descontada</td>
                                    <td class="numeric editable"><span id="prima_descontada"></span></td>
                                </tr>
                                <tr>
                                    <td>(+) Impuesto Bomberos</td>
                                    <td class="numeric "><span id="impuesto_bomberos"></span></td>
                                </tr>
                                <tr>
                                    <td>Gastos Emision</td>
                                    <td class="numeric editable"><span id="gastos_emision"></span></td>
                                </tr>
                                <tr>
                                    <td>Otros</td>
                                    <td class="numeric editable"><span id="otros"></span></td>
                                </tr>
                                <tr>
                                    <td>Sub Total</td>
                                    <td class="numeric editable"><span id="sob_total"></span></td>
                                </tr>
                               <tr>
                                    <td>13% Iva</td>
                                    <td class="numeric editable"><span id="iva"></span></td>
                                </tr>
                        <!--  <tr>
                            <td>Total Factura</td>
                            <td class="numeric editable"><span id="total_factura"></span></td>
                        </tr> -->
                                <tr>
                                    <td>(-) Estructura CCF de Comisión (10%)</td>
                                    <td class="numeric editable"><span id="comision"></span></td>
                                </tr>
                                <tr>
                                    <td>Total a pagar</td>
                                    <td class="numeric editable"><span id="liquido_pagar"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br><br>
                    </div>

                    <div>
                        <form action="{{ url('polizas/residencia/agregar_pago') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ExcelURL" id="ExcelURL" value="{{ session('ExcelURL') }}" class="form-control">
                            <input type="hidden" name="Residencia" id="Residencia" value="{{ $residencia->Id }}" class="form-control">
                            <input type="hidden" name="Tasa" value="{{ $residencia->Tasa }}">
                            <input type="hidden" name="FechaInicio" value="{{ isset($fecha) ? $fecha->FechaInicio : '' }}">
                            <input type="hidden" name="FechaFinal" value="{{ isset($fecha) ? $fecha->FechaFinal : '' }}">




                            <input type="hidden" name="MontoCartera" id="MontoCarteraDetalle">                   
                            <input type="hidden" name="PrimaCalculada" id="PrimaCalculadaDetalle">
                            <input type="hidden" name="PrimaDescontada" id="PrimaDescontadaDetalle">
                            <input type="hidden" name="SubTotal" id="SubTotalDetalle">
                            <input type="hidden" name="Iva" id="IvaDetalle">
                            <input type="hidden" name="TasaComision" value="{{ $residencia->TasaComision }}">
                            <input type="hidden" name="Comision" id="ComisionDetalle">
                            <input type="hidden" name="IvaSobreComision" id="IvaComisionDetalle">
                            <input type="hidden" name="Retencion" id="RetencionDetalle">
                            <input type="hidden" name="ValorCCF" id="ValorCCFDetalle">
                            <input type="hidden" name="APagar" id="APagarDetalle">
                            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-aplicar">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <h4 class="modal-title">Aplicación de cobro</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Esta seguro/a que desea aplicar el cobro?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                            <button id="boton_pago" class="btn btn-primary">Confirmar
                                                Cobro</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>

                            <div align="center">
                                <br><br><br>
                                <a class="btn btn-default" data-target="#modal-cancelar" data-toggle="modal" onclick="cancelarpago()">Cancelar Cobro</a>
                                <a class="btn btn-primary" data-target="#modal-aplicar" data-toggle="modal" onclick="aplicarpago()">Generar Cobro</a>
                            </div>

                        </form>
                    </div>

                    <div class="modal fade" id="modal-cancelar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
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

                                        <input type="hidden" name="Residencia" value="{{ $residencia->Id }}">
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
        </div>
    </div>

</div>


<script>
        $(document).ready(function() {
            

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
                    let suma_asegurada = convertirANumero(saldo_capital) + convertirANumero(monto_nominal) + convertirANumero(monto_otorgado) +
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
                console.log(tasa_comision);
                let extra_prima = document.getElementById('ExtraPrima').value;

                //modificando valores de cuadros
                document.getElementById("monto_total_cartera").textContent = total_suma_asegurada_formateada;
                document.getElementById('MontoCarteraDetalle').value = total_suma_asegurada;
                document.getElementById('PrimaCalculadaDetalle').value = parseFloat(
                    total_suma_asegurada) * parseFloat(tasa);
                document.getElementById('PrimaDescontadaDetalle').value = parseFloat(
                    total_suma_asegurada) * parseFloat(tasa);


                let sub_total = total_suma_asegurada * tasa;

                document.getElementById("sub_total").textContent = formatearCantidad(sub_total);
                document.getElementById('SubTotalDetalle').value = sub_total;
                document.getElementById("sub_total_extra_prima").textContent = formatearCantidad(extra_prima);



                let descuento = (parseFloat(sub_total) + parseFloat(extra_prima)) * parseFloat(parseFloat(document.getElementById('DescuentoRentabilidad').value) / 100);
                document.getElementById('descuento_rentabilidad').textContent = formatearCantidad(descuento);
                prima_a_cobrar = (parseFloat(sub_total) + parseFloat(extra_prima)) - parseFloat(descuento);
                document.getElementById("prima_a_cobrar").textContent = formatearCantidad(prima_a_cobrar);
                document.getElementById("prima_a_cobrar_ccf").textContent = formatearCantidad(prima_a_cobrar);
                let iva = parseFloat(prima_a_cobrar) * 0.13;
                // document.getElementById('iva').textContent = formatearCantidad(iva);
                document.getElementById('IvaDetalle').value = iva;
                let total_factura = parseFloat(iva) + parseFloat(prima_a_cobrar);
                // document.getElementById('total_factura').textContent = formatearCantidad(total_factura);

                // let comision = prima_a_cobrar * (tasa_comision / 100);

                // document.getElementById("comision").textContent = formatearCantidad(comision);

                //estructura ccf

                let valor_comision = parseFloat(prima_a_cobrar) * (parseFloat(tasa_comision) / 100);
                document.getElementById('valor_comision').textContent = formatearCantidad(valor_comision);
                console.log(valor_comision);
                document.getElementById('ComisionDetalle').value = valor_comision;
                let iva_comision = parseFloat(valor_comision) * 0.13;
                document.getElementById('iva_comision').textContent = formatearCantidad(iva_comision);
                document.getElementById('IvaComisionDetalle').value = iva_comision;
                let retencion_comision = parseFloat(valor_comision) * 0.01;
                let sub_total_ccf = parseFloat(valor_comision) + parseFloat(iva_comision);
                document.getElementById('sub_total_ccf').textContent = formatearCantidad(sub_total_ccf);

                document.getElementById('retencion_comision').textContent = formatearCantidad(
                    retencion_comision);
                let comision_ccf = parseFloat(sub_total_ccf) - parseFloat(
                    retencion_comision);
                document.getElementById('comision_ccf').textContent = formatearCantidad(comision_ccf);
                document.getElementById('comision').textContent = formatearCantidad(comision_ccf);
                let liquido_pagar = parseFloat(prima_a_cobrar) - parseFloat(comision_ccf);
                document.getElementById("liquido_pagar").textContent = formatearCantidad(liquido_pagar);
                document.getElementById('RetencionDetalle').value = retencion_comision;
                document.getElementById('ValorCCFDetalle').value = comision_ccf;
                document.getElementById('APagarDetalle').value = liquido_pagar;



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
    </script>