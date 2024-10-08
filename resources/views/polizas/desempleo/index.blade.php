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
                        <th>Numero Poliza</th>
                        <th>Asegurado</th>
                        <th>Aseguradora</th>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($desempleo as $obj)
                    <tr>
                        <td>{{ $obj->NumeroPoliza }}</td>
                        @isset($obj->Asegurado)
                        <td>{{ $obj->clientes->Nombre }}</td>
                        @else
                        <td></td>
                        @endif
                        @isset($obj->Aseguradora)
                        <td>{{ $obj->aseguradoras->Nombre }}</td>
                        @else
                        <td></td>
                        @endif
                        @isset($obj->Ejecutivo)
                        <td>{{ $obj->ejecutivos->Nombre }}</td>
                        @else
                        <td></td>
                        @endif   
                        @isset($obj->EstadoPoliza)
                        <td>{{ $obj->estadoPolizas->Nombre }}</td>
                        @else
                        <td></td>
                        @endif
                       
                        <td align="center">

                            @if($obj->Configuracion == 1)
                            <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}/edit" class="on-default edit-row">
                                <i class="fa fa-pencil fa-lg"></i></a>&nbsp;
                                <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}" class="on-default edit-row">
                                <i class="fa fa-cog fa-lg"></i></a>
                            @else
                            <a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}" class="on-default edit-row">
                                <i class="fa fa-cog fa-lg"></i></a>
                            @endif
                            &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                            @can('delete userss')
                            &nbsp;&nbsp;<a href="{{ url('polizas/desempleo') }}/{{ $obj->Id }}/renovar" class="on-default edit-row"><i class="fa fa-refresh fa-lg"></i></a>

                            &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>


                            @endcan
                        </td>
                    </tr>
                    @include('polizas.desempleo.modal')
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection