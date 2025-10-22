<div class="modal fade" id="modal_extraprimados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/deuda/store_extraprimado') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Extraprimado </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">DUI/Documento</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                                    <input class="form-control" type="hidden" value="{{$deuda->Id}}" name="PolizaDeuda" readonly>
                                    <input class="form-control" type="text"  id="ExtraprimadosDui" name="Dui" readonly>
                                    <input class="form-control" type="hidden"  id="DeudaCarteraId" name="DeudaCarteraId" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" name="Nombre" id="ExtraprimadosNombre" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número referencia </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" name="NumeroReferencia" id="ExtraprimadosNumeroReferencia" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha Otorgamiento </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" name="FechaOtorgamiento" id="ExtraprimadosFechaOtorgamiento" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto Otorgado </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="number" name="MontoOtorgamiento" id="ExtraprimadosMontoOtorgamiento" readonly>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tarifa</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="number" step="any" name="Tarifa" readonly value="{{$deuda->Tasa}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Porcentaje EP %</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control"  type="number" name="PorcentajeEP" id="PorcentajeEP" required min="0.01"  step="0.01" onblur="totalPago({{$deuda->Tasa}})" >
                                </div>
                            </div>


                            {{-- <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Pago EP</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control"  type="number"  name="PagoEP" id="PagoEP" readonly  step="0.01">
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit"  class="btn btn-primary">Aceptar</button>
                </div>
            </form>

        </div>
    </div>
</div>

