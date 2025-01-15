<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Fecha otorgamiento</th>
            <th>Edad otorgamiento</th>
            <th>Tipo cartera</th>
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
                <td>{{ $obj->EdadDesembloso }}</td>

                <td>{{ $obj->linea_credito->tipoCarteras->Nombre ?? '' }}
                    {{ $obj->linea_credito->saldos->Abreviatura ? '(' . $obj->linea_credito->saldos->Descripcion . ')' : '' }}
                </td>
                </td>
                <td class="text-right">
                    ${{ number_format($obj->saldo_total, 2, '.', ',') }}
                </td>
                @php($total += $obj->saldo_total)
            </tr>
        @endforeach

        <tr>
            <th colspan="5">TOTAL</th>

            <th class="text-right">
                ${{ number_format($total, 2, '.', ',') }}
            </th>
        </tr>
    </tbody>
</table>
