<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-recibo-{{ $obj->Id }}">

    <form method="POST" action="{{ url('poliza/deuda/recibo', $obj->Id) }}">

        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Generar Recibo de la poliza</h4>

                    <div class="modal-body">
                        <input type="hidden" value="{{ $deuda->Id }}" name="Residencia" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Saldo a</label>
                                <input type="date" name="SaldoA" id="ModalSaldoA" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Impresión de
                                    Recibo</label>
                                <input type="date" name="ImpresionRecibo" id="ModalImpresionRecibo" value="{{ date('Y-m-d') }}" class="form-control" readonly>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Numero
                                    Correlativo</label>
                                <input type="text" class="form-control" name="NumeroCorrelativo">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Anexo</label>
                                <textarea class="form-control" rows="4" name="Anexo"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Referencia</label>
                                <input type="text" class="form-control" name="Referencia">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btn_confirmar_recibo">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{ $obj->Id }}">

    <form method="POST" action="{{ url('polizas/deuda/delete_pago', $obj->Id) }}">
        @method('POST')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Anular Registro de Cartera</h4>
                </div>
                <div class="modal-body">
                    <p>Confirme si desea anular el Registro</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </form>

</div>


<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-view-{{ $obj->Id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Comentarios Generados por el Pago</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="" class="form-labeL"><h5>Comentario</h5></label>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="form-label"><h5>Usuario</h5></label>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="form-label"><h5>Fecha Creación</h5></label>
                        </div>
                    </div>
                    <hr>
                    @foreach($comentarios as $comen)
                    @if($comen->DetalleResidencia == $obj->Id)
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="" class="form-label">{{$comen->Comentario}}</label>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="form-label">{{$comen->usuarios->name}}</label>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="form-label">{{ \Carbon\Carbon::parse($obj->FechaIngreso)->format('d/m/Y') }}</label>
                        </div>
                    </div>
                    <hr>

                    @endif
                    @endforeach
                </div>
            </div>
        
            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    
                </div>

        </div>
    </div>
</div>