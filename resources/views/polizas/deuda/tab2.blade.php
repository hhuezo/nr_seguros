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
    <tr>
        <td>{{ $lineas->tipo }}</td>
        <td contenteditable="true">Editable</td>
        <td class="numeric editable" contenteditable="true">
            {{ $lineas->SaldoCapital ? number_format($lineas->SaldoCapital, 2, '.', ',') : '' }}
        </td>
        <td class="numeric editable" contenteditable="true">
            {{ $lineas->MontoNominal ? number_format($lineas->MontoNominal, 2, '.', ',') : '' }}
        </td>
        <td class="numeric editable" contenteditable="true">
            {{ $lineas->Intereses ? number_format($lineas->Intereses, 2, '.', ',') : '' }}
        </td>
        <td class="numeric editable" contenteditable="true">
            {{ $lineas->InteresesCovid ? number_format($lineas->InteresesCovid, 2, '.', ',') : '' }}
        </td>
        <td class="numeric editable" contenteditable="true">
            {{ $lineas->InteresesMoratorios ? number_format($lineas->InteresesMoratorios, 2, '.', ',') : '' }}
        </td>
        <td class="numeric total"> </td>
    </tr>
@endforeach

                        <tr>
                            <th colspan="2">Totales</th>
                            <td contenteditable="true">Editable</td>
                            <td contenteditable="true">Editable</td>
                            <td contenteditable="true">Editable</td>
                            <td contenteditable="true">Editable</td>
                            <td contenteditable="true">Editable</td>
                            <td contenteditable="true">Editable</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Calcula la suma de los valores de las columnas numéricas y muestra el resultado en la columna total
            $('.editable').on('input', function () {
                let sum = 0;
                $(this).closest('tr').find('.editable').each(function () {
                    const value = parseFloat($(this).text().replace(/[^0-9.-]+/g, ''));
                    if (!isNaN(value)) {
                        sum += value;
                    }
                });
                $(this).closest('tr').find('.total').text(sum.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            });
        });
    </script>
    
</div>
