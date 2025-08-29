@extends('layouts.private')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reportes.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-4 mt-4">Reportes de Campañas</h1>
        </div>
    </div>

    <!-- Tabla de Campañas Display -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Campañas Display</h5>
                </div>
                <div class="card-body">
                    @if(isset($display) && count($display) > 0)
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
                                    @foreach($display as $campania)
                                        <tr>
                                            <td>{{ $campania['cliente']['nombre'] ?? 'Sin cliente' }}</td>
                                            <td>{{ $campania['pedido']['nombre'] ?? 'Sin nombre' }}</td>
                                            <td>{{ $campania['pedido']['banner'] ?? 'Sin banner' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($campania['pedido']['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($campania['pedido']['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $campania['pedido']['estado'] === 'Finalizado' ? 'success' : 'warning' }}">
                                                    {{ $campania['pedido']['estado'] ?? 'Sin estado' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('reportes.display', $campania['id']) }}" class="btn-global btn-view btn-sm" target="_blank">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay campañas Display por el momento.
                        </div>
                    @endif
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
                </div>
                <div class="card-body">
                    @if(isset($branded) && count($branded) > 0)
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
                                    @foreach($branded as $campania)
                                        <tr>
                                            <td>{{ $campania['cliente']['nombre'] ?? 'Sin cliente' }}</td>
                                            <td>{{ $campania['pedido']['nombre'] ?? 'Sin nombre' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($campania['pedido']['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($campania['pedido']['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $campania['pedido']['estado'] === 'Finalizado' ? 'success' : 'warning' }}">
                                                    {{ $campania['pedido']['estado'] ?? 'Sin estado' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('reportes.branded', $campania['id']) }}" class="btn-global btn-view btn-sm" target="_blank">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay campañas Branded Content por el momento.
                        </div>
                    @endif
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
                </div>
                <div class="card-body">
                    @if(isset($redes) && count($redes) > 0)
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
                                    @foreach($redes as $campania)
                                        <tr>
                                            <td>{{ $campania['cliente']['nombre'] ?? 'Sin cliente' }}</td>
                                            <td>{{ $campania['pedido']['nombre'] ?? 'Sin nombre' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($campania['pedido']['fecha_hora_inicio'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($campania['pedido']['fecha_hora_fin'])->locale('es')->isoFormat('D [de] MMMM YYYY') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $campania['pedido']['estado'] === 'Finalizado' ? 'success' : 'warning' }}">
                                                    {{ $campania['pedido']['estado'] ?? 'Sin estado' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('reportes.redes', $campania['id']) }}" class="btn-global btn-view btn-sm" target="_blank">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay campañas de Redes Sociales por el momento.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 