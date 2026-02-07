@extends ('welcome')
@section('contenido')
@can('linea-credito edit')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Editar Tipo Cartera <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">

                </ul>
                <div class="clearfix"></div>
            </div>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="x_content">
                <br />

                <form method="POST" action="{{ route('tipo_cartera.update', $tipo_cartera->Id) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-horizontal">
                        <br>


                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Nombre" type="text" value="{{$tipo_cartera->Nombre}}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de Póliza</label>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <select name="Poliza" class="form-control select2" id="TipoCobro" style="width: 100%" required>

                                    <option value="1" @if($tipo_cartera->Poliza == 1) selected @endif>Vida</option>
                                    <option value="2" @if($tipo_cartera->Poliza == 2) selected @endif>Deuda</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('catalogo/tipo_cartera') }}?idRegistro={{$tipo_cartera->Id}}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>

                    </div>
                </form>



            </div>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@else
    <p class="text-center text-danger">No tiene permiso para editar.</p>
@endcan
@endsection
