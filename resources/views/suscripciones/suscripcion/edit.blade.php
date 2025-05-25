@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Editar Suscripción </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('suscripciones') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-arrow-left"></i></button></a>
            </div>
            <div class="clearfix"></div>
        </div>


        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
            <li class="nav-item {{ $tab == 1 ? 'active in' : '' }}">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">General</a>
            </li>
            <li class="nav-item {{ $tab == 2 ? 'active in' : '' }}">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">Comentarios</a>
            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ $tab == 1 ? 'active in' : '' }}" id="home" role="tabpanel"
                aria-labelledby="home-tab">

                <form action="{{ url('suscripciones_update') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="x_content">
                        <input type="hidden" value="{{ $suscripcion->Id }}" name="Id">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label ">Fecha de Ingreso</label>
                                <input type="date" name="FechaIngreso"
                                    value="{{ date('Y-m-d', strtotime($suscripcion->FechaIngreso)) }}" class="form-control"
                                    autofocus="true">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Aseguradora</label>
                                <select name="CompaniaId" id="CompaniaId" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($aseguradoras as $cia)
                                        <option value="{{ $cia->Id }}"
                                            {{ $suscripcion->CompaniaId == $cia->Id ? 'selected' : '' }}>
                                            {{ $cia->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Ejecutivo </label>
                                {{-- <input type="text" name="Gestor" value="{{old('Gestor')}}" class="form-control"> --}}
                                <select name="Gestor" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($ejecutivos as $ejecutivo)
                                        <option value="{{ $ejecutivo->id }}"
                                            {{ $suscripcion->GestorId == $ejecutivo->id ? 'selected' : '' }}>
                                            {{ $ejecutivo->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">

                            <div class="col-sm-6">
                                <label class="control-label ">Contratante</label>
                                <select name="ContratanteId" class="form-control select2">
                                    <option value="">Seleccione...</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->Id }}"
                                            {{ $suscripcion->ContratanteId == $cliente->Id ? 'selected' : '' }}>
                                            {{ $cliente->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Número de Poliza Deuda</label>
                                <select name="PolizaDeuda" class="form-control select2">
                                    <option value="">Seleccione...</option>
                                    @foreach ($polizas_deuda as $deuda)
                                        <option value="{{ $deuda->Id }}"
                                            {{ $suscripcion->PolizaDeuda == $deuda->Id ? 'selected' : '' }}>
                                            {{ $deuda->NumeroPoliza }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">Número de Poliza Vida</label>
                                <select name="PolizaVida" class="form-control select2">
                                    <option value="">Seleccione...</option>
                                    @foreach ($polizas_vida as $vida)
                                        <option value="{{ $vida->Id }}"
                                            {{ $suscripcion->PolizaVida == $vida->Id ? 'selected' : '' }}>
                                            {{ $vida->NumeroPoliza }}</option>
                                    @endforeach
                                </select>
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
                                    value="{{ $suscripcion->Dui }}" data-inputmask="'mask': ['99999999-9']">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Edad</label>
                                <input type="number" name="Edad" value="{{ $suscripcion->Edad }}"
                                    class="form-control" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>


                        <div class="row" style="padding-top: 15px!important;">


                            <div class="col-sm-3">
                                <label class="control-label">Genero</label>
                                <select name="Genero" id="Genero" class="form-control">
                                    <option value="1" {{ $suscripcion->Genero == 1 ? 'selected' : '' }}>F
                                    </option>
                                    <option value="2" {{ $suscripcion->Genero == 2 ? 'selected' : '' }}>M
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Suma Asegurada Evaluada Deuda</label>
                                <input type="float" name="SumaAseguradaDeuda"
                                    value="{{ number_format($suscripcion->SumaAseguradaDeuda, 2, '.', ',') }}"
                                    class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label ">Suma Asegurada Evaluada vida colectivo
                                    usuarios</label>
                                <input type="float" name="SumaAseguradaVida"
                                    value="{{ number_format($suscripcion->SumaAseguradaVida, 2, '.', ',') }}"
                                    class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tipo de Cliente</label>
                                <select name="TipoClienteId" id="TipoClienteId" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipo_clientes as $cliente)
                                        <option value="{{ $cliente->Id }}"
                                            {{ $suscripcion->TipoClienteId == $cliente->Id ? 'selected' : '' }}>
                                            {{ $cliente->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-2">
                                <label for="DireccionResidencia" class="form-label">Peso (lb)</label>
                                <input type="float" name="Peso" value="{{ $suscripcion->Peso }}" id="Peso"
                                    class="form-control" onchange="calculo()">
                            </div>
                            <div class="col-sm-2">
                                <label for="DireccionResidencia" class="form-label">Estatura (m) </label>
                                <input type="float" name="Estatura" value="{{ $suscripcion->Estatura }}"
                                    id="Estatura" class="form-control" onchange="calculo()">
                            </div>
                            <div class="col-sm-2">
                                <label for="DireccionResidencia" class="form-label">IMC</label>
                                <!-- <input type="checkbox"  class="js-switch" > -->
                                <input type="number" name="Imc"
                                    value="{{ number_format($suscripcion->Imc, 2, '.', ',') }}" id="Imc"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tipo de IMC</label>

                                <select name="TipoIMCId" id="TipoImcId" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipos_imc as $tipo)
                                        <option value="{{ $tipo->Id }}"
                                            {{ $suscripcion->TipoIMCId == $tipo->Id ? 'selected' : '' }}>
                                            {{ $tipo->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tipo de Orden Medica</label>
                                <!-- <input type="text" name="TipoOrdenMedicaId" value="{{ $suscripcion->TipoOrdenMedicaId }}" id="TipoOrdenMedicaId" class="form-control"> -->
                                <select name="TipoOrdenMedicaId" id="TipoOrdenMedicaId" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipo_orden as $tipo)
                                        <option value="{{ $tipo->Id }}"
                                            {{ $suscripcion->TipoOrdenMedicaId == $tipo->Id ? 'selected' : '' }}>
                                            {{ $tipo->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Padecimientos</label>
                                <textarea type="text" id="Padecimiento" name="Padecimiento" class="form-control">{{ $suscripcion->Padecimiento }}</textarea>
                                <!-- <input type="checkbox"  class="js-switch" > -->
                            </div>

                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Resolución Oficial</label>
                                <textarea name="ResolucionFinal" class="form-control">{{ $suscripcion->ResolucionFinal }}</textarea>
                            </div>

                        </div>
                        <div class="row" style="padding-top: 15px!important;">


                            <div class="col-sm-6">
                                <label for="DireccionResidencia" class="form-label">Resumen de Gestión</label>
                                <select name="ResumenGestion" id="ResumenGestion" class="form-control">
                                    <option value="">SELECCIONE</option>
                                    @foreach ($resumen_gestion as $resumen)
                                        <option value="{{ $resumen->Id }}" class="bg-{{ $resumen->Color }}"
                                            {{ $suscripcion->ResumenGestion == $resumen->Id ? 'selected' : '' }}>
                                            {{ $resumen->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Fecha de Reportado Cia</label>
                                <input type="date" name="FechaReportadoCia"
                                    value="{{ isset($suscripcion->FechaReportadoCia) ? date('Y-m-d', strtotime($suscripcion->FechaReportadoCia)) : '' }}"
                                    class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Tareas Eva (Sisa)</label>
                                <input type="text" name="TareasEvaSisa" value="{{ $suscripcion->TareasEvaSisa }}"
                                    id="TareasEvaSisa" class="form-control">
                            </div>




                        </div>



                        <div class="row" style="padding-top: 15px!important;">


                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">Fecha de Resolución</label>
                                <input type="date" name="FechaResolucion"
                                    value="{{ $suscripcion->FechaResolucion ? date('Y-m-d', strtotime($suscripcion->FechaResolucion)) : '' }}"
                                    class="form-control">

                            </div>
                            <div class="col-sm-3">
                                <label for="DireccionResidencia" class="form-label">% ExtraPrima</label>
                                <input type="number" name="ValorExtraPrima" value="{{ $suscripcion->ValorExtraPrima }}"
                                    id="ValorExtraPrima" class="form-control">
                            </div>



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

                        <br>
                        <div class="form-group" align="center">
                            <button class="btn btn-success" type="submit">Guardar</button>
                            <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary"
                                    type="button">Cancelar</button></a>
                        </div>
                    </div>
                </form>


            </div>
            <div class="tab-pane fade {{ $tab == 2 ? 'active in' : '' }}" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">

                <ul class="nav navbar-right panel_toolbox">
                    <button type="button" class="btn btn-success" style="color: white" data-target="#modal-create"
                        data-toggle="modal"> <i class="fa fa-plus"></i>
                        Agregar</button>
                </ul>

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Comentario</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($suscripcion->comentarios as $comen)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d/m/Y', strtotime($comen->FechaCreacion)) }}</td>
                                <td>{{ $comen->usuario->name ?? '' }}</td>
                                <td>{{ $comen->Comentario }}</td>

                                <td align="center">
                                    <a class="btn btn-primary" class="on-default edit-row">
                                        <i class="fa fa-pencil fa-lg"></i></a>
                                    <a href="#" class="btn btn-danger"><i class="fa fa-trash fa-lg"></i></a>
                                </td>
                            </tr>
                             @php($i++)
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
