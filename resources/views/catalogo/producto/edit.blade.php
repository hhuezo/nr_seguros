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
        <div class="x_panel">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                    <div class="x_title">
                        <h2>Modificar Producto <small></small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <a href="{{ url('catalogo/producto') }}?idRegistro={{ $producto->Id }}"
                                class="btn btn-info fa fa-undo " style="color: white"> Atrás</a>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="{{ $tab == 1 ? 'active' : '' }}"><a href="#producto"
                                    id="producto-tab" role="tab" data-toggle="tab" aria-expanded="true">Producto</a>

                            </li>
                            <li role="presentation" class="{{ $tab == 2 ? 'active' : '' }}"><a href="#coberturas"
                                    role="tab" id="coberturas-tab" data-toggle="tab"
                                    aria-expanded="false">Coberturas</a>
                            </li>

                            <li role="presentation" class="{{ $tab == 3 ? 'active' : '' }}"><a href="#datos_tecnicos"
                                    role="tab" id="datos_tecnicos-tab" data-toggle="tab" aria-expanded="false">Datos
                                    técnicos</a>
                            </li>

                        </ul>


                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 1 ? 'active in' : '' }} " id="producto"
                                aria-labelledby="home-tab">

                                <form method="POST" action="{{ route('producto.update', $producto->Id) }}">
                                    @method('PUT')
                                    @csrf

                                    <div class="x_content">
                                        <br />
                                        <div class="row">


                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label ">Nombre del Producto</label>
                                                    <input type="text" name="Nombre" id="Nombre"
                                                        value="{{ $producto->Nombre }}" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="Aseguradora" class="form-label">Aseguradora</label>
                                                    <select id="Aseguradora" name="Aseguradora" class="form-control select2"
                                                        style="width: 100%">
                                                        @foreach ($aseguradoras as $obj)
                                                            <option value="{{ $obj->Id }}"
                                                                {{ $producto->Aseguradora == $obj->Id ? 'selected' : '' }}>
                                                                {{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="NecesidadProteccion" class="form-label">Ramo</label>
                                                    <select id="NecesidadProteccion" name="NecesidadProteccion"
                                                        class="form-control select2" style="width: 100%">
                                                        @foreach ($ramos as $obj)
                                                            <option value="{{ $obj->Id }}"
                                                                {{ $producto->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>
                                                                {{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Descripcion" class="form-label">Descripción</label>
                                                    <textarea class="form-control" name="Descripcion">{{ $producto->Descripcion }}</textarea>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="card-footer" align="right">
                                        <button class="btn btn-success" type="submit">Modificar</button>
                                    </div>
                                </form>

                            </div>

                            <div role="tabpanel" class="tab-pane fade {{ $tab == 2 ? 'active in' : '' }}" id="coberturas"
                                aria-labelledby="home-tab">
                                <div class="col-12" style="text-align: right;">
                                    <button class="btn btn-primary" data-toggle="modal"
                                        data-target=".bs-modal-nuevo-cobertura"><i class="fa fa-plus fa-lg"></i>
                                        Nueva Cobertura</button>
                                </div>
                                @if ($coberturas->count() > 0)
                                    <br>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>N</th>
                                                <th>Cobertura</th>
                                                <th>Tarificación</th>
                                                <th>Descuento</th>
                                                <th>IVA</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($coberturas as $obj)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $obj->Nombre }}</td>
                                                    <td>{{ $obj->tarificacion->Nombre ?? '' }}</td>

                                                    @if ($obj->Descuento)
                                                        <td>Si</td>
                                                    @else
                                                        <td>No</td>
                                                    @endif
                                                    @if ($obj->Iva)
                                                        <td>Si</td>
                                                    @else
                                                        <td>No</td>
                                                    @endif
                                                    <td>
                                                        <i class="fa fa-pencil fa-lg"
                                                            data-target="#modal-edit-cobertura-{{ $obj->Id }}"
                                                            data-toggle="modal"></i>
                                                        &nbsp;&nbsp;
                                                        <i class="fa fa-trash fa-lg"
                                                            data-target="#modal-delete-cobertura-{{ $obj->Id }}"
                                                            data-toggle="modal"></i>


                                                    </td>
                                                    @include('catalogo.producto.modal_edit_cobertura')
                                                    @include('catalogo.producto.modal_delete_cobertura')
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div style="height: 200px">
                                        <br>
                                        <div class="alert alert-danger alert-dismissible " role="alert">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close"><span aria-hidden="true">×</span>
                                            </button>
                                            <strong>Sin datos que mostrar.</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div role="tabpanel" class="tab-pane fade {{ $tab == 3 ? 'active in' : '' }}"
                                id="datos_tecnicos" aria-labelledby="home-tab">
                                <div class="col-12" style="text-align: right;">
                                    <button class="btn btn-primary" data-toggle="modal"
                                        data-target=".bs-modal-nuevo-dato_tecnico"><i class="fa fa-plus fa-lg"></i>
                                        Nuevo Dato Técnico</button>
                                </div>
                                @if ($datos_tecnicos->count() > 0)
                                    <br>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>N</th>
                                                <th>Campo</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($datos_tecnicos as $obj)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $obj->Nombre }}</td>
                                                    <td>{{ $obj->Descripcion }}</td>
                                                    <td>
                                                        <i class="fa fa-pencil fa-lg"
                                                            data-target="#modal-edit-dato_tecnico-{{ $obj->Id }}"
                                                            data-toggle="modal"></i>
                                                        &nbsp;&nbsp;
                                                        <i class="fa fa-trash fa-lg"
                                                            data-target="#modal-delete-dato-tecnico-{{ $obj->Id }}"
                                                            data-toggle="modal"></i>
                                                    </td>
                                                </tr>

                                                @include('catalogo.producto.modal_edit_dato_tecnico')
                                                 @include('catalogo.producto.modal_delete_dato_tecnico')
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div style="height: 200px">
                                        <br>
                                        <div class="alert alert-danger alert-dismissible " role="alert">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close"><span aria-hidden="true">×</span>
                                            </button>
                                            <strong>Sin datos que mostrar.</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>



                    </div>
                </div>

            </div>


            {{-- ingresar dato tecnico --}}
            <div class="col-12">
                <div class="modal fade bs-modal-nuevo-dato_tecnico" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{ route('add_dato_tecnico') }}">
                            @csrf
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Nuevo dato técnico</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="Producto" value="{{ $producto->Id }}"
                                        class="form-control">
                                    <div class="form-group">
                                        Nombre
                                        <input type="text" name="Nombre" class="form-control"
                                            oninput="uppercaseCaretSafe(this)" required>
                                    </div>

                                    <div class="form-group">
                                        Descripción
                                        <textarea class="form-control" name="Descripcion" oninput="uppercaseCaretSafe(this)"></textarea>
                                    </div>

                                </div>
                                <div>&nbsp; </div>
                                <div class="clearfix"></div>
                                <br>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>

                        </form>

                    </div>
                </div>
            </div>
            {{-- editar dato tecnico --}}
            <div class="col-12">
                <div class="modal fade modal-edit-dato_tecnico" tabindex="-1" role="dialog" aria-hidden="true"
                    id="modal-edit-dato_tecnico">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{ url('catalogo/producto/edit_dato_tecnico') }}">
                            @csrf
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Editar dato técnico</h4>
                                    <input type="hidden" name="Id" id="ModalDatoTecnicoId" class="form-control"
                                        required>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="Producto" value="{{ $producto->Id }}"
                                        class="form-control">
                                    <div class="form-group">
                                        <div class="row">
                                            Nombre
                                            <input type="text" name="Nombre" id="ModalDatoTecnicoNombre"
                                                class="form-control" required>
                                        </div>
                                        <div class="row">
                                            Descripción
                                            <textarea class="form-control" name="Descripcion" id="ModalDatoTecnicoDescripcion"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div>&nbsp; </div>
                                <div class="clearfix"></div>
                                <br>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>

                        </form>

                    </div>
                </div>
            </div>
            {{-- eliminar dato tecnico --}}

            <div class="col-12">
                <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                    id="modal-delete-dato_tecnico">

                    <form method="POST" action="{{ url('catalogo/producto/delete_dato_tecnico') }}">
                        @csrf
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <input type="hidden" name="Id" id="IdDatoTecnico">
                                    <h4 class="modal-title">Eliminar dato técnico</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Confirme si desea Eliminar el Registro</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- modales cobertura --}}
        <div class="col-12">
            <div class="modal fade bs-modal-nuevo-cobertura" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/producto/add_cobertura') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Nueva cobertura</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Producto" value="{{ $producto->Id }}" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        Nombre
                                        <input type="text" name="Nombre" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-6">
                                        Tarificación
                                        <select name="Tarificacion" id="Tarificacion" class="form-control" required>
                                            @foreach ($tarificaciones as $tarificacion)
                                                <option value="{{ $tarificacion->Id }}">{{ $tarificacion->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-6">
                                        Descuento
                                        <select name="Descuento" id="Descuento" class="form-control" required>
                                            <option value="0" {{ old('Descuento') == '0' ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1" {{ old('Descuento') == '1' ? 'selected' : '' }}>Si
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        IVA
                                        <select name="Iva" id="Iva" class="form-control" required>
                                            <option value="0" {{ old('Iva') == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('Iva') == '1' ? 'selected' : '' }}>Si</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <br>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>



    </div>


    <script>
        $(document).ready(function() {});

        function uppercaseCaretSafe(input) {
            let start = input.selectionStart;
            let end = input.selectionEnd;

            let originalLength = input.value.length;

            input.value = input.value.toUpperCase();

            let newLength = input.value.length;
            let diff = newLength - originalLength;

            input.selectionStart = start + diff;
            input.selectionEnd = end + diff;
        }
    </script>


@endsection
