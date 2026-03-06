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
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="busqueda">
                                DUI / NIT / Pasaporte <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="busqueda" name="busqueda" required="required"
                                    class="form-control col-md-7 col-xs-12"
                                    value="{{ $busqueda ?? '' }}"
                                    placeholder="Ingrese DUI, NIT o Pasaporte">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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

                        <div class="table-responsive">
                            <table id="tablaResultados" class="table table-striped table-bordered" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Aseguradora</th>
                                        <th>Póliza</th>
                                        <th>Contratante</th>
                                        <th>Línea</th>
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
                                        <th>Núm. Referencia</th>
                                        <th>Monto Otorgado</th>
                                        <th>Saldo Capital</th>
                                        <th>Interés Corriente</th>
                                        <th>Interés Moratorio</th>
                                        <th>Interés COVID</th>
                                        <th>% Extraprima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resultados as $index => $resultado)
                                        @php
                                            // Determinar documento de identidad
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

                                            // Formatear fechas
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
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $resultado->AseguradoraNombre ?? '-' }}</td>
                                            <td>{{ $resultado->NumeroPoliza ?? '-' }}</td>
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
                                            <td>
                                                @if(isset($resultado->MontoOtorgado) && $resultado->MontoOtorgado !== '' && $resultado->MontoOtorgado !== null)
                                                    ${{ number_format((float) $resultado->MontoOtorgado, 2, '.', ',') }}
                                                @elseif(isset($resultado->SumaAsegurada) && $resultado->SumaAsegurada !== '' && $resultado->SumaAsegurada !== null)
                                                    ${{ number_format((float) $resultado->SumaAsegurada, 2, '.', ',') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($resultado->SaldoCapital) && $resultado->SaldoCapital !== '' && $resultado->SaldoCapital !== null)
                                                    ${{ number_format((float) $resultado->SaldoCapital, 2, '.', ',') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($resultado->Intereses) && $resultado->Intereses !== '' && $resultado->Intereses !== null)
                                                    ${{ number_format((float) $resultado->Intereses, 2, '.', ',') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($resultado->InteresesMoratorios) && $resultado->InteresesMoratorios !== '' && $resultado->InteresesMoratorios !== null)
                                                    ${{ number_format((float) $resultado->InteresesMoratorios, 2, '.', ',') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($resultado->InteresesCovid) && $resultado->InteresesCovid !== '' && $resultado->InteresesCovid !== null)
                                                    ${{ number_format((float) $resultado->InteresesCovid, 2, '.', ',') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($resultado->PorcentajeExtraprima) && $resultado->PorcentajeExtraprima !== '' && $resultado->PorcentajeExtraprima !== null)
                                                    {{ number_format((float) $resultado->PorcentajeExtraprima, 2, '.', ',') }}%
                                                @else
                                                    -
                                                @endif
                                            </td>
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

    @if(isset($resultados) && $resultados->count() > 0)
    <script>
        $(document).ready(function() {
            $('#tablaResultados').DataTable({
                order: [[0, 'asc']],
                pageLength: 25,
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
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
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });
    </script>
    @endif
@endsection

