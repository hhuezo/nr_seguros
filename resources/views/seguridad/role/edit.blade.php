@extends ('welcome')
@section('contenido')


    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    <div class="x_panel">
        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endif

        @if (session('error'))
            <script>
                toastr.error("{{ session('error') }}");
            </script>
        @endif


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


            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Permisos</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        @foreach ($permisos as $permiso)
                            <div class="col-md-3 col-sm-12 col-xs-12"
                                style="display: flex; align-items: center; gap: 10px;">
                                <label class="switch">
                                    <input type="checkbox"
                                        onchange="updateRolePermiso({{ $permiso->id }},{{ $role->id }})"
                                        {{ in_array($permiso->id, $permisos_actuales) ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <label class="control-label" style="margin-bottom: 0">{{ $permiso->name }}</label>
                            </div>
                        @endforeach

                    </div>
                </div>


            </div>




        </div>




    </div>


    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-seguridad", "li-catalogo-role");

        });

        function updateRolePermiso(permisoId, roleId) {
            // Construir la URL con los parámetros GET
            const url = new URL('{{ url('role/permission_link') }}');
            url.searchParams.append('permission_id', permisoId);
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
