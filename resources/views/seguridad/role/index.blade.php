@extends ('welcome')
@section('contenido')
    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    <!-- Drawer Styles CSS -->
    <link href="{{ asset('css/drawer_styles.css') }}" rel="stylesheet">

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

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Listado de roles </h2>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                @can('rol create')
                <button class="btn btn-info float-right" data-target="#modal-create" data-toggle="modal"> <i
                        class="fa fa-plus"></i>
                    Nuevo</button>
                @endcan
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
                            @include('seguridad.role.edit_drawer', ['rol' => $obj])
                            @include('seguridad.role.permissions_drawer', ['rol' => $obj])
                            <tr>
                                <td align="center">{{ $obj->id }}</td>
                                <td>{{ $obj->name }}</td>
                                <td align="center">
                                    @can('rol edit')
                                    <button class="btn btn-primary btn-sm" onclick="openEditDrawer({{ $obj->id }})" title="Modificar Rol">
                                        <i class="fa fa-pencil fa-lg"></i>
                                    </button>
                                    @endcan
                                    @can('rol edit')
                                    <button class="btn btn-info btn-sm" onclick="openPermissionsDrawer_{{ $obj->id }}({{ $obj->id }})" title="Asignar Permisos" style="margin-left: 5px;">
                                        <i class="fa fa-key fa-lg"></i>
                                    </button>
                                    @endcan
                                    @can('rol delete')
                                    <a href="" data-target="#modal-delete-{{ $obj->id }}"
                                        data-toggle="modal" style="margin-left: 5px;">
                                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash fa-lg"></i></button>
                                    </a>
                                    @endcan
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


    <div class="modal fade" id="modal-create" tabindex="-1" user="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Nuevo rol</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <form action="{{ url('rol') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">rol</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="form-control"
                                autofocus>
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
        var displayStart = 0;
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                pageLength: 10,
                displayStart: displayStart,
            });
        });

        function openEditDrawer(roleId) {
            document.getElementById(`editDrawer_${roleId}`).classList.add('active');
            document.getElementById(`editDrawerOverlay_${roleId}`).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEditDrawer(roleId) {
            document.getElementById(`editDrawer_${roleId}`).classList.remove('active');
            document.getElementById(`editDrawerOverlay_${roleId}`).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Cerrar drawer con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                @foreach ($roles as $obj)
                    if (document.getElementById('editDrawer_{{ $obj->id }}').classList.contains('active')) {
                        closeEditDrawer({{ $obj->id }});
                    }
                    if (document.getElementById('permissionsDrawer_{{ $obj->id }}').classList.contains('active')) {
                        closePermissionsDrawer_{{ $obj->id }}({{ $obj->id }});
                    }
                @endforeach
            }
        });
    </script>
@endsection
