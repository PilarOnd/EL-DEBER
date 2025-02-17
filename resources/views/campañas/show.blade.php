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
    <div class="row g-0"> <!-- Añadimos g-0 para eliminar los gutters -->
        <div class="col-12 p-0"> <!-- Añadimos p-0 para eliminar el padding -->
            <img src="{{ asset('img/reporte.png') }}" alt="Informe de campaña" 
                 style="width: 100%; height: 200px; object-fit: cover;">
        </div>
    </div>
    <!-- datos-->
    <div class="row g-0 ">
        <div class="col-12">
            <div class="row text-center align-items-stretch g-0" style="padding: 20px; background-color: rgba(245, 245, 245, 0.9);">
                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">CAMPAÑA</h5>
                    <p class="mb-0">{{ $campaña['nombre'] }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">FECHA</h5>
                    <p class="mb-0">{{ date('d M Y', strtotime($campaña['fecha_inicio'])) }} <br> al <br> {{ date('d M Y', strtotime($campaña['fecha_fin'])) }}</p>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-center">
                    <h5 class="fw-bold text-success">FACEBOOK</h5>
                    <p class="mb-0">{{ $campaña['plataforma']['tipo'] }} <br> act. {{ date('d/m/Y', strtotime($campaña['plataforma']['actualización'])) }}</p>
                </div>
   
                <div class="col-md-3 text-center d-flex flex-column align-items-center">
                    <h5 class="fw-bold text-success">PRESUPUESTO</h5>
                    <h4 class="fw-bold">{{ number_format($campaña['presupuesto']['monto'], 0, '', '.') }} ${{ strtoupper($campaña['presupuesto']['moneda']) }}</h4>

                    <div class="progress" style="height: 10px; background-color: #ddd; border-radius: 5px; width: 100%;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: 100%; border-radius: 5px;" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100"></div>
                   </div>
                 <p class="mb-0" style="font-size: 14px;">{{ number_format($campaña['presupuesto']['monto'], 0, '', '.') }} ${{ strtoupper($campaña['presupuesto']['moneda']) }}</p>
               </div>            
            </div>
        </div>
    </div>
</div>

</body>
</html>