{{-- modales contactos --}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-contacto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/add_contacto') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nuevo Contacto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding-bottom: 15px;">
                            <div class="col-md-6">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <label for="Nombre" class="form-label">Nombre *</label>
                                <input type="text" name="Nombre" required class="form-control"
                                    oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                            </div>


                            <div class="col-md-6">
                                <label for="Telefono" class="form-label">Teléfono </label>
                                <input type="text" name="Telefono" data-inputmask="'mask': ['9999-9999']" data-mask
                                    class="form-control">
                            </div>

                        </div>

                        <div class="row" style="padding-bottom: 15px;">

                            <div class="col-md-6">
                                <label for="Email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="Email">
                            </div>
                            <div class="col-md-6">
                                <label for="LugarTrabajo" class="form-label">Lugar de Trabajo</label>
                                <input type="text" class="form-control" name="LugarTrabajo"
                                    oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                            </div>

                        </div>

                        <div class="row" style="padding-bottom: 15px;">
                            <div class="col-md-12">
                                <label for="Email" class="form-label">Cargo</label>
                                <div class="input-group">
                                    <select name="Cargo" id="Cargo" class="form-control">
                                        @foreach ($cliente_contacto_cargos as $cargo)
                                            <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                        @endforeach

                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" data-target="#modal_addCargo" data-toggle="modal" >+</button>
                                    </span>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>

<div class="col-12">
    <div class="modal fade modal-edit-contacto" tabindex="-1" role="dialog" aria-hidden="true"
        id="modal-edit-contacto">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/edit_contacto') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar contacto</h4>
                        <input type="hidden" name="Id" id="ModalContactoId" class="form-control" required>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-6">
                                Nombre *
                                <input type="text" name="Nombre" id="ModalContactoNombre" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Cargo *
                                <select name="Cargo" id="ModalContactoCargo" class="form-control"
                                    required>
                                    @foreach ($cliente_contacto_cargos as $cargo)
                                        <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Teléfono *
                                <input type="text" name="Telefono" id="ModalContactoTelefono"
                                    data-inputmask="'mask': ['9999-9999']" data-mask class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                Email *
                                <input type="email" name="Email" id="ModalContactoEmail" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Lugar Trabajo *
                                <input type="text" name="LugarTrabajo" id="ModalContactoLugarTrabajo" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group"> * Campo requerido</div>



                    </div>
                    <div>&nbsp; </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>

<div class="col-12">
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
        id="modal-delete-contacto">

        <form method="POST" action="{{ url('catalogo/cliente/delete_contacto') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <input type="hidden" name="Id" id="IdContacto">
                        <h4 class="modal-title">Eliminar Registro</h4>
                    </div>
                    <div class="modal-body">
                        <p>Confirme si desea Eliminar el Registro</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_addCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
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
                        <div class="form-group">
                            <label class="control-label">Nombre *</label>
                                <input type="text" name="Nombre" id="ModalNombreCargo" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" class="form-control" autofocus="true">
                        </div>
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
            },
            error: function(xhr, status, error) {
                if (xhr.status === 500) {
                      toastr.error('Error al guardar el registro');
                } else {
                    console.error('Error:', status, error);
                }
            }
        })
    });
    $("#btn_cancelar_cargo").click(function() {
        $('#modal_addCargo').modal('hide');
    });


</script>

