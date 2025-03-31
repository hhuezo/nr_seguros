<div class="modal fade" id="modal-tasa-diferenciada_edit-{{ $tasa_diferenciada->Id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/deuda/tasa_diferenciada') }}/{{ $tasa_diferenciada->Id }}" method="POST">
                @method('PUT')
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabelEdit">Agregar tasa diferenciada</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="TipoCalculoEdit" value="{{ $tipo->TipoCalculo }}" class="form-control">
                            <div class="form-group row">
                                <label class="control-label">Línea crédito</label>
                                <select class="form-control" name="LineaCreditoEdit">
                                    @foreach ($lineas_credito as $linea)
                                    <option value="{{ $linea->Id }}" {{$linea->Id == $tasa_diferenciada->LineaCredito ? 'selected':''}}>
                                        {{ $linea->Abreviatura }} - {{ $linea->Descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row" id="divFechaDesdeEdit" style="display: none">
                                <label class="control-label">Fecha inicio</label>
                                <input type="date" name="FechaDesdeEdit" class="form-control"
                                    value="{{$tasa_diferenciada->FechaDesde}}">
                            </div>

                            <div class="form-group row" id="divFechaHastaEdit" style="display: none">
                                <label class="control-label">Fecha final</label>
                                <input type="date" name="FechaHastaEdit" class="form-control"
                                    value="{{$tasa_diferenciada->FechaHasta}}">
                            </div>

                            <div class="form-group row" id="divEdadDesdeEdit" style="display: none">
                                <label class="control-label">Edad inicio</label>
                                <input type="text" step="1" name="EdadDesdeEdit" class="form-control"
                                    value="{{$tasa_diferenciada->EdadDesde}}">
                            </div>

                            <div class="form-group row" id="divEdadHastaEdit" style="display: none">
                                <label class="control-label">Edad final</label>
                                <input type="number" step="1" name="EdadHastaEdit" class="form-control"
                                    value="{{$tasa_diferenciada->EdadHasta}}">
                            </div>

                            <div class="form-group row">
                                <label class="control-label">Tasa</label>
                                <input type="number" name="TasaEdit" step="any" class="form-control" required
                                    value="{{$tasa_diferenciada->Tasa}}">
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
