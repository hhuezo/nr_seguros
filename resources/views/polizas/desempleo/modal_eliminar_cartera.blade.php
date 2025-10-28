<div class="modal fade bs-example-modal-lg" id="modal-delete-{{ $tipo->PolizaDesempleoTipoCartera }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar archivo Excel </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('polizas/desempleo/eliminar_pago') }}/{{ $desempleo->Id }}"
                id="deleteForm{{ $tipo->PolizaDesempleoTipoCartera }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo cartera</label>
                        <input type="hidden" name="PolizaVidaTipoCartera" value="{{ $tipo->PolizaDesempleoTipoCartera }}">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input type="text" class="form-control"
                                value="{{ $tipo_cartera->saldos_montos->Descripcion ?? '' }}" readonly>



                        </div>

                    </div> --}}
                    <h6>¿Esta seguro que desea eliminar la cartera?</h6>

                     <input type="hidden" name="DesempleoTipoCartera" class="form-control"
                                value="{{ $tipo->PolizaDesempleoTipoCartera }}" readonly>



                </div>

                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Eliminar Cartera</button>
                    <!-- <button type="button" class="btn btn-primary" id="submitButton-{{ $tipo->PolizaDesempleoTipoCartera }}">Subir Cartera</button> -->
                </div>
            </form>




        </div>
    </div>
</div>

