<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
                            {{ \Carbon\Carbon::parse($linea_pedido['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                            <br> al <br>
                            {{ \Carbon\Carbon::parse($linea_pedido['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                        </p>
                    </div>
   
                    <div class="col-md-4 text-center d-flex flex-column align-items-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">PRESUPUESTO</h5>
                        <h4 class="fw-bold" style="font-size: 19.5px;">{{ number_format($linea_pedido['tarifa']['cpd'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</h4>

                        <div class="progress" style="height: 10px; background-color: #ddd; border-radius: 5px; width: 100%;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: 100%; border-radius: 5px;" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-1" style="font-size: 13px;">{{ number_format($linea_pedido['tarifa']['cpd'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</p>
                    </div>            
                </div>
            </div>
        </div>

        {{-- Métricas y objetivos --}}
        <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229, 0.9);">
            <div class="col-md-3 text-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Formato Display</h5>
                <p class="mb-0">act. {{ date('d/m/Y') }}</p>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Impresiones</h5>
                <p class="mb-0">{{ number_format($displayTakeover['metricas_totales']['impresiones'] ?? 0, 0, '', '.') }}</p>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Clics</h5>
                <p class="mb-0">{{ number_format($displayTakeover['metricas_totales']['clics'] ?? 0, 0, '', '.') }}</p>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">CTR</h5>
                <p class="mb-0">{{ number_format($displayTakeover['metricas_totales']['ctr'] ?? 0, 2, ',', '.') }}%</p>
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
                        <img src="{{ asset('img/creatividades/display_takeover_desktop1.jpeg') }}" 
                             class="card-img-top" 
                             alt="Display Desktop"
                             style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <div class="card-body" style="min-height: auto; padding: 10px;">
                        <h6 class="card-title">Desktop</h6>
                        <p class="card-text mb-0">
                            <small class="text-muted">970x250px</small>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mobile Creatividad --}}
            <div class="col-md-6">
                <div class="card mb-3 h-100">
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; padding: 10px;">
                        <img src="{{ asset('img/creatividades/display_takeover_mobile1.jpeg') }}" 
                             class="card-img-top" 
                             alt="Display Mobile"
                             style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <div class="card-body" style="min-height: auto; padding: 10px;">
                        <h6 class="card-title">Mobile</h6>
                        <p class="card-text mb-0">
                            <small class="text-muted">320x100px</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resultados totales (directamente después de las creatividades) --}}
        <div style="background-color: rgba(245, 245, 245, 0.9); padding: 10px;">
            <h5 class="fw-bold cliente-text text-center m-0" style="color: {{ $cliente['color_fuente'] }}">Resultados totales</h5>
        </div>
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
                    <td colspan="2"></td>
                    <td>{{ number_format($displayTakeover['metricas_totales']['impresiones'], 0, '', '.') }}</td>
                    <td>{{ number_format($displayTakeover['metricas_totales']['clics'], 0, '', '.') }}</td>
                    <td>{{ number_format($displayTakeover['metricas_totales']['ctr'], 2, ',', '.') }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Sección de Gráficos (después de la tabla) --}}
<div class="container mt-4" style="max-width: 65%; margin: 0 auto;">
    <div class="row">
        {{-- Gráfico de Clics por Hora --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center cliente-text" style="color: {{ $cliente['color_fuente'] }}">Clics por Hora</h5>
                    <canvas id="clicksChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de CTR por Hora --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center cliente-text" style="color: {{ $cliente['color_fuente'] }}">CTR por Hora</h5>
                    <canvas id="ctrChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de Impresiones por Hora --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center cliente-text" style="color: {{ $cliente['color_fuente'] }}">Impresiones por Hora</h5>
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
// Datos de ejemplo (puedes reemplazarlos con datos reales de tu backend)
const hoursLabels = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', 
                     '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];

// Configuración de colores
const primaryColor = '{{ $cliente['color_fuente'] }}';
const backgroundColor = primaryColor + '40'; // Añade transparencia

// Gráfico de Clics por Hora
new Chart(document.getElementById('clicksChart'), {
    type: 'bar',
    data: {
        labels: hoursLabels,
        datasets: [{
            label: 'Clics',
            data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80],
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

// Gráfico de CTR por Hora
new Chart(document.getElementById('ctrChart'), {
    type: 'bar',
    data: {
        labels: hoursLabels,
        datasets: [{
            label: 'CTR (%)',
            data: [2.1, 1.9, 2.3, 2.8, 2.0, 1.8, 2.2, 2.1, 1.9, 2.3, 2.8, 2.0, 1.8, 2.2, 2.1, 1.9, 2.3, 2.8, 2.0, 1.8, 2.2, 2.1, 1.9, 2.3],
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

// Gráfico de Impresiones por Hora
new Chart(document.getElementById('impressionsChart'), {
    type: 'bar',
    data: {
        labels: hoursLabels,
        datasets: [{
            label: 'Impresiones',
            data: [3200, 2900, 3500, 2800, 3100, 3000, 2700, 3200, 2900, 3500, 2800, 3100, 3000, 2700, 3200, 2900, 3500, 2800, 3100, 3000, 2700, 3200, 2900, 3500],
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
                {{ collect($displayTakeover['resultados_bloque'])->where('nombre', 'like', '%Desktop%')->sum('impresiones') }},
                {{ collect($displayTakeover['resultados_bloque'])->where('nombre', 'like', '%Mobile%')->sum('impresiones') }}
            ],
            backgroundColor: ['#FF0000', '#FFB6C1'],
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
