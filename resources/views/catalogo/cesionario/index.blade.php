@extends ('welcome')
@section('contenido')
@include('sweetalert::alert')
<div class="x_panel">
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Cesionarios</h3>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-create">
                <i class="fa fa-plus"></i> Nuevo
            </button>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table id="datatable" class="table table-striped table-bordered" style="width:100%;">
            <thead>
                <tr>
                    <th width="8%">#</th>
                    <th>Nombre</th>
                    <th width="16%">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cesionarios as $obj)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $obj->Nombre }}</td>
                        <td align="center">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $obj->Id }}">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-delete-{{ $obj->Id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ url('catalogo/cesionario') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Nuevo cesionario</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="Nombre" class="form-control" value="{{ old('Nombre') }}" maxlength="200" required
                            oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach ($cesionarios as $obj)
    <div class="modal fade" id="modal-edit-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ url('catalogo/cesionario/' . $obj->Id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Editar cesionario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="Nombre" class="form-control" value="{{ old('Nombre', $obj->Nombre) }}" maxlength="200" required
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-delete-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ url('catalogo/cesionario/' . $obj->Id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Eliminar cesionario</h4>
                    </div>
                    <div class="modal-body">
                        <p>Confirme si desea eliminar el registro:</p>
                        <strong>{{ $obj->Nombre }}</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
    $(function() {
        if (!$.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable({
                pageLength: 10,
                scrollY: 350,
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Sin registros',
                    zeroRecords: 'No se encontraron registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Ultimo',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });
        }
    });
</script>
@endsection
