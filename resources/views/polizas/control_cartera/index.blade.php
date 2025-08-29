@extends ('welcome')
@section('contenido')
    <style>
        .table {
            font-size: 12px;
        }
    </style>
    <div class="x_panel">
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Control de flujo de carteras</h4>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12" align="right">

                <select class="form-control" name="Mes" id="Mes">
                    @foreach ($meses as $key => $nombre)
                        <option value="{{ $key }}" @if ($key == $mes) selected @endif>
                            {{ $nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" align="right">

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
                <table  id="datatable"  class="table table-striped table-bordered">
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
                                <td>{{ $deuda->clientes->Nombre ?? '' }}</td>
                                <td>{{ $deuda->VigenciaDesde ? date('d/m/Y', strtotime($deuda->VigenciaDesde)) : '' }}</td>
                                <td>{{ $deuda->VigenciaHasta ? date('d/m/Y', strtotime($deuda->VigenciaHasta)) : '' }}</td>
                                <td>Deuda</td>
                                <td>{{ $deuda->aseguradoras->Abreviatura ?? '' }}</td>
                                <td>{{ $deuda->NumeroPoliza }}</td>

                                {{-- Campos de control_cartera --}}
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaRecepcionArchivo ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaRecepcionArchivo)) : '' }}
                                </td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaEnvioCia ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaEnvioCia)) : '' }}
                                </td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->TrabajoEfectuado ?? '' }}</td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->HoraTarea ? date('H:i', strtotime($deuda->control_cartera_por_mes_anio->HoraTarea)) : '' }}
                                </td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->FlujoAsignado ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->Usuario ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->UsuariosReportados ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->Tarifa ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->PrimaBruta ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->ExtraPrima ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->PrimaEmitida ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->PorcentajeComision ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->ComisionNeta ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->Iva ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->PrimaLiquida ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->AnexoDeclaracion ?? ''}}</td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaVencimiento ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaVencimiento)) : '' }}
                                </td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaEnvioCorreccion ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaEnvioCorreccion)) : '' }}
                                </td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaSeguimientoCobro ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaSeguimientoCobro)) : '' }}
                                </td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaReporteCia ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaReporteCia)) : '' }}
                                </td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->RepocesoNr ?? '' }}</td>
                                <td>{{ optional($deuda->control_cartera_por_mes_anio)->FechaAplicacion ? date('d/m/Y', strtotime($deuda->control_cartera_por_mes_anio->FechaAplicacion)) : '' }}
                                </td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->Comentarios ?? '' }}</td>
                                <td>{{ $deuda->control_cartera_por_mes_anio->NumeroCisco ?? '' }}</td>
                            </tr>
                            @php($i++)
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
@endsection
