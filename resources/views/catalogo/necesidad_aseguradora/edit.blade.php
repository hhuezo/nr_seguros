@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Asignación necesidad de protección a aseguradora <small></small></h2>
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

                
                <form method="POST" action="{{ route('necesidad_aseguradora.update', $asignacion->Id) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-horizontal">
                        <br>


                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora </label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                            <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                    
                                    @foreach ($aseguradoras as $obj)
                                    <option value="{{ $obj->Id }}" {{ $asignacion->aseguradora_id == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Necesidades de Protección </label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                            <select name="NecesidadProteccion" class="form-control select2" style="width: 100%" required>
                                    
                                    @foreach ($necesidades as $obj)
                                    <option value="{{ $obj->Id }}" {{ $asignacion->necesidad_proteccion_id == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de Póliza </label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                            <select name="TipoPoliza" class="form-control select2" style="width: 100%" required>
                                    
                                    @foreach ($tipo_polizas as $obj)
                                    <option value="{{ $obj->Id }}" {{ $asignacion->TipoPoliza == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('catalogo/estado_polizas') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
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
