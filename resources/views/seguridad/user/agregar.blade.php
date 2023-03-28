@extends ('menu')
@section ('contenido')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>&nbsp; Agregar Paciente<small></small></h2>
                <ul class="nav navbar-right panel_toolbox">

                </ul>
                <div class="clearfix"></div>
            </div>
            @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="x_content">
                <br />

                {!!Form::open(array('url'=>'guardar_paciente','method'=>'POST','autocomplete'=>'off'))!!}
                {{Form::token()}}

                <div class="form-group">
                    <label class="col-sm-3 control-label">DUI</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input name="dui" id="dui" class="form-control" autofocus="true" type="text" required="true" data-inputmask="'mask': ['99999999-9']">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group" style="display: show;" id="Nombre">
                    <label class="col-sm-3 control-label">Nombre</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="nombre" id="nombre" class="form-control" autofocus="true" onblur="this.value = this.value.toUpperCase()">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Correo Electronico</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="email" id="email" class="form-control" autofocus="true">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Telefono</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="telefono" id="telefono" class="form-control" autofocus="true" data-inputmask="'mask': ['9999-9999']" required="true" onblur="this.value = this.value.toUpperCase()">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Fecha de Nacimiento</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" autofocus="true" required="true" onblur="this.value = this.value.toUpperCase()">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Genero</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="genero" id="genero" class="form-control" required>

                            <option value="1">Femenino</option>
                            <option value="2">Masculino</option>
                            <!-- <option value="3">No Determinado</option> -->
                        </select>
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Peso</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input name="peso" class="form-control" autofocus="true" type="number" id="peso" placeholder="lbs" step="0.01" required="true" onblur="this.value = this.value.toUpperCase()">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Estatura</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input name="estatura" class="form-control" autofocus="true" placeholder="mts" type="number" id="estatura" step="0.01" required="true" onblur="this.value = this.value.toUpperCase()">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>
                <br>
                <br>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Doctores</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="Doctor" id="Doctor" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach($doctores as $obj)
                            <option value="{{$obj->Id}}">{{$obj->Nombre}} {{$obj->especialidad->Nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Fecha</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input name="Fecha" id="calendario" class="form-control" autofocus="true" type="date" required="true" value="{{date('Y-m-d')}}">
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Horario</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="Hora" id="Hora" class="form-control" required>

                        </select>
                    </div>
                    <label class="col-sm-3 control-label">&nbsp;</label>
                </div>


                <input name="rol" class="form-control" value="4" type="hidden">

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{url('admin_citas/')}}" class="btn btn-default">Cancelar</a>
                </div>

                {!!Form::close()!!}



            </div>
            <div id="data"></div>

        </div>
        @include('sweet::alert')
    </div>
</div>
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#calendario").change(function() {

            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            var parametros = {
                "Doctor": document.getElementById('Doctor').value,
                "Fecha": document.getElementById('calendario').value
            };
            $.ajax({
                type: "get",
                //ruta para obtener el horario del doctor
                url: "{{ url('get_horario') }}",
                data: parametros,
                success: function(data) {
                    console.log(data);
                    var _select = '<option value="">SELECCIONE</option>'
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Hora +
                        '</option>';

                    $('#Hora').html(_select);
                }
            });

        });
    });
</script>



@endsection