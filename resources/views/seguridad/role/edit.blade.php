@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="x_title">
                <h2>Modificación de roles</h2>

                <ul class="nav navbar-right panel_toolbox">

                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('rol.update', $role->id) }}">
                    @method('PUT')
                    @csrf
                    <br />


                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="name" required class="form-control" value="{{ $role->name }}">
                        </div>
                    </div>

                    <div class="form-group" align="center">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                        <a href="{{ url('rol') }}"><button type="button" class="btn btn-danger">Cancelar</button></a>
                    </div>

                </form>




            </div>
        </div>






        <div class="row">
            <div class="x_title">
                <h2>Permisos</h2>

                <ul class="nav navbar-right panel_toolbox">

                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <form method="POST" action="{{ url('role/permission_link') }}">
                    @csrf


                    <input type="hidden" name="Rol" value="{{ $role->id }}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Permiso</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <select name="Permiso" class="form-control select2">
                                @foreach ($permisos as $obj)
                                    <option value="{{ $obj->id }}">{{ $obj->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <button class="btn btn-success" type="submit">Agregar</button>
                        </div>
                    </div>
                </form>
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Descripción</th>
                            <th><i class="fa fa-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($permisos_actuales)
                            @foreach ($permisos_actuales as $obj)
                                <tr>
                                    <td align="center">{{ $obj->id }}</td>
                                    <td>{{ $obj->name }}</td>
                                    <td align="center">
                                        <i class="fa fa-trash" onclick="delete_permiso(<?php echo $obj->id; ?>);"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>

            </div>










            <!-- Modal eliminar permiso -->
            <div class="modal fade" id="modal_delete_permiso" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ url('role/permission_unlink') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <input type="hidden" name="Rol" value="{{ $role->id }}">
                            <input type="hidden" id="permiso" name="Permiso">

                            <div class="modal-body">
                                <div class="box-body">

                                    ¿Desea eliminar el archivo?
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Aceptar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>









        </div>







    </div>


    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <script type="text/javascript">
        function delete_permiso(permiso) {
            document.getElementById('permiso').value = permiso;

            $('#modal_delete_permiso').modal('show');

        }
    </script>
@endsection
