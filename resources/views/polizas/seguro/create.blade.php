@extends ('welcome')
@section('contenido')
    <style>
        #loading-overlay-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loading-overlay-modal img {
            width: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
            height: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
        }
    </style>
    <div class="x_panel">
        <div id="loading-overlay-modal">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
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
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Nueva póliza de seguro</h4>
            </div>
            <!-- <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                                        <a href="{{ url('poliza/seguro/create') }}"><button class="btn btn-info float-right">
                                                <i class="fa fa-plus"></i> Nuevo</button></a>
                                    </div> -->
            <div class="clearfix"></div>
        </div>
        <form action="{{ url('poliza/seguro') }}" method="post">
            @csrf
            <div class="row">

                <div class="form-horizontal">
                    <div class="col-sm-6" style="background-color: lightgrey; padding: 12px; border-radius: 15px;">
                        <label class="control-label"># Oferta</label>
                        <select name="Oferta" id="Oferta" class="form-control" onchange="select_oferta(this)">
                            <option value="">Seleccionar</option>
                            @foreach ($ofertas as $off)
                                <option value="{{ $off->Id }}">{{ $off->clientes->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Forma de Pago *</label>
                        <select name="FormaPago" id="FormaPago" class="form-control" required>
                            @foreach ($forma_pago as $pago)
                                <option value="{{ $loop->index }}">{{ $pago == '' ? 'Seleccione...' : $pago }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-horizontal">
                    <div class="col-sm-6">
                        <label class="control-label">Número Póliza *</label>
                        <input type="text" name="NumeroPoliza" class="form-control" required>
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Estado de Poliza *</label>
                        <select name="EstadoPoliza" id="EstadoPoliza" class="form-control" required>
                            @foreach ($estado_poliza as $estado)
                                <option value="{{ $estado->Id }}">{{ $estado->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Productos -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label">Productos *</label>
                        <select name="Productos" id="Productos" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($productos as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Planes -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label">Planes *</label>
                        <select name="Planes" id="Planes" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($planes as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Nombre Cliente *</label>
                        <select name="Cliente" id="Cliente" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($clientes as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Número documento *</label>
                        <input type="text" name="NumeroDocumento" id="NumeroDocumento" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-horizontal">
                    <div class="col-sm-6">
                        <label class="control-label">Vigencia Desde *</label>
                        <input class="form-control" id="vigencia_desde" name="VigenciaDesde" type="date"
                            value="{{ old('VigenciaDesde') }}" required>
                    </div>

                    <div class="col-sm-3">
                        <label class="control-label">Vigencia Hasta *</label>
                        <input class="form-control" id="vigencia_hasta" name="VigenciaHasta" type="date"
                            value="{{ old('VigenciaHasta') }}" required>

                    </div>

                    <div class="col-sm-3">
                        <label class="control-label">Días</label>
                        <input type="number" name="DiasVigencia" id="dias_vigencia" class="form-control" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Motivo Cancelación</label>
                        <input type="text" name="MotivoCancelacion" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Fecha Cancelación</label>
                        <input type="date" name="FechaCancelacion" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Cod Cancelación</label>
                        <select name="CodCancelacion" id="CodCancelacion" class="form-control select2"
                            style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($cancelacion as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Fecha Envío Anexo</label>
                        <input type="date" name="FechaEnvioAnexo" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-horizontal">
                    <div class="col-sm-6">
                        <label class="control-label">Observación Ren. </label>
                        <input type="text" name="Observacion" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Solicitud Renovación</label>
                        <input type="date" name="SolicitudRenovacion" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Origen Poliza </label>
                        <select name="OrigenPoliza" id="OrigenPoliza" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($origen_poliza as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Fecha Vinculación</label>
                        <input type="date" name="FechaVinculacion" class="form-control">
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="form-horizontal">

                    <div class="col-sm-6">
                        <label class="control-label">Departamento </label>
                        <select name="DepartamentoNr" id="DepartamentoNr" class="form-control select2"
                            style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($departamento_nr as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Fecha Recepción</label>
                        <input type="date" name="FechaRecepcion" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-horizontal">
                    <div class="col-sm-6">
                        <label class="control-label">Sustituida por póliza</label>
                        <input type="date" name="SustituidaPoliza" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Observación Siniestro</label>
                        <input type="text" name="ObservacionSiniestro" class="form-control">
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Ejecutivo Cia</label>
                        <select name="EjecutivoCia" id="EjecutivoCia" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($tipo_cartera_nr as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label">Grupo Cliente</label>
                        <input type="text" name="GrupoCliente" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-horizontal">

                    <div class="col-sm-6">
                        <label class="control-label">Tipo de Deducible</label>
                        <select name="Deducible" id="Deducible" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($tipo_deducible as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row" style="display: flex; justify-content: space-around;">
                <div class="form-horizontal">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ url('poliza/seguro') }}" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        const desdeInput = document.getElementById('vigencia_desde');
        const hastaInput = document.getElementById('vigencia_hasta');
        const diasInput = document.getElementById('dias_vigencia');

        function calcularDias() {
            const desde = new Date(desdeInput.value);
            const hasta = new Date(hastaInput.value);

            if (!isNaN(desde.getTime()) && !isNaN(hasta.getTime())) {
                const diferencia = Math.ceil((hasta - desde) / (1000 * 60 * 60 * 24));
                diasInput.value = diferencia >= 0 ? diferencia : 0;
            } else {
                diasInput.value = '';
            }
        }

        desdeInput.addEventListener('change', calcularDias);
        hastaInput.addEventListener('change', calcularDias);

        function select_oferta(id) {
            document.getElementById('loading-overlay-modal').style.display = 'flex';

            var parametros = {
                "Oferta": document.getElementById('Oferta').value
            };

            $.ajax({
                type: "get",
                url: "{{ url('poliza/seguro/get_oferta') }}",
                data: parametros,
                success: function(data) {
                    document.getElementById('loading-overlay-modal').style.display = 'none';

                    console.log(data);

                    if (data.success) {
                        document.getElementById('FormaPago').value = data.oferta.forma_pago ?? '';
                        document.getElementById('NumeroDocumento').value = data.oferta.dui_cliente ?? '';
                        $('#Cliente').val(data.oferta.id_cliente ?? '').trigger('change');
                        $('#Productos').val(data.oferta.productos ?? '').trigger('change');
                        $('#Planes').val(data.oferta.planes ?? '').trigger('change');
                    } else {
                        // Vaciar los campos si no se obtuvo una oferta válida
                        document.getElementById('FormaPago').value = '';
                        document.getElementById('NumeroDocumento').value = '';
                        $('#Cliente').val('').trigger('change');
                        $('#Productos').val('').trigger('change');
                        $('#Planes').val('').trigger('change');

                        // Opcional: Mostrar mensaje
                        //alert(data.message || 'No se pudo obtener la oferta.');
                    }
                },
                error: function(xhr) {
                    document.getElementById('loading-overlay-modal').style.display = 'none';
                    document.getElementById('FormaPago').value = '';
                    document.getElementById('NumeroDocumento').value = '';
                    $('#Cliente').val('').trigger('change');
                    $('#Productos').val('').trigger('change');
                    $('#Planes').val('').trigger('change');
                    //alert('Ocurrió un error al procesar la solicitud.');
                }
            });
        }


        $("#Cliente").change(function() {
            document.getElementById('loading-overlay-modal').style.display = 'flex';

            var parametros = {
                "Cliente": document.getElementById('Cliente').value
            };

            $.ajax({
                type: "get",
                url: "{{ url('get_cliente') }}",
                data: parametros,
                success: function(data) {
                    console.log(data);
                    document.getElementById('NumeroDocumento').value = data.Nit;
                    document.getElementById('loading-overlay-modal').style.display = 'none';
                },
                error: function() {
                    document.getElementById('loading-overlay-modal').style.display = 'none';
                    alert('Error al obtener los datos del cliente.');
                }
            });
        });
    </script>
@endsection
