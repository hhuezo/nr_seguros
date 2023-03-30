@extends ('welcome')
@section('contenido')
<script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Editar Aseguradora <small></small></h2>
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
           <form method="PATCH" action="{{route('aseguradoras.update', $aseguradora->Id)}}" >
                @csrf
                <div class="x_content">
                    <br />


                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="Nombre" class="form-control" autofocus="true" value="{{$aseguradora->Nombre}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Código</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="Codigo" class="form-control" value="{{$aseguradora->Codigo}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Teléfono</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="Telefono" class="form-control" data-inputmask="'mask': ['9999-9999']" value="{{$aseguradora->Telefono}}" >
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Contacto</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="Contacto" class="form-control" autofocus="true" value="{{$aseguradora->Contacto}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Dirección</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            
                            <textarea name="Direccion" id="Direccion" class="form-control" cols="3" rows="5">{{$aseguradora->Direccion}}</textarea>
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Página Web</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="PaginaWeb" class="form-control" >
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Fax</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="Fax" class="form-control" autofocus="true" data-inputmask="'mask': ['9999-9999']" value="{{$aseguradora->Fax}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nit</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="Nit" class="form-control" data-inputmask="'mask': ['9999-999999-999-9']" value="{{$aseguradora->Nit}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Registro Físcal</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="RegistroFiscal" class="form-control" value="{{$aseguradora->RegistroFiscal}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Abreviatura</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="Abreviatura" class="form-control" value="{{$aseguradora->Abreviatura}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Correo</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="email" name="Correo" class="form-control" value="{{$aseguradora->Correo}}">
                        </div>
                        <label class="col-sm-3 control-label">&nbsp;</label>
                    </div>

                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Modificar</button>
                    <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>
            


        </div>


    </div>
</div>
</div>
@endsection