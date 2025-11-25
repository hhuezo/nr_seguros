<div class="col-12">
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-dato_tecnico-{{ $obj->Id }}">
        <div class="modal-dialog">

            <form method="POST" action="{{ route('edit_dato_tecnico', $obj->Id) }}">
                @csrf
                @method('PUT')

                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Editar cobertura</h4>
                    </div>

                    <div class="modal-body row">

                        <input type="hidden" name="Producto" value="{{ $producto->Id }}">

                        <div class="form-group">
                            Nombre
                            <input type="text" name="Nombre" value="{{ $obj->Nombre }}" class="form-control" oninput="uppercaseCaretSafe(this)"
                                required>
                        </div>

                        <div class="form-group">
                            Descripción
                            <textarea class="form-control" name="Descripcion" oninput="uppercaseCaretSafe(this)">{{ $obj->Descripcion }}</textarea>
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
