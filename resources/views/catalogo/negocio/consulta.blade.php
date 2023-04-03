<script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>

@if($negocio->count > 0)
<table class="table table-striped table-bordered">
    <thead>
        <th>Vendedor</th>
        <th>Núm Poliza</th>
        <th>Tipo Poliza</th>
        <th>Asegurado</th>
        <th>Prima</th>
        <th>Notas</th>
        <th>Fecha Venta</th>
        <th>Encargado de Cuenta</th>
    </thead>
    <tbody>
        @foreach($negocio as $obj)
        <tr>
            <td>{{$obj->ejecutivos->Nombre}}</td>
            <td>{{$obj->aseguradora->Nombre}}</td>
            <td>{{$obj->tipo_poliza->Nombre}}</td>
            <td>{{$obj->Asegurado}}</td>
            <td></td>
            <td><a href="" data-target="#modal-show-{{ $obj->Id }}"  data-toggle="modal">Memo</a></td>
            <td>{{$obj->FechaVenta}}</td>
            <td>{{$obj->ejecutivos->Nombre}}</td>
        </tr>
        @include('catalogo.negocio.modal')
        @endforeach
    </tbody>
</table>
@endif