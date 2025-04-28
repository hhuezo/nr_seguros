<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-edit-{{ $obj->id }}">
    <form method="POST" action="{{ route('permission.update', $obj->id) }}">
        @method('PUT')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Modificar registro</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label" align="right">Nombre</label>

                        <input class="form-control" name="name" required type="text" value="{{ $obj->name }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </form>

</div>
