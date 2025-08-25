@extends('layouts.private')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/campañas/display/create_display.css') }}">
<style>
.campaign-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
    border-radius: 10px;
}
.campaign-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
.status-activo { background-color: #d4edda; color: #155724; }
.status-pausado { background-color: #fff3cd; color: #856404; }
.status-finalizado { background-color: #f8d7da; color: #721c24; }
.creativity-preview {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 5px;
}
.no-campaigns {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}
</style>
@endpush

@section('content')
<div class="container-fluid mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-desktop me-2"></i>Campañas Display Creadas
        </h1>
        <a href="{{ route('campañas.display.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva Campaña Display
        </a>
    </div>

    @if(isset($error))
        <div class="alert alert-danger">
            <strong>Error:</strong> {{ $error }}
        </div>
    @endif

    @if(isset($success))
        <div class="alert alert-success">
            <strong>Éxito:</strong> {{ $success }}
        </div>
    @endif

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Campañas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCampañas }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Campañas Activas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $campañasActivas }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                CPM Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($cpmTotal) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Última Actualización</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $ultimaActualizacion }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de campañas -->
    @if(empty($campañas))
        <div class="card shadow">
            <div class="card-body no-campaigns">
                <i class="fas fa-desktop fa-4x mb-3 text-muted"></i>
                <h4>No hay campañas display creadas</h4>
                <p class="text-muted">¡Crea tu primera campaña display para empezar!</p>
                <a href="{{ route('campañas.display.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Crear Primera Campaña
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($campañas as $campaña)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card campaign-card shadow">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0 font-weight-bold">{{ $campaña['nombre'] }}</h6>
                            <span class="badge status-badge status-{{ $campaña['estado'] }}">
                                {{ ucfirst($campaña['estado']) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- Información básica -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Posición:</small>
                                    <div class="font-weight-bold">{{ ucfirst($campaña['posicion']) }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">CPM:</small>
                                    <div class="font-weight-bold">{{ number_format($campaña['cpm']) }}</div>
                                </div>
                            </div>

                            <!-- Dispositivos -->
                            <div class="mb-3">
                                <small class="text-muted">Dispositivos:</small>
                                <div>
                                    @if($campaña['device_target'] === 'both')
                                        <span class="badge badge-primary">Desktop</span>
                                        <span class="badge badge-primary">Mobile</span>
                                    @elseif($campaña['device_target'] === 'desktop')
                                        <span class="badge badge-info">Desktop</span>
                                    @else
                                        <span class="badge badge-success">Mobile</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Fechas -->
                            <div class="mb-3">
                                <small class="text-muted">Duración:</small>
                                <div class="small">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($campaña['fecha_inicio'])->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($campaña['fecha_fin'])->format('d/m/Y') }}
                                </div>
                            </div>

                            <!-- Creatividades preview -->
                            @if(isset($campaña['creatividades']) && !empty($campaña['creatividades']))
                                <div class="mb-3">
                                    <small class="text-muted">Creatividades:</small>
                                    <div class="d-flex flex-wrap">
                                        @foreach($campaña['creatividades'] as $dispositivo => $creatividades)
                                            @foreach($creatividades as $posicion => $creatividad)
                                                @if(isset($creatividad['ruta']))
                                                    <img src="{{ asset($creatividad['ruta']) }}" 
                                                         class="creativity-preview" 
                                                         title="{{ $dispositivo }} - {{ $posicion }}"
                                                         alt="Creatividad {{ $dispositivo }}">
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Metadatos -->
                            <div class="text-muted small">
                                <div>Creado: {{ \Carbon\Carbon::parse($campaña['created_at'])->format('d/m/Y H:i') }}</div>
                                @if(isset($campaña['usuario_creador']))
                                    <div>Por: {{ $campaña['usuario_creador'] }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm btn-outline-primary" onclick="verDetalles({{ $campaña['id'] }})">
                                    <i class="fas fa-eye me-1"></i>Ver Detalles
                                </button>
                                <div>
                                    <button class="btn btn-sm btn-outline-warning" onclick="editarCampaña({{ $campaña['id'] }})">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarCampaña({{ $campaña['id'] }})">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal para detalles de campaña -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetallesBody">
                <!-- Se llena dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
function verDetalles(id) {
    // Implementar vista de detalles
    console.log('Ver detalles de campaña:', id);
    alert('Función de detalles en desarrollo para campaña ID: ' + id);
}

function editarCampaña(id) {
    // Implementar edición
    console.log('Editar campaña:', id);
    alert('Función de edición en desarrollo para campaña ID: ' + id);
}

function eliminarCampaña(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta campaña?')) {
        // Implementar eliminación
        console.log('Eliminar campaña:', id);
        alert('Función de eliminación en desarrollo para campaña ID: ' + id);
    }
}
</script>
@endsection
