<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-edit-{{ $obj->Id }}">
    <form method="POST" action="{{ route('nr_cartera.update', $obj->Id) }}">
        @method('PUT')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Editar Tipo de Cartera NR</h4>
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
                        <input type="text" name="Nombre" class="form-control" value="{{ strtoupper($obj->Nombre) }}" required
                            autofocus="true" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
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
