<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    <title>Detalles de la Campaña</title>
</head>
<body>
    <header>
        <p>holaaaaaaaa</p>
    </header>

    <div class="container mt-4">
        <div class="row text-center align-items-center" style="background-image: url('/images/background.jpg'); background-size: cover; padding: 20px; border-radius: 10px;">
            <div class="col-md-3">
                <h5 class="fw-bold text-success">CAMPAÑA</h5>
                <p class="mb-0">{{ $campaña['nombre'] }}</p>
            </div>

            <div class="col-md-3">
                <h5 class="fw-bold text-success">FECHA</h5>
                <p class="mb-0">{{ date('d M Y', strtotime($campaña['fecha_inicio'])) }} <br> al <br> {{ date('d M Y', strtotime($campaña['fecha_fin'])) }}</p>
            </div>

            <div class="col-md-3">
                <h5 class="fw-bold text-success">FACEBOOK</h5>
                <p class="mb-0">{{ $campaña['plataforma']['tipo'] }} <br> act. {{ date('d/m/Y', strtotime($campaña['plataforma']['actualización'])) }}</p>
            </div>

            <div class="col-md-3">
                <h5 class="fw-bold text-success">PRESUPUESTO</h5>
                <h4 class="fw-bold">{{ number_format($campaña['presupuesto']['monto'], 0, '', '.') }} ${{ strtoupper($campaña['presupuesto']['moneda']) }}</h4>

                <div class="progress" style="height: 10px; background-color: #ddd; border-radius: 5px;">
                    <div class="progress-bar bg-success" role="progressbar"
                         style="width: 100%; border-radius: 5px;" aria-valuenow="100"
                         aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <p class="mb-0">{{ number_format($campaña['presupuesto']['monto'], 0, '', '.') }} ${{ strtoupper($campaña['presupuesto']['moneda']) }}</p>
            </div>
        </div>
    </div>

</body>
</html>