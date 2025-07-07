@extends('layouts.private')

@section('content')
<div class="container-fluid mt-5">
    @php
        $medidasBanners = $medidasBanners ?? [];
    @endphp

    @if(empty($medidasBanners))
        <div class="alert alert-warning">
            <strong>Advertencia:</strong> No hay medidas de banners configuradas.
        </div>
    @endif

    @if(isset($error))
        <div class="alert alert-danger">
            <strong>Error:</strong> {{ $error }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header py-3 bg-light border-bottom">
            <h1 class="h4 mb-0 text-gray-800">Nueva Campaña Display</h1>
        </div>
        <div class="card-body p-4">
            <form id="formCampaña" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="nombre_campaña" class="form-label">Nombre de Campaña</label>
                            <input type="text" 
                                class="form-control" 
                                id="nombre_campaña" 
                                name="nombre_campaña"
                                placeholder="Ingrese el nombre de la campaña"
                                required>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="cpm" class="form-label">CPM</label>
                            <select class="form-select" id="cpm" name="cpm" required>
                                <option value="" selected disabled>Seleccione el CPM</option>
                                <option value="500000">500.000</option>
                                <option value="1000000">1.000.000</option>
                                <option value="2000000">2.000.000</option>
                                <option value="5000000">5.000.000</option>
                                <option value="8000000">8.000.000</option>
                                <option value="otro">Otro...</option>
                            </select>
                            <input type="number" 
                                   class="form-control mt-2" 
                                   id="cpm_otro" 
                                   name="cpm_otro"
                                   placeholder="Ingrese el CPM deseado"
                                   style="display: none;"
                                   min="1">
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="posicion" class="form-label">Posición</label>
                            <select class="form-select" id="posicion" name="posicion" required>
                                <option value="" selected disabled>Seleccione una posición</option>
                                @foreach($medidasBanners as $key => $value)
                                    <option value="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Sección de Creatividades Desktop -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Creatividades Desktop</h5>
                            </div>
                            <div class="card-body" id="creatividadesDesktop">
                                <div class="alert alert-info">
                                    Seleccione una posición para ver las creatividades requeridas para Desktop
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Creatividades Mobile -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Creatividades Mobile</h5>
                            </div>
                            <div class="card-body" id="creatividadesMobile">
                                <div class="alert alert-info">
                                    Seleccione una posición para ver las creatividades requeridas para Mobile
                                </div>
                            </div>
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
// Debug inicial
console.log('=== DEBUGGING INFORMACIÓN ===');
const medidasBanners = @json($medidasBanners);
console.log('Medidas banners iniciales:', medidasBanners);
console.log('Posiciones disponibles:', Object.keys(medidasBanners));

function crearCampoSubida(posicion, medidas, dispositivo) {
    const div = document.createElement('div');
    div.className = 'form-group mb-3';
    
    const label = document.createElement('label');
    label.className = 'form-label';
    label.textContent = `${posicion.toUpperCase()} (${medidas})`;
    
    const input = document.createElement('input');
    input.type = 'file';
    input.className = 'form-control';
    input.name = `creatividad_${dispositivo}_${posicion}`;
    input.accept = 'image/*';
    input.required = true;
    
    const small = document.createElement('small');
    small.className = 'form-text text-muted';
    small.textContent = `Medidas requeridas: ${medidas}`;
    
    div.appendChild(label);
    div.appendChild(input);
    div.appendChild(small);
    
    return div;
}

function actualizarCreatividades() {
    const posicion = document.getElementById('posicion').value;
    console.log('=== ACTUALIZACIÓN DE CREATIVIDADES ===');
    console.log('Posición seleccionada:', posicion);
    
    const desktopSection = document.getElementById('creatividadesDesktop');
    const mobileSection = document.getElementById('creatividadesMobile');
    
    if (!medidasBanners || Object.keys(medidasBanners).length === 0) {
        console.error('No hay medidas de banners disponibles');
        desktopSection.innerHTML = '<div class="alert alert-danger">Error: No hay medidas de banners disponibles</div>';
        mobileSection.innerHTML = '<div class="alert alert-danger">Error: No hay medidas de banners disponibles</div>';
        return;
    }
    
    const medidas = medidasBanners[posicion];
    console.log('Medidas encontradas para la posición:', medidas);
    
    if (!medidas) {
        console.error('No se encontraron medidas para la posición:', posicion);
        desktopSection.innerHTML = '<div class="alert alert-danger">No se encontraron medidas para la posición seleccionada</div>';
        mobileSection.innerHTML = '<div class="alert alert-danger">No se encontraron medidas para la posición seleccionada</div>';
        return;
    }
    
    // Limpiar secciones
    desktopSection.innerHTML = '';
    mobileSection.innerHTML = '';
    
    // Procesar medidas desktop
    if (medidas.desktop) {
        if (typeof medidas.desktop === 'object') {
            Object.entries(medidas.desktop).forEach(([pos, med]) => {
                desktopSection.appendChild(crearCampoSubida(pos, med, 'desktop'));
            });
        } else {
            desktopSection.appendChild(crearCampoSubida('principal', medidas.desktop, 'desktop'));
        }
    } else {
        desktopSection.innerHTML = '<div class="alert alert-info">No hay medidas desktop para esta posición</div>';
    }
    
    // Procesar medidas mobile
    if (medidas.mobile) {
        if (typeof medidas.mobile === 'object') {
            Object.entries(medidas.mobile).forEach(([pos, med]) => {
                mobileSection.appendChild(crearCampoSubida(pos, med, 'mobile'));
            });
        } else {
            mobileSection.appendChild(crearCampoSubida('principal', medidas.mobile, 'mobile'));
        }
    } else {
        mobileSection.innerHTML = '<div class="alert alert-info">No hay medidas mobile para esta posición</div>';
    }
}

// Event listener para el cambio de posición
document.getElementById('posicion').addEventListener('change', actualizarCreatividades);

// Validación de dimensiones de imagen
async function validarDimensionesImagen(file, medidasRequeridas) {
    return new Promise((resolve) => {
        const img = new Image();
        img.onload = function() {
            const [ancho, alto] = medidasRequeridas.split('x').map(Number);
            resolve(this.width === ancho && this.height === alto);
        };
        img.src = URL.createObjectURL(file);
    });
}

// Event listener para validar dimensiones de imágenes
document.addEventListener('change', async function(e) {
    if (e.target.type === 'file' && e.target.name.startsWith('creatividad_')) {
        const file = e.target.files[0];
        if (file) {
            const medidasRequeridas = e.target.parentElement.querySelector('small').textContent.match(/\d+x\d+/)[0];
            const esValido = await validarDimensionesImagen(file, medidasRequeridas);
            if (!esValido) {
                alert(`La imagen debe tener las medidas exactas de ${medidasRequeridas}`);
                e.target.value = '';
            }
        }
    }
});

document.getElementById('cpm').addEventListener('change', function() {
    const otroInput = document.getElementById('cpm_otro');
    if (this.value === 'otro') {
        otroInput.style.display = 'block';
        otroInput.required = true;
    } else {
        otroInput.style.display = 'none';
        otroInput.required = false;
        otroInput.value = '';
    }
});
</script>
@endsection

