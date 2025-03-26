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
                                onchange="registroValidado('{{ $obj->NumeroReferencia }}','{{ $obj->Dui }}')"
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


<script>
    function registroValidado(NumeroReferencia, Dui) {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "NumeroReferencia": NumeroReferencia
        };
        $.ajax({
            type: "POST",
            url: "{{ url('polizas/deuda/agregar_validado') }}",
            data: parametros,
            success: function(data) {
                console.log(data);

                // Asegurarse de que el ID esté correctamente formateado
                var button = $("#cumulo-" + Dui);

                if (button.length) { // Verificar si el botón existe
                    if (data.count == 0) {
                        button.removeClass("btn-primary").addClass("btn-success");
                    } else {
                        button.removeClass("btn-success").addClass("btn-primary");
                    }
                } else {
                    console.error("Botón no encontrado con ID: cumulo-" + Dui);
                }

            }
        })
    }
</script>
