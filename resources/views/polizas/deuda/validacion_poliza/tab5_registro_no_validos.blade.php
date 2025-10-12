  <div class="col-md-6 col-sm-12">

  </div>
  <div class="col-md-6 col-sm-12" align="right">
      <form method="POST" action="{{ url('exportar/registros_no_validos') }}/{{ $deuda->Id }}">
          @csrf
          <button class="btn btn-success">Descargar Excel</button>
      </form>
  </div>
  <br>
  <br>
  <div id="creditos_no_validos">


  </div>
