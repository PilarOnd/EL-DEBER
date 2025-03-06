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
    <title>Detalles de la Campaña</title>
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
    
    <!-- SECCIÓN DE CAMPAÑA DIGITAL -->
    @if(isset($campañaDigital))
        <div class="row g-0">
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">CAMPAÑA DIGITAL</h5>
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
                        <h4 class="fw-bold"style="font-size: 19.5px;">{{ number_format($linea_pedido['tarifa']['cpd'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</h4>

                        <div class="progress" style="height: 10px; background-color: #ddd; border-radius: 5px; width: 100%;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: 100%; border-radius: 5px;" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                       </div>
                     <p class="mb-1" style="font-size: 13px;">{{ number_format($linea_pedido['tarifa']['cpd'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</p>
                   </div>            
                </div>
            </div>
            
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229, 0.9);">
                    <div class="col-md-3 text-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Formato Campaña Digital</h5>
                        <p class="mb-0">act. {{ date('d/m/Y') }}</p>
                    </div>

                    <div class="col-md-3 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Proyectado</h5>
                        <p class="mb-0">{{ number_format($linea_pedido['objetivo'], 0, '', '.') }} Impresiones</p>
                    </div>

                    <div class="col-md-3 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Logrado</h5>
                        <p class="mb-0">{{ number_format($totales['impresiones'], 0, '', '.') }} Impresiones</p>
                    </div>

                    <div class="col-md-3 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">% de Efectividad</h5>
                        <p class="mb-0">{{ $efectividad }}%</p>
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
                        $creatividadesRedSocial = array_filter($creatividades, function($creatividad) use ($redSocial) {
                            return in_array($redSocial, $creatividad['plataformas'] ?? []) && 
                                   $creatividad['tipo_formato'] === 'campaña_digital';
                        });
                        
                        $redSocialLower = strtolower($redSocial);
                        $metricasRedSocial = $campañaDigital[$redSocialLower] ?? [];
                    @endphp

                    @foreach($creatividadesRedSocial as $creatividad)
                        <div class="col-md-4">
                            <div style="background-color: rgb(233, 229, 229); padding: 15px; height: 100%;">
                                <div class="card mb-3">
                                    <img src="{{ asset(str_replace('public/', '', $creatividad['recursos']['imagen'])) }}" 
                                         class="card-img-top" 
                                         alt="Creatividad"
                                         style="max-height: 200px; object-fit: contain; padding: 10px;">
                                    <div class="card-body">
                                        <p class="card-text">
                                            <small class="text-muted">Display: {{ implode(', ', $creatividad['especificaciones']['dispositivos']) }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div style="background-color: rgb(233, 229, 229); padding: 15px; height: 100%;">
                                <h6 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">
                                    Período: {{ \Carbon\Carbon::parse($creatividad['fecha_publicacion'])->locale('es')->isoFormat('D/MM/YYYY') }}
                                </h6>
                                <table class="table table-bordered text-center align-middle">
                                    <tbody>
                                        @php
                                            $rendimiento = $creatividad['rendimiento'][$redSocialLower] ?? [];
                                            unset($rendimiento['ctr']);
                                            $chunks = array_chunk($rendimiento, 4, true);
                                        @endphp

                                        @foreach ($chunks as $chunk)
                                            <tr style="background-color: white;">
                                                @foreach ($chunk as $metrica => $valor)
                                                    <td style="width: 25%" class="fw-bold">{{ ucfirst(str_replace('_', ' ', $metrica)) }}</td>
                                                @endforeach
                                            </tr>
                                            <tr style="background-color: white;">
                                                @foreach ($chunk as $valor)
                                                    <td style="width: 25%">{{ is_numeric($valor) ? number_format($valor, 0, '', '.') : $valor }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif

    <!-- SECCIÓN DE BRANDED CONTENT -->
    @if(isset($brandedContent))
        <div class="col-12">
            <div style="margin: 40px 0;"></div>
            
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
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Formato Branded Content</h5>
                    <p class="mb-0">act. {{ date('d/m/Y') }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Vistas</h5>
                    <p class="mb-0">{{ number_format($brandedContent['metricas_nota']['vistas'] ?? 0, 0, '', '.') }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Usuarios Activos</h5>
                    <p class="mb-0">{{ number_format($brandedContent['metricas_nota']['usuarios_activos'] ?? 0, 0, '', '.') }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Tiempo Promedio</h5>
                    <p class="mb-0">{{ $brandedContent['metricas_nota']['tiempo_promedio_lectura'] ?? '00:00:00' }}</p>
                </div>
            </div>

            {{-- Nota Digital --}}
            <div class="row g-0">
                <div class="col-12">
                    <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                        <div class="col-md-12 text-center">
                            <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">NOTA DIGITAL</h5>
                        </div>
                    </div>
                </div>
                
                {{-- Mostrar solo una vez la imagen y tabla de métricas --}}
                @php $mostradoNotaDigital = false; @endphp
                @foreach ($creatividades as $creatividad)
                    @if($creatividad['tipo_formato'] === 'nota_digital' && !$mostradoNotaDigital)
                        @php $mostradoNotaDigital = true; @endphp
                        <div class="col-md-6">
                            <div style="background-color: rgb(233, 229, 229); padding: 15px; height: 100%;">
                                <img src="{{ asset('img/creatividades/notadigitalpagweb.jpeg') }}" 
                                     class="img-fluid" 
                                     alt="Nota Digital"
                                     style="width: 100%; object-fit: contain;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div style="background-color: rgb(233, 229, 229); padding: 15px; height: 100%;">
                                <table class="table table-bordered text-center align-middle" style="background-color: white;">
                                    <thead class="custom-row-bg">
                                        <tr>
                                            <th>EVENTO</th>
                                            <th>RESULTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Vistas</td>
                                            <td>{{ number_format($brandedContent['metricas_nota']['vistas'] ?? 0, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Usuarios activos</td>
                                            <td>{{ number_format($brandedContent['metricas_nota']['usuarios_activos'] ?? 0, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Vistas por usuario</td>
                                            <td>{{ number_format($brandedContent['metricas_nota']['vistas_por_usuario'] ?? 0, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Sesiones</td>
                                            <td>{{ number_format($brandedContent['metricas_nota']['sesiones'] ?? 0, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tiempo promedio de lectura</td>
                                            <td>{{ $brandedContent['metricas_nota']['tiempo_promedio_lectura'] ?? '00:00:00' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Periodo</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($brandedContent['metricas_nota']['fecha_publicacion'] ?? $linea_pedido['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Tabla de Resultados --}}
            <div class="col-12">
                <table class="table table-bordered text-center align-middle" style="background-color: white;">
                    <thead class="custom-row-bg">
                        <tr>
                            <th>Icono</th>
                            <th>Dispositivo</th>
                            <th>Impresiones</th>                   
                            <th>Clics</th>
                            <th>CTR (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($creatividades as $creatividad)
                            @if($creatividad['tipo_formato'] === 'nota_digital')
                                @php 
                                    $impresiones = $brandedContent['metricas_nota']['vistas'] ?? 0;
                                    $clics = $brandedContent['metricas_nota']['usuarios_activos'] ?? 0;
                                    $ctr = $impresiones > 0 ? round(($clics / $impresiones) * 100, 2) : 0;
                                @endphp
                                <tr style="background-color: white;">
                                    <td>
                                        <img src="{{ asset(str_replace('public/', '', $creatividad['recursos']['imagen'])) }}" 
                                             alt="Icono" 
                                             class="icono-hover" 
                                             style="width: 50px; cursor: pointer;">
                                    </td>
                                    <td>{{ implode(", ", $creatividad['especificaciones']['dispositivos']) }}</td>                           
                                    <td>{{ number_format($impresiones, 0, '', '.') }}</td>                           
                                    <td>{{ number_format($clics, 0, '', '.') }}</td>
                                    <td>{{ $ctr }}%</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- SECCIÓN DE DISPLAY -->
    @if(isset($displayTakeover))
        <div class="col-12">
            {{-- Salto de sección --}}
            <div style="margin: 40px 0;"></div>

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
                        <div class="card-body">
                            <h6 class="card-title">Desktop</h6>
                            <p class="card-text">
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
                        <div class="card-body">
                            <h6 class="card-title">Mobile</h6>
                            <p class="card-text">
                                <small class="text-muted">320x100px</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tablas de Resultados Display --}}
            <div class="col-12">
                {{-- Resultados Totales --}}
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
    @endif
</div>
</div>

</body>
</html>