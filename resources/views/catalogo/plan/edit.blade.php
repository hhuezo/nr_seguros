@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="x_title">
                <h2>Modificar Plan <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{ url('catalogo/plan') }}?idRegistro={{ $plan->Id }}" class="btn btn-info fa fa-undo" style="color: white"> Atras</a>
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

            <form method="POST" action="{{ route('plan.update', $plan->Id) }}">
                @method('PUT')
                @csrf

                <div class="x_content">
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label">Nombre del Plan</label>
                            <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre', $plan->Nombre) }}" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="Producto" class="form-label">Producto</label>
                            <select id="Producto" name="Producto" class="form-control select2" style="width: 100%" required>
                                @foreach ($productos as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('Producto', $plan->Producto) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="form-group" align="right">
                    @can('plan edit')
                        <button class="btn btn-success" type="submit">Modificar</button>
                    @endcan
                </div>
            </form>

            <div class="x_title">
                <h2>Coberturas <small></small></h2>
                <div class="clearfix"></div>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Cobertura</th>
                        <th>Tarificacion</th>
                        <th>Principal</th>
                        <th>Suma Asegurada</th>
                        <th>Tasa</th>
                        <th>Prima</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plan->coberturas as $obj)
                        @php
                            $tarificacionNombre = $obj->pivot->TarificacionNombre ?: optional($obj->tarificacion)->Nombre;
                            $tarificacionId = $obj->pivot->Tarificacion ?: $obj->Tarificacion;
                            $principal = (int) ($obj->pivot->CoberturaPrincipal ?? 0) === 1;
                            $payloadCobertura = json_encode([
                                'Id' => $obj->Id,
                                'Nombre' => $obj->Nombre,
                                'Tarificacion' => $tarificacionId,
                                'TarificacionNombre' => $tarificacionNombre,
                                'SumaAsegurada' => $obj->pivot->SumaAsegurada,
                                'Tasa' => $obj->pivot->Tasa,
                                'Prima' => $obj->pivot->Prima,
                                'CoberturaPrincipal' => $principal,
                            ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        @endphp
                        <tr>
                            <td>{{ $obj->Nombre }}</td>
                            <td>{{ $tarificacionNombre }}</td>
                            <td>
                                @if ($principal)
                                    <span class="label label-primary">Principal</span>
                                @else
                                    <span class="label label-default">No</span>
                                @endif
                            </td>
                            <td>${{ number_format((float) $obj->pivot->SumaAsegurada, 2, '.', ',') }}</td>
                            <td>{{ number_format((float) $obj->pivot->Tasa, 6, '.', ',') }}</td>
                            <td>${{ number_format((float) $obj->pivot->Prima, 2, '.', ',') }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" title="Editar cobertura"
                                    onclick='modal_edit_cobertura({!! $payloadCobertura !!})'
                                    data-target="#modal-edit-cobertura" data-toggle="modal">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    @foreach ($coberturasDisponibles as $obj)
                        @php
                            $tarificacionNombre = optional($obj->tarificacion)->Nombre;
                            $payloadCobertura = json_encode([
                                'Id' => $obj->Id,
                                'Nombre' => $obj->Nombre,
                                'Tarificacion' => $obj->Tarificacion,
                                'TarificacionNombre' => $tarificacionNombre,
                                'SumaAsegurada' => 0,
                                'Tasa' => 0,
                                'Prima' => 0,
                                'CoberturaPrincipal' => false,
                            ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        @endphp
                        <tr>
                            <td>{{ $obj->Nombre }}</td>
                            <td>{{ $tarificacionNombre }}</td>
                            <td><span class="label label-default">No</span></td>
                            <td>${{ number_format(0, 2, '.', ',') }}</td>
                            <td>{{ number_format(0, 6, '.', ',') }}</td>
                            <td>${{ number_format(0, 2, '.', ',') }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" title="Configurar cobertura"
                                    onclick='modal_edit_cobertura({!! $payloadCobertura !!})'
                                    data-target="#modal-edit-cobertura" data-toggle="modal">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        <div class="modal fade modal-edit-cobertura" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-cobertura">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/plan/edit_cobertura_detalle') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Editar Cobertura</h4>
                            <input type="hidden" name="Plan" value="{{ $plan->Id }}" class="form-control">
                            <input type="hidden" name="Cobertura" id="ModalCoberturaId" class="form-control" required>
                            <input type="hidden" id="ModalCoberturaTarificacion" class="form-control">
                            <input type="hidden" id="ModalCoberturaTarificacionNombre" class="form-control">
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Cobertura</label>
                                    <input readonly type="text" id="ModalCoberturaNombre" class="form-control" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Tarificacion</label>
                                    <input readonly type="text" id="ModalCoberturaTarificacionTexto" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <label>Suma Asegurada</label>
                                    <input type="number" name="SumaAsegurada" id="ModalCoberturaSumaAsegurada" step="0.01" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <label>Tasa</label>
                                    <input type="number" name="Tasa" id="ModalCoberturaTasa" step="0.000001" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <label>Prima</label>
                                    <input type="number" name="Prima" id="ModalCoberturaPrima" step="0.01" class="form-control">
                                </div>
                                <div class="col-sm-12" style="margin-top: 10px;">
                                    <label>
                                        <input type="checkbox" name="CoberturaPrincipal" id="ModalCoberturaPrincipal" value="1">
                                        Cobertura principal
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            displayOption("ul-catalogo", "li-plan");
        });

        function normalizarTarificacion(valor) {
            return String(valor || '').toLowerCase().trim();
        }

        function configurarCampo(selector, habilitado, valorCero = false, requerido = false) {
            const campo = $(selector);
            campo.prop('readonly', !habilitado);
            campo.prop('required', requerido);

            if (valorCero) {
                campo.val(selector === '#ModalCoberturaTasa' ? '0.000000' : '0.00');
            }
        }

        function aplicarReglasTarificacion() {
            const tipo = normalizarTarificacion($('#ModalCoberturaTarificacionNombre').val());

            if (tipo.includes('sin cobro')) {
                configurarCampo('#ModalCoberturaSumaAsegurada', false, true, false);
                configurarCampo('#ModalCoberturaTasa', false, true, false);
                configurarCampo('#ModalCoberturaPrima', false, true, false);
                return;
            }

            if (tipo.includes('prima')) {
                configurarCampo('#ModalCoberturaSumaAsegurada', false, true, false);
                configurarCampo('#ModalCoberturaTasa', false, true, false);
                configurarCampo('#ModalCoberturaPrima', true, false, true);
                return;
            }

            if (tipo.includes('millar') || tipo.includes('porcentual')) {
                configurarCampo('#ModalCoberturaSumaAsegurada', true, false, true);
                configurarCampo('#ModalCoberturaTasa', true, false, true);
                configurarCampo('#ModalCoberturaPrima', false, true, false);
                return;
            }

            configurarCampo('#ModalCoberturaSumaAsegurada', true, false, false);
            configurarCampo('#ModalCoberturaTasa', true, false, false);
            configurarCampo('#ModalCoberturaPrima', true, false, false);
        }

        function modal_edit_cobertura(cobertura) {
            $('#ModalCoberturaId').val(cobertura.Id);
            $('#ModalCoberturaNombre').val(cobertura.Nombre);
            $('#ModalCoberturaTarificacion').val(cobertura.Tarificacion || '');
            $('#ModalCoberturaTarificacionNombre').val(cobertura.TarificacionNombre || '');
            $('#ModalCoberturaTarificacionTexto').val(cobertura.TarificacionNombre || '');
            $('#ModalCoberturaSumaAsegurada').val(Number(cobertura.SumaAsegurada || 0).toFixed(2));
            $('#ModalCoberturaTasa').val(Number(cobertura.Tasa || 0).toFixed(6));
            $('#ModalCoberturaPrima').val(Number(cobertura.Prima || 0).toFixed(2));
            $('#ModalCoberturaPrincipal').prop('checked', cobertura.CoberturaPrincipal === true || cobertura.CoberturaPrincipal === 1);
            aplicarReglasTarificacion();
        }
    </script>
</div>
@include('sweetalert::alert')
@endsection
