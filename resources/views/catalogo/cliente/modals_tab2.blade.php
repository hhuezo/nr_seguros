{{-- ventanas modales metodo de pago--}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-tarjeta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/add_tarjeta') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nuevo método de pago</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Método Pago *
                                    <select name="MetodoPago" class="form-control" id="MetodoPago" style="text-transform: uppercase;">
                                        @foreach ($metodos_pago as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Número Tarjeta *
                                    <input type="text" name="NumeroTarjeta" id="tarjeta" class="form-control" data-inputmask="'mask': ['9999-9999-9999-9999']" disabled data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Fecha Vencimiento *
                                    <input type="text" id="vencimiento" class="form-control" data-inputmask="'mask': ['99/99']" data-mask disabled name="FechaVencimiento">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Póliza Vinculada *
                                    <input type="text" name="PolizaVinculada" class="form-control" oninput="this.value = this.value.toUpperCase()" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">* Campo requerido</div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-tarjeta">

        <form method="POST" action="{{ url('catalogo/cliente/delete_tarjeta') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <input type="hidden" name="Id" id="IdTarjeta">
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
</div>

<div class="col-12">
    <div class="modal fade modal-edit-tarjeta" id="modal-edit-tarjeta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/edit_tarjeta') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar tarjeta</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Id" id="ModalTarjetaId" class="form-control">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Método pago *
                                    <select name="MetodoPago" id="ModalMetodoPago" class="form-control" style="text-transform: uppercase;" disabled>
                                        @foreach ($metodos_pago as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Número Tarjeta *
                                    <input type="text" name="NumeroTarjeta" id="ModalNumeroTarjeta" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">

                                    Fecha vencimiento *
                                    <input type="text" name="FechaVencimiento" id="ModalFechaVencimiento" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">

                                    Póliza vinculada *
                                    <input type="text" name="PolizaVinculada" id="ModalPolizaVinculada" class="form-control"
                                    style="text-transform: uppercase;" onblur="this.value = this.value.toUpperCase()" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">* Campo requerido</div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>
