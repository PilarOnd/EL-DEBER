@extends('layouts.private')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 mt-4">Dashboard</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Campañas Activas</h5>
                    <p class="card-text display-4">{{ count($pedidos) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Impresiones</h5>
                    <p class="card-text display-4">
                        @php
                            $totalImpresiones = 0;
                            foreach ($pedidos as $pedido) {
                                if (isset($pedido['impresiones'])) {
                                    $totalImpresiones += (int)$pedido['impresiones'];
                                }
                                if (isset($pedido['web']['vistas'])) {
                                    $totalImpresiones += (int)$pedido['web']['vistas'];
                                }
                                if (isset($pedido['facebook']['visualizaciones'])) {
                                    $totalImpresiones += (int)$pedido['facebook']['visualizaciones'];
                                }
                            }
                            echo number_format($totalImpresiones, 0, '', '.');
                        @endphp
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tasa de Clic</h5>
                    <p class="card-text display-4">
                        @php
                            $totalClics = 0;
                            foreach ($pedidos as $pedido) {
                                if (isset($pedido['clics'])) {
                                    $totalClics += $pedido['clics'];
                                } elseif (isset($pedido['facebook']['clics_enlaces'])) {
                                    $totalClics += $pedido['facebook']['clics_enlaces'];
                                }
                            }
                            $tasaClic = $totalImpresiones > 0 ? round(($totalClics / $totalImpresiones) * 100, 2) : 0;
                            echo $tasaClic . '%';
                        @endphp
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Últimos Pedidos</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidos as $pedido)
                                    <tr>
                                        <td>{{ $pedido['nombre'] ?? 'Sin nombre' }}</td>
                                        <td>{{ $pedido['tipo'] ?? 'Sin tipo' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pedido['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pedido['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $pedido['estado'] === 'Finalizado' ? 'success' : 'warning' }}">
                                                {{ $pedido['estado'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $ruta = '';
                                                switch($pedido['tipo_campaña']) {
                                                    case 'display':
                                                        $ruta = route('reportes.display', $pedido['id_lineadepedidos']);
                                                        break;
                                                    case 'branded':
                                                        $ruta = route('reportes.branded', $pedido['id_lineadepedidos']);
                                                        break;
                                                    case 'redes':
                                                        $ruta = route('reportes.digital', $pedido['id_lineadepedidos']);
                                                        break;
                                                }
                                            @endphp
                                            <a href="{{ $ruta }}" class="btn btn-sm btn-primary" target="_blank">Ver</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 