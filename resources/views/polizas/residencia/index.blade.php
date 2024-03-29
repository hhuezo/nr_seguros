@extends ('welcome')
@section('contenido')
<div class="x_panel">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Pólizas de Residencia </h3>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('polizas/residencia/create/') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i> Nuevo</button></a>
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
                        <th>Vigencia Desde</th>
                        <th>Vigencia Hasta</th>
                        <th>Estado</th>
                        <th>Vendedor</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($residencias as $obj)
                    <tr>
                        <td>{{$obj->NumeroPoliza}}</td>
                        @if($obj->Asegurado)
                        <td>{{ $obj->clientes->Nombre }}</td>
                        @else
                        <td></td>
                        @endif
                        <td>{{ $obj->aseguradoras->Nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($obj->VigenciaDesde)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($obj->VigenciaHasta)->format('d/m/Y') }}</td>
                        <td>{{ $obj->estadoPolizas->Nombre}}</td>
                        <td>{{ $obj->ejecutivos->Nombre }}</td>
                        <td align="center">
                            @can('edit users')
                            <a href="{{ url('polizas/residencia') }}/{{ $obj->Id }}/edit" class="on-default edit-row" title="Generar Pago">
                                <i class="fa fa-pencil fa-lg"></i></a>
                            @endcan
                            &nbsp;&nbsp;<a href="{{ url('polizas/residencia') }}/{{ $obj->Id }}/renovar" class="on-default edit-row" title="Renovar o Cancelar "><i class="fa fa-refresh fa-lg"></i></a>
                            @can('delete users')
                            &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" title="Anular Poliza"><i class="fa fa-trash fa-lg"></i></a>
                            @endcan
                            @if($obj->Modificar == 1)
                            &nbsp;&nbsp;<a href="" data-target="#modal-desactivar-{{ $obj->Id }}" data-toggle="modal" title="Desactivar modificación"><i class="fa fa-check-square fa-lg"></i></a>
                            @else
                            &nbsp;&nbsp;<a href="" data-target="#modal-activar-{{ $obj->Id }}" data-toggle="modal" title="Activar modificación"><i class="fa fa-square fa-lg"></i></a>
                            @endif
                        </td>
                    </tr>
                    @include('polizas.residencia.modal')
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection