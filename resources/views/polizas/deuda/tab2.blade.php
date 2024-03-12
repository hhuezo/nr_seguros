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

            <div class="container mt-5">
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
                                <td contenteditable="true">Editable</td>
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
                            <td><span id="total_saldo_capital"></span></td>
                            <td><span id="total_monto_nominal"></span></td>
                            <td><span id="total_interes"></span></td>
                            <td><span id="total_interes_covid"></span></td>
                            <td><span id="total_interes_moratorio"></span></td>
                            <td><span id="total_suma_asegurada"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>

    <script>
        $(document).ready(function() {
            let lineas = @json($lineas_abreviatura);

            console.log(lineas);
            calculoTotales();
            // Calcula la suma de los valores de las columnas numéricas y muestra el resultado en la columna total
            $('.editable').on('input', function() {
                let sum = 0;
                $(this).closest('tr').find('.editable').each(function() {
                    const value = parseFloat($(this).text().replace(/[^0-9.-]+/g, ''));
                    if (!isNaN(value)) {
                        sum += value;
                    }
                });
                $(this).closest('tr').find('.total').text(sum.toFixed(2).replace(/\d(?=(\d{3})+\.)/g,
                    '$&,'));
            });

            function calculoTotales() {
                for (let i = 0; i < lineas.length; i++) {
                    let linea = lineas[i];
                    let elemento = document.getElementById(linea + "_saldo_capital");

                    let saldo_capital = elemento.innerText || elemento.textContent;
                    console.log(linea + "_saldo_capital :", saldo_capital);

                    elemento = document.getElementById(linea + "_monto_nominal");
                    let monto_nominal = elemento.innerText || elemento.textContent;
                    console.log(linea + "_monto_nominal: ", monto_nominal);

                    elemento = document.getElementById(linea + "_interes");
                    let interes = elemento.innerText || elemento.textContent;
                    console.log(linea + "_interes: ", interes);

                    elemento = document.getElementById(linea + "_interes_covid");
                    let interes_covid = elemento.innerText || elemento.textContent;
                    console.log(linea + "_interes_covid: ", interes_covid);

                    elemento = document.getElementById(linea + "_interes_moratorio");
                    let interes_moratorio = elemento.innerText || elemento.textContent;
                    console.log(linea + "_interes_moratorio: ", interes_moratorio);

                    elemento = document.getElementById(linea + "_suma_asegurada");
                    let suma_asegurada = saldo_capital + monto_nominal + interes + interes_covid + interes_moratorio + interes_moratorio;


                }
            }
        });
    </script>

</div>
