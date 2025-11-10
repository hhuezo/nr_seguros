<div class="col-md-12 col-sm-12" align="right">
    <form method="POST" action="{{ url('exportar/extraprimados_excluidos') }}/{{ $deuda->Id }}">
        @csrf
        <button class="btn btn-success">Descargar Excel</button>
    </form>
    <br>
</div>
<br>
<table class="table table-striped" id="datatable">

    <thead>
        <tr>

            <th>Número Referencia</th>
            <th>Nombre</th>
            <th>DUI</th>
            <th>Fecha Otorgamiento</th>
            <th>Saldo</th>
            <th>Porcentaje EP</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($extra_primados->where('Existe', '=', 0) as $extra_primado)
            <tr>
                <td>{{ $extra_primado->NumeroReferencia }}</td>
                <td>{{ $extra_primado->Nombre }}</td>
                <td>{{ $extra_primado->Dui }}</td>
                <td>{{ $extra_primado->FechaOtorgamiento }}</td>
                <td>
                    {{ $extra_primado->MontoOtorgamiento ? number_format($extra_primado->MontoOtorgamiento, 4, '.', ',') : '' }}
                </td>

                <td> {{ $extra_primado->PorcentajeEP }}%</td>
            </tr>
        @endforeach


    </tbody>
</table>
