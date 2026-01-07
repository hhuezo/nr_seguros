@extends ('welcome')
@section('contenido')

    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>


    <div class="x_panel">

        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endif

        @if (session('error'))
            <script>
                toastr.error("{{ session('error') }}");
            </script>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="x_panel">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                    <div class="x_title">
                        <h2>Configuración de Recibo</h2>
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

                        <form method="POST" action="{{ url('catalogo/numeracion_recibo') }}/{{ $datos_generares->Id }}">
                            @method('PUT')
                            @csrf
                            <div class="form-horizontal">
                                <br>


                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número
                                        recibo</label>
                                    <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                                        <input type="number" name="Id_recibo" min="0" required
                                            value="{{ $datos_generares->Id_recibo }}" class="form-control">
                                    </div>
                                </div>


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-success">Aceptar</button>
                                    </div>
                                </div>

                            </div>
                        </form>



                    </div>

                </div>
            </div>
        </div>
    @endsection
