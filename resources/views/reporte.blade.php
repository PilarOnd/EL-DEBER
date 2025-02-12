@extends('layouts.app')

@section('content')
<div class="pdf-container">
    <!-- Imagen superior -->
    <div class="text-center mb-4">
        <img src="{{ asset('img/header-campaña.png') }}" class="img-fluid header-logo" alt="Informe de Campaña">
    </div>

    <!-- Información general -->
    <div class="info-section">
        <div class="row text-center">
            <!-- Campaña -->
            <div class="col-3">
                <h5 class="fw-bold text-danger mb-0">CAMPAÑA</h5>
                <p class="mb-0">{{ $data['campaña']['nombre'] }}</p>
            </div>

            <!-- Fecha -->
            <div class="col-3">
                <h5 class="fw-bold text-danger mb-0">FECHA</h5>
                <p class="mb-0">{{ date('d M Y', strtotime($data['campaña']['fecha_inicio'])) }} - 
                   {{ date('d M Y', strtotime($data['campaña']['fecha_fin'])) }}</p>
            </div>

            <!-- Plataforma -->
            <div class="col-3">
                <h5 class="fw-bold text-danger mb-0">PLATAFORMA</h5>
                <p class="mb-0">{{ $data['campaña']['plataformas'][0]['nombre'] }} ({{ $data['campaña']['plataformas'][0]['tipo'] }})</p>
            </div>

            <!-- Presupuesto -->
            <div class="col-3">
                <h5 class="fw-bold text-danger mb-0">PRESUPUESTO</h5>
                <p class="mb-2"><strong>${{ number_format($data['campaña']['presupuesto']['total'], 2) }} {{ $data['campaña']['presupuesto']['moneda'] }}</strong></p>
                <div class="progress">
                    <div class="progress-bar bg-danger" style="width: 100%;">${{ number_format($data['campaña']['presupuesto']['total'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos personalizados -->
<style>
    .pdf-container {
        background: white;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin: 20px auto;
        max-width: 1200px;
    }

    .header-logo {
        max-width: 300px;
        height: auto;
    }

    .info-section {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .info-section h5 {
        font-size: 0.9rem;
    }

    .info-section p {
        font-size: 0.85rem;
    }

    @media print {
        .pdf-container {
            box-shadow: none;
            padding: 0;
        }

        body {
            background: white;
        }
    }
</style>
@endsection
