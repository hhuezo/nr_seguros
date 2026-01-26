@extends ('welcome')
@section('contenido')

    <style>
        .drawer-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            transition: opacity 0.3s ease;
        }

        .drawer-overlay.active {
            display: block;
        }

        .drawer {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100%;
            background: #ffffff;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .drawer.active {
            right: 0;
        }

        .drawer-header {
            padding: 20px;
            border-bottom: 1px solid #e8e8e8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .drawer-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .drawer-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drawer-close:hover {
            color: #1f2937;
        }

        .drawer-body {
            padding: 20px;
        }

        .drawer-footer {
            padding: 20px;
            border-top: 1px solid #e8e8e8;
            background: #f8f9fa;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .filter-btn-right {
            text-align: right;
        }
    </style>
    
    <!-- Header con título y botón de filtro -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <h4 class="page-title">{{ strtoupper($meses[$mes ?? $mesActual] ?? '') }}-{{ $anio ?? $anioActual }}</h4>
        </div>
        <div class="col-md-6 filter-btn-right">
            <button type="button" class="btn btn-primary" onclick="openDrawer()">
                <i class="fa fa-filter"></i> Filtrar
            </button>
        </div>
    </div>

    <!-- Overlay del drawer -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>

    <!-- Drawer -->
    <div class="drawer" id="drawer">
        <div class="drawer-header">
            <h4>Filtros de Búsqueda</h4>
            <button type="button" class="drawer-close" onclick="closeDrawer()">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <form method="GET" action="{{ url('control-primas') }}" id="filterForm">
            <div class="drawer-body">
                <div class="form-group">
                    <label for="TipoPoliza" style="font-weight: 600; margin-bottom: 8px; display: block;">Tipo de Póliza</label>
                    <select class="form-control" name="TipoPoliza" id="TipoPoliza">
                        <option value="1" {{ request('TipoPoliza') == 1 ? 'selected' : '' }}>Personas</option>
                        <option value="2" {{ request('TipoPoliza') == 2 ? 'selected' : '' }}>Residencia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mes" style="font-weight: 600; margin-bottom: 8px; display: block;">Mes</label>
                    <select class="form-control" name="mes" id="mes">
                        @foreach ($meses as $numero => $nombre)
                            <option value="{{ $numero }}" @if ($numero == ($mes ?? $mesActual)) selected @endif>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="anio" style="font-weight: 600; margin-bottom: 8px; display: block;">Año</label>
                    <select class="form-control" name="anio" id="anio">
                        @foreach ($anios as $anioOption)
                            <option value="{{ $anioOption }}" @if ($anioOption == ($anio ?? $anioActual)) selected @endif>
                                {{ $anioOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-search"></i> Aplicar Filtros
                </button>
                <button type="button" class="btn btn-default btn-block" onclick="closeDrawer()" style="margin-top: 10px;">
                    Cancelar
                </button>
            </div>
        </form>
    </div>

    <script>
        function openDrawer() {
            document.getElementById('drawer').classList.add('active');
            document.getElementById('drawerOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            document.getElementById('drawer').classList.remove('active');
            document.getElementById('drawerOverlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Cerrar drawer con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDrawer();
            }
        });
    </script>

    <div>
            <style>
                .metric-card {
                    background: #ffffff;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    transition: all 0.3s ease;
                    border: 1px solid #e8e8e8;
                    position: relative;
                }

                .metric-card:hover {
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    transform: translateY(-2px);
                }

                .metric-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 15px;
                    gap: 12px;
                }

                .metric-icon-circle {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    color: white;
                    flex-shrink: 0;
                }

                .icon-blue { background-color: #3b82f6; }
                .icon-cyan { background-color: #06b6d4; }
                .icon-yellow { background-color: #fbbf24; }
                .icon-green { background-color: #10b981; }
                .icon-gray { background-color: #6b7280; }
                .icon-teal { background-color: #14b8a6; }
                .icon-purple { background-color: #8b5cf6; }
                .icon-orange { background-color: #f97316; }
                .icon-pink { background-color: #ec4899; }

                .metric-title {
                    font-size: 12px;
                    font-weight: 600;
                    color: #6b7280;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    line-height: 1.4;
                    flex: 1;
                    margin: 0;
                }

                .metric-value {
                    font-size: 28px;
                    font-weight: 700;
                    color: #1f2937;
                    margin-bottom: 12px;
                    font-family: 'Arial', sans-serif;
                }

            </style>

            <!-- Dashboard de Métricas -->
            <div class="row" style="margin-top: 20px;">
                <!-- Prima Bruta -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-blue">
                                <i class="fa fa-dollar"></i>
                            </div>
                            <div class="metric-title">PRIMA BRUTA</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['prima_bruta'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
                <!-- Extra Prima -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-cyan">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                            <div class="metric-title">EXTRA PRIMA</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['extra_prima'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
                <!-- Prima Emitida -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-yellow">
                                <i class="fa fa-file-text"></i>
                            </div>
                            <div class="metric-title">PRIMA EMITIDA</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['prima_emitida'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
                <!-- Valor Descuento Rentabilidad -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-green">
                                <i class="fa fa-percent"></i>
                            </div>
                            <div class="metric-title">VALOR DESCUENTO RENTABILIDAD</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['valor_descuento_rentabilidad'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Prima Descontada -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-gray">
                                <i class="fa fa-minus-circle"></i>
                            </div>
                            <div class="metric-title">PRIMA DESCONTADA</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['prima_descontada'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
                <!-- Comisión Neta -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-purple">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="metric-title">COMISIÓN NETA</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['comision_neta'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
                <!-- IVA 13% -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-orange">
                                <i class="fa fa-calculator"></i>
                            </div>
                            <div class="metric-title">IVA 13%</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['iva_13'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
                <!-- Retención 1% -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-pink">
                                <i class="fa fa-percent"></i>
                            </div>
                            <div class="metric-title">RETENCIÓN 1%</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['retencion_1'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Prima Líquida -->
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <div class="metric-icon-circle icon-teal">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="metric-title">PRIMA LÍQUIDA</div>
                        </div>
                        <div class="metric-value">${{ number_format($totales['prima_liquida'] ?? 0, 2, '.', ',') }}</div>
                    </div>
                </div>
            </div>
    </div>

@endsection
