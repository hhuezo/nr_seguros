@extends ('welcome')
@section('contenido')
<script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nueva Aseguradora <small></small></h2>
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
            <form action="{{ url('catalogo/aseguradoras') }}" method="POST" class="forms-sample">
                @csrf
                <div class="x_content">
                    <br />
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Nombre</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Nombre" value="{{old('Nombre')}}" class="form-control"  required autofocus="true">
                            </div>
                           
                        </div>
    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Código</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Codigo" value="{{old('Codigo')}}" class="form-control">
                            </div>
                          
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Teléfono</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Telefono" value="{{old('Telefono')}}" class="form-control" data-inputmask="'mask': ['9999-9999']" >
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Contacto</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Contacto" value="{{old('Contacto')}}" class="form-control" autofocus="true">
                            </div>
                            
                        </div>
    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Dirección</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <textarea name="Direccion" rows="3"  class="form-control">{{old('Direccion')}}</textarea>
                            </div>
                            
                        </div>
    

                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Página Web</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="PaginaWeb" value="{{old('PaginaWeb')}}" class="form-control" >
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fax</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Fax" value="{{old('Fax')}}" class="form-control" autofocus="true" data-inputmask="'mask': ['9999-9999']">
                            </div>
                            
                        </div>
    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Nit</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Nit" value="{{old('Nit')}}" class="form-control" data-inputmask="'mask': ['9999-999999-999-9']">
                            </div>
                            
                        </div>
    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Registro Físcal</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="RegistroFiscal" value="{{old('RegistroFiscal')}}" class="form-control">
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Abreviatura</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Abreviatura" value="{{old('Abreviatura')}}" class="form-control" >
                            </div>
                            
                        </div>
    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Correo</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="email" name="Correo" value="{{old('Correo')}}" class="form-control">
                            </div>
                            
                        </div>
    
                    </div>

                   

                  
                  
                    

                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>

        </div>

    </div>
</div>
</div>
@endsection