@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Listado de negocios </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('catalogo/negocio/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id Cotización</th>
                            <th>Nombre Asegurado</th>
                            <th>Aseguradora</th>
                            <th>Email</th>
                            <th>Estado Cliente</th>
                            <th>Fecha de Venta</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($negocios as $obj)
                            <tr>
                                <td>{{$obj->Id}}</td>
                                @if ($obj->clientes)
                                    <td>{{ $obj->clientes->Nombre }}</td>
                                @else
                                    <td></td>
                                @endif
                                @if ($obj->Aseguradora)
                                    <td>{{ $obj->aseguradora->Nombre }}</td>
                                @else
                                    <td></td>
                                @endif
                                @if ($obj->clientes)
                                    <td>{{ $obj->clientes->CorreoPrincipal }}</td>
                                @else
                                    <td></td>
                                @endif
                                @if ($obj->clientes)
                                    <td>{{ $obj->clientes->estado->Nombre }}</td>
                                @else
                                    <td></td>
                                @endif

                                @if ($obj->FechaVenta)
                                    <td>{{ date('d/m/Y', strtotime($obj->FechaVenta)) }}</td>
                                @else
                                    <td></td>
                                @endif
                                <td align="center"><a href="" data-target="#modal-observacion-{{ $obj->Id }}"
                                        data-toggle="modal"><i class="fa fa-eye fa-lg"></i></a>

                                    @can('edit userss')
                                        <a href="{{ url('catalogo/negocio') }}/{{ $obj->Id }}/edit"
                                            class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i></a>
                                    @endcan


                                    @can('delete users')
                                        &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}"
                                            data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
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
    <script>
        let Eliminar = {{ session('Eliminar') ?? 'null' }};
        console.log(Eliminar);
        if (Eliminar === 1) {
            localStorage.clear();
        }
    </script>
@endsection
