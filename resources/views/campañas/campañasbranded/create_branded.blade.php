@extends('layouts.private')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow">
        <div class="card-header py-3 bg-light border-bottom">
            <h1 class="h4 mb-0 text-gray-800">Nueva Campaña Branded Content</h1>
        </div>
        <div class="card-body p-4">
            <form id="formCampaña" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="nombre_campania" class="form-label">Nombre de Campaña</label>
                            <input type="text" 
                                class="form-control" 
                                id="nombre_campania" 
                                name="nombre_campania"
                                placeholder="Ingrese el nombre de la campaña"
                                required>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="tipo_contenido" class="form-label">Tipo de Contenido</label>
                            <select class="form-select" id="tipo_contenido" name="tipo_contenido" required>
                                <option value="" selected disabled>Seleccione el tipo de contenido</option>
                                <option value="nota">Nota Digital</option>
                                <option value="entrevista">Entrevista al CEO</option>
                                <option value="brandedplay">Branded Play</option>
                                <option value="radio">Radio Capsula Multimedia</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="contenido" class="form-label">Contenido</label>
                            <textarea class="form-control" 
                                id="contenido" 
                                name="contenido"
                                rows="5"
                                placeholder="Ingrese el contenido de la campaña"
                                required></textarea>
                        </div>
                    </div>

                    <div id="imagenContainer" class="col-12 mb-4">
                        <div class="form-group">
                            <label for="imagen_principal" class="form-label">Imagen Principal</label>
                            <input type="file" 
                                class="form-control" 
                                id="imagen_principal" 
                                name="imagen_principal"
                                accept="image/*"
                                required>
                            <small class="form-text text-muted">
                                Formatos aceptados: JPG, PNG. Tamaño máximo: 5MB
                            </small>
                        </div>
                    </div>

                    <div id="videoContainer" class="col-12 mb-4" style="display: none;">
                        <div class="form-group">
                            <label for="video_principal" class="form-label">Video Principal</label>
                            <input type="file" 
                                class="form-control" 
                                id="video_principal" 
                                name="video_principal"
                                accept="video/*"
                                required>
                            <small class="form-text text-muted">
                                Formatos aceptados: MP4, MOV. Tamaño máximo: 100MB
                            </small>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="materiales_adicionales" class="form-label">Materiales Adicionales</label>
                            <input type="file" 
                                class="form-control" 
                                id="materiales_adicionales" 
                                name="materiales_adicionales[]"
                                multiple
                                accept="image/*,video/*,application/pdf">
                            <small class="form-text text-muted">
                                Puede subir múltiples archivos. Formatos aceptados: JPG, PNG, PDF, MP4
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
                            <input type="datetime-local" 
                                class="form-control" 
                                id="fecha_inicio"
                                name="fecha_inicio"
                                required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="fecha_fin" class="form-label">Fecha y Hora de Fin</label>
                            <input type="datetime-local" 
                                class="form-control" 
                                id="fecha_fin"
                                name="fecha_fin"
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCampaña');
    const tipoContenido = document.getElementById('tipo_contenido');
    const imagenContainer = document.getElementById('imagenContainer');
    const videoContainer = document.getElementById('videoContainer');
    const imagenInput = document.getElementById('imagen_principal');
    const videoInput = document.getElementById('video_principal');

    tipoContenido.addEventListener('change', function() {
        const valor = this.value;
        if (valor === 'entrevista' || valor === 'brandedplay' || valor === 'radio') {
            imagenContainer.style.display = 'none';
            videoContainer.style.display = 'block';
            imagenInput.removeAttribute('required');
            videoInput.setAttribute('required', 'required');
        } else {
            imagenContainer.style.display = 'block';
            videoContainer.style.display = 'none';
            videoInput.removeAttribute('required');
            imagenInput.setAttribute('required', 'required');
        }
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Aquí iría la lógica para enviar el formulario
    });
});
</script>
@endsection
