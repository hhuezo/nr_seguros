<div class="modal fade bs-example-modal-lg" id="modal-add-{{ $tipo_cartera->Id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('polizas/desempleo/create_pago') }}/{{$desempleo->Id }}" id="uploadForm{{ $tipo_cartera->Id }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo cartera</label>
                        <input type="hidden" name="PolizaVidaTipoCartera" value="{{ $tipo_cartera->Id }}">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input type="text" class="form-control"
                                value="{{ $tipo_cartera->saldos_montos->Descripcion ?? '' }}" readonly>


                                <input type="text" name="DesempleoTipoCartera" class="form-control"
                                value="{{ $tipo_cartera->Id  }}" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Axo" id="Axo{{ $tipo_cartera->Id }}" class="form-control"
                                onchange="calcularFechas('Axo{{ $tipo_cartera->Id }}', 'Mes{{ $tipo_cartera->Id }}', 'FechaInicio{{ $tipo_cartera->Id }}', 'FechaFinal{{ $tipo_cartera->Id }}','{{$tipo_cartera->Id}}')">
                                @foreach ($anios as $year => $value)
                                <option value="{{ $value }}" {{ $axo == $value ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Mes" id="Mes{{ $tipo_cartera->Id }}" class="form-control"
                                onchange="calcularFechas('Axo{{ $tipo_cartera->Id }}', 'Mes{{ $tipo_cartera->Id }}', 'FechaInicio{{ $tipo_cartera->Id }}', 'FechaFinal{{ $tipo_cartera->Id }}','{{$tipo_cartera->Id}}')">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>
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

                            <input class="form-control" type="date" name="FechaInicio"
                                id="FechaInicio{{ $tipo_cartera->Id }}" value="{{$fechaInicio}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                            final</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="FechaFinal" id="FechaFinal{{ $tipo_cartera->Id }}" value="{{$fechaFinal}}"
                                max="{{ !empty($poliza_vida->VigenciaHasta) && strtotime($poliza_vida->VigenciaHasta) ? date('Y-m-d', strtotime($poliza_vida->VigenciaHasta)) : '' }}"
                                type="date" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="Archivo" id="Archivo" type="file" required onchange="get_cartera('{{ $tipo_cartera->Id }}')">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">

                            <label class="switch">
                                <input type="checkbox" name="validacion_dui">
                                <span class="slider round"></span>
                            </label>

                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <label class="control-label" align="left">Desea omitir la validación de formato de
                                DUI?</label>
                            {{-- <input type="checkbox" class="form-control" name="validacion_dui" align="left"> --}}
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">

                            <label class="switch">
                                <input type="checkbox" name="validacion_credito">
                                <span class="slider round"></span>
                            </label>

                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <label class="control-label" align="left">Desea omitir la validacion de número de crédito?</label>
                        </div>

                    </div>
                    <div class="alert alert-info" role="alert" id="div_error{{$tipo_cartera->Id}}" style="display: none;">
                        <ul>
                            <li>Ya se tiene una cartera de este mes</li>
                        </ul>
                    </div>


                </div>

                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir Cartera</button>
                    <!-- <button type="button" class="btn btn-primary" id="submitButton-{{ $tipo_cartera->Id }}">Subir Cartera</button> -->
                </div>
            </form>




        </div>
    </div>
</div>
<script>
    document.getElementById('uploadForm{{ $tipo_cartera->Id }}').addEventListener('submit', function() {
        document.getElementById('loading-overlay').style.display = 'flex'; // Muestra el overlay de carga
    });

    function calcularFechas(axoId, mesId, fechaInicioId, fechaFinalId, Id) {
        // Obtener los valores de año y mes
        const axo = document.getElementById(axoId).value;
        const mes = document.getElementById(mesId).value;

        // Calcular la fecha de inicio (primer día del mes seleccionado)
        const fechaInicio = `${axo}-${mes.toString().padStart(2, '0')}-01`;
        document.getElementById(fechaInicioId).value = fechaInicio;

        // Calcular la fecha final (primer día del mes siguiente)
        const fechaFinal = new Date(axo, mes, 1); // Mes siguiente
        const fechaFinalFormateada = fechaFinal.toISOString().split('T')[0];
        document.getElementById(fechaFinalId).value = fechaFinalFormateada;
        get_cartera(Id);
    }

    function get_cartera(id) {
        const VidaId = document.getElementById('VidaId').value;
        // Tomamos los valores del año y mes del modal actual
        const mes = document.getElementById('Mes' + id).value;
        const axo = document.getElementById('Axo' + id).value;
        $.get("{{ url('polizas/vida/get_cartera') }}" + '/' + VidaId + '/' + mes + '/' + axo, function(data) {
            //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
            console.log('data:', data);

            if (data == 1) {
                document.getElementById('div_error' + id).style.display = 'block';
            } else {
                document.getElementById('div_error' + id).style.display = 'none';
            }

        });
    }
</script>
