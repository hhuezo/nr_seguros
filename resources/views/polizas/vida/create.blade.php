@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

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
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="x_content">
                    <br />




                    <div class="form-horizontal" style="font-size: 12px;">
                        <form action="{{ url('polizas/vida') }}" method="POST">
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
                                                    {{ $obj->Nombre }}</option>
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

                                {{-- <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label">Saldos y Montos</label>
                                    <select name="Saldos" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($saldos as $obj)
                                            <option value="{{ $obj->Id }}">
                                                {{ $obj->Abreviatura }} -
                                                {{ $obj->Descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Tipo cobro</label>
                                    <select name="TipoCobro" class="form-control" required>
                                        @foreach ($tipoCobro as $tipo)
                                            <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-sm-12" style="padding: 0% !important">

                                    <!-- Vigencia Desde -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Vigencia Desde</label>
                                        <input class="form-control" name="VigenciaDesde" type="date"
                                            value="{{ old('VigenciaDesde') }}" required>
                                    </div>

                                    <!-- Vigencia Hasta -->
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Vigencia Hasta</label>
                                        <input class="form-control" name="VigenciaHasta" type="date"
                                            value="{{ old('VigenciaHasta') }}" required>
                                    </div>
                                </div>

                                <!-- Edad máxima de inscripción -->
                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Edad máxima de inscripción</label>
                                    <input class="form-control" name="EdadMaximaInscripcion" type="number"
                                        min="18" max="100" step="any"
                                        value="{{ old('EdadMaximaInscripcion') }}" required>
                                </div>

                                <!-- Edad Terminación -->
                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Edad Terminación</label>
                                    <input class="form-control" name="EdadTerminacion" type="number" step="any"
                                        value="{{ old('EdadTerminacion') }}" min="18" max="100" required>
                                </div>

                                <!-- Tasa Millar Mensual -->
                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Tasa Millar Mensual</label>
                                    <input class="form-control" name="Tasa" type="number" step="any"
                                        value="{{ old('Tasa') }}" required>
                                </div>

                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Suma asegurada</label>
                                    <input class="form-control" name="SumaAsegurada" type="number" step="any"
                                        required value="{{ old('SumaAsegurada') }}" min="100">
                                </div>

                                <!-- Tasa Millar Mensual -->
                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label class="control-label" align="right">Descuento</label>
                                    <input class="form-control" name="TasaDescuento" type="number" step="any"
                                        value="{{ old('TasaDescuento') }}">
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
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-success">Aceptar</button>
                            <a href="{{ url('poliza/vida') }}"><button type="button"
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

            $("#TipoCobro").change(function() {
                if (document.getElementById('TipoCobro').value == 1) {
                    document.getElementById('LimiteGrupo').removeAttribute('required');
                    document.getElementById('LimiteIndividual').removeAttribute('required');

                    $("#creditos").hide();
                } else {

                    $('#creditos').show();
                    $('#LimiteGrupo').attr('required', 'required');
                    $('#LimiteIndividual').attr('required', 'required');
                }
            })

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
    </script>
@endsection
