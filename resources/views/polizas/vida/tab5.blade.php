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
                        <th>Saldos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $obj)
                    @if ($clientes->count() > 0)
                    <tr>
                        <td align="center"><button class="btn btn-primary" onclick="modalExtraprimados({{ $poliza_vida->Id }},'{{ $obj->Dui }}','{{ $obj->NumeroReferencia }}')" data-target="#modal_extraprimados" data-toggle="modal">
                                <i class="fa fa-edit fa-lg"></i>
                            </button></td>
                        <td>{{ $obj->Dui }}</td>
                        <td>{{ $obj->Nombre }}</td>
                        <td>{{ $obj->NumeroReferencia }}</td>
                        <td>${{ number_format($obj->SumaAsegurada, 2, '.', ',') }}</td>
                        {{-- @if($obj->linia_credito->Saldos == 1)
                        <td>${{ number_format($obj->SaldoCapital, 2, '.', ',') }} </td>
                        @elseif($obj->linia_credito->Saldos == 2)
                        @php($linea2 = $obj->SaldoCapital + $obj->Intereses)
                        <td>${{ number_format(($linea2), 2, '.', ',') }} </td>
                        @elseif($obj->linia_credito->Saldos == 3)
                        <td>${{ number_format(($obj->SaldoCapital + $obj->Intereses + $obj->InteresesCovid), 2, '.', ',') }} </td>
                        @elseif($obj->linia_credito->Saldos == 4)
                        <td>${{ number_format(($obj->SaldoCapital + $obj->Intereses + $obj->InteresesCovid + $obj->InteresesMoratorios), 2, '.', ',') }} </td>
                        @else
                        <td>${{ number_format($obj->MontoNominal, 2, '.', ',') }} </td>
                        @endif --}}
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
                        <th>Crédito</th>
                        <th>Nombre</th>
                        <th>Fecha de Otorgamiento</th>
                        <th>SA</th>
                        {{-- <th>Interes</th>
                        <th>Total</th> --}}
                        <th>Prima neta</th>
                        <th>%EP</th>
                        <th>Extra prima</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($extraprimados->count() > 0)
                    @foreach ($extraprimados as $obj)
                    <tr >
                        <td align="center"><button class="btn btn-primary" data-target="#modal-edit-extraprimados-{{ $obj->Id }}" data-toggle="modal">
                                <i class="fa fa-edit fa-lg"></i>
                            </button>
                            <a href="" data-target="#modal-delete_extraprima-{{ $obj->Id }}" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash fa-lg"></i></a>
                        </td>
                        <td>{{ $obj->NumeroReferencia }}</td>
                        <td>{{ $obj->Nombre }}</td>
                        <td>{{ $obj->FechaOtorgamiento }}</td>
                        <td>${{ number_format($obj->SumaAsegurada, 2, '.', ',') }}</td>
                        {{-- <td>${{ number_format($obj->Intereses, 2, '.', ',') }}</td> --}}
                        {{-- <td align="right">${{ number_format($obj->total, 2, '.', ',') }}</td> --}}
                        <td align="right">${{ number_format($obj->PrimaNeta, 2, '.', ',') }}</td>
                        <td>{{ $obj->PorcentajeEP }}%</td>
                        <td align="right">${{ number_format($obj->ExtraPrima, 2, '.', ',') }}</td>
                    </tr>
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete_extraprima-{{ $obj->Id }}">

                        <form method="POST" action="{{ url('polizas/vida/eliminar_extraprimado')}}/{{ $obj->Id}}">
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <input type="hidden" name="IdExtraPrima" value="{{$obj->Id}}">
                                        <h4 class="modal-title">Eliminar Registro</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Confirme si desea Eliminar el Registro</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>

                    @include('polizas.vida.modal_edit_extraprimados')
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr align="right">
                        <td colspan="7">Total</td>
                        <td>${{ number_format($total_extrapima, 2, '.', ',') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@include('polizas.vida.modal_extraprimados')

<script type="text/javascript">
    function modalExtraprimados(poliza, dui, NumeroReferencia) {
        console.log(poliza, dui);
        // Construir la URL con los parámetros
        var url = "{{ url('polizas/vida/get_extraprimado') }}" + '/' + poliza + '/' + dui;

        // Realizar la solicitud GET
        $.get(url, function(data) {
                console.log(data);
                document.getElementById('ExtraprimadosDui').value = data.Dui;
                document.getElementById('ExtraprimadosNombre').value = data.Nombre;
                document.getElementById('ExtraprimadosFechaOtorgamiento').value = data.FechaOtorgamiento;
                document.getElementById('ExtraprimadosNumeroReferencia').value = NumeroReferencia;
                document.getElementById('ExtraprimadosVidaSumaAsegurada').value = parseFloat(data.SumaAsegurada).toFixed(2);

            })
            .fail(function() {
                // Manejar errores si es necesario
                console.error("Error al realizar la solicitud GET.");
            });
    }

</script>
