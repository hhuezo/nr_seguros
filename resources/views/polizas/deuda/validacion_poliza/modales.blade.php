<div class="modal fade" id="modal_cambio_credito_valido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Excluir crédito no válido</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Seleccione
                                credito</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select id="creditos" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer" align="center">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="agregarValidos()" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <div class="col-md-6">
                    <h4 class="modal-title" id="myModalLabel">Detalle créditos</h4>
                </div>
                <div class="col-md-6">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body" id="modal-creditos">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade bs-example-modal-lg" id="modal-primer-ingreso" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('polizas/deuda/store_poliza_primara_carga') }}/{{ $deuda->Id }}">
                @csrf
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title" id="myModalLabel">Primer carga</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                    <input type="hidden" name="MesActual" value="{{ $mesActual }}">
                    <input type="hidden" name="AxoActual" value="{{ $axoActual }}">
                    <input type="hidden" name="MesAnterior" value="{{ $mesAnterior }}">
                    <input type="hidden" name="AxoAnterior" value="{{ $axoAnterior }}">
                    <p class="fs-5 mb-0">
                        ¿Desea realizar el primer carga?
                    </p>
                </div>

                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>
