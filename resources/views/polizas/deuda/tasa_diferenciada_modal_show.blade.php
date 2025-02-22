    <div class="modal fade" id="modal-creditos-show-{{ $obj->Id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Tasa diferenciada</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-simulated">
                        <div class="table-header">
                            <div>Tipo cálculo</div>
                            <div>Fecha inicio</div>
                            <div>Fecha final</div>
                            <div>Edad inicio</div>
                            <div>Edad final</div>
                            <div>Tasa</div>
                        </div>

                        @foreach ($obj->tasasDiferenciadas as $registro)
                            <div class="table-row">
                                <div>
                                    {{ $registro->TipoCalculo == 1 ? 'Fecha' : ($registro->TipoCalculo == 2 ? 'Edad' : '') }}
                                </div>
                                <div>
                                    {{ !empty($registro->FechaDesde) ? date('d/m/Y', strtotime($registro->FechaDesde)) : '' }}
                                </div>
                                <div>
                                    {{ !empty($registro->FechaHasta) ? date('d/m/Y', strtotime($registro->FechaHasta)) : '' }}
                                </div>
                                <div>{{ !empty($registro->EdadDesde) ? $registro->EdadDesde . ' AÑOS' : '' }}</div>
                                <div>{{ !empty($registro->EdadHasta) ? $registro->EdadHasta . ' AÑOS' : '' }}</div>
                                <div>{{ $registro->Tasa }}</div>

                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                </div>


            </div>
        </div>
    </div>
