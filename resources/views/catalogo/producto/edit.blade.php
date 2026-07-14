@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="x_title">
                    <h2>Modificar Producto <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('catalogo/producto') }}?idRegistro={{ $producto->Id }}"
                            class="btn btn-info fa fa-undo " style="color: white"> Atras</a>
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

                <div role="tabpanel">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}">
                            <a href="#producto" id="producto-tab" role="tab" data-toggle="tab">Producto</a>
                        </li>
                        <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}">
                            <a href="#coberturas" role="tab" id="coberturas-tab" data-toggle="tab">Coberturas</a>
                        </li>
                        <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}">
                            <a href="#datos_tecnicos" role="tab" id="datos_tecnicos-tab" data-toggle="tab">Datos tecnicos</a>
                        </li>
                        <li role="presentation" class="{{ session('tab1') == 4 ? 'active' : '' }}">
                            <a href="#certificado" role="tab" id="certificado-tab" data-toggle="tab">Certificado</a>
                        </li>
                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }}" id="producto">
                            <form method="POST" action="{{ route('producto.update', $producto->Id) }}">
                                @method('PUT')
                                @csrf
                                <div class="x_content">
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label ">Nombre del Producto</label>
                                            <input type="text" name="Nombre" value="{{ $producto->Nombre }}" class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Aseguradora</label>
                                            <select name="Aseguradora" class="form-control select2" style="width: 100%">
                                                @foreach ($aseguradoras as $obj)
                                                    <option value="{{ $obj->Id }}" {{ $producto->Aseguradora == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-sm-6">
                                            <label class="form-label">Ramo</label>
                                            <select name="NecesidadProteccion" class="form-control select2" style="width: 100%">
                                                @foreach ($ramos as $obj)
                                                    <option value="{{ $obj->Id }}" {{ $producto->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">% Comisión NR (No Declarativas)</label>
                                            <div class="input-group">
                                                <input class="form-control text-right" name="PorcentajeComisionNoDeclarativa" type="number" min="0" max="100"
                                                    step="0.0001" value="{{ old('PorcentajeComisionNoDeclarativa', $producto->PorcentajeComisionNoDeclarativa) }}" placeholder="0.0000">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                            <small class="text-muted">Tasa de comisión propia del producto.</small>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Descripcion</label>
                                            <textarea class="form-control" name="Descripcion">{{ $producto->Descripcion }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" align="center">
                                    @can('producto edit')
                                        <button class="btn btn-success" type="submit">Modificar</button>
                                    @endcan
                                    <a href="{{ url('catalogo/producto/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                                </div>
                            </form>
                        </div>

                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}" id="coberturas">
                            <div class="col-12" style="text-align: right;">
                                <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-cobertura">
                                    <i class="fa fa-plus fa-lg"></i> Nueva Cobertura
                                </button>
                            </div>
                            @if ($coberturas->count() > 0)
                                <br>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N</th>
                                            <th>Cobertura</th>
                                            <th>Tarificacion</th>
                                            <th>Descuento</th>
                                            <th>IVA</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coberturas as $obj)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $obj->Nombre }}</td>
                                                <td>{{ $obj->tarificacion->Nombre ?? '' }}</td>
                                                <td>{{ $obj->Descuento ? 'Si' : 'No' }}</td>
                                                <td>{{ $obj->Iva ? 'Si' : 'No' }}</td>
                                                <td>
                                                    <i class="fa fa-pencil fa-lg"
                                                        onclick="modal_edit_cobertura({{ $obj->Id }},'{{ $obj->Nombre }}','{{ $obj->Tarificacion }}','{{ $obj->Descuento }}','{{ $obj->Iva }}')"
                                                        data-target="#modal-edit-cobertura" data-toggle="modal"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_cobertura({{ $obj->Id }})"
                                                        data-target="#modal-delete-cobertura" data-toggle="modal"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div style="height: 120px"><br><div class="alert alert-danger">Sin datos que mostrar.</div></div>
                            @endif
                        </div>

                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}" id="datos_tecnicos">
                            <div class="col-12" style="text-align: right;">
                                <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-dato_tecnico">
                                    <i class="fa fa-plus fa-lg"></i> Nuevo Dato Tecnico
                                </button>
                            </div>
                            @if ($datos_tecnicos->count() > 0)
                                <br>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N</th>
                                            <th>Campo</th>
                                            <th>Descripcion</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos_tecnicos as $obj)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $obj->Nombre }}</td>
                                                <td>{{ $obj->Descripcion }}</td>
                                                <td>
                                                    <i class="fa fa-pencil fa-lg"
                                                        onclick="modal_edit_dato_tecnico({{ $obj->Id }},'{{ $obj->Nombre }}','{{ $obj->Descripcion }}')"
                                                        data-target="#modal-edit-dato_tecnico" data-toggle="modal"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_dato_tecnico({{ $obj->Id }})"
                                                        data-target="#modal-delete-dato_tecnico" data-toggle="modal"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div style="height: 120px"><br><div class="alert alert-danger">Sin datos que mostrar.</div></div>
                            @endif
                        </div>

                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 4 ? 'active in' : '' }}" id="certificado">
                            @php
                                $parentescoHeredado = $certificado_campos->first(function ($campo) {
                                    return $campo->NombreCampo === 'parentesco'
                                        && ($campo->OrigenOpciones ?? 'manual') === 'catalogo'
                                        && $campo->CatalogoOrigen === 'parentesco_beneficiario';
                                });
                            @endphp
                            <form method="POST" action="{{ url('catalogo/producto/certificado/config/' . $producto->Id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="control-label">El certificado permite dependientes</label>
                                        <select name="PermiteDependientesCertificado" class="form-control">
                                            <option value="0" {{ (int) $producto->PermiteDependientesCertificado === 0 ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ (int) $producto->PermiteDependientesCertificado === 1 ? 'selected' : '' }}>Si</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3" style="padding-top: 24px;">
                                        <button class="btn btn-success" type="submit">Guardar configuracion</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row" style="margin-top: 12px;">
                                <div class="col-sm-12" style="text-align: right;">
                                    @if ($parentescoHeredado)
                                        <button type="button" class="btn btn-success" disabled>
                                            <i class="fa fa-check fa-lg"></i> Parentescos heredados
                                        </button>
                                    @else
                                        <form method="POST" action="{{ url('catalogo/producto/certificado/heredar_parentesco/' . $producto->Id) }}" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-info">
                                                <i class="fa fa-sitemap fa-lg"></i> Heredar parentescos
                                            </button>
                                        </form>
                                    @endif
                                    <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-certificado-campo">
                                        <i class="fa fa-plus fa-lg"></i> Nuevo Campo de Certificado
                                    </button>
                                </div>
                            </div>
                            <hr>

                            @if ($certificado_campos->count() > 0)
                                <br>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Orden</th>
                                            <th>Etiqueta</th>
                                            <th>Nombre campo</th>
                                            <th>Tipo</th>
                                            <th>Validacion</th>
                                            <th>Req.</th>
                                            <th>Reporte</th>
                                            <th>Opciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($certificado_campos as $obj)
                                            @php
                                                $opciones = [];
                                                if ($obj->OpcionesJson) {
                                                    $tmp = json_decode($obj->OpcionesJson, true);
                                                    $opciones = is_array($tmp) ? $tmp : [];
                                                }
                                                $esCatalogo = ($obj->OrigenOpciones ?? 'manual') === 'catalogo';
                                            @endphp
                                            <tr>
                                                <td>{{ $obj->Orden }}</td>
                                                <td>{{ $obj->Etiqueta }}</td>
                                                <td>{{ $obj->NombreCampo }}</td>
                                                <td>{{ $obj->TipoCampo }}</td>
                                                <td>{{ $obj->ValidacionCampo ?? 'ninguna' }}</td>
                                                <td>{{ $obj->Requerido ? 'Si' : 'No' }}</td>
                                                <td>{{ $obj->MostrarEnReporte ? 'Si' : 'No' }}</td>
                                                <td>
                                                    @if ($esCatalogo)
                                                        <span class="label label-success">Heredado desde Parentescos</span>
                                                        <br>
                                                        <small class="text-muted">Catalogo: {{ $obj->CatalogoOrigen }}</small>
                                                    @else
                                                        {{ implode(', ', $opciones) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="fa fa-pencil fa-lg"
                                                        onclick='modal_edit_certificado_campo(@json($obj->Id), @json($obj->Etiqueta), @json($obj->NombreCampo), @json($obj->TipoCampo), @json($obj->ValidacionCampo ?? "ninguna"), @json((string)$obj->Requerido), @json((string)($obj->MostrarEnReporte ?? 0)), @json((string)$obj->Orden), @json($obj->Placeholder ?? ""), @json($obj->Ayuda ?? ""), @json(implode("\n", $opciones)), @json($obj->OrigenOpciones ?? "manual"), @json($obj->CatalogoOrigen ?? ""))'
                                                        data-target="#modal-edit-certificado-campo" data-toggle="modal"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_certificado_campo({{ $obj->Id }})"
                                                        data-target="#modal-delete-certificado-campo" data-toggle="modal"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div style="height: 120px"><br><div class="alert alert-danger">Sin campos de certificado configurados.</div></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-modal-nuevo-dato_tecnico" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/add_dato_tecnico') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Nuevo dato tecnico</h4></div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{ $producto->Id }}" class="form-control">
                            <div class="form-group">Nombre<input type="text" name="Nombre" class="form-control" required></div>
                            <div class="form-group">Descripcion<textarea class="form-control" name="Descripcion"></textarea></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade modal-edit-dato_tecnico" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-dato_tecnico">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/edit_dato_tecnico') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Editar dato tecnico</h4>
                            <input type="hidden" name="Id" id="ModalDatoTecnicoId">
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{ $producto->Id }}" class="form-control">
                            <div class="form-group">Nombre<input type="text" name="Nombre" id="ModalDatoTecnicoNombre" class="form-control" required></div>
                            <div class="form-group">Descripcion<textarea class="form-control" name="Descripcion" id="ModalDatoTecnicoDescripcion"></textarea></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-delete-dato_tecnico" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ url('catalogo/producto/delete_dato_tecnico') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Eliminar dato tecnico</h4></div>
                        <div class="modal-body"><input type="hidden" name="Id" id="IdDatoTecnico"><p>Confirme si desea eliminar.</p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade bs-modal-nuevo-cobertura" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/add_cobertura') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Nueva cobertura</h4></div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{ $producto->Id }}">
                            <div class="form-group">Nombre<input type="text" name="Nombre" class="form-control" required></div>
                            <div class="form-group">
                                Tarificacion
                                <select name="Tarificacion" class="form-control" required>
                                    @foreach ($tarificaciones as $tarificacion)
                                        <option value="{{ $tarificacion->Id }}">{{ $tarificacion->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">Descuento
                                <select name="Descuento" class="form-control" required>
                                    <option value="0">No</option><option value="1">Si</option>
                                </select>
                            </div>
                            <div class="form-group">IVA
                                <select name="Iva" class="form-control" required>
                                    <option value="0">No</option><option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-edit-cobertura" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/edit_cobertura') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Editar cobertura</h4>
                            <input type="hidden" name="Id" id="ModalCoberturaId">
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{ $producto->Id }}">
                            <div class="form-group">Nombre<input type="text" name="Nombre" id="ModalCoberturaNombre" class="form-control" required></div>
                            <div class="form-group">Tarificacion
                                <select name="Tarificacion" id="ModalCoberturaTarificacion" class="form-control" required>
                                    @foreach ($tarificaciones as $tarificacion)
                                        <option value="{{ $tarificacion->Id }}">{{ $tarificacion->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">Descuento
                                <select name="Descuento" id="ModalCoberturaDescuento" class="form-control" required>
                                    <option value="0">No</option><option value="1">Si</option>
                                </select>
                            </div>
                            <div class="form-group">IVA
                                <select name="Iva" id="ModalCoberturaIva" class="form-control" required>
                                    <option value="0">No</option><option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-delete-cobertura" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ url('catalogo/producto/delete_cobertura') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Eliminar cobertura</h4></div>
                        <div class="modal-body"><input type="hidden" name="Id" id="Idcobertura"><p>Confirme si desea eliminar.</p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade bs-modal-nuevo-certificado-campo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/certificado/add_campo') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Nuevo campo de certificado</h4></div>
                        <div class="modal-body">
                            <input type="hidden" name="Producto" value="{{ $producto->Id }}">
                            <input type="hidden" name="OrigenOpciones" value="manual">
                            <input type="hidden" name="CatalogoOrigen" value="">
                            <div class="row">
                                <div class="col-sm-6"><label>Etiqueta</label><input type="text" name="Etiqueta" class="form-control" required></div>
                                <div class="col-sm-6"><label>Nombre campo (clave)</label><input type="text" name="NombreCampo" class="form-control" required></div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-4"><label>Tipo</label>
                                    <select name="TipoCampo" class="form-control js-cert-tipo-campo" data-opciones-target="#opciones-certificado-nuevo">
                                        <option value="text">text</option><option value="number">number</option><option value="date">date</option>
                                        <option value="select">select</option><option value="textarea">textarea</option><option value="email">email</option>
                                    </select>
                                </div>
                                <div class="col-sm-4"><label>Validacion del valor</label>
                                    <select name="ValidacionCampo" class="form-control">
                                        <option value="ninguna">Sin validacion especial</option>
                                        <option value="dui">DUI 00000000-0</option>
                                        <option value="solo_numeros">Solo numeros</option>
                                        <option value="solo_numeros_letras">Solo numeros y letras</option>
                                        <option value="solo_texto">Solo letras y caracteres comunes</option>
                                        <option value="correo">Correo electronico</option>
                                    </select>
                                </div>
                                <div class="col-sm-4"><label>Requerido</label>
                                    <select name="Requerido" class="form-control"><option value="1">Si</option><option value="0">No</option></select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-4"><label>Mostrar en reporte</label>
                                    <select name="MostrarEnReporte" class="form-control"><option value="0">No</option><option value="1">Si</option></select>
                                </div>
                                <div class="col-sm-4"><label>Orden</label><input type="number" name="Orden" min="1" class="form-control" value="1"></div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-6"><label>Placeholder</label><input type="text" name="Placeholder" class="form-control"></div>
                                <div class="col-sm-6"><label>Ayuda</label><input type="text" name="Ayuda" class="form-control"></div>
                            </div>
                            <div class="form-group js-cert-opciones" id="opciones-certificado-nuevo" style="margin-top:10px; display:none;">
                                <label>Opciones del select <span class="text-danger">*</span></label>
                                <textarea name="OpcionesTexto" class="form-control js-cert-opciones-texto" rows="4"
                                    placeholder="Ingrese una opción por línea&#10;Ejemplo:&#10;Masculino&#10;Femenino"></textarea>
                                <small class="text-muted">Estas opciones serán las que verá el usuario al llenar el certificado.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-edit-certificado-campo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/producto/certificado/edit_campo') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Editar campo de certificado</h4></div>
                        <div class="modal-body">
                            <input type="hidden" name="Id" id="CertCampoId">
                            <input type="hidden" name="OrigenOpciones" id="CertCampoOrigenOpciones" value="manual">
                            <input type="hidden" name="CatalogoOrigen" id="CertCampoCatalogoOrigen" value="">
                            <div class="row">
                                <div class="col-sm-6"><label>Etiqueta</label><input type="text" name="Etiqueta" id="CertCampoEtiqueta" class="form-control" required></div>
                                <div class="col-sm-6"><label>Nombre campo (clave)</label><input type="text" name="NombreCampo" id="CertCampoNombreCampo" class="form-control" required></div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-4"><label>Tipo</label>
                                    <select name="TipoCampo" id="CertCampoTipoCampo" class="form-control js-cert-tipo-campo" data-opciones-target="#opciones-certificado-edit">
                                        <option value="text">text</option><option value="number">number</option><option value="date">date</option>
                                        <option value="select">select</option><option value="textarea">textarea</option><option value="email">email</option>
                                    </select>
                                </div>
                                <div class="col-sm-4"><label>Validacion del valor</label>
                                    <select name="ValidacionCampo" id="CertCampoValidacionCampo" class="form-control">
                                        <option value="ninguna">Sin validacion especial</option>
                                        <option value="dui">DUI 00000000-0</option>
                                        <option value="solo_numeros">Solo numeros</option>
                                        <option value="solo_numeros_letras">Solo numeros y letras</option>
                                        <option value="solo_texto">Solo letras y caracteres comunes</option>
                                        <option value="correo">Correo electronico</option>
                                    </select>
                                </div>
                                <div class="col-sm-4"><label>Requerido</label>
                                    <select name="Requerido" id="CertCampoRequerido" class="form-control"><option value="1">Si</option><option value="0">No</option></select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-4"><label>Mostrar en reporte</label>
                                    <select name="MostrarEnReporte" id="CertCampoMostrarEnReporte" class="form-control"><option value="0">No</option><option value="1">Si</option></select>
                                </div>
                                <div class="col-sm-4"><label>Orden</label><input type="number" name="Orden" id="CertCampoOrden" min="1" class="form-control"></div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-6"><label>Placeholder</label><input type="text" name="Placeholder" id="CertCampoPlaceholder" class="form-control"></div>
                                <div class="col-sm-6"><label>Ayuda</label><input type="text" name="Ayuda" id="CertCampoAyuda" class="form-control"></div>
                            </div>
                            <div class="form-group js-cert-opciones" id="opciones-certificado-edit" style="margin-top:10px; display:none;">
                                <label>Opciones del select <span class="text-danger">*</span></label>
                                <textarea name="OpcionesTexto" id="CertCampoOpcionesTexto" class="form-control js-cert-opciones-texto" rows="4"
                                    placeholder="Ingrese una opción por línea&#10;Ejemplo:&#10;Masculino&#10;Femenino"></textarea>
                                <small class="text-muted">Estas opciones serán las que verá el usuario al llenar el certificado.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-delete-certificado-campo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ url('catalogo/producto/certificado/delete_campo') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Eliminar campo de certificado</h4></div>
                        <div class="modal-body"><input type="hidden" name="Id" id="CertCampoDeleteId"><p>Confirme si desea eliminar.</p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            displayOption("ul-catalogo", "li-producto");
        });

        function modal_edit_dato_tecnico(Id, Nombre, Descripcion) {
            $('#ModalDatoTecnicoId').val(Id);
            $('#ModalDatoTecnicoNombre').val(Nombre);
            $('#ModalDatoTecnicoDescripcion').val(Descripcion);
        }
        function modal_delete_dato_tecnico(id) { $('#IdDatoTecnico').val(id); }

        function modal_edit_cobertura(Id, Nombre, Tarificacion, Descuento, Iva) {
            $('#ModalCoberturaId').val(Id);
            $('#ModalCoberturaNombre').val(Nombre);
            $('#ModalCoberturaTarificacion').val(Tarificacion);
            $('#ModalCoberturaDescuento').val(Descuento);
            $('#ModalCoberturaIva').val(Iva);
        }
        function modal_delete_cobertura(id) { $('#Idcobertura').val(id); }

        function modal_edit_certificado_campo(id, etiqueta, nombreCampo, tipoCampo, validacionCampo, requerido, mostrarEnReporte, orden, placeholder, ayuda, opcionesTexto, origenOpciones, catalogoOrigen) {
            $('#CertCampoId').val(id);
            $('#CertCampoEtiqueta').val(etiqueta);
            $('#CertCampoNombreCampo').val(nombreCampo);
            $('#CertCampoTipoCampo').val(tipoCampo);
            $('#CertCampoValidacionCampo').val(validacionCampo || 'ninguna');
            $('#CertCampoRequerido').val(requerido);
            $('#CertCampoMostrarEnReporte').val(mostrarEnReporte);
            $('#CertCampoOrden').val(orden);
            $('#CertCampoPlaceholder').val(placeholder);
            $('#CertCampoAyuda').val(ayuda);
            $('#CertCampoOpcionesTexto').val(opcionesTexto);
            $('#CertCampoOrigenOpciones').val(origenOpciones || 'manual');
            $('#CertCampoCatalogoOrigen').val(catalogoOrigen || '');
            toggleOpcionesCertificado($('#CertCampoTipoCampo'));
        }
        function modal_delete_certificado_campo(id) { $('#CertCampoDeleteId').val(id); }

        function toggleOpcionesCertificado($select) {
            var target = $select.data('opciones-target');
            var $wrapper = $(target);
            var $textarea = $wrapper.find('.js-cert-opciones-texto');

            var origen = $select.attr('id') === 'CertCampoTipoCampo'
                ? ($('#CertCampoOrigenOpciones').val() || 'manual')
                : 'manual';

            if ($select.val() === 'select' && origen === 'manual') {
                $wrapper.show();
                $textarea.prop('required', true);
            } else {
                $wrapper.hide();
                $textarea.prop('required', false).val('');
            }
        }

        $(document).on('change', '.js-cert-tipo-campo', function() {
            toggleOpcionesCertificado($(this));
        });

        $(function() {
            $('.js-cert-tipo-campo').each(function() {
                toggleOpcionesCertificado($(this));
            });
        });
    </script>
    @include('sweetalert::alert')
@endsection
