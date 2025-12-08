@extends ('welcome')
@section('contenido')
    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>


    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nuevo plan <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('catalogo/plan') }}" class="btn btn-info fa fa-undo " style="color: white">Atrás</a>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ url('catalogo/plan') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="x_content">
                        <br />
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label ">Nombre del plan</label>
                                <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="Producto" class="form-label">Producto</label>
                                <select id="Producto" name="Producto" class="form-control select2" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($productos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ old('Producto') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div id="divCoberturas">
                            <div class="x_title">
                                <h2>Coberturas <small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Cobertura</th>
                                        <th>Tarificación</th>
                                        <th>Descuento</th>
                                        <th>IVA</th>
                                    </tr>
                                </thead>
                                <tbody id="coberturasTBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group" align="right">
                        <button class="btn btn-success" type="submit">Guardar</button>
                    </div>
                </form>


            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            //mostrar opcion en menu
            displayOption("ul-catalogo", "li-plan");

            $("#divCoberturas").hide();
            $("#Producto").on("change", function() {
                generarRegistrosCobertura();
            });

        });

        function generarRegistrosCobertura() {
            let parametros = {
                "ProductoId": $('#Producto').val()
            };
            $.ajax({
                url: "{{ url('catalogo/plan/getCoberturas', '') }}",
                type: 'GET',
                data: parametros,
                success: function(response) {
                    //console.log(response.datosRecibidos);
                    cargarTabla(response.datosRecibidos);
                    $("#divCoberturas").show();

                },
                error: function(error) {
                    $("#divCoberturas").hide();

                    console.error(error.responseJSON.datosRecibidos);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Este Producto no contiene ninguna cobertura.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }

        function cargarTabla(datosRecibidos) {

            /// Obtener el cuerpo de la tabla
            let tablaCuerpo = $("#coberturasTBody");
            tablaCuerpo.empty();

            // Recorrer los datos y agregar filas a la tabla
            $.each(datosRecibidos, function(index, obj) {

                console.log(obj);
                let Nombre = obj.Nombre || "";
                let Tarificacion = obj.Tarificacion ?? '-';
                let Descuento = obj.Descuento ? "Si" : "No";
                let Iva = obj.Iva ? "Si" : "No";

                // Crear una nueva fila y agregar celdas
                let fila = $("<tr>");
                fila.append(`<td>${index+1}</td>`);
                fila.append(`<td>${Nombre}</td>`);
                fila.append(`<td>${Tarificacion}</td>`);
                fila.append(`<td>${Descuento}</td>`);
                fila.append(`<td>${Iva}</td>`);

                // Agregar la fila al cuerpo de la tabla
                tablaCuerpo.append(fila);

            });
        }
    </script>
@endsection
