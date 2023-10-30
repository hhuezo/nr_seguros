@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Modificar Producto <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{url('catalogo/producto')}}" class="btn btn-info fa fa-undo " style="color: white"> Atrás</a>
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

            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#producto" id="producto-tab" role="tab" data-toggle="tab" aria-expanded="true">Producto</a>

                    </li>
                    <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#coberturas" role="tab" id="coberturas-tab" data-toggle="tab" aria-expanded="false">Coberturas</a>
                    </li>

                    <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}"><a href="#datos_tecnicos" role="tab" id="datos_tecnicos-tab" data-toggle="tab" aria-expanded="false">Datos técnicos</a>
                    </li>

                </ul>


                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} " id="producto" aria-labelledby="home-tab">

                        <form method="POST" action="{{ route('producto.update', $producto->Id) }}">
                            @method('PUT')
                            @csrf

                            <div class="x_content">
                                <br />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="control-label ">Nombre del Producto</label>
                                        <input type="text" name="Nombre" id="Nombre" value="{{$producto->Nombre}}" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="Aseguradora" class="form-label">Aseguradora</label>
                                        <select id="Aseguradora" name="Aseguradora" class="form-control select2" style="width: 100%">
                                            @foreach ($aseguradoras as $obj)
                                            <option value="{{ $obj->Id }}" {{ $producto->Aseguradora == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-sm-6">
                                        <label for="NecesidadProteccion" class="form-label">Ramo</label>
                                        <select id="NecesidadProteccion" name="NecesidadProteccion" class="form-control select2" style="width: 100%">
                                            @foreach ($ramos as $obj)
                                            <option value="{{ $obj->Id }}" {{ $producto->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" name="Descripcion">{{ $producto->Descripcion }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" align="center">
                                <button class="btn btn-success" type="submit">Modificar</button>
                                <a href="{{ url('catalogo/producto/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                            </div>

                        </form>

                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}" id="coberturas" aria-labelledby="home-tab">
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-cobertura"><i class="fa fa-plus fa-lg"></i>
                                Nueva Cobertura</button>
                        </div>
                        @if ($coberturas->count() > 0)
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Cobertura</th>
                                    <th>Tarificación</th>
                                    <th>Descuento</th>
                                    <th>IVA</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coberturas as $obj)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $obj->Nombre }}</td>
                                    @if ($obj->Tarificacion)
                                    <td>Millar</td>
                                    @else
                                    <td>Porcentual</td>
                                    @endif
                                    @if ($obj->Descuento)
                                    <td>Si</td>
                                    @else
                                    <td>No</td>
                                    @endif
                                    @if ($obj->Iva)
                                    <td>Si</td>
                                    @else
                                    <td>No</td>
                                    @endif
                                    <td>
                                        <i class="fa fa-pencil fa-lg" onclick="modal_edit_cobertura({{ $obj->Id }},'{{ $obj->Nombre }}','{{ $obj->Tarificacion }}','{{ $obj->Descuento }}','{{ $obj->Iva }}')" data-target="#modal-edit-cobertura" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_cobertura({{ $obj->Id }})" data-target="#modal-delete-cobertura" data-toggle="modal"></i>


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}" id="datos_tecnicos" aria-labelledby="home-tab">
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-dato_tecnico"><i class="fa fa-plus fa-lg"></i>
                                Nuevo Dato Técnico</button>
                        </div>
                        @if ($datos_tecnicos->count() > 0)
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Campo</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datos_tecnicos as $obj)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $obj->Nombre }}</td>
                                    <td>{{ $obj->Descripcion }}</td>
                                    <td>
                                        <i class="fa fa-pencil fa-lg" onclick="modal_edit_dato_tecnico({{ $obj->Id }},'{{ $obj->Nombre }}','{{ $obj->Descripcion }}')" data-target="#modal-edit-dato_tecnico" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_dato_tecnico({{ $obj->Id }})" data-target="#modal-delete-dato_tecnico" data-toggle="modal"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>



            </div>
        </div>

    </div>

     {{-- modales datos tecnicos --}}
        {{-- ingresar dato tecnico --}}
     <div class="col-12">
        <div class="modal fade bs-modal-nuevo-dato_tecnico" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/add_dato_tecnico') }}">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Nuevo dato técnico</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{$producto->Id}}" class="form-control">
                            <div class="form-group">
                                <div class="row">
                                    Nombre
                                    <input type="text" name="Nombre" class="form-control" required>
                                </div>
                                <div class="row">
                                    Descripción
                                    <textarea class="form-control" name="Descripcion"></textarea>
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
        {{-- editar dato tecnico --}}
        <div class="col-12">
            <div class="modal fade modal-edit-dato_tecnico" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-dato_tecnico">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/producto/edit_dato_tecnico') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Editar dato técnico</h4>
                                <input type="hidden" name="Id" id="ModalDatoTecnicoId" class="form-control" required>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Producto" value="{{$producto->Id}}" class="form-control">
                                <div class="form-group">
                                    <div class="row">
                                        Nombre
                                        <input type="text" name="Nombre" id="ModalDatoTecnicoNombre" class="form-control" required>
                                    </div>
                                    <div class="row">
                                        Descripción
                                        <textarea class="form-control" name="Descripcion"  id="ModalDatoTecnicoDescripcion" ></textarea>
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
    {{-- eliminar dato tecnico --}}

        <div class="col-12">
            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-dato_tecnico">

                <form method="POST" action="{{ url('catalogo/producto/delete_dato_tecnico') }}">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <input type="hidden" name="Id" id="IdDatoTecnico">
                                <h4 class="modal-title">Eliminar dato técnico</h4>
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
        </div>
    </div>

    {{-- modales cobertura --}}
    <div class="col-12">
        <div class="modal fade bs-modal-nuevo-cobertura" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/add_cobertura') }}">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Nueva cobertura</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{$producto->Id}}" class="form-control">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Nombre
                                    <input type="text" name="Nombre" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Tarificación
                                    <select name="Tarificacion" id="Tarificacion" class="form-control" required>
                                        <option value="0" {{ old('Tarificacion') == "0" ? 'selected' : '' }}>Porcentual</option>
                                        <option value="1" {{ old('Tarificacion') == "1" ? 'selected' : '' }}>Millar</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Descuento
                                    <select name="Descuento" id="Descuento" class="form-control" required>
                                        <option value="0" {{ old('Descuento') == "0" ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('Descuento') == "1" ? 'selected' : '' }}>Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    IVA
                                    <select name="Iva" id="Iva" class="form-control" required>
                                        <option value="0" {{ old('Iva') == "0" ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('Iva') == "1" ? 'selected' : '' }}>Si</option>
                                    </select>
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

    <div class="col-12">
        <div class="modal fade modal-edit-cobertura" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-cobertura">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/edit_cobertura') }}">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Editar cobertura</h4>
                            <input type="hidden" name="Id" id="ModalCoberturaId" class="form-control" required>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{$producto->Id}}" class="form-control">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Nombre
                                    <input type="text" name="Nombre" id="ModalCoberturaNombre" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Tarificación
                                    <select name="Tarificacion" id="ModalCoberturaTarificacion" class="form-control" required>
                                        <option value="0" {{ old('Tarificacion') == "0" ? 'selected' : '' }}>Porcentual</option>
                                        <option value="1" {{ old('Tarificacion') == "1" ? 'selected' : '' }}>Millar</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Descuento
                                    <select name="Descuento" id="ModalCoberturaDescuento" class="form-control" required>
                                        <option value="0" {{ old('Descuento') == "0" ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('Descuento') == "1" ? 'selected' : '' }}>Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    IVA
                                    <select name="Iva" id="ModalCoberturaIva" class="form-control" required>
                                        <option value="0" {{ old('Iva') == "0" ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('Iva') == "1" ? 'selected' : '' }}>Si</option>
                                    </select>
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

    <div class="col-12">
        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-cobertura">

            <form method="POST" action="{{ url('catalogo/producto/delete_cobertura') }}">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <input type="hidden" name="Id" id="Idcobertura">
                            <h4 class="modal-title">Eliminar Cobertura</h4>
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
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
    });

    function modal_edit_dato_tecnico(Id, Nombre, Descripcion) {
        $('#ModalDatoTecnicoId').val(Id);
        $('#ModalDatoTecnicoNombre').val(Nombre);
        $('#ModalDatoTecnicoDescripcion').val(Descripcion);
    }

    function modal_delete_dato_tecnico(id) {
        $('#IdDatoTecnico').val(id);
        //$('#modal_borrar_ato_tecnico').modal('show');
    }

    function modal_edit_cobertura(Id, Nombre, Tarificacion, Descuento,Iva) {
        $('#ModalCoberturaId').val(Id);
        $('#ModalCoberturaNombre').val(Nombre);
        $('#ModalCoberturaTarificacion').val(Tarificacion);
        $('#ModalCoberturaDescuento').val(Descuento);
        $('#ModalCoberturaIva').val(Iva);
    }

    function modal_delete_cobertura(id) {
        $('#Idcobertura').val(id);
        $('#modal_borrar_documento').modal('show');
    }

</script>
</div>
@include('sweetalert::alert')

@endsection
