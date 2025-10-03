@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Listado de Ejecutivos </h3>
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

                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Cargo o puesto</th>
                            <th>Correo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ejecutivo as $obj)
                            <tr>
                                <td>{{ $obj->Codigo }}</td>
                                <td>{{ $obj->Nombre }}</td>
                                <td>{{ $obj->Telefono }}</td>
                                <td>{{ $obj->areaComercial ? $obj->areaComercial->Nombre : '' }}</td>
                                <td>{{ $obj->Correo }}</td>

                                <td align="center">
                                    @can('edit users')
                                        <a href="#" data-target="#modal-edit-{{ $obj->Id }}" data-toggle="modal"
                                            class="on-default edit-row"><button class="btn btn-primary"><i
                                                    class="fa fa-pencil fa-lg"></i></button></a>
                                    @endcan
                                    @can('delete users')
                                        <a href="#" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-danger"><i class="fa fa-trash fa-lg"></i></button></a>
                                    @endcan
                                </td>


                            </tr>
                            @include('catalogo.ejecutivo.modal')
                            @include('catalogo.ejecutivo.edit')
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
                        <h4 class="modal-title">Nuevo Ejecutivo</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <form action="{{ url('catalogo/ejecutivos') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" required name="Nombre" value="{{ old('Nombre') }}" class="form-control"
                                autofocus oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Codigo</label>
                            <input type="text" required name="Codigo" value="{{ old('Codigo') }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Telefono</label>
                            <input type="text" required name="Telefono" value="{{ old('Telefono') }}"
                                class="form-control" data-inputmask="'mask': ['9999-9999']">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Cargo o puesto</label>
                            <select name="AreaComercial" class="form-control select2" style="width: 100%">
                                @foreach ($area_comercial as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                          <div class="form-group">
                            <label class="control-label">Correo</label>
                            <input type="email" required name="Correo" value="{{ old('Correo') }}"
                                class="form-control">
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
