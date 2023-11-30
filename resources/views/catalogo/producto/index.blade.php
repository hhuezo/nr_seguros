@extends ('welcome')
@section('contenido')
<div class="x_panel">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Listado de Productos </h3>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('catalogo/producto/create/') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i> Nuevo</button></a>
        </div>
        <div class="clearfix"></div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del producto</th>
                        <th>Aseguradora</th>
                        <th>Ramo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $obj)
                    <tr>
                        <td>{{ $obj->Id }}</td>
                        <td>{{$obj->Nombre}}</td>
                        <td>{{ $obj->aseguradoras->Nombre }}</td>
                        <td>{{ $obj->ramos->Nombre }}</td>

                        <td align="center">
                            @can('edit users')
                            <a href="{{ url('catalogo/producto') }}/{{ $obj->Id }}/edit" class="on-default edit-row">
                                <i class="fa fa-pencil fa-lg"></i></a>
                            @endcan

                            @can('delete users')
                            &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                            @endcan
                        </td>
                    </tr>
                    @include('catalogo.producto.modal')
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection

