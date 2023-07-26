@if ($aseguradora)
<table class="table table-striped jambo_table bulk_action">
    <thead>
        <tr class="headings">
            <th class="column-title">Aseguradoras</th>
            <th class="column-title">Necesidad Proteccion </th>
            <th class="column-title">Suma Asegurada</th>
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
            <td>{{$obj['Prima']}}</td>
            <td><a href="" class="on-default edit-row">
                                <i class="fa fa-pencil fa-lg"></i></a>
                                &nbsp;&nbsp;<a href="" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

