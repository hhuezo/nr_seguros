<table class="table table-striped" id="datatable">

    <thead>
        <tr>

            <th>Número Referencia</th>
            <th>Nombre</th>
            <th>Fecha Otorgamiento</th>
            <th>Monto Otorgamiento</th>
            <th>Porcentaje EP</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($extra_primados->where('Existe', '=', 0) as $extra_primado)
        <tr>
            <td>{{ $extra_primado->NumeroReferencia }}</td>
            <td>{{ $extra_primado->Nombre }}</td>
            <td>{{ $extra_primado->FechaOtorgamiento }}</td>
            <td>{{ $extra_primado->MontoOtorgamiento }}</td>
            <td> {{ $extra_primado->PorcentajeEP }}%</td>
        </tr>
        @endforeach


    </tbody>
</table>