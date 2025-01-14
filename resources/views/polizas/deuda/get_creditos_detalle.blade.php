<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Fecha otorgamiento</th>
            <th>Monto otorgado</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php($total = 0)
        @foreach ($data as $obj)
            <tr>
                <td>{{ $obj->NumeroReferencia }}</td>
                <td>
                    {{ $obj->PrimerApellido }}
                    {{ $obj->SegundoApellido }}
                    {{ $obj->ApellidoCasada }}
                    {{ $obj->PrimerNombre }}
                    {{ $obj->SegundoNombre }}
                </td>
                <td>{{ $obj->FechaOtorgamiento }}</td>
                <td>  ${{ number_format($obj->MontoOtorgado, 2, '.', ',') }}</td>
                <td class="text-right">
                    ${{ number_format($obj->saldo_total, 2, '.', ',') }}
                </td>
                @php($total += $obj->saldo_total)
            </tr>


        @endforeach

        <tr>
            <th colspan="4">TOTAL</th>

            <th class="text-right">
                ${{ number_format($total, 2, '.', ',') }}
            </th>
        </tr>
    </tbody>
</table>
