@extends('layouts.private')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-4 mt-4">Nueva Campaña de Redes Sociales</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('campañas.redes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre de la Campaña</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente['id'] }}">{{ $cliente['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_hora_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="datetime-local" class="form-control" id="fecha_hora_inicio" name="fecha_hora_inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_hora_fin" class="form-label">Fecha de Fin</label>
                                <input type="datetime-local" class="form-control" id="fecha_hora_fin" name="fecha_hora_fin" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="redes_sociales" class="form-label">Redes Sociales</label>
                                <select class="form-select" id="redes_sociales" name="redes_sociales[]" multiple required>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Twitter">Twitter</option>
                                    <option value="LinkedIn">LinkedIn</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Finalizado">Finalizado</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Crear Campaña</button>
                                <a href="{{ route('campañas.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 