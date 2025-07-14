@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
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
                <h3>Suscripciones </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <button class="btn btn-success" data-target="#modal-importar" data-toggle="modal"><i class="fa fa fa-file-text-o"></i></button>
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
                                <td>{{ $obj->FechaIngreso ? date('d/m/Y', strtotime($obj->FechaIngreso)) : '' }}</td>
                                <td>{{ $obj->gestor->Nombre ?? ' ' }}</td>
                                <td>{{ $obj->compania->Nombre ?? '' }}</td>
                                <td>{{ $obj->contratante->Nombre ?? '' }}</td>
                                <td>{{ $obj->polizaDeuda->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->polizaVida->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->Asegurado }}</td>
                                <td>{{ $obj->Dui }}</td>
                                <td>{{ $obj->Edad }}</td>
                                <td>{{ $obj->Genero == 1 ? 'F' : ($obj->Genero == 2 ? 'M' : '') }} </td>
                                <td>{{ $obj->SumaAseguradaDeuda !== null && $obj->SumaAseguradaDeuda > 0
                                        ? number_format($obj->SumaAseguradaDeuda, 2)
                                        : '' }}
                                </td>
                                <td>{{ $obj->SumaAseguradaVida !== null && $obj->SumaAseguradaVida > 0
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
                                    {{ $obj->resumenGestion->Nombre ?? '' }}
                                </td>
                                <td>{{ $obj->FechaReportadoCia ? date('d/m/Y', strtotime($obj->FechaReportadoCia)) : '' }} </td>
                                <td>{{ $obj->TareasEvaSisa }}</td>
                                <td>{{ $obj->ValorExtraPrima }}</td>
                                <td>{{ $obj->FechaResolucion ? date('d/m/Y', strtotime($obj->FechaResolucion)) : '' }}</td>
                                <td>{{ $obj->FechaEnvioResoCliente ? date('d/m/Y', strtotime($obj->FechaEnvioResoCliente)) : '' }}</td>
                                <td>{{ $obj->DiasProcesamientoResolucion ?? 0 }}</td>
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
                            <input type="file" name="Archivo" id="Archivo"  class="form-control" required>
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


    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>


    <script type="text/javascript">
        document.getElementById('btn_importar').addEventListener('click', function () {
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
    </script>
@endsection
