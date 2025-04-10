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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <title>Detalles de la Campaña</title>
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

    <!-- SECCIÓN DE BRANDED CONTENT -->
    <div class="col-12">
        {{-- Cabecera de campaña --}}
        <div class="row g-0">
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">CAMPAÑA BRANDED CONTENT</h5>
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
                                $porcentajePresupuesto = $linea_pedido['objetivo'] > 0 ? min(100, ($totales['impresiones'] / $linea_pedido['objetivo']) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $porcentajePresupuesto }}%; border-radius: 5px;" aria-valuenow="{{ $porcentajePresupuesto }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-1" style="font-size: 13px;">{{ number_format($linea_pedido['tarifa']['monto'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</p>
                    </div>            
                </div>
            </div>
        </div>

        {{-- Métricas y objetivos --}}
        <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229, 0.9);">
            <div class="col-md-3 text-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Formato Branded Content</h5>
                <p class="mb-0">{{ \Carbon\Carbon::parse($pedido['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</p>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Proyectado</h5>
                <p class="mb-0">{{ number_format($linea_pedido['objetivo'] ?? 0, 0, '', '.') }} Visualizaciones</p>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Logrado</h5>
                <p class="mb-0">{{ number_format($totales['impresiones'] ?? 0, 0, '', '.') }} Visualizaciones</p>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Tiempo Promedio</h5>
                <p class="mb-0">{{ $pedido['web']['tiempo_promedio_lectura'] ?? '00:00:00' }}</p>
            </div>
        </div>

        {{-- Nota Digital Desktop --}}
        <div class="row g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
            <div class="col-12 text-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">DETALLE DEL REPORTE</h5>
            </div>
        </div>
        <div class="row g-0" style="padding: 20px; background-color: rgb(233, 229, 229);">
            <div class="col-md-6 text-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-calendar" style="font-size: 1.5rem; margin-right: 8px;"></i> Fecha:
                </h5>
                <p class="mb-0" style="font-size: 1.2rem;">{{ \Carbon\Carbon::parse($pedido['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</p>
            </div>
            <div class="col-md-6 text-center">
                <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-file-earmark-text" style="font-size: 1.5rem; margin-right: 8px;"></i> Ejecución:
                </h5>
                <p class="mb-0" style="font-size: 1.2rem;">Nota Digital</p>
            </div>
            <div class="col-12 text-center mt-3">
                <p class="mb-0" style="font-size: 1.2rem;"><i class="bi bi-eye" style="font-size: 1.5rem; margin-right: 8px;"></i>Visitas a la página</p>
                <p class="mb-0" style="font-size: 1.2rem;"><i class="bi bi-clock" style="font-size: 1.5rem; margin-right: 8px;"></i>Tiempo promedio de lectura</p>
                <p class="mb-0" style="font-size: 1.2rem;"><i class="bi bi-people" style="font-size: 1.5rem; margin-right: 8px;"></i>Usuarios</p>
            </div>
        </div>

        <div class="row g-0">
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                    <div class="col-md-12 text-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">NOTA DIGITAL DESKTOP</h5>
                    </div>
                </div>
            </div>
            
            @php 
                $notaDesktop = collect($creatividades)->firstWhere('nombre', 'Nota Digital Desktop');
            @endphp
            @if($notaDesktop)
                <div class="col-12">
                    <div style="background-color: rgb(233, 229, 229); padding: 5px 15px; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <img src="{{ asset(str_replace('public/', '', $notaDesktop['icono'])) }}" 
                             class="img-fluid" 
                             alt="Nota Digital Desktop"
                             style="max-width: 90%; max-height: 80%; object-fit: contain; margin: 20px 0;">
                        <div class="text-center">
                            <a href="{{ $pedido['link_web'] }}" target="_blank" class="btn btn-primary" style="background-color: {{ $cliente['color_fuente'] }}; border-color: {{ $cliente['color_fuente'] }};">
                                Ver Nota Digital
                            </a>
                        </div>
                        <div class="mt-2 text-center" style="padding: 10px 20px;">
                            <a href="{{ $pedido['link_web'] }}" target="_blank" style="color: black; text-decoration: none;">
                                Link: {{ $pedido['link_web'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Nota Digital Mobile --}}
        <div class="row g-0">
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                    <div class="col-md-12 text-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">NOTA DIGITAL</h5>
                    </div>
                </div>
            </div>
            
            @php 
                $notaMobile = collect($creatividades)->firstWhere('nombre', 'Nota Digital Mobile');
            @endphp
            @if($notaMobile)
                <div class="col-md-6">
                    <div style="background-color: rgb(233, 229, 229); padding: 5px 15px; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset(str_replace('public/', '', $notaMobile['icono'])) }}" 
                             class="img-fluid" 
                             alt="Nota Digital"
                             style="max-width: 100%; max-height: 90%; object-fit: contain; margin: -25px 0;">
                    </div>
                </div>

                <div class="col-md-6" style="display: flex; justify-content: center; align-items: center;">
                    <div style="background-color: rgb(233, 229, 229); padding: 15px; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                        <table class="table table-bordered text-center align-middle" style="background-color: white; margin: 0 auto;">
                            <thead class="custom-row-bg">
                                <tr>
                                    <th>EVENTO</th>
                                    <th>RESULTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Vistas</td>
                                    <td>{{ number_format($pedido['web']['vistas'] ?? 0, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Usuarios activos</td>
                                    <td>{{ number_format($pedido['web']['usuarios_activos'] ?? 0, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Vistas por usuario</td>
                                    <td>{{ number_format($pedido['web']['vistas_por_usuario'] ?? 0, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Sesiones</td>
                                    <td>{{ number_format($pedido['web']['sesiones'] ?? 0, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Tiempo promedio de lectura</td>
                                    <td>{{ $pedido['web']['tiempo_promedio_lectura'] ?? '00:00:00' }}</td>
                                </tr>
                                <tr>
                                    <td>Periodo</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($pedido['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Resultados totales --}}
        <div style="background-color: rgba(245, 245, 245, 0.9);">
            <div style="padding: 10px;">
                <h5 class="fw-bold cliente-text text-center m-0" style="color: {{ $cliente['color_fuente'] }}">Resultados totales</h5>
            </div>
            <div class="d-flex justify-content-center" style="padding: 20px;">
                <div style="width: 90%; max-width: 1000px;">
                    <table class="table table-bordered text-center align-middle mb-4" style="background-color: white;">
                        <thead class="custom-row-bg">
                            <tr>
                                <th>Icono</th>
                                <th>Dispositivo</th>
                                <th>Red Social</th>
                                <th>Impresiones</th>                   
                                <th>Clics</th>
                                <th>CTR (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($creatividades as $index => $creatividad)
                                <tr>
                                    <td>
                                        <img src="{{ asset(str_replace('public/', '', $creatividad['icono'])) }}"
                                             alt="Icono"
                                             class="icono-hover"
                                             style="width: 50px; cursor: pointer;">
                                    </td>
                                    <td>{{ is_array($creatividad['dispositivo']) ? implode(', ', $creatividad['dispositivo']) : $creatividad['dispositivo'] }}</td>
                                    <td>{{ isset($creatividad['redes_sociales']) ? implode(', ', $creatividad['redes_sociales']) : 'N/A' }}</td>
                                    <td>{{ number_format($totales['detalles_por_creatividad'][$index]['impresiones'], 0, '', '.') }}</td>
                                    <td>{{ number_format($totales['detalles_por_creatividad'][$index]['clics'], 0, '', '.') }}</td>
                                    <td>{{ number_format($totales['detalles_por_creatividad'][$index]['ctr'], 2, ',', '.') }}%</td>
                                </tr>
                            @endforeach
                            <tr style="background-color: rgba(245, 245, 245, 0.9); font-weight: bold;">
                                <td colspan="3">Total</td>
                                <td>{{ number_format($totales['impresiones'], 0, '', '.') }}</td>
                                <td>{{ number_format($totales['clics'], 0, '', '.') }}</td>
                                <td>{{ number_format($totales['ctr'], 2, ',', '.') }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Redes Sociales -->
        @foreach(['Facebook', 'Instagram'] as $redSocial)
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                    <div class="col-md-12 text-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">{{ $redSocial }}</h5>
                    </div>
                </div>
            </div>
            <div class="row g-0">
                @php
                    $redSocialLower = strtolower($redSocial);
                @endphp

                @foreach($creatividades as $creatividad)
                    @if(isset($creatividad['redes_sociales']) && in_array($redSocial, $creatividad['redes_sociales']))
                        @php
                            $pedidoActual = collect($pedidos)->firstWhere('id', $creatividad['pedido_id']);
                        @endphp

                        @if($pedidoActual)
                            <div class="col-md-4">
                                <div style="background-color: rgb(233, 229, 229); padding: 15px; height: 100%;">
                                    <div class="card">
                                        <div style="height: 200px; display: flex; align-items: center; justify-content: center; padding: 10px; overflow: hidden;">
                                            <img src="{{ asset(str_replace('public/', '', $creatividad['icono'])) }}" 
                                                 class="card-img-top" 
                                                 alt="Creatividad"
                                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                        <div class="card-body" style="min-height: auto; padding: 10px;">
                                            <p class="card-text mb-0">
                                                <small class="text-muted">Dispositivo: {{ is_array($creatividad['dispositivo']) ? implode(', ', $creatividad['dispositivo']) : $creatividad['dispositivo'] }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div style="background-color: rgb(233, 229, 229); padding: 15px; height: 100%;">
                                    <h6 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">
                                        Período: {{ \Carbon\Carbon::parse($pedidoActual['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                                    </h6>
                                    <table class="table table-bordered text-center align-middle">
                                        <tbody>
                                            @php
                                                $metricas = isset($pedidoActual['redes_sociales'][$redSocialLower]) ? $pedidoActual['redes_sociales'][$redSocialLower] : [];
                                                $chunks = array_chunk($metricas, 4, true);
                                            @endphp

                                            @foreach ($chunks as $chunk)
                                                <tr style="background-color: white;">
                                                    @foreach ($chunk as $metrica => $valor)
                                                        <td style="width: 25%" class="fw-bold">{{ ucfirst(str_replace('_', ' ', $metrica)) }}</td>
                                                    @endforeach
                                                </tr>
                                                <tr style="background-color: white;">
                                                    @foreach ($chunk as $valor)
                                                        <td style="width: 25%">{{ number_format($valor, 0, '', '.') }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>

</body>
</html>
