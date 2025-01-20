<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Fecha otorgamiento</th>
            <th>Edad otorgamiento</th>
            <th>Tipo cartera</th>
            <th>Total</th>
            <th>Validado</th>
        </tr>
    </thead>
    <tbody>
        @php($total = 0)
        @foreach ($data->sortBy('EdadDesembloso') as $obj)
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
            <td>
                <label class="switch">
                    <input type="checkbox" onchange="registroValidado('{{$obj->NumeroReferencia}}')" {{ $obj->Validado > 0 ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
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


<script>
    function registroValidado(NumeroReferencia) {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}"
            , "NumeroReferencia": NumeroReferencia
        };
        $.ajax({
            type: "POST"
            , url: "{{ url('polizas/deuda/agregar_validado') }}"
            , data: parametros
            , success: function(data) {
                console.log(data);

            }
        })
    }

</script>
