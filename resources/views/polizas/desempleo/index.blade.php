@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Polizas de Desempleo </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                @can('desempleo create')
                <a href="{{ url('polizas/desempleo/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Número de Póliza</th>
                            <th>Asegurado</th>
                            <th>Aseguradora</th>
                            <th>Ejecutivo</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($desempleo as $obj)
                            <tr>
                                <!-- Número de Póliza -->
                                <td style="width: 150px;">{{ $obj->NumeroPoliza }}</td>

                                <!-- Asegurado -->
                                <td>
                                    @isset($obj->Asegurado)
                                        {{ $obj->cliente->Nombre }}
                                    @else
                                        Sin asegurado
                                    @endisset
                                </td>

                                <!-- Aseguradora -->
                                <td>
                                    @isset($obj->Aseguradora)
                                        {{ $obj->aseguradora->Nombre }}
                                    @else
                                        Sin aseguradora
                                    @endisset
                                </td>

                                <!-- Ejecutivo -->
                                <td>
                                    @isset($obj->Ejecutivo)
                                        {{ $obj->ejecutivo->Nombre }}
                                    @else
                                        Sin ejecutivo
                                    @endisset
                                </td>

                                <!-- Estado de la Póliza -->
                                <td>
                                    @isset($obj->EstadoPoliza)
                                        {{ $obj->estadoPoliza->Nombre }}
                                    @else
                                        Sin estado
                                    @endisset
                                </td>


                                <td class="text-center">
                                    <div class="poliza-opciones">
                                        @can('desempleo edit')
                                            <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}?tab=2"
                                                class="btn btn-sm btn-info" title="Generar Pago">
                                                <i class="fa fa-file"></i>
                                            </a>
                                            <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}/edit"
                                                class="btn btn-sm btn-primary" title="Editar">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endcan
                                        <a data-target="#modal-renovar-{{ $obj->Id }}" data-toggle="modal"
                                            class="btn btn-sm btn-success" title="Renovar">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                        @can('desempleo delete')
                                            <a href="" data-target="#modal-delete-{{ $obj->Id }}"
                                                data-toggle="modal" class="btn btn-sm btn-danger" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @include('polizas.desempleo.modal')

                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script>
        var displayStart = {{ $posicion }};
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                pageLength: 10,
                displayStart: displayStart,
                ordering: false
            });
        });
    </script>
    @include('sweetalert::alert')
@endsection
