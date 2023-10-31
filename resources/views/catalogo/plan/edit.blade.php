@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Modificar Plan <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{url('catalogo/plan')}}" class="btn btn-info fa fa-undo " style="color: white"> Atrás</a>
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

            <form method="POST" action="{{ route('plan.update', $plan->Id) }}">
                @method('PUT')
                @csrf

                <div class="x_content">
                    <br />
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label ">Nombre del Plan</label>
                            <input type="text" name="Nombre" id="Nombre" value="{{$plan->Nombre}}" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label for="Producto" class="form-label">Producto</label>
                            <select disabled id="Producto" name="Producto" class="form-control select2" style="width: 100%">
                                @foreach ($productos as $obj)
                                <option value="{{ $obj->Id }}" {{ $plan->Producto == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="x_title">
                    <h2>Coberturas <small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Cobertura</th>
                            <th>Suma Asegurada</th>
                            <th>Tasa</th>
                            <th>Prima</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plan->coberturas as $obj)
                        <tr>
                            <td>{{ $obj->Nombre }}</td>
                            <td>${{  number_format($obj->pivot->SumaAsegurada, 2, '.', ',')}}</td>
                            <td>{{  number_format($obj->pivot->Tasa, 3, '.', ',')}}</td>
                            <td>${{  number_format($obj->pivot->Prima, 2, '.', ',')}}</td>
                            <td>
                                <i class="fa fa-pencil fa-lg" onclick="modal_edit_cobertura({{ $obj->Id }},'{{ $obj->Nombre }}','{{ $obj->pivot->SumaAsegurada}}','{{ $obj->pivot->Tasa}}','{{ $obj->pivot->Prima }}')" data-target="#modal-edit-cobertura" data-toggle="modal"></i>

                            </td>
                        </tr>
                        @endforeach
                        @foreach ($coberturasDisponibles as $obj)
                        <tr>
                            <td>{{ $obj->Nombre }}</td>
                            <td>${{number_format(0, 2, '.', ',')}}</td>
                            <td>{{number_format(0, 3, '.', ',')}}</td>
                            <td>${{number_format(0, 2, '.', ',')}}</td>
                            <td>
                                <i class="fa fa-pencil fa-lg" onclick="modal_edit_cobertura({{ $obj->Id }},'{{ $obj->Nombre }}','0','0','0')" data-target="#modal-edit-cobertura" data-toggle="modal"></i>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Modificar</button>
                    <a href="{{ url('catalogo/plan/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>

        </div>

    </div>


        {{-- editar Cobertura --}}
        <div class="col-12">
            <div class="modal fade modal-edit-cobertura" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-cobertura">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/plan/edit_cobertura_detalle') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Editar Cobertura</h4>
                                <input type="hidden" name="Plan" value="{{$plan->Id}}" class="form-control">
                                <input type="hidden" name="Cobertura" id="ModalCoberturaId" class="form-control" required>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                       Cobertura
                                        <input disabled type="text" name="Nombre" id="ModalCoberturaNombre" class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                        Suma Asegurada
                                        <input type="number" name="SumaAsegurada" id="ModalCoberturaSumaAsegurada" step="0.01" class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                       Tasa
                                        <input type="number" name="Tasa" id="ModalCoberturaTasa" step="0.01" class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                        Prima
                                        <input type="number" name="Prima" id="ModalCoberturaPrima" step="0.01" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <br>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>




<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
    });

    function modal_edit_cobertura(Id,Nombre,SumaAsegurada, Tasa, Prima) {
        $('#ModalCoberturaId').val(Id);
        $('#ModalCoberturaNombre').val(Nombre);
        $('#ModalCoberturaSumaAsegurada').val(Number(SumaAsegurada).toFixed(2));
        $('#ModalCoberturaTasa').val(Number(Tasa).toFixed(3));
        $('#ModalCoberturaPrima').val(Number(Prima).toFixed(2));
    }


</script>
</div>
@include('sweetalert::alert')

@endsection
