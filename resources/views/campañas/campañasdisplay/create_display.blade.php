@extends('layouts.private')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow">
        <div class="card-header py-3 bg-light border-bottom">
            <h1 class="h4 mb-0 text-gray-800">Nueva Campaña Display</h1>
        </div>
        <div class="card-body p-4">
            <form>
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="nombre_campania" class="form-label">Nombre de Campaña</label>
                            <input type="text" 
                                class="form-control" 
                                id="nombre_campania" 
                                placeholder="Ingrese el nombre de la campaña"
                                required>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="posicion" class="form-label">Posición</label>
                            <select class="form-select" id="posicion" required>
                                <option value="" selected disabled>Seleccione una posición</option>
                                <option value="premium">Premium</option>
                                <option value="gold">Gold</option>
                                <option value="social">Social</option>
                                <option value="takeover">TakeOver</option>
                                <option value="sticky">Sticky</option>
                                <option value="push">Push Notification</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
                            <input type="datetime-local" 
                                class="form-control" 
                                id="fecha_inicio"
                                required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="fecha_fin" class="form-label">Fecha y Hora de Fin</label>
                            <input type="datetime-local" 
                                class="form-control" 
                                id="fecha_fin"
                                required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-light me-2">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Guardar Campaña</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control:focus, .form-select:focus {
    border-color: #666;
    box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.btn {
    padding: 0.5rem 1.2rem;
    font-weight: normal;
}

.form-label {
    font-weight: 500;
    color: #444;
}

.form-select {
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
}
</style>
@endsection

