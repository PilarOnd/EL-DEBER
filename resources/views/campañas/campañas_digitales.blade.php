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
<body style="--cliente-color: {{ $cliente[0]['color_fuente'] }}">

<div class="container mt-4" style="max-width: 65%; margin: 0 auto;">
    <!--banner-->
    <div class="row g-0"> 
        <div class="col-12 p-0"> 
            <div class="banner-cliente" style="background-color: {{ $cliente[0]['color'] }}">
                <img src="{{ asset(str_replace('public/', '', $cliente[0]['logo'])) }}" alt="{{ $cliente[0]['nombre'] }}" class="cliente-logo">
                <div class="banner-texto">INFORME<br>DE CAMPAÑA</div>
            </div>
        </div>
    </div>
    
    <div class="row g-0">
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">CAMPAÑA DIGITAL</h5>
                    <p class="mb-0">{{ $linea_pedido['tipo'] }}</p>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">FECHA</h5>
                    <p class="mb-0">
                        {{ \Carbon\Carbon::parse($linea_pedido['fecha_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                        <br> al <br>
                        {{ \Carbon\Carbon::parse($linea_pedido['fecha_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                    </p>
                </div>

                <div class="col-md-4 text-center d-flex flex-column align-items-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">PRESUPUESTO</h5>
                    <h4 class="fw-bold" style="font-size: 19.5px;">{{ number_format($linea_pedido['tarifa']['monto'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</h4>
                    <div class="progress" style="height: 10px; background-color: #ddd; border-radius: 5px; width: 100%;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: 100%; border-radius: 5px;" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-1" style="font-size: 13px;">{{ number_format($linea_pedido['tarifa']['monto'], 0, '', '.') }} ${{ strtoupper($linea_pedido['tarifa']['moneda']) }}</p>
                </div>            
            </div>
        </div>

        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229, 0.9);">
                <div class="col-md-3 text-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">Formato Campaña Digital</h5>
                    <p class="mb-0">act. {{ date('d/m/Y') }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">Objetivo Proyectado</h5>
                    <p class="mb-0">{{ number_format($linea_pedido['objetivo'], 0, '', '.') }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">Objetivo Logrado</h5>
                    <p class="mb-0">{{ number_format($totalImpresiones, 0, '', '.') }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">% de Efectividad</h5>
                    <p class="mb-0">{{ number_format($efectividad, 2) }}%</p>
                </div>
            </div>
        </div>
        
        <!-- Redes Sociales -->
        @foreach(['Facebook', 'Instagram'] as $redSocial)
            <div class="col-12">
                <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                    <div class="col-md-12 text-center">
                        <h5 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">{{ $redSocial }}</h5>
                    </div>
                </div>
            </div>
            <div class="row g-0">
                @php
                    $redSocialLower = strtolower($redSocial);
                @endphp

                @foreach($creatividades as $creatividad)
                    @if(in_array($redSocial, $creatividad['redes_sociales']))
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
                                                <small class="text-muted">Display: {{ implode(', ', $creatividad['display']) }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div style="background-color: rgb(233, 229, 229); padding: 15px; height: 100%;">
                                    <h6 class="fw-bold cliente-text" style="color: {{ $cliente[0]['color_fuente'] }}">
                                        Período: {{ \Carbon\Carbon::parse($pedidoActual['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}
                                    </h6>
                                    <table class="table table-bordered text-center align-middle">
                                        <tbody>
                                            @php
                                                $metricas = $pedidoActual[$redSocialLower];
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