@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Nuevos créditos </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('catalogo/bombero/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Credito</th>
                            <th>Dui</th>
                            <th>Nit</th>
                            <th>Nombres</th>
                            <th>Edad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nuevos as $obj)
                            <tr>
                                <td>{{ $obj->NoRefereciaCredito }}</td>
                                <td>{{ $obj->Dui }}</td>
                                <td>{{ $obj->Nit }}</td>
                                <td>{{ $obj->PrimerNombre }} {{ $obj->SegundoNombre }} {{ $obj->SociedadNombre }} {{ $obj->PrimerApellido }} {{ $obj->SegundoApellido }} {{ $obj->CasadaApellido }}</td>
                                <td>{{ $obj->Edad }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection