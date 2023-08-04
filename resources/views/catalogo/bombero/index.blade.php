@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Impuesto Bomberos </h3>
            </div>
            @can('create bomberos')
                <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                    <a href="{{ url('catalogo/bombero/create/') }}"><button class="btn btn-info float-right"> <i
                                class="fa fa-plus"></i> Nuevo</button></a>
                </div>
            @endcan

            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="tablaIndexBomberos" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Porcentaje de Impuesto</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bombero as $obj)
                            <tr>
                                <td>{{ $obj->Valor }}%</td>
                                @if ($obj->Activo == 1)
                                    <td>Activo GOES</td>
                                @else
                                    <td> Inactivo GOES</td>
                                @endif
                                <td align="center">

                                    @can('edit users')
                                        <a href="{{ url('catalogo/bombero') }}/{{ $obj->Id }}/edit"
                                            class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i></a>
                                    @endcan


                                    @can('delete users')
                                        &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}"
                                            data-toggle="modal"><i class="fa fa-refresh fa-lg"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @include('catalogo.bombero.modal')
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @include('sweetalert::alert')
    <script>
        //Tabla Bomberos
        $(document).ready(function() {
            $('#tablaIndexBomberos').DataTable({
                order: [
                    [1, 'asc']
                ] // 1 corresponde al index de la columna estado
            });
        });
    </script>
@endsection
