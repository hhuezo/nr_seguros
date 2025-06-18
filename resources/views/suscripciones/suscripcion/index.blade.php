@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        <style>
            #datatable {
                font-size: 12px;
                /* Ajusta el tamaño según lo necesites */
            }
        </style>
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Suscripciones </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <button class="btn btn-primary" data-target="#modal-filtro-dui" data-toggle="modal"><i
                        class="fa fa-filter"></i></button>
                <button class="btn btn-warning" data-target="#modal-filtro" data-toggle="modal"><i
                        class="fa fa-calendar"></i></button>
                <a href="{{ url('suscripciones/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th># Tarea</th>
                            <th>Fecha ingreso</th>
                            <th>Gestor</th>
                            <th>CIA</th>
                            <th>Contratante </th>
                            <th>Número <br>póliza deuda</th>
                            <th>Número <br>póliza vida</th>
                            <th>Asegurado </th>
                            <th>DUI</th>
                            <th>Edad</th>
                            <th>Genero</th>
                            <th>Suma<br>Asegurada<br>Deuda</th>
                            <th>Suma<br>Asegurada<br>Vida</th>
                            <th>Tipo cliente</th>
                            <th>Altura</th>
                            <th>Peso</th>
                            <th>IMC</th>
                            <th>Padecimientos</th>
                            <th>Tipo de orden medica</th>
                            <th>Estatus del caso</th>
                            <th>Resumen de gestion</th>
                            <th>Fecha reportado cia</th>
                            <th>Tareas eva (sisa)</th>
                            <th>% Extra prima</th>
                            <th>Fecha de recepción de resolución de CIA</th>
                            <th>Fecha de envió de resolución al cliente</th>
                            <th>Días de procesamiento de resolución</th>
                            {{-- <th>Comentarios</th> --}}
                            <th>Opciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($suscripciones as $obj)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $obj->NumeroTarea }}</td>
                                <td>
                                    {{ $obj->FechaIngreso ? date('d/m/Y', strtotime($obj->FechaIngreso)) : '' }}
                                </td>
                                <td>{{ $obj->gestor->Nombre ?? ' ' }}</td>
                                <td>{{ $obj->compania->Nombre ?? '' }}</td>
                                <td>{{ $obj->contratante->Nombre ?? '' }}</td>
                                <td>{{ $obj->polizaDeuda->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->polizaVida->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->Asegurado }}</td>
                                <td>{{ $obj->Dui }}</td>
                                <td>{{ $obj->Edad }}</td>
                                <td>
                                    {{ $obj->Genero == 1 ? 'F' : ($obj->Genero == 2 ? 'M' : '') }}
                                </td>
                                <td>
                                    {{ $obj->SumaAseguradaDeuda !== null && $obj->SumaAseguradaDeuda > 0
                                        ? number_format($obj->SumaAseguradaDeuda, 2)
                                        : '' }}
                                </td>
                                <td>
                                    {{ $obj->SumaAseguradaVida !== null && $obj->SumaAseguradaVida > 0
                                        ? number_format($obj->SumaAseguradaVida, 2)
                                        : '' }}
                                </td>

                                <td>{{ $obj->tipoCliente->Nombre ?? '' }}</td>
                                <td>{{ $obj->Peso }} Lb</td>
                                <td>{{ $obj->Estatura }} Mts</td>
                                <td>{{ number_format($obj->Imc, 2) }}</td>
                                <td>{{ $obj->Padecimiento }}</td>
                                <td>{{ $obj->tipoOrdenMedica->Nombre ?? '' }}</td>
                                <td>{{ $obj->estadoCaso->Nombre }}</td>
                                <td class="bg-{{ $obj->resumenGestion->Color ?? '' }}">
                                    {{ $obj->resumenGestion->Nombre ?? '' }}</td>
                                <td>
                                    {{ $obj->FechaReportadoCia ? date('d/m/Y', strtotime($obj->FechaReportadoCia)) : '' }}
                                </td>
                                <td>{{ $obj->TareasEvaSisa }}</td>
                                <td>{{ $obj->ValorExtraPrima }}</td>
                                <td>
                                    {{ $obj->FechaResolucion ? date('d/m/Y', strtotime($obj->FechaResolucion)) : '' }}
                                </td>
                                <td>
                                    {{ $obj->FechaEnvioResoCliente ? date('d/m/Y', strtotime($obj->FechaEnvioResoCliente)) : '' }}
                                </td>
                                <td>
                                    {{ $obj->DiasProcesamientoResolucion ?? 0 }}
                                </td>
                                {{-- <td>
                                    @if ($obj->comentarios->count() > 0)
                                        @foreach ($obj->comentarios as $comentario)
                                            {{ $comentario->FechaCreacion }} - {{ $comentario->usuario->name ?? '' }} -
                                            {{ $comentario->Comentario }};
                                        @endforeach
                                    @endif

                                </td> --}}
                                <td align="center">
                                    <a href="{{ url('suscripciones') }}/{{ $obj->Id }}/edit" class="btn btn-primary"
                                        class="on-default edit-row">
                                        <i class="fa fa-pencil fa-lg"></i></a>

                                    <a href="#" class="btn btn-danger"
                                        data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i
                                            class="fa fa-trash fa-lg"></i></a>

                                    <a href="#" class="btn btn-info" data-target="#modal-comentario"
                                        data-toggle="modal" onclick="getComentarios({{ $obj->Id }})"><i
                                            class="fa fa-book fa-lg"></i></a>
                                </td>
                            </tr>
                            @include('suscripciones.suscripcion.modal')
                            @php($i++)
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>



    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-filtro">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="GET" action="{{ url('suscripciones') }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Filtrar</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label ">Fecha inicio</label>
                            <input type="date" name="FechaInicio" value="{{ $fecha_inicio }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="control-label ">Fecha final</label>
                            <input type="date" name="FechaFinal" value="{{ $fecha_final }}" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-filtro-dui">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="GET" action="{{ url('suscripciones') }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Filtrar</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label ">DUI/Otro doc. de identidad</label>
                            <input type="text" name="Documento" id="Documento" rows="1" class="form-control"
                                value="{{ $documento }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
        id="modal-comentario">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Comentarios</h4>
                </div>
                <div class="modal-body" id="listComentarios">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>


    <script type="text/javascript">
        function getComentarios(id) {

            $.get("{{ url('suscripciones/getComentarios') }}/" + id, function(data) {
                if (data.success) {
                    const comentarios = data.data;
                    const contenedor = $('#listComentarios');
                    contenedor.empty(); // Limpia el contenido anterior

                    if (comentarios.length > 0) {
                        const ul = $('<ul></ul>');

                        comentarios.forEach(comentario => {
                            ul.append(
                                `<li><strong>${comentario.FechaCreacion} - ${comentario.Usuario}</strong> <br> ${comentario.Comentario}</li><br>`
                            );
                        });

                        contenedor.append(ul);
                    } else {
                        contenedor.html('<p>No hay comentarios disponibles.</p>');
                    }
                } else {
                    $('#listComentarios').html('<p>Error al cargar los comentarios.</p>');
                }
            }).fail(function() {
                $('#listComentarios').html('<p>Error de conexión con el servidor.</p>');
            });

        }
    </script>

    {{--

    <script type="text/javascript">
        document.getElementById('Documento').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9\-]/g, '');
        });

        function noGuardardo() {
            Swal.fire('Debe guardar los datos inicial de la poliza');
        }



        $(document).ready(function() {
            // Procesar todas las filas al cargar la página
            $('.resultado-dias').each(function() {
                const $celda = $(this);
                const fechaInicio = $celda.data('fecha-inicio');
                const fechaFin = $celda.data('fecha-fin');
                const id = $celda.data('id');

                if (fechaInicio && fechaFin) {
                    $.ajax({
                        url: "{{ route('calcular.dias.habiles.json') }}",
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'fecha_inicio': fechaInicio,
                            'fecha_fin': fechaFin
                        },
                        success: function(response) {
                            $celda.text(response.dias_habiles);

                        },
                        error: function(xhr) {
                            $celda.text('0');
                            console.error(`Error para ID ${id}:`, xhr.responseJSON);
                        }
                    });
                } else {
                    $celda.text('0');
                }
            });



            if ($.fn.DataTable.isDataTable('#sus_tabla')) {
                $('#sus_tabla').DataTable().destroy();
            }
            $('#sus_tabla').DataTable({
                "language": {
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
                },
                layout: {
                    topStart: 'buttons',
                    topEnd: 'search'
                },
                scrollX: true,
                buttons: [{
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                                19, 20, 21, 22, 23, 24, 25, 26, 27, 28
                            ] // Especifica los índices de las columnas que deseas exportar
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                                19, 20, 21, 22, 23, 24, 25, 26, 27, 28
                            ] // Especifica los índices de las columnas que deseas exportar
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                                19, 20, 21, 22, 23, 24, 25, 26, 27, 28
                            ] // Especifica los índices de las columnas que deseas exportar
                        }
                    }
                    /*,
                                        {
                                            extend: 'pdf',
                                            orientation: 'landscape', // Configura la orientación a horizontal
                                            exportOptions: {
                                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                                                    19, 20, 21, 22, 23, 24, 25, 26, 27, 28
                                                ] // Especifica los índices de las columnas que deseas exportar
                                            }
                                        },
                                        {
                                            extend: 'print',
                                            text: 'Imprimir',
                                            exportOptions: {
                                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                                                    19, 20, 21, 22, 23, 24, 25, 26, 27, 28
                                                ] // Especifica los índices de las columnas que deseas exportar
                                            }
                                        }*/
                ],
                columnDefs: [{
                    targets: [28], // Índices de las columnas que deseas ocultar en la UI.
                    visible: false // Esto hace que las columnas estén ocultas en la tabla.
                }]
            });


        });
    </script> --}}
@endsection
