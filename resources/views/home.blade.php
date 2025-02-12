<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <title>EL DEBER - Lectura de Campañas</title>
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
</head>
<body>
    <div class="hero-section">
        <div class="overlay"></div>
        <div class="content">
            <div class="brand">
                <img src="{{ asset('img/logo.png') }}" alt="EL DEBER">
            </div>            
            <h2 class="fw-bold">LECTURA SIMPLE DE TUS CAMPAÑAS</h2>
            <p>Comparte con todo tu equipo la información online y en PDF.</p>
            <a href="{{ url('login') }}" class="btn btn-custom">INGRESAR →</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>    
</body>
</html>
