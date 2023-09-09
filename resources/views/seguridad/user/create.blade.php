@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nuevo usuario <small></small></h2>
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
                <form action="{{ url('usuario') }}" method="POST">
                    @csrf
                    <div class="x_content">
                        <br />


                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="form-control" autofocus="true">
                            </div>
                            <label class="col-sm-3 control-label">&nbsp;</label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Clave</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required name="password" value="{{ old('password') }}"
                                    class="form-control">
                            </div>
                            <label class="col-sm-3 control-label">&nbsp;</label>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label">Correo</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" required name="email" value="{{ old('email') }}"
                                    class="form-control" onblur="this.value = this.value.toLowerCase();">
                            </div>
                            <label class="col-sm-3 control-label">&nbsp;</label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Rol</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="rol" required class="form-control">
                                    @foreach ($roles as $obj)
                                        <option value="{{ $obj->name }}" {{ old('rol') == $obj->id ?: '' }}>
                                            {{ $obj->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>




                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit">Guardar</button>
                        <a href="{{ url('usuario/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                    </div>

                </form>


            </div>

        </div>
    </div>

@endsection
