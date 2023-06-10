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
                            <input type="text" id="ModalId" name="Vida">
                            <input type="text" id="ModalTipoTasa" name="TipoTasa">

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
<div class="modal fade" id="modal-usuario-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- <form action="{{ url('poliza/vida/usuario_edit') }}" method="POST">
                                        @csrf-->
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Editar usuario - Polizas de Vida
                        Colectivo </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="Id" id="ModalEditId" value="{{ $obj->Id }}">
                        <input type="text" id="ModalEditTipoTasa" name="TipoTasa">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Numero de Usuarios</label>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input class="form-control" name="NumeroUsuario" id="ModalEditNumeroUsuario" type="number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Suma
                                Asegurada</label>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input class="form-control" name="SumaAsegurada" id="ModalEditSumaAsegurada" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">SubTotal</label>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input class="form-control" name="SubTotal" id="ModalEditSubTotal" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa</label>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input class="form-control" name="Tasa" id="ModalEditTasaUsuario" type="number" step="0.01">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">TotalAsegurado</label>
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input class="form-control" name="TotalAsegurado" id="ModalEditTotalAsegurado" type="number" step="0.01" value="{{ $obj->TotalAsegurado }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer" align="center">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-edit-usuario" class="btn btn-primary">Aceptar</button>
            </div>
            <!--</form> -->

        </div>
    </div>
</div>




<div class="modal fade" id="modal-usuario-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar usuario - Polizas de Vida
                        Colectivo </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" id="ModalDeleteId">
                        ¿Desea eliminar el registro?
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer" align="center">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-delete-usuario" class="btn btn-primary">Aceptar</button>
            </div>

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

        $('#ModalEditSumaAsegurada').change(function() {
            calculoEdit();
        })

        $('#ModalEditNumeroUsuario').change(function() {
            calculoEdit();
        })

        $('#ModalEditSubTotal').change(function() {
            calculoEdit();
        })

        $('#ModalEditTasaUsuario').change(function() {
            calculoEdit();
        })
        $('#ModalEditTotalAsegurada').change(function() {
            calculoEdit();
        })

        function calculoEdit() {
            if (document.getElementById('ModalEditTipoTasa').value == 1) { //mensual
                var tasa = (document.getElementById('ModalEditTasaUsuario').value / 1000);
                var usuarios = document.getElementById('ModalEditNumeroUsuario').value;
                document.getElementById('ModalEditSubTotal').value = usuarios * document.getElementById('ModalEditSumaAsegurada').value;
                document.getElementById('ModalEditTotalAsegurado').value = document.getElementById('ModalEditSubTotal').value * tasa;
            } else if (document.getElementById('ModalEditTipoTasa').value == 0) { //anual
                var tasa = (document.getElementById('ModalEditTasaUsuario').value / 1000) / 12;
                var usuarios = document.getElementById('ModalEditNumeroUsuario').value;
                document.getElementById('ModalEditSubTotal').value = usuarios * document.getElementById('ModalEditSumaAsegurada').value;
                document.getElementById('ModalEditTotalAsegurado').value = document.getElementById('ModalEditSubTotal').value * tasa;
            }

        }

    })
    $("#btn_guardar").click(function() {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Vida": document.getElementById('ModalId').value,
            "NumeroUsuario": document.getElementById('ModalNumeroUsuario').value,
            "SumaAsegurada": document.getElementById('ModalSumaAsegurada').value,
            "SubTotalAsegurado": document.getElementById('ModalSubTotal').value,
            "Tasa": document.getElementById('ModalTasaUsuario').value,
            "TotalAsegurado": document.getElementById('ModalTotalAsegurado').value,
        };
        $.ajax({
            type: "get",
            url: "{{ url('poliza/vida/usuario_create') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#response').html(data);

                $('#modal_usuario').modal('hide');

            }
        })
        $('#modal_usuario').modal('hide');
        get_usuarios(document.getElementById('ModalPoliza').value);
    });

    $("#btn-edit-usuario").click(function() {

        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Id": document.getElementById('ModalEditId').value,
            "NumeroUsuario": document.getElementById('ModalEditNumeroUsuario').value,
            "SumaAsegurada": document.getElementById('ModalEditSumaAsegurada').value,
            "SubTotalAsegurado": document.getElementById('ModalEditSubTotal').value,
            "Tasa": document.getElementById('ModalEditTasaUsuario').value,
            "TotalAsegurado": document.getElementById('ModalEditTotalAsegurado').value,
        };
        $.ajax({
            type: "post",
            url: "{{ url('poliza/vida/usuario_edit') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#response').html(data);
                $('#modal-usuario-edit').modal('hide');
            }
        })
        $('#modal_usuario').modal('hide');
        get_usuarios(document.getElementById('NumeroPoliza').value);
    });
    $("#btn-delete-usuario").click(function() {

        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Id": document.getElementById('ModalDeleteId').value,
        };
        $.ajax({
            type: "post",
            url: "{{ url('poliza/vida/usuario_delete') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#response').html(data);
                $('#modal-usuario-delete').modal('hide');
            }
        })
        get_usuarios(document.getElementById('NumeroPoliza').value);
    });
</script>