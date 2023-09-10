@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="x_title">
                <h2>Modificación de permiso</h2>
                <ul class="nav navbar-right panel_toolbox">

                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('permission.update', $permission->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-horizontal">
                        <br>

                        <div class="form-group row">
                            <label class="control-label col-md-3" align="right">Nombre</label>
                            <div class="col-md-6">
                                <input class="form-control" name="name" required type="text"
                                    value="{{ $permission->name }}" onblur="this.value = this.value.toLowerCase();">
                            </div>
                        </div>


                        <div class="form-group" align="center">
                            <button class="btn btn-success" type="submit">Guardar</button>
                            <a href="{{ url('permission') }}"><button type="button"
                                    class="btn btn-primary">Cancelar</button></a>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
