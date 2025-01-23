<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-requisito-{{ $requisitoId}}">

    <form method="POST" action="{{ url('polizas/deuda/update_requisito') }}">
        @csrf
        <input type="hidden" name="Id" value="{{$requisitoId}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Eliminar Registro</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                            <label class="control-label">Perfiles médicos</label>
                            <select name="Perfil" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach ($perfiles as $obj)
                                <option value="{{ $obj->Id }}" {{$perfilId == $obj->Id ? 'selected':''}}>{{ $obj->Descripcion }}</option>
                                @endforeach
                            </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Edad
                            inicial</label>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <input class="form-control"  type="number" name="EdadInicial" value="{{$edadInicial}}" required min="18">
                        </div>


                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Edad
                            final</label>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <input class="form-control" name="EdadFinal" value="{{$edadFinal}}" type="number" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                            inicial</label>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <input class="form-control" step="0.01" type="number" name="MontoInicial" value="{{$montoInicial}}" required>
                        </div>
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                            final</label>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <input class="form-control" step="0.01" type="number" name="MontoFinal" value="{{$montoFinal}}"  required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </form>

</div>
