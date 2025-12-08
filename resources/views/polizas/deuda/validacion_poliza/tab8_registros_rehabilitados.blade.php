<div class="col-md-12 col-sm-12" align="right">
    <form method="POST" action="{{ url('exportar/rehabilitados') }}/{{ $deuda->Id }}">
        @csrf
        <button class="btn btn-success">Descargar Excel</button>
    </form>
    <br>
</div>
<br>
<table class="table table-striped" id="MyTable8">
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
        </tr>
    </thead>
    <tbody>
        @foreach ($registros_rehabilitados as $registro)
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
                <td>{{ $registro->Edad ? $registro->Edad : '' }}
                    Años</td>
            </tr>
        @endforeach


    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#MyTable8').DataTable();
    });
</script>

