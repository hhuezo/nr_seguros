@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">

                <h2>Editar Ejecutivo <small></small></h2>

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
            <form method="POST" action="{{ route('ejecutivos.update', $ejecutivo->Id) }}">
                @method('PUT')
                @csrf
                <div class="x_content">
                    <br />
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Nombre</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Nombre" class="form-control" value="{{$ejecutivo->Nombre}}" required autofocus="true">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Código</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Codigo" class="form-control" required value="{{$ejecutivo->Codigo}}">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Teléfono</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Telefono" class="form-control" value="{{$ejecutivo->Telefono}}" data-inputmask="'mask': ['9999-9999']">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Área Comercial</label>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <select name="AreaComercial" class="form-control select2" style="width: 100%">
                                    @foreach ($area_comercial as $obj)
                                    <option value="{{$obj->Id}}" {{$ejecutivo->AreaComercial == $obj->Id ? 'selected ="selected"' : ''}}>

                                        {{ $obj->Nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>


                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('catalogo/ejecutivos/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>

        </div>

    </div>
</div>
</div>
@endsection
