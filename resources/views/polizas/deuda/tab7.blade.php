<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


        <div class="x_title">
            <h4>&nbsp;&nbsp; Listado de Clientes<small></small>
            </h4>
            <div class="clearfix"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table width="100%" class="table table-striped" id="clientes">
                <thead>
                    <tr>
                        <th>Extra Prima</th>
                        <th>Dui</th>
                        <th>Nombre</th>
                        <th>Numero Referencia</th>
                        <th>Saldo Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $obj)
                        @if ($clientes->count() > 0)
                            <tr>
                                <td align="center"><button class="btn btn-primary"
                                        onclick="modalExtraprimados({{ $deuda->Id }},'{{ $obj->Dui }}')"
                                        data-target="#modal_extraprimados" data-toggle="modal">
                                        <i class="fa fa-edit fa-lg"></i>
                                    </button></td>
                                <td>{{ $obj->Dui }}</td>
                                <td>{{ $obj->Nombre }}</td>
                                <td>{{ $obj->NumeroReferencia }}</td>
                                <td>{{ $obj->SaldoTotal }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<br><br>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="x_title">
            <h4>&nbsp;&nbsp; Listado de Extra Primados<small></small>
            </h4>
            <div class="clearfix"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table width="100%" class="table table-striped" id="clientes-extra">
                <thead>
                    <tr>
                        <th>Extra Prima</th>
                        <th>Dui</th>
                        <th>Nombre</th>
                        <th>Numero Referencia</th>
                        <th>Saldo Total</th>
                        <th>Tarifa</th>
                        <th>Porcentaje EP</th>
                        <th>Pago EP</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($extraprimados->count() > 0)
                        @foreach ($extraprimados as $obj)
                            <tr>
                                <td align="center"><button class="btn btn-primary"
                                    data-target="#modal-edit-extraprimados-{{ $obj->Id }}" data-toggle="modal">
                                    <i class="fa fa-edit fa-lg"></i>
                                </button></td>
                                <td>{{ $obj->Dui }}</td>
                                <td>{{ $obj->PrimerNombre }} {{ $obj->PrimerApellido }}</td>
                                <td>{{ $obj->NumeroReferencia }}</td>
                                <td>{{ $obj->SaldoTotal }}</td>
                                <td>{{ $obj->Tarifa }}</td>
                                <td>{{ $obj->PorcentajeEP }}%</td>
                                <td>{{ $obj->PagoEP }}</td>
                            </tr>
                            @include('polizas.deuda.modal_edit_extraprimados')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('polizas.deuda.modal_extraprimados')

<script type="text/javascript">
    function modalExtraprimados(poliza, dui) {
        console.log(poliza, dui);
        // Construir la URL con los parámetros
        var url = "{{ url('polizas/deuda/get_extraprimado') }}" + '/' + poliza + '/' + dui;

        // Realizar la solicitud GET
        $.get(url, function(data) {
                console.log(data);
                document.getElementById('ExtraprimadosDui').value = data.Dui;
                document.getElementById('ExtraprimadosNombre').value = data.Nombre;
                document.getElementById('ExtraprimadosFechaOtorgamiento').value = data.FechaOtorgamiento;
                document.getElementById('ExtraprimadosNumeroReferencia').value = data.NumeroReferencia;
                if (data.hasOwnProperty('MontoOtorgamiento')) {
                    document.getElementById('ExtraprimadosMontoOtorgamiento').value = data.MontoOtorgamiento;
                }
            })
            .fail(function() {
                // Manejar errores si es necesario
                console.error("Error al realizar la solicitud GET.");
            });
    }
</script>
