@extends("welcome")
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Control de Primas General <small></small></h2>
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

                <form method="POST">
                    @csrf
                    <div class="form-horizontal">
                        <br>


                        <div class="form-group row">
                            <label class="control-label col-md-4" align="right">Fecha Inicio</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" id="FechaInicioDetalle"  name="FechaInicioDetalle" value="<?php echo date("Y-m-d"); ?>" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-4" align="right">Fecha Final</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" id="FechaFinalDetalle"  name="FechaFinalDetalle" value="<?php echo date("Y-m-d"); ?>" >
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button id="btnPrimaGeneral" type="button" class="btn btn-success">Aceptar</button>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <table id="tablaPrimas"  class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Asegurado</th>
                                    <th>Tipo de Poliza</th>
                                    <th>Número de Póliza</th>
                                    <th>Aseguradora</th>
                                    <th>Vigencia Desde</th>
                                    <th>Vigencia Hasta</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Final</th>
                                    <th>Descuento</th>
                                    <th>A Pagar</th>
                                    <th>Impresion de Recibo</th>
                                    <th>Envio de Cartera</th>
                                    <th>Envio de Pago</th>
                                    <th>Pago Aplicado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody id="primaGeneralTBody">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@include('sweetalert::alert')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
    let fechas = obtenerPrimerYUltimoDiaDelMes();
        $("#FechaInicioDetalle").val(fechas.primerDia);
        $("#FechaFinalDetalle").val(fechas.primerDiaSiguienteMes);
        generarPrimaGeneral();

    $("#tablaPrimas").hide();
    $("#btnPrimaGeneral").on("click", function() {
        generarPrimaGeneral();
    });

});
  // Función para obtener el primer día y el último día del mes actual
  function obtenerPrimerYUltimoDiaDelMes() {
            let fechaActual = new Date();
            let primerDiaDelMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
            let ultimoDiaDelMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 0);
            let primerDiaSiguienteMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 1);
            return {
                primerDia: primerDiaDelMes.toISOString().slice(0, 10),
                diaActual: fechaActual.toISOString().slice(0, 10),
                ultimoDia: ultimoDiaDelMes.toISOString().slice(0, 10),
                primerDiaSiguienteMes: primerDiaSiguienteMes.toISOString().slice(0, 10)
            };
        }


function generarPrimaGeneral() {
    let parametros = {
                        "FechaInicioDetalle": $('#FechaInicioDetalle').val(),
                        "FechaFinalDetalle": $('#FechaFinalDetalle').val(),
                    };
                $.ajax({
                    url: "{{ url('/home/getPrimaGeneral', '') }}",
                    type: 'GET',
                    data: parametros,
                    success: function (response) {
                        //console.log(response.datosRecibidos);
                        cargarTabla(response.datosRecibidos);
                        $("#tablaPrimas").show();

                    },
                    error: function (error) {
                        $("#tablaPrimas").hide();

                        // Las credenciales son incorrectas, muestra un mensaje de error
                        console.error(error.responseJSON.datosRecibidos);
                    Swal.fire({
                        title: 'Error!',
                        text: 'No existe ningún registro en el intervalo de fechas seleccionado',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    }
                });
}

function cargarTabla(datosRecibidos) {
     // obtener url del servidor
       let urlCompleta = window.location.href;

        /// Obtener el cuerpo de la tabla
        let tablaCuerpo = $("#primaGeneralTBody");
        tablaCuerpo.empty();

        // Recorrer los datos y agregar filas a la tabla
        $.each(datosRecibidos, function(index, obj) {

        // Crear una instancia de Intl.DateTimeFormat con la zona horaria deseada (GMT-6)
        let opcionesDeFormatoFecha = {
        //timeZone: 'America/El_Salvador', // Zona horaria GMT-6
        timeZone: 'GMT', // Zona horaria GMT
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
       /* hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false // Formato de 24 horas*/
        };

        let opcionesDeFormatoMoneda = {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        };
            //console.log(obj.VigenciaDesdePoliza);
            let IdPoliza = obj.IdPoliza || "";
            let Asegurado = obj.Asegurado || "";
            let TipoPoliza = obj.TipoPoliza || "";
            let PolizaNo = obj.PolizaNo || "";
            let Seguradora = obj.Seguradora || "";
            let VigenciaDesdePoliza = obj.VigenciaDesdePoliza ? new Date(obj.VigenciaDesdePoliza).toLocaleDateString('es-SV',opcionesDeFormatoFecha) : "";
            let VigenciaHastaPoliza = obj.VigenciaHastaPoliza ? new Date(obj.VigenciaHastaPoliza).toLocaleDateString('es-SV', opcionesDeFormatoFecha) : "";
            let FechaInicioDetalle = obj.FechaInicioDetalle ? new Date(obj.FechaInicioDetalle).toLocaleDateString('es-SV', opcionesDeFormatoFecha) : "";
            let FechaFinalDetalle =  obj.FechaFinalDetalle ?new Date(obj.FechaFinalDetalle).toLocaleDateString('es-SV', opcionesDeFormatoFecha) : "";
            let Descuento = obj.Descuento.toLocaleString('en-US',opcionesDeFormatoMoneda) || "";
            let Apagar = obj.Apagar.toLocaleString('en-US',opcionesDeFormatoMoneda) || "";
            let ImpresionRecibo = obj.ImpresionRecibo ? new Date(obj.ImpresionRecibo).toLocaleDateString('es-SV',opcionesDeFormatoFecha) : "";
            let EnvioCartera =obj.EnvioCartera ?  new Date(obj.EnvioCartera ).toLocaleDateString('es-SV',opcionesDeFormatoFecha) : "";
            let EnvioPago = obj.EnvioPago ? new Date(obj.EnvioPago).toLocaleDateString('es-SV',opcionesDeFormatoFecha) : "";
            let PagoAplicado = obj.PagoAplicado ? new Date(obj.PagoAplicado).toLocaleDateString('es-SV',opcionesDeFormatoFecha) : "";


            // Crear una nueva fila y agregar celdas
            let fila = $("<tr>");
            fila.append(`<td>${Asegurado}</td>`);
            fila.append(`<td>${TipoPoliza}</td>`);
            fila.append(`<td>${PolizaNo}</td>`);
            fila.append(`<td>${Seguradora}</td>`);
            fila.append(`<td>${VigenciaDesdePoliza}</td>`);
            fila.append(`<td>${VigenciaHastaPoliza}</td>`);
            fila.append(`<td>${FechaInicioDetalle}</td>`);
            fila.append(`<td>${FechaFinalDetalle}</td>`);
            fila.append(`<td>${Descuento}</td>`);
            fila.append(`<td>${Apagar}</td>`);
            fila.append(`<td>${ImpresionRecibo}</td>`);
            fila.append(`<td>${EnvioCartera}</td>`);
            fila.append(`<td>${EnvioPago}</td>`);
            fila.append(`<td>${PagoAplicado}</td>`);

            //botón botón editar
            let botonEditar = $(
                "<a href='"+urlCompleta+"polizas/residencia/"+IdPoliza+"/edit' class='on-default edit-row' title='Editar Poliza'><i class='fa fa-pencil fa-lg'></i></a>"
            );

            fila.append($("<td>").append(botonEditar));

            // Agregar la fila al cuerpo de la tabla
            tablaCuerpo.append(fila);
 });
}
</script>
@endsection
