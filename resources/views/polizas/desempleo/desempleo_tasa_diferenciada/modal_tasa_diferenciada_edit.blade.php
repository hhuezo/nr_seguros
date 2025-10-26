<div class="modal fade" id="modal-tasa-diferenciada_edit-{{ $tasa_diferenciada->Id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/desempleo/tasa_diferenciada') }}/{{ $tasa_diferenciada->Id }}" method="POST">
                @method('PUT')
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabelEdit">Editar tasa diferenciada</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="TipoCalculoEdit" value="{{ $tipo->TipoCalculo }}"
                                class="form-control">
                            <div class="form-group row">
                                <label class="control-label">Saldos y Montos</label>
                                <select class="form-control" name="SaldosMontosEdit">
                                    @foreach ($saldos_montos as $saldo)
                                        <option value="{{ $saldo->Id }}"
                                            {{ $tasa_diferenciada->SaldosMontos == $saldo->Id ? 'selected' : '' }}>
                                            {{ $saldo->Abreviatura }} -
                                            {{ $saldo->Descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row" id="divFechaDesdeEdit" style="display: none">
                                <label class="control-label">Fecha inicio</label>
                                <input type="date" name="FechaDesdeEdit" class="form-control"
                                    value="{{ $tasa_diferenciada->FechaDesde }}">
                            </div>

                            <div class="form-group row" id="divFechaHastaEdit" style="display: none">
                                <label class="control-label">Fecha final</label>
                                <input type="date" name="FechaHastaEdit" class="form-control"
                                    value="{{ $tasa_diferenciada->FechaHasta }}">
                            </div>

                            <div class="form-group row" id="divMontoDesdeEdit" style="display: none">
                                <label class="control-label">Monto inicio</label>
                                <input type="text" step="1" name="MontoDesdeEdit" class="form-control"
                                    value="{{ $tasa_diferenciada->MontoDesde }}">
                            </div>

                            <div class="form-group row" id="divMontoHastaEdit" style="display: none">
                                <label class="control-label">Monto final</label>
                                <input type="number" step="1" name="MontoHastaEdit" class="form-control"
                                    value="{{ $tasa_diferenciada->MontoHasta }}">
                            </div>

                            <div class="form-group row">
                                <label class="control-label">Tasa</label>
                                <input type="number" name="TasaEdit" id="TasaEditar" step="any"
                                    class="form-control" required value="{{ $tasa_diferenciada->Tasa }}">
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
