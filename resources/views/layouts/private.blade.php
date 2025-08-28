<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Deber - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/private.css') }}">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('img/logo.png') }}" alt="El Deber" class="logo">
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('campañas.*') ? 'active' : '' }}">
                    <a href="{{ route('campañas.index') }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Campañas</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <a href="{{ route('reportes.index') }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Reportes</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('facturas.*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('mensajes.*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="bi bi-chat-dots"></i>
                        <span>Mensajes</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
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
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <span class="me-3">Bienvenido, {{ session('usuario')['nombre'] ?? 'Usuario' }}</span>
                        <a href="#" class="position-relative me-2">
                            <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                1
                            </span>
                        </a>
                        <div class="dropdown">
                            <img src="{{ asset('img/avatar.png') }}"
                                 alt="Avatar"
                                 class="rounded-circle dropdown-toggle"
                                 id="userDropdown"
                                 data-bs-toggle="dropdown"
                                 aria-expanded="false"
                                 style="width: 40px; height: 40px; object-fit: cover; cursor:pointer;">
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li class="px-3 py-2 text-center">
                                    <strong>{{ session('usuario')['nombre'] ?? 'Usuario' }}</strong><br>
                                    <small>{{ session('usuario')['email'] ?? '' }}</small>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid main-content" id="main-scroll">
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