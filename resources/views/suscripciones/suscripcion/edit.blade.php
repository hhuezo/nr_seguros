@extends ('welcome')
@section('contenido')
    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>
    <div class="x_panel">

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
                <h4>Editar Suscripción</h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a
                    href="{{ url('suscripciones') }}?Id={{ $suscripcion->Id }}&FechaInicio={{ $fechaInicio }}&FechaFinal={{ $fechaFinal }}"><button
                        class="btn btn-info float-right"> <i class="fa fa-arrow-left"></i></button></a>
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

                        <div class="col-sm-4">
                            <label class="control-label "># Tarea</label>
                            <input type="text" name="NumeroTarea" value="{{ $suscripcion->NumeroTarea }}" readonly
                                class="form-control">
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label">Ejecutivo </label>
                            {{-- <input type="text" name="Gestor" value="{{old('Gestor')}}" class="form-control"> --}}
                            <select name="Gestor" class="form-control">
                                <option value="">Seleccione...</option>
                                @foreach ($ejecutivos as $ejecutivo)
                                    <option value="{{ $ejecutivo->Id }}"
                                        {{ $suscripcion->GestorId == $ejecutivo->Id ? 'selected' : '' }}>
                                        {{ $ejecutivo->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
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

                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Datos póliza</h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label ">Fecha de Ingreso</label>
                            <input type="date" name="FechaIngreso" id="FechaIngreso"
                                value="{{ date('Y-m-d', strtotime($suscripcion->FechaIngreso)) }}" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label ">Días para completar información (cliente)</label>
                            <input type="number" name="DiasCompletarInfoCliente" id="DiasCompletarInfoCliente"
                                value="{{ $suscripcion->DiasCompletarInfoCliente }}" class="form-control"
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label ">Fecha entrega documentos completos</label>
                            <input type="date" name="FechaEntregaDocsCompletos" id="FechaEntregaDocsCompletos"
                                value="{{ $suscripcion->FechaEntregaDocsCompletos ? date('Y-m-d', strtotime($suscripcion->FechaEntregaDocsCompletos)) : '' }}"
                                class="form-control">
                        </div>

                        <div class="col-sm-4">
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

                        <div class="col-sm-4">
                            <label class="control-label ">Categoria</label>
                            <select name="CategoriaSisa" id="CategoriaSisa" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="ALTERNA" {{ $suscripcion->CategoriaSisa == 'ALTERNA' ? 'selected' : '' }}>
                                    ALTERNA</option>
                                <option value="TRADICIONAL"
                                    {{ $suscripcion->CategoriaSisa == 'TRADICIONAL' ? 'selected' : '' }}>TRADICIONAL
                                </option>
                            </select>

                        </div>
                        <div class="col-sm-4">
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
                        <div class="col-sm-6">
                            <label class="control-label">Número de Póliza Deuda</label>
                            <select name="PolizaDeuda" class="form-control select2" required>
                                <option value="">Seleccione...</option>
                                @foreach ($polizas_deuda as $deuda)
                                    <option value="{{ $deuda->Id }}"
                                        {{ old('PolizaDeuda', $suscripcion->PolizaDeuda) == $deuda->Id ? 'selected' : '' }}>
                                        {{ $deuda->NumeroPoliza }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
                            <label class="control-label ">Suma Asegurada Evaluada Deuda</label>
                            <input type="float" name="SumaAseguradaDeuda"
                                value="{{ number_format($suscripcion->SumaAseguradaDeuda, 2, '.', '') }}"
                                class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Suma Asegurada Evaluada vida colectivo
                                usuarios</label>
                            <input type="float" name="SumaAseguradaVida"
                                value="{{ number_format($suscripcion->SumaAseguradaVida, 2, '.', '') }}"
                                class="form-control">
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
                                value="{{ $suscripcion->Dui }}">
                        </div>
                        <div class="col-sm-4">
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
                        <div class="col-sm-4">
                            <label class="control-label ">Tipo crédito</label>
                            <select name="TipoCreditoId" id="TipoCreditoId" class="form-control">
                                <option value="">Seleccione...</option>
                                @foreach ($tipo_creditos as $obj)
                                    <option value="{{ $obj->Id }}"
                                        {{ $suscripcion->TipoCreditoId == $obj->Id ? 'selected' : '' }}>
                                        {{ $obj->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label ">Asegurado</label>
                            <input type="text" name="Asegurado" value="{{ $suscripcion->Asegurado }}"
                                class="form-control"
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label ">Edad</label>
                            <input type="number" name="Edad" value="{{ $suscripcion->Edad }}" class="form-control"
                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label">Genero</label>
                            <select name="Genero" id="Genero" class="form-control">
                                <option value="1" {{ $suscripcion->Genero == 1 ? 'selected' : '' }}>F
                                </option>
                                <option value="2" {{ $suscripcion->Genero == 2 ? 'selected' : '' }}>M
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label ">Ocupación</label>
                            <div class="input-group">
                                <select name="OcupacionId" id="OcupacionId" class="form-control select2">
                                    <option value="">Seleccione...</option>
                                    @foreach ($ocupaciones as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $suscripcion->OcupacionId == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-target="#modal-create-ocupacion"
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
                            <input type="float" name="Peso" value="{{ $suscripcion->Peso }}" id="Peso"
                                class="form-control" onchange="calculo()">
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">Estatura (m) </label>
                            <input type="float" name="Estatura" value="{{ $suscripcion->Estatura }}" id="Estatura"
                                class="form-control" onchange="calculo()">
                        </div>
                        <div class="col-sm-3">
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
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
                            <label for="Padecimiento" class="form-label">Padecimientos</label>
                            <div class="input-group">
                                <select name="Padecimiento[]" id="Padecimiento" class="form-control select2"
                                    multiple="multiple">
                                    @foreach ($padecimientos as $padecimiento)
                                        <option value="{{ $padecimiento->Id }}"
                                            {{ is_array(old('Padecimiento', $padecimientos_seleccionados)) && in_array($padecimiento->Id, old('Padecimiento', $padecimientos_seleccionados)) ? 'selected' : '' }}>
                                            {{ $padecimiento->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary"
                                        data-target="#modal-create-padecimiento" data-toggle="modal"
                                        title="Agregar nuevo padecimiento" style="margin-left: 2px;">+</button>
                                </span>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Gestiones</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-sm-12">
                            <label for="DireccionResidencia" class="form-label">Resumen de Gestión</label>
                            <select name="ResumenGestion" id="ResumenGestion" class="form-control"
                                onchange="resumenGestionChanged(this.value)">
                                <option value="">SELECCIONE</option>

                                @foreach ($resumen_gestion as $resumen)
                                    @if ($resumen->Id != 18)
                                        <option value="{{ $resumen->Id }}" class=" bg-{{ $resumen->Color }}"
                                            {{ $suscripcion->ResumenGestion == $resumen->Id ? 'selected' : '' }}>
                                            {{ $resumen->Nombre }}</option>
                                    @else
                                        <option value="{{ $resumen->Id }}" style="background-color: #000;color: #fff;"
                                            {{ $suscripcion->ResumenGestion == $resumen->Id ? 'selected' : '' }}>
                                            {{ $resumen->Nombre }}</option>
                                    @endif
                                @endforeach


                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Fecha de Reportado Cia / Resolución
                                Anticipada</label>
                            <input type="date" name="FechaReportadoCia" id="FechaReportadoCia"
                                value="{{ isset($suscripcion->FechaReportadoCia) ? date('Y-m-d', strtotime($suscripcion->FechaReportadoCia)) : '' }}"
                                class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Tareas Eva (Sisa)</label>
                            <input type="text" name="TareasEvaSisa" value="{{ $suscripcion->TareasEvaSisa }}"
                                id="TareasEvaSisa" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Trabajo efectuado día hábil</label>
                            <input type="number" name="TrabajadoEfectuadoDiaHabil" id="TrabajadoEfectuadoDiaHabil"
                                value="{{ $suscripcion->TrabajadoEfectuadoDiaHabil }}" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Fecha cierre de gestión</label>
                            <input type="date" name="FechaCierreGestion"
                                value="{{ $suscripcion->FechaCierreGestion ? date('Y-m-d', strtotime($suscripcion->FechaCierreGestion)) : '' }}"
                                class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Fecha de envio de corrección</label>
                            <input type="date" name="FechaEnvioCorreccion"
                                value="{{ $suscripcion->FechaEnvioCorreccion ? date('Y-m-d', strtotime($suscripcion->FechaEnvioCorreccion)) : '' }}"
                                class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Reproceso</label>
                            <select name="ReprocesoId" id="ReprocesoId" class="form-control">
                                <option value="">SELECCIONE</option>
                                @foreach ($reprocesos as $repro)
                                    <option value="{{ $repro->Id }}" class="bg-{{ $repro->Color }}"
                                        {{ $suscripcion->ReprocesoId == $repro->Id ? 'selected' : '' }}>
                                        {{ $repro->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Total dias ciclo de proceso</label>
                            <input type="text" name="TotalDiasProceso" readonly
                                value="{{ $suscripcion->TotalDiasProceso }}" class="form-control">
                        </div>

                        <div class="clearfix"></div>
                        <br>
                        <div class="x_title">
                            <h2>Resolución brindada</h2>
                            <div class="clearfix"></div>
                        </div>


                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Resolución Oficial</label>
                            <textarea name="ResolucionFinal" class="form-control" rows="4">{{ $suscripcion->ResolucionFinal }}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">Fecha de recepción de resolución de
                                CIA</label>
                            <input type="date" name="FechaResolucion" id="FechaResolucion"
                                value="{{ $suscripcion->FechaResolucion ? date('Y-m-d', strtotime($suscripcion->FechaResolucion)) : '' }}"
                                class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <label for="DireccionResidencia" class="form-label">% ExtraPrima</label>
                            <input type="number" name="ValorExtraPrima" value="{{ $suscripcion->ValorExtraPrima }}"
                                id="ValorExtraPrima" class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label ">Fecha de envió de resolución al cliente</label>
                            <input type="date" name="FechaEnvioResoCliente" id="FechaEnvioResoCliente"
                                value="{{ $suscripcion->FechaEnvioResoCliente ? date('Y-m-d', strtotime($suscripcion->FechaEnvioResoCliente)) : '' }}"
                                class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label ">Dias de procesamiento de resolución</label>
                            <input type="number" name="DiasProcesamiento" id="DiasProcesamiento"
                                value="{{ old('DiasProcesamiento') }}" readonly class="form-control">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit">Guardar</button>
                        <a href="{{ url('suscripciones/') }}"><button class="btn btn-primary"
                                type="button">Cancelar</button></a>
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
                        @foreach ($suscripcion->comentarios as $comentario)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d/m/Y', strtotime($comentario->FechaCreacion)) }}</td>
                                <td>{{ $comentario->usuario->name ?? '' }}</td>
                                <td>{{ $comentario->Comentario }}</td>

                                <td align="center">
                                    <a class="btn btn-primary" class="on-default edit-row"
                                        data-target="#modal-edit-comentario-{{ $comentario->Id }}"
                                        onclick="showCountComentarioEditIni({{ $comentario->Id }})" data-toggle="modal">
                                        <i class="fa fa-pencil fa-lg"></i></a>
                                    <a href="#" class="btn btn-danger"><i class="fa fa-trash fa-lg"
                                            data-target="#modal-delete-comentario-{{ $comentario->Id }}"
                                            data-toggle="modal">
                                        </i></a>
                                </td>
                            </tr>
                            @php($i++)
                            @include('suscripciones.suscripcion.edit_comentario')
                            @include('suscripciones.suscripcion.delete_comentario')
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
                        <label for="Comentario" class="form-label">
                            Comentario <span id="countComentario">0/3000</span>
                        </label>
                        <input type="hidden" value="{{ $suscripcion->Id }}" name="SuscripcionId">
                        <textarea name="Comentario" id="Comentario" class="form-control" rows="4" maxlength="3000" required
                            oninput="showCountComentario()">{{ old('Comentario') }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="modal fade" id="modal-create-ocupacion" tabindex="-1" user="dialog" aria-hidden="true">
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



    <div class="modal fade" id="modal-create-padecimiento" tabindex="-1" user="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-6">
                        <h4 class="modal-title">Nuevo Padecimiento</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <form id="formCrearPadecimiento">
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

        $(document).ready(function() {
            // Mostrar opción en menú
            displayOption("ul-suscripciones", "li-suscripciones");

            calFechaHabil($('#FechaResolucion').val(), $('#FechaEnvioResoCliente').val())
                .then(function(dias) {
                    $('#DiasProcesamiento').val(dias);
                })
                .catch(function(error) {
                    console.error('Error al calcular días hábiles:', error);
                    $('#DiasProcesamiento').val('');
                });


            calFechaHabil($('#FechaReportadoCia').val(), $('#FechaEntregaDocsCompletos').val())
                .then(function(dias) {
                    $('#TrabajadoEfectuadoDiaHabil').val(dias);
                })
                .catch(function(error) {
                    console.error('Error al calcular días hábiles:', error);
                    $('#TrabajadoEfectuadoDiaHabil').val('');
                });

            calFechaHabil($('#FechaIngreso').val(), $('#FechaEntregaDocsCompletos').val())
                .then(function(dias) {
                    $('#DiasCompletarInfoCliente').val(dias);
                })
                .catch(function(error) {
                    console.error('Error al calcular días hábiles:', error);
                    $('#DiasCompletarInfoCliente').val('');
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
                        $('#modal-create-ocupacion').modal('hide');
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

        $('#FechaResolucion, #FechaEnvioResoCliente').on('change', function() {
            const inicio = $('#FechaResolucion').val();
            const fin = $('#FechaEnvioResoCliente').val();

            if (inicio && fin) {
                calFechaHabil(inicio, fin)
                    .then(function(dias) {
                        $('#DiasProcesamiento').val(dias);
                    })
                    .catch(function(error) {
                        console.error('Error al calcular días hábiles:', error);
                        $('#DiasProcesamiento').val('');
                    });
            } else {
                // Si alguno está vacío, limpiar el campo de resultado
                $('#DiasProcesamiento').val('');
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


        $('#FechaReportadoCia, #FechaEntregaDocsCompletos').on('change', function() {
            const inicio = $('#FechaReportadoCia').val();
            const fin = $('#FechaEntregaDocsCompletos').val();

            if (inicio && fin) {
                calFechaHabil(inicio, fin)
                    .then(function(dias) {
                        $('#TrabajadoEfectuadoDiaHabil').val(dias);
                    })
                    .catch(function(error) {
                        console.error('Error al calcular días hábiles:', error);
                        $('#TrabajadoEfectuadoDiaHabil').val('');
                    });
            } else {
                // Si alguno está vacío, limpiar el campo de resultado
                $('#TrabajadoEfectuadoDiaHabil').val('');
            }
        });


        function calFechaHabil(inicio, fin) {
            return new Promise((resolve, reject) => {
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
                            resolve(response.dias_habiles);
                        },
                        error: function(xhr) {
                            reject(xhr.responseJSON ?? 'Error desconocido');
                        }
                    });
                } else {
                    resolve('');
                }
            });
        }


        function resumenGestionChanged(id) {
            if (id > 8) {
                document.getElementById('EstadoId').value = 2;
            } else {
                document.getElementById('EstadoId').value = 1;
            }
        }


        function showCountComentario() {
            const textarea = document.getElementById('Comentario');
            const counter = document.getElementById('countComentario');
            const maxLength = 3000;
            const length = textarea.value.length;
            counter.textContent = `${length}/${maxLength}`;
        }

        function showCountComentarioEdit(id) {
            const textarea = document.getElementById('Comentario-' + id);
            const counter = document.getElementById('countComentario-' + id);
            const maxLength = 3000;
            const length = textarea.value.length;
            counter.textContent = `${length}/${maxLength}`;
        }

        function showCountComentarioEditIni(id) {
            const textarea = document.getElementById('Comentario-' + id);
            const counter = document.getElementById('countComentario-' + id);
            const maxLength = 3000;
            const length = textarea.value.length;
            counter.textContent = `${length}/${maxLength}`;
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



        $('#formCrearPadecimiento').submit(function(e) {
            e.preventDefault();

            $.ajax({
                // Cambia esta URL por la ruta real que apunte a tu función agregar_padecimiento
                url: "{{ url('suscripcion/padecimiento_store') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {

                    // 1. Cerrar el modal y limpiar el formulario
                    $('#modal-create-padecimiento').modal('hide');
                    $('#formCrearPadecimiento').trigger('reset');

                    // 2. Validar que el ID sea un número válido
                    var idLimpio = parseInt(response.id);

                    if (!isNaN(idLimpio)) {
                        // 3. Crear opción: new Option(texto, valor, defaultSelected, selected)
                        // Cambiamos a true, true para que se agregue Y se seleccione de una vez
                        var newOption = new Option(response.nombre, idLimpio, true, true);

                        // 4. Inyectar en el Select2 y disparar el cambio
                        $('#Padecimiento').append(newOption).trigger('change');

                        toastr.success(response.message || "Padecimiento agregado y seleccionado.");
                    } else {
                        console.error("Error: El ID recibido no es un número", response);
                        toastr.error("Error crítico: El servidor devolvió un ID no numérico.");
                    }

                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // Si pasaste los errores como array en el controlador
                        var mensajeError = Array.isArray(errors) ? errors[0] : Object
                            .values(errors)[0][0];

                        Swal.fire({
                            title: '¡Error!',
                            text: mensajeError,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            title: '¡Error!',
                            text: 'Hubo un problema al procesar la solicitud.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }
            });
        });
    </script>
@endsection
