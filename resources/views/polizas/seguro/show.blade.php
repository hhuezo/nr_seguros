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

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="x_panel">

        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Polizas de Seguro </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('poliza/seguro/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-arrow-left"></i></button></a>
            </div>
            <div class="clearfix"></div>
        </div>
        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
            <li class="nav-item {{ isset($tab) && $tab == 1 ? 'active in' : '' }}">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">Póliza</a>
            </li>
            <li class="nav-item {{ isset($tab) && $tab == 2 ? 'active in' : '' }}">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">Cobertura</a>
            </li>
            <li class="nav-item {{ isset($tab) && $tab == 3 ? 'active in' : '' }}">
                <a class="nav-link" id="hoja-tab" data-toggle="tab" href="#hoja" role="tab" aria-controls="hoja"
                    aria-selected="false">Datos Técnicos</a>
            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ isset($tab) && $tab == 1 ? 'active in' : '' }}" id="home" role="tabpanel"
                aria-labelledby="home-tab">
                <form action="{{ url('poliza/seguro/save') }}/{{ $poliza_seguro->Id }}" method="post">
                    @csrf
                    <div class="row">

                        <div class="form-horizontal">
                            <div class="col-sm-6" style="background-color: lightgrey; padding: 12px; border-radius: 15px;">
                                <label class="control-label"># Oferta</label>
                                <select name="Oferta" id="Oferta" class="form-control" onchange="select_oferta(this)">
                                    <option value="">Seleccionar</option>
                                    @foreach ($ofertas as $off)
                                        <option value="{{ $off->Id }}"
                                            {{ $off->Id == $poliza_seguro->Oferta ? 'selected' : '' }}>
                                            {{ $off->clientes->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Forma de Pago *</label>
                                <select name="FormaPago" id="FormaPago" class="form-control" required>
                                    @foreach ($forma_pago as $pago)
                                        <option value="{{ $loop->index }}"
                                            {{ $loop->index == $poliza_seguro->FormaPago ? 'selected' : '' }}>
                                            {{ $pago == '' ? 'Seleccione...' : $pago }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-horizontal">
                            <div class="col-sm-6">
                                <label class="control-label">Número Póliza *</label>
                                <input type="text" name="NumeroPoliza" class="form-control" required
                                    value="{{ $poliza_seguro->NumeroPoliza }}">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Estado de Poliza *</label>
                                <select name="EstadoPoliza" id="EstadoPoliza" class="form-control" required>
                                    @foreach ($estado_poliza as $estado)
                                        <option value="{{ $estado->Id }}"
                                            {{ $estado->Id == $poliza_seguro->EstadoPoliza ? 'selected' : '' }}>
                                            {{ $estado->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Productos -->
                            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                <label class="control-label">Productos *</label>
                                <select name="Productos" id="Productos" class="form-control select2" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($productos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->Productos ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Planes -->
                            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                <label class="control-label">Planes *</label>
                                <select name="Planes" id="Planes" class="form-control select2" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($planes as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->Planes ? 'selected' : '' }}>{{ $obj->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">ID Cliente *</label>
                                <input type="text" name="IdCliente" id="IdCliente" class="form-control" id="IdCliente"
                                    required value="{{ $poliza_seguro->clientes->Dui ?? '' }}">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Nombre Cliente *</label>
                                <select name="Cliente" id="Cliente" class="form-control select2" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($clientes as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->clientes->Id ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-sm-6">
                                <label class="control-label">Vigencia Desde *</label>
                                <input class="form-control" id="vigencia_desde" name="VigenciaDesde" type="date"
                                    value="{{ $poliza_seguro->VigenciaDesde }}" required>
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">Vigencia Hasta *</label>
                                <input class="form-control" id="vigencia_hasta" name="VigenciaHasta" type="date"
                                    value="{{ $poliza_seguro->VigenciaHasta }}" required>

                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">Días</label>
                                <input type="number" name="DiasVigencia" id="dias_vigencia" class="form-control"
                                    readonly value="{{ $poliza_seguro->DiasVigencia }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Motivo Cancelación</label>
                                <input type="text" name="MotivoCancelacion" class="form-control"
                                    value="{{ $poliza_seguro->MotivoCancelacion }}">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Fecha Cancelación</label>
                                <input type="date" name="FechaCancelacion" class="form-control">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Cod Cancelación</label>
                                <select name="CodCancelacion" id="CodCancelacion" class="form-control select2"
                                    style="width: 100%" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($cancelacion as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->CodCancelacion ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Fecha Envío Anexo</label>
                                <input type="date" name="FechaEnvioAnexo" class="form-control"
                                    value="{{ $poliza_seguro->FechaEnvioAnexo }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-horizontal">

                            <div class="col-sm-6">
                                <label class="control-label">Observación Ren. </label>
                                <input type="text" name="Observacion" class="form-control"
                                    value="{{ $poliza_seguro->Observacion }}">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Solicitud Renovación</label>
                                <input type="date" name="SolicitudRenovacion" class="form-control"
                                    value="{{ $poliza_seguro->SolicitudRenovacion }}">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Origen Poliza </label>
                                <select name="OrigenPoliza" id="OrigenPoliza" class="form-control select2"
                                    style="width: 100%">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($origen_poliza as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->OrigenPoliza ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Fecha Vinculación</label>
                                <input type="date" name="FechaVinculacion" class="form-control"
                                    value="{{ $poliza_seguro->FechaVinculacion }}">
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
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->Departamento ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Fecha Recepción</label>
                                <input type="date" name="FechaRecepcion" class="form-control"
                                    value="{{ $poliza_seguro->FechaRecepcion }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-horizontal">
                            <div class="col-sm-6">
                                <label class="control-label">Sustituida por póliza</label>
                                <input type="date" name="SustituidaPoliza" class="form-control"
                                    value="{{ $poliza_seguro->SustituidaPoliza }}">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Observación Siniestro</label>
                                <input type="text" name="ObservacionSiniestro" class="form-control"
                                    value="{{ $poliza_seguro->ObservacionSiniestro }}">
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Ejecutivo Cia</label>
                                <select name="EjecutivoCia" id="EjecutivoCia" class="form-control select2"
                                    style="width: 100%">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($tipo_cartera_nr as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->EjecutivoCia ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label">Grupo Cliente</label>
                                <input type="text" name="GrupoCliente" class="form-control"
                                    value="{{ $poliza_seguro->GrupoCliente }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-horizontal">

                            <div class="col-sm-6">
                                <label class="control-label">Tipo de Deducible</label>
                                <select name="Deducible" id="Deducible" class="form-control select2"
                                    style="width: 100%">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($tipo_deducible as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $obj->Id == $poliza_seguro->Deducible ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
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
            <div class="tab-pane fade {{ isset($tab) && $tab == 2 ? 'active in' : '' }}" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">
                <div style="text-align: right">
                    <button class="btn btn-primary" data-toggle="modal"
                        data-target="#modal-nuevo-cobertura">Agregar</button>
                </div>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripción</th>
                            <th>Tarificación</th>
                            <th>Descuento</th>
                            <th>IVA</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poliza_seguro->coberturas as $cobertura)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cobertura->Nombre ?? '' }}</td>
                                <td>{{ $cobertura->Tarificacion ? 'Millar' : 'Porcentual' }}</td>
                                <td>{{ $cobertura->Descuento ? 'Si' : 'No' }}</td>
                                <td>{{ $cobertura->Iva ? 'Si' : 'No' }}</td>
                                <td style="text-align: center">
                                    <button class="btn btn-danger" type="button"
                                        data-target="#modal-cobertura-delete-{{ $cobertura->Id }}" data-toggle="modal"><i
                                            class="fa fa-trash fa-lg"></i></button>
                                </td>
                                {{-- <td><input type="text" class="form-control"
                                        onchange="update_cobertura({{ $obj->Id }}, event)"
                                        value="{{ $obj->Valor }}"></td> --}}
                            </tr>
                            @include('polizas.seguro.modal_coberura_delete')
                        @endforeach
                    </tbody>
                </table>


            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 3 ? 'active in' : '' }}" id="hoja" role="tabpanel"
                aria-labelledby="hoja-tab">
                <div style="text-align: right">
                    <button class="btn btn-primary" data-toggle="modal"
                        data-target="#modal-nuevo-dato_tecnico">Agregar</button>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poliza_seguro->datosTecnicos as $dato)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dato->Nombre }}</td>
                                <td>{{ $dato->Descripcion }}</td>
                                <td style="text-align: center">
                                    <button class="btn btn-danger" type="button"
                                        data-target="#modal-dato-tecnico-delete-{{ $dato->Id }}"
                                        data-toggle="modal"><i class="fa fa-trash fa-lg"></i></button>
                                </td>
                                {{-- <td><input type="text" class="form-control"
                                        onchange="update_datos_tecnicos({{ $obj->Id }}, event)"
                                        value="{{ $obj->Valor }}"></td> --}}
                            </tr>
                            @include('polizas.seguro.modal_dato_tecnico_delete')
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>




    <div>
        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
            id="modal-nuevo-cobertura">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('poliza/seguro/cobertura_store') }}/{{ $poliza_seguro->Id }}">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Nueva cobertura</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Nombre
                                    <input type="text" name="Nombre" value="{{ old('Nombre') }}"
                                        class="form-control" onblur="this.value = this.value.toUpperCase()" required>
                                </div>

                                <div class="col-sm-6">
                                    Tarificación
                                    <select name="Tarificacion" class="form-control" required>
                                        <option value="0" {{ old('Tarificacion') == '0' ? 'selected' : '' }}>
                                            Porcentual</option>
                                        <option value="1" {{ old('Tarificacion') == '1' ? 'selected' : '' }}>Millar
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div>&nbsp; </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Descuento
                                    <select name="Descuento" class="form-control" required>
                                        <option value="0" {{ old('Descuento') == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('Descuento') == '1' ? 'selected' : '' }}>Si</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    IVA
                                    <select name="Iva" class="form-control" required>
                                        <option value="0" {{ old('Iva') == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('Iva') == '1' ? 'selected' : '' }}>Si</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div>&nbsp; </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                </form>

            </div>
        </div>
    </div>

    <div>
        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
            id="modal-nuevo-dato_tecnico">
            <div class="modal-dialog">
                <form method="POST" action="{{ url('poliza/seguro/dato_tecnico_store') }}/{{ $poliza_seguro->Id }}">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Nuevo dato técnico</h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="row">
                                        Nombre
                                        <input type="text" name="Nombre" class="form-control" onblur="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>
                                <div>&nbsp; </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        Descripción
                                        <textarea class="form-control" name="Descripcion" onblur="this.value = this.value.toUpperCase()"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                </form>

            </div>
        </div>
    </div>





    <script>
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-poliza_seguro");
        });

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


        function update_cobertura(id) {
            const input = event.target;
            const valor = input.value;
            var url = new URL('{{ url('poliza/cobertura/update') }}/' + id);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        valor: valor
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // alert('Valor actualizado correctamente');
                        Swal.fire('Valor actualizado correctamente');
                    } else {
                        // alert('');
                        Swal.fire('Error al actualizar');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // alert('Ocurrió un error inesperado');
                });
        }



        function update_datos_tecnicos(id) {
            const input = event.target;
            const valor = input.value;
            var url = new URL('{{ url('poliza/datos_tecnicos/update') }}/' + id);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        valor: valor
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // alert('Valor actualizado correctamente');
                        Swal.fire('Valor actualizado correctamente');
                    } else {
                        // alert('');
                        Swal.fire('Error al actualizar');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // alert('Ocurrió un error inesperado');
                });
        }
    </script>
@endsection
