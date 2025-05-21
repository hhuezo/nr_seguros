@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nueva aseguradora <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('suscripciones') }}" class="btn btn-info fa fa-undo " style="color: white">Atrás</a>
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
                <form action="{{ url('suscripciones_update') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="x_content">
                        <br />
                        <input type="hidden" value="{{ $suscripcion->Id }}" name="Id">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label ">Fecha de Ingreso</label>
                                <input type="date" name="FechaIngreso"
                                    value="{{ date('Y-m-d', strtotime($suscripcion->FechaIngreso)) }}" class="form-control"
                                    autofocus="true">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Compania</label>
                                <select name="CompaniaId" id="CompaniaId" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($companias as $cia)
                                        <option value="{{ $cia->Id }}"
                                            {{ $suscripcion->CompaniaId == $cia->Id ? 'selected' : '' }}>{{ $cia->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Gestor</label>
                                <input type="text" name="Gestor" value="{{ $suscripcion->Gestor }}" class="form-control"
                                    autofocus="true">

                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">

                            <div class="col-sm-6">
                                <label class="control-label ">Contratante</label>
                                <input type="text" name="Contratante" value="{{ $suscripcion->Contratante }}"
                                    class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Número de Poliza Deuda</label>
                                <input type="text" name="PolizaDeuda" value="{{ $suscripcion->PolizaDeuda }}"
                                    class="form-control">
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">Número de Poliza Vida</label>
                                <input type="text" name="PolizaVida" value="{{ $suscripcion->PolizaVida }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">

                            <div class="col-sm-6">
                                <label class="control-label ">Asegurado</label>
                                <input type="text" name="Asegurado" value="{{ $suscripcion->Asegurado }}"
                                    class="form-control" autofocus="true" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Dui</label>
                                <input type="text" name="Dui" rows="1" class="form-control"
                                    oninput="this.value = this.value.toUpperCase()" value="{{ $suscripcion->Dui }}"
                                    data-inputmask="'mask': ['99999999-9']">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Edad</label>
                                <input type="number" name="Edad" value="{{ $suscripcion->Edad }}" class="form-control"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>


                        <div class="row" style="padding-top: 15px!important;">


                            <div class="col-sm-3">
                                <label class="control-label">Genero</label>
                                <select name="Genero" id="Genero" class="form-control">
                                    <option value="1" {{ $suscripcion->Genero == 1 ? 'selected' : '' }}>F</option>
                                    <option value="2" {{ $suscripcion->Genero == 2 ? 'selected' : '' }}>M</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Suma Asegurada Evaluada Deuda</label>
                                <input type="number" name="SumaAseguradaDeuda"
                                    value="{{ $suscripcion->SumaAseguradaDeuda }}" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Suma Asegurada Evaluada vida colectivo usuarios</label>
                                <input type="number" name="SumaAseguradaVida"
                                    value="{{ $suscripcion->SumaAseguradaVida }}" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tipo de Cliente</label>
                                <select name="TipoClienteId" id="TipoClienteId" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipo_clientes as $cliente)
                                        <option value="{{ $cliente->Id }}"
                                            {{ $suscripcion->TipoClienteId == $cliente->Id ? 'selected' : '' }}>
                                            {{ $cliente->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Peso (lb)</label>
                                <input type="text" name="Peso" value="{{ $suscripcion->Peso }}" id="Peso"
                                    class="form-control" onchange="calculo()">
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Estatura (m) </label>
                                <input type="text" name="Estatura" value="{{ $suscripcion->Estatura }}"
                                    id="Estatura" class="form-control" onchange="calculo()">
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">IMC</label>
                                <!-- <input type="checkbox"  class="js-switch" > -->
                                <input type="number" name="Imc" value="{{ $suscripcion->Imc }}" id="Imc"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tipo de IMC</label>
                                <input type="text" name="TipoIMCId" value="{{ $suscripcion->TipoIMCId }}"
                                    id="TipoImcId">
                                <input type="text" id="TipoIMCDes" class="form-control" readonly
                                    value="{{ $suscripcion->tipoImc->Nombre ?? '' }}">
                                <!-- <input type="checkbox"  class="js-switch" > -->
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Padecimientos</label>
                                <input type="text" id="Padecimiento" name="Padecimiento" class="form-control"
                                    value="{{ $suscripcion->Padecimiento }}">
                                <!-- <input type="checkbox"  class="js-switch" > -->
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tipo de Orden Medica</label>
                                <!-- <input type="text" name="TipoOrdenMedicaId" value="{{ $suscripcion->TipoOrdenMedicaId }}" id="TipoOrdenMedicaId" class="form-control"> -->
                                <select name="TipoOrdenMedicaId" id="TipoOrdenMedicaId" class="form-control">
                                    @foreach ($tipo_orden as $tipo)
                                        <option value="{{ $tipo->Id }}"
                                            {{ $suscripcion->TipoOrdenMedicaId == $tipo->Id ? 'selected' : '' }}>
                                            {{ $tipo->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Resumen de Gestión</label>
                                <textarea name="ResumenGestion" id="ResumenGestion" class="form-control" rows="3">{{ $suscripcion->ResumenGestion }}</textarea>
                                <!-- <input type="checkbox"  class="js-switch" > -->
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Fecha de Reportado Cia</label>
                                <input type="date" name="FechaReportadoCia"
                                    value="{{ date('Y-m-d', strtotime($suscripcion->FechaReportadoCia)) }}"
                                    id="FechaReportadoCia" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tareas Eva (Sisa)</label>
                                <input type="text" name="TareasEvaSisa" value="{{ $suscripcion->TareasEvaSisa }}"
                                    id="TareasEvaSisa" class="form-control">
                            </div>
                        </div>

                        <div class="row" style="padding-top: 15px!important;">

                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Resolución Oficial</label>
                                <textarea name="ResolucionFinal" id="ResolucionFinal" class="form-control" rows="3">{{ $suscripcion->ResolucionFinal }}</textarea>
                                <!-- <input type="checkbox"  class="js-switch" > -->
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Fecha de Resolución</label>
                                <input type="date" name="FechaResolucion"
                                    value="{{ date('Y-m-d', strtotime($suscripcion->FechaResolucion)) }}"
                                    id="FechaResolucion" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">% ExtraPrima</label>
                                <input type="number" name="ValorExtraPrima" value="{{ $suscripcion->ValorExtraPrima }}"
                                    id="ValorExtraPrima" class="form-control">
                            </div>
                            <br>

                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Estado del Caso</label>
                                <!-- <input type="text" name="TipoOrdenMedicaId" value="{{ old('TipoOrdenMedicaId') }}" id="TipoOrdenMedicaId" class="form-control"> -->
                                <select name="EstadoId" id="EstadoId" class="form-control">
                                    @foreach ($estados as $tipo)
                                        <option value="{{ $tipo->Id }}"
                                            {{ $suscripcion->EstadoId == $tipo->Id ? 'selected' : '' }}>
                                            {{ $tipo->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <br><br>
                        <div class="form-group" align="center">
                            <button class="btn btn-success" type="submit">Guardar</button>
                            <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary"
                                    type="button">Cancelar</button></a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="x_title">
                    <h2>Comentarios <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <button type="button" class="btn btn-success" style="color: white" data-target="#modal-create"
                            data-toggle="modal"> <i class="fa fa-plus"></i>
                            Agregar</button>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <table class="table table-striped jambo_table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Comentario</th>
                             <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suscripcion->comentarios as $comen)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($comen->FechaCreacion)) }}</td>
                                <td>{{ $comen->Usuario }}</td>
                                <td>{{ $comen->Comentario }}</td>

                                <td align="center">
                                        <a
                                            class="btn btn-primary" class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i></a>
                                            <a href="#" class="btn btn-danger"><i
                                                class="fa fa-trash fa-lg"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>

            </div>
        </div>
    </div>



    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-create">

        <form action="{{ url('suscripciones/agregar_comentario') }}" method="POST" class="forms-sample">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Nuevo comentario</h4>
                    </div>
                    <div class="modal-body">
                        <label for="DireccionResidencia" class="form-label">Comentario</label>
                        <input type="hidden" value="{{ $suscripcion->Id }}" name="SuscripcionId">
                        <textarea name="Comentario" class="form-control" rows="3" required>{{ old('Comentario') }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </form>

    </div>


    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        function calculo() {
            const peso = document.getElementById('Peso').value;
            const estatura = document.getElementById('Estatura').value;

            if (peso !== '' && estatura !== '') {
                // Enviar peso y estatura al backend
                $.get("{{ url('get_imc') }}", {
                    peso: peso,
                    estatura: estatura
                }, function(data) {
                    console.log(data);

                    document.getElementById('Imc').value = data.data.imc;
                    document.getElementById('TipoImcId').value = data.data.tipo_id;
                    document.getElementById('TipoIMCDes').value = data.data.desc_tipo;
                });
            }
        }
    </script>

@endsection
