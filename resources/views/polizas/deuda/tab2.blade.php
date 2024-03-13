<div>
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>


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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="box-body row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="excel-like-table">
                    <thead>
                        <tr>
                            <th>Lineas de crédito</th>
                            <th>Tasa interés</th>
                            <th>Saldo capital</th>
                            <th>Monto nominal</th>
                            <th>Intereses corrientes</th>
                            <th>Interes COVID</th>
                            <th>Intereses Moratorios</th>
                            <th>Suma asegurada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lineas_credito as $lineas)
                            @php($total = $lineas->SaldoCapital + $lineas->MontoNominal + $lineas->Intereses + $lineas->InteresesCovid + $lineas->InteresesMoratorios)
                            <tr>
                                <td>{{ $lineas->tipo }}</td>
                                <td>{{ $deuda->Tasa }} %</td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_saldo_capital">
                                    {{ $lineas->SaldoCapital ? number_format($lineas->SaldoCapital, 2, '.', ',') : '' }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_monto_nominal">
                                    {{ $lineas->MontoNominal ? number_format($lineas->MontoNominal, 2, '.', ',') : '' }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes">
                                    {{ $lineas->Intereses ? number_format($lineas->Intereses, 2, '.', ',') : '' }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes_covid">
                                    {{ $lineas->InteresesCovid ? number_format($lineas->InteresesCovid, 2, '.', ',') : '' }}
                                </td>
                                <td class="numeric editable" contenteditable="true"
                                    id="{{ $lineas->Abreviatura }}_interes_moratorio">
                                    {{ $lineas->InteresesMoratorios ? number_format($lineas->InteresesMoratorios, 2, '.', ',') : '' }}
                                </td>
                                <td class="numeric total" id="{{ $lineas->Abreviatura }}_suma_asegurada">
                                    {{ number_format($total, 2, '.', ',') }} </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th colspan="2">Totales</th>
                            <td class="numeric"><span id="total_saldo_capital"></span></td>
                            <td class="numeric"><span id="total_monto_nominal"></span></td>
                            <td class="numeric"><span id="total_interes"></span></td>
                            <td class="numeric"><span id="total_interes_covid"></span></td>
                            <td class="numeric"><span id="total_interes_moratorio"></span></td>
                            <td class="numeric"><span id="total_suma_asegurada"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">&nbsp;
                <input type="text" id="Tasa" value="{{ $deuda->Tasa }}">
                <input type="text" id="TasaComision" value="{{ $deuda->TasaComision }}">
                <input type="text" id="ExtraPrima" value="{{ $total_extrapima }}">

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
                            <td>Porcentaje de comisión</td>
                            <td class="numeric editable">{{ $deuda->Tasa }}</td>
                        </tr>
                        <tr>
                            <td>Comisión</td>
                            <td class="numeric editable">{{ $deuda->TasaComision }}</td>
                        </tr>
                        <tr>
                            <td>(+) 13% IVA</td>
                            <td class="numeric editable">0.00</td>
                        </tr>
                        <tr>
                            <td>(-) 1% Retención</td>
                            <td class="numeric editable">0.00</td>
                        </tr>
                        <tr>
                            <td>(=) Valor CCF Comisión</td>
                            <td class="numeric editable">0.00</td>
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
                            <td>Sub total</td>
                            <td class="numeric editable"><span id="sub_total"></span></td>
                        </tr>
                        <tr>
                            <td>Sub total Extra Prima</td>
                            <td class="numeric editable"><span id="sub_total_extra_prima"></span></td>
                        </tr>
                        <tr>
                            <td>Prima a cobrar</td>
                            <td class="numeric editable"><span id="prima_a_cobrar"></span></td>
                        </tr>
                        <tr>
                            <td>Comisión (10%)</td>
                            <td class="numeric editable"><span id="comision"></span></td>
                        </tr>
                        <tr>
                            <td>Liquido a pagar</td>
                            <td class="numeric editable"><span id="iva"></span></td>
                        </tr>
                    </tbody>
                </table>

            </div>


        </div>
    </div>

    <script>
        $(document).ready(function() {
            let lineas = @json($lineas_abreviatura);

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
                    // console.log(linea + "_monto_nominal: ", monto_nominal);

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
                    let suma_asegurada = convertirANumero(saldo_capital) + convertirANumero(monto_nominal) +
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
                    total_interes += convertirANumero(interes);
                    total_interes_covid += convertirANumero(interes_covid);
                    total_interes_moratorio += convertirANumero(interes_moratorio);
                    total_suma_asegurada += suma_asegurada;
                }

                let total_saldo_capital_formateada = formatearCantidad(total_saldo_capital);
                document.getElementById("total_saldo_capital").textContent = total_saldo_capital_formateada;

                let total_monto_nominal_formateada = formatearCantidad(total_monto_nominal);
                document.getElementById("total_monto_nominal").textContent = total_monto_nominal_formateada;

                let total_interes_formateada = formatearCantidad(total_interes);
                document.getElementById("total_interes").textContent = total_interes_formateada;

                let total_interes_covid_formateada = formatearCantidad(total_interes_covid);
                document.getElementById("total_interes_covid").textContent = total_interes_covid_formateada;

                let total_interes_moratorio_formateada = formatearCantidad(total_interes_moratorio);
                document.getElementById("total_interes_moratorio").textContent = total_interes_moratorio_formateada;

                let total_suma_asegurada_formateada = formatearCantidad(total_suma_asegurada);
                document.getElementById("total_suma_asegurada").textContent = total_suma_asegurada_formateada;


                let tasa = document.getElementById('Tasa').value;
                let tasa_comision = parseFloat(document.getElementById('TasaComision').value);
                let extra_prima = document.getElementById('ExtraPrima').value;

                //modificando valores de cuadros
                document.getElementById("monto_total_cartera").textContent = total_suma_asegurada_formateada;

                let sub_total = total_suma_asegurada * tasa;

                document.getElementById("sub_total").textContent = formatearCantidad(sub_total);

                document.getElementById("sub_total_extra_prima").textContent = formatearCantidad(extra_prima);


                prima_a_cobrar = parseFloat(sub_total) + parseFloat(extra_prima);

                document.getElementById("prima_a_cobrar").textContent = formatearCantidad(prima_a_cobrar);

                let comision = prima_a_cobrar * (tasa_comision / 100);

                document.getElementById("comision").textContent = formatearCantidad(comision);

                let iva = comision * 0.13;
                document.getElementById("iva").textContent = formatearCantidad(iva);

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

</div>
