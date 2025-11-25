<div class="col-12">
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-cobertura-{{ $obj->Id }}">
        <div class="modal-dialog modal-lg">

            <form method="POST" action="{{ route('edit_cobertura', $obj->Id) }}">
                @csrf
                @method('PUT')

                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Editar cobertura</h4>

                        {{-- ID DE COBERTURA --}}
                        <input type="hidden" name="Id" value="{{ $obj->Id }}">
                    </div>

                    <div class="modal-body row">

                        {{-- PRODUCTO --}}
                        <input type="hidden" name="Producto" value="{{ $producto->Id }}">

                        {{-- NOMBRE --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                Nombre
                                <input type="text" name="Nombre" value="{{ $obj->Nombre }}" class="form-control"
                                    required>
                            </div>
                        </div>

                        {{-- TARIFICACION --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                Tarificación
                                <select name="Tarificacion" class="form-control" required>
                                    @foreach ($tarificaciones as $tarificacion)
                                        <option value="{{ $tarificacion->Id }}"
                                            {{ $obj->TarificacionId == $tarificacion->Id ? 'selected' : '' }}>
                                            {{ $tarificacion->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- DESCUENTO --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                Descuento
                                <select name="Descuento" class="form-control" required>
                                    <option value="0" {{ $obj->Descuento == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $obj->Descuento == 1 ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                        </div>

                        {{-- IVA --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                IVA
                                <select name="Iva" class="form-control" required>
                                    <option value="0" {{ $obj->Iva == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $obj->Iva == 1 ? 'selected' : '' }}>Sí</option>
                                </select>
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
