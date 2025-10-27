@extends ('welcome')
@section('contenido')
    <style>
        .table {
            font-size: 12px;
        }
    </style>
    <div class="x_panel">
        <form method="GET" action="{{ url('control_cartera') }}">
            <div class="x_title">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h4>Control de flujo de carteras</h4>
                </div>

                <div class="col-md-2 col-sm-12 col-xs-12" align="right">
                    <select class="form-control" name="TipoPoliza" id="TipoPoliza">
                        <option value="1" {{ request('TipoPoliza') == 1 ? 'selected' : '' }}>Poliza deuda</option>
                        <option value="2" {{ request('TipoPoliza') == 2 ? 'selected' : '' }}>Poliza vida</option>
                        <option value="3" {{ request('TipoPoliza') == 3 ? 'selected' : '' }}>Poliza desempleo</option>
                        <option value="4" {{ request('TipoPoliza') == 4 ? 'selected' : '' }}>Poliza residencia</option>
                    </select>
                </div>

                <div class="col-md-2 col-sm-12 col-xs-12" align="right">
                    <select class="form-control" name="Mes" id="Mes">
                        @foreach ($meses as $key => $nombre)
                            <option value="{{ $key }}" @if ($key == $mes) selected @endif>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-12 col-xs-12" align="right">
                    <div class="input-group">
                        <select class="form-control" name="Anio" id="Anio">
                            @for ($i = date('Y'); $i >= 2024; $i--)
                                <option value="{{ $i }}" @if ($i == $anio) selected @endif>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </span>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </form>



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

                        @foreach ($registro_control as $registro)
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-edit-{{ $registro->Id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>

                                {{-- Datos base --}}
                                <td>{{ $registro->ClienteNombre ?? '' }}</td>
                                <td>{{ $registro->VigenciaDesde ? date('d/m/Y', strtotime($registro->VigenciaDesde)) : '' }}
                                </td>
                                <td>{{ $registro->VigenciaHasta ? date('d/m/Y', strtotime($registro->VigenciaHasta)) : '' }}
                                </td>
                                <td>Deuda</td>
                                <td>{{ $registro->ProductoNombre ?? '' }}</td>
                                <td>{{ $registro->NumeroPoliza }}</td>

                                {{-- Campos de control_cartera (solo si existen) --}}
                                <td></td>
                                <td></td>
                                <td></td> {{-- Trabajo efectuado (no existe) --}}
                                <td></td> {{-- Hora tarea (no existe) --}}
                                <td></td> {{-- Flujo asignado (no existe) --}}
                                <td>{{ $registro->Usuario ?? '' }}</td>
                                <td style="text-align: right">
                                    {{ number_format($registro->UsuariosReportados ?? 0, 0, '.', ',') }}</td>

                                {{-- ✅ Campos reales de $deuda --}}
                                <td>{{ number_format($registro->MontoCartera ?? 0, 2) }}</td>
                                <td>{{ number_format($registro->Tasa ?? 0, 2) }}</td>
                                <td>{{ number_format($registro->PrimaCalculada ?? 0, 2) }}</td>
                                <td>{{ number_format($registro->ExtraPrima ?? 0, 2) }}</td>
                                <td>{{ number_format($registro->PrimaDescontada ?? 0, 2) }}</td>
                                <td>{{ number_format($registro->TasaComision ?? 0, 2) }}</td>
                                <td>{{ number_format($registro->Comision ?? 0, 2) }}</td>
                                <td>{{ number_format($registro->IvaSobreComision ?? ($registro->Iva ?? 0), 2) }}</td>
                                <td>{{ number_format($registro->APagar ?? 0, 2) }}</td>

                                <td>{{ $registro->Anexo ?? '' }}</td>
                                <td>{{ $registro->VigenciaHasta ? date('d/m/Y', strtotime($registro->VigenciaHasta)) : '' }}
                                </td>

                                {{-- Columnas sin campo real --}}
                                <td></td> {{-- Fecha envío corrección --}}
                                <td></td> {{-- Fecha seguimiento cobro --}}
                                <td></td> {{-- Fecha reporte CIA --}}
                                <td></td> {{-- Reproceso NR --}}
                                <td>{{ $registro->FechaIngreso ? date('d/m/Y', strtotime($registro->FechaIngreso)) : '' }}
                                </td>
                                <td>{{ $registro->Comentario ?? '' }}</td>
                                <td></td> {{-- Número Cisco --}}
                            </tr>
                            @include('polizas.control_cartera.modal_edit')
                        @endforeach


                    </tbody>

                </table>


            </div>
        </div>
    </div>
@endsection
