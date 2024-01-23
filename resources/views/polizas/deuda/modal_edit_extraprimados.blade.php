<div class="modal fade" id="modal-edit-extraprimados-{{$obj->Id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/deuda/update_extraprimado') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Editar extraprimado </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">DUI</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    
                                    <input class="form-control" type="hidden" value="{{$obj->Id}}" name="Id" readonly>
                                    <input class="form-control" type="text" value="{{$obj->Dui}}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" value="{{$obj->Nombre}}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número referencia </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" value="{{$obj->NumeroReferencia}}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha Otorgamiento </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" value="{{$obj->FechaOtorgamiento}}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto Otorgado </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" value="{{$obj->MontoOtorgado}}" readonly>
                                </div>
                            </div>

                            
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tarifa</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" type="number" name="Tarifa" value="{{$obj->Tarifa}}" required >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Porcentaje EP</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control"  type="number" name="PorcentajeEP" value="{{$obj->PorcentajeEP}}" required min="0.01" max="100.00" step="0.01"   >
                                </div>
                            </div>

                            
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Pago EP</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control"  type="number"  name="PagoEP" value="{{$obj->PagoEP}}" required  step="0.01">
                                </div>
                            </div>
                         
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

