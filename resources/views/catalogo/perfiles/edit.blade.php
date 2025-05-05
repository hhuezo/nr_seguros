
<div class="modal fade" id="modal-edit-{{ $item->Id }}" tabindex="-1" user="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-6">
                    <h4 class="modal-title">Modificar requisito</h4>
                </div>
                <div class="col-md-6">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{ route('perfiles.update', $item->Id) }}">
                @method('PUT')
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Código</label>
                        <input type="text" name="Codigo" value="{{ $item->Codigo }}" required class="form-control"
                            autofocus>
                    </div>

                    <div class="form-group">
                        <label for="Aseguradora" class="form-label">Aseguradora</label>
                        <select id="Aseguradora" name="Aseguradora" class="form-control select2" style="width: 100%">
                            @foreach ($aseguradoras as $obj)
                                <option value="{{ $obj->Id }}" {{ $item->Aseguradora == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" align="right">Descripción</label>
                        <textarea name="Descripcion"  class="form-control" oninput="this.value = this.value.toUpperCase();">{{ $item->Descripcion }}</textarea>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="PagoAutomatico" value="1" class="js-switch" {{ $item->PagoAutomatico == 1 ? 'checked' : '' }} />&nbsp;
                        <label class="control-label" align="right">Pago Automático</label>

                        <div class="form-group row col-md-6">
                            <input type="checkbox" name="DeclaracionJurada" value="1" class="js-switch" {{  $item->DeclaracionJurada == 1 ? 'checked' : '' }} />&nbsp;
                            <label class="control-label" align="right">Declaración Jurada</label>
                        </div>
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
