<div class="col-md-12 col-sm-12" align="right">
    <form method="POST" action="{{ url('exportar/nuevos_registros') }}/{{ $deuda->Id }}">
        @csrf
        <button class="btn btn-success" {{ $nuevos_registros->count() > 0 ? '' : 'disabled' }}>Descargar
            Excel</button>
    </form>
</div>
<br>
<table class="table table-striped" id="MyTable1">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>DUI/DOCUMENTO</th>
            <th>Nombre</th>
            <th>Fecha nacimiento</th>
            <th>Edad Actual</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($nuevos_registros->where('Edad', '<=', $deuda->EdadMaximaTerminacion) as $registro)
            <tr>
                <td>{{ $registro->NumeroReferencia }}</td>
                <td>{{ $registro->Dui }} {{ $registro->Pasaporte }} {{ $registro->CarnetResidencia }}</td>
                <td>{{ $registro->PrimerNombre }}
                    {{ $registro->SegundoNombre }}
                    {{ $registro->PrimerApellido }}
                    {{ $registro->SegundoApellido }}
                    {{ $registro->ApellidoCasada }}
                </td>
                <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                </td>
                <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                <td>${{ number_format($registro->TotalCredito, 2) }}</td>
            </tr>
        @endforeach


    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#MyTable1').DataTable();
    });
</script>
