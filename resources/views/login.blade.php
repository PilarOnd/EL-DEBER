<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMID BI</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h1>EL DEBER</h1>
            <p>REPORTING ONLINE</p>
        </div>
        <div class="login-right">
            <h2>Login</h2>
            <p>Para revisar sus campañas activas, por favor inicie sesión con su cuenta de ED-REPORTES.</p>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="recordar" name="remember">
                    <label for="recordar">Recuérdame</label>
                </div>
                <button type="submit" class="btn">Iniciar sesión →</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validar campos
            const usuario = document.getElementById('usuario').value;
            const password = document.getElementById('password').value;
            
            if (!usuario || !password) {
                alert('Por favor complete todos los campos');
                return;
            }
            
            // Enviar formulario
            this.submit();
        });
    </script>
</body>
</html>
