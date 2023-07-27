@extends ('welcome')
@section('contenido')
<div class="x_panel">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Listado de negocios </h3>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('catalogo/negocio/create/') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i> Nuevo</button></a>
            <a href="{{ url('catalogo/negocio/show/') }}"><button class="btn btn-primary float-right"> <i class="fa fa-search"></i> Consultar</button></a>
        </div>
        <div class="clearfix"></div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Asegurado</th>
                        <th>Aseguradora</th>
                        <th>Fecha venta</th>
                        <th>Inicio vigencia</th>
                        <th>Observaciones</th>
                        <th>Activo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($negocios as $obj)
                    <tr>
                        <td>{{ $obj->Asegurado }}</td>
                        @if ($obj->Aseguradora)
                        <td>{{ $obj->aseguradora->Nombre }}</td>
                        @else
                        <td></td>
                        @endif

                        @if ($obj->FechaVenta)
                        <td>{{ date('d/m/Y', strtotime($obj->FechaVenta)) }}</td>
                        @else
                        <td></td>
                        @endif

                        @if ($obj->InicioVigencia)
                        <td>{{ date('d/m/Y', strtotime($obj->InicioVigencia)) }}</td>
                        @else
                        <td></td>
                        @endif

                        <td align="center"><a href="" data-target="#modal-observacion-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-eye fa-lg"></i></a></td>
                        @if ($obj->Activo == 1)
                        <td align="center"><input type="checkbox" checked></td>
                        @else
                        <td align="center"><input type="checkbox"></td>
                        @endif
                        <td align="center">

                            @can('edit userss')
                            <a href="{{ url('catalogo/negocio') }}/{{ $obj->Id }}/edit" class="on-default edit-row">
                                <i class="fa fa-pencil fa-lg"></i></a>
                            @endcan


                            @can('delete users')
                            &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                            @endcan
                        </td>
                    </tr>
                    @include('catalogo.negocio.modal')
                    @include('catalogo.negocio.observacion')
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection