@extends ('welcome')
@section('contenido')
<div class="x_panel">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Polizas de Desempleo </h3>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('polizas/desempleo/create/') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i> Nuevo</button></a>
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
                            <td>{{ $obj->NumeroPoliza }}</td>

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

                            <!-- Opciones -->
                            <td align="center">

                                    <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}/edit" class="on-default edit-row">
                                        <i class="fa fa-pencil fa-lg"></i>
                                    </a>

                                <!-- Configuración -->
                                <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}" class="on-default edit-row">
                                    <i class="fa fa-cog fa-lg"></i>
                                </a>

                                <!-- Eliminar -->
                                &nbsp;&nbsp;
                                <a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>

                                <!-- Renovar (solo para usuarios con permiso) -->
                                @can('delete userss')
                                    &nbsp;&nbsp;
                                    <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}/renovar" class="on-default edit-row">
                                        <i class="fa fa-refresh fa-lg"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>

                        <!-- Modal de eliminación -->
                        @include('polizas.desempleo.modal')
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection
