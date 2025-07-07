@extends('layouts.private')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-4 mt-4">Crear Nueva Campaña</h1>
            <p>Seleccione el tipo de campaña que desea crear.</p>
        </div>
    </div>

    <div class="row">
        <!-- Card for Campaña Display -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Campaña Display</h5>
                    <p class="card-text">Cree y gestione campañas de display, aproveche al máximo su visibilidad.</p>
                    <a href="{{ route('campañas.display.create') }}" class="btn btn-primary">Crear Campaña Display</a>
                </div>
            </div>
        </div>

        <!-- Card for Campaña Branded Content -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Campaña Branded Content</h5>
                    <p class="card-text">Cree y gestione campañas de branded content, genere un mayor engagement.</p>
                    <a href="{{ route('campañas.branded.create') }}" class="btn btn-primary">Crear Campaña Branded Content</a>
                </div>
            </div>
        </div>

        <!-- Card for Campaña Redes Sociales -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Campaña Redes Sociales</h5>
                    <p class="card-text">Cree y gestione campañas de redes sociales, llege a más personas.</p>
                    <a href="{{ route('campañas.redes.create') }}" class="btn btn-primary">Crear Campaña Redes Sociales</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 