@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nuevo permiso <small></small></h2>
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

                <div class="x_content">
                    <br />

                    <form action="{{ url('permission') }}" method="POST">
                        @csrf
                        <div class="form-horizontal">
                            <br>
                            <div class="form-group row">
                                <label class="control-label col-md-3" align="right">Nombre</label>
                                <div class="col-md-6">
                                    <input class="form-control" name="name" required type="text" autofocus="true"
                                        onblur="this.value = this.value.toLowerCase();">
                                </div>
                            </div>

                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('permission') }}"><button type="button"
                                        class="btn btn-primary">Cancelar</button></a>
                            </div>

                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection
