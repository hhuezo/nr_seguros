@if ($aseguradora)
<table class="table table-striped jambo_table bulk_action">
    <thead>
        <tr class="headings">
            <th class="column-title">Aseguradoras</th>
            <th class="column-title">Necesidad Proteccion </th>
            <th class="column-title">Suma Asegurada</th>
            <th class="column-title"># Cuotas</th>
            <th class="column-title">Prima</th>
            <th class="column-title">Opciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($aseguradora as $obj)
        <tr class="even pointer">
            <td>{{$obj['Aseguradora']}}</td>
            <td>{{$obj['NecesidadProteccion']}}</td>
            <td>{{$obj['SumaAsegurada']}}</td>
            <td>Num Cuotas</td>
            <td>{{$obj['Prima']}}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

