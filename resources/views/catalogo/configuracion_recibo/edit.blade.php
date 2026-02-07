@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
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

                <form method="POST" action="{{ route('configuracion_recibo.update', $configuracion_recibo->Id) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-horizontal">
                        <br>


                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nota</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">

                                <textarea id="summernote_nota" name="Nota" >{{$configuracion_recibo->Nota}}</textarea>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Pie</label>
                            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">

                                <textarea id="summernote_pie" name="Pie">{{$configuracion_recibo->Pie}}</textarea>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group text-right" >
                                @can('configuracion-recibo edit')
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                @endcan
                               
                            </div>
                        </div>

                    </div>
                </form>



            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#summernote_nota').summernote();
        $('#summernote_pie').summernote();
    });
</script>
@include('sweetalert::alert')
@endsection
