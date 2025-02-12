@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <style>
        /* Estilo para el cuadro que contiene los campos */
        .campo-container {
            border: 1px solid #333;
            padding: 10px;
            border-radius: 10px;
        }

        /* Estilo para los campos individuales */
        .campo {
            margin-bottom: 10px;
        }

        /* Estilo para el título "Ruta de Cobro" */
        .titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Registro de Cliente <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('catalogo/cliente') }}" class="btn btn-info fa fa-undo" style="color: white"> Atrás</a>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div id="error-messages" class="alert alert-danger"
                    style="display: {{ count($errors) > 0 ? 'block' : 'none' }}">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>


                <div class="x_content">
                    <form action="{{ url('catalogo/cliente') }}" method="POST" id="myform">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <!-- Columna Izquierda (6 unidades) -->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="TipoPersona" class="form-label">Tipo Persona *</label>
                                        <select name="TipoPersona" id="TipoPersona"
                                            onchange="validaciones.cboTipoPersona(this.value)" required
                                            class="form-control">
                                            <option value="1" {{ old('TipoPersona') == 1 ? 'selected' : '' }}>Natural
                                            </option>
                                            <option value="2" {{ old('TipoPersona') == 2 ? 'selected' : '' }}>Jurídica
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Nombre" class="form-label">NIT *</label>
                                        <input class="form-control" name="Nit" id="Nit"
                                            value="{{ old('Nit') }}" data-inputmask="'mask': ['9999-999999-999-9']"
                                            data-mask type="text" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="Nombre" class="form-label">DUI </label>
                                                <input class="form-control" name="Dui" id="Dui"
                                                    value="{{ old('Dui') }}" data-inputmask="'mask': ['99999999-9']"
                                                    data-mask type="text">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row" id="Homolo">
                                                    <label for="Nombre" class="form-label">¿Homologado?</label><br>
                                                    <input name="Homologado" id="Homologado" type="checkbox"
                                                        onchange="validaciones.cambiarEstado()" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Nombre" class="form-label">Registro Fiscal </label>
                                        <input class="form-control" name="RegistroFiscal" id="RegistroFiscal" required
                                            value="{{ old('RegistroFiscal') }}" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="Nombre" class="form-label">Nombre o Razón Social *</label>
                                        <input class="form-control" id="Nombre" name="Nombre"
                                            value="{{ old('Nombre') }}" type="text" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="FechaNacimiento" class="form-label">Fecha de Nacimiento ó Fundación
                                            *</label>
                                        <input class="form-control" name="FechaNacimiento" id="FechaNacimiento" required
                                            value="{{ old('FechaNacimiento') }}" type="date">
                                    </div>
                                    <div class="form-group">
                                        <label for="FechaNacimiento" class="form-label">Edad *</label>
                                        <input class="form-control" id="EdadCalculada" value="" type="text"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="Genero" class="form-label">Estado Familiar</label>
                                        <select class="form-control" name="EstadoFamiliar" id="EstadoFamiliar" required>
                                            <option value="" selected disabled>Seleccione ...</option>
                                            <option value="0" {{ old('TipoPersona') == 0 ? 'selected' : '' }}>No
                                                Aplica
                                            </option>
                                            <option value="1" {{ old('TipoPersona') == 1 ? 'selected' : '' }}>Soltero
                                            </option>
                                            <option value="2" {{ old('TipoPersona') == 2 ? 'selected' : '' }}>Casado
                                            </option>
                                            <option value="3" {{ old('TipoPersona') == 3 ? 'selected' : '' }}>
                                                Divorciado
                                            </option>
                                            <option value="4" {{ old('TipoPersona') == 4 ? 'selected' : '' }}>Viudo
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="NumeroDependientes" class="form-label">Número Dependientes</label>
                                        <input class="form-control" name="NumeroDependientes" id="NumeroDependientes"
                                            value="{{ old('NumeroDependientes') }}" min="0" type="number"
                                            style="display: block;">
                                    </div>
                                    <div class="form-group">
                                        <label for="Genero" class="form-label">Ocupación</label>
                                        <input class="form-control" id="Ocupacion" name="Ocupacion"
                                            value="{{ old('Ocupacion') }}" required type="text">
                                    </div>
                                    <div class="form-group" style="padding-bottom: 38px;">

                                    </div>
                                    <div class="form-group">
                                        <label for="DireccionResidencia" class="form-label">Dirección Residencia</label>
                                        <textarea class="form-control" name="DireccionResidencia" id="DireccionResidencia">{{ old('DireccionResidencia') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="DireccionResidencia" class="form-label">Dirección Correspondencia
                                            *</label>
                                        <textarea class="form-control" name="DireccionCorrespondencia" id="DireccionCorrespondencia" required>{{ old('DireccionCorrespondencia') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Referencia" class="form-label">Teléfono Principal *</label>
                                        <input class="form-control" name="TelefonoCelular" id="TelefonoCelular"
                                            value="{{ old('TelefonoCelular') }}" required
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="Referencia" class="form-label">Teléfono Residencia</label>
                                        <input class="form-control" name="TelefonoResidencia" id="TelefonoResidencia"
                                            value="{{ old('TelefonoResidencia') }}"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text"
                                            style="display: block;">
                                    </div>
                                    <div class="form-group">
                                        <label for="Referencia" class="form-label">Teléfono Oficina </label>
                                        <input class="form-control" name="TelefonoOficina"
                                            value="{{ old('TelefonoOficina') }}"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="TelefonoCelular2" class="form-label">Teléfono Celular</label>
                                        <input class="form-control" name="TelefonoCelular2"
                                            value="{{ old('TelefonoCelular2') }}"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="CorreoPrincipal" class="form-label">Correo Principal</label>
                                        <input class="form-control" name="CorreoPrincipal" id="CorreoPrincipal"
                                            value="{{ old('CorreoPrincipal') }}" type="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="CorreoSecundario" class="form-label">Correo Secundario</label>
                                        <input class="form-control" name="CorreoSecundario"
                                            value="{{ old('CorreoSecundario') }}" type="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="FechaVinculacion" class="form-label">Fecha Vinculación *</label>
                                        <input class="form-control" name="FechaVinculacion" id="FechaVinculacion"
                                            value="{{ old('FechaVinculacion') }}" type="date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="FechaBaja" class="form-label">Fecha Baja Cliente </label>
                                        <input class="form-control" name="FechaBaja" value="{{ old('FechaBaja') }}"
                                            type="date">
                                    </div>
                                    <div class="form-group">
                                    </div>
                                    <div class="form-group">
                                    </div>
                                </div>
                                <!-- Columna Derecha (6 unidades) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Genero" class="form-label">Estado Cliente * </label>
                                        <select name="Estado" id="Estado" class="form-control" required>
                                            @foreach ($cliente_estados as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Estado') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Genero" class="form-label">Género *</label>
                                        <select name="Genero" id="Genero" class="form-control" required>
                                            <option value="" selected disabled>Seleccione ...</option>
                                            <option value="1" {{ old('Genero') == 1 ? 'selected' : '' }}>Masculino
                                            </option>
                                            <option value="2" {{ old('Genero') == 2 ? 'selected' : '' }}>Femenino
                                            </option>
                                            <option value="3" {{ old('Genero') == 2 ? 'selected' : '' }}>No Aplica
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Nombre" class="form-label">Tipo Contribuyente * </label>
                                        <select name="TipoContribuyente" id="TipoContribuyente" required
                                            class="form-control" onchange="validaciones.cboTipoContribuyente(this.value)"
                                            style="width: 100%">
                                            <option value="" disabled selected>Seleccione ...</option>
                                            @foreach ($tipos_contribuyente as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('TipoContribuyente') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" style="padding-bottom: 90px!important;">
                                        <label for="Referencia" class="form-label">Vinculado al Grupo o Referencia
                                            </label>
                                        <input class="form-control" name="Referencia" id="Referencia"
                                            value="{{ old('Referencia') }}" type="text">
                                    </div>

                                    <div class="campo-container">
                                        <div class="titulo">Formas de pago</div>
                                        <div class="form-group">
                                            <label for="Genero" class="form-label">Responsable de Pago  </label>
                                            <input class="form-control" id="ResponsablePago" name="ResponsablePago"
                                                value="{{ old('ResponsablePago') }}" type="text">
                                        </div>
                                        <div class="form-group">
                                            <label for="Genero" class="form-label">Ubicación de cobro * </label>
                                            <select name="UbicacionCobro" id="UbicacionCobro" class="form-control" style="width: 100%"
                                                required>
                                                <option value="" selected disabled>Seleccione ...</option>
                                                @foreach ($ubicaciones_cobro as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ old('UbicacionCobro') == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="campo-container">
                                            <div class="titulo">
                                                Ruta
                                            </div>
                                            <div class="form-group">
                                                <label for="DireccionResidencia" class="form-label">Departamento
                                                </label>
                                                <select id="Departamento" class="form-control select2"
                                                    style="width: 100%">
                                                    <option value="" disabled selected>Seleccione</option>
                                                    @foreach ($departamentos as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ old('Estado') == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="DireccionResidencia" class="form-label">Municipio</label>
                                                <select name="Municipio" id="Municipio"
                                                    class="form-control select2" style="width: 100%" disabled>
                                                    <option value="">Seleccione</option>
                                                    @foreach ($municipios as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ old('Estado') == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="DireccionResidencia" class="form-label">Distrito</label>
                                                <select id="Distrito" name="Distrito" class="form-control select2"
                                                    style="width: 100%" disabled>
                                                    <option value="">Seleccione</option>
                                                    @foreach ($distritos as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ old('Estado') == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="BancoPrefencia" class="form-label">Banco de su Preferencia
                                                    </label>
                                                <input class="form-control" name="BancoPrefencia"
                                                    value="{{ old('BancoPrefencia') }}" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label for="CuentasDevolucionPrimas" class="form-label">Cuentas para
                                                    devolución de Primas </label>
                                                <input class="form-control" name="CuentasDevolucionPrimas"
                                                    value="{{ old('CuentasDevolucionPrimas') }}" type="text">
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Comentarios" class="form-label">Comentarios</label>
                                        <textarea class="form-control" name="Comentarios">{{ old('Comentarios') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for=""> * Campo requerido </label>
                            </div>
                        </div>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px !important;">
                            <div class="form-group" align="center">
                                <button type="button" class="btn btn-success"
                                    onclick="validar_cliente()">Guardar</button>
                                <a href="{{ url('catalogo/cliente') }}">
                                    <button type="button" class="btn btn-primary">Cancelar
                                    </button>
                                </a>
                            </div>
                        </div>

                    </form>


                </div>

            </div>
        </div>
    </div>


    @include('sweetalert::alert')
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#opcionCliente").addClass("current-page");
            $("#botonMenuCliente").addClass("active");
            $("#menuCliente").css("display", "block");

            let homologadoCheck = $('#Homologado');
            let switchery = new Switchery(homologadoCheck[0]);

            $("#TipoPersona").change(function() {
                tipo_persona(switchery);
            });

            $('#FechaNacimiento').on('change', function() {
                var fecha_nacimiento = new Date($(this).val());
                var fecha_actual = new Date();

                var edad = fecha_actual.getFullYear() - fecha_nacimiento.getFullYear();

                var mes_nacimiento = fecha_nacimiento.getMonth();
                var mes_actual = fecha_actual.getMonth();

                if (mes_actual < mes_nacimiento || (mes_actual === mes_nacimiento && fecha_actual
                        .getDate() <
                        fecha_nacimiento.getDate())) {
                    edad--;
                }

                if (isNaN(edad)) {
                    $('#EdadCalculada').val('Seleccione una fecha valida');
                } else {
                    $('#EdadCalculada').val(edad);
                }
            });

            $("#Departamento").change(function() {
                // var para la Departamento
                var Departamento = $(this).val();

                //funcionpara las municipios
                $.get("{{ url('get_municipio') }}" + '/' + Departamento, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    var _select = '<option value="" disabled selected>Seleccione</option>'
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                        '</option>';
                    $("#Municipio").prop('disabled', false);
                    $("#Municipio").html(_select);
                });


            });

            $("#Municipio").change(function() {
                // var para la Departamento
                var Municipio = $(this).val();

                //funcionpara las distritos
                $.get("{{ url('get_distrito') }}" + '/' + Municipio, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    var _select = ''
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                        '</option>';
                    $("#Distrito").prop('disabled', false);
                    $("#Distrito").html(_select);
                });


            });

        });
        const validaciones = {
            cboTipoPersona(idTipoPersona) {
                console.log(idTipoPersona)
                // 1 natural
                // 2 jurudica
                if (idTipoPersona === '1') {
                    document.getElementById("TipoContribuyente").value = ""; // no aplica
                    // $('#TipoContribuyente').trigger('change');
                }

            },
            cboTipoContribuyente(idTipoContribuyente) {
                document.getElementById("RegistroFiscal").disabled = idTipoContribuyente === '4';
            },
            cambiarEstado() {
                console.log("se activo la funcion");

                if (document.getElementById('Homologado').checked) {
                    $('#Nit').prop('readonly', true);
                    $('#Nit').inputmask('remove');
                    $('#Nit').inputmask({
                        'mask': '99999999-9'
                    });
                    $('#Nit').val($('#Dui').val());
                } else {
                    $('#Nit').prop('readonly', false);
                    $('#Nit').inputmask('remove');
                    $('#Nit').inputmask({
                        'mask': '9999-999999-999-9'
                    });
                    $('#Nit').val('');
                }
            }
        }

        function tipo_persona(switchery) {
            let dui = $('#Dui');
            let nit = $('#Nit');
            let dependientes = $("#NumeroDependientes");
            let residencia = $("#DireccionResidencia");
            let telefono = $("#TelefonoResidencia");
            let tipoPersona = $('#TipoPersona');
            let homologado = $('#Homologado');
            let genero = $('#Genero');
            let estadoFamiliar = $('#EstadoFamiliar');
            if (tipoPersona.val() === '2') { //juridico
                dui.prop('readonly', true);
                dui.val('');
                dependientes.prop('readonly', true);
                residencia.prop('readonly', true);
                telefono.prop('readonly', true);
                switchery.disable();
                if (homologado.prop('checked')) {
                    switchery.setPosition(true); // Cambia a estado seleccionado
                }
                nit.val('');
                nit.prop('readonly', false);
                nit.inputmask('remove');
                nit.inputmask({
                    'mask': '9999-999999-999-9'
                });
                genero.val('3');
                estadoFamiliar.val('0');
                /*genero.prop('readonly', true);
                estadoFamiliar.prop('readonly', true);*/
            } else {
                dependientes.prop('readonly', false);
                residencia.prop('readonly', false);
                telefono.prop('readonly', false);
                dui.prop('readonly', false);
                switchery.enable(); // Cambia a estado seleccionado
                genero.find('option:selected').prop('selected', false);
                genero.val(null);
                estadoFamiliar.find('option:selected').prop('selected', false);
                estadoFamiliar.val(null);
                /*genero.prop('readonly', false);
                estadoFamiliar.prop('readonly', false);*/
            }
        }

        function validar_cliente() {
            var tipoPersona = document.getElementById('TipoPersona').value;
            var nit = document.getElementById('Nit').value;
            var dui = document.getElementById('Dui').value;
            var nombre = document.getElementById('Nombre').value;
            var fechaNacimiento = document.getElementById('FechaNacimiento').value;
            var direccionCorrespondencia = document.getElementById('DireccionCorrespondencia').value;
            var telefonoCelular = document.getElementById('TelefonoCelular').value;
            var correoPrincipal = document.getElementById('CorreoPrincipal').value;
            var fechaVinculacion = document.getElementById('FechaVinculacion').value;
            var estado = document.getElementById('Estado').value;
            var genero = document.getElementById('Genero').value;
            var tipoContribuyente = document.getElementById('TipoContribuyente').value;
            var ubicacionCobro = document.getElementById('UbicacionCobro').value;
            var departamento = document.getElementById('Departamento').value;
            var municipio = document.getElementById('Municipio').value;
            var distrito = document.getElementById('Distrito').value;


            // Construir la URL con los parámetros
            var url = new URL('{{ url('catalogo/cliente/validar_cliente') }}');
            var params = {
                Dui: dui,
                Nit: nit,
                Nombre: nombre,
                TipoPersona: tipoPersona,
                FechaNacimiento: fechaNacimiento,
                DireccionCorrespondencia: direccionCorrespondencia,
                TelefonoCelular: telefonoCelular,
                CorreoPrincipal: correoPrincipal,
                FechaVinculacion: fechaVinculacion,
                Estado: estado,
                Genero: genero,
                TipoContribuyente: tipoContribuyente,
                UbicacionCobro: ubicacionCobro,
                Departamento: departamento,
                Municipio: municipio,
                Distrito: distrito
            };
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            // Realizar la solicitud GET
            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Limpiar mensajes de error previos
                    var errorDiv = document.getElementById('error-messages');
                    errorDiv.innerHTML = '';

                    console.log(data);

                    // Verificar si hay errores de validación
                    if (data.success === false && data.errors) {
                        // Mostrar los errores de validación
                        for (const [key, messages] of Object.entries(data.errors)) {
                            messages.forEach(message => {
                                var p = document.createElement('p');
                                p.textContent = message;
                                errorDiv.appendChild(p);
                            });
                        }

                        errorDiv.style.display = 'block';
                    } else {
                        // Manejar la respuesta exitosa
                        console.log('Validación exitosa', data);

                        // Obtener el formulario por su ID
                        var form = document.getElementById('myform');

                        // Realizar el submit del formulario
                        form.submit();
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                });

                window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
                });
        }
    </script>
@endsection
