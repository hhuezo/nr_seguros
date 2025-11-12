<div class="modal fade bs-example-modal-lg" id="modal-edit-{{ $registro->Id }}" tabindex="-1" role="dialog"
    aria-labelledby="modalEditLabel{{ $registro->Id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $registro->ClienteNombre ?? 'Sin nombre' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Recepción archivo</label>
                            <input type="date" class="form-control"
                                v-model="registro.FechaRecepcionArchivo"
                                @change="calcularDiasHabiles(registro.Id)">
                        </div>

                        <div class="form-group">
                            <label>Fecha envío a CIA</label>
                            <input type="date" class="form-control"
                                v-model="registro.FechaEnvioCia"
                                @change="calcularDiasHabiles(registro.Id)">
                        </div>

                        <div class="form-group">
                            <label>Trabajo día hábil</label>
                            <input type="number" class="form-control" readonly
                                v-model="registro.TrabajoEfectuadoDiaHabil">
                        </div>

                        <div class="form-group">
                            <label>Hora tarea</label>
                            <input type="time" class="form-control" v-model="registro.HoraTarea">
                        </div>

                        <div class="form-group">
                            <label>Flujo asignado</label>
                            <input type="text" class="form-control" v-model="registro.FlujoAsignado">
                        </div>

                        <div class="form-group">
                            <label>Fecha vencimiento</label>
                            <input type="date" class="form-control" v-model="registro.FechaVencimiento">
                        </div>

                        <div class="form-group">
                            <label>Fecha envío cliente</label>
                            <input type="date" class="form-control" v-model="registro.FechaEnvioCliente">
                        </div>

                        <div class="form-group">
                            <label>Anexo declaración</label>
                            <input type="text" class="form-control" v-model="registro.AnexoDeclaracion">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Número Cisco</label>
                            <input type="text" class="form-control" v-model="registro.NumeroSisco">
                        </div>

                        <div class="form-group">
                            <label>Reproceso NR</label>
                            <select class="form-control" v-model="registro.ReprocesoNRId">
                                <option value="">SELECCIONE</option>
                                @foreach ($reprocesos as $reproceso)
                                    <option value="{{ $reproceso->Id }}">{{ $reproceso->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fecha envío corrección</label>
                            <input type="date" class="form-control" v-model="registro.FechaEnvioCorreccion">
                        </div>

                        <div class="form-group">
                            <label>Fecha seguimiento cobros</label>
                            <input type="date" class="form-control" v-model="registro.FechaSeguimientoCobros">
                        </div>

                        <div class="form-group">
                            <label>Fecha recepción pago</label>
                            <input type="date" class="form-control" v-model="registro.FechaRecepcionPago">
                        </div>

                        <div class="form-group">
                            <label>Fecha reporte CIA</label>
                            <input type="date" class="form-control" v-model="registro.FechaReporteACia">
                        </div>

                        <div class="form-group">
                            <label>Fecha aplicación</label>
                            <input type="date" class="form-control" v-model="registro.FechaAplicacion">
                        </div>

                        <div class="form-group">
                            <label>Comentarios</label>
                            <textarea class="form-control" rows="3" v-model="registro.Comentarios"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="guardarCambios(registro)">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
