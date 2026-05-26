<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-edit-{{ $obj->Id }}">
    <form method="POST" action="{{ route('resumengestiones.update', $obj->Id) }}">
        @method('PUT')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Editar Resumen de Gestión</h4>
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
                            autofocus="true" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Color</label>
                        <select class="form-control" name="Color">
                            <option value="" {{ empty($obj->Color) ? 'selected' : '' }}>Sin color</option>
                            <option value="success" {{ $obj->Color === 'success' ? 'selected' : '' }}>Verde (success) — finaliza el caso</option>
                            <option value="danger" {{ $obj->Color === 'danger' ? 'selected' : '' }}>Rojo (danger) — finaliza el caso</option>
                            <option value="info" {{ $obj->Color === 'info' ? 'selected' : '' }}>Azul (info) — finaliza el caso</option>
                            <option value="warning" {{ $obj->Color === 'warning' ? 'selected' : '' }}>Amarillo (warning)</option>
                            <option value="primary" {{ $obj->Color === 'primary' ? 'selected' : '' }}>Primario (primary)</option>
                            <option value="secondary" {{ $obj->Color === 'secondary' ? 'selected' : '' }}>Secundario (secondary)</option>
                        </select>
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
