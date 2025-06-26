@extends ('welcome')
@section('contenido')
    <div class="x_panel">
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Nueva póliza de seguro</h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('poliza/seguro/create') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i>
                        Nuevo</button></a>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="form-horizontal">
                <div class="col-sm-6">
                    <label class="control-label ">Número Póliza</label>
                    <input type="text" name="Nombre" value="" class="form-control" autofocus="true">
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Forma de pago</label>
                    <select name="FormaPago" class="form-control">
                        <option value="">Anual</option>
                        <option value="">Semestral</option>
                        <option value="">Trimestral</option>
                        <option value="">Mensual</option>
                    </select>
                </div>

                <!-- Aseguradora -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Aseguradora *</label>
                    <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%" required>
                        <option value="">Seleccione...</option>
                        @foreach ($aseguradora as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Productos -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label">Productos *</label>
                    <select name="Productos" id="Productos" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($productos as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Planes -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label">Planes *</label>
                    <select name="Planes" id="Planes" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($planes as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>



            </div>
        </div>
    </div>
@endsection
