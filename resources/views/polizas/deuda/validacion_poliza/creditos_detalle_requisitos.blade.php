<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Fecha otorgamiento</th>
            <th>Edad otorgamiento</th>
            <th>Tipo cartera</th>
            <th>Total</th>
            @if ($tipo == 1)
                <th>Validado</th>
            @endif

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

                <td>{{ $obj->linea_credito->Nombre ?? '' }}
                    {{ $obj->linea_credito->Abreviatura ? '(' . $obj->linea_credito->Descripcion . ')' : '' }}
                </td>
                </td>
                <td class="text-right">
                    ${{ number_format($obj->TotalCredito, 2, '.', ',') }}
                </td>
                @if ($tipo == 1)
                    <td>
                        <label class="switch">
                            <input type="checkbox"
                                onchange="registroValidado('{{ $obj->Id }}')"
                                {{ $obj->Validado > 0 ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </td>
                @endif
                @php($total += $obj->TotalCredito)
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

