<style>
    .row-warning {
        background-color: #eeb458 !important;
        /* Color naranja sólido */
        color: #292828;
        /* Texto negro */
    }

    .row-error {
        background-color: #E63946 !important;
        /* Color naranja sólido */
        color: #F1FAEE;
        /* Texto negro */
    }
</style>
<table class="table table-striped" id="histoTable">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>DUI</th>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Fecha nacimiento</th>
            <th>Edad actual</th>
            <th>Edad otorgamiento</th>
            <th>Fecha otorgamiento</th>
            <th>Saldo</th>
            <th>Línea de Crédito</th>
        </tr>
    </thead>
    @php $i=1 @endphp
    <tbody>

        @foreach ($tabla_historico as $registro)
            <tr class="table-warning">
                <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
                <td>{{ $registro->Dui }}</td>
                <td>{{ $registro->Nit }}</td>
                <td>{{ $registro->PrimerNombre }}
                    {{ $registro->SegundoNombre }}
                    {{ $registro->PrimerApellido }}
                    {{ $registro->SegundoApellido }}
                    {{ $registro->ApellidoCasada }}
                </td>
                <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                </td>
                <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                    Años</td>
                <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                </td>
                <td class="text-right">
                    ${{ number_format($registro->total_saldo, 2,'.',',') }}</td>
                <td>{{$registro->TipoCarteraNombre}} {{$registro->Abreviatura}}</td>

            </tr>
            @php $i++ @endphp
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
            $('#histoTable').DataTable({
                ordering: false // Desactiva el ordenamiento
            });
        });
</script>
