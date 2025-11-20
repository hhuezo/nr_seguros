<div class="col-md-12 col-sm-12" align="right">
    <form method="POST" action="{{ url('exportar/registros_eliminados') }}/{{ $deuda->Id }}">
        @csrf
        <button class="btn btn-success" {{ $registros_eliminados->count() > 0 ? '' : 'disabled' }}>Descargar
            Excel</button>
    </form>
</div>
<br>
<table class="table table-striped" id="MyTable2">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>Linea credito</th>
            <th>DUI</th>
            <th>Nombre</th>
            <th>Fecha Nacimiento</th>
            <th>Fecha Otorgamiento</th>
            <th>Edad Actual</th>
            <th>Edad Desembolso</th>
            <th>Saldo reportado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registros_eliminados as $registro)
            <tr>
                <td>{{ $registro->NumeroReferencia }}</td>
                <td>{{ $registro->LineaCredito }} </td>
                <td>{{ $registro->Dui }}</td>
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
                <td>{{ $registro->Edad ? $registro->Edad : '' }}
                    Años</td>
                <td>
                    {{ $registro->TotalCredito ? '$'.number_format($registro->TotalCredito, 2, '.', ',') : '' }}
                </td>

            </tr>
        @endforeach


    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#MyTable2').DataTable();
    });
</script>
