@extends ('layouts.admin')
@section ('contenido')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
<div class="x_panel">
    <div class="row">
        <div class="x_title">
            <h2>Cambiar Contraseña</h2>

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

            <form method="POST" action="{{route('cambiar.password', $usuario->id)}}">
                {{Form::token()}}
                <br />

                <div class="form-group">
                    <label class="col-sm-3 control-label">Nombre</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="name" value="{{$usuario->name}}" readonly class="form-control">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Usuario</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="user" id="user" class="form-control" readonly value="{{$usuario->user}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Clave</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="password" id="password" required value="{{old('password')}}" class="form-control">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Confirmar Clave</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="ConfirmPassword" id="ConfirmPassword" required value="{{old('ConfirmPassword')}}" class="form-control">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-primary" onclick="validar()">Guardar</button>
                    <a href="{{url('seguridad/user/')}}"><button class="btn btn-danger" type="button">Cancelar</button></a>
                </div>


            </form>




            <!-- jQuery -->
            <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>

            <script type="text/javascript">
                $(document).ready(function() {
                    document.getElementById('Activo').value = 0;

                });

                function validar() {
                   
                        if (document.getElementById('Activo').checked == true) {
                            document.getElementById('Activo').value = 1;
                        } else {
                            document.getElementById('Activo').value = 0;
                        }
                        var parametros
                    }
                
            </script>
            <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}">

            </script>

            <script type="text/javascript">
                function modal(id) {
                    document.getElementById('oficina').value = id;
                    document.getElementById('Users').value = document.getElementById('Usuarios').value;
                    //document.getElementById('role').value = document.getElementById('rol').value;

                    $('#modal_borrar_permiso').modal('show');

                }
            </script>
        </div>
        @include('sweet::alert')
    </div>
</div>



@endsection