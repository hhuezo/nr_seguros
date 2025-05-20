<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-edit-{{ $obj->Id }}">
    <form method="POST" action="{{ route('ubicacion_cobro.update', $obj->Id) }}">
        @method('PUT')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Editar Ubicación de Cobro</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                   

                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input type="text" name="Nombre" class="form-control" value="{{ $obj->Nombre }}" required
                            autofocus="true" >
                    </div>

                    
                    &nbsp;
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
    </form>

</div>
