<div class="modal fade" id="modal_usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('poliza/vida/usuario_create') }}" method="POST">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo usuario - Polizas de Vida Colectivo </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" id="ModalId" name="Vida">
                            <div class="form-group row">
                                <input type="hidden" id="ModalTipoTasa">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Numero de Poliza</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Poliza" id="ModalPoliza" type="number" autofocus="true" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Numero de Usuarios</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <input class="form-control" name="NumeroUsuario" id="ModalNumeroUsuario" type="number" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Suma Asegurada</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <input class="form-control" name="SumaAsegurada" id="ModalSumaAsegurada" type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">SubTotal</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <input class="form-control" name="SubTotal" id="ModalSubTotal" type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Tasa" id="ModalTasaUsuario" type="number" step="0.01" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">TotalAsegurado</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TotalAsegurado" id="ModalTotalAsegurado" type="number" step="0.01">
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn_guardar" class="btn btn-primary">Aceptar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#ModalSumaAsegurada').change(function() {
            calculo();

        })

        $('#ModalNumeroUsuario').change(function() {
            calculo();
        })

        $('#ModalSubTotal').change(function() {
            calculo();
        })

        $('#ModalTasaUsuario').change(function() {
            calculo();
        })
        $('#ModalTotalAsegurada').change(function() {
            calculo();
        })

        function calculo() {
            if (document.getElementById('ModalTipoTasa').value == 1) { //mensual
                var tasa = (document.getElementById('ModalTasaUsuario').value / 1000);
                var usuarios = document.getElementById('ModalNumeroUsuario').value;
                document.getElementById('ModalSubTotal').value = usuarios * document.getElementById('ModalSumaAsegurada').value;
                document.getElementById('ModalTotalAsegurado').value = document.getElementById('ModalSubTotal').value * tasa;
            } else if (document.getElementById('ModalTipoTasa').value == 0) { //anual
                var tasa = (document.getElementById('ModalTasaUsuario').value / 1000) / 12;
                var usuarios = document.getElementById('ModalNumeroUsuario').value;
                document.getElementById('ModalSubTotal').value = usuarios * document.getElementById('ModalSumaAsegurada').value;
                document.getElementById('ModalTotalAsegurado').value = document.getElementById('ModalSubTotal').value * tasa;
            }

        }

    })
    $("#btn_guardar").click(function() {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Vida": document.getElementById('ModalId').value,
            "Poliza": document.getElementById('ModalPoliza').value,
            "NumeroUsuario": document.getElementById('ModalNumeroUsuario').value,
            "SumaAsegurada": document.getElementById('ModalSumaAsegurada').value,
            "SubTotalAsegurado": document.getElementById('ModalSubTotal').value,
            "Tasa": document.getElementById('ModalTasaUsuario').value,
            "TotalAsegurado": document.getElementById('ModalTotalAsegurado').value,
        };
        $.ajax({
            type: "post",
            url: "{{ url('poliza/vida/usuario_create') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#response').html(data);

                $('#modal_usuario').modal('hide');

                document.getElementById('NumeroPoliza').setAttribute('readonly', true)
            }
        })
        $('#modal_usuario').modal('hide');
        get_usuarios(document.getElementById('ModalPoliza').value);
    });
</script>
