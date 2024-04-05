@if ($opcion == 1)
<table class="table table-striped" id="MyTable3">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>DUI</th>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Fecha nacimiento</th>
            <th>Fecha otorgamiento</th>
            <th>Edad actual</th>
            <th>Edad desembolso</th>
            <th>Saldo</th>
            <th>Agregar a válidos</th>
        </tr>
    </thead>
    <tbody>
        @php $i=1; $total=0; @endphp
        @foreach ($poliza_cumulos as $registro)
        <tr>
            <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
            <td>{{ $registro->Dui }}</td>
            <td>{{ $registro->Nit }}</td>
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
                ${{ number_format($registro->total_saldo, 2) }}
            </td>
            <td align="center" data-target="#modal_cambio_credito_valido" data-toggle="modal" onclick="get_creditos({{ $registro->Id }})">
                <button class="btn btn-primary">
                    <i class="fa fa-exchange"></i>
                </button>
            </td>
        </tr>
        @php $i++; $total += $registro->total_saldo; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8" align="right">Total</td>
            <td>${{ number_format($total, 2, '.', ',') }}</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>

@else
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
        @foreach ($poliza_cumulos->where('NoValido', 0)->where('Perfiles', "") as $registro)
        <tr>
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



        @foreach ($poliza_cumulos->where('Perfiles', '<>', null) as $registro)
            <tr>
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
<script>
      $(document).ready(function() {
       $('#MyTable4').DataTable();
      });
</script>
@endif