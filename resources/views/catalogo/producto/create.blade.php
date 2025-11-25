<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-create">

    <form action="{{ url('catalogo/producto') }}" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Nuevo producto</h4>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label class="control-label">Nombre del Producto</label>
                        <input type="text"
                               name="Nombre"
                               id="Nombre"
                               value="{{ old('Nombre') }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="Aseguradora" class="form-label">Aseguradora</label>
                        <select id="Aseguradora"
                                name="Aseguradora"
                                class="form-control select2"
                                style="width: 100%"
                                required>
                            @foreach ($aseguradoras as $obj)
                                <option value="{{ $obj->Id }}"
                                    {{ old('Aseguradora') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="NecesidadProteccion" class="form-label">Ramo</label>
                        <select id="NecesidadProteccion"
                                name="NecesidadProteccion"
                                class="form-control select2"
                                style="width: 100%"
                                required>
                            @foreach ($ramos as $obj)
                                <option value="{{ $obj->Id }}"
                                    {{ old('NecesidadProteccion') == $obj->Id ? 'selected' : '' }}>
                                    {{ $obj->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control"
                                  name="Descripcion"
                                  id="Descripcion">{{ old('Descripcion') }}</textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>

            </div>
        </div>
    </form>

</div>
