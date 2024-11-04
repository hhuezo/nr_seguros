<table class="table table-striped" id="MyTable1">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>DUI</th>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Fecha nacimiento</th>
            <th>Edad Actual</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($nuevos_registros as $registro)
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
            <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
        </tr>
        @endforeach


    </tbody>
</table>