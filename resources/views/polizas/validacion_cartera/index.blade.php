@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Validación de cartera</h2>
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

                    <div class="form-horizontal" style="font-size: 12px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form action="{{ url('polizas/validacion_cartera') }}" method="POST"
                                enctype="multipart/form-data" class="forms-sample">
                                @csrf
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Póliza</label>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <select name="Poliza" class="form-control">
                                            @foreach ($polizas as $poliza)
                                                <option value="{{ $poliza->Id }}">{{ $poliza->NumeroPoliza }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <select name="Axo" class="form-control">
                                            @for ($i = date('Y'); $i >= 2022; $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <select name="Mes" class="form-control">
                                            @for ($i = 1; $i < 12; $i++)
                                                @if (date('m') == $i)
                                                    <option value="{{ $i }}" selected>{{ $meses[$i] }}
                                                    </option>
                                                @else
                                                    <option value="{{ $i }}">{{ $meses[$i] }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Archivo" type="file" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group" align="center">
                                        <button type="submit" class="btn btn-success">Aceptar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>




        @include('sweetalert::alert')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- jQuery -->
        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {});
        </script>

    @endsection
