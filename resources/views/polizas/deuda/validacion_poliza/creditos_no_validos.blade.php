<table class="table table-striped" id="MyTable3">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>Tipo cartera</th>
            <th>DUI/DOCUMENTO</th>
            <th>Nombre</th>
            <th>Fecha nacimiento</th>
            <th>Fecha otorgamiento</th>
            <th>Edad actual</th>
            <th>Edad desembolso</th>
            <th>Saldo</th>
            <th>Motivo</th>
            <th>Agregar a válidos</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
            $total = 0;
        @endphp
        @foreach ($poliza_cumulos->where('Excluido', 0) as $registro)
            <tr>
                <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
                <td>{{ $registro->TipoCarteraNombre }}
                </td>
                <td>{{ $registro->Dui }} {{ $registro->Pasaporte }} {{ $registro->CarnetResidencia }}</td>
                <td>{{ $registro->PrimerNombre }}
                    {{ $registro->SegundoNombre }}
                    {{ $registro->PrimerApellido }}
                    {{ $registro->SegundoApellido }}
                    {{ $registro->ApellidoCasada }}
                </td>
                <td>{{ $registro->FechaNacimiento ?? '' }}</td>
                <td>{{ $registro->FechaOtorgamiento ?? '' }}</td>
                <td>{{ $registro->Edad ? $registro->Edad . ' Años' : '' }}</td>
                <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso . ' Años' : '' }}</td>
                <td class="text-right">
                    ${{ number_format($registro->saldo_total, 2, '.', ',') }}
                </td>
                @if ($registro->MontoMaximoIndividual == 1)
                    <td> Registro supera monto maximo individual</td>
                @else
                    <td> {{ $registro->Motivo }}</td>
                @endif

                <td align="center" data-target="#modal_cambio_credito_valido" data-toggle="modal"
                    onclick="get_referencia_creditos({{ $registro->Id }},{{$registro->TipoCarteraId}})">
                    <button class="btn btn-primary">
                        <i class="fa fa-exchange"></i>
                    </button>
                </td>
            </tr>
            @php

                $i++;
            $total += $registro->saldo_total; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" align="right">Total</td>
            <td>${{ number_format($total, 2, '.', ',') }}</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>



<script>
    $(document).ready(function() {
        $('#MyTable3').DataTable();
    });
</script>
