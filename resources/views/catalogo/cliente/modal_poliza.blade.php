<div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('catalogo/cliente_create') }}" method="POST">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo cliente</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    persona</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoPersona" id="ModalTipoPersona" class="form-control">
                                        <option value="1">Natural</option>
                                        <option value="2">Jurídica</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dui</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Dui" id="ModalDui" data-inputmask="'mask': ['99999999-9']" data-mask type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                               
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Homologado</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <input name="Homologado" id="Homologado" type="checkbox" class="js-switch" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">NIT</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nit" id="ModalNit" data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nombre" id="ModalNombre" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionResidencia" id="ModalDireccionResidencia"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    correspondecia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionCorrespondencia" id="ModalDireccionCorrespondencia"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoResidencia" id="ModalTelefonoResidencia" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    oficina</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoOficina" id="ModalTelefonoOficina" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    celular</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoCelular" id="ModalTelefonoCelular" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo
                                    electrónico</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Correo" id="ModalCorreo" type="email">
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ruta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Ruta" id="ModalRuta" class="form-control select2" style="width: 100%">
                                        @foreach ($rutas as $obj)
                                        <option value="{{ $obj->id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Responsable
                                    pago</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="ResponsablePago" id="ModalResponsablePago" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    contribuyente</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoContribuyente" id="ModalTipoContribuyente" class="form-control" style="width: 100%">
                                        @foreach ($tipos_contribuyente as $obj)
                                        <option value="{{ $obj->id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ubicación de
                                    cobro</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="UbicacionCobro" id="ModalUbicacionCobro" class="form-control" style="width: 100%">
                                        @foreach ($ubicaciones_cobro as $obj)
                                        <option value="{{ $obj->id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Contacto</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Contacto" id="ModalContacto" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Referencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Referencia" id="ModalReferencia" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número
                                    tarjeta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="NumeroTarjeta" id="ModalNumeroTarjeta" data-inputmask="'mask': ['9999-9999-9999-9999']" data-mask type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    vencimiento</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="FechaVencimiento" id="ModalFechaVencimiento" data-inputmask="'mask': ['99/99']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Género</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Genero" id="ModalGenero" class="form-control">
                                        <option value="1">Masculino</option>
                                        <option value="2">Femenino</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
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
        $("#Homologado").change(function() {
            if (document.getElementById('Homologado').checked == true) {
                document.getElementById('ModalNit').setAttribute("readonly", true);
                document.getElementById('ModalNit').removeAttribute("data-inputmask");
                document.getElementById('ModalNit').removeAttribute("data-mask");
                document.getElementById('ModalNit').value = document.getElementById('ModalDui').value;
                

            }
        })
        $("#ModalDui").change(function(){
            if (document.getElementById('Homologado').checked == true) {
                document.getElementById('ModalNit').removeAttribute("data-inputmask");
                document.getElementById('ModalNit').removeAttribute("data-mask");
                document.getElementById('ModalNit').value = document.getElementById('ModalDui').value;
                

            }
        })

    })
    $("#btn_guardar").click(function() {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nit": document.getElementById('ModalNit').value,
            "Dui": document.getElementById('ModalDui').value,
            "Nombre": document.getElementById('ModalNombre').value,
            "DireccionResidencia": document.getElementById('ModalDireccionResidencia').value,
            "DireccionCorrespondencia": document.getElementById('ModalDireccionCorrespondencia').value,
            "TelefonoResidencia": document.getElementById('ModalTelefonoResidencia').value,
            "TelefonoOficina": document.getElementById('ModalTelefonoOficina').value,
            "TelefonoCelular": document.getElementById('ModalTelefonoCelular').value,
            "Correo": document.getElementById('ModalCorreo').value,

            "Ruta": document.getElementById('ModalRuta').value,
            "ResponsablePago": document.getElementById('ModalResponsablePago').value,
            "TipoContribuyente": document.getElementById('ModalTipoContribuyente').value,
            "UbicacionCobro": document.getElementById('ModalUbicacionCobro').value,
            "Contacto": document.getElementById('ModalContacto').value,
            "Referencia": document.getElementById('ModalReferencia').value,
            "NumeroTarjeta": document.getElementById('ModalNumeroTarjeta').value,
            "FechaVencimiento": document.getElementById('ModalFechaVencimiento').value,
            "Genero": document.getElementById('ModalGenero').value,
            "TipoPersona": document.getElementById('ModalTipoPersona').value,
        };
        $.ajax({
            type: "get",
            url: "{{ url('catalogo/cliente_create') }}",
            data: parametros,
            success: function(data) {
                //console.log(data);
                //$('#response').html(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '" selected >' + data[i].Nombre +
                    '</option>';
                $("#Asegurado").html(_select);
                $('#modal_cliente').modal('hide');
            }
        })
    });
</script>