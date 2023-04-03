@extends ('welcome')
@section('contenido')
<script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Consulta de Nuevos Negocios <small></small></h2>
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fecha Inicio</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                <input type="date" name="FechaInicio" id="FechaInicio" class="form-control"  required autofocus="true">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fecha Final</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                <input type="date" name="FechaFinal" id="FechaFinal" class="form-control">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Ejecutivo</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                <select name="Ejecutivo" id="Ejecutivo" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach($ejecutivo as $obj)
                                    <option value="{{$obj->Id}}">{{$obj->Codigo}} {{$obj->Nombre}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>


                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit" id="btn_aceptar">Consultar</button>
                    <a href="{{ url('catalogo/negocio/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

        </div>

        <div id="response">

        </div>

    </div>
</div>
<script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#btn_aceptar").click(function() { 
                $('#response').html('<div><img src="../../public/img/ajax-loader.gif"/></div>');
                    var Ejecutivo = document.getElementById('Ejecutivo').value;
                    var FechaInicio = document.getElementById('FechaInicio').value;
                    var FechaFinal = document.getElementById('FechaFinal').value;
                    // alert('');

                    var parametros = {
                        "Ejecutivo": Ejecutivo,
                        "FechaInicio": FechaInicio,
                        "FechaFinal": FechaFinal
                    }
                     
                    $.ajax({
                        type: 'get',
                        url: "{{ url('catalogo/negocios/consultar') }}",
                        data: parametros,
                        success: function(data) {
                            console.log(data);
                            $('#response').html(data);
                            
                        }
                    })
                
            })

        });
    
</script>
@endsection