<div class="modal fade bs-example-modal-lg" id="modal-add-{{ $obj->Id }}" tabindex="-1" role="dialog"
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
            <form action="{{ url('polizas/deuda/create_pago') }}" id="uploadForm{{ $obj->Id }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Linea de
                            Credito</label>
                        <input type="hidden" name="LineaCredito" value="{{ $obj->Id }}">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" value="{{ $obj->saldos->Descripcion }}" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Axo" id="Axo{{ $obj->Id }}" class="form-control" onchange="calcularFechas('Axo{{ $obj->Id }}', 'Mes{{ $obj->Id }}', 'FechaInicio{{ $obj->Id }}', 'FechaFinal{{ $obj->Id }}')">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                    <option value="{{ $i }}" {{ $axo == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Mes" id="Mes{{ $obj->Id }}" class="form-control"  onchange="calcularFechas('Axo{{ $obj->Id }}', 'Mes{{ $obj->Id }}', 'FechaInicio{{ $obj->Id }}', 'FechaFinal{{ $obj->Id }}')">
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
                            <input class="form-control" name="Id" value="{{ $deuda->Id }}" type="hidden"
                                required>
                            <input class="form-control" type="date" name="FechaInicio" id="FechaInicio{{ $obj->Id }}" value="{{ $fecha_inicial }}"
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                            final</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="FechaFinal" id="FechaFinal{{ $obj->Id }}" value="{{ $fecha_final }}"
                                max="{{ !empty($deuda->VigenciaHasta) && strtotime($deuda->VigenciaHasta) ? date('Y-m-d', strtotime($deuda->VigenciaHasta)) : '' }}"
                                type="date" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="Archivo" id="Archivo" type="file" required>
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

                </div>

                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir Cartera</button>
                    <!-- <button type="button" class="btn btn-primary" id="submitButton-{{ $obj->Id }}">Subir Cartera</button> -->
                </div>
            </form>




        </div>
    </div>
</div>
<script>
    document.getElementById('uploadForm{{ $obj->Id }}').addEventListener('submit', function() {
        document.getElementById('loading-overlay').style.display = 'flex'; // Muestra el overlay de carga
    });

    function calcularFechas(axoId, mesId, fechaInicioId, fechaFinalId) {
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
    }
</script>
