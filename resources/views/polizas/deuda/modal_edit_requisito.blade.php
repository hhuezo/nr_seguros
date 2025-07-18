<div class="modal fade" id="modal-requisito-{{ $requisitoId }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ url('polizas/deuda/update_requisito') }}">
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar requisito</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                            <div class="form-group row">
                                <input type="hidden" name="Id" value="{{ $requisitoId }}">
                                <label class="control-label">Perfiles médicos</label>
                                <select name="Perfil" style="width: 100%" class="form-control select2" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($perfiles as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $perfilId == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Descripcion }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group row">
                                <dv class="col-md-6">
                                    <label class="control-label">Edad
                                        inicial</label>
                                    <input class="form-control" type="number" name="EdadInicial"
                                        value="{{ $edadInicial }}" required min="18">
                                </dv>
                                <dv class="col-md-6">
                                    <label class="control-label">Edad
                                        final</label>
                                    <input class="form-control" name="EdadFinal" value="{{ $edadFinal }}"
                                        type="number" required>
                                </dv>


                            </div>
                            <div class="form-group row">
                                <dv class="col-md-6">
                                    <label class="control-label">Monto
                                        inicial</label>
                                    <input class="form-control" step="0.01" type="number" name="MontoInicial"
                                        value="{{ $montoInicial }}" required>
                                </dv>
                                <dv class="col-md-6">
                                    <label class="control-label">Monto
                                        final</label>
                                    <input class="form-control" step="0.01" type="number" name="MontoFinal"
                                        value="{{ $montoFinal }}" required>
                                </dv>
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
