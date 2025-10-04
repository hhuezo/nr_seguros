@extends ('welcome')
@section('contenido')

    @include('sweetalert::alert')
    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="x_title">
                <h2>Nuevo registro <small></small></h2>
                {{-- <ul class="nav navbar-right panel_toolbox">
                <a href="{{ url('suscripciones') }}" class="btn btn-info fa fa-undo " style="color: white">Atrás</a>
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

            <form action="{{ url('suscripciones') }}" method="POST" class="forms-sample">
                @csrf

                <div class="x_content">

                    <div class="form-horizontal">

                        <div class="col-sm-4">
                            <label class="control-label "># Tarea</label>
                            <input type="text" name="NumeroTarea" class="form-control">
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label">Ejecutivo</label>
                            <select name="Gestor" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach ($ejecutivos as $ejecutivo)
                                    <option value="{{ $ejecutivo->Id }}">{{ $ejecutivo->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-sm-4">
                            <label class="form-label">Estado del Caso</label>
                            <select name="EstadoId" id="EstadoId" class="form-control">
                                @foreach ($estados as $tipo)
                                    <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Datos póliza</h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Fecha de Ingreso</label>
                            <input type="date" name="FechaIngreso" id="FechaIngreso"  value="{{ date('Y-m-d') }}" class="form-control">
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Días para completar información (cliente)</label>
                            <input type="number" name="DiasCompletarInfoCliente" id="DiasCompletarInfoCliente"
                                value="{{ old('DiasCompletarInfoCliente') }}" class="form-control">
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Fecha entrega documentos completos</label>
                            <input type="date" name="FechaEntregaDocsCompletos" id="FechaEntregaDocsCompletos"
                                value="{{ old('FechaEntregaDocsCompletos') }}" class="form-control">
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Aseguradora</label>
                            <select name="CompaniaId" id="CompaniaId" class="form-control">
                                <option value="">Seleccione...</option>
                                @foreach ($aseguradoras as $cia)
                                    <option value="{{ $cia->Id }}">{{ $cia->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Categoria</label>
                            <select name="CategoriaSisa" id="CategoriaSisa" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="ALTERNA">ALTERNA</option>
                                <option value="TRADICIONAL">TRADICIONAL</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label ">Contratante</label>
                            <select name="ContratanteId" class="form-control select2">
                                <option value="">Seleccione</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->Id }}">{{ $cliente->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label ">Número de Poliza Deuda</label>
                            <select name="PolizaDeuda" class="form-control select2" required>
                                <option value="">Seleccione</option>
                                @foreach ($polizas_deuda as $deuda)
                                    <option value="{{ $deuda->Id }}">{{ $deuda->NumeroPoliza }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label">Número de Poliza Vida</label>
                            <select name="PolizaVida" class="form-control select2">
                                <option value="">Seleccione</option>
                                @foreach ($polizas_vida as $vida)
                                    <option value="{{ $vida->Id }}">{{ $vida->NumeroPoliza }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label ">Suma Asegurada Evaluada Deuda</label>
                            <input type="number" name="SumaAseguradaDeuda" value="{{ old('SumaAseguradaDeuda') }}"
                                step="any" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Suma Asegurada Evaluada vida colectivo usuarios</label>
                            <input type="number" name="SumaAseguradaVida" value="{{ old('SumaAseguradaVida') }}"
                                step="any" class="form-control">
                        </div>

                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Datos cliente</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label">DUI/Otro doc. de identidad</label>
                            <input type="text" name="Dui" id="Dui" rows="1" class="form-control"
                                value="{{ old('Dui') }}">
                        </div>


                        <div class="col-sm-4">
                            <label for="DireccionResidencia" class="form-label">Tipo de Cliente</label>
                            <select name="TipoClienteId" id="TipoClienteId" class="form-control">
                                <option value="">Seleccione...</option>
                                @foreach ($tipo_clientes as $cliente)
                                    <option value="{{ $cliente->Id }}">{{ $cliente->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Tipo crédito</label>
                            <select name="TipoCreditoId" id="TipoCreditoId" class="form-control">
                                <option value="">Seleccione...</option>
                                @foreach ($tipo_creditos as $obj)
                                    <option value="{{ $obj->Id }}">
                                        {{ $obj->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-sm-4">
                            <label class="control-label ">Asegurado</label>
                            <input type="text" name="Asegurado" value="{{ old('Asegurado') }}" class="form-control"
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                        </div>

                        <div class="col-sm-2">
                            <label class="control-label ">Edad</label>
                            <input type="number" name="Edad" value="{{ old('Edad') }}" class="form-control">
                        </div>

                        <div class="col-sm-2">
                            <label class="control-label">Genero</label>
                            <select name="Genero" id="Genero" class="form-control">
                                <option value="1" {{ old('Genero') == 1 ? 'selected' : '' }}>F</option>
                                <option value="2" {{ old('Genero') == 2 ? 'selected' : '' }}>M</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Ocupación</label>

                            <div class="input-group">
                                <select name="OcupacionId" id="OcupacionId" class="form-control select2">
                                    <option value="">Seleccione...</option>
                                    @foreach ($ocupaciones as $obj)
                                        <option value="{{ $obj->Id }}">
                                            {{ $obj->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-target="#modal-create"
                                        data-toggle="modal">+</button>
                                </span>
                            </div>
                        </div>




                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Declaración de salud y evaluación</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">Peso (lb)</label>
                            <input type="number" name="Peso" value="{{ old('Peso') }}" id="Peso"
                                class="form-control" onchange="calculo()">
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">Estatura (m) </label>
                            <input type="decimal" name="Estatura" value="{{ old('Estatura') }}" id="Estatura"
                                step="any" class="form-control" onchange="calculo()">
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">IMC</label>
                            <input type="number" name="Imc" value="{{ old('Imc') }}" id="Imc"
                                class="form-control" readonly>
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">Tipo de IMC</label>
                            <select name="TipoIMCId" id="TipoImcId" class="form-control">
                                <option value="">Seleccione...</option>
                                @foreach ($tipos_imc as $tipo)
                                    <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Tipo de Orden Medica</label>
                            <select name="TipoOrdenMedicaId" id="TipoOrdenMedicaId" class="form-control">
                                @foreach ($tipo_orden as $tipo)
                                    <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Padecimientos</label>
                            <textarea id="Padecimiento" name="Padecimiento" class="form-control"></textarea>
                        </div>

                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Gestiones</h2>
                            <div class="clearfix"></div>
                        </div>


                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <label for="DireccionResidencia" class="form-label">Resumen de Gestión</label>
                                    <select name="ResumenGestion" id="ResumenGestion" class="form-control"
                                        onchange="resumenGestionChanged(this.value)">
                                        <option value="">SELECCIONE</option>
                                        @foreach ($resumen_gestion as $resumen)
                                            @if ($resumen->Id != 20)
                                                <option value="{{ $resumen->Id }}" class=" bg-{{ $resumen->Color }}">
                                                    {{ $resumen->Nombre }}</option>
                                            @else
                                                <option value="{{ $resumen->Id }}"
                                                    style="background-color: #000;color: #fff;">
                                                    {{ $resumen->Nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="DireccionResidencia" class="form-label">Fecha de Reportado Cia /
                                        Resolución Anticipada</label>
                                    <input type="date" name="FechaReportadoCia"
                                        value="{{ old('FechaReportadoCia') }}" id="FechaReportadoCia"
                                        class="form-control">
                                </div>


                                <div class="col-sm-6">
                                    <label for="DireccionResidencia" class="form-label">Tareas Eva (Sisa)</label>
                                    <input type="text" name="TareasEvaSisa" value="{{ old('TareasEvaSisa') }}"
                                        id="TareasEvaSisa" class="form-control">
                                </div>

                                <div class="col-sm-6">
                                    <label class="control-label ">Trabajo efectuado día hábil</label>
                                    <input type="number" name="TrabajadoEfectuadoDiaHabil"
                                        id="TrabajadoEfectuadoDiaHabil" value="{{ old('TrabajadoEfectuadoDiaHabil') }}"
                                        class="form-control">
                                </div>

                                <div class="col-sm-6">
                                    <label class="control-label ">Fecha cierre de gestión</label>
                                    <input type="date" name="FechaCierreGestion"
                                        value="{{ old('FechaCierreGestion') }}" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label ">Fecha de envio de corrección</label>
                                    <input type="date" name="FechaEnvioCorreccion"
                                        value="{{ old('FechaEnvioCorreccion') }}" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label ">Total dias ciclo de proceso</label>
                                    <input type="text" name="TotalDiasProceso" readonly
                                        value="{{ old('TotalDiasProceso') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Reproceso de NR</label>
                                <select name="ReprocesoId" id="ReprocesoId" class="form-control">
                                    <option value="">SELECCIONE</option>
                                    @foreach ($reprocesos as $repro)
                                        <option value="{{ $repro->Id }}">
                                            {{ $repro->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Comentarios NR</label>
                                <textarea name="Comentarios" rows="7" class="form-control">{{ old('Comentarios') }}</textarea>

                            </div>

                        </div>



                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Resolución brindada</h2>
                            <div class="clearfix"></div>
                        </div>


                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Resolución Oficial</label>
                            <textarea name="ResolucionFinal" id="ResolucionFinal" class="form-control" rows="4">{{ old('ResolucionFinal') }}</textarea>
                        </div>

                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">Fecha de recepción de Resolución de
                                CIA</label>
                            <input type="date" name="FechaResolucion" value="{{ old('FechaResolucion') }}"
                                id="FechaResolucion" class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">% ExtraPrima</label>
                            <input type="number" name="ValorExtraPrima" value="{{ old('ValorExtraPrima') }}"
                                step="any" id="ValorExtraPrima" class="form-control">
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label ">Fecha de envió de resolución al cliente</label>
                            <input type="date" name="FechaEnvioResoCliente" id="FechaEnvioResoCliente"
                                value="{{ old('FechaEnvioResoCliente') }}" class="form-control">
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label ">Dias de procesamiento de resolución</label>
                            <input type="number" name="DiasProcesamiento" id="DiasProcesamiento"
                                value="{{ old('DiasProcesamiento') }}" readonly class="form-control">
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>
                <br>
                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('suscripciones') }}"><button class="btn btn-primary"
                            type="button">Cancelar</button></a>
                </div>




            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-create" tabindex="-1" user="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Nueva Ocupación</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <form id="formCrearOcupacion">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" name="Nombre" type="text" autofocus
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        document.getElementById('Dui').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9\-]/g, '');
        });


        function resumenGestionChanged(id) {
            if (id > 8) {
                document.getElementById('EstadoId').value = 2;
            } else {
                document.getElementById('EstadoId').value = 1;
            }
        }

        function calculo() {
            const peso = document.getElementById('Peso').value;
            const estatura = document.getElementById('Estatura').value;

            if (peso !== '' && estatura !== '') {

                var subTotalPeso = peso / 2.2;
                var subTotalEstatura = estatura * estatura;

                var total = subTotalPeso / subTotalEstatura;

                console.log("subTotalPeso " + subTotalPeso);
                console.log("subTotalEstatura " + subTotalEstatura);
                console.log("total " + total);


                document.getElementById('Imc').value = total.toFixed(2);



                let tipo_imc = 1;

                if (total < 18.5) {
                    tipo_imc = 1;
                } else if (total >= 18.5 && total < 24.9) {
                    tipo_imc = 2;
                } else if (total >= 25 && total < 29.9) {
                    tipo_imc = 3;
                } else if (total >= 30 && total < 34.9) {
                    tipo_imc = 4;
                } else if (total >= 35 && total < 39.9) {
                    tipo_imc = 5;
                } else if (total >= 40 && total < 49.9) {
                    tipo_imc = 6;
                } else {
                    tipo_imc = 7;
                }

                document.getElementById('TipoImcId').value = tipo_imc;

            }


            function formatToTwoNonZeroDecimals(num) {
                const decimals = num.toString().split('.')[1] || '';
                let count = 0;
                let result = '';

                for (let i = 0; i < decimals.length; i++) {
                    if (decimals[i] !== '0') {
                        result += decimals[i];
                        count++;
                        if (count === 2) break;
                    } else {
                        result += decimals[i];
                    }
                }

                return '0.' + result.padEnd(2, '0');
            }


        }

        $(document).ready(function() {

            // Mostrar opción en menú
            displayOption("ul-suscripciones", "li-suscripciones");

            $('#FechaResolucion, #FechaEnvioResoCliente').change(function() {
                var inicio = $('#FechaResolucion').val();
                var fin = $('#FechaEnvioResoCliente').val();

                if (inicio && fin) {
                    $.ajax({
                        url: "{{ route('calcular.dias.habiles.json') }}",
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'fecha_inicio': inicio,
                            'fecha_fin': fin
                        },
                        success: function(response) {
                            $('#DiasProcesamiento').val(response.dias_habiles);
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseJSON);
                            $('#DiasProcesamiento').val("");
                        }
                    });
                }
            });


            $('#FechaIngreso, #FechaEntregaDocsCompletos').change(function() {

                var inicio = $('#FechaIngreso').val();
                var fin = $('#FechaEntregaDocsCompletos').val();

                if (inicio && fin) {
                    $.ajax({
                        url: "{{ route('calcular.dias.habiles.json') }}",
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'fecha_inicio': inicio,
                            'fecha_fin': fin
                        },
                        success: function(response) {
                            $('#DiasCompletarInfoCliente').val(response.dias_habiles);
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseJSON);
                            $('#DiasCompletarInfoCliente').val("");
                        }
                    });
                }
            });

            // Enviar formulario via AJAX
            $('#formCrearOcupacion').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('ocupaciones.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Cerrar modal y limpiar formulario
                        $('#modal-create').modal('hide');
                        $('#formCrearOcupacion').trigger('reset');

                        // Agregar nueva opción al Select2
                        var newOption = new Option(
                            response.ocupacion.Nombre,
                            response.ocupacion.Id,
                            true, // selected
                            true // selected
                        );

                        $('#OcupacionId').append(newOption).trigger('change');

                        // Mostrar notificación
                        Swal.fire({
                            title: '¡Éxito!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        })
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error: ' + Object.values(errors)[0][0],
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error inesperado',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    }
                });
            });
        });
    </script>

@endsection
