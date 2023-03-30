@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Modificar Aseguradora <small></small></h2>
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


                <form method="POST" action="{{ route('aseguradoras.update', $aseguradora->Id) }}">
                    @method('PUT')
                    @csrf

                    <div class="x_content">
                        <br />
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Nombre</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Nombre" class="form-control" autofocus="true"
                                        value="{{ $aseguradora->Nombre }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Código</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Codigo" class="form-control"
                                        value="{{ $aseguradora->Codigo }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Teléfono</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Telefono" class="form-control"
                                        data-inputmask="'mask': ['9999-9999']" value="{{ $aseguradora->Telefono }}">
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Contacto</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Contacto" class="form-control" autofocus="true"
                                        value="{{ $aseguradora->Contacto }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Dirección</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                                    <textarea name="Direccion" class="form-control" rows="3">{{ $aseguradora->Direccion }}</textarea>
                                </div>

                            </div>

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Página Web</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="PaginaWeb" class="form-control">
                                </div>
    
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Fax</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Fax" class="form-control" autofocus="true"
                                        data-inputmask="'mask': ['9999-9999']" value="{{ $aseguradora->Fax }}">
                                </div>
    
                            </div>
    
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Nit</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Nit" class="form-control"
                                        data-inputmask="'mask': ['9999-999999-999-9']" value="{{ $aseguradora->Nit }}">
                                </div>
    
                            </div>
    
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Registro Físcal</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="RegistroFiscal" class="form-control"
                                        value="{{ $aseguradora->RegistroFiscal }}">
                                </div>
    
                            </div>
    
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Abreviatura</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Abreviatura" class="form-control"
                                        value="{{ $aseguradora->Abreviatura }}">
                                </div>
    
                            </div>
    
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Correo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="email" name="Correo" class="form-control"
                                        value="{{ $aseguradora->Correo }}">
                                </div>
    
                            </div>
                        </div>



                       

                    </div>

                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit">Modificar</button>
                        <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary"
                                type="button">Cancelar</button></a>
                    </div>

                </form>



            </div>


        </div>
    </div>
    @include('sweetalert::alert')
    </div>
@endsection
