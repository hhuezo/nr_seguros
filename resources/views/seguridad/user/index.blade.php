@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        <script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Listado de usuarios </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <button class="btn btn-info float-right" data-target="#modal-create" data-toggle="modal"> <i
                        class="fa fa-plus"></i>
                    Nuevo</button>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Activo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $obj)
                            <tr>
                                <td align="center">{{ $obj->id }}</td>
                                <td>{{ $obj->name }}</td>
                                <td>{{ $obj->email }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" {{ $obj->activo == 1 ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td align="center">

                                    @can('edit users')
                                        <a href="{{ url('usuario') }}/{{ $obj->id }}/edit" class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i></a>
                                    @endcan

                                </td>
                            </tr>
                            @include('seguridad.user.modal')
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-create" tabindex="-1" user="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2">Nuevo usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                  </div>
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo usuario</h4>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <form action="{{ url('usuario') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>

                            <input type="text" name="name" value="{{ old('name') }}" required class="form-control"
                                autofocus="true">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Clave</label>
                                <input type="text" required name="password" value="{{ old('password') }}"
                                    class="form-control">
                        </div>


                        <div class="form-group">
                            <label class="control-label">Correo</label>
                                <input type="email" required name="email" value="{{ old('email') }}"
                                    class="form-control" onblur="this.value = this.value.toLowerCase();">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Rol</label>
                                <select name="rol" required class="form-control">
                                    @foreach ($roles as $obj)
                                        <option value="{{ $obj->name }}" {{ old('rol') == $obj->id ?: '' }}>
                                            {{ $obj->name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
@endsection
