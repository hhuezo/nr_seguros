@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nuevo producto <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{url('catalogo/producto')}}" class="btn btn-info fa fa-undo " style="color: white" >Atrás</a>
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
            <form action="{{ url('catalogo/producto') }}" method="POST" class="forms-sample">
                @csrf
                <div class="x_content">
                    <br />
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label ">Nombre del Producto</label>
                            <input type="text" name="Nombre" id="Nombre" value="{{old('Nombre')}}" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label for="Aseguradora" class="form-label">Aseguradora</label>
                            <select id="Aseguradora" name="Aseguradora" class="form-control select2" style="width: 100%">
                                @foreach ($aseguradoras as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Aseguradora') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 15px!important;">
                        <div class="col-sm-6">
                            <label for="NecesidadProteccion" class="form-label">Ramo</label>
                            <select id="NecesidadProteccion" name="NecesidadProteccion" class="form-control select2" style="width: 100%">
                                @foreach ($ramos as $obj)
                                <option value="{{ $obj->Id }}" {{ old('NecesidadProteccion') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="Descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="Descripcion">{{ old('Descripcion') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('catalogo/producto/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>
            </form>


        </div>
    </div>

</div>
<script type="text/javascript">

</script>
@endsection
