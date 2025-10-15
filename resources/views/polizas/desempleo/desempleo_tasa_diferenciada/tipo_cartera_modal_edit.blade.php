    <div class="modal fade" id="modal-tipo-cartera-edit-{{ $tipo->Id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('polizas/desempleo/update_tipo_cartera') }}/{{ $desempleo->Id }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="VidaTipoCartera" value="{{ $tipo->Id }}">
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Tipo de cartera</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Tipo de Cartera</label>
                                    <select class="form-control" name="TipoCartera">
                                        @foreach ($saldos_montos as $obj)
                                            <option value="{{ $obj->Id }}"
                                                {{ $obj->Id == $tipo->SaldosMontos ? 'selected' : '' }}>{{$obj->Abreviatura}} - {{ $obj->Descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
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
