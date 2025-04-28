@extends('layouts.private')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow">
        <div class="card-header py-3 bg-light border-bottom">
            <h1 class="h4 mb-0 text-gray-800">Nueva Campaña de Redes Sociales</h1>
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
                            <label for="red_social" class="form-label">Red Social</label>
                            <select class="form-select" id="red_social" name="red_social" required>
                                <option value="" selected disabled>Seleccione la red social</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="twitter">Tiktok</option>
                                <option value="linkedin">LinkedIn</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="tipo_contenido" class="form-label">Tipo de Contenido</label>
                            <select class="form-select" id="tipo_contenido" name="tipo_contenido" required>
                                <option value="" selected disabled>Seleccione el tipo de contenido</option>
                                <option value="imagen">Imagen</option>
                                <option value="video">Video</option>
                                <option value="storie">Storie</option>
                                <option value="live">Live</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="creatividad" class="form-label">Imagen</label>
                            <input type="file" 
                                class="form-control" 
                                id="creatividad" 
                                name="creatividad"
                                accept="image/*"
                                data-type="imagen">
                            <small class="form-text text-muted">
                                Formatos aceptados: JPG, PNG. Tamaño máximo: 10MB
                            </small>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="video" class="form-label">Video</label>
                            <input type="file" 
                                class="form-control" 
                                id="video" 
                                name="video"
                                accept="video/*"
                                data-type="video">
                            <small class="form-text text-muted">
                                Formatos aceptados: MP4. Tamaño máximo: 50MB
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
    const inputImagen = document.getElementById('creatividad');
    const inputVideo = document.getElementById('video');

    // Función para validar que solo un archivo esté seleccionado
    function validarArchivos() {
        if (inputImagen.files.length > 0 && inputVideo.files.length > 0) {
            alert('Por favor, seleccione solo un tipo de archivo (imagen o video)');
            inputImagen.value = '';
            inputVideo.value = '';
            return false;
        }
        return true;
    }

    // Event listeners para validar la selección de archivos
    inputImagen.addEventListener('change', validarArchivos);
    inputVideo.addEventListener('change', validarArchivos);

    tipoContenido.addEventListener('change', function() {
        if (this.value === 'video') {
            inputImagen.style.display = 'none';
            inputVideo.style.display = 'block';
        } else if (this.value === 'imagen') {
            inputImagen.style.display = 'block';
            inputVideo.style.display = 'none';
        } else {
            inputImagen.style.display = 'block';
            inputVideo.style.display = 'block';
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!validarArchivos()) {
            return;
        }
        // Aquí iría la lógica para enviar el formulario
    });
});
</script>
@endsection
