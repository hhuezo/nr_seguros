@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Polizas de Vida </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('polizas/vida/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <td style="width: 150px;">{{ $obj->NumeroPoliza }}</td>
                            <th>Asegurado</th>
                            <th>Aseguradora</th>
                            <th>Ejecutivo</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vida as $obj)
                            <tr>
                                <td>{{ $obj->NumeroPoliza }}</td>
                                <td>{{ $obj->cliente->Nombre ?? '' }}</td>
                                <td>{{ $obj->aseguradora->Nombre ?? '' }}</td>
                                <td>{{ $obj->ejecutivo->Nombre ?? '' }}</td>
                                <td>{{ $obj->estadoPoliza->Nombre ?? '' }}</td>

                                <td align="center">
                                    @if ($obj->Configuracion == 1)
                                        <a href="{{ url('polizas/vida') }}/{{ $obj->Id }}?tab=2" title="Generar Pago">
                                            <i class="fa fa-file fa-lg"></i></a>
                                        <a href="{{ url('polizas/vida') }}/{{ $obj->Id }}/edit" title="Configuracion">
                                            <i class="fa fa-lock fa-lg"></i></a>
                                    @else
                                        <a href="{{ url('polizas/vida') }}/{{ $obj->Id }}/edit"
                                            title="Configuracion">
                                            <i class="fa fa-unlock fa-lg"></i></a>
                                    @endif
                                    @can('delete users')
                                        &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}"
                                            data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @include('polizas.vida.modal')
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
