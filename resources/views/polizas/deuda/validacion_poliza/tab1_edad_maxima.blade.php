     <br>
     <div class="col-md-6 col-sm-6 col-xs-12">
         <h4>Edad Maxima de Terminación {{ $deuda->EdadMaximaTerminacion }} años
         </h4>
     </div>
     <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo"
         style="display:{{ $poliza_edad_maxima->count() > 0 ? 'block' : 'none' }}">

         <form action="{{ url('exportar/registros_edad_maxima') }}/{{ $deuda->Id }}" method="POST">
             @csrf
             <button style="text-align: right;" class="btn btn-success">Descargar
                 Excel</button>
         </form>

     </div>
     <br><br>
     <div class="col-md-12 col-sm-12 col-xs-12">
         <table class="table table-striped" id="MyTable6">
             <thead>
                 <tr>
                     <th>Número crédito</th>
                     <th>DUI</th>
                     <th>Nombre</th>
                     <th>Fecha nacimiento</th>
                     <th>Edad Otorgamiento</th>
                     <th>Edad Actual</th>
                     <th>Total</th>
                     <th style="text-align: center;">Excluir</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($poliza_edad_maxima as $registro)
                     <tr>
                         <td>{{ $registro->NumeroReferencia }}</td>
                         <td>{{ $registro->Dui }}</td>
                         <td>{{ $registro->PrimerNombre }}
                             {{ $registro->SegundoNombre }}
                             {{ $registro->PrimerApellido }}
                             {{ $registro->SegundoApellido }}
                             {{ $registro->ApellidoCasada }}
                         </td>
                         <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                         </td>
                         <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                             Años</td>
                         <td>{{ $registro->EdadDesembloso ? $registro->Edad : '' }}
                             Años</td>
                         <td>${{ number_format($registro->TotalCredito, 2) }}</td>
                         <td>
                             <input type="checkbox" onchange="excluir({{ $registro->Id }},0,1)" class="js-switch"
                                 {{ $registro->excluidoEdad() > 0 ? 'checked' : '' }}>
                             <input type="hidden" id="id_excluido-{{ $registro->Id }}"
                                 value="{{ $registro->Excluido }}">
                         </td>
                     </tr>
                 @endforeach


             </tbody>
         </table>
     </div>
