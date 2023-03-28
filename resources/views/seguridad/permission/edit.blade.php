@extends ('menu')
@section ('contenido')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>

<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="x_title">
            <h2>Modificación de permiso</h2>
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

            {!!Form::model($permission,['method'=>'PATCH','route'=>['permission.update',$permission->id]])!!}
            {{Form::token()}}

            <form class="form-horizontal">
                <br>

                <div class="form-group row">
                    <label class="control-label col-md-3" align="right">Nombre</label>
                    <div class="col-md-6">
                        <input class="form-control" name="name" type="text" value="{{$permission->name}}" onblur="this.value = this.value.toLowerCase();">
                    </div>
                </div>


                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{url('seguridad/permission')}}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                </div>

            </form>

            {!!Form::close()!!}

        </div>
        @include('sweet::alert')
    </div>
</div>
@endsection
