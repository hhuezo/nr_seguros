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
                <h3>Requisitos de asegurabilidad </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <button class="btn btn-info float-right" data-target="#modal-create" data-toggle="modal"> <i
                        class="fa fa-plus"></i> Nuevo</button>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Aseguradora</th>
                            <th>Pago Automatico</th>
                            <th>Declaracion Jurada</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perfiles as $item)
                            <tr>
                                <td>{{ $item->Codigo }}</td>
                                <td>{{ $item->Descripcion }}</td>
                                <td>{{ $item->aseguradoras->Nombre }}</td>
                                <td><input type="checkbox" class="js-switch"
                                        {{ $item->PagoAutomatico == 1 ? 'checked' : '' }}>
                                </td>
                                <td><input type="checkbox" class="js-switch"
                                        {{ $item->DeclaracionJurada == 1 ? 'checked' : '' }}></td>
                                <td style="text-align: center; display: flex; justify-content: center; gap: 8px;">
                                    @can('edit users')
                                        <a href="" data-target="#modal-edit-{{ $item->Id }}" data-toggle="modal"
                                            class="btn btn-primary btn-sm">
                                            <i class="fa fa-pencil fa-lg"></i>
                                        </a>
                                    @endcan

                                    @can('delete users')
                                        <a href="" data-target="#modal-delete-{{ $item->Id }}" data-toggle="modal"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </a>
                                    @endcan
                                </td>





                            </tr>
                            @include('catalogo.perfiles.modal')
                            @include('catalogo.perfiles.edit')
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
                        <h4 class="modal-title">Nuevo requisito</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <form action="{{ url('catalogo/perfiles') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Código</label>
                            <input type="text" name="Codigo" value="{{ old('Codigo') }}" required class="form-control"
                                autofocus>
                        </div>

                        <div class="form-group">
                            <label for="Aseguradora" class="form-label">Aseguradora</label>
                            <select id="Aseguradora" name="Aseguradora" class="form-control select2" style="width: 100%">
                                @foreach ($aseguradoras as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('Aseguradora') == $obj->Id ? 'selected' : '' }}>
                                        {{ $obj->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label" align="right">Descripción</label>
                            <textarea name="Descripcion"  class="form-control" oninput="this.value = this.value.toUpperCase();">{{ old('Descripcion') }}</textarea>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" name="PagoAutomatico" value="1" class="js-switch" {{ old('PagoAutomatico') ? 'checked' : '' }} />&nbsp;
                            <label class="control-label" align="right">Pago Automático</label>

                            <div class="form-group row col-md-6">
                                <input type="checkbox" name="DeclaracionJurada" value="1" class="js-switch" {{ old('DeclaracionJurada') ? 'checked' : '' }} />&nbsp;
                                <label class="control-label" align="right">Declaración Jurada</label>
                            </div>
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


    @include('sweetalert::alert')
@endsection
