
<div class="modal fade bs-example-modal-lg" id="modal-edit-{{ $registro->Id }}" tabindex="-1" role="dialog"
    aria-labelledby="modalEditLabel{{ $registro->Id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="modalEditLabel{{ $registro->Id }}">
                        Editar control de flujo ({{ $registro->Axo }}/{{ $registro->Mes }})
                    </h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ url('control_cartera/update/' . $registro->PolizaDeudaId) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    {{-- Fechas principales --}}
                    <div class="form-group row">
                        <label class="control-label col-md-3">Recepción archivo</label>
                        <div class="col-md-9">
                            <input type="date" name="FechaRecepcionArchivo" class="form-control"
                                value="{{ $registro->FechaRecepcionArchivo }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3">Envío a compañía</label>
                        <div class="col-md-9">
                            <input type="date" name="FechaEnvioACia" class="form-control"
                                value="{{ $registro->FechaEnvioACia }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3">Vencimiento</label>
                        <div class="col-md-9">
                            <input type="date" name="FechaVencimiento" class="form-control"
                                value="{{ $registro->FechaVencimiento }}">
                        </div>
                    </div>

                    {{-- Trabajo efectuado día hábil --}}
                    <div class="form-group row">
                        <label class="control-label col-md-3">Trabajo día hábil</label>
                        <div class="col-md-9">
                            <label class="switch">
                                <input type="checkbox" name="TrabajoEfectuadoDiaHabil" value="1"
                                    {{ $registro->TrabajoEfectuadoDiaHabil ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    {{-- Hora tarea --}}
                    <div class="form-group row">
                        <label class="control-label col-md-3">Hora tarea</label>
                        <div class="col-md-9">
                            <input type="time" name="HoraTarea" class="form-control" value="{{ $registro->HoraTarea }}">
                        </div>
                    </div>

                    {{-- Flujo asignado --}}
                    <div class="form-group row">
                        <label class="control-label col-md-3">Flujo asignado</label>
                        <div class="col-md-9">
                            <input type="text" name="FlujoAsignado" class="form-control"
                                value="{{ $registro->FlujoAsignado }}">
                        </div>
                    </div>

                    {{-- Rentabilidad --}}
                    <div class="form-group row">
                        <label class="control-label col-md-3">% Rentabilidad</label>
                        <div class="col-md-9">
                            <input type="number" step="0.01" name="PorcentajeRentabilidad" class="form-control"
                                value="{{ $registro->PorcentajeRentabilidad }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3">Valor descuento</label>
                        <div class="col-md-9">
                            <input type="number" step="0.01" name="ValorDescuentoRentabilidad" class="form-control"
                                value="{{ $registro->ValorDescuentoRentabilidad }}">
                        </div>
                    </div>

                    {{-- Fechas adicionales --}}
                    <div class="form-group row">
                        <label class="control-label col-md-3">Recepción pago</label>
                        <div class="col-md-9">
                            <input type="date" name="FechaRecepcionPago" class="form-control"
                                value="{{ $registro->FechaRecepcionPago }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3">Aplicación</label>
                        <div class="col-md-9">
                            <input type="date" name="FechaAplicacion" class="form-control"
                                value="{{ $registro->FechaAplicacion }}">
                        </div>
                    </div>

                    {{-- Comentarios --}}
                    <div class="form-group row">
                        <label class="control-label col-md-3">Comentarios</label>
                        <div class="col-md-9">
                            <textarea name="Comentarios" class="form-control" rows="3">{{ $registro->Comentarios }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
