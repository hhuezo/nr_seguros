@extends ('welcome')
@section('contenido')
    <div class="ventas-page">
        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <h3>Ofertas</h3>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12" align="right">
                    <a href="{{ url('ventas/ofertas/formulario') }}" class="btn btn-info">
                        <i class="fa fa-plus"></i> Nueva oferta
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection
