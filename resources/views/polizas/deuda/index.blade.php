@extends ('welcome')
@section('contenido')
<div class="x_panel">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Polizas de Deuda </h3>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('polizas/deuda/create/') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i> Nuevo</button></a>
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
                    @foreach ($deuda as $obj)
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

                        <td align="right">
                            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">
                                @if($obj->Configuracion == 1)
                                <a href="{{ url('polizas/deuda') }}/{{ $obj->Id }}/edit" class="btn btn-primary on-default edit-row" title="Generar Pago">
                                    <i class="fa fa-file fa-lg"></i></a>
                                <a href="{{ url('polizas/deuda') }}/{{ $obj->Id }}" class="btn btn-success on-default edit-row" title="Configuracion">
                                    <i class="fa fa-lock fa-lg"></i></a>
                                @else
                                <a href="{{ url('polizas/deuda') }}/{{ $obj->Id }}" class="btn btn-success on-default edit-row" title="Configuracion">
                                    <i class="fa fa-unlock fa-lg"></i></a>
                                @endif
                                <a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" class="btn btn-danger" title="Eliminar"><i class="fa fa-trash fa-lg"></i></a>
                                
                                <a data-target="#modal-renovar-{{ $obj->Id }}" data-toggle="modal" class="on-default edit-row btn btn-info" title="Renovar"><i class="fa fa-refresh fa-lg"></i></a>
                                <!-- <a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" class="btn btn-danger" title="Eliminar"><i class="fa fa-trash fa-lg"></i></a> -->
                                
                            </div>
                        </td>
                        
                    </tr>
                    @include('polizas.deuda.modal')
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection