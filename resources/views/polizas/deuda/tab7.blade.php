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
                        <td><input type="checkbox"></td>
                        <td>{{$obj->Dui}}</td>
                        <td>{{$obj->PrimerNombre}} {{$obj->PrimerApellido}}</td>
                        <td>{{$obj->NumeroReferencia}}</td>
                        <td>{{$obj->SaldoTotal}}</td>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($extraprimados as $obj)
                    @if ($extraprimados->count() > 0)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{$obj->Dui}}</td>
                        <td>{{$obj->PrimerNombre}} {{$obj->PrimerApellido}}</td>
                        <td>{{$obj->NumeroReferencia}}</td>
                        <td>{{$obj->SaldoTotal}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>