<div class="modal fade" id="modal_informacion_negocio_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="ContactoNegocioFormEdit" method="POST">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Registro de Información de Negocio </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" id="IndexEdit">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Contacto </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="ContactoEdit" id="ContactoEdit"
                                        value="{{ old('Contacto') }}" class="form-control" required autofocus="true"  maxlength="100">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Descripción de la operación </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="DescripcionOperacionEdit" id="DescripcionOperacionEdit"
                                        value="{{ old('DescripcionOperacion') }}" class="form-control" required
                                        autofocus="true"  maxlength="200">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Telefono de contacto</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="TelefonoContactoEdit" id="TelefonoContactoEdit"
                                        value="{{ old('TelefonoContacto') }}" class="form-control" required
                                        autofocus="true" data-inputmask="'mask': ['9999-9999']">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Observaciones</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="ObservacionContactoEdit" id="ObservacionContactoEdit" rows="3" class="form-control" maxlength="500"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <br>
                <div class="clearfix"></div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn_negocio_edit" class="btn btn-primary">Aceptar</button>
                </div>
            </form>

        </div>
    </div>
</div>



<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $("#btn_negocio_edit").click(function() {

        // Obtener el índice del contacto que se está editando
        const indexEdit = $("#IndexEdit").val();

        // Obtener los contactos existentes (si los hay) o crear un nuevo array vacío
        let negocioContactos = JSON.parse(localStorage.getItem("negocioContactos")) || [];


        // Actualizar los datos del contacto en el array
        negocioContactos[indexEdit].Contacto = $("#ContactoEdit").val();
        negocioContactos[indexEdit].DescripcionOperacion = $("#DescripcionOperacionEdit").val();
        negocioContactos[indexEdit].TelefonoContacto = $("#TelefonoContactoEdit").val();
        negocioContactos[indexEdit].ObservacionContacto = $("#ObservacionContactoEdit").val();


        // Almacenar el array actualizado en localStorage
        localStorage.setItem("negocioContactos", JSON.stringify(negocioContactos));

        $("#IndexEdit").val("");
        $("#ContactooEdit").val("");
        $("#DescripcionOperacionoEdit").val("");
        $("#TelefonoContactooEdit").val("");
        $("#ObservacionContactooEdit").val("");
        negocioContactos = JSON.parse(localStorage.getItem("negocioContactos")) || [];

        cargarTabla(negocioContactos);
        // Asigna los datos al campo oculto
        $("#datos_localstorage").val(JSON.stringify(negocioContactos));

        $('#modal_informacion_negocio_edit').modal('hide');

        Swal.fire({
            title: '¡Éxito!',
            text: 'Información de negocio ha sido editada',
            icon: 'success',
            confirmButtonText: 'Aceptar',
            timer: 3500
        })

    });
</script>
