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
    <!-- datos-->
    <div class="row g-0 ">
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">CAMPAÑA</h5>
                    <p class="mb-0">{{ $campaña['nombre'] }}</p>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">FECHA</h5>
                    <p class="mb-0">{{ date('d M Y', strtotime($campaña['fecha_inicio'])) }} <br> al <br> {{ date('d M Y', strtotime($campaña['fecha_fin'])) }}</p>
                </div>
   
                <div class="col-md-4 text-center d-flex flex-column align-items-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">PRESUPUESTO</h5>
                    <h4 class="fw-bold"style="font-size: 19.5px;">{{ number_format($campaña['presupuesto']['monto'], 0, '', '.') }} ${{ strtoupper($campaña['presupuesto']['moneda']) }}</h4>

                    <div class="progress" style="height: 10px; background-color: #ddd; border-radius: 5px; width: 100%;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: 100%; border-radius: 5px;" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100"></div>
                   </div>
                 <p class="mb-1" style="font-size: 13px;">{{ number_format($campaña['presupuesto']['monto'], 0, '', '.') }} ${{ strtoupper($campaña['presupuesto']['moneda']) }}</p>
               </div>            
            </div>
        </div>
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229, 0.9);">
                <div class="col-md-3 text-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Formato Display</h5>
                    <p class="mb-0">act. {{ date('d/m/Y', strtotime($campaña['plataforma']['actualización'])) }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Proyectado</h5>
                    <p class="mb-0">{{ number_format($campaña['objetivo'], 0, '', '.') }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Objetivo Logrado</h5>
                    <p class="mb-0">{{ number_format($campaña['impresiones'], 0, '', '.') }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">% de Efectividad</h5>
                    <p class="mb-0">{{ $efectividad }}%</p>
                </div>
            </div>
        </div>
        @if(isset($campaña['plataforma']['redes_sociales']))
            @foreach ($campaña['plataforma']['redes_sociales'] as $redSocial)
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
                            return in_array($redSocial, $creatividad['plataformas'] ?? []);
                        });
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
                                    Período: {{ date('d/m/Y', strtotime($creatividad['fecha_publicacion'])) }}
                                </h6>
                                <table class="table table-bordered text-center align-middle">
                                    <tbody>
                                        @php
                                            $redSocialLower = strtolower($redSocial);
                                            $metricas = $creatividad['rendimiento'][$redSocialLower] ?? [];
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
                <div class="col-12">
                    <table class="table table-bordered text-center align-middle" style="background-color: white;">
                        <thead class="custom-row-bg">
                            <tr>
                                <th>Icono</th>
                                <th>Dispositivo</th>
                                <th>Impresiones</th>                   
                                <th>Clics</th>
                            </tr>
                        </thead>
                        <tbody>
                           @php
                               $totalesRedSocial = [
                                   'impresiones' => 0,
                                   'clics' => 0
                               ];
                           @endphp
                           
                           @foreach ($creatividadesRedSocial as $creatividad)
                               @php
                                   $redSocialLower = strtolower($redSocial);
                                   $metricas = $creatividad['rendimiento'][$redSocialLower] ?? [];
                                   
                                   // Actualizar totales según la red social
                                   if ($redSocial === 'Facebook') {
                                       $impresiones = $metricas['visualizaciones'] ?? 0;
                                       $clics = $metricas['clics_enlaces'] ?? 0;
                                   } else { // Instagram
                                       $impresiones = $metricas['visualizaciones'] ?? 0;
                                       $clics = $metricas['me_gusta'] ?? 0;
                                   }
                                   
                                   $totalesRedSocial['impresiones'] += $impresiones;
                                   $totalesRedSocial['clics'] += $clics;
                               @endphp
                               <tr style="background-color: white;">
                                  <td>
                                     <img src="{{ asset(str_replace('public/', '', $creatividad['recursos']['imagen'])) }}" alt="Icono" class="icono-hover" style="width: 50px; cursor: pointer;">
                                  </td>
                                  <td>{{ implode(", ", $creatividad['especificaciones']['dispositivos']) }}</td>                           
                                  <td>{{ number_format($impresiones, 0, '', '.') }}</td>                           
                                  <td>{{ number_format($clics, 0, '', '.') }}</td>
                               </tr>
                           @endforeach
                           
                           <tr class="fw-bold custom-row-bg">
                               <td colspan="2">Total {{ $redSocial }}</td>
                               <td>{{ number_format($totalesRedSocial['impresiones'], 0, '', '.') }}</td>
                               <td>{{ number_format($totalesRedSocial['clics'], 0, '', '.') }}</td>
                           </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Sección de Display --}}
    @if(isset($campaña['plataforma']['display']))
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                <div class="col-md-12 text-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Display</h5>
                </div>
            </div>

            {{-- Creatividades de Campaña --}}
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgb(233, 229, 229);">
                <div class="col-md-12 text-center mb-4">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Creatividades de Campaña</h5>
                </div>
                
                {{-- Desktop Creatividad --}}
                @foreach($creatividades as $creatividad)
                    @if(in_array('Desktop', $creatividad['especificaciones']['dispositivos']))
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <img src="{{ asset(str_replace('public/', '', $creatividad['recursos']['imagen'])) }}" 
                                     class="card-img-top" 
                                     alt="Display Desktop"
                                     style="max-height: 300px; object-fit: contain; padding: 10px;">
                                <div class="card-body">
                                    <h6 class="card-title">Desktop</h6>
                                    <p class="card-text">
                                        <small class="text-muted">{{ $creatividad['especificaciones']['dimensiones']['ancho'] }}x{{ $creatividad['especificaciones']['dimensiones']['alto'] }}px</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Mobile Creatividad --}}
                @foreach($creatividades as $creatividad)
                    @if(in_array('Mobile', $creatividad['especificaciones']['dispositivos']))
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <img src="{{ asset(str_replace('public/', '', $creatividad['recursos']['imagen'])) }}" 
                                     class="card-img-top" 
                                     alt="Display Mobile"
                                     style="max-height: 300px; object-fit: contain; padding: 10px;">
                                <div class="card-body">
                                    <h6 class="card-title">Mobile</h6>
                                    <p class="card-text">
                                        <small class="text-muted">{{ $creatividad['especificaciones']['dimensiones']['ancho'] }}x{{ $creatividad['especificaciones']['dimensiones']['alto'] }}px</small>
                                    </p>
                                </div>
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
                            <th>Dispositivo</th>
                            <th>Bloque</th>
                            <th>Período</th>
                            <th>Impresiones</th>
                            <th>Clics</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($creatividades as $creatividad)
                            @if($creatividad['tipo_formato'] === 'display')
                                <tr style="background-color: white;">
                                    <td>{{ implode(', ', $creatividad['especificaciones']['dispositivos']) }}</td>
                                    <td>{{ $creatividad['recursos']['url_destino'] }}</td>
                                    <td>{{ date('d/m/Y', strtotime($creatividad['fecha_publicacion'])) }}</td>
                                    <td>{{ number_format($creatividad['rendimiento']['metricas']['impresiones'], 0, '', '.') }}</td>
                                    <td>{{ number_format($creatividad['rendimiento']['metricas']['clics'], 0, '', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach

                        {{-- Total General --}}
                        <tr class="fw-bold custom-row-bg">
                            <td colspan="3">Total General</td>
                            <td>{{ number_format($totalesDisplay['impresiones'], 0, '', '.') }}</td>
                            <td>{{ number_format($totalesDisplay['clics'], 0, '', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Sección de Branded Content --}}
    @if(isset($campaña['plataforma']['branded_content']))
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                <div class="col-md-12 text-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Branded Content</h5>
                </div>
            </div>
            {{-- Aquí irá el código para mostrar creatividades y métricas de branded content --}}
        </div>
    @endif

    {{-- Sección de Display Takeover --}}
    @if(isset($displayTakeover))
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                <div class="col-md-12 text-center">
                    <h5 class="fw-bold cliente-text" style="color: {{ $cliente['color_fuente'] }}">Display Takeover</h5>
                </div>
            </div>

            {{-- Primera tabla --}}
            <div class="row g-0">
                <div class="col-12" style="background-color: rgb(233, 229, 229); padding: 20px;">
                    <table class="table table-bordered text-center align-middle mb-0" style="background-color: white;">
                        <thead class="custom-row-bg">
                            <tr>
                                <th>Dispositivo</th>
                                <th>Bloque</th>
                                <th>Período</th>
                                <th>Impresiones</th>
                                <th>Clics</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mobile</td>
                                <td>https://sofia.com/promociones</td>
                                <td>20/02/2025</td>
                                <td>100.010</td>
                                <td>550</td>
                            </tr>
                            <tr>
                                <td>Desktop</td>
                                <td>https://sofia.com/promociones</td>
                                <td>20/02/2025</td>
                                <td>185.000</td>
                                <td>950</td>
                            </tr>
                            <tr class="fw-bold custom-row-bg">
                                <td colspan="3">Total General</td>
                                <td>285.010</td>
                                <td>1.500</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Métricas por Dispositivo --}}
            <div class="row g-0">
                <div class="col-12" style="background-color: rgb(233, 229, 229); padding: 20px;">
                    <div class="row">
                        @foreach(['Desktop', 'Mobile'] as $dispositivo)
                            <div class="col-md-6">
                                <table class="table table-bordered text-center align-middle mb-4" style="background-color: white;">
                                    <thead class="custom-row-bg">
                                        <tr>
                                            <th colspan="2">{{ $dispositivo }}</th>
                                        </tr>
                                        <tr>
                                            <th>Métrica</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Impresiones</td>
                                            <td>{{ $dispositivo == 'Desktop' ? '185.000' : '100.010' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Clics</td>
                                            <td>{{ $dispositivo == 'Desktop' ? '950' : '550' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Resultados por Bloque --}}
            <div class="row g-0">
                <div class="col-12" style="background-color: rgb(233, 229, 229); padding: 20px;">
                    <table class="table table-bordered text-center align-middle mb-0" style="background-color: white;">
                        <thead class="custom-row-bg">
                            <tr>
                                <th>Bloque</th>
                                <th>Fecha</th>
                                <th>Impresiones</th>
                                <th>Clics</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Portada Desktop</td>
                                <td>20/02/2025</td>
                                <td>92.500</td>
                                <td>475</td>
                            </tr>
                            <tr>
                                <td>Notas Mobile</td>
                                <td>20/02/2025</td>
                                <td>50.005</td>
                                <td>275</td>
                            </tr>
                            <tr class="fw-bold custom-row-bg">
                                <td colspan="2">Total General</td>
                                <td>285.010</td>
                                <td>1.500</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

</body>
</html>