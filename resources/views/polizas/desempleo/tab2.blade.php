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


    <ul class="nav navbar-right panel_toolbox">
        <a href="{{url('polizas/desempleo/subir_cartera')}}/{{$desempleo->Id}}" class="btn btn-info float-right">
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
                <table class="excel-like-table">
                    <thead>
                        <tr>
                            <th>Linea credito</th>
                            <th>Tasa Millar</th>
                            <th>Saldo capital</th>
                            <th>Intereses corrientes</th>
                            <th>Interes COVID</th>
                            <th>Intereses Moratorios</th>
                            <th>Monto nominal</th>
                            <th>Monto Cartera</th>
                            <th>Prima por pagar</th>
                        </tr>
                    </thead>

                    @foreach ($desempleo->desempleo_tipos_cartera as $tipo_cartera)
                        <tr>
                            <td contenteditable="true">
                                {{ $tipo_cartera->saldos_montos->Abreviatura ?? '' }} -
                                {{ $tipo_cartera->saldos_montos->Descripcion ?? '' }}
                            </td>
                            <td contenteditable="true" id="tasa_millar">
                                {{ $desempleo->Tasa }}
                            </td>


                        </tr>
                    @endforeach

                    <tr>
                        <td contenteditable="true" id="tasa_millar">
                            {{ $desempleo->Tasa }}
                        </td>



                        <td class="numeric editable" contenteditable="true" id="saldo_capital"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['saldoCapital'], 2, '.', ',') }}
                        </td>
                        <td class="numeric total" contenteditable="true" id="intereses" onblur="actualizarCalculos()">
                            {{ number_format($data['intereses'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="intereses_covid"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['interesesCovid'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="intereses_moratorios"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['interesesMoratorios'], 2, '.', ',') }}
                        </td>
                        <td class="numeric editable" contenteditable="true" id="monto_nominal"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['montoNominal'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="monto_cartera"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['total'], 2, '.', ',') }}
                        </td>

                        <td class="numeric editable" contenteditable="true" id="prima_por_pagar"
                            onblur="actualizarCalculos()">
                            {{ number_format($data['primaPorPagar'], 2, '.', ',') }}
                        </td>
                    </tr>


                    <tr>
                        <th>Totales</th>
                        <td class="numeric"><span
                                id="total_saldo_capital">{{ number_format($data['saldoCapital'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_interes">{{ number_format($data['intereses'], 2, '.', ',') }}</span></td>
                        <td class="numeric"><span
                                id="total_interes_covid">{{ number_format($data['interesesCovid'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_interes_moratorios">{{ number_format($data['interesesMoratorios'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_monto_nominal">{{ number_format($data['montoNominal'], 2, '.', ',') }}</span>
                        </td>
                        <td class="numeric"><span
                                id="total_monto_cartera">{{ number_format($data['total'], 2, '.', ',') }}</span></td>
                        <td class="numeric"><span
                                id="total_prima_por_pagar">{{ number_format($data['primaPorPagar'], 2, '.', ',') }}</span>
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
                                    id="prima_a_cobrar_ccf">{{ number_format($data['primaCobrar'], 2, '.', ',') }}</span>
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
                                    id="sub_total">{{ number_format($data['primaPorPagar'], 2, '.', ',') }}</span></td>
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
                    <input type="hidden" name="FechaInicio"
                        value="{{ isset($fechas) ? $fechas->FechaInicio : '' }}">
                    <input type="hidden" name="FechaFinal" value="{{ isset($fechas) ? $fechas->FechaFinal : '' }}">
                    <input type="hidden" name="MontoCartera" id="MontoCarteraDetalle"
                        value="{{ $data['total'] }}">
                    <input type="hidden" name="Desempleo" value="{{ $desempleo->Id }}">
                    <input type="hidden" name="Tasa" value="{{ $desempleo->Tasa }}">
                    <input type="hidden" name="PrimaCalculada" id="PrimaCalculadaDetalle"
                        value="{{ $data['primaPorPagar'] }}">
                    <input type="hidden" name="PrimaDescontada" id="PrimaDescontadaDetalle"
                        value="{{ $data['primaDescontada'] }}">
                    <!-- <input type="hidden" name="SubTotal" id="SubTotalDetalle" value="{{ $data['primaPorPagar'] }}" > -->
                    <!-- <input type="hidden" name="Iva" id="IvaDetalle"> -->
                    <input type="hidden" name="TasaComision" value="{{ $desempleo->TasaComision }}">
                    <input type="hidden" name="Comision" id="ComisionDetalle"
                        value="{{ $data['valorComision'] }}">
                    <input type="hidden" name="IvaSobreComision" id="IvaComisionDetalle"
                        value="{{ $data['ivaComision'] }}">
                    <input type="hidden" name="Descuento" id="DescuentoDetalle" value="{{ $data['descuento'] }}">
                    <input type="hidden" name="Retencion" id="RetencionDetalle"
                        value="{{ $data['retencionComision'] }}">
                    <input type="hidden" name="ValorCCF" id="ValorCCFDetalle" value="{{ $data['comisionCcf'] }}">
                    <input type="hidden" name="APagar" id="APagarDetalle" value="{{ $data['liquidoApagar'] }}">
                    <input type="hidden" name="ExtraPrima" value="{{ $data['extra_prima'] }}">
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
                    <form action="{{ url('polizas/residencia/cancelar_pago') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Cancelar Cobro</h4>

                                <input type="hidden" name="Residencia" value="{{ $desempleo->Id }}">
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
