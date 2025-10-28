<div class="modal fade bs-example-modal-lg" id="modal-delete-{{ $obj->Id }}" tabindex="-1" role="dialog"
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
            <form action="{{ url('polizas/deuda/eliminar_pago') }}/{{ $deuda->Id }}"
                id="deleteForm{{ $obj->Id }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="PolizaDeudaTipoCartera" value="{{ $obj->Id }}">

                    <h6>¿Esta seguro que desea eliminar la cartera?</h6>


                </div>

                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Eliminar Cartera</button>

                </div>
            </form>




        </div>
    </div>
</div>

