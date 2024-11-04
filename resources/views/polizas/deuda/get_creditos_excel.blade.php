<style>
    .row-warning {
        background-color: #eeb458 !important;
        /* Color naranja sólido */
        color: #292828;
        /* Texto negro */
    }
</style>
<table class="table table-striped" id="MyTable4">
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
            <th>Requisitos</th>
            <th>Saldo</th>
        </tr>
    </thead>
    @php $i=1 @endphp
    <tbody>


        @foreach ($poliza_cumulos->where('Perfiles', '<>', null)->sortBy('Rehabilitado')->reverse() as $registro)
            <tr class="{{ $registro->Rehabilitado == 1 ? 'row-warning' : '' }}">
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
                <td>
                    @php
                    $perfilesArreglo = explode(',', $registro->Perfiles);
                    $uniquePerfiles = array_unique($perfilesArreglo);
                    @endphp

                    @foreach ($uniquePerfiles as $key => $perfil)
                    {{ $perfil }}{{ $loop->last ? '' : ', ' }}
                    @endforeach
                </td>
                <td class="text-right">
                    ${{ number_format($registro->total_saldo, 2) }}</td>

            </tr>
            @php $i++ @endphp
            @endforeach


            @foreach ($poliza_cumulos->where('NoValido', 0)->where('Perfiles', '')->where('Excluido', 0)->sortBy('Rehabilitado')->reverse() as $registro)
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
                <td>
                    @php
                    $perfilesArreglo = explode(',', $registro->Perfiles);
                    $uniquePerfiles = array_unique($perfilesArreglo);
                    @endphp

                    @foreach ($uniquePerfiles as $key => $perfil)
                    {{ $perfil }}{{ $loop->last ? '' : ', ' }}
                    @endforeach
                </td>
                <td class="text-right">
                    ${{ number_format($registro->total_saldo, 2) }}</td>

            </tr>
            @php $i++ @endphp
            @endforeach








    </tbody>
</table>

