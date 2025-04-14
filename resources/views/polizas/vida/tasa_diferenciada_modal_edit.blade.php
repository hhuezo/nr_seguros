    <div class="modal fade" id="modal-creditos-edit-{{$registro->Id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-tipo="1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('polizas/vida/tasa_diferenciada') }}/{{$registro->Id}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Nueva tasa diferenciada</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Tipo de Cartera</label>
                                    <input type="hidden" name="Id" value="{{ $deuda_credito->Id }}"
                                        class="form-control" readonly>
                                    <input type="text" name="TipoCartera"
                                        value="{{ optional($deuda_credito->tipoCarteras)->Nombre ?? '' }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label">Saldos y Montos</label>
                                    <input type="text" name="Saldos"
                                        value="{{ optional($deuda_credito->saldos)->Abreviatura ?? '' }} - {{ trim(optional($deuda_credito->saldos)->Descripcion ?? '') }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label">Tipo cálculo</label>
                                    <select name="TipoCalculo" id="TipoCalculo{{$registro->Id}}" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <option value="1" {{ $registro->TipoCalculo == '1' ? 'selected' : '' }}>Fecha
                                        </option>
                                        <option value="2" {{ $registro->TipoCalculo == '2' ? 'selected' : '' }}>Monto
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group row" id="divFechaDesde{{$registro->Id}}" style="display: none">
                                    <label class="control-label">Fecha inicio</label>
                                    <input type="date" name="FechaDesde" value="{{$registro->FechaDesde}}" class="form-control"
                                        value="">
                                </div>

                                <div class="form-group row" id="divFechaHasta{{$registro->Id}}" style="display: none">
                                    <label class="control-label">Fecha final</label>
                                    <input type="date" name="FechaHasta" value="{{$registro->FechaHasta}}" class="form-control"
                                        value="">
                                </div>

                                <div class="form-group row" id="divMontoDesde{{$registro->Id}}" style="display: none">
                                    <label class="control-label">Monto inicio</label>
                                    <input type="number" step="1" name="MontoDesde" value="{{$registro->MontoDesde}}" class="form-control"
                                        value="">
                                </div>

                                <div class="form-group row" id="divMontoHasta{{$registro->Id}}" style="display: none">
                                    <label class="control-label">Monto final</label>
                                    <input type="number" step="1" name="MontoHasta" value="{{$registro->MontoHasta}}" class="form-control"
                                        value="">
                                </div>

                                <div class="form-group row" id="divTasa{{$registro->Id}}" style="display: none">
                                    <label class="control-label">Tasa</label>
                                    <input type="number" name="Tasa" step="any" value="{{$registro->Tasa}}" class="form-control"
                                        value="">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer" align="center">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
