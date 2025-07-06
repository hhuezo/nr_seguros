@extends ('welcome')
@section('contenido')
<div class="x_panel">
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Nueva póliza de seguro</h4>
        </div>
        <!-- <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('poliza/seguro/create') }}"><button class="btn btn-info float-right">
                    <i class="fa fa-plus"></i> Nuevo</button></a>
        </div> -->
        <div class="clearfix"></div>
    </div>
    <form action="{{url('poliza/seguro')}}" method="post">
        @csrf
        <div class="row">

            <div class="form-horizontal">
                <div class="col-sm-6" style="background-color: lightgrey; padding: 12px; border-radius: 15px;">
                    <label class="control-label"># Oferta</label>
                    <select name="Oferta" id="Oferta" class="form-control" onchange="select_oferta(this)">
                        <option value="">Seleccionar</option>
                        @foreach($ofertas as $off)
                        <option value="{{$off->Id}}">{{$off->clientes->Nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Forma de Pago</label>
                    <select name="FormaPago" id="FormaPago" class="form-control">
                        @foreach($forma_pago as $pago)
                        <option value="{{ $loop->index }}">{{ $pago == '' ? 'Seleccione...' : $pago }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-horizontal">
                <div class="col-sm-6">
                    <label class="control-label">Número Póliza</label>
                    <input type="text" name="NumeroPoliza" class="form-control">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Estado de Poliza</label>
                    <select name="EstadoPoliza" id="EstadoPoliza" class="form-control">
                        @foreach($estado_poliza as $estado)
                        <option value="{{ $estado->Id }}">{{ $estado->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Productos -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label">Productos *</label>
                    <select name="Productos" id="Productos" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($productos as $obj)
                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Planes -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label">Planes *</label>
                    <select name="Planes" id="Planes" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($planes as $obj)
                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6">
                    <label class="control-label">ID Cliente</label>
                    <input type="text" name="IdCliente" id="IdCliente" class="form-control" id="IdCliente">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Nombre Cliente</label>
                    <select name="Cliente" id="Cliente" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($clientes as $obj)
                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-sm-6">
                    <label class="control-label">Vigencia Desde *</label>
                    <input class="form-control" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}">
                </div>

                <div class="col-sm-3">
                    <label class="control-label">Vigencia Hasta *</label>
                    <input class="form-control" name="VigenciaHasta" type="date" value="{{ old('VigenciaHasta') }}">
                </div>

                <div class="col-sm-3">
                    <label class="control-label">Días</label>
                    <input type="number" name="DiasVigencia" class="form-control" value="365">
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Motivo Cancelación</label>
                    <input type="text" name="MotivoCancelacion" class="form-control" value="365">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Fecha Cancelación</label>
                    <input type="date" name="FechaCancelacion" class="form-control">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Id Cancelación <small style="color: red;">(revisar)</small></label>
                    <input type="text" name="MotivoCancelacion" class="form-control" value="365">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Fecha Envío Anexo</label>
                    <input type="date" name="FechaEnvioAnexo" class="form-control">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Reservacion Ren. <small style="color: red;">(revisar)</small></label>
                    <input type="text" name="MotivoCancelacion" class="form-control" value="365">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Solicitud Renovación</label>
                    <input type="date" name="SolicitudRenovacion" class="form-control">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Origen Poliza <small style="color: red;">(revisar)</small></label>
                    <select name="Cliente" id="Cliente" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($clientes as $obj)
                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Fecha Vinculación</label>
                    <input type="date" name="FechaVinculacion" class="form-control">
                </div>

            </div>
        </div>
        <div class="row">
            <div class="form-horizontal">

                <div class="col-sm-6">
                    <label class="control-label">Departamento <small style="color: red;">(revisar)</small></label>
                    <select name="Cliente" id="Cliente" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($clientes as $obj)
                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Fecha Recepción</label>
                    <input type="date" name="FechaRecepcion" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-horizontal">
                <div class="col-sm-6">
                    <label class="control-label">Sustituida por póliza <small style="color: red;">(revisar)</small></label>
                    <input type="date" name="FechaVinculacion" class="form-control">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Observación Siniestro</label>
                    <input type="text" name="ObservacionSiniestro" class="form-control">
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Ejecutivo Cia</label>
                    <select name="EjecutivoCia" id="EjecutivoCia" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($tipo_cartera_nr as $obj)
                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Grupo Cliente</label>
                    <input type="text" name="GrupoCliente" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-horizontal">

                <div class="col-sm-6">
                    <label class="control-label">Deducible</label>
                    <input type="text" name="Deducible" class="form-control" value="Deducible por Vigencia">
                </div>
            </div>
        </div>
        <br>
        <div class="row" style="display: flex; justify-content: space-around;">
            <div class="form-horizontal">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{url('poliza/seguro')}}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
    function select_oferta(id) {
        // lógica para selección de oferta
        $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
        var parametros = {
            "Oferta": document.getElementById('Oferta').value
        };
        $.ajax({
            type: "get",
            //ruta para obtener el horario del doctor
            url: "{{ url('get_oferta') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                document.getElementById('FormaPago').value = data.oferta.forma_pago;
                document.getElementById('Productos').value = data.oferta.productos;
                document.getElementById('Planes').value = data.oferta.planes;
                document.getElementById('IdCliente').value = data.oferta.dui_cliente;
                // document.getElementById('Cliente').value = data.oferta.id_cliente;
                $('#Cliente').val(data.oferta.id_cliente).trigger('change'); // <- Aquí el cambio
            }
        });
    }
</script>
@endsection