@extends ('welcome')
@section('contenido')
    @can('ventas-campo-comparativo read')
        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

        <style>
            #tabla-campos-comparativos,
            #tabla-campos-comparativos_wrapper {
                font-size: 12px;
            }

            #tabla-campos-comparativos td,
            #tabla-campos-comparativos th {
                vertical-align: middle;
            }

            .acciones-campo-comparativo {
                white-space: nowrap;
                min-width: 86px;
            }

            .acciones-campo-comparativo .btn {
                width: 34px;
                height: 30px;
                padding: 5px 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        </style>

        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <h3>Plantilla comparativa: {{ $ramo->Nombre }}</h3>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12" align="right">
                    <a href="{{ url('catalogo/ventas_campo_comparativo') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                    @can('ventas-campo-comparativo create')
                        <button class="btn btn-info" data-target="#modal-create" data-toggle="modal">
                            <i class="fa fa-plus"></i> Nuevo concepto
                        </button>
                    @endcan
                </div>
                <div class="clearfix"></div>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-info">
                Estos conceptos son las casillas que deberan llenarse en cada plan comercial de este ramo.
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table id="tabla-campos-comparativos" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="8%">No</th>
                                <th width="42%">Concepto</th>
                                <th width="35%">Nombre interno</th>
                                <th width="7%">Orden</th>
                                <th width="8%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campos as $obj)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $obj->Etiqueta }}</strong></td>
                                    <td>{{ $obj->NombreInterno }}</td>
                                    <td class="text-center">{{ $obj->Orden }}</td>
                                    <td align="center" class="acciones-campo-comparativo">
                                        <div class="btn-group" role="group">
                                            @can('ventas-campo-comparativo edit')
                                                <button class="btn btn-primary btn-sm" data-target="#modal-edit-{{ $obj->Id }}" data-toggle="modal" title="Editar concepto">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            @endcan
                                            @can('ventas-campo-comparativo delete')
                                                <button class="btn btn-danger btn-sm" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" title="Desactivar concepto">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modal-edit-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('ventas_campo_comparativo.update', $obj->Id) }}">
                                                @method('PUT')
                                                @csrf
                                                <input type="hidden" name="ReturnRamo" value="{{ $ramo->Id }}">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Editar concepto</h4>
                                                </div>
                                                <div class="modal-body">
                                                    @include('catalogo.ventas_campo_comparativo.partials.form', ['campo' => $obj, 'ramoActual' => $ramo])
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-delete-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('ventas_campo_comparativo.destroy', $obj->Id) }}">
                                                @method('delete')
                                                @csrf
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Eliminar registro</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Confirme si desea desactivar el concepto <strong>{{ $obj->Etiqueta }}</strong>.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ url('catalogo/ventas_campo_comparativo') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ReturnRamo" value="{{ $ramo->Id }}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Nuevo concepto</h4>
                        </div>
                        <div class="modal-body">
                            @include('catalogo.ventas_campo_comparativo.partials.form', ['campo' => null, 'ramoActual' => $ramo])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function normalizarTextoCatalogo(input) {
                if (!input || input.readOnly) {
                    return;
                }

                var start = input.selectionStart;
                var end = input.selectionEnd;
                input.value = input.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
                input.setSelectionRange(start, end);
            }

            $(document).on('input', '.campo-mayuscula', function () {
                normalizarTextoCatalogo(this);
            });

            $(function () {
                if (!$.fn.DataTable.isDataTable('#tabla-campos-comparativos')) {
                    $('#tabla-campos-comparativos').DataTable({
                        scrollY: 450,
                        scrollCollapse: true,
                        pageLength: 25,
                        order: [[3, 'asc']],
                    });
                }
            });
        </script>
    @endcan
@endsection
