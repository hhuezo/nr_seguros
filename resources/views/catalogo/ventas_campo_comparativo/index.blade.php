@extends ('welcome')
@section('contenido')
    @can('ventas-campo-comparativo read')
        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

        <style>
            #tabla-plantillas-comparativas,
            #tabla-plantillas-comparativas_wrapper {
                font-size: 12px;
            }

            #tabla-plantillas-comparativas td,
            #tabla-plantillas-comparativas th {
                vertical-align: middle;
            }

            .acciones-plantilla {
                white-space: nowrap;
                min-width: 86px;
            }

            .acciones-plantilla .btn {
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
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <h3>Plantillas comparativas por ramo</h3>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="alert alert-info">
                Cada ramo contiene su propia lista de conceptos estandar para armar comparativos de ventas.
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table id="tabla-plantillas-comparativas" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="8%">No</th>
                                <th width="62%">Ramo</th>
                                <th width="18%">Conceptos agregados</th>
                                <th width="12%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ramos as $ramo)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $ramo->Nombre }}</strong></td>
                                    <td>
                                        <span class="label label-info">
                                            {{ $conteos[$ramo->Id] ?? 0 }} conceptos agregados
                                        </span>
                                    </td>
                                    <td align="center" class="acciones-plantilla">
                                        <a href="{{ url('catalogo/ventas_campo_comparativo/ramo/' . $ramo->Id) }}" class="btn btn-primary btn-sm" title="Editar plantilla">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            $(function () {
                if (!$.fn.DataTable.isDataTable('#tabla-plantillas-comparativas')) {
                    $('#tabla-plantillas-comparativas').DataTable({
                        scrollY: 450,
                        scrollCollapse: true,
                        pageLength: 25,
                    });
                }
            });
        </script>
    @endcan
@endsection
