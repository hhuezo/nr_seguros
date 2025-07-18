@extends ('welcome')
@section('contenido')
    <style>
        .table {
            font-size: 12px;
        }
    </style>
    <div class="x_panel">
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Área Comercial </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                {{-- <a href="{{ url('catalogo/area_comercial/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a> --}}
                <button class="btn btn-info float-right" data-target="#modal-create" data-toggle="modal"> <i
                        class="fa fa-plus"></i>
                    Nuevo</button>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {{-- id="datatable" --}}
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Vigencia desde</th>
                            <th>Vigencia hasta</th>
                            <th>Tipo póliza</th>
                            <th>Póliza No</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($polizas_deuda as $deuda)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $deuda->clientes->Nombre ?? '' }}</td>
                                <td>{{ $deuda->VigenciaDesde ? date('d/m/Y', strtotime($deuda->VigenciaDesde)) : '' }}</td>
                                <td>{{ $deuda->VigenciaHasta ? date('d/m/Y', strtotime($deuda->VigenciaHasta)) : '' }}</td>
                                <td>{{ $deuda->aseguradoras->Nombre ?? '' }}</td>
                                <td>{{ $deuda->NumeroPoliza }}</td>
                            </tr>
                            @php($i++)
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
