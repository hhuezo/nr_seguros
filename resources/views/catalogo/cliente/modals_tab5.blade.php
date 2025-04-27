{{-- modales habito --}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-habito" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/add_habito') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nuevo Hábito</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Actividad Económica *</label>
                                    <input type="text" name="ActividadEconomica" class="form-control"
                                           value="{{ old('ActividadEconomica') }}"
                                           oninput="this.value = this.value.toUpperCase()"
                                           required>
                                    @error('ActividadEconomica')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ingreso Promedio *</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control"
                                           name="IngresoPromedio"
                                           value="{{ old('IngresoPromedio') }}"
                                           required>
                                    @error('IngresoPromedio')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Porción de ingresos que gasta en seguros mensual *</label>
                                    <input type="number" step="0.01" min="0.01" name="GastoMensualSeguro"
                                           class="form-control"
                                           value="{{ old('GastoMensualSeguro') }}"
                                           required>
                                    @error('GastoMensualSeguro')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nivel Educativo *</label>
                                    <input type="text" name="NivelEducativo" class="form-control"
                                           value="{{ old('NivelEducativo') }}"
                                           oninput="this.value = this.value.toUpperCase()"
                                           required>
                                    @error('NivelEducativo')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row"> * Campo requerido</div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="col-12">
    <div class="modal fade modal-edit-habito" id="modal-edit-habito" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/edit_habito') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar Hábito</h4>
                        <input type="hidden" name="Id" id="ModalHabitoId" class="form-control">
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Actividad Económica *
                                    <input type="text" name="ActividadEconomica" id="ModalHabitoActividadEconomica" oninput="this.value = this.value.toUpperCase()" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Ingreso Promedio *
                                    <input type="number" name="IngresoPromedio" id="ModalHabitoIngresoPromedio" step="0.001" min="0" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Porción de ingresos que gasta en seguros mensual *
                                    <input type="number" step="0.001" min="0" name="GastoMensualSeguro" id="ModalHabitoGastoMensualSeguro" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Nivel Educativo *
                                    <input type="text" name="NivelEducativo" id="ModalHabitoNivelEducativo" oninput="this.value = this.value.toUpperCase()" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row"> * Campo requerido</div>
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
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-habito">

        <form method="POST" action="{{ url('catalogo/cliente/delete_habito') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <input type="hidden" name="Id" id="IdHabito">
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
