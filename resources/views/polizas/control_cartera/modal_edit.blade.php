<div class="modal fade bs-example-modal-lg" id="modal-edit-{{ $registro->Id }}" tabindex="-1" role="dialog"
    aria-labelledby="modalEditLabel{{ $registro->Id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="modalEditLabel{{ $registro->Id }}">
                        {{ $registro->ClienteNombre ?? 'Sin nombre' }}
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ url('control_cartera/' . $registro->Id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Recepción archivo</label>
                                <input type="date" name="FechaRecepcionArchivo"
                                    id="FechaRecepcionArchivo{{ $registro->Id }}" class="form-control"
                                    value="{{ $registro->FechaRecepcionArchivo }}"
                                    onchange="calcularDiasHabiles({{ $registro->Id }})">
                            </div>

                            <div class="form-group">
                                <label>Fecha envío a CIA</label>
                                <input type="date" name="FechaEnvioCia" id="FechaEnvioCia{{ $registro->Id }}"
                                    class="form-control" value="{{ $registro->FechaEnvioCia }}"
                                    onchange="calcularDiasHabiles({{ $registro->Id }})">
                            </div>

                            <div class="form-group">
                                <label>Trabajo día hábil</label>
                                <input type="number" name="TrabajoEfectuadoDiaHabil"
                                    id="TrabajoEfectuadoDiaHabil{{ $registro->Id }}" class="form-control"
                                    value="{{ $registro->TrabajoEfectuadoDiaHabil }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Hora tarea</label>
                                <input type="time" name="HoraTarea" class="form-control"
                                    value="{{ $registro->HoraTarea }}">
                            </div>

                            <div class="form-group">
                                <label>Flujo asignado</label>
                                <input type="text" name="FlujoAsignado" class="form-control"
                                    value="{{ $registro->FlujoAsignado }}">
                            </div>

                            <div class="form-group">
                                <label>Fecha vencimiento</label>
                                <input type="date" name="FechaVencimiento" class="form-control"
                                    value="{{ $registro->FechaVencimiento }}">
                            </div>

                            <div class="form-group">
                                <label>Fecha envío cliente</label>
                                <input type="date" name="FechaEnvioCliente" class="form-control"
                                    value="{{ $registro->FechaEnvioCliente }}">
                            </div>

                            <div class="form-group">
                                <label>% Rentabilidad</label>
                                <input type="number" step="0.01" name="PorcentajeRentabilidad" class="form-control"
                                    value="{{ $registro->PorcentajeRentabilidad }}">
                            </div>

                            <div class="form-group">
                                <label>Valor descuento</label>
                                <input type="number" step="0.01" name="ValorDescuentoRentabilidad"
                                    class="form-control" value="{{ $registro->ValorDescuentoRentabilidad }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Anexo declaración</label>
                                <input type="text" name="AnexoDeclaracion" class="form-control"
                                    value="{{ $registro->AnexoDeclaracion }}">
                            </div>

                            <div class="form-group">
                                <label>Número Cisco</label>
                                <input type="text" name="NumeroSisco" class="form-control"
                                    value="{{ $registro->NumeroSisco }}">
                            </div>

                            <div class="form-group">
                                <label>Reproceso NR</label>
                                <select class="form-control" name="ReprocesoNRId">
                                    <option value="">SELECCIONE</option>
                                    @foreach ($reprocesos as $reproceso)
                                        <option value="{{ $reproceso->Id }}"
                                            {{ $registro->ReprocesoNRId == $reproceso->Id ? 'selected' : '' }}>
                                            {{ $reproceso->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Fecha envío corrección</label>
                                <input type="date" name="FechaEnvioCorreccion" class="form-control"
                                    value="{{ $registro->FechaEnvioCorreccion }}">
                            </div>

                            <div class="form-group">
                                <label>Fecha seguimiento cobros</label>
                                <input type="date" name="FechaSeguimientoCobros" class="form-control"
                                    value="{{ $registro->FechaSeguimientoCobros }}">
                            </div>

                            <div class="form-group">
                                <label>Fecha recepción pago</label>
                                <input type="date" name="FechaRecepcionPago" class="form-control"
                                    value="{{ $registro->FechaRecepcionPago }}">
                            </div>

                            <div class="form-group">
                                <label>Fecha reporte CIA</label>
                                <input type="date" name="FechaReporteACia" class="form-control"
                                    value="{{ $registro->FechaReporteACia }}">
                            </div>

                            <div class="form-group">
                                <label>Fecha aplicación</label>
                                <input type="date" name="FechaAplicacion" class="form-control"
                                    value="{{ $registro->FechaAplicacion }}">
                            </div>

                            <div class="form-group">
                                <label>Comentarios</label>
                                <textarea name="Comentarios" class="form-control" rows="3">{{ $registro->Comentarios }}</textarea>
                            </div>
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
