@extends ('welcome')
@section('contenido')

    <style>
        #loading-overlay-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loading-overlay-modal img {
            width: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
            height: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
        }
    </style>
    <div class="x_panel">
        <div id="loading-overlay-modal">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
        </div>
        <style>
            #datatable {
                font-size: 12px;
                /* Ajusta el tamaño según lo necesites */
            }
        </style>
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Control de flujo de suscripciones </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <button class="btn btn-success" data-target="#modal-importar" data-toggle="modal"><i
                        class="fa fa fa-file-text-o"></i></button>
                <button class="btn btn-primary" data-target="#modal-filtro-dui" data-toggle="modal"><i
                        class="fa fa-filter"></i></button>
                <button class="btn btn-warning" data-target="#modal-filtro" data-toggle="modal"><i
                        class="fa fa-calendar"></i></button>
                @can('suscripcion create')
                <a href="{{ url('suscripciones/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>No.</th>
                            <th># Tarea</th>
                            <th>Fecha ingreso</th>
                            <th>Gestor</th>
                            <th>CIA</th>
                            <th>Tipo de poliza</th>
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


                        </tr>
                    </thead>

                </table>

            </div>
        </div>
    </div>



    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-filtro">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="GET" action="{{ url('suscripciones') }}" id="form-filtro">
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

                        <div class="form-group ">
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label class="switch">
                                    <input type="checkbox" name="Exportar">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label" style="margin-left: -37px;"> Exportar excel</label>
                            </div>
                            <br>
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

                        <div class="form-group">
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label class="switch">
                                    <input type="checkbox" name="Exportar">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label" style="margin-left: -37px;"> Exportar excel</label>
                            </div>
                            <br>
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

    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-importar">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('suscripciones/importar') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Importar Archivo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label ">Archivo</label>
                            <input type="file" name="Archivo" id="Archivo" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btn_importar">Aceptar</button>
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


    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete">

        <form id="form-delete" method="POST">
            @method('delete')
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Eliminar Registro</h4>
                    </div>
                    <div class="modal-body">
                        <p>Confirme si desea Eliminar el Registro</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </form>

    </div>


    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>


    <script>
        $(document).ready(function() {

            const pageSize = 10; // debe coincidir con pageLength
            const recordIndex = {{ $recordIndex }}; // registro que quieres mostrar
            const page = Math.floor((recordIndex - 1) / pageSize);


            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: pageSize,
                displayStart: page * pageSize, // 👈 aquí saltas a la página
                ajax: '{{ url('suscripciones/data') }}/{{ $fecha_inicio }}/{{ $fecha_final }}',
                columns: [{
                        data: 'acciones',
                        name: 'acciones',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'Id',
                        name: 'suscripcion.Id'
                    },
                    {
                        data: 'NumeroTarea',
                        name: 'suscripcion.NumeroTarea'
                    },
                    {
                        data: 'FechaIngreso',
                        name: 'suscripcion.FechaIngreso'
                    },
                    {
                        data: 'Ejecutivo',
                        name: 'ejecutivo.Nombre'
                    },
                    {
                        data: 'Aseguradora',
                        name: 'aseguradora.Nombre'
                    },
                    {
                        data: 'TipoPoliza',
                        name: 'TipoPoliza',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'Contratante',
                        name: 'cliente.Nombre'
                    },
                    {
                        data: 'PolizaDeuda',
                        name: 'poliza_deuda.NumeroPoliza'
                    },
                    {
                        data: 'PolizaVida',
                        name: 'poliza_vida.NumeroPoliza'
                    },
                    {
                        data: 'Asegurado',
                        name: 'suscripcion.Asegurado'
                    },
                    {
                        data: 'Dui',
                        name: 'suscripcion.Dui'
                    },
                    {
                        data: 'Edad',
                        name: 'suscripcion.Edad'
                    },
                    {
                        data: 'Genero',
                        name: 'suscripcion.Genero'
                    },
                    {
                        data: 'SumaAseguradaDeuda',
                        name: 'suscripcion.SumaAseguradaDeuda'
                    },
                    {
                        data: 'SumaAseguradaVida',
                        name: 'suscripcion.SumaAseguradaVida'
                    },
                    {
                        data: 'TipoCliente',
                        name: 'sus_tipo_cliente.Nombre'
                    },
                    {
                        data: 'Estatura',
                        name: 'suscripcion.Estatura'
                    }, // Aún si es CONCAT, usa la tabla base
                    {
                        data: 'Peso',
                        name: 'suscripcion.Peso'
                    },
                    {
                        data: 'Imc',
                        name: 'suscripcion.Imc'
                    },
                    {
                        data: 'Padecimiento',
                        name: 'suscripcion.Padecimiento'
                    },
                    {
                        data: 'TipoOrdenMedica',
                        name: 'sus_orden_medica.Nombre'
                    },
                    {
                        data: 'EstadoCaso',
                        name: 'sus_estado_caso.Nombre'
                    },
                    {
                        data: 'ResumenGestion',
                        name: 'sus_resumen_gestion.Nombre',
                        createdCell: function(td, cellData, rowData) {
                            const color = rowData.Color || '';
                            $(td).addClass('bg-' + color);
                        }
                    },
                    {
                        data: 'FechaReportadoCia',
                        name: 'suscripcion.FechaReportadoCia'
                    },
                    {
                        data: 'TareasEvaSisa',
                        name: 'suscripcion.TareasEvaSisa'
                    },
                    {
                        data: 'ValorExtraPrima',
                        name: 'suscripcion.ValorExtraPrima'
                    },
                    {
                        data: 'FechaResolucion',
                        name: 'suscripcion.FechaResolucion'
                    },
                    {
                        data: 'FechaEnvioResoCliente',
                        name: 'suscripcion.FechaEnvioResoCliente'
                    },
                    {
                        data: 'DiasProcesamientoResolucion',
                        name: 'suscripcion.DiasProcesamientoResolucion'
                    },

                ]
            });

        });
    </script>



    <script type="text/javascript">
        document.getElementById('btn_importar').addEventListener('click', function() {
            document.getElementById('loading-overlay-modal').style.display = 'flex';
        });

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



        function shoModalDelete(id) {
            const baseUrl = '{{ url('suscripciones') }}';
            // 1) Construimos la URL definitiva
            const url = `${baseUrl}/${id}`;

            // 2) Asignamos el action al form
            document.getElementById('form-delete').action = url;

            // 3) Mostramos el modal
            $('#modal-delete').modal('show');
        }
    </script>


    <script>
        $('#form-filtro').on('submit', function() {
            const exportar = $(this).find('input[name="Exportar"]').is(':checked');

            if (exportar) {
                $('#modal-filtro').modal('hide');
            }
        });
    </script>

@endsection
