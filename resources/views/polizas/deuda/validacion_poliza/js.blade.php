<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var loadingOverlay = document.getElementById('loading-overlay');

        // Muestra el overlay de carga cuando se hace clic en el botón
        document.querySelector('button').addEventListener('click', function() {
            loadingOverlay.style.display = 'flex'; // Cambia a 'flex' para usar flexbox
        });

        // Oculta el overlay de carga después de que la página se haya cargado completamente
        window.addEventListener('load', function() {
            loadingOverlay.style.display = 'hide';
        });


        var form = document.getElementById('miFormulario');

        form.addEventListener('submit', function() {
            loadingOverlay.style.display = 'flex'; // Muestra el overlay de carga
        });
    });
</script>

<script>
    $(document).ready(function() {
        var loadingOverlay = document.getElementById('loading-overlay');
    });
</script>






<script type="text/javascript">
    $(document).ready(function() {
        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-deuda");

        //buscar registros no validos
        loadCreditosNoValidos();

        //buscar registros con requisitos (1), rehabilitados(2), limite por linea(3)
        loadCreditosConRquisitos(1);
    });

    function excluir(id, subtotal, val) {
        let id_ex = document.getElementById('id_excluido-' + id).value;

        $.ajax({
            url: "{{ url('poliza/deuda/add_excluidos') }}",
            type: 'POST',
            data: {
                id: id,
                subtotal: subtotal,
                val: val,
                _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
            },
            success: function(response) {
                console.log(response.conteo);

                if (response.conteo == 0) {
                    $('#btnGuardarCartera').prop('disabled', false);
                } else {
                    $('#btnGuardarCartera').prop('disabled', true);
                }
            },
            error: function(error) {
                // Aquí manejas el error, si ocurre alguno durante la petición
                console.error(error);
            }
        });

    }

    function excluir_dinero(id, subtotal, val) {
        let id_ex = document.getElementById('id_excluido_dinero-' + id).value;

        $.ajax({
            url: "{{ url('poliza/deuda/add_excluidos_responsabilidad') }}",
            type: 'POST',
            data: {
                id: id,
                subtotal: subtotal,
                val: val,
                _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
            },
            success: function(response) {
                console.log(response.conteo);

                if (response.conteo == 0) {
                    $('#btnGuardarCartera').prop('disabled', false);
                } else {
                    $('#btnGuardarCartera').prop('disabled', true);
                }
            },
            error: function(error) {
                // Aquí manejas el error, si ocurre alguno durante la petición
                console.error(error);
            }
        });

    }



    function get_referencia_creditos(id, tipo_cartera_id) {
        $.ajax({
            url: "{{ url('polizas/deuda/get_referencia_creditos') }}/" + id + '/' + tipo_cartera_id,
            type: 'GET',
            success: function(response) {
                // Aquí manejas la respuesta. Por ejemplo, podrías imprimir la respuesta en la consola:
                //console.log(response);
                var _select = '<option value=""> Seleccione ... </option>';
                for (var i = 0; i < response.length; i++)
                    _select += '<option value="' + response[i].Id + '"  >' + response[i].NumeroReferencia +
                    ' <strong>($' + response[i].saldo_total + ')</strong>' +
                    '</option>';
                $("#creditos").html(_select);
            },
            error: function(error) {
                // Aquí manejas el error, si ocurre alguno durante la petición
                console.error(error);
            }
        });
    }




    function loadCreditosNoValidos() {
        var loadingOverlay = document.getElementById('loading-overlay'); // Cambiado para coincidir con el HTML


        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex'; // Mostrar overlay
        }
        $.ajax({
            url: "{{ url('polizas/deuda/get_creditos_no_validos') }}/" + '{{ $deuda->Id }}',
            type: 'GET',
            success: function(response) {
                $('#creditos_no_validos').html(response);
            },
            error: function(error) {
                console.error(error);
            },
            complete: function() {
                if (loadingOverlay) {
                    console.log("Ocultando overlay en complete");
                    loadingOverlay.style.display = 'none';
                }
            }
        });


    }



    function loadCreditosConRquisitos(tipo) {
        var loadingOverlay = document.getElementById('loading-overlay'); // Cambiado para coincidir con el HTML


        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex'; // Mostrar overlay
        }
        $.ajax({
            url: "{{ url('polizas/deuda/get_creditos_con_requisitos') }}/" + '{{ $deuda->Id }}',
            type: 'GET',
            data: {
                tipo: tipo,
            },
            success: function(response) {
                $('#creditos_validos').html(response);
            },
            error: function(error) {
                console.error(error);
            },
            complete: function() {
                if (loadingOverlay) {
                    console.log("Ocultando overlay en complete");
                    loadingOverlay.style.display = 'none';
                }
            }
        });


    }


    function loadDetalleCreditoRequisito(documento, poliza, tipo, tipo_cartera) {
        console.log(documento)
        $.ajax({
            url: "{{ url('polizas/deuda/get_creditos_detalle_requisitos') }}/" + documento + "/" + poliza +
                "/" + tipo + "/" + tipo_cartera,
            type: 'GET',
            success: function(response) {
                //console.log(response);
                $('#modal-creditos').html(response);
            },
            error: function(error) {
                // Aquí manejas el error, si ocurre alguno durante la petición
                console.error(error);
            }
        });
    }



    function registroValidado(id) {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "id": id
        };
        $.ajax({
            type: "POST",
            url: "{{ url('polizas/deuda/agregar_valido_detalle') }}",
            data: parametros,
            success: function(data) {

                // Buscar el botón por su ID
                let button = $("#cumulo-" + data.Dui);
                console.log(button, data);
                if (button.length) { // Verificar si el botón existe
                    if (data.count == 0) {
                        button.removeClass("btn-primary").addClass("btn-success");
                    } else {
                        button.removeClass("btn-success").addClass("btn-primary");
                    }
                } else {
                    console.error("Botón no encontrado con ID: cumulo-" + Dui);
                }

            }
        })
    }


    function agregarValidos() {
        var id = document.getElementById('creditos').value;
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "id": id
        };
        $.ajax({
            type: "POST",
            url: "{{ url('polizas/deuda/agregar_valido') }}",
            data: parametros,
            success: function(data) {
                $('#modal_cambio_credito_valido').modal('hide');
                //buscar registros no validos
                loadCreditosNoValidos();
            }
        })
    }


    // function agregarValidos() {
    //     var id = document.getElementById('creditos').value;

    //     var loadingOverlay = document.getElementById('loading-overlay'); // Cambiado para coincidir con el HTML

    //     if (loadingOverlay) {
    //         loadingOverlay.style.display = 'flex'; // Mostrar overlay
    //     }

    //     if (id != '') {
    //         $.ajax({
    //             url: "{{ url('polizas/deuda/agregar_valido') }}",
    //             type: 'POST',
    //             data: {
    //                 id: id,
    //                 _token: '{{ csrf_token() }}'
    //             },
    //             success: function(response) {
    //                 console.log(response);
    //                 $('#modal_cambio_credito_valido').modal('hide');
    //                 loadCreditos(1, "");
    //                 loadCreditos(2, "");
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error(error);
    //             },
    //             complete: function() {
    //                 if (loadingOverlay) {
    //                     console.log("Ocultando overlay en complete");
    //                     loadingOverlay.style.display = 'none'; // Ocultar overlay después de la solicitud
    //                 }
    //             }
    //         });

    //     } else {
    //         Swal.fire({
    //             title: 'Error!',
    //             text: 'Debe de seleccionar el credito',
    //             icon: 'error',
    //             confirmButtonText: 'Aceptar'
    //         });

    //         if (loadingOverlay) {
    //             loadingOverlay.style.display = 'none'; // Ocultar overlay si no se seleccionó un crédito
    //         }
    //     }
    // }

    // $('#btn_valido').on('click', function() {
    //     var buscar = document.getElementById('buscar_valido').value;

    //     loadCreditos(2, buscar);
    //     console.log("hola", buscar);
    // });

    // $('#btn_limpiarn_valido').on('click', function() {
    //     document.getElementById('buscar_valido').value = "";
    //     var buscar = "";

    //     loadCreditos(2, buscar);
    //     console.log("hola", buscar);
    // });
</script>
