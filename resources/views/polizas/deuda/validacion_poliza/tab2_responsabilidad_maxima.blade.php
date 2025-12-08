  <br>
  <div class="col-md-6 col-sm-6 col-xs-12">
      <h4 id="text_dinero" style="display: block;">Responsabilidad Máxima
          ${{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }} </h4>
      <h4 id="text_dinero_ac" style="display: none;"> </h4>

  </div>
  <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo2"
      style="display:{{ $poliza_responsabilidad_maxima->count() > 0 ? 'block' : 'none' }}">

      <form action="{{ url('exportar/registros_responsabilidad_maxima/') }}/{{ $deuda->Id }}" method="POST">
          @csrf
          <button style="text-align: right;" class="btn btn-success">Descargar
              Excel</button>
      </form>
  </div>
  <br><br>


  <table class="table table-striped" id="MyTable7">
      <thead>
          <tr>
              <th>Número crédito</th>
              <th>DUI</th>
              <th>Nombre</th>
              <th>Fecha Nacimiento</th>
              <th>Fecha Otorgamiento</th>
              <th>Edad Actual</th>
              <th>Edad Desembolso</th>
              <th>Total </th>
              <th>Excluir</th>
          </tr>
      </thead>
      <tbody>
          @foreach ($poliza_responsabilidad_maxima as $registro)
              <tr>
                  <td>{{ $registro->NumeroReferencia }} <br>
                  </td>
                  <td>{{ $registro->Dui }}</td>
                  <td>{{ $registro->PrimerNombre }}
                      {{ $registro->SegundoNombre }}
                      {{ $registro->PrimerApellido }}
                      {{ $registro->SegundoApellido }}
                      {{ $registro->ApellidoCasada }}
                  </td>
                  <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                  </td>
                  <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                  </td>
                  <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                  <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                      Años</td>
                  <td>${{ number_format($registro->TotalCredito, 2) }}</td>
                  <td style="text-align: center;">
                      <input type="checkbox"
                          onchange="excluir_dinero({{ $registro->Id }},{{ $registro->TotalCredito }},1)"
                          class="js-switch" {{ $registro->excluidoResponsabilidad() > 0 ? 'checked' : '' }}>
                      <input type="hidden" id="id_excluido_dinero-{{ $registro->Id }}"
                          value="{{ $registro->Excluido }}">
                  </td>

              </tr>
          @endforeach


      </tbody>
  </table>

  <script type="text/javascript">
      $(document).ready(function() {
          $('#MyTable7').DataTable();
      });
  </script>
