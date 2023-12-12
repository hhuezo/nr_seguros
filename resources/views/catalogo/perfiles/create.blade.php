@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nueva Perfiles <small></small></h2>
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

                <form action="{{ url('catalogo/perfiles') }}" method="POST">
                    @csrf
                    <div class="form-horizontal">
                        <br>
                        <div class="col-md-3">&nbsp;</div>
                        <div class="form-group row col-md-6">
                            <label for="Aseguradora" class="form-label">Aseguradora</label>
                            <select id="Aseguradora" name="Aseguradora" class="form-control select2" style="width: 100%">
                                @foreach ($aseguradoras as $obj)
                                <option value="{{ $obj->Id }}">{{$obj->Nombre}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">&nbsp;</div>
                        <br>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             &nbsp;
                        </div>
                        <div class="col-md-3">&nbsp;</div>
                        <div class="form-group row col-md-6">
                            <label class="control-label" align="right">Descripción</label>

                            <textarea name="Descripcion" id="Descripcion" cols="30" rows="10" class="form-control"></textarea>

                        </div>




                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('catalogo/perfiles/') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>

                    </div>
                </form>



            </div>

        </div>
    </div>
</div>
@include('sweetalert::alert')
@endsection