<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-delete-{{ $obj->Id }}">

    <form method="POST" action="{{ route('negocio.destroy', $obj->Id) }}">
        @method('delete')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Eliminar Registro</h4>
                </div>
                <div class="modal-body">
                    <p>Confirme si desea Eliminar el Registro</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </form>

</div>
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"  id="modal-show-{{ $obj->Id }}">

 
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <textarea name="" id="" cols="30" rows="3" class="form-control" readonly>{{$obj->Observacion}}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    
                </div>
            </div>
        </div>


</div>
