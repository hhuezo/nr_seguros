@extends ('welcome')
@section('contenido')
    <style>
        .table {
            font-size: 12px;
        }
    </style>
    <style>
        /* 🟢 Verde intenso (éxito) */
        .table-success,
        .table-success>th,
        .table-success>td {
            background-color: #9adf8f !important;
            /* más vivo */
            color: #0f3e23 !important;
        }

        /* 🟡 Amarillo brillante (advertencia) */
        .table-warning,
        .table-warning>th,
        .table-warning>td {
            background-color: #fffc4c !important;
            /* más fuerte */
            color: #5a4300 !important;
        }

        /* 🔵 Azul intenso (info) */
        .table-info,
        .table-info>th,
        .table-info>td {
            background-color: #91d6e3 !important;
            /* azul más saturado */
            color: #063c45 !important;
        }

        /* 🔴 Rojo vivo (error/peligro) */
        .table-danger,
        .table-danger>th,
        .table-danger>td {
            background-color: #f28b82 !important;
            /* rojo más brillante */
            color: #5d0a0a !important;
        }

        /* 🟠 Naranja fuerte (personalizado) */
        .table-orange,
        .table-orange>th,
        .table-orange>td {
            background-color: #ffb84d !important;
            /* naranja más intenso */
            color: #4a2500 !important;
        }

        /* ⚪ Blanco neutro (secundario o sin color) */
        .table-secondary,
        .table-secondary>th,
        .table-secondary>td {
            background-color: #f5f5f5 !important;
            color: #000000 !important;
        }

        /* Opcional: bordes más suaves */
        .table td,
        .table th {
            border-color: #dddddd !important;
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
                        <option value="1" {{ request('TipoPoliza') == 1 ? 'selected' : '' }}>Personas</option>
                        <option value="2" {{ request('TipoPoliza') == 2 ? 'selected' : '' }}>Poliza residencia</option>
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

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Asegurado</th>
                            <th>Vigencia desde</th>
                            <th>Vigencia hasta</th>
                            <th>Tipo de póliza</th>
                            <th>CIA. de seguros</th>
                            <th>Póliza No.</th>
                            <th>Fecha recepción archivo</th>
                            <th>Fecha de envío a CIA.</th>
                            <th>Trabajo efectuado día hábil</th>
                            <th>Hora tarea</th>
                            <th>Flujo asignado</th>
                            <th>Usuario</th>
                            <th>Usuarios reportados</th>
                            <th>Suma asegurada</th>
                            <th>Tarifa</th>
                            <th>Prima bruta</th>
                            <th>Extra prima</th>
                            <th>Prima emitida</th>
                            <th>% de rentabilidad</th>
                            <th>Valor descuento rentabilidad</th>
                            <th>Prima descontada</th>
                            <th>% de comisión</th>
                            <th>Comisión neta</th>
                            <th>IVA 13%</th>
                            <th>Retención 1%</th>
                            <th>Prima líquida</th>
                            <th>Anexo de declaración</th>
                            <th>Número AC SISCO</th>
                            <th>Fecha vencimiento</th>
                            <th>Fecha de envío a cliente</th>
                            <th>Reproceso de NR</th>
                            <th>Fecha de envío de corrección</th>
                            <th>Fecha seguimiento cobros</th>
                            <th>Fecha recepción de pago</th>
                            <th>Fecha de reporte a CIA.</th>
                            <th>Fecha de aplicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registro_control as $registro)
                            @php
                                $map = [
                                    'success' => 'table-success',
                                    'warning' => 'table-warning',
                                    'info' => 'table-info',
                                    'orange' => 'table-orange', // personalizada
                                    'secondary' => 'table-secondary',
                                ];
                                $key = $registro->Color ?? 'secondary';
                                $colorClass = $map[$key] ?? 'table-secondary';
                            @endphp

                            <tr id="fila-{{ $registro->Id }}" class="{{ $colorClass }}">
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-edit-{{ $registro->Id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                                <td>{{ $registro->ClienteNombre ?? '' }}</td>
                                <td>{{ $registro->VigenciaDesde ? date('d/m/Y', strtotime($registro->VigenciaDesde)) : '' }}
                                </td>
                                <td>{{ $registro->VigenciaHasta ? date('d/m/Y', strtotime($registro->VigenciaHasta)) : '' }}
                                </td>
                                <td>{{$registro->TipoPoliza}}</td>
                                <td>{{ $registro->ProductoNombre ?? '' }}</td>
                                <td>{{ $registro->NumeroPoliza ?? '' }}</td>
                                <td>{{ $registro->FechaRecepcionArchivo ? date('d/m/Y', strtotime($registro->FechaRecepcionArchivo)) : '' }}
                                </td>
                                <td>{{ $registro->FechaEnvioCia ? date('d/m/Y', strtotime($registro->FechaEnvioCia)) : '' }}
                                </td>
                                <td>{{ $registro->TrabajoEfectuadoDiaHabil ?? '' }}</td>
                                <td>{{ $registro->HoraTarea ?? '' }}</td>
                                <td>{{ $registro->FlujoAsignado ?? '' }}</td>
                                <td>{{ $registro->Usuario ?? '' }}</td>

                                <td class="text-end">
                                    {{ $registro->UsuariosReportados && $registro->UsuariosReportados != 0 ? number_format($registro->UsuariosReportados, 0, '.', ',') : '' }}
                                </td>
                                <td>
                                    {{ $registro->MontoCartera && $registro->MontoCartera != 0 ? number_format($registro->MontoCartera, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->Tasa && $registro->Tasa != 0 ? number_format($registro->Tasa, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->PrimaCalculada && $registro->PrimaCalculada != 0 ? number_format($registro->PrimaCalculada, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->ExtraPrima && $registro->ExtraPrima != 0 ? number_format($registro->ExtraPrima, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->PrimaDescontada && $registro->PrimaDescontada != 0 ? number_format($registro->PrimaDescontada, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->Descuento && $registro->Descuento != 0 ? number_format($registro->Descuento, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->ValorDescuentoRentabilidad && $registro->ValorDescuentoRentabilidad != 0 ? number_format($registro->ValorDescuentoRentabilidad, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->PrimaDescontada && $registro->PrimaDescontada != 0 ? number_format($registro->PrimaDescontada, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->TasaComision && $registro->TasaComision != 0 ? number_format($registro->TasaComision, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->Comision && $registro->Comision != 0 ? number_format($registro->Comision, 2) : '' }}
                                </td>
                                <td>
                                    {{ ($registro->IvaSobreComision ?? $registro->Iva) && ($registro->IvaSobreComision ?? $registro->Iva) != 0 ? number_format($registro->IvaSobreComision ?? $registro->Iva, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->Retencion && $registro->Retencion != 0 ? number_format($registro->Retencion, 2) : '' }}
                                </td>
                                <td>
                                    {{ $registro->APagar && $registro->APagar != 0 ? number_format($registro->APagar, 2) : '' }}
                                </td>



                                <td>{{ $registro->AnexoDeclaracion ?? '' }}</td>
                                <td>
                                    {{ $registro->NumeroRecibo && $registro->Axo
                                        ? 'AC ' . str_pad($registro->NumeroRecibo, 6, '0', STR_PAD_LEFT) . ' ' . $registro->Axo
                                        : '' }}
                                </td>

                                <td>{{ $registro->FechaInicio ? date('d/m/Y', strtotime($registro->FechaInicio)) : '' }}
                                </td>

                                <td>{{ $registro->FechaEnvioCliente ? date('d/m/Y', strtotime($registro->FechaEnvioCliente)) : '' }}
                                </td>


                                <td>{{ $registro->ReprocesoNombre ?? '' }}</td>
                                <td>{{ $registro->FechaEnvioCorreccion ? date('d/m/Y', strtotime($registro->FechaEnvioCorreccion)) : '' }}
                                </td>
                                <td>{{ $registro->FechaSeguimientoCobros ? date('d/m/Y', strtotime($registro->FechaSeguimientoCobros)) : '' }}
                                </td>

                                <td>{{ $registro->FechaRecepcionPago ? date('d/m/Y', strtotime($registro->FechaRecepcionPago)) : '' }}
                                </td>


                                <td>{{ $registro->FechaReporteACia ? date('d/m/Y', strtotime($registro->FechaReporteACia)) : '' }}
                                </td>
                                <td>{{ $registro->FechaAplicacion ? date('d/m/Y', strtotime($registro->FechaAplicacion)) : '' }}
                                </td>
                            </tr>
                            @include('polizas.control_cartera.modal_edit')
                        @endforeach
                    </tbody>
                </table>



            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                paging: false,
                searching: true,
                info: true,
                ordering: false,
            });
        });


        function calcularDiasHabiles(id) {

            const fechaInicio = document.getElementById('FechaRecepcionArchivo' + id).value;
            const fechaFin = document.getElementById('FechaEnvioCia' + id).value;

            console.log("fechaInicio: ", fechaInicio);
            console.log("fechaFin: ", fechaFin);

            if (!fechaInicio || !fechaFin) return; // si falta una fecha, no hace nada

            let inicio = new Date(fechaInicio);
            let fin = new Date(fechaFin);

            // si las fechas están invertidas, intercambiarlas
            if (fin < inicio)[inicio, fin] = [fin, inicio];

            let diasHabiles = 0;
            let fechaTemp = new Date(inicio);

            while (fechaTemp <= fin) {
                const diaSemana = fechaTemp.getDay(); // 0 = domingo, 6 = sábado
                if (diaSemana !== 0 && diaSemana !== 6) {
                    diasHabiles++;
                }
                fechaTemp.setDate(fechaTemp.getDate() + 1);
            }

            document.getElementById('TrabajoEfectuadoDiaHabil' + id).value = diasHabiles;
        }
    </script>
@endsection
