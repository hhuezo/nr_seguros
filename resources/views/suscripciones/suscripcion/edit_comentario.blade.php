<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-edit-comentario-{{ $comentario->Id }}">

    <form method="POST" action="{{ url('suscripciones/comentarios/update') }}/{{ $comentario->Id }} ">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Modificar comentario</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label ">Comentario <span
                                id="countComentario-{{ $comentario->Id }}">0/3000</span></label>
                        <textarea class="form-control" name="Comentario" id="Comentario-{{ $comentario->Id }}" rows="4" maxlength="3000"
                           oninput="showCountComentarioEdit({{ $comentario->Id }})">{{ $comentario->Comentario }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </div>
    </form>

</div>
