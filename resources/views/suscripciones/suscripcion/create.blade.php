@extends ('welcome')
@section('contenido')


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
                            <input type="text" name="NumeroTarea" readonly class="form-control">
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
                            <input type="date" name="FechaIngreso" value="{{ date('Y-m-d') }}" class="form-control">
                        </div>

                         <div class="col-sm-4">
                            <label class="control-label ">Días para completar información (cliente)</label>
                            <input type="number" name="DiasCompletarInfoCliente"
                                value="{{ old('DiasCompletarInfoCliente') }}" class="form-control">
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Fecha entrega documentos completos</label>
                            <input type="date" name="FechaEntregaDocsCompletos"
                                value="{{ old('FechaEntregaDocsCompletos') }}" class="form-control">
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label ">Aseguradora</label>
                            <select name="CompaniaId" id="CompaniaId" class="form-control">
                                <option value="">Seleccione...</option>
                                @foreach ($aseguradoras as $cia)
                                    <option value="{{ $cia->Id }}">{{ $cia->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
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
                            <select name="PolizaDeuda" class="form-control select2">
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
                            <input type="text" name="Dui" rows="1" class="form-control"
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
                                oninput="this.value = this.value.toUpperCase()">
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
                                    <button type="button" class="btn btn-primary">+</button>
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
                                    <select name="ResumenGestion" id="ResumenGestion" class="form-control">
                                        <option value="">SELECCIONE</option>
                                        @foreach ($resumen_gestion as $resumen)
                                            <option value="{{ $resumen->Id }}" class="bg-{{ $resumen->Color }}">
                                                {{ $resumen->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="DireccionResidencia" class="form-label">Fecha de Reportado Cia</label>
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
                                        value="{{ old('TrabajadoEfectuadoDiaHabil') }}" class="form-control">
                                </div>

                                <div class="col-sm-6">
                                    <label class="control-label ">Fecha cierre de gestión</label>
                                    <input type="date" name="FechaCierreGestion"
                                        value="{{ old('FechaCierreGestion') }}" class="form-control" autofocus="true">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Comentarios NR</label>
                                <textarea name="Comentarios" rows="4" class="form-control">{{ old('Comentarios') }}</textarea>

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
                            <label for="DireccionResidencia" class="form-label">Fecha de recepción de Resolución de CIA</label>
                            <input type="date" name="FechaResolucion" value="{{ old('FechaResolucion') }}"
                                id="FechaResolucion" class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">% ExtraPrima</label>
                            <input type="number" name="ValorExtraPrima" value="{{ old('ValorExtraPrima') }}"
                                step="any" id="ValorExtraPrima" class="form-control">
                        </div>






                        {{-- <div class="col-sm-3">
                            <label class="control-label ">Fecha de recepción de resolución de CIA</label>
                            <input type="date" name="FechaRecepcionResuCIA"
                                value="{{ old('FechaRecepcionResuCIA') }}" class="form-control" autofocus="true">
                        </div> --}}
                        <div class="col-sm-3">
                            <label class="control-label ">Fecha de envió de resolución al cliente</label>
                            <input type="date" name="FechaEnvioResoCliente"
                                value="{{ old('FechaEnvioResoCliente') }}" class="form-control" autofocus="true">
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label ">Dias de procesamiento de resolución</label>
                            <input type="number" name="DiasProcesamiento" value="{{ old('DiasProcesamiento') }}"
                                class="form-control" autofocus="true">
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

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
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
    </script>

@endsection
