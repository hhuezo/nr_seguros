<div class="modal fade" id="modal_addMotivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
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
                        <div class="form-group">
                            <label class="control-label">Motivo</label>
                            <input type="text" name="Nombre" id="ModalNombreMotivo" class="form-control"
                                oninput="this.value = this.value.toUpperCase()">

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
<div class="modal fade" id="modal_addPreferencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
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

                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" name="Nombre" id="ModalNombrePreferencia" class="form-control"
                                oninput="this.value = this.value.toUpperCase()">
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

<script>
    $("#btn_guardar_motivo").click(function() {
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nombre": document.getElementById('ModalNombreMotivo').value
        };

        $.ajax({
            type: "GET", // Recomendación: Cambiar a POST para operaciones que modifican datos
            url: "{{ url('catalogo/cliente/addMotivo') }}",
            data: parametros,
            success: function(data) {
                var _select = '';
                for (var i = 0; i < data.length; i++) {
                    _select += '<option value="' + data[i].Id + '" selected>' + data[i].Nombre +
                        '</option>';
                }
                $("#MotivoEleccion").html(_select);
                $('#modal_addMotivo').modal('hide');
            },
            error: function(xhr, status, error) {
                if (xhr.status === 500) {
                    // console.error('Error del servidor:', xhr.responseJSON ? xhr.responseJSON
                    //     .message : xhr.responseText);
                    // Opcional: Mostrar mensaje al usuario
                    toastr.error('Error al guardar el registro');
                } else {
                    console.error('Error:', status, error);
                }
            }
        });
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
            },
            error: function(xhr, status, error) {
                if (xhr.status === 500) {
                    // console.error('Error del servidor:', xhr.responseJSON ? xhr.responseJSON
                    //     .message : xhr.responseText);
                    // Opcional: Mostrar mensaje al usuario
                    toastr.error('Error al guardar el registro');
                } else {
                    console.error('Error:', status, error);
                }
            }
        })
    });
</script>
