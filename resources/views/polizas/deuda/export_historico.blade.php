
<table >
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
            <tr >
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
                <td >
                    ${{ number_format($registro->total_saldo, 2,'.',',') }}</td>
                <td>{{$registro->TipoCarteraNombre}} {{$registro->Abreviatura}}</td>

            </tr>
            @php $i++ @endphp
        @endforeach
    </tbody>
</table>

