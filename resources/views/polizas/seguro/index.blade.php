@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Listado de polizas de seguro </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                @can('seguro create')
                <a href="{{ url('poliza/seguro/create') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i>
                        Nuevo</button></a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>



        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Número Póliza</th>
                            <th>Asegurado</th>
                            <th>Forma pago</th>
                            <th>Producto</th>
                            <th>Plan</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($polizas as $poliza)
                            <tr>
                                <!-- Número de Póliza -->
                                <td>{{ $poliza->NumeroPoliza }}</td>
                                <td>{{ $poliza->clientes->Nombre ?? '' }}</td>
                                <td>{{ $poliza->forma_pago->Nombre ?? '' }}</td>
                                <td>{{ $poliza->producto->Nombre ?? '' }}</td>
                                <td>{{ $poliza->plan->Nombre ?? '' }}</td>
                                <td>{{ $poliza->estado_polizas->Nombre ?? '' }}</td>


                                <!-- Opciones -->
                                <td align="center">
                                    @can('seguro read')
                                        <a href="{{ url('poliza/seguro') }}/{{ $poliza->Id }}"
                                            class="btn btn-sm btn-info" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('seguro delete')
                                        <a href="" data-target="#modal-delete-{{ $poliza->Id }}" data-toggle="modal"
                                            class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    @endcan
                                   {{-- @if ($poliza->Configuracion == 0)
                                        <a href="{{ url('polizas/desempleo') }}/{{ $poliza->Id }}/edit"
                                            class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i>
                                        </a>
                                    @else
                                        <!-- Configuración -->
                                        <a href="{{ url('polizas/desempleo') }}/{{ $poliza->Id }}"
                                            class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i>
                                        </a>
                                    @endif
                                    <!-- Eliminar -->
                                    &nbsp;&nbsp;
                                    <a href="" data-target="#modal-delete-{{ $poliza->Id }}" data-toggle="modal">
                                        <i class="fa fa-trash fa-lg"></i>
                                    </a>

                                    <!-- Renovar (solo para usuarios con permiso) -->

                                     &nbsp;&nbsp;
                                    <a href="{{ url('polizas/desempleo') }}/{{ $poliza->Id }}/renovar"
                                        class="on-default edit-row">
                                        <i class="fa fa-refresh fa-lg"></i>
                                    </a> --}}

                                </td>
                            </tr>
                            @can('seguro delete')
                                <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                                    id="modal-delete-{{ $poliza->Id }}">
                                    <form method="POST" action="{{ route('seguro.destroy', $poliza->Id) }}">
                                        @method('delete')
                                        @csrf
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title">Eliminar poliza</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Confirme si desea eliminar la poliza {{ $poliza->NumeroPoliza }}.</p>
                                                    <p>Esta accion no podrá reestablecerse.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endcan
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                pageLength: 10,
                ordering: false
            });
        });
    </script>
    @include('sweetalert::alert')
@endsection
