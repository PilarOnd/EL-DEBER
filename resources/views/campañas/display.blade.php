<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <title>Campaña Display</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card-body {
            min-height: 250px;
            position: relative;
            padding: 15px;
        }
        canvas {
            width: 100% !important;
            height: 250px !important;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body style="--cliente-color: {{ $cliente['color_fuente'] }}">

<div class="container mt-4" style="max-width: 65%; margin: 0 auto;">
    <!--banner-->
    <div class="row g-0"> 
        <div class="col-12 p-0"> 
            <div class="banner-cliente" style="background-color: {{ $cliente['color'] }}">
                <img src="{{ asset(str_replace('public/', '', $cliente['logo'])) }}" alt="{{ $cliente['nombre'] }}" class="cliente-logo">
                <div class="banner-texto">INFORME<br>DE CAMPAÑA</div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE DISPLAY -->
    <div class="col-12">
        {{-- Cabecera de campaña --}}
        <div class="row g-0">
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">CAMPAÑA DISPLAY</h5>
                        <p class="mb-0">{{ $linea_pedido['tipo'] }}</p>
                    </div>

                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">FECHA</h5>
                        <p class="mb-0">
                            {{ \Carbon\Carbon::parse($pedido['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                            <br> al <br>
                            {{ \Carbon\Carbon::parse($pedido['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                        </p>
                    </div>
   
                    <div class="col-md-4 text-center d-flex flex-column align-items-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">PRESUPUESTO</h5>
                        <h4 class="fw-bold" style="font-size: 19.5px;">{{ number_format($linea_pedido['tarifa']['monto'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</h4>
                        <div class="progress" style="height: 10px; background-color: #ddd; border-radius: 5px; width: 100%;">
                            @php
                                $porcentaje = $linea_pedido['objetivo'] > 0 ? min(100, ($pedido['impresiones'] / $linea_pedido['objetivo']) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $porcentaje }}%; border-radius: 5px;" aria-valuenow="{{ $porcentaje }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-1" style="font-size: 13px;">{{ number_format($linea_pedido['tarifa']['monto'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</p>
                    </div>            
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229, 0.9);">
                <div class="col-md-3 text-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Formato Campaña Display</h5>
                    <p class="mb-0">{{ \Carbon\Carbon::parse($pedido['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Proyectado</h5>
                    <p class="mb-0">{{ number_format($linea_pedido['objetivo'], 0, '', '.') }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Logrado</h5>
                    <p class="mb-0">{{ number_format($displayTakeover['metricas_totales']['impresiones'], 0, '', '.') }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">% de Efectividad</h5>
                    <p class="mb-0">{{ number_format(($displayTakeover['metricas_totales']['impresiones'] / $linea_pedido['objetivo']) * 100, 2) }}%</p>
                </div>
            </div>
        </div>

        {{-- Métricas y objetivos --}}
        <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229);">
            <div class="col-12 mb-4">
                <h3 class="fw-bold" style="color: {{ $cliente['color_fuente'] }}">Resultados Generales - Take overs</h3>
            </div>

            <div class="col-12 d-flex flex-column align-items-center">
                {{-- Views/Impresiones --}}
                <div class="mb-4" style="width: 80%; max-width: 600px;">
                    <div style="background-color: {{ $cliente['color_fuente'] }}; padding: 15px; border-radius: 15px;">
                        <div class="d-flex align-items-center">
                            <div style="background-color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                                <i class="bi bi-eye-fill" style="font-size: 24px; color: {{ $cliente['color_fuente'] }};"></i>
                            </div>
                            <div class="text-white text-start">
                                <h5 class="mb-0">Views / Impresiones : {{ number_format($displayTakeover['metricas_totales']['impresiones'], 0, '', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Clics --}}
                <div class="mb-4" style="width: 80%; max-width: 600px;">
                    <div style="background-color: {{ $cliente['color_fuente'] }}; padding: 15px; border-radius: 15px;">
                        <div class="d-flex align-items-center">
                            <div style="background-color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                                <i class="bi bi-hand-index-thumb-fill" style="font-size: 24px; color: {{ $cliente['color_fuente'] }};"></i>
                            </div>
                            <div class="text-white text-start">
                                <h5 class="mb-0">Clics: {{ number_format($displayTakeover['metricas_totales']['clics'], 0, '', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CTR --}}
                <div class="mb-4" style="width: 80%; max-width: 600px;">
                    <div style="background-color: {{ $cliente['color_fuente'] }}; padding: 15px; border-radius: 15px;">
                        <div class="d-flex align-items-center">
                            <div style="background-color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                                <i class="bi bi-graph-up-arrow" style="font-size: 24px; color: {{ $cliente['color_fuente'] }};"></i>
                            </div>
                            <div class="text-white text-start">
                                <h5 class="mb-0">CTR (promedio de clics) : {{ number_format($displayTakeover['metricas_totales']['ctr'], 2, ',', '.') }}%</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Creatividades de Campaña --}}
        <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229);">
            <div class="col-md-12 text-center mb-4">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Creatividades de Campaña</h5>
            </div>
            
            {{-- Desktop Creatividad --}}
            <div class="col-md-6">
                <div class="card mb-3 h-100">
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; padding: 10px;">
                        @php
                            $desktopCreatividad = collect($creatividades)->firstWhere('dispositivo', 'Desktop');
                        @endphp
                        @if($desktopCreatividad && isset($desktopCreatividad['icono']))
                            <img src="{{ asset(str_replace('public/', '', $desktopCreatividad['icono'])) }}" 
                                 class="card-img-top" 
                                 alt="Display Desktop"
                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        @else
                            <div class="text-center">
                                <i class="bi bi-image" style="font-size: 48px; color: #ccc;"></i>
                                <p class="text-muted mt-2">No hay creatividad disponible</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-body" style="min-height: auto; padding: 10px;">
                        <h6 class="card-title">Desktop</h6>
                    </div>
                </div>
            </div>

            {{-- Mobile Creatividad --}}
            <div class="col-md-6">
                <div class="card mb-3 h-100">
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; padding: 10px;">
                        @php
                            $mobileCreatividad = collect($creatividades)->firstWhere('dispositivo', 'Mobile');
                        @endphp
                        @if($mobileCreatividad && isset($mobileCreatividad['icono']))
                            <img src="{{ asset(str_replace('public/', '', $mobileCreatividad['icono'])) }}"
                                 class="card-img-top"
                                 alt="Display Mobile"
                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        @else
                            <div class="text-center">
                                <i class="bi bi-image" style="font-size: 48px; color: #ccc;"></i>
                                <p class="text-muted mt-2">No hay creatividad disponible</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-body" style="min-height: auto; padding: 10px;">
                        <h6 class="card-title">Mobile</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Evidencias de Publicación --}}
        <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229);">
            <div class="col-md-12 text-center mb-4">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Evidencia de Publicación</h5>
            </div>
            
            {{-- Desktop Evidencia --}}
            <div class="col-md-6">
                <div class="card mb-3 h-100">
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; padding: 10px;">
                        @php
                            $desktopEvidencia = collect($creatividades)
                                ->where('tipo', 'Evidencia')
                                ->where('dispositivo', 'Desktop')
                                ->first();
                        @endphp
                        @if($desktopEvidencia && isset($desktopEvidencia['icono']))
                            <img src="{{ asset(str_replace('public/', '', $desktopEvidencia['icono'])) }}" 
                                 class="card-img-top" 
                                 alt="Evidencia Desktop"
                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        @else
                            <div class="text-center">
                                <i class="bi bi-image" style="font-size: 48px; color: #ccc;"></i>
                                <p class="text-muted mt-2">No hay evidencia disponible</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-body" style="min-height: auto; padding: 10px;">
                        <h6 class="card-title">Desktop</h6>
                    </div>
                    <div class="card-footer p-0">
                        <table class="table table-sm table-bordered m-0">
                            <tr>
                                <td><strong>Cliente:</strong></td>
                                <td>{{ $cliente['nombre'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Banner:</strong></td>
                                <td>{{ $linea_pedido['tipo'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Campaña:</strong></td>
                                <td>{{ $pedido['nombre'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Mobile Evidencia --}}
            <div class="col-md-6">
                <div class="card mb-3 h-100">
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; padding: 10px;">
                        @php
                            $mobileEvidencia = collect($creatividades)
                                ->where('tipo', 'Evidencia')
                                ->where('dispositivo', 'Mobile')
                                ->first();
                        @endphp
                        @if($mobileEvidencia && isset($mobileEvidencia['icono']))
                            <img src="{{ asset(str_replace('public/', '', $mobileEvidencia['icono'])) }}"
                                 class="card-img-top"
                                 alt="Evidencia Mobile"
                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        @else
                            <div class="text-center">
                                <i class="bi bi-image" style="font-size: 48px; color: #ccc;"></i>
                                <p class="text-muted mt-2">No hay evidencia disponible</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-body" style="min-height: auto; padding: 10px;">
                        <h6 class="card-title">Mobile</h6>
                    </div>
                    <div class="card-footer p-0">
                        <table class="table table-sm table-bordered m-0">
                            <tr>
                                <td><strong>Cliente:</strong></td>
                                <td>{{ $cliente['nombre'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Banner:</strong></td>
                                <td>{{ $linea_pedido['tipo'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Campaña:</strong></td>
                                <td>{{ $pedido['nombre'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resultados totales (directamente después de las creatividades) --}}
        <div style="background-color: rgba(245, 245, 245, 0.9);">
            <div style="padding: 10px;">
                <h5 class="fw-bold cliente-text text-center m-0" style="color: {{ $cliente['color_fuente'] }}">Resultados totales</h5>
            </div>
            {{-- Tabla de totales generales por dispositivo --}}
            <div class="d-flex justify-content-center" style="padding: 20px;">
                <div style="width: 90%; max-width: 1000px;">
                    <table class="table table-bordered text-center align-middle mb-4" style="background-color: white;">
                        <thead class="custom-row-bg">
                            <tr>
                                <th>Dispositivo</th>
                                <th>Impresiones</th>
                                <th>Clics</th>
                                <th>CTR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalesDesktop = $displayTakeover['totales_dispositivos']['desktop'];
                                $totalesMobile = $displayTakeover['totales_dispositivos']['mobile'];
                            @endphp
                            
                            <tr>
                                <td>Desktop</td>
                                <td>{{ number_format($totalesDesktop['impresiones'], 0, '', '.') }}</td>
                                <td>{{ number_format($totalesDesktop['clics'], 0, '', '.') }}</td>
                                <td>{{ number_format($totalesDesktop['ctr'], 2, ',', '.') }}%</td>
                            </tr>
                            <tr>
                                <td>Mobile</td>
                                <td>{{ number_format($totalesMobile['impresiones'], 0, '', '.') }}</td>
                                <td>{{ number_format($totalesMobile['clics'], 0, '', '.') }}</td>
                                <td>{{ number_format($totalesMobile['ctr'], 2, ',', '.') }}%</td>
                            </tr>
                            <tr style="background-color: rgba(245, 245, 245, 0.9); font-weight: bold;">
                                <td>Total</td>
                                <td>{{ number_format($displayTakeover['metricas_totales']['impresiones'], 0, '', '.') }}</td>
                                <td>{{ number_format($displayTakeover['metricas_totales']['clics'], 0, '', '.') }}</td>
                                <td>{{ number_format($displayTakeover['metricas_totales']['ctr'], 2, ',', '.') }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabla detallada por día --}}
            <div class="d-flex justify-content-center" style="padding: 20px;">
                <div style="width: 90%; max-width: 1000px;">
                    <table class="table table-bordered text-center align-middle mb-0" style="background-color: white;">
                        <thead class="custom-row-bg">
                            <tr>
                                <th>Dispositivo</th>
                                <th>Periodo</th>
                                <th>Impresiones</th>
                                <th>Clics</th>
                                <th>CTR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($displayTakeover['resultados_bloque'] as $bloque)
                                @php
                                    $dispositivo = str_contains($bloque['nombre'], 'Desktop') ? 'Desktop' : 'Mobile';
                                @endphp
                                <tr>
                                    <td>{{ $dispositivo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($bloque['fecha'])->locale('es')->isoFormat('D [de] MMMM') }}</td>
                                    <td>{{ number_format($bloque['impresiones'], 0, '', '.') }}</td>
                                    <td>{{ number_format($bloque['clics'], 0, '', '.') }}</td>
                                    <td>{{ number_format($bloque['ctr'], 2, ',', '.') }}%</td>
                                </tr>
                            @endforeach
                            <tr style="background-color: rgba(245, 245, 245, 0.9); font-weight: bold;">
                                <td colspan="2">Total</td>
                                <td>{{ number_format($displayTakeover['metricas_totales']['impresiones'], 0, '', '.') }}</td>
                                <td>{{ number_format($displayTakeover['metricas_totales']['clics'], 0, '', '.') }}</td>
                                <td>{{ number_format($displayTakeover['metricas_totales']['ctr'], 2, ',', '.') }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sección de Gráficos (después de la tabla) --}}
<div class="container" style="max-width: 65%; margin: 0 auto;">
    {{-- Título de Histogramas --}}
    <div style="background-color: rgb(233, 229, 229); padding: 10px;">
        <h5 class="fw-bold cliente-text text-center m-0" style="color: {{ $cliente['color_fuente'] }}">Histogramas</h5>
    </div>
    <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229);">
        {{-- Gráfico de Clics por Día --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center cliente-text" style="color: {{ $cliente['color_fuente'] }}">Clics por Día</h5>
                    <canvas id="clicksChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de CTR por Día --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center cliente-text" style="color: {{ $cliente['color_fuente'] }}">CTR por Día</h5>
                    <canvas id="ctrChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de Impresiones por Día --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center cliente-text" style="color: {{ $cliente['color_fuente'] }}">Impresiones por Día</h5>
                    <canvas id="impressionsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de Dispositivos --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center cliente-text" style="color: {{ $cliente['color_fuente'] }}">Distribución por Dispositivos</h5>
                    <div style="position: relative; height: 250px;">
                        <canvas id="devicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Datos del histograma
const fechas = @json($histograma['fechas']);
const impresiones = @json($histograma['impresiones']);
const clics = @json($histograma['clics']);
const ctr = @json($histograma['ctr']);

// Configuración de colores
const primaryColor = '{{ $cliente['color_fuente'] }}';
const backgroundColor = primaryColor + '40'; // Añade transparencia

// Gráfico de Clics por Día
new Chart(document.getElementById('clicksChart'), {
    type: 'bar',
    data: {
        labels: fechas,
        datasets: [{
            label: 'Clics',
            data: clics,
            backgroundColor: backgroundColor,
            borderColor: primaryColor,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de CTR por Día
new Chart(document.getElementById('ctrChart'), {
    type: 'bar',
    data: {
        labels: fechas,
        datasets: [{
            label: 'CTR (%)',
            data: ctr,
            backgroundColor: backgroundColor,
            borderColor: primaryColor,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de Impresiones por Día
new Chart(document.getElementById('impressionsChart'), {
    type: 'bar',
    data: {
        labels: fechas,
        datasets: [{
            label: 'Impresiones',
            data: impresiones,
            backgroundColor: backgroundColor,
            borderColor: primaryColor,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de Dispositivos
new Chart(document.getElementById('devicesChart'), {
    type: 'doughnut',
    data: {
        labels: ['Desktop', 'Mobile'],
        datasets: [{
            data: [
                {{ $pedido['dispositivos']['desktop']['impresiones'] ?? 0 }},
                {{ $pedido['dispositivos']['mobile']['impresiones'] ?? 0 }}
            ],
            backgroundColor: [primaryColor, primaryColor + '80'],
            borderWidth: 1,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '50%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            }
        },
        layout: {
            padding: 20
        }
    }
});
</script>

</body>
</html>
