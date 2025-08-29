@extends('layouts.private')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-4 mt-4">Campañas</h1>
        </div>
    </div>

    <!-- Tabla de Campañas Display -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Campañas Display</h5>
                    <a href="{{ route('campañas.display.create') }}" class="btn-global btn-primary">
                        <i class="bi bi-plus-circle"></i> Nueva Campaña Display
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Nombre</th>
                                    <th>Banner</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($display))
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="bi bi-info-circle"></i> No hay campañas Display por el momento.
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($display as $campaña)
                                        <tr>
                                            <td>
                                                @if(isset($campaña['cliente']))
                                                    {{ $campaña['cliente']['nombre'] }}
                                                    @if(isset($campaña['es_nueva']))
                                                        <span class="badge bg-success ms-1" style="font-size: 0.6em;">NUEVA</span>
                                                    @endif
                                                @else
                                                    Cliente ID: {{ $campaña['cliente_id'] ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($campaña['pedido']))
                                                    {{ $campaña['pedido']['nombre'] }}
                                                @else
                                                    {{ $campaña['nombre'] ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($campaña['pedido']['banner']))
                                                    {{ $campaña['pedido']['banner'] }}
                                                @else
                                                    {{ ucfirst($campaña['posicion'] ?? 'N/A') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($campaña['pedido']['fecha_hora_inicio']))
                                                    {{ \Carbon\Carbon::parse($campaña['pedido']['fecha_hora_inicio'])->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($campaña['fecha_inicio'])->format('d/m/Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($campaña['pedido']['fecha_hora_fin']))
                                                    {{ \Carbon\Carbon::parse($campaña['pedido']['fecha_hora_fin'])->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($campaña['fecha_fin'])->format('d/m/Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $estado = $campaña['estado'] ?? ($campaña['pedido']['estado'] ?? 'N/A');
                                                    $badgeClass = match($estado) {
                                                        'activo' => 'bg-success',
                                                        'Finalizado' => 'bg-secondary',
                                                        'pausado' => 'bg-warning',
                                                        default => 'bg-info'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($estado) }}</span>
                                            </td>
                                            <td>
                                                @if(isset($campaña['pedido']))
                                                    <!-- Campaña de reportes -->
                                                    <a href="{{ route('reportes.display', $campaña['pedido']['id']) }}" 
                                                       class="btn-global btn-view btn-sm">
                                                        <i class="bi bi-eye"></i> Ver Reporte
                                                    </a>
                                                @else
                                                    <!-- Campaña creada desde formulario -->
                                                    <a href="{{ route('campañas.display.index') }}" 
                                                       class="btn-global btn-secondary btn-sm">
                                                        <i class="bi bi-list"></i> Ver Lista
                                                    </a>
                                                    <button class="btn-global btn-info btn-sm ms-1" 
                                                            onclick="verDetallesCampaña({{ $campaña['id'] }}, '{{ addslashes($campaña['nombre']) }}')">
                                                        <i class="bi bi-info-circle"></i> Detalles
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Campañas Branded Content -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Campañas Branded Content</h5>
                    <a href="{{ route('campañas.branded.create') }}" class="btn-global btn-primary">
                        <i class="bi bi-plus-circle"></i> Nueva Campaña Branded Content
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Nombre</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle"></i> No hay campañas Branded Content por el momento.
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Campañas Redes Sociales -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Campañas Redes Sociales</h5>
                    <a href="{{ route('campañas.redes.create') }}" class="btn-global btn-primary">
                        <i class="bi bi-plus-circle"></i> Nueva Campaña Redes Sociales
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Nombre</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle"></i> No hay campañas de Redes Sociales por el momento.
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function verDetallesCampaña(id, nombre) {
    // Crear modal dinámico para mostrar detalles
    const modalHtml = `
        <div class="modal fade" id="modalDetallesCampaña" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetallesLabel">
                            <i class="bi bi-info-circle"></i> Detalles de Campaña: ${nombre}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-global btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <a href="{{ route('campañas.display.index') }}" class="btn-global btn-primary">
                            <i class="bi bi-list"></i> Ver Todas las Campañas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal anterior si existe
    const existingModal = document.getElementById('modalDetallesCampaña');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Agregar nuevo modal al contenedor principal en lugar del body
    const mainContent = document.querySelector('.main-content') || document.body;
    mainContent.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetallesCampaña'));
    modal.show();
    
    // Cargar datos de la campaña (simulado)
    setTimeout(() => {
        const modalBody = document.querySelector('#modalDetallesCampaña .modal-body');
        modalBody.innerHTML = `
            <div class="alert alert-info">
                <h6><i class="bi bi-info-circle"></i> Información de la Campaña</h6>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Nombre:</strong> ${nombre}</p>
                <p><strong>Tipo:</strong> Campaña Display (Creada desde formulario)</p>
                <p><strong>Estado:</strong> <span class="badge bg-success">Activa</span></p>
            </div>
            <div class="alert alert-warning">
                <h6><i class="bi bi-exclamation-triangle"></i> Vista Detallada</h6>
                <p>Para ver todos los detalles de esta campaña, incluyendo creatividades y configuraciones, 
                   visita la página dedicada de campañas display.</p>
            </div>
        `;
    }, 1000);
}
</script>
@endpush
