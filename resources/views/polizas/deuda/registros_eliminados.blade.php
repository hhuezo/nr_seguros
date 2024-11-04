<table class="table table-striped" id="MyTable2">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>DUI</th>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Fecha Nacimiento</th>
            <th>Fecha Otorgamiento</th>
            <th>Edad Actual</th>
            <th>Edad Desembolso</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registros_eliminados as $registro)
        <tr>
            <td>{{ $registro->NumeroReferencia }}</td>
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
            <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
            </td>
            <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
            <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                Años</td>
            <td>${{ number_format($registro->total_saldo, 2) }}</td>
        </tr>
        @endforeach


    </tbody>
</table>