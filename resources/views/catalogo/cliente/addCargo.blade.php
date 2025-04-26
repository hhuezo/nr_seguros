<div class="modal fade" id="modal_addCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Cargo</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">

                    <div class="x_content">
                        <br />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre *</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="Nombre" id="ModalNombreCargo" class="form-control" autofocus="true" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <label class="col-sm-3 control-label">&nbsp;</label>
                        </div>
                        <div class="row"> * Campo requerido</div>
                    </div>


                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="btn_cancelar_cargo">Cancelar</button>
                <button type="button" id="btn_guardar_cargo" class="btn btn-primary">Aceptar</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal_addMotivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Motivo de Elección</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">

                    <div class="x_content">
                        <br />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="Nombre" id="ModalNombreMotivo" class="form-control" autofocus="true">
                            </div>
                            <label class="col-sm-3 control-label">&nbsp;</label>
                        </div>

                    </div>


                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn_guardar_motivo" class="btn btn-primary">Aceptar</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal_addPreferencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Preferencia Compra</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body">

                    <div class="x_content">
                        <br />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="Nombre" id="ModalNombrePreferencia" class="form-control" autofocus="true">
                            </div>
                            <label class="col-sm-3 control-label">&nbsp;</label>
                        </div>

                    </div>


                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn_guardar_preferencia" class="btn btn-primary">Aceptar</button>
            </div>

        </div>
    </div>
</div>
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $("#btn_guardar_cargo").click(function() {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nombre": document.getElementById('ModalNombreCargo').value
        };
        $.ajax({
            type: "get",
            url: "{{ url('catalogo/cliente/addCargo') }}",
            data: parametros,
            success: function(data) {
                //console.log(data);
                //$('#response').html(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '" selected >' + data[i].Nombre +
                    '</option>';
                $("#Cargo").html(_select);
                $('#modal_addCargo').modal('hide');
            }
        })
    });
    $("#btn_cancelar_cargo").click(function() {
        $('#modal_addCargo').modal('hide');
    });

    $("#btn_guardar_motivo").click(function() {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nombre": document.getElementById('ModalNombreMotivo').value
        };
        $.ajax({
            type: "get",
            url: "{{ url('catalogo/cliente/addMotivo') }}",
            data: parametros,
            success: function(data) {
                //console.log(data);
                //$('#response').html(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '" selected >' + data[i].Nombre +
                    '</option>';
                $("#MotivoEleccion").html(_select);
                $('#modal_addMotivo').modal('hide');
            }
        })
    });


    $("#btn_guardar_preferencia").click(function() {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nombre": document.getElementById('ModalNombrePreferencia').value
        };
        $.ajax({
            type: "get",
            url: "{{ url('catalogo/cliente/addPreferencia') }}",
            data: parametros,
            success: function(data) {
                //console.log(data);
                //$('#response').html(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '" selected >' + data[i].Nombre +
                    '</option>';
                $("#PreferenciaCompra").html(_select);
                $('#modal_addPreferencia').modal('hide');
            }
        })
    });
</script>