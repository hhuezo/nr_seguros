@extends ('welcome')
@section('contenido')
<div class="x_panel">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Configuracion de Recibo </h3>
        </div>
        <!-- <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('catalogo/departamento_nr/create/') }}"><button class="btn btn-info float-right"> <i
                        class="fa fa-plus"></i> Nuevo</button></a>
        </div> -->
        <div class="clearfix"></div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nota</th>
                        <th>Pie</th>
                        <th style="width: 30%;">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($configuracion as $obj)
                    <tr>
                        <td>{{ $obj->Nota }}</td>
                        <td>{{ $obj->Pie }}</td>
                        <td style="vertical-align: middle; text-align: center;"> <a href="{{ url('catalogo/configuracion_recibo') }}/{{ $obj->Id }}/edit"
                                class="on-default edit-row" >
                                <i class="fa fa-pencil fa-lg"></i></a></td>
                    </tr>
                    <!-- @include('catalogo.configuracion_recibo.modal') -->
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection