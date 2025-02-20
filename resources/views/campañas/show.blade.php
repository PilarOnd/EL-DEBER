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
<body>

<div class="container mt-4" style="max-width: 65%; margin: 0 auto;">
    <!--banner-->
    <div class="row g-0"> 
        <div class="col-12 p-0"> 
            <div class="banner-cliente" style="background-color: {{ $cliente['color'] }}">
                <img src="{{ asset(str_replace('public/', '', $cliente['logo'])) }}" alt="{{ $cliente['nombre'] }}" class="cliente-logo">
            </div>
        </div>
    </div>
    <!-- datos-->
    <div class="row g-0 ">
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">CAMPAÑA</h5>
                    <p class="mb-0">{{ $campaña['nombre'] }}</p>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">FECHA</h5>
                    <p class="mb-0">{{ date('d M Y', strtotime($campaña['fecha_inicio'])) }} <br> al <br> {{ date('d M Y', strtotime($campaña['fecha_fin'])) }}</p>
                </div>
   
                <div class="col-md-4 text-center d-flex flex-column align-items-center">
                    <h5 class="fw-bold text-success">PRESUPUESTO</h5>
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
                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">{{ $campaña['plataforma']['nombre'] }}</h5>
                    <p class="mb-0">act. {{ date('d/m/Y', strtotime($campaña['plataforma']['actualización'])) }}</p>
                </div>
                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">Objetivo Proyectado</h5>
                    <p class="mb-0">{{ $campaña['objetivo'] }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">Objetivo Logrado</h5>
                    <p class="mb-0">{{ $campaña['impresiones'] }} Impresiones</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">% de Efectividad</h5>
                    <p class="mb-0">{{ $efectividad }}%</p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-bordered text-center align-middle">
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
                       <tr class="custom-row-bg">
                          <td>
                             <img src="{{ asset(str_replace('public/', '', $creatividad['icono'])) }}" alt="Icono" class="icono-hover" style="width: 50px; cursor: pointer;">
                          </td>
                          <td>{{ implode(", ", $creatividad['display']) }}</td>                           
                          <td>{{ $creatividad['rendimiento']['impresiones'] }}</td>                           
                          <td>{{ $creatividad['rendimiento']['clics'] }}</td>                           
                          <td>{{ $creatividad['rendimiento']['ctr'] }}</td>
                        </tr>
                   @endforeach
                   <tr class="fw-bold custom-row-bg">
                       <td colspan="2">Total</td>
                       <td>0000000</td>
                       <td>0000000</td>
                       <td>0000000</td>
                   </tr>
             </tbody>
         </table>
       </div>
    </div>
</div>

</body>
</html>