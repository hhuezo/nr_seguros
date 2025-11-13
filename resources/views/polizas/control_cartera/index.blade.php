@extends('welcome')
@section('contenido')
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
                    <div class="clearfix"></div>
                </div>
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
                            <th>Fecha envío a CIA.</th>
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
                        <tr v-for="registro in registros" :key="registro.Id" :class="getRowClass(registro.Color)">
                            <td>
                                <button class="btn btn-primary btn-sm" @click="abrirModal(registro)">
                                    <i class="fa fa-edit"></i>
                                </button>
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
                $('#datatable1').DataTable({
                    paging: false,
                    searching: true,
                    info: false,
                    ordering: false,
                    scrollX: true,
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

                    // Normalizar a solo la parte "YYYY-MM-DD"
                    // Si incluye hora, ejemplo: "2025-08-01 00:00:00"
                    if (date.includes(' ')) {
                        date = date.split(' ')[0];
                    }

                    // Seguridad adicional: eliminar milisegundos si vienen
                    if (date.includes('.')) {
                        date = date.split('.')[0];
                    }

                    const [year, month, day] = date.split('-');

                    // Evitar errores si por alguna razón la fecha no viene como se espera
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

                                // Buscar nombre del reproceso
                                const reproceso = this.reprocesos.find(
                                    r => r.Id == this.registroActivo.ReprocesoNRId
                                );

                                this.registroActivo.ReprocesoNombre =
                                    reproceso ? reproceso.Nombre : '';

                                // Actualizar registro completo
                                this.registros[index] = JSON.parse(
                                    JSON.stringify(this.registroActivo)
                                );

                                // Recalcular color
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
    </script>
@endsection
