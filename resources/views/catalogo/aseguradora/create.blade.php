@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nueva aseguradora <small></small></h2>
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
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Nit</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Nit" value="{{old('Nit')}}" class="form-control" data-inputmask="'mask': ['9999-999999-999-9']">
                            </div>                            
                        </div>
    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Registro fiscal</label>
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
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fecha vinculacion</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="date" name="FechaVinculacion" value="{{old('FechaVinculacion')}}" class="form-control">
                            </div>                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tipo contribuyente</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="TipoContribuyente" class="form-control">
                                    @foreach ($tipo_contribuyente as $obj)
                                        <option value="{{$obj->Id}}" {{ old('TipoContribuyente') == $obj->Id ? 'selected' : '' }}>{{$obj->Nombre}}</option>
                                    @endforeach
                                </select>
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
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fecha constitucion</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="date" name="FechaConstitucion" value="{{old('FechaConstitucion')}}" class="form-control">
                            </div>                            
                        </div>



                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Dirección</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <textarea name="Direccion" rows="3"  class="form-control">{{old('Direccion')}}</textarea>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Teléfono fijo</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="TelefonoFijo" value="{{old('TelefonoFijo')}}" class="form-control" data-inputmask="'mask': ['9999-9999']" >
                            </div>
                            
                        </div>

                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Teléfono whatsapp</label>
                             <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="TelefonoWhatsapp" value="{{old('TelefonoWhatsapp')}}" class="form-control" data-inputmask="'mask': ['9999-9999']" >
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