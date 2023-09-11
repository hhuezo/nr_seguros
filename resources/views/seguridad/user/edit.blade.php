@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="x_title">
                <h2>Modificación de usuarios</h2>

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

                <form method="POST" action="{{ route('usuario.update', $usuario->id) }}">
                    @method('PUT')
                    @csrf
                    <br />

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="name" value="{{ $usuario->name }}" class="form-control">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">Clave</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="password" class="form-control">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Correo</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="email" name="email" value="{{ $usuario->email }}" class="form-control">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>



                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit">Guardar</button>
                        <a href="{{ url('usuario/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                    </div>


                </form>


            </div>
        </div>

        <div class="row">




            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Roles</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-horizontal form-label-left">
                            <form method="POST" action="{{ url('usuario/rol_link') }}">
                                @csrf
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type="hidden" name="Usuario" value="{{ $usuario->id }}">
                                            <select name="Rol" class="form-control select2">
                                                @foreach ($roles as $obj)
                                                    <option value="{{ $obj->id }}">{{ $obj->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="divider-dashed"></div>

                            <div class="form-group">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Registros</th>
                                            <th><i class="fa fa-trash fa-lg"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user_has_rol as $obj)
                                            <tr>
                                                <td align="center">{{ $obj->name }}</td>
                                                <td>
                                                    <i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_rol(<?php echo $obj->id; ?>)"></i>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>





                <!-- Modal eliminar roles -->
                <div class="modal fade" id="modal_delete_rol" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ url('usuario/rol_unlink') }}" method="POST">
                                <div class="modal-header">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <input type="hidden" id="RolModal" name="Rol">
                                <input type="hidden" value="{{ $usuario->id }}" name="Usuario">
                                <div class="modal-body">
                                    <div class="box-body">
                                        @csrf
                                        ¿Desea eliminar el registro?
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







        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript">
            function modal_delete_rol(rol) {
                document.getElementById('RolModal').value = rol;
                $('#modal_delete_rol').modal('show');
            }
        </script>
    @endsection
