<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-edit-{{ $obj->Id }}">
    <form method="POST" action="{{ route('fechasferiadas.update', $obj->Id) }}">
        @method('PUT')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Editar Fecha Feriada</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">


                    <div class="form-group row">
                        <label class="control-label ">Fecha Inicio</label>
                        <input type="date" name="FechaInicio" value="{{ date('Y-m-d', strtotime($obj->FechaInicio)) }}" class="form-control">
                    </div>
                    <div class="form-group row">
                        <label class="control-label ">Fecha Final</label>
                        <input type="date" name="FechaFinal" value="{{ date('Y-m-d', strtotime($obj->FechaFinal)) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Descripción</label>
                        <input type="text" name="Descripcion" class="form-control" value="{{ $obj->Descripcion }}" required
                            autofocus="true" oninput="this.value = this.value.toUpperCase()">
                    </div>


                    &nbsp;
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
    </form>

</div>
