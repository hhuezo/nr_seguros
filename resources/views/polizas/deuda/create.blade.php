@extends ('welcome')
@section('contenido')

    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>
    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Pólizas / Deuda / Póliza de deuda / Nueva póliza
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">

                    </ul>
                    <div class="clearfix"></div>
                </div>
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


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab"
                                data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                        </li>
                        <li role="presentation" class=" "><a>Tasa diferencia</a>
                        </li>
                        <li role="presentation" class=""><a>Requisitos Minimos de
                                Asegurabilidad </a>
                        </li>


                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                            <form action="{{ url('polizas/deuda') }}" method="POST">
                                @csrf
                                <div class="x_content" style="font-size: 12px;">
                                    <div class="col-sm-12 row">
                                        <div class="col-sm-4 ">
                                            <label class="control-label" align="right">Número de Póliza *</label>
                                            <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                                value="{{ old('NumeroPoliza') }}" required>
                                        </div>

                                        <div class="col-sm-4" style="display: none !important;">
                                            <label class="control-label" align="right">Código *</label>
                                            <input class="form-control" name="Codigo" type="text"
                                                value="{{ $ultimo }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Aseguradora *</label>
                                        <select name="Aseguradora" id="Aseguradora" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($aseguradora as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Aseguradora') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
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
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label">Planes *</label>
                                        <select name="Planes" id="Planes" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            @foreach ($planes as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Planes') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Asegurado *</label>
                                        <select name="Asegurado" id="Asegurado" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($cliente as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Asegurado') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Nit *</label>
                                        <input class="form-control" name="Nit" id="Nit" type="text"
                                            value="{{ old('Nit') }}" readonly>
                                    </div>

                                    <div class="col-sm-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Desde *</label>
                                        <input class="form-control" name="VigenciaDesde" type="date"
                                            value="{{ old('VigenciaDesde') }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Hasta *</label>
                                        <input class="form-control" name="VigenciaHasta" type="date"
                                            value="{{ old('VigenciaHasta') }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Estatus *</label>
                                        <select name="EstadoPoliza" class="form-control" style="width: 100%"
                                            required>
                                            @foreach ($estadoPoliza as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('EstadoPoliza') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Ejecutivo *</label>
                                        <select name="Ejecutivo" class="form-control select2" style="width: 100%"
                                            required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($ejecutivo as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Ejecutivo') == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Descuento de Rentabilidad *</label>
                                        <input class="form-control" name="Descuento" type="number" step="any"
                                            id="Descuento" value="{{ old('Descuento') }}" required>
                                    </div>

                                    <div class="col-sm-4">&nbsp;</div>
                                    <div class="col-md-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Edad Máxima Terminación *</label>
                                        <input type="number" name="EdadMaximaTerminacion" class="form-control"
                                            value="{{ old('EdadMaximaTerminacion') }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Responsabilidad Máxima *</label>
                                        <div class="form-group has-feedback">
                                            <input type="text" name="ResponsabilidadMaxima"
                                                id="ResponsabilidadMaximaTexto" class="form-control"
                                                style="padding-left: 15%; display: block;" required
                                                value="{{ old('ResponsabilidadMaxima') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                                onblur="formatearCantidad(this)">
                                            <span class="fa fa-dollar form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>
                                    </div>


                                    <div class="col-sm-4">&nbsp;</div>
                                    <div class="col-md-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Clausulas Especiales </label>
                                        <textarea class="form-control" name="ClausulasEspeciales">{{ old('ClausulasEspeciales') }}</textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Beneficios Adicionales </label>
                                        <textarea class="form-control" name="Beneficios">{{ old('Beneficios') }}</textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Concepto </label>
                                        <textarea class="form-control" name="Concepto" required>{{ old('Concepto') }}</textarea>
                                    </div>

                                    <div class="col-sm-4 ocultar" style="display: none !important;">
                                        <br>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                                {{ old('tipoTasa', '1') == '1' ? 'checked' : '' }}>
                                            <label class="control-label">Tasa Millar Mensual *</label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Anual" value="0"
                                                {{ old('tipoTasa') == '0' ? 'checked' : '' }}>
                                            <label class="control-label">Tasa ‰ Millar Anual *</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Tasa Millar Mensual* </label>
                                        <input class="form-control" name="Tasa" type="number" id="Tasa"
                                            step="any" value="{{ old('Tasa') }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">% de Comisión *</label>
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                                            step="any" value="{{ old('TasaComision') }}">
                                    </div>
                                    <div class="col-sm-2"><br>
                                        <label class="control-label" align="right">¿IVA incluído?</label>
                                        <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch"
                                            {{ old('ComisionIva') ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-sm-2"><br>
                                        <label class="control-label" align="right">Cobro con tarifa excel&nbsp;</label>
                                        <input name="TarifaExcel" type="checkbox" class="js-switch"
                                            {{ old('TarifaExcel') ? 'checked' : '' }}>
                                    </div>

                                    <div class="col-sm-6">
                                        <div>
                                            <label class="control-label">Póliza Vida</label>
                                            <select class="form-control" name="PolizaVida">
                                                <option value="" selected>SELECCIONE</option>
                                                @foreach ($polizas_vida as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ old('PolizaVida') == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->NumeroPoliza }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div>
                                            <label class="control-label">Póliza Desempleo</label>
                                            <select class="form-control" name="PolizaDesempleo">
                                                <option value="" selected>SELECCIONE</option>
                                                @foreach ($polizas_desempleo as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ old('PolizaDesempleo') == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->NumeroPoliza }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="x_title">
                                    <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                    <div class="clearfix"></div>
                                </div>

                                <br>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group" align="center">
                                        <button type="submit" class="btn btn-success">Guardar y Continuar</button>
                                        <a href="{{ url('polizas/deuda') }}"><button type="button"
                                                class="btn btn-primary">Cancelar</button></a>
                                    </div>
                                </div>
                            </form>

                        </div>
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
            displayOption("ul-poliza", "li-poliza-deuda");


            $("#Asegurado").change(function() {
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                var parametros = {
                    "Cliente": document.getElementById('Asegurado').value
                };
                $.ajax({
                    type: "GET",
                    url: "{{ url('get_cliente') }}",
                    data: parametros,
                    success: function(data) {
                        console.log(data);
                        document.getElementById('Nit').value = data.Nit;
                    }
                });
            });


            $("#Aseguradora").change(function() {
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                var Aseguradora = $(this).val();

                $.get("{{ url('get_producto') }}" + '/' + Aseguradora, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    var _select = '<option value=""> Seleccione </option>';
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                        '</option>';
                    $("#Productos").html(_select);
                });
            })

            $("#Productos").change(function() {
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                var Productos = $(this).val();

                $.get("{{ url('get_plan') }}" + '/' + Productos, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    var _select = '<option value=""> Seleccione </option>';
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                        '</option>';
                    $("#Planes").html(_select);
                });
            })

        });


        function formatearCantidad(input) {
            let valor = input.value.trim();

            if (!valor) return;

            // Contar cantidad de puntos y comas
            const puntos = (valor.match(/\./g) || []).length;
            const comas = (valor.match(/,/g) || []).length;

            // Si no hay separador decimal, quitar todas las comas y puntos
            // o si hay varios puntos y comas, alertar error
            if (puntos + comas > 1) {
                // Asumiremos que el separador decimal es el último de los dos caracteres (coma o punto)
                let ultimoSeparadorIndex = Math.max(valor.lastIndexOf('.'), valor.lastIndexOf(','));
                let separadorDecimal = valor.charAt(ultimoSeparadorIndex);

                // Limpiar separadores de miles (todos menos el último separador decimal)
                let parteEntera = valor.slice(0, ultimoSeparadorIndex).replace(/[.,]/g, '');
                let parteDecimal = valor.slice(ultimoSeparadorIndex + 1);

                // Reconstruir valor estándar, cambiando separador decimal a punto
                valor = parteEntera + '.' + parteDecimal;
            } else {
                // Si sólo hay uno o ninguno separador, sustituimos coma por punto para decimal
                valor = valor.replace(',', '.').replace(/,/g, '');
            }

            // Validar que ahora solo hay un punto decimal
            const partes = valor.split('.');
            if (partes.length > 2) {
                toastr.error('Cantidad inválida: múltiples separadores decimales.', 'Error');
                input.value = "";
                return;
            }

            // Validar que sólo haya dígitos en partes
            if (!partes.every(p => /^\d*$/.test(p))) {
                toastr.error('Cantidad inválida: contiene caracteres no numéricos.', 'Error');
                input.value = "";
                return;
            }

            let numero = parseFloat(valor);
            if (isNaN(numero)) {
                toastr.error('Cantidad inválida.', 'Error');
                input.value = "";
                return;
            }

            // Formatear con miles (coma) y punto decimal
            input.value = numero.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    </script>
@endsection
