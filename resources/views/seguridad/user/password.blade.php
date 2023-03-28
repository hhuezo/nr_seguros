@extends ('menu')
@section ('contenido')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
<div class="x_panel">
    <div class="row">
        <div class="x_title">
            <h2>Cambio de contraseña</h2>

            <ul class="nav navbar-right panel_toolbox">

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {!!Form::model($usuario,['method'=>'PATCH','route'=>['password.update',$usuario->id]])!!}
            {{Form::token()}}
            <br />

            <div class="form-group">
                <label class="col-sm-3 control-label">Nueva contraseña</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" name="Password" class="form-control" minlength="6" required>
                </div>
                <label class="col-sm-3 control-label">&nbsp;</label>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Repita contraseña</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" name="Password2" class="form-control" minlength="6" required>
                </div>
            </div>

            <div class="form-group" align="center">
                <button class="btn btn-success" type="submit">Guardar</button>
                <a href="{{url('catalogo/usuario/')}}"><button class="btn btn-primary" type="button">Cancelar</button></a>
            </div>


            {!!Form::close()!!}


        </div>
        @include('sweet::alert')
    </div>



</div>




@endsection