<div class="modal fade modal-edit-contacto" tabindex="-1" role="dialog" aria-hidden="true"
    id="modal-edit-contacto-{{ $contacto->Id }}">
    <div class="modal-dialog">
        <form method="POST" action="{{ url('catalogo/cliente/edit_contacto') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Editar contacto</h4>
                    <input type="hidden" name="Id" id="ModalContactoId" value="{{ $contacto->Id ?? '' }}" class="form-control" required>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">

                    <div class="form-group">
                        <div class="col-sm-12">
                            Nombre *
                            <input type="text" name="Nombre" id="ModalContactoNombre" class="form-control"
                                value="{{ $contacto->Nombre ?? '' }}"
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            Cargo *
                            <select name="Cargo" id="ModalContactoCargo" class="form-control" required>
                                @foreach ($cliente_contacto_cargos as $cargo)
                                    <option value="{{ $cargo->Id }}" {{ isset($contacto) && $contacto->Cargo == $cargo->Id ? 'selected' : '' }}>
                                        {{ $cargo->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            Teléfono *
                            <input type="text" name="Telefono" id="ModalContactoTelefono"
                                value="{{ $contacto->Telefono ?? '' }}"
                                data-inputmask="'mask': ['9999-9999']" data-mask class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            Email *
                            <input type="email" name="Email" id="ModalContactoEmail"
                                value="{{ $contacto->Email ?? '' }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            Lugar Trabajo *
                            <input type="text" name="LugarTrabajo" id="ModalContactoLugarTrabajo"
                                value="{{ $contacto->LugarTrabajo ?? '' }}"
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group"> * Campo requerido</div>
                </div>

                <div>&nbsp;</div>
                <div class="clearfix"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
