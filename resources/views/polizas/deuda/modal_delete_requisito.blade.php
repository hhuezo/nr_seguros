<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-delete-{{ $requisitoId }}">

    <form method="POST" action="{{ url('polizas/deuda/eliminar_requisito') }}">
        @method('POST')
        @csrf

        <input type="hidden" value="{{ $requisitoId }}" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h5 class="modal-title">Eliminar Registro</h5>
                </div>
                <div class="modal-body">
                    <p>Confirme si desea eliminar el registro</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </form>

</div>
