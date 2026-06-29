@extends ('welcome')
@section('contenido')
    <div class="x_panel"
        style="background-image:url('dentco-html/images/LOGO_app.png'); background-repeat: no-repeat; background-size: 30% ; background-position-x:right ; background-position-y:bottom ;">

        <div class="x_title">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3>Consulta de Cliente en Todas las Carteras</h3>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <form action="{{ url('consulta/cliente/buscar') }}" method="GET" class="form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="tipo_busqueda">
                                Buscar por
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select id="tipo_busqueda" name="tipo_busqueda" class="form-control col-md-7 col-xs-12">
                                    <option value="dui" {{ ($tipo_busqueda ?? 'documento') === 'dui' ? 'selected' : '' }}>DUI</option>
                                    <option value="nit" {{ ($tipo_busqueda ?? 'documento') === 'nit' ? 'selected' : '' }}>NIT</option>
                                    <option value="pasaporte" {{ ($tipo_busqueda ?? 'documento') === 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                    <option value="documento" {{ ($tipo_busqueda ?? 'documento') === 'documento' ? 'selected' : '' }}>DUI / NIT / Pasaporte</option>
                                    <option value="nombre" {{ ($tipo_busqueda ?? 'documento') === 'nombre' ? 'selected' : '' }}>Nombre completo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="busqueda">
                                Valor de busqueda <span class="required">*</span>
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="busqueda" name="busqueda" required="required"
                                    class="form-control col-md-7 col-xs-12"
                                    value="{{ $busqueda ?? '' }}"
                                    placeholder="Ingrese el valor a buscar">
                                <small class="help-block" id="ayuda-busqueda" style="margin-bottom: 0; padding-left: 0;">
                                    Para nombre completo la busqueda minima es de 4 caracteres.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                                <a href="{{ url('consulta/cliente') }}" class="btn btn-default">
                                    <i class="fa fa-refresh"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($mensaje))
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> {{ $mensaje }}
                </div>
            @endif

            @if(isset($resultados) && $resultados->count() > 0)
                <div class="row" style="margin-top: 20px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i>
                            Se encontraron <strong>{{ $resultados->count() }}</strong> registro(s) para: <strong>{{ $busqueda }}</strong>
                        </div>

                        <div class="table-responsive" style="margin-bottom: 15px;">
                            <table class="table table-bordered table-condensed" style="font-size: 11px; margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>Total Monto Otorgado</th>
                                        <th>Total Suma Asegurada</th>
                                        <th>Total Saldo Capital</th>
                                        <th>Total Interes Corriente</th>
                                        <th>Total Interes Moratorio</th>
                                        <th>Total Interes COVID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${{ number_format((float) ($totales['MontoOtorgado'] ?? 0), 2, '.', ',') }}</td>
                                        <td>${{ number_format((float) ($totales['SumaAsegurada'] ?? 0), 2, '.', ',') }}</td>
                                        <td>${{ number_format((float) ($totales['SaldoCapital'] ?? 0), 2, '.', ',') }}</td>
                                        <td>${{ number_format((float) ($totales['Intereses'] ?? 0), 2, '.', ',') }}</td>
                                        <td>${{ number_format((float) ($totales['InteresesMoratorios'] ?? 0), 2, '.', ',') }}</td>
                                        <td>${{ number_format((float) ($totales['InteresesCovid'] ?? 0), 2, '.', ',') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table id="tablaResultados" class="table table-striped table-bordered" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Aseguradora</th>
                                        <th>Poliza</th>
                                        <th>Periodo</th>
                                        <th>Tarifa Mes</th>
                                        <th>Contratante</th>
                                        <th>Linea</th>
                                        <th>Documento Identidad</th>
                                        <th>Nacionalidad</th>
                                        <th>Fec. Nacimiento</th>
                                        <th>Primer Apellido</th>
                                        <th>Segundo Apellido</th>
                                        <th>Apellido Casada</th>
                                        <th>Primer Nombre</th>
                                        <th>Segundo Nombre</th>
                                        <th>Nombre Sociedad</th>
                                        <th>Fec. Otorgamiento</th>
                                        <th>Fec. Vencimiento</th>
                                        <th>Num. Referencia</th>
                                        <th>Monto Otorgado</th>
                                        <th>Suma Asegurada</th>
                                        <th>Saldo Capital</th>
                                        <th>Interes Corriente</th>
                                        <th>Interes Moratorio</th>
                                        <th>Interes COVID</th>
                                        <th>% Extraprima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resultados as $index => $resultado)
                                        @php
                                            $documentoIdentidad = '';
                                            if (!empty($resultado->Dui)) {
                                                $documentoIdentidad = 'DUI: ' . $resultado->Dui;
                                            } elseif (!empty($resultado->Nit)) {
                                                $documentoIdentidad = 'NIT: ' . $resultado->Nit;
                                            } elseif (!empty($resultado->Pasaporte)) {
                                                $documentoIdentidad = 'Pasaporte: ' . $resultado->Pasaporte;
                                            } elseif (!empty($resultado->CarnetResidencia)) {
                                                $documentoIdentidad = 'Carnet: ' . $resultado->CarnetResidencia;
                                            }

                                            $formatearFecha = function($fecha) {
                                                if (!$fecha) return '-';
                                                try {
                                                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
                                                        return \Carbon\Carbon::createFromFormat('d/m/Y', $fecha)->format('d/m/Y');
                                                    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}/', $fecha)) {
                                                        return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
                                                    }
                                                    return $fecha;
                                                } catch (\Exception $e) {
                                                    return $fecha;
                                                }
                                            };

                                            $formatearDinero = function($valor) {
                                                if ($valor === '' || $valor === null) return '-';
                                                return '$' . number_format((float) $valor, 2, '.', ',');
                                            };

                                            $formatearPorcentaje = function($valor, $decimales = 4) {
                                                if ($valor === '' || $valor === null) return '-';
                                                $valorFormateado = number_format((float) $valor, $decimales, '.', '');
                                                $valorFormateado = rtrim(rtrim($valorFormateado, '0'), '.');
                                                return $valorFormateado . '%';
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $resultado->AseguradoraNombre ?? '-' }}</td>
                                            <td>{{ $resultado->NumeroPoliza ?? '-' }}</td>
                                            <td>{{ $resultado->PeriodoRegistro ?? '-' }}</td>
                                            <td>{{ $formatearPorcentaje($resultado->TarifaMes ?? null) }}</td>
                                            <td>{{ $resultado->ContratanteNombre ?? '-' }}</td>
                                            <td>{{ $resultado->LineaDescripcion ?? '-' }}</td>
                                            <td>{{ $documentoIdentidad ?: '-' }}</td>
                                            <td>{{ $resultado->Nacionalidad ?? '-' }}</td>
                                            <td>{{ $formatearFecha($resultado->FechaNacimiento ?? null) }}</td>
                                            <td>{{ $resultado->PrimerApellido ?? '-' }}</td>
                                            <td>{{ $resultado->SegundoApellido ?? '-' }}</td>
                                            <td>{{ $resultado->ApellidoCasada ?? '-' }}</td>
                                            <td>{{ $resultado->PrimerNombre ?? '-' }}</td>
                                            <td>{{ $resultado->SegundoNombre ?? '-' }}</td>
                                            <td>{{ $resultado->NombreSociedad ?? '-' }}</td>
                                            <td>{{ $formatearFecha($resultado->FechaOtorgamiento ?? null) }}</td>
                                            <td>{{ $formatearFecha($resultado->FechaVencimiento ?? null) }}</td>
                                            <td>{{ $resultado->NumeroReferencia ?? '-' }}</td>
                                            <td>{{ $formatearDinero($resultado->MontoOtorgado ?? null) }}</td>
                                            <td>{{ $formatearDinero($resultado->SumaAsegurada ?? null) }}</td>
                                            <td>{{ $formatearDinero($resultado->SaldoCapital ?? null) }}</td>
                                            <td>{{ $formatearDinero($resultado->Intereses ?? null) }}</td>
                                            <td>{{ $formatearDinero($resultado->InteresesMoratorios ?? null) }}</td>
                                            <td>{{ $formatearDinero($resultado->InteresesCovid ?? null) }}</td>
                                            <td>{{ $formatearPorcentaje($resultado->PorcentajeExtraprima ?? null, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif(isset($resultados) && $resultados->count() == 0 && isset($busqueda) && $busqueda != '')
                <div class="row" style="margin-top: 20px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            No se encontraron registros para: <strong>{{ $busqueda }}</strong>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if ($('#tablaResultados').length) {
                $('#tablaResultados').DataTable({
                    order: [[0, 'asc']],
                    pageLength: 25,
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningun dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Ultimo",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            }

            function actualizarAyudaBusqueda() {
                var tipo = $('#tipo_busqueda').val();
                var mensaje = 'Para nombre completo la busqueda minima es de 4 caracteres.';

                if (tipo === 'dui') {
                    mensaje = 'La busqueda se realiza solo en el campo DUI.';
                } else if (tipo === 'nit') {
                    mensaje = 'La busqueda se realiza solo en el campo NIT.';
                } else if (tipo === 'pasaporte') {
                    mensaje = 'La busqueda se realiza solo en el campo Pasaporte.';
                } else if (tipo === 'documento') {
                    mensaje = 'La busqueda se realiza en DUI, NIT y Pasaporte.';
                }

                $('#ayuda-busqueda').text(mensaje);
            }

            $('#tipo_busqueda').on('change', actualizarAyudaBusqueda);
            actualizarAyudaBusqueda();
        });
    </script>
@endsection
