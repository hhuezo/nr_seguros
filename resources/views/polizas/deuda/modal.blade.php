<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-delete-{{ $obj->Id }}">

    <form method="POST" action="{{ route('deuda.destroy', $obj->Id) }}">
        @method('delete')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Eliminar Registro</h4>
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

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-renovar-{{ $obj->Id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Renovar Registro</h4>
            </div>
            <div class="modal-body">
                <p>Confirme si desea Renovar el Registro</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <a href="{{ url('polizas/deuda/renovar') }}/{{ $obj->Id }}" class="on-default edit-row btn btn-info" title="Renovar">Confirmar </a>

                
            </div>
        </div>
    </div>


</div>