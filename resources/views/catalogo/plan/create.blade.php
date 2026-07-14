@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="x_title">
                <h2>Nuevo Plan <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{ url('catalogo/plan') }}" class="btn btn-info fa fa-undo" style="color: white"> Atras</a>
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

            <form method="POST" action="{{ url('catalogo/plan') }}">
                @csrf
                <div class="x_content">
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label">Nombre del Plan</label>
                            <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="Producto" class="form-label">Producto</label>
                            <select id="Producto" name="Producto" class="form-control select2" style="width: 100%" required>
                                <option value="">Seleccione...</option>
                                @foreach ($productos as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('Producto') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="form-group" align="right">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            displayOption("ul-catalogo", "li-plan");
        });
    </script>
</div>
@include('sweetalert::alert')
@endsection
