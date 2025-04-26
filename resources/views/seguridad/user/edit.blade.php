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
                        <label class="col-sm-3 control-label">Correo</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="email" name="email" value="{{ $usuario->email }}" class="form-control">
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

                        @foreach ($roles as $rol)
                            <div class="col-md-3 col-sm-12 col-xs-12"
                                style="display: flex; align-items: center; gap: 10px;">
                                <label class="switch">
                                    <input type="checkbox"
                                        onchange="updateUserRole({{ $usuario->id }}, {{ $rol->id }})"
                                        {{ $usuario->hasRole($rol->name) ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <label class="control-label" style="margin-bottom: 0">{{ $rol->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>


            </div>




        </div>







        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                //mostrar opcion en menu
                displayOption("ul-seguridad", "li-catalogo-usuario");

            });

            function updateUserRole(userId, roleId) {
                // Construir la URL con los parámetros GET
                const url = new URL('{{ url('usuario/rol_link') }}');
                url.searchParams.append('user_id', userId);
                url.searchParams.append('role_id', roleId);

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            //alert(data.message || 'Rol actualizado correctamente');
                        } else {
                            throw new Error(data.message || 'Error al actualizar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message);
                    });
            }
        </script>
    @endsection
