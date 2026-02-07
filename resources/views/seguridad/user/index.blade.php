@extends ('welcome')
@section('contenido')

    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/drawer_styles.css') }}" rel="stylesheet">

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

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

    <div class="x_panel">

        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Listado de usuarios </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                @can('usuario create')
                <button class="btn btn-info float-right" data-target="#modal-create" data-toggle="modal"> <i
                        class="fa fa-plus"></i>
                    Nuevo</button>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Roles</th>
                            <th>Activo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $obj)
                            @include('seguridad.user.edit_drawer', ['usuario' => $obj])
                            @include('seguridad.user.roles_drawer', ['usuario' => $obj])
                            <tr>
                                <td align="center">{{ $loop->iteration }}</td>
                                <td>{{ $obj->name }}</td>
                                <td>{{ $obj->email }}</td>
                                <td>
                                    {{ $obj->user_has_role->pluck('name')->implode(', ') }}
                                </td>

                                <td>
                                    @can('usuario edit')
                                    <input type="checkbox" class="js-switch-manual" onchange="toggleUserActive({{ $obj->id }})"
                                        {{ $obj->activo == 1 ? 'checked' : '' }} />
                                    @else
                                    <input type="checkbox" class="js-switch-manual" disabled
                                        {{ $obj->activo == 1 ? 'checked' : '' }} />
                                    @endcan
                                </td>

                                <td align="center">
                                    @can('usuario edit')
                                    <button class="btn btn-primary btn-sm" onclick="openEditDrawer({{ $obj->id }})" title="Modificar Usuario">
                                        <i class="fa fa-pencil fa-lg"></i>
                                    </button>
                                    @endcan
                                    @can('usuario edit')
                                    <button class="btn btn-info btn-sm" onclick="openRolesDrawer_{{ $obj->id }}({{ $obj->id }})" title="Asignar Roles" style="margin-left: 5px;">
                                        <i class="fa fa-user-plus fa-lg"></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
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
                    <div class="col-md-6">
                        <h4 class="modal-title">Nuevo usuario</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <form action="{{ url('usuario') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="form-control"
                                autofocus>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Correo</label>
                            <input type="email" required name="email" value="{{ old('email') }}" class="form-control"
                                onblur="this.value = this.value.toLowerCase();">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Clave</label>
                            <input type="text" required name="password" class="form-control">
                            @if ($errors->has('password'))
                                <small class="text-danger">{{ $errors->first('password') }}</small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="control-label">Rol</label>
                            <select name="role_id" required class="form-control">
                                @foreach ($roles as $obj)
                                    <option value="{{ $obj->id }}"
                                        {{ old('role_id') == $obj->id ? 'selected' : '' }}>
                                        {{ $obj->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role_id'))
                                <small class="text-danger">{{ $errors->first('role_id') }}</small>
                            @endif
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

    <script>
        var displayStart = {{ $posicion }};
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                pageLength: 10,
                displayStart: displayStart,
            });

            // CAMBIO AQUÍ: Inicializamos solo nuestra clase personalizada
            table.rows().nodes().to$().find('.js-switch-manual').each(function() {
                if (!$(this).data('switchery')) {
                    var switchery = new Switchery(this, { color: '#26B99A' });
                }
            });
        });
    </script>

    <script>
        function toggleUserActive(id) {
            fetch(`{{ url('usuario/active') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}'
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.nuevo_estado == 1) {
                            toastr.success(data.message || 'Usuario activado correctamente');
                        } else {
                            toastr.success(data.message || 'Usuario desactivado correctamente');
                        }
                    } else {
                        throw new Error(data.message || 'Acción fallida');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error(error.message);
                });
        }

        function openEditDrawer(userId) {
            document.getElementById(`editDrawer_${userId}`).classList.add('active');
            document.getElementById(`editDrawerOverlay_${userId}`).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEditDrawer(userId) {
            document.getElementById(`editDrawer_${userId}`).classList.remove('active');
            document.getElementById(`editDrawerOverlay_${userId}`).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function closeAllEditDrawers() {
            @foreach ($usuarios as $obj)
                closeEditDrawer({{ $obj->id }});
            @endforeach
        }

        // Cerrar drawer con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                @foreach ($usuarios as $obj)
                    if (document.getElementById('editDrawer_{{ $obj->id }}').classList.contains('active')) {
                        closeEditDrawer({{ $obj->id }});
                    }
                    if (document.getElementById('rolesDrawer_{{ $obj->id }}').classList.contains('active')) {
                        closeRolesDrawer_{{ $obj->id }}({{ $obj->id }});
                    }
                @endforeach
            }
        });

    </script>
@endsection