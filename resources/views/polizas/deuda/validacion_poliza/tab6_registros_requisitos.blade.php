    <br>
    <div class="col-md-6 col-sm-12">

        <div class="form-group row">
            <label class="control-label">Opciones</label>

            <select class="form-control" onchange="loadCreditosConRquisitos(this.value)">
                <option value="1">Creditos con requisitos</option>
                <option value="2">Creditos válidos</option>
                <option value="3">Creditos rehabilitados</option>
                <option value="4">Histórico de validados</option>
            </select>
        </div>

        <br>

        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background-color: #eeb458; margin-right: 10px;">
            </div>
            <span>Los créditos rehabilitados se mostrarán de color naranja.</span>
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background-color: #b12020; margin-right: 10px;">
            </div>
            <span>Los montos resaltados en rojo deben redimir evaluación.</span>
        </div>
        <br>

        <br>

    </div>
    <div class="col-md-6 col-sm-12" align="right">
        <form method="POST" action="{{ url('exportar/registros_requisitos') }}/{{ $deuda->Id }}">
            @csrf
            <button class="btn btn-success">Descargar Excel</button>
        </form>
        <br>

    </div>
    <br>
    <br>
    <div id="creditos_validos">
    </div>
