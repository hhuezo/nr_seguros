@extends ('welcome')
@section('contenido')
    <script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>


    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Pólizas / Residencia / Póliza de Residencia / Nueva <small></small></h2>
                    {{-- <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('polizas/residencia') }}" class="btn btn-info fa fa-undo " style="color: white">
                            Atrás</a>
                    </ul> --}}
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

                <form action="{{ route('residencia.store') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                        <br />
                        <div class="col-sm-12 row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="control-label">Número de Póliza *</label>
                                <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                    value="{{ old('NumeroPoliza') }}" required>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                &nbsp;
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <label class="control-label">Asegurado *</label>
                            <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%"
                                required>
                                <option value="" disabled selected>Seleccione...</option>
                                @foreach ($cliente as $obj)
                                    <option value="{{ $obj->Id }}" @selected(old('Asegurado') == $obj->Id)>
                                        {{ $obj->Nombre }} {{ $obj->Dui }} {{ $obj->Nit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">NIT *</label>
                            <input class="form-control" name="Nit" id="Nit" type="text"
                                value="{{ old('Nit') }}" readonly>
                        </div>
                        <div class="col-sm-4" style="display: none;">
                            <label class="control-label ">Código</label>
                            <input class="form-control" name="Codigo" type="text" value="{{ $ultimo }}"
                                readonly>
                        </div>

                        <div class="col-sm-8">
                            <label class="control-label">Aseguradora *</label>
                            <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%"
                                required>
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach ($aseguradoras as $obj)
                                    <option value="{{ $obj->Id }}" @selected(old('Aseguradora') == $obj->Id)>
                                        {{ $obj->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label">Productos *</label>
                            <select name="Productos" id="Productos" class="form-control select2" style="width: 100%"
                                required>
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach ($productos as $obj)
                                    <option value="{{ $obj->Id }}" @selected(old('Productos') == $obj->Id)>
                                        {{ $obj->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label">Planes *</label>
                            <select name="Planes" id="Planes" class="form-control select2" style="width: 100%" required>
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach ($planes as $obj)
                                    <option value="{{ $obj->Id }}" @selected(old('Planes') == $obj->Id)>
                                        {{ $obj->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            &nbsp;
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Vigencia Desde *</label>
                            <input class="form-control" name="VigenciaDesde" type="date"
                                value="{{ old('VigenciaDesde') }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Vigencia Hasta *</label>
                            <input class="form-control" name="VigenciaHasta" type="date" placeholder="dd/mm/yyyy"
                                value="{{ old('VigenciaHasta') }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Estatus *</label>
                            <select name="EstadoPoliza" class="form-control" style="width: 100%" required>
                                @foreach ($estados_poliza as $obj)
                                    @if ($obj->Id == 1)
                                        <option value="{{ $obj->Id }}" @selected(old('EstadoPoliza') == $obj->Id)>
                                            {{ $obj->Nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Ejecutivo *</label>
                            <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                <option value="" disabled selected>Seleccione...</option>
                                @foreach ($ejecutivo as $obj)
                                    <option value="{{ $obj->Id }}" @selected(old('Ejecutivo') == $obj->Id)>
                                        {{ $obj->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Descuento de Rentabilidad --}}
                        <div class="col-sm-4">
                            <label class="control-label">Descuento de Rentabilidad % *</label>
                            <div class="form-group has-feedback">
                                <input type="text" name="TasaDescuento" id="TasaDescuento"
                                    class="form-control cantidad-texto" required style="padding-left: 15%; display: block;"
                                    value="{{ old('TasaDescuento') }}"
                                    oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                    onblur="formatearCantidad(this)">
                                <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <input type="hidden" name="Bomberos" id="Bomberos" value="{{ $bomberos }}">
                            <label class="control-label">Límite de Grupo *</label>
                            <div class="form-group has-feedback">
                                <input type="text" name="LimiteGrupo" id="LimiteGrupo"
                                    class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                    value="{{ old('LimiteGrupo') }}" required
                                    oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                    onblur="formatearCantidad(this)">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                        {{-- Límite Individual --}}
                        <div class="col-sm-4">
                            <label class="control-label">Límite Individual *</label>
                            <div class="form-group has-feedback">
                                <input type="text" name="LimiteIndividual" id="LimiteIndividual"
                                    class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                    value="{{ old('LimiteIndividual') }}" required
                                    oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                    onblur="formatearCantidad(this)">
                                <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                        {{-- Tasa --}}
                        <div class="col-sm-4">
                            <label class="control-label">Tasa % *</label>
                            <div class="form-group has-feedback">
                                <input type="text" name="Tasa" id="Tasa" class="form-control cantidad-texto"
                                    style="padding-left: 15%; display: block;" value="{{ old('Tasa') }}" required
                                    oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                    onblur="formatearCantidad(this)">
                                <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                        {{-- Comisión --}}
                        <div class="col-sm-4">
                            <label class="control-label">Porcentaje de comisión *</label>
                            <div class="form-group has-feedback">
                                <input type="text" name="TasaComision" id="TasaComision"
                                    class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                    value="{{ old('TasaComision') }}" required
                                    oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                    onblur="formatearCantidad(this)">
                                <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                            <div class="form-group row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="tipoTasaMensual" value="1"
                                        {{ old('tipoTasa', '1') == '1' ? 'checked' : '' }} required>
                                    <label class="control-label" for="tipoTasaMensual">Tasa Millar Mensual *</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="tipoTasaAnual" value="0"
                                        {{ old('tipoTasa') == '0' ? 'checked' : '' }} required>
                                    <label class="control-label" for="tipoTasaAnual">Tasa ‰ Millar Anual *</label>
                                </div>
                            </div>
                        </div>

                        {{-- IVA incluido --}}
                        <div class="col-sm-2">
                            <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch"
                                {{ old('ComisionIva') ? 'checked' : '' }}>
                            <label class="control-label" align="right">¿IVA incluído?</label>
                        </div>

                        <br><br>
                        <div class="col-sm-12 row">* Campo requerido</div>

                        <div class="x_title">
                            <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                            <div class="clearfix"></div>
                        </div>
                        <br>
                    </div>

                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit" id="btn-guardar">Guardar</button>
                        <a href="{{ url('polizas/residencia/') }}"><button class="btn btn-primary"
                                type="button">Cancelar</button></a>
                    </div>
                </form>



                @include('catalogo.cliente.modal_poliza')

            </div>

        </div>
    </div>


    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-residencia");



            $("#Aseguradora").change(function() {
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                // var para la Departamento
                var Aseguradora = $(this).val();

                //funcionpara las distritos
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
                // var para la Departamento
                var Productos = $(this).val();

                //funcionpara las distritos
                $.get("{{ url('get_plan') }}" + '/' + Productos, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    var _select = '<option value=""> Seleccione </option>';
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                        '</option>';
                    $("#Planes").html(_select);
                });
            });


            $("#Asegurado").change(function() {
                // alert(document.getElementById('Asegurado').value);
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
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
                    }
                });
            });

        });




        function formatearCantidad(input) {
            let valor = input.value.trim();

            if (!valor) return;

            const puntos = (valor.match(/\./g) || []).length;
            const comas = (valor.match(/,/g) || []).length;

            if (puntos + comas > 1) {
                let ultimoSeparadorIndex = Math.max(valor.lastIndexOf('.'), valor.lastIndexOf(','));
                let separadorDecimal = valor.charAt(ultimoSeparadorIndex);

                let parteEntera = valor.slice(0, ultimoSeparadorIndex).replace(/[.,]/g, '');
                let parteDecimal = valor.slice(ultimoSeparadorIndex + 1);

                valor = parteEntera + '.' + parteDecimal;
            } else {
                valor = valor.replace(',', '.').replace(/,/g, '');
            }

            const partes = valor.split('.');
            if (partes.length > 2) {
                toastr.error('Cantidad inválida: múltiples separadores decimales.', 'Error');
                input.value = "";
                return;
            }

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

            // ✅ Mostrar hasta 6 decimales pero sin cortar los necesarios
            input.value = numero.toLocaleString('en-US', {
                minimumFractionDigits: (numero % 1 !== 0) ? 3 : 0, // si hay decimales, mostrar al menos 3
                maximumFractionDigits: 6
            });
        }
    </script>





@endsection
