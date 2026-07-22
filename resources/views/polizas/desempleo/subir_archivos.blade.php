@extends ('welcome')
@section('contenido')
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            displayOption("ul-poliza", "li-poliza-desempleo");
        });
    </script>

    <style>
        #validar-progress-wrap {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.92);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        #validar-progress-box {
            width: 90%;
            max-width: 420px;
            text-align: center;
        }

        #validar-progress-box h4 {
            margin-bottom: 8px;
            color: #333;
        }

        #validar-progress-label {
            margin-bottom: 12px;
            color: #666;
            font-size: 13px;
        }

        #validar-progress-bar-outer {
            height: 22px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #ced4da;
        }

        #validar-progress-bar-inner {
            height: 100%;
            width: 0%;
            background: #337ab7;
            transition: width 0.25s ease;
            line-height: 22px;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }

        #validar-progress-detail {
            margin-top: 10px;
            font-size: 12px;
            color: #888;
        }
    </style>

    <div id="loading-overlay">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>

    <div id="validar-progress-wrap">
        <div id="validar-progress-box">
            <h4>Validando póliza</h4>
            <div id="validar-progress-label">Preparando...</div>
            <div id="validar-progress-bar-outer">
                <div id="validar-progress-bar-inner">0%</div>
            </div>
            <div id="validar-progress-detail"></div>
        </div>
    </div>

    <div class="x_panel">

        <div class="x_title">
            <div class="col-md-10 col-sm-10 col-xs-12">
                <h3>Subir Carteras de <br> {{ $desempleo->NumeroPoliza }} | {{ $desempleo->cliente->Nombre ?? '' }} </h3>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12" align="right">
                <a href="{{ url('polizas/desempleo') }}/{{ $desempleo->Id }}?tab=2"><button class="btn btn-info float-right">
                        <i class="fa fa-undo"></i> Atras</button></a>
            </div>


            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo cálculo</th>
                            <th>Linea credito</th>
                            <th>Mes/Año</th>
                            <th>Datos Ingresados</th>
                            <th align="center">Carga de <br> archivo de cartera </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tipos_cartera as $tipo)
                            <tr>
                                <td>{{ $tipo->TipoCalculoTexto }}</td>
                                <td>{{ $tipo->SaldosMontosTexto }}</td>
                                <td>{{$tipo->Mes ?? ''}}{{$tipo->Mes ? '/':''}}{{ $tipo->Axo ?? ''}}</td>
                                <td style="text-align: right">
                                    {{ $tipo->Total > 0 ? '$' . number_format($tipo->Total, 2) : '-' }}
                                </td>

                                <td align="center">
                                    @if ($desempleo->Aseguradora == 3 || $desempleo->Aseguradora == 4)
                                        <a data-target="#modal-add-fede-{{ $tipo->PolizaDesempleoTipoCartera }}"
                                            data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button>
                                        </a>
                                    @else
                                        <a data-target="#modal-add-{{ $tipo->PolizaDesempleoTipoCartera }}"
                                            data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button>
                                        </a>
                                    @endif
                                    @if($tipo->Total > 0)
                                    <a data-target="#modal-delete-{{ $tipo->PolizaDesempleoTipoCartera }}"
                                        data-toggle="modal">
                                        <button class="btn btn-default"><i class="fa fa-trash fa-lg"></i></button>
                                    </a>
                                    @endif
                                </td>

                                @include('polizas.desempleo.modal_subir_cartera')
                                @include('polizas.desempleo.modal_eliminar_cartera')

                            </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
                <button type="button" id="btn-validar-poliza" class="btn btn-primary float-right">
                    Validar póliza
                </button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        (function() {
            var polizaId = {{ (int) $desempleo->Id }};
            var csrfToken = '{{ csrf_token() }}';
            var urlInit = '{{ url('polizas/desempleo/validar_poliza_init') }}/' + polizaId;
            var urlChunk = '{{ url('polizas/desempleo/validar_poliza_chunk') }}/' + polizaId;
            var urlResumen = '{{ url('polizas/desempleo/validar_poliza_resumen') }}/' + polizaId;
            var urlResultado = '{{ url('polizas/desempleo/validar_poliza_resultado') }}/' + polizaId;

            var resumenPasos = [
                { paso: 'edad', label: 'Calculando edad máxima...', base: 70, span: 6 },
                { paso: 'eliminados', label: 'Detectando eliminados...', base: 76, span: 6 },
                { paso: 'nuevos', label: 'Detectando nuevos...', base: 82, span: 8 },
                { paso: 'rehabilitados', label: 'Listando rehabilitados...', base: 90, span: 5 }
            ];

            function setProgress(percent, label, detail) {
                percent = Math.max(0, Math.min(100, Math.round(percent)));
                $('#validar-progress-bar-inner').css('width', percent + '%').text(percent + '%');
                if (label) {
                    $('#validar-progress-label').text(label);
                }
                $('#validar-progress-detail').text(detail || '');
            }

            function showProgress() {
                $('#validar-progress-wrap').css('display', 'flex');
                $('#btn-validar-poliza').prop('disabled', true);
            }

            function hideProgress() {
                $('#validar-progress-wrap').hide();
                $('#btn-validar-poliza').prop('disabled', false);
            }

            function ajaxError(xhr) {
                hideProgress();
                var msg = 'Error al validar la póliza.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.status === 419) {
                    msg = 'La sesión expiró. Recarga la página e intenta de nuevo.';
                } else if (xhr.status === 504 || xhr.status === 502) {
                    msg = 'El servidor tardó demasiado en un lote. Intenta de nuevo.';
                }
                alert(msg);
            }

            function processChunks(meta, lastId, processed) {
                var total = meta.total || 1;
                setProgress(
                    5 + (65 * processed / total),
                    'Marcando rehabilitados...',
                    processed + ' de ' + total + ' registros'
                );

                $.ajax({
                    url: urlChunk,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        last_id: lastId,
                        chunk_size: meta.chunk_size,
                        axo_anterior: meta.axo_anterior,
                        mes_anterior: meta.mes_anterior
                    },
                    success: function(res) {
                        if (!res.ok) {
                            hideProgress();
                            alert(res.message || 'Error en el lote de validación');
                            return;
                        }

                        var nextProcessed = processed + (res.processed || 0);

                        if (res.done || !res.processed) {
                            setProgress(70, 'Generando resumen...', 'Edad máxima');
                            processResumen(meta, 0, 0, 0);
                            return;
                        }

                        processChunks(meta, res.last_id, nextProcessed);
                    },
                    error: ajaxError
                });
            }

            function processResumen(meta, pasoIndex, lastId, processedInPaso) {
                var pasoCfg = resumenPasos[pasoIndex];
                if (!pasoCfg) {
                    setProgress(98, 'Cargando resumen...', 'Abriendo resultado');
                    window.location.href = urlResultado;
                    return;
                }

                var ratio = meta.total ? Math.min(1, processedInPaso / meta.total) : 0;
                setProgress(
                    pasoCfg.base + (pasoCfg.span * ratio),
                    pasoCfg.label,
                    (processedInPaso ? processedInPaso + ' procesados' : 'Iniciando...') +
                        (pasoCfg.paso === 'nuevos' || pasoCfg.paso === 'edad' || pasoCfg.paso === 'eliminados' || pasoCfg.paso === 'rehabilitados'
                            ? '' : '')
                );

                $.ajax({
                    url: urlResumen,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        paso: pasoCfg.paso,
                        last_id: lastId,
                        chunk_size: meta.chunk_size
                    },
                    success: function(res) {
                        if (!res.ok) {
                            hideProgress();
                            alert(res.message || 'Error al calcular el resumen');
                            return;
                        }

                        if (res.done || !res.processed) {
                            processResumen(meta, pasoIndex + 1, 0, 0);
                            return;
                        }

                        var detail = (res.accumulated != null ? res.accumulated + ' encontrados · ' : '') +
                            'lote ' + res.processed + ' regs';
                        setProgress(
                            pasoCfg.base + (pasoCfg.span * Math.min(1, (processedInPaso + res.processed) / (meta.total || 1))),
                            pasoCfg.label,
                            detail
                        );
                        processResumen(meta, pasoIndex, res.last_id, processedInPaso + res.processed);
                    },
                    error: ajaxError
                });
            }

            $('#btn-validar-poliza').on('click', function() {
                showProgress();
                setProgress(2, 'Preparando datos...', 'Actualizando identificadores');

                $.ajax({
                    url: urlInit,
                    method: 'POST',
                    data: { _token: csrfToken },
                    success: function(res) {
                        if (!res.ok) {
                            hideProgress();
                            alert(res.message || 'No se pudo iniciar la validación');
                            return;
                        }

                        if (!res.total) {
                            hideProgress();
                            alert('No hay registros en la cartera temporal');
                            return;
                        }

                        setProgress(5, 'Marcando rehabilitados...', '0 de ' + res.total + ' registros');
                        processChunks(res, 0, 0);
                    },
                    error: ajaxError
                });
            });
        })();
    </script>

    @include('sweetalert::alert')
@endsection
