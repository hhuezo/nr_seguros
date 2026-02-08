@extends('welcome')
@section('contenido')
@can('control-cartera read')
    <style>
        .table {
            font-size: 12px;
        }

        .table-success,
        .table-success>th,
        .table-success>td {
            background-color: #9adf8f !important;
            color: #0f3e23 !important;
        }

        .table-warning,
        .table-warning>th,
        .table-warning>td {
            background-color: #fffc4c !important;
            color: #5a4300 !important;
        }

        .table-info,
        .table-info>th,
        .table-info>td {
            background-color: #91d6e3 !important;
            color: #063c45 !important;
        }

        .table-orange,
        .table-orange>th,
        .table-orange>td {
            background-color: #ffb84d !important;
            color: #4a2500 !important;
        }

        .table-secondary,
        .table-secondary>th,
        .table-secondary>td {
            background-color: #f5f5f5 !important;
            color: #000000 !important;
        }

        tfoot th {
            background: #e9ecef !important;
            font-weight: 700;
        }

        .x_panel, .x_title {
            margin-bottom: 0 !important;
        }
    </style>




    <div id="app">
        <div class="x_panel">
            <form method="GET" action="{{ url('control_cartera') }}">
                <div class="x_title">
                    <div class="col-md-6">
                        <h4>Control de flujo de carteras</h4>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="TipoPoliza">
                            <option value="1" {{ request('TipoPoliza') == 1 ? 'selected' : '' }}>Personas</option>
                            <option value="2" {{ request('TipoPoliza') == 2 ? 'selected' : '' }}>Residencia</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="Mes">
                            @foreach ($meses as $key => $nombre)
                                <option value="{{ $key }}" @if ($key == $mes) selected @endif>
                                    {{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <select class="form-control" name="Anio">
                                @for ($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}" @if ($i == $anio) selected @endif>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                            <span class="input-group-btn"><button type="submit"
                                    class="btn btn-primary">Aceptar</button></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success" onclick="exportarExcel()">
                            <i class="fa fa-file-excel-o"></i> Exportar Excel
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>

            <form id="form-exportar-excel" method="POST" action="{{ url('control_cartera/exportar_excel') }}" style="display: none;">
                @csrf
                <input type="hidden" name="TipoPoliza" value="{{ request('TipoPoliza', 1) }}">
                <input type="hidden" name="Mes" value="{{ $mes }}">
                <input type="hidden" name="Anio" value="{{ $anio }}">
            </form>

            <div class="x_content">
                <table id="datatable1" class="table table-striped table-bordered">
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
                            <th>Fecha envío CIA.</th>
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
                            <th>Fech envío cliente</th>
                            <th>Reproceso de NR</th>
                            <th>Fecha envío corrección</th>
                            <th>Fecha seguimiento cobros</th>
                            <th>Fecha recepción de pago</th>
                            <th>Fecha reporte CIA.</th>
                            <th>Fecha aplicación</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="registro in registros" :key="registro.Id" :class="getRowClass(registro.Color)">
                            <td>
                                @can('control-cartera edit')
                                <button class="btn btn-primary btn-sm" @click="abrirModal(registro)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                @endcan
                            </td>
                            <td>@{{ registro.ClienteNombre }}</td>
                            <td>@{{ formatDate(registro.VigenciaDesde) }}</td>
                            <td>@{{ formatDate(registro.VigenciaHasta) }}</td>
                            <td>@{{ registro.PlanNombre }}</td>
                            <td>@{{ registro.Abreviatura }}</td>
                            <td>@{{ registro.NumeroPoliza }}</td>
                            <td>@{{ formatDate(registro.FechaRecepcionArchivo) }}</td>
                            <td>@{{ formatDate(registro.FechaEnvioCia) }}</td>
                            <td>@{{ registro.TrabajoEfectuadoDiaHabil }}</td>
                            <td>@{{ registro.HoraTarea }}</td>
                            <td>@{{ registro.FlujoAsignado }}</td>
                            <td>@{{ registro.Usuario }}</td>
                            <td>@{{ formatNumber(registro.UsuariosReportados) }}</td>
                            <td>@{{ formatNumber(registro.MontoCartera) }}</td>
                            <td>@{{ registro.Tasa }}</td>
                            <td>@{{ formatNumber(registro.PrimaCalculada) }}</td>
                            <td>@{{ formatNumber(registro.ExtraPrima) }}</td>

                            <td>@{{ formatNumber(registro.PrimaDescontada) }}</td>

                            <td>@{{ formatNumber(registro.Descuento) }}</td>
                            <td>@{{ formatNumber(registro.ValorDescuentoRentabilidad) }}</td>
                            <td>@{{ formatNumber(registro.PrimaDescontada) }}</td>
                            <td>@{{ formatNumber(registro.TasaComision) }}</td>
                            <td>@{{ formatNumber(registro.Comision) }}</td>
                            <td>@{{ formatNumber(registro.IvaSobreComision ?? registro.Iva) }}</td>
                            <td>@{{ formatNumber(registro.Retencion) }}</td>
                            <td>@{{ formatNumber(registro.APagar) }}</td>
                            <td>@{{ registro.AnexoDeclaracion }}</td>
                            <td>@{{ registro.NumeroRecibo }}</td>
                            <td>@{{ formatDate(registro.FechaInicio) }}</td>
                            <td>@{{ formatDate(registro.FechaEnvioCliente) }}</td>
                            <td>@{{ registro.ReprocesoNombre }}</td>
                            <td>@{{ formatDate(registro.FechaEnvioCorreccion) }}</td>
                            <td>@{{ formatDate(registro.FechaSeguimientoCobros) }}</td>
                            <td>@{{ formatDate(registro.FechaRecepcionPago) }}</td>
                            <td>@{{ formatDate(registro.FechaReporteACia) }}</td>
                            <td>@{{ formatDate(registro.FechaAplicacion) }}</td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr  style="display: :none !important">
                            <th colspan="14" style="text-align:right">TOTALES:</th>
                            <th></th> <!-- 14 Suma asegurada (MontoCartera) -->
                            <th></th> <!-- 15 Tarifa -->
                            <th></th> <!-- 16 Prima bruta (PrimaCalculada) -->
                            <th></th> <!-- 17 Extra prima -->
                            <th></th> <!-- 18 Prima emitida -->
                            <th></th> <!-- 19 % rentabilidad -->
                            <th></th> <!-- 20 Valor descuento rentabilidad -->
                            <th></th> <!-- 21 Prima descontada -->
                            <th></th> <!-- 22 % comisión -->
                            <th></th> <!-- 23 Comisión -->
                            <th></th> <!-- 24 IVA -->
                            <th></th> <!-- 25 Retención -->
                            <th></th> <!-- 26 Prima líquida -->
                            <th colspan="10"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- 🔹 MODAL COMPLETA -->
        <div class="modal fade" id="modal-edit" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" v-if="registroActivo">
                    <div class="modal-header">
                        <h5 class="modal-title">@{{ registroActivo.ClienteNombre }}</h5>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <!-- COLUMNA IZQUIERDA -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Recepción archivo</label>
                                    <input type="date" class="form-control"
                                        v-model="registroActivo.FechaRecepcionArchivo"
                                        @change="calcularDiasHabiles(registroActivo)">
                                </div>

                                <div class="form-group">
                                    <label>Fecha envío a CIA</label>
                                    <input type="date" class="form-control" v-model="registroActivo.FechaEnvioCia"
                                        @change="calcularDiasHabiles(registroActivo)">
                                </div>

                                <div class="form-group">
                                    <label>Trabajo día hábil</label>
                                    <input type="number" class="form-control"
                                        v-model="registroActivo.TrabajoEfectuadoDiaHabil" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Hora tarea</label>
                                    <input type="time" class="form-control" v-model="registroActivo.HoraTarea">
                                </div>

                                <div class="form-group">
                                    <label>Flujo asignado</label>
                                    <input type="text" class="form-control" v-model="registroActivo.FlujoAsignado">
                                </div>

                                <div class="form-group">
                                    <label>Fecha envío cliente</label>
                                    <input type="date" class="form-control" v-model="registroActivo.FechaEnvioCliente">
                                </div>

                                <div class="form-group">
                                    <label>Anexo declaración</label>
                                    <input type="text" class="form-control" v-model="registroActivo.AnexoDeclaracion">
                                </div>

                            </div>

                            <!-- COLUMNA DERECHA -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Reproceso NR</label>
                                    <select class="form-control" v-model="registroActivo.ReprocesoNRId">
                                        <option value="">SELECCIONE</option>
                                        @foreach ($reprocesos as $reproceso)
                                            <option value="{{ $reproceso->Id }}">{{ $reproceso->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Fecha envío corrección</label>
                                    <input type="date" class="form-control"
                                        v-model="registroActivo.FechaEnvioCorreccion">
                                </div>

                                <div class="form-group">
                                    <label>Fecha seguimiento cobros</label>
                                    <input type="date" class="form-control"
                                        v-model="registroActivo.FechaSeguimientoCobros">
                                </div>

                                <div class="form-group">
                                    <label>Fecha recepción pago</label>
                                    <input type="date" class="form-control" v-model="registroActivo.FechaRecepcionPago">
                                </div>

                                <div class="form-group">
                                    <label>Fecha reporte CIA</label>
                                    <input type="date" class="form-control" v-model="registroActivo.FechaReporteACia">
                                </div>

                                <div class="form-group">
                                    <label>Fecha aplicación</label>
                                    <input type="date" class="form-control" v-model="registroActivo.FechaAplicacion">
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" @click="guardarCambios">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <br>

    {{-- Scripts --}}

    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    <script src="{{ asset('vendors/jquery.dataTables.min.js') }}"></script>
    <link href="{{ asset('vendors/jquery.dataTables.min.css') }}" rel="stylesheet" />

    <script src="{{ asset('vendors/vue.global.prod.js') }}"></script>

    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    registros: @json($registro_control ?? []),
                    registroActivo: null,
                    reprocesos: @json($reprocesos ?? [])
                }
            },
            mounted() {
                this.$nextTick(() => {

                    $('#datatable1').DataTable({
                        destroy: true,
                        paging: false,
                        searching: true,
                        info: false,
                        ordering: false,
                        scrollX: true,

                        scrollY: '60vh',
                        scrollCollapse: true,

                        footerCallback: function(row, data) {
                            const api = this.api();

                            const toNumber = (val) => {
                                if (val === null || val === undefined) return 0;
                                const s = String(val)
                                    .replace(/\s/g, '')
                                    .replace(/[^0-9\.\-]/g,
                                        ''
                                    ); // quita comas/puntos de miles/cualquier símbolo
                                const n = parseFloat(s);
                                return isNaN(n) ? 0 : n;
                            };

                            const sum = (col) => {
                                const total = api
                                    .column(col, {
                                        search: 'applied'
                                    })
                                    .data()
                                    .reduce((a, b) => toNumber(a) + toNumber(b), 0);

                                return total.toLocaleString('es-SV', {
                                    minimumFractionDigits: 2
                                });
                            };

                            // Índices exactos según TU tabla
                            $(api.column(14).footer()).html(sum(
                                14)); // Suma asegurada (MontoCartera)
                            $(api.column(16).footer()).html(sum(
                                16)); // Prima bruta (PrimaCalculada)
                            $(api.column(17).footer()).html(sum(17)); // Extra prima
                            $(api.column(18).footer()).html(sum(18)); // Prima emitida
                            $(api.column(20).footer()).html(sum(
                                20)); // Valor descuento rentabilidad
                            $(api.column(21).footer()).html(sum(21)); // Prima descontada
                            $(api.column(23).footer()).html(sum(23)); // Comisión neta
                            $(api.column(24).footer()).html(sum(24)); // IVA 13%
                            $(api.column(25).footer()).html(sum(25)); // Retención 1%
                            $(api.column(26).footer()).html(sum(26)); // Prima líquida
                        },

                        language: {
                            decimal: ",",
                            thousands: ".",
                            processing: "Procesando...",
                            search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ registros",
                            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            infoEmpty: "Mostrando 0 a 0 de 0 registros",
                            infoFiltered: "(filtrado de _MAX_ registros totales)",
                            infoPostFix: "",
                            loadingRecords: "Cargando...",
                            zeroRecords: "No se encontraron resultados",
                            emptyTable: "Ningún dato disponible en esta tabla",
                            paginate: {
                                first: "Primero",
                                previous: "Anterior",
                                next: "Siguiente",
                                last: "Último"
                            },
                            aria: {
                                sortAscending: ": activar para ordenar la columna ascendente",
                                sortDescending: ": activar para ordenar la columna descendente"
                            }
                        }
                    });

                });
            },
            methods: {

                // =====================================================
                // 🔹 CLASES DE COLOR
                // =====================================================
                getRowClass(color) {
                    const map = {
                        success: 'table-success',
                        warning: 'table-warning',
                        info: 'table-info',
                        orange: 'table-orange',
                        secondary: 'table-secondary'
                    };
                    return map[color] ?? 'table-secondary';
                },

                // =====================================================
                // 🔹 FORMATEO DE FECHA
                // =====================================================
                formatDate(date) {
                    if (!date) return '';

                    if (date.includes(' ')) {
                        date = date.split(' ')[0];
                    }

                    if (date.includes('.')) {
                        date = date.split('.')[0];
                    }

                    const [year, month, day] = date.split('-');
                    if (!year || !month || !day) return '';

                    return `${day}/${month}/${year}`;
                },

                // =====================================================
                // 🔹 FORMATEO DE NUMEROS
                // =====================================================
                formatNumber(num) {
                    if (!num || isNaN(num)) return '';
                    return parseFloat(num).toLocaleString('es-SV', {
                        minimumFractionDigits: 2
                    });
                },

                // =====================================================
                // 🔹 ABRIR MODAL
                // =====================================================
                abrirModal(registro) {
                    this.registroActivo = JSON.parse(JSON.stringify(registro));
                    $('#modal-edit').modal('show');
                },

                // =====================================================
                // 🔹 CALCULAR DÍAS HÁBILES
                // =====================================================
                calcularDiasHabiles(registro) {
                    if (!registro.FechaRecepcionArchivo || !registro.FechaEnvioCia) {
                        registro.TrabajoEfectuadoDiaHabil = '';
                        return;
                    }

                    $.ajax({
                        url: "{{ route('calcular.dias.habiles.json') }}",
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'fecha_inicio': registro.FechaRecepcionArchivo,
                            'fecha_fin': registro.FechaEnvioCia
                        },
                        success: (response) => {
                            registro.TrabajoEfectuadoDiaHabil =
                                response?.dias_habiles ?? '';
                        },
                        error: () => {
                            registro.TrabajoEfectuadoDiaHabil = '';
                        }
                    });
                },

                // =====================================================
                // 🔹 CÁLCULO DEL COLOR (igual que backend)
                // =====================================================
                calcularColor(item) {
                    if (item.FechaAplicacion) return 'success';

                    if (item.FechaRecepcionPago || item.FechaReporteACia)
                        return 'warning';

                    if (
                        item.AnexoDeclaracion ||
                        item.NumeroSisco ||
                        item.FechaVencimiento ||
                        item.FechaEnvioCliente ||
                        item.ReprocesoNRId ||
                        item.FechaEnvioCorreccion ||
                        item.FechaSeguimientoCobros
                    ) return 'info';

                    if (
                        item.FechaRecepcionArchivo ||
                        item.FechaEnvioCia ||
                        item.TrabajoEfectuadoDiaHabil
                    ) return 'orange';

                    return 'secondary';
                },

                // =====================================================
                // 🔹 GUARDAR CAMBIOS
                // =====================================================
                guardarCambios() {
                    if (!this.registroActivo) return;

                    $.ajax({
                        url: "{{ url('control_cartera') }}/" + this.registroActivo.Id,
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ...this.registroActivo
                        },
                        success: (response) => {

                            const index = this.registros.findIndex(
                                r => r.Id === this.registroActivo.Id
                            );

                            if (index !== -1) {

                                const reproceso = this.reprocesos.find(
                                    r => r.Id == this.registroActivo.ReprocesoNRId
                                );

                                this.registroActivo.ReprocesoNombre =
                                    reproceso ? reproceso.Nombre : '';

                                this.registros[index] = JSON.parse(
                                    JSON.stringify(this.registroActivo)
                                );

                                this.registros[index].Color =
                                    this.calcularColor(this.registros[index]);
                            }

                            $('#modal-edit').modal('hide');
                            toastr.success('Registro actualizado correctamente', 'Éxito');
                        },
                        error: (xhr) => {
                            console.error(xhr.responseText);
                            toastr.error('No se pudo actualizar el registro', 'Error');
                        }
                    });
                }

            }
        }).mount('#app');

        function exportarExcel() {
            document.getElementById('form-exportar-excel').submit();
        }
    </script>
@else
    <p class="text-center text-danger">No tiene permiso para ver Control de carteras.</p>
@endcan
@endsection
