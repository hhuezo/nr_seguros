<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-edit-{{ $obj->Id }}">
    <form method="POST" action="{{ route('ejecutivos.update', $obj->Id) }}">
        @method('PUT')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Modificar Ejecutivo</h4>
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
                            autofocus="true">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Código</label>
                        <input type="text" name="Codigo" class="form-control" required value="{{ $obj->Codigo }}">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Teléfono</label>
                        <input type="text" name="Telefono" class="form-control" value="{{ $obj->Telefono }}"
                            data-inputmask="'mask': ['9999-9999']">
                    </div>

                    <div class="form-group">
                        <label class="control-label ">Cargo o puesto</label>
                        <select name="AreaComercial" class="form-control select2" style="width: 100%">
                            @foreach ($area_comercial as $area)
                                <option value="{{ $area->Id }}"
                                    {{ $obj->AreaComercial == $area->Id ? 'selected' : '' }}>
                                    {{ $area->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Correo</label>
                        <input type="text" required name="Correo" value="{{ $obj->Correo }}" class="form-control">
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
