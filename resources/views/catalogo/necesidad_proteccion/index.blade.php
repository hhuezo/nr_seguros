@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Ramos </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                @can('ramo create')
                <button class="btn btn-info float-right" data-target="#modal-create" data-toggle="modal"> <i
                        class="fa fa-plus"></i>
                    Nuevo</button>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="55%">Nombre</th>
                            <th width="20%">Modulo</th>
                            <th width="20%">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($necesidad_proteccion as $obj)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $obj->Nombre }}</td>
                                <td>{{ $obj->tipo_poliza->Nombre ?? '' }}</td>
                                <td align="center">

                                    @can('ramo edit')
                                        <a href="#" data-target="#modal-edit-{{ $obj->Id }}" data-toggle="modal"
                                            class="on-default edit-row"><button class="btn btn-primary"><i
                                                    class="fa fa-pencil fa-lg"></i></button></a>
                                    @endcan


                                    @can('ramo delete')
                                        <a href="#" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-danger"><i class="fa fa-trash fa-lg"></i></button></a>
                                    @endcan
                                </td>
                            </tr>
                            @include('catalogo.necesidad_proteccion.modal')
                            @include('catalogo.necesidad_proteccion.edit')
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
                        <h4 class="modal-title">Nuevo Ramo</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <form action="{{ url('catalogo/necesidad_proteccion') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" name="Nombre" type="text" autofocus required
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                        </div>

                        <div class="form-group row">
                            <label class="control-label">Modulo</label>
                            <select name="TipoPoliza" class="form-control">
                                 <option value="">Seleccione</option>
                                @foreach ($tipos_poliza as $tipo)
                                    <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
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

    <script>
        var displayStart = {{ $posicion }};
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                pageLength: 10,
                displayStart: displayStart,
                ordering: false
            });
        });
    </script>
    @include('sweetalert::alert')
@endsection
