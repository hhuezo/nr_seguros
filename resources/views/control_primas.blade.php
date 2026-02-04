@extends('welcome')

@section('contenido')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;500&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --bg-main: #f8fafc;
            --border-color: #e2e8f0;
            --primary-blue: #1e3a8a;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-dark); }

        /* --- DRAWER --- */
        .drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 9998;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }
        .drawer-overlay.active { display: block; }
        .drawer {
            position: fixed;
            top: 0;
            right: -420px;
            width: 400px;
            height: 100%;
            background: #ffffff;
            z-index: 9999;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        .drawer.active { right: 0; }
        .drawer-header { padding: 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
        .drawer-title { font-weight: 700; font-size: 16px; color: var(--text-dark); text-transform: uppercase; display: flex; align-items: center; gap: 10px; margin: 0; }
        .drawer-body { padding: 24px; flex-grow: 1; overflow-y: auto; }
        .drawer-footer { padding: 24px; border-top: 1px solid var(--border-color); background: #f8fafc; }

        /* --- ESTILOS DE TABLA --- */
        .chart-wrapper { background: #fff; padding: 24px; border-radius: 16px; border: 1px solid var(--border-color); margin-bottom: 24px; }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); }
        .table-premium th { background: #e9e9e9; color: var(--text-muted); text-transform: uppercase; font-size: 11px; padding: 14px; border-bottom: 2px solid var(--border-color); }
        .table-premium td { padding: 14px; border-bottom: 1px solid rgba(0,0,0,0.05); font-size: 13px; vertical-align: middle; }
        .cell-money { font-family: 'Monaco', monospace; font-weight: 600; text-align: right; }

        /* Utility */
        .btn-close-drawer { background: #f1f5f9; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted); }
        .form-select-custom { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px; margin-bottom: 15px; }
        .form-label-custom { display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; }
    </style>

    {{-- Encabezado Principal --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="m-0 font-weight-bold">
                Dashboard de Primas <span class="text-muted font-weight-light">|</span> {{ $meses[$mes ?? $mesActual] ?? '' }} {{ $anio ?? $anioActual }}
            </h4>
        </div>
        <div class="col-md-6 text-right">
            <button onclick="openDrawer()" class="btn btn-primary px-4 shadow-sm d-inline-flex align-items-center gap-2" style="background: var(--primary-blue); border: none; border-radius: 8px; padding: 10px 20px;">
                <span class="material-symbols-outlined" style="font-size: 20px;">filter_list</span>
            </button>
        </div>
    </div>

    @php
        $coloresConfig = [
            'success'   => ['label' => 'Primas Aplicadas',     'bg' => '#22c55e', 'pastel' => 'rgba(34, 197, 94, 0.1)'],
            'warning'   => ['label' => 'Primas por Aplicar',   'bg' => '#facc15', 'pastel' => 'rgba(250, 204, 21, 0.15)'],
            'info'      => ['label' => 'Gestión de Cobro',     'bg' => '#38bdf8', 'pastel' => 'rgba(56, 189, 248, 0.1)'],
            'orange'    => ['label' => 'Primas por Causar',    'bg' => '#f97316', 'pastel' => 'rgba(249, 115, 22, 0.1)'],
            'secondary' => ['label' => 'Carteras por Procesar','bg' => '#94a3b8', 'pastel' => 'rgba(148, 163, 184, 0.1)'],
        ];
        $metricas = [
            'prima_emitida' => ['titulo' => 'Prima Emitida', 'id' => 'chartEmitida'],
            'prima_descontada' => ['titulo' => 'Prima Descontada', 'id' => 'chartDescontada'],
            'prima_liquida' => ['titulo' => 'Prima Líquida', 'id' => 'chartLiquida'],
        ];
    @endphp

    {{-- Gráficas --}}
    <div class="row">
        @foreach ($metricas as $m)
            <div class="col-lg-4">
                <div class="chart-wrapper">
                    <div id="{{ $m['id'] }}" style="height: 280px;"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tabla --}}
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table-premium shadow-sm">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th class="text-right">Emitida</th>
                            <th class="text-right">Descontada</th>
                            <th class="text-center">% s/Total</th>
                            <th class="text-right">Líquida</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalEmitida = 0; $totalDescontada = 0; $totalLiquida = 0;
                            foreach ($coloresConfig as $key => $conf) {
                                $t = $totalesPorColor[$key] ?? [];
                                $totalEmitida += (float) ($t['prima_emitida'] ?? 0);
                                $totalDescontada += (float) ($t['prima_descontada'] ?? 0);
                                $totalLiquida += (float) ($t['prima_liquida'] ?? 0);
                            }
                        @endphp
                        <tr style="background: #f8fafc; font-weight: 800;">
                            <td style="color: var(--primary-blue)">TOTAL GENERAL</td>
                            <td class="cell-money">$ {{ number_format($totalEmitida, 2) }}</td>
                            <td class="cell-money">$ {{ number_format($totalDescontada, 2) }}</td>
                            <td class="text-center">100%</td>
                            <td class="cell-money">$ {{ number_format($totalLiquida, 2) }}</td>
                        </tr>
                        @foreach ($coloresConfig as $key => $config)
                            @php
                                $t = $totalesPorColor[$key] ?? [];
                                $desc = (float)($t['prima_descontada'] ?? 0);
                                $porc = $totalDescontada > 0 ? ($desc / $totalDescontada) * 100 : 0;
                            @endphp
                            <tr style="background-color: {{ $config['pastel'] }};">
                                <td style="font-weight: 600;">
                                    <span style="display:inline-block; width:12px; height:12px; border-radius:3px; background:{{ $config['bg'] }}; margin-right:8px;"></span>
                                    {{ $config['label'] }}
                                </td>
                                <td class="cell-money text-muted">$ {{ number_format($t['prima_emitida'] ?? 0, 2) }}</td>
                                <td class="cell-money">$ {{ number_format($desc, 2) }}</td>
                                <td class="text-center font-weight-bold text-dark">{{ number_format($porc, 2) }}%</td>
                                <td class="cell-money text-muted">$ {{ number_format($t['prima_liquida'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DRAWER --}}
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
    <div class="drawer" id="drawer">
        <div class="drawer-header">
            <h5 class="drawer-title">
                <span class="material-symbols-outlined">filter_alt</span>
                Filtros de Análisis
            </h5>
            <button onclick="closeDrawer()" class="btn-close-drawer">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="drawer-body">
            <form method="GET" action="{{ url('control-primas') }}" id="filterForm">
                <label class="form-label-custom">Mes de Consulta</label>
                <select class="form-select-custom" name="mes">
                    @foreach ($meses as $num => $nombre)
                        <option value="{{ $num }}" {{ ($mes ?? $mesActual) == $num ? 'selected' : '' }}>{{ strtoupper($nombre) }}</option>
                    @endforeach
                </select>

                <label class="form-label-custom">Año</label>
                <select class="form-select-custom" name="anio">
                    @for ($i = date('Y'); $i >= 2025; $i--)
                        <option value="{{ $i }}" {{ ($anio ?? $anioActual) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>

                <label class="form-label-custom">Tipo de Póliza</label>
                <select class="form-select-custom" name="TipoPoliza">
                    <option value="1" {{ request('TipoPoliza') == 1 ? 'selected' : '' }}>Personas</option>
                    <option value="2" {{ request('TipoPoliza') == 2 ? 'selected' : '' }}>Residencia</option>
                </select>
            </form>
        </div>

        <div class="drawer-footer">
            <button type="submit" form="filterForm" class="btn btn-primary btn-block py-3 font-weight-bold shadow-sm" style="background: var(--primary-blue); border: none; border-radius: 10px;">
                Aplicar Filtros
            </button>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
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

        function renderChart(id, title, data) {
            Highcharts.chart(id, {
                chart: { type: 'column', backgroundColor: 'transparent' },
                title: { text: title, align: 'left', style: { fontSize: '12px', fontWeight: '700', color: '#64748b' } },
                xAxis: { type: 'category', labels: { enabled: false }, lineWidth: 0, tickWidth: 0 },
                yAxis: { title: { text: null }, gridLineColor: '#f1f5f9', labels: { enabled: false } },
                legend: { enabled: false },
                credits: { enabled: false },
                plotOptions: {
                    column: {
                        borderRadius: 6,
                        colorByPoint: true,
                        dataLabels: { enabled: true, format: '${point.y:,.0f}', style: { fontSize: '10px' } }
                    }
                },
                series: [{ name: 'Monto', data: data }]
            });
        }

        @php
            $getChartData = function ($key) use ($coloresConfig, $totalesPorColor) {
                return collect($coloresConfig)->map(function ($c, $k) use ($key, $totalesPorColor) {
                    return ['name' => $c['label'], 'y' => (float)($totalesPorColor[$k][$key] ?? 0), 'color' => $c['bg']];
                })->values();
            };
        @endphp

        document.addEventListener('DOMContentLoaded', function() {
            renderChart('chartEmitida', 'PRIMA EMITIDA', {!! json_encode($getChartData('prima_emitida')) !!});
            renderChart('chartDescontada', 'PRIMA DESCONTADA', {!! json_encode($getChartData('prima_descontada')) !!});
            renderChart('chartLiquida', 'PRIMA LÍQUIDA', {!! json_encode($getChartData('prima_liquida')) !!});
        });
    </script>
@endsection
