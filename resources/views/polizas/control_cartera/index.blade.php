@extends ('welcome')
@section('contenido')
    <style>
        .table {
            font-size: 12px;
        }
    </style>
    <div class="x_panel">
        <div class="x_title">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <h4>Control de flujo de carteras</h4>
            </div>

            <div class="col-md-2 col-sm-12 col-xs-12" align="right">

                <select class="form-control" name="TipoPoliza" id="TipoPoliza">
                    <option value="1">Poliza deuda</option>
                    <option value="2">Poliza vida</option>
                    <option value="3">Poliza desempleo</option>
                    <option value="4">Poliza residencia</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12" align="right">

                <select class="form-control" name="Mes" id="Mes">
                    @foreach ($meses as $key => $nombre)
                        <option value="{{ $key }}" @if ($key == $mes) selected @endif>
                            {{ $nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-12 col-xs-12" align="right">

                <select class="form-control" name="Anio" id="Anio">
                    @for ($i = date('Y'); $i >= 2024; $i--)
                        <option value="{{ $i }}" @if ($i == $anio) selected @endif>
                            {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {{-- id="datatable" --}}
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Asegurado</th>
                            <th>Vigencia desde</th>
                            <th>Vigencia hasta</th>
                            <th>Tipo póliza</th>
                            <th>CIA. de seguros</th>
                            <th>Póliza No</th>
                            <th>Fecha recepción archivo</th>
                            <th>Fecha envío a CIA</th>
                            <th>Trabajo efectuado</th>
                            <th>Hora tarea</th>
                            <th>Flujo asignado</th>
                            <th>Usuario</th>
                            <th>Usuarios reportados</th>

                            <th>Suma asegurada</th>
                             <th>Tarifa</th>
                            <th>Prima bruta</th>
                            <th>Extra prima</th>
                            <th>Prima emitida</th>
                            <th>% Comisión</th>
                            <th>Comisión neta</th>
                            <th>IVA</th>
                            <th>Prima líquida</th>
                            <th>Anexo declaración</th>
                            <th>Fecha vencimiento</th>
                            <th>Fecha envío corrección</th>
                            <th>Fecha seguimiento cobro</th>
                            <th>Fecha reporte CIA</th>
                            <th>Reproceso NR</th>
                            <th>Fecha aplicación</th>
                            <th>Comentarios</th>
                            <th>Número Cisco</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($polizas_deuda as $deuda)
                            <tr>
                                <td>
                                    <a
                                        href="{{ url('control_cartera') }}/{{ $deuda->Id }}/1/{{ $anio }}/{{ $mes }}">
                                        <button class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                    </a>
                                </td>

                                {{-- Datos base --}}
                                <td>{{ $deuda->clientes->Nombre ?? '' }}</td>
                                <td>{{ $deuda->VigenciaDesde ? date('d/m/Y', strtotime($deuda->VigenciaDesde)) : '' }}</td>
                                <td>{{ $deuda->VigenciaHasta ? date('d/m/Y', strtotime($deuda->VigenciaHasta)) : '' }}</td>
                                <td>Deuda</td>
                                <td>{{ $deuda->aseguradoras->Abreviatura ?? '' }}</td>
                                <td>{{ $deuda->NumeroPoliza }}</td>

                                {{-- Campos de control_cartera (solo si existen) --}}
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaRecepcionArchivo ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaRecepcionArchivo)) : '' }}
                                </td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaEnvioCia ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaEnvioCia)) : '' }}
                                </td>
                                <td></td> {{-- Trabajo efectuado (no existe) --}}
                                <td></td> {{-- Hora tarea (no existe) --}}
                                <td></td> {{-- Flujo asignado (no existe) --}}
                                <td>{{ $deuda->Usuario ?? '' }}</td>
                                <td>{{ number_format($deuda->UsuariosReportados, 0, '.', ',') }}</td>

                                {{-- ✅ Campos reales de $deuda --}}
                                <td>{{ number_format($deuda->MontoCartera, 2) }}</td>
                                <td>{{ number_format($deuda->Tasa, 2) }}</td>
                                <td>{{ number_format($deuda->PrimaCalculada, 2) }}</td>
                                <td>{{ number_format($deuda->ExtraPrima, 2) }}</td>
                                <td>{{ number_format($deuda->PrimaDescontada, 2) }}</td>
                                <td>{{ number_format($deuda->TasaComision, 2) }}</td>
                                <td>{{ number_format($deuda->Comision, 2) }}</td>
                                <td>{{ number_format($deuda->IvaSobreComision ?? $deuda->Iva, 2) }}</td>
                                <td>{{ number_format($deuda->APagar, 2) }}</td>

                                <td>{{ $deuda->Anexo }}</td>
                                <td>{{ $deuda->VigenciaHasta ? date('d/m/Y', strtotime($deuda->VigenciaHasta)) : '' }}</td>

                                {{-- Columnas sin campo real --}}
                                <td></td> {{-- Fecha envío corrección --}}
                                <td></td> {{-- Fecha seguimiento cobro --}}
                                <td></td> {{-- Fecha reporte CIA --}}
                                <td></td> {{-- Reproceso NR --}}
                                <td>{{ $deuda->FechaIngreso ? date('d/m/Y', strtotime($deuda->FechaIngreso)) : '' }}</td>
                                <td>{{ $deuda->Comentario ?? '' }}</td>
                                <td></td> {{-- Número Cisco --}}
                            </tr>
                            @php($i++)
                        @endforeach
                    </tbody>

                </table>


            </div>
        </div>
    </div>
@endsection
