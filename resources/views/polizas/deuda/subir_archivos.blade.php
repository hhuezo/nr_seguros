@extends ('welcome')
@section('contenido')
<div class="x_panel">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Subir Carteras de <br>  {{$deuda->NumeroPoliza}} | {{$deuda->clientes->Nombre}} </h3>
        </div>
        <!-- <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('polizas/deuda/create/') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i> Nuevo</button></a>
        </div> -->
        <div class="clearfix"></div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tipo de Cartera</th>
                        <th>Abreviatura</th>
                        <th>Descripcion</th>
                        <th>Datos Ingresados</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($linea_credito as $obj)
                    <tr>
                        <td>{{$obj->tipoCarteras->Nombre}}</td>
                        <td>{{$obj->saldos->Abreviatura}}</td>
                        <td>{{$obj->saldos->Descripcion}}</td>
                        <td>La idea es poner la cantidad de datos que se <br> subieron a la temporal de este tipo de credito</td>


                        <td align="center">

                            
                        <a  data-target="#modal-add-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-upload fa-lg"></i></a>
                            
                        </td>
                    </tr>
                    @include('polizas.deuda.add_cartera')
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection