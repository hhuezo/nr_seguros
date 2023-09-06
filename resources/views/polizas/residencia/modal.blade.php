<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-delete-{{ $obj->Id }}">

    <form method="POST" action="{{ route('residencia.destroy', $obj->Id) }}">
        @method('delete')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Desactivar Registro de la poliza</h4>
                </div>
                <div class="modal-body">
                    <p>Confirme si desea Desactivar el Registro</p>
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
    id="modal-activar-{{ $obj->Id }}">

    <form method="POST" action="{{ url('poliza/residencia/active', $obj->Id) }}">
        @method('POST')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Activar Modificación de Poliza </h4>
                </div>
                <div class="modal-body">
                    <p>Confirme si desea modificar</p>
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
    id="modal-desactivar-{{ $obj->Id }}">

    <form method="POST" action="{{ url('poliza/residencia/desactive', $obj->Id) }}">
        @method('POST')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Desactivar  modificación de la poliza</h4>
                </div>
                <div class="modal-body">
                    <p>Confirme si desea desactivar la modificacion </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </form>

</div>
