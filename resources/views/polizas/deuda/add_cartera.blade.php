<div class="modal fade bs-example-modal-lg" id="modal-add-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  action="{{ url('polizas/deuda/create_pago') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Linea de
                            Credito</label>
                        <input type="hidden" name="LineaCredito" value="{{$obj->Id}}">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" value="{{$obj->saldos->Descripcion}}" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Axo" class="form-control">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                <option value="{{ $i }}"> {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Mes" class="form-control">
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
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
                            <input class="form-control" name="Id" value="{{ $deuda->Id }}" type="hidden" required>
                            <input class="form-control" type="date" name="FechaInicio" value="{{ $ultimo_pago ? date('Y-m-d', strtotime($ultimo_pago->FechaFinal)) : date('Y-m-d', strtotime($primerDia)) }}" {{ $ultimo_pago ? 'readonly' : '' }} required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                            final</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="FechaFinal" value="{{ $ultimo_pago_fecha_final ? $ultimo_pago_fecha_final : date('Y-m-d', strtotime($ultimoDia)) }}" type="date" required>
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
                    <!-- <button type="button" class="btn btn-primary" id="submitButton-{{$obj->Id}}">Subir Cartera</button> -->
                </div>
            </form>

            <div id="loading-indicator" style="text-align: center; display:none">
                <img src="{{ asset('img/ajax-loader.gif') }}">
                <br>
            </div>


        </div>
    </div>
</div>
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var loadingOverlay = document.getElementById('loading-overlay');
        var id = document.getElementById('LineaCredito').value;
        var submitButton = document.getElementById('submitButton-'+id);
        var myForm = document.getElementById('myForm');

        submitButton.addEventListener('click', function(event) {
            alert('holi');
            event.preventDefault(); // Evita que el formulario se envíe automáticamente

            loadingOverlay.style.display = 'flex'; // Cambia a 'flex' para usar flexbox

            // Validación del formulario
            // if (document.getElementById('LineaCredito_Subir').value === '') {
            //     Swal.fire('Debe seleccionar una línea de crédito');
            //     loadingOverlay.style.display = 'none'; // Oculta el overlay en caso de error
            //     return;
            // } else
             if (document.getElementById('Archivo').value === '') {
                Swal.fire('Debe seleccionar un archivo');
                loadingOverlay.style.display = 'none'; // Oculta el overlay en caso de error
                return;
            }
            myForm.submit();

        });
    });
</script>