<div class="modal fade bs-example-modal-lg" id="modal-add-fede-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel Fedecredito</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  action="{{ url('polizas/vida/fede/create_pago') }}"  id="uploadForm{{$obj->Id}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Linea de
                            Credito..</label>
                        <input type="hidden" name="PolizaDeudaTipoCartera" value="{{ $obj->Id }}">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" value="{{$obj->catalogo_tipo_cartera->Nombre ?? ''}}" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Axo" id="AxoFede{{ $obj->Id }}" class="form-control" onchange="calcularFechas('AxoFede{{ $obj->Id }}', 'MesFede{{ $obj->Id }}', 'FechaInicioFede{{ $obj->Id }}', 'FechaFinalFede{{ $obj->Id }}')">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                <option value="{{ $i }}"> {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Mes" id="MesFede{{ $obj->Id }}" class="form-control" onchange="calcularFechas('AxoFede{{ $obj->Id }}', 'MesFede{{ $obj->Id }}', 'FechaInicioFede{{ $obj->Id }}', 'FechaFinalFede{{ $obj->Id }}')">
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>
                                    {{ $meses[$i] }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                            inicio</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="Id" value="{{ $poliza_vida->Id }}" type="hidden" required>
                            <input class="form-control" type="date" name="FechaInicio" id="FechaInicioFede{{ $obj->Id }}"  required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                            final</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="FechaFinal" id="FechaFinalFede{{ $obj->Id }}" type="date" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="Archivo" id="Archivo" type="file" required>
                        </div>
                    </div>


                </div>

                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir Cartera</button>
                    <!-- <button type="button" class="btn btn-primary" id="submitButton-{{$obj->Id}}">Subir Cartera</button> -->
                </div>
            </form>




        </div>
    </div>
</div>
<script>
    document.getElementById('uploadForm{{$obj->Id}}').addEventListener('submit', function() {
        document.getElementById('loading-overlay').style.display = 'flex'; // Muestra el overlay de carga
    });

</script>
