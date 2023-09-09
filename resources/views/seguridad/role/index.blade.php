@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">


        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Listado de roles </h2>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('rol/create') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i>
                        Nuevo</button></a>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>




        <br />
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $obj)
                            <tr>
                                <td align="center">{{ $obj->id }}</td>
                                <td>{{ $obj->name }}</td>
                                <td align="center">
                                    <a href="{{ url('rol') }}/{{ $obj->id }}/edit" class="on-default edit-row"><i
                                            class="fa fa-pencil fa-lg"></i></a>
                                    &nbsp;&nbsp;
                                    <a href="" data-target="#modal-delete-{{ $obj->id }}" data-toggle="modal"><i
                                            class="fa fa-trash fa-lg"></i></a>

                                </td>
                            </tr>
                            @include('seguridad.role.modal')
                        @endforeach
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
@endsection
