<div class="modal fade" id="modal_informacion_negocio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="ContactoNegocioForm" method="POST">
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

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Contacto </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Contacto" id="Contacto" value="{{ old('Contacto') }}"
                                        class="form-control" required autofocus="true" maxlength="100">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Descripción de la operación </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="DescripcionOperacion" id="DescripcionOperacion"
                                        value="{{ old('DescripcionOperacion') }}" class="form-control" required
                                        autofocus="true" maxlength="200">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Telefono de contacto</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="TelefonoContacto" id="TelefonoContacto"
                                        value="{{ old('TelefonoContacto') }}" class="form-control" required
                                        autofocus="true"  data-inputmask="'mask': ['9999-9999']">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    style="text-align: left;">Observaciones</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="ObservacionContacto" id="ObservacionContacto" rows="3" class="form-control" maxlength="500"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <br>
                <div class="clearfix"></div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn_negocio" class="btn btn-primary">Aceptar</button>
                </div>
            </form>

        </div>
    </div>
</div>



<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    negocioContactos = JSON.parse(localStorage.getItem("negocioContactos")) || [];
    //console.log(negocioContactos);
    cargarTabla(negocioContactos);
    // Asigna los datos al campo oculto
    $("#datos_localstorage").val(JSON.stringify(negocioContactos));

    $("#btn_negocio").click(function() {

        // Crear un objeto para el contacto
        const contactoForm = {
            Contacto: $("#Contacto").val(),
            DescripcionOperacion: $("#DescripcionOperacion").val(),
            TelefonoContacto: $("#TelefonoContacto").val(),
            ObservacionContacto: $("#ObservacionContacto").val()
        };

        // Obtener los contactos existentes (si los hay) o crear un nuevo array vacío
        let negocioContactos = JSON.parse(localStorage.getItem("negocioContactos")) || [];

        // Agregar el nuevo contacto al array
        negocioContactos.push(contactoForm);

        // Almacenar el array actualizado en localStorage
        localStorage.setItem("negocioContactos", JSON.stringify(negocioContactos));

        $("#Contacto").val("");
        $("#DescripcionOperacion").val("");
        $("#TelefonoContacto").val("");
        $("#ObservacionContacto").val("");
        negocioContactos = JSON.parse(localStorage.getItem("negocioContactos")) || [];

        cargarTabla(negocioContactos);
        // Asigna los datos al campo oculto
        $("#datos_localstorage").val(JSON.stringify(negocioContactos));

        $('#modal_informacion_negocio').modal('hide');

        Swal.fire({
            title: '¡Éxito!',
            text: 'Información de negocio registrada',
            icon: 'success',
            confirmButtonText: 'Aceptar',
            timer: 3500
        })

    });

    function cargarTabla(negocioContactos) {
        /// Obtener el cuerpo de la tabla
        let tablaCuerpo = $("#tablaCuerpo");
        tablaCuerpo.empty();

        // Recorrer los datos y agregar filas a la tabla
        $.each(negocioContactos, function(index, obj) {
            let contacto = obj.Contacto || "";
            let descripcion = obj.DescripcionOperacion || "";
            let telefono = obj.TelefonoContacto || "";
            let observacion = obj.ObservacionContacto || "";


            // Crear una nueva fila y agregar celdas
            let fila = $("<tr>");
            fila.append(`<td>${contacto}</td>`);
            fila.append(`<td>${descripcion}</td>`);
            fila.append(`<td>${telefono}</td>`);
            fila.append(`<td>${observacion}</td>`);

            //botón eliminar
            let botonEliminar = $(
                "<button type='button' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-lg'></i></button>"
            );
            botonEliminar.click(function() {
                confirmarEliminar(index);
            });

            fila.append($("<td>").append(botonEliminar));

            //botón botón editar
            let botonEditar = $(
                "<button type='button' class='btn btn-warning btn-sm'><i class='fa fa-pencil fa-lg'></i></button>"
            );
            botonEditar.click(function() {
                showEditContactos(index, obj);
            });

            fila.append($("<td>").append(botonEditar));

            // Agregar la fila al cuerpo de la tabla
            tablaCuerpo.append(fila);

            // Asigna los datos al campo oculto
            //$("#datos_localstorage").val(JSON.stringify(negocioContactos)); cuando se elimina el ultimo registro, ya no se actualiza el campo oculto... se debe de sacar de esta función para que funcione

        });
    }

    function confirmarEliminar(index) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let negocioContactos = JSON.parse(localStorage.getItem("negocioContactos")) || [];

                if (negocioContactos[index]) {
                    negocioContactos.splice(index, 1); // Elimina el elemento del array
                    localStorage.setItem("negocioContactos", JSON.stringify(negocioContactos));
                    negocioContactos = JSON.parse(localStorage.getItem("negocioContactos")) || [];
                    // Actualiza la tabla
                    cargarTabla(negocioContactos);
                    // Asigna los datos al campo oculto
                    $("#datos_localstorage").val(JSON.stringify(negocioContactos));
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Información de negocio eliminada',
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        timer: 3500
                    })
                }
            }
        });
    }

    function showEditContactos(index, obj) {
        $("#IndexEdit").val(index);
        $("#ContactoEdit").val(obj.Contacto);
        $("#DescripcionOperacionEdit").val(obj.DescripcionOperacion);
        $("#TelefonoContactoEdit").val(obj.TelefonoContacto);
        $("#ObservacionContactoEdit").val(obj.ObservacionContacto);
        $('#modal_informacion_negocio_edit').modal('show');
    }
</script>
