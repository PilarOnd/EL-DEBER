<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Deber - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/private.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('img/logo.png') }}" alt="El Deber" class="logo">
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('campañas.index') }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Campañas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-graph-up"></i>
                        <span>Reportes</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-chat-dots"></i>
                        <span>Mensajes</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-gear"></i>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        @if(session('usuario'))
                            <span class="me-3">Bienvenido, {{ session('usuario')['nombre'] }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            });
        });
    </script>
</body>
</html> 