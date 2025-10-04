@extends ('welcome')
@section('contenido')

    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nuevo Poliza de Vida &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VICO - Vida Colectivo Seguros<small></small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">

                    </ul>
                    <div class="clearfix"></div>
                </div>


                <div class="x_content">
                    <br />
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-horizontal" style="font-size: 12px;">
                        <form action="{{ url('polizas/vida') }}" method="POST" id="form_create">
                            @csrf

                            <div class="x_content" style="font-size: 12px;">
                                <div class="col-sm-12" style="padding: 0% !important">
                                    <!-- Número de Póliza -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Número de Póliza *</label>
                                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                            value="{{ old('NumeroPoliza') }}" required>
                                    </div>


                                    <!-- Aseguradora -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Aseguradora *</label>
                                        <select name="Aseguradora" id="Aseguradora" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($aseguradora as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Aseguradora') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Aseguradora')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Productos -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label">Productos *</label>
                                        <select name="Productos" id="Productos" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            @foreach ($productos as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Productos') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Productos')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Planes -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label">Planes *</label>
                                        <select name="Planes" id="Planes" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            @foreach ($planes as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Planes') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Planes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <!-- Asegurado -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Asegurado</label>
                                        <select name="Asegurado" id="Asegurado" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($cliente as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Asegurado') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Nit -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Nit</label>
                                        <input class="form-control" name="Nit" id="Nit" type="text"
                                            value="{{ old('Nit') }}" readonly>
                                    </div>
                                </div>

                                <!-- Ejecutivo -->
                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Ejecutivo</label>
                                    <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($ejecutivo as $obj)
                                            <option value="{{ $obj->Id }}"
                                                {{ old('Ejecutivo') == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Tipo cobro</label>
                                    <select name="TipoCobro" class="form-control" onchange="showTipoCobro(this.value)"
                                        required>
                                        @foreach ($tipoCobro as $tipo)
                                            <option value="{{ $tipo->Id }}"
                                                {{ old('TipoCobro', 2) == $tipo->Id ? 'selected' : '' }}>
                                                {{ $tipo->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>



                                <div class="col-sm-12" style="padding: 0% !important; display: none"
                                    id="div-cobro-usuarios">


                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Suma minima</label>
                                        <input class="form-control" name="SumaMinima" type="number" min="0.00"
                                            step="any" value="{{ old('SumaMinima') }}">
                                    </div>


                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Suma máxima</label>
                                        <input class="form-control" name="SumaMaxima" type="number" min="0.00"
                                            step="any" value="{{ old('SumaMaxima') }}">
                                    </div>


                                </div>


                                <div class="col-sm-12" style="padding: 0% !important" id="div-cobro-creditos">

                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Tipo de suma</label>
                                        <select name="TipoTarifa" class="form-control"
                                            onchange="showMultitarifa(this.value)">
                                            <option value="1">Suma uniforme</option>
                                            <option value="2">Multicategoria</option>
                                        </select>
                                    </div>


                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6" id="div-sumaAsegurada">
                                        <label class="control-label" align="right">Suma asegurada</label>
                                        <input class="form-control" name="SumaAsegurada" type="number" step="any"
                                            value="{{ old('SumaAsegurada') }}" min="100">
                                    </div>

                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6" id="div-multitarifa"
                                        style="display: none">
                                        <label class="control-label" align="right">Multicategoria</label>
                                        <input class="form-control" name="Multitarifa" type="text" id="Multitarifa"
                                            min="1" step="any" value="{{ old('Multitarifa') }}"
                                            oninput="formatMultitarifa(this)">
                                        <label id="multitarifa-error" class="text-danger" style="display: none;">Formato
                                            inválido: use cantidades separadas
                                            por coma.</label>
                                    </div>

                                </div>

                                <div class="col-sm-12" style="padding: 0% !important">



                                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                        <label class="control-label" align="right">Opcion tarifa</label>
                                        <select name="Opcion" id="Opcion" class="form-control">
                                            <option value="0">NO APLICA</option>
                                            <option value="1">TASA DIFERENCIADA</option>
                                            <option value="2">COBRO CON TARIFA EXCEL</option>
                                        </select>

                                    </div>

                                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                        <label class="control-label" align="right">Tasa Millar Mensual</label>
                                        <input class="form-control" name="Tasa" id="Tasa" type="number"
                                            step="any" value="{{ old('Tasa') }}">
                                    </div>

                                    <!-- Edad máxima de inscripción -->
                                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                        <label class="control-label" align="right">Edad máxima de
                                            inscripción</label>
                                        <input class="form-control" name="EdadMaximaInscripcion" type="number"
                                            min="18" max="100" step="any"
                                            value="{{ old('EdadMaximaInscripcion') }}" required>
                                    </div>


                                    <!-- Edad Terminación -->
                                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                        <label class="control-label" align="right">Edad Terminación</label>
                                        <input class="form-control" name="EdadTerminacion" type="number" step="any"
                                            value="{{ old('EdadTerminacion') }}" min="18" max="100" required>
                                    </div>



                                </div>


                                <div class="col-sm-12" style="padding: 0% !important">
                                    <!-- Vigencia Desde -->
                                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                        <label class="control-label" align="right">Vigencia Desde</label>
                                        <input class="form-control" name="VigenciaDesde" type="date"
                                            value="{{ old('VigenciaDesde') }}" required>
                                    </div>

                                    <!-- Vigencia Hasta -->
                                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                        <label class="control-label" align="right">Vigencia Hasta</label>
                                        <input class="form-control" name="VigenciaHasta" type="date"
                                            value="{{ old('VigenciaHasta') }}" required>
                                    </div>


                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Status *</label>
                                        <select name="EstadoPoliza" id="EstadoPoliza" class="form-control">
                                            @foreach ($estados as $estado)
                                                <option value="{{$estado->Id}}">{{$estado->Nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <!-- Tasa Millar Mensual -->
                                <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                    <label class="control-label" align="right">Descuento</label>
                                    <input class="form-control" name="TasaDescuento" type="number" step="any"
                                        value="{{ old('TasaDescuento') }}">
                                </div>

                                <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                    <label class="control-label" align="right">% de Comisión *</label>
                                    <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                                        step="any" value="{{ old('TasaComision') }}" required>
                                </div>

                                <!-- Concepto -->
                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Concepto</label>
                                    <textarea class="form-control" name="Concepto" rows="3" cols="4">{{ old('Concepto') }}</textarea>
                                </div>
                            </div>

                            <br><br>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <br>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <button type="button" onclick="validar()" class="btn btn-success">Aceptar</button>
                                    <a href="{{ url('polizas/vida') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>

                        </form>
                    </div>


                </div>

            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-vida");

            $("#Asegurado").change(function() {
                // alert(document.getElementById('Asegurado').value);
                $('#response').html('<div><img src="{{ url(' / img / ajax - loader.gif ') }}"/></div>');
                var parametros = {
                    "Cliente": document.getElementById('Asegurado').value
                };
                $.ajax({
                    type: "get",
                    //ruta para obtener el horario del doctor
                    url: "{{ url('get_cliente') }}",
                    data: parametros,
                    success: function(data) {
                        console.log(data);
                        document.getElementById('Nit').value = data.Nit;
                        if (data.TipoContribuyente == 1) {
                            document.getElementById('Retencion').setAttribute("readonly", true);
                            document.getElementById('Retencion').value = 0;
                        }


                    }
                });
            });

        });

        $("#Opcion").change(function() {
            if ($(this).val() == "0") {
                // Si el valor del select es 0, input editable
                $("#Tasa").prop('readonly', false);
            } else {
                // Si es otro valor, limpiar y poner readonly
                $("#Tasa").val('').prop('readonly', true);
            }
        });

        function showTipoCobro(id) {
            const divUsuarios = document.getElementById('div-cobro-usuarios');
            const divCreditos = document.getElementById('div-cobro-creditos');

            if (id == 1) {
                divUsuarios.style.display = 'block';
                divCreditos.style.display = 'none';
            } else if (id == 2) {
                divUsuarios.style.display = 'none';
                divCreditos.style.display = 'block';
            } else if (id == 3) {
                divUsuarios.style.display = 'none';
                divCreditos.style.display = 'none';
            }
        }

        function showMultitarifa(id) {
            const divSuma = document.getElementById('div-sumaAsegurada');
            const divMulti = document.getElementById('div-multitarifa');

            if (id == 1) {
                divSuma.style.display = 'block';
                divMulti.style.display = 'none';
            } else if (id == 2) {
                divSuma.style.display = 'none';
                divMulti.style.display = 'block';
            }
        }

        function formatMultitarifa(input) {
            // Permitir solo dígitos, puntos y comas
            input.value = input.value.replace(/[^0-9.,]/g, '');
        }


        async function validar() {
            const form = document.getElementById('form_create');
            const formData = new FormData(form);

            try {
                const response = await fetch("{{ url('poliza/vida/validar_store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Validación exitosa: puedes enviar el formulario real
                    form.submit();
                } else {
                    // Mostrar errores de validación
                    mostrarErrores(result.errors);
                }

            } catch (error) {
                console.error("Error al validar:", error);
            }
        }

        function mostrarErrores(errores) {
            // Limpiar mensajes anteriores
            document.querySelectorAll('.text-danger').forEach(el => el.remove());

            for (const campo in errores) {
                const input = document.querySelector(`[name="${campo}"]`);
                if (input) {
                    const mensaje = document.createElement('div');
                    mensaje.className = 'text-danger';
                    mensaje.textContent = errores[campo][0]; // Mostrar solo el primer error
                    input.parentElement.appendChild(mensaje);
                }
            }
        }
    </script>
@endsection
