@extends('layouts.private')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/campañas/display/create_display.css') }}">
@endpush

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
                    @if($esAdministrador && !empty($clientes))
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="" selected disabled>Seleccione el cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente['id'] }}">{{ $cliente['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
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
                    @else
                        <div class="col-md-8 mb-4">
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
                    @endif

                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="cpm" class="form-label">Cantidad de CPM</label>
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

                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="posicion" class="form-label">Espacio CPM</label>
                            <select class="form-select" id="posicion" name="posicion" required>
                                <option value="" selected disabled>Seleccione una posición</option>
                                @foreach($medidasBanners as $key => $value)
                                    <option value="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selector de Dispositivos -->
                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label class="form-label">Dispositivos de Campaña</label>
                            <div class="device-selector-container">
                                <div class="device-options">
                                    <div class="device-card">
                                        <input type="radio" id="device_desktop" name="device_target" value="desktop" class="device-radio">
                                        <label for="device_desktop" class="device-label">
                                            <div class="device-icon">
                                                <img src="{{ asset('img/computadora.png') }}" alt="Desktop" />
                                            </div>
                                            <div class="device-text">
                                                <span class="device-title">Solo Desktop</span>
                                                <span class="device-desc">Campaña únicamente para computadores</span>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="device-card">
                                        <input type="radio" id="device_both" name="device_target" value="both" class="device-radio" checked>
                                        <label for="device_both" class="device-label">
                                            <div class="device-icon">
                                                <img src="{{ asset('img/ambos.png') }}" alt="Ambos Dispositivos" />
                                            </div>
                                            <div class="device-text">
                                                <span class="device-title">Ambos Dispositivos</span>
                                                <span class="device-desc">Campaña para desktop y mobile</span>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="device-card">
                                        <input type="radio" id="device_mobile" name="device_target" value="mobile" class="device-radio">
                                        <label for="device_mobile" class="device-label">
                                            <div class="device-icon">
                                                <img src="{{ asset('img/celular.png') }}" alt="Mobile" />
                                            </div>
                                            <div class="device-text">
                                                <span class="device-title">Solo Mobile</span>
                                                <span class="device-desc">Campaña únicamente para móviles</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Creatividades Desktop -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">Creatividades Desktop</h6>
                            </div>
                            <div class="card-body py-2 px-3" id="creatividadesDesktop" style="min-height: auto;">
                                <div class="alert alert-info py-2 px-3 mb-0 small">
                                    Seleccione una posición para ver las creatividades requeridas para Desktop
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Creatividades Mobile -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">Creatividades Mobile</h6>
                            </div>
                            <div class="card-body py-2 px-3" id="creatividadesMobile" style="min-height: auto;">
                                <div class="alert alert-info py-2 px-3 mb-0 small">
                                    Seleccione una posición para ver las creatividades requeridas para Mobile
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Fechas Mejorada -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Configuración de Fechas</h5>
                            </div>
                            <div class="card-body">
                                <!-- Botones de Acceso Rápido -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Duración de Campaña</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="establecerDuracion(1)">
                                            <i class="fas fa-clock"></i> 1 Día
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="establecerDuracion(7)">
                                            <i class="fas fa-calendar-week"></i> 1 Semana
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="establecerDuracion(15)">
                                            <i class="fas fa-calendar-alt"></i> 15 Días
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="establecerDuracion(30)">
                                            <i class="fas fa-calendar"></i> 1 Mes
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="establecerDuracion(90)">
                                            <i class="fas fa-calendar"></i> 3 Meses
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="establecerDuracion(180)">
                                            <i class="fas fa-calendar"></i> 6 Meses
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="establecerDuracion(365)">
                                            <i class="fas fa-calendar"></i> 1 Año
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarFechas()">
                                            <i class="fas fa-eraser"></i> Personalizar
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Fecha y Hora de Inicio -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-8">
                                                    <label for="fecha_inicio_date" class="form-label small">Fecha de inicio*</label>
                                                    <input type="date" 
                                                        class="form-control" 
                                                        id="fecha_inicio_date"
                                                        name="fecha_inicio_date"
                                                        required>
                                                </div>
                                                <div class="col-4">
                                                    <label for="hora_inicio" class="form-label small">Hora de inicio*</label>
                                                    <select class="form-select hora-dropdown" id="hora_inicio" name="hora_inicio" required>
                                                        <option value="00:00">12:00 a.m.</option>
                                                        <option value="01:00">1:00 a.m.</option>
                                                        <option value="02:00">2:00 a.m.</option>
                                                        <option value="03:00">3:00 a.m.</option>
                                                        <option value="04:00">4:00 a.m.</option>
                                                        <option value="05:00">5:00 a.m.</option>
                                                        <option value="06:00">6:00 a.m.</option>
                                                        <option value="07:00">7:00 a.m.</option>
                                                        <option value="08:00">8:00 a.m.</option>
                                                        <option value="09:00">9:00 a.m.</option>
                                                        <option value="10:00">10:00 a.m.</option>
                                                        <option value="11:00">11:00 a.m.</option>
                                                        <option value="12:00">12:00 p.m.</option>
                                                        <option value="13:00">1:00 p.m.</option>
                                                        <option value="14:00">2:00 p.m.</option>
                                                        <option value="15:00">3:00 p.m.</option>
                                                        <option value="16:00">4:00 p.m.</option>
                                                        <option value="17:00">5:00 p.m.</option>
                                                        <option value="18:00">6:00 p.m.</option>
                                                        <option value="19:00">7:00 p.m.</option>
                                                        <option value="20:00">8:00 p.m.</option>
                                                        <option value="21:00">9:00 p.m.</option>
                                                        <option value="22:00">10:00 p.m.</option>
                                                        <option value="23:00">11:00 p.m.</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> No puede ser anterior a la fecha actual
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Fecha y Hora de Fin -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-8">
                                                    <label for="fecha_fin_date" class="form-label small">Fecha de finalización*</label>
                                                    <input type="date" 
                                                        class="form-control" 
                                                        id="fecha_fin_date"
                                                        name="fecha_fin_date"
                                                        required>
                                                </div>
                                                <div class="col-4">
                                                    <label for="hora_fin" class="form-label small">Hora de finalización*</label>
                                                    <select class="form-select hora-dropdown" id="hora_fin" name="hora_fin" required>
                                                        <option value="00:59">12:59 a.m.</option>
                                                        <option value="01:59">1:59 a.m.</option>
                                                        <option value="02:59">2:59 a.m.</option>
                                                        <option value="03:59">3:59 a.m.</option>
                                                        <option value="04:59">4:59 a.m.</option>
                                                        <option value="05:59">5:59 a.m.</option>
                                                        <option value="06:59">6:59 a.m.</option>
                                                        <option value="07:59">7:59 a.m.</option>
                                                        <option value="08:59">8:59 a.m.</option>
                                                        <option value="09:59">9:59 a.m.</option>
                                                        <option value="10:59">10:59 a.m.</option>
                                                        <option value="11:59">11:59 a.m.</option>
                                                        <option value="12:59">12:59 p.m.</option>
                                                        <option value="13:59">1:59 p.m.</option>
                                                        <option value="14:59">2:59 p.m.</option>
                                                        <option value="15:59">3:59 p.m.</option>
                                                        <option value="16:59">4:59 p.m.</option>
                                                        <option value="17:59">5:59 p.m.</option>
                                                        <option value="18:59">6:59 p.m.</option>
                                                        <option value="19:59">7:59 p.m.</option>
                                                        <option value="20:59">8:59 p.m.</option>
                                                        <option value="21:59">9:59 p.m.</option>
                                                        <option value="22:59">10:59 p.m.</option>
                                                        <option value="23:59" selected>11:59 p.m.</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> Debe ser igual o posterior a la fecha de inicio
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Campos ocultos para mantener compatibilidad -->
                                    <input type="hidden" id="fecha_inicio" name="fecha_inicio">
                                    <input type="hidden" id="fecha_fin" name="fecha_fin">
                                </div>

                                <!-- Información de Duración -->
                                <div id="infoDuracion" class="alert alert-info d-none">
                                    <i class="fas fa-calendar-check"></i>
                                    <span id="textoDuracion"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-light me-2">Cancelar</button>
                    <button type="button" id="btnGuardar" class="btn btn-dark">Guardar Campaña</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
console.log('🔧 JavaScript cargado - create_display.blade.php');

// Script inteligente para controlar altura del dropdown sin afectar layout
document.addEventListener('DOMContentLoaded', function() {
    const selectElements = document.querySelectorAll('.hora-dropdown');
    
    selectElements.forEach(select => {
        // Crear un contenedor overlay para el dropdown expandido
        const container = select.parentElement;
        container.style.position = 'relative';
        
        select.addEventListener('mousedown', function(e) {
            // Si ya está abierto, permitir comportamiento normal
            if (this.size > 1) return;
            
            // Prevenir el comportamiento por defecto
            e.preventDefault();
            
            // Crear overlay temporal
            const overlay = this.cloneNode(true);
            overlay.size = 6;
            overlay.style.position = 'absolute';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.zIndex = '1001';
            overlay.style.backgroundColor = 'white';
            overlay.style.border = '1px solid #dee2e6';
            overlay.style.borderRadius = '0.375rem';
            overlay.style.boxShadow = '0 0.125rem 0.25rem rgba(0, 0, 0, 0.075)';
            
            // Ocultar el original temporalmente
            this.style.visibility = 'hidden';
            
            // Agregar overlay al contenedor
            container.appendChild(overlay);
            
            // Focus en el overlay
            overlay.focus();
            
            // Cuando se selecciona una opción en el overlay
            overlay.addEventListener('change', () => {
                this.value = overlay.value;
                this.style.visibility = 'visible';
                container.removeChild(overlay);
                
                // Disparar evento change en el original
                this.dispatchEvent(new Event('change'));
            });
            
            // Cuando se pierde el focus del overlay
            overlay.addEventListener('blur', () => {
                setTimeout(() => {
                    if (container.contains(overlay)) {
                        this.style.visibility = 'visible';
                        container.removeChild(overlay);
                    }
                }, 150);
            });
        });
    });
});

// JavaScript para los botones de radio visuales de dispositivos
document.addEventListener('DOMContentLoaded', function() {
    const deviceRadios = document.querySelectorAll('.device-radio');
    const desktopSection = document.querySelector('.col-md-6:has(#creatividadesDesktop)');
    const mobileSection = document.querySelector('.col-md-6:has(#creatividadesMobile)');
    
    // Función para mostrar/ocultar secciones según el dispositivo seleccionado
    function updateDeviceSections(selectedValue) {
        if (selectedValue === 'desktop') {
            desktopSection.style.display = 'block';
            mobileSection.style.display = 'none';
        } else if (selectedValue === 'mobile') {
            desktopSection.style.display = 'none';
            mobileSection.style.display = 'block';
        } else { // both
            desktopSection.style.display = 'block';
            mobileSection.style.display = 'block';
        }
    }
    
    // Event listeners para los radio buttons
    deviceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateDeviceSections(this.value);
            }
        });
    });
    
    // Inicializar con el estado por defecto (both)
    updateDeviceSections('both');
});
</script>

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

// Configurar fechas mínimas
function configurarFechas() {
    const fechaInicioDate = document.getElementById('fecha_inicio_date');
    const fechaFinDate = document.getElementById('fecha_fin_date');
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    
    // Obtener fecha actual
    const ahora = new Date();
    const fechaActual = ahora.toISOString().slice(0, 10);
    
    // Establecer fecha mínima para fecha de inicio (no puede ser anterior a hoy)
    fechaInicioDate.min = fechaActual;
    
    // Event listeners para actualizar campos ocultos y validaciones
    function actualizarCamposOcultos() {
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');
        
        if (fechaInicioDate.value && horaInicio.value) {
            fechaInicio.value = fechaInicioDate.value + 'T' + horaInicio.value;
        }
        
        if (fechaFinDate.value && horaFin.value) {
            fechaFin.value = fechaFinDate.value + 'T' + horaFin.value;
        }
        
        // Actualizar información de duración
        mostrarInfoDuracion();
    }
    
    // Event listener para fecha de inicio
    fechaInicioDate.addEventListener('change', function() {
        if (this.value) {
            // La fecha de fin no puede ser anterior a la fecha de inicio
            fechaFinDate.min = this.value;
            
            // Si la fecha de fin ya está seleccionada y es anterior a la nueva fecha de inicio, limpiarla
            if (fechaFinDate.value && fechaFinDate.value < this.value) {
                fechaFinDate.value = '';
                horaFin.value = '23:59';
            }
        }
        actualizarCamposOcultos();
    });
    
    // Event listener para fecha de fin
    fechaFinDate.addEventListener('change', function() {
        if (this.value && fechaInicioDate.value && this.value < fechaInicioDate.value) {
            alert('La fecha de fin no puede ser anterior a la fecha de inicio');
            this.value = '';
            return;
        }
        actualizarCamposOcultos();
    });
    
    // Event listeners para las horas
    horaInicio.addEventListener('change', actualizarCamposOcultos);
    horaFin.addEventListener('change', actualizarCamposOcultos);
}

// Inicializar configuración de fechas cuando se carga la página
document.addEventListener('DOMContentLoaded', configurarFechas);

// Función para establecer duración de campaña con botones rápidos
function establecerDuracion(dias) {
    const fechaInicioDate = document.getElementById('fecha_inicio_date');
    const fechaFinDate = document.getElementById('fecha_fin_date');
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    
    // Obtener fecha actual
    const ahora = new Date();
    const fechaActual = ahora.toISOString().slice(0, 10);
    const horaActual = ahora.getHours().toString().padStart(2, '0') + ':00';
    
    // Establecer fecha de inicio como hoy
    fechaInicioDate.value = fechaActual;
    horaInicio.value = horaActual;
    
    // Calcular fecha de fin
    const fechaFinCalculada = new Date(ahora);
    fechaFinCalculada.setDate(fechaFinCalculada.getDate() + dias - 1); // -1 porque incluye el día de inicio
    
    fechaFinDate.value = fechaFinCalculada.toISOString().slice(0, 10);
    horaFin.value = '23:59'; // Terminar a las 11:59 PM
    
    // Actualizar campos ocultos
    document.getElementById('fecha_inicio').value = fechaInicioDate.value + 'T' + horaInicio.value;
    document.getElementById('fecha_fin').value = fechaFinDate.value + 'T' + horaFin.value;
    
    // Actualizar información de duración
    mostrarInfoDuracion();
    
    // Resaltar el botón seleccionado
    resaltarBotonSeleccionado(event.target);
}

// Función para limpiar fechas y permitir personalización
function limpiarFechas() {
    document.getElementById('fecha_inicio_date').value = '';
    document.getElementById('fecha_fin_date').value = '';
    document.getElementById('hora_inicio').value = '00:00';
    document.getElementById('hora_fin').value = '23:59';
    document.getElementById('fecha_inicio').value = '';
    document.getElementById('fecha_fin').value = '';
    document.getElementById('infoDuracion').classList.add('d-none');
    
    // Quitar resaltado de todos los botones
    document.querySelectorAll('.btn-outline-primary, .btn-primary').forEach(btn => {
        if (btn.onclick && btn.onclick.toString().includes('establecerDuracion')) {
            btn.className = btn.className.replace('btn-primary', 'btn-outline-primary');
        }
    });
    
    // Resaltar botón personalizar
    resaltarBotonSeleccionado(event.target);
}

// Función para resaltar el botón seleccionado
function resaltarBotonSeleccionado(botonSeleccionado) {
    // Quitar resaltado de todos los botones de duración
    document.querySelectorAll('.btn-outline-primary, .btn-primary, .btn-outline-secondary, .btn-secondary').forEach(btn => {
        if (btn.onclick && (btn.onclick.toString().includes('establecerDuracion') || btn.onclick.toString().includes('limpiarFechas'))) {
            if (btn.classList.contains('btn-primary')) {
                btn.className = btn.className.replace('btn-primary', 'btn-outline-primary');
            }
            if (btn.classList.contains('btn-secondary')) {
                btn.className = btn.className.replace('btn-secondary', 'btn-outline-secondary');
            }
        }
    });
    
    // Resaltar el botón seleccionado
    if (botonSeleccionado.classList.contains('btn-outline-primary')) {
        botonSeleccionado.className = botonSeleccionado.className.replace('btn-outline-primary', 'btn-primary');
    } else if (botonSeleccionado.classList.contains('btn-outline-secondary')) {
        botonSeleccionado.className = botonSeleccionado.className.replace('btn-outline-secondary', 'btn-secondary');
    }
}

// Función para mostrar información de duración
function mostrarInfoDuracion() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    
    if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        // Calcular la diferencia en días de forma más precisa
        const inicioSoloFecha = new Date(inicio.getFullYear(), inicio.getMonth(), inicio.getDate());
        const finSoloFecha = new Date(fin.getFullYear(), fin.getMonth(), fin.getDate());
        const diferenciaMilisegundos = finSoloFecha - inicioSoloFecha;
        const diferenciaDias = Math.round(diferenciaMilisegundos / (1000 * 60 * 60 * 24)) + 1;
        
        let textoInfo = `Duración: ${diferenciaDias} día${diferenciaDias > 1 ? 's' : ''}`;
        
        // Agregar información adicional según la duración
        if (diferenciaDias === 1) {
            textoInfo += ' (Campaña de un día)';
        } else if (diferenciaDias <= 7) {
            textoInfo += ' (Campaña semanal)';
        } else if (diferenciaDias <= 31) {
            textoInfo += ` (Aproximadamente ${Math.ceil(diferenciaDias / 7)} semana${Math.ceil(diferenciaDias / 7) > 1 ? 's' : ''})`;
        } else if (diferenciaDias <= 365) {
            textoInfo += ` (Aproximadamente ${Math.ceil(diferenciaDias / 30)} mes${Math.ceil(diferenciaDias / 30) > 1 ? 'es' : ''})`;
        } else {
            textoInfo += ` (Aproximadamente ${Math.ceil(diferenciaDias / 365)} año${Math.ceil(diferenciaDias / 365) > 1 ? 's' : ''})`;
        }
        
        document.getElementById('textoDuracion').textContent = textoInfo;
        document.getElementById('infoDuracion').classList.remove('d-none');
    } else {
        document.getElementById('infoDuracion').classList.add('d-none');
    }
}

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

// Manejar envío del formulario vía AJAX
console.log('📝 Registrando event listener para el botón guardar...');
const btnGuardar = document.getElementById('btnGuardar');
const formulario = document.getElementById('formCampaña');
console.log('📝 Botón encontrado:', btnGuardar);
console.log('📝 Formulario encontrado:', formulario);

if (btnGuardar && formulario) {
    btnGuardar.addEventListener('click', async function(e) {
    e.preventDefault();
    
    // Debug: Confirmar que el evento se está ejecutando
    console.log('🚀 BOTÓN PRESIONADO - Evento click capturado');
    
    const form = formulario;
    const submitBtn = this; // El botón que se presionó
    const originalBtnText = submitBtn.textContent;
    
    // Deshabilitar botón y mostrar loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    
    // Limpiar mensajes de error previos
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    document.querySelectorAll('.alert').forEach(el => {
        if (el.classList.contains('alert-danger') || el.classList.contains('alert-success')) {
            el.remove();
        }
    });
    
    try {
        // Debug: Mostrar información del formulario
        console.log('=== ENVIANDO FORMULARIO ===');
        console.log('Formulario:', form);
        
        // Validar que todos los archivos requeridos estén presentes
        const posicion = document.getElementById('posicion').value;
        const deviceTarget = document.querySelector('input[name="device_target"]:checked').value;
        
        console.log('Posición:', posicion);
        console.log('Device Target:', deviceTarget);
        
        if (posicion && !validarArchivosRequeridos(posicion, deviceTarget)) {
            throw new Error('Faltan creatividades requeridas para la posición seleccionada');
        }
        
        // Crear FormData
        const formData = new FormData(form);
        
        // Agregar token CSRF
        formData.append('_token', '{{ csrf_token() }}');
        
        // Debug: Mostrar datos del formulario
        console.log('=== DATOS DEL FORMULARIO ===');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Enviar datos
        console.log('Enviando a:', '{{ route("campañas.display.store") }}');
        const response = await fetch('{{ route("campañas.display.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Response status:', response.status);
        console.log('Response:', response);
        
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            if (response.status === 422) {
                // Error de validación
                const errorData = await response.json();
                console.log('Errores de validación:', errorData.errors);
                mostrarErroresValidacion(errorData.errors);
                return;
            } else {
                // Otro tipo de error HTTP
                const errorText = await response.text();
                throw new Error(`Error HTTP ${response.status}: ${errorText}`);
            }
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            // Mostrar mensaje de éxito
            mostrarMensaje('success', data.message);
            
            // Redireccionar después de un breve delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            throw new Error(data.message || 'Error al crear la campaña');
        }
        
    } catch (error) {
        console.error('=== ERROR CAPTURADO ===');
        console.error('Error completo:', error);
        console.error('Mensaje:', error.message);
        console.error('Stack:', error.stack);
        
        // Mostrar mensaje de error general
        mostrarMensaje('danger', error.message || 'Error al crear la campaña');
    } finally {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.textContent = originalBtnText;
    }
    });
} else {
    console.error('❌ ERROR: No se encontró el botón o formulario');
    console.error('Botón:', btnGuardar);
    console.error('Formulario:', formulario);
}

// Función para validar archivos requeridos
function validarArchivosRequeridos(posicion, deviceTarget) {
    const medidasBanners = @json($medidasBanners);
    const medidas = medidasBanners[posicion];
    
    if (!medidas) return true; // Si no hay medidas definidas, no validar
    
    let archivosRequeridos = [];
    
    // Determinar qué archivos se requieren según el dispositivo
    if (deviceTarget === 'desktop' || deviceTarget === 'both') {
        if (medidas.desktop) {
            if (typeof medidas.desktop === 'object') {
                Object.keys(medidas.desktop).forEach(pos => {
                    archivosRequeridos.push(`creatividad_desktop_${pos}`);
                });
            } else {
                archivosRequeridos.push('creatividad_desktop_principal');
            }
        }
    }
    
    if (deviceTarget === 'mobile' || deviceTarget === 'both') {
        if (medidas.mobile) {
            if (typeof medidas.mobile === 'object') {
                Object.keys(medidas.mobile).forEach(pos => {
                    archivosRequeridos.push(`creatividad_mobile_${pos}`);
                });
            } else {
                archivosRequeridos.push('creatividad_mobile_principal');
            }
        }
    }
    
    // Verificar que todos los archivos requeridos estén presentes
    for (const nombreArchivo of archivosRequeridos) {
        const input = document.querySelector(`input[name="${nombreArchivo}"]`);
        if (!input || !input.files || input.files.length === 0) {
            return false;
        }
    }
    
    return true;
}

// Función para mostrar mensajes
function mostrarMensaje(tipo, mensaje) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <strong>${tipo === 'success' ? 'Éxito:' : 'Error:'}</strong> ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insertar al inicio del contenido del card
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto-dismiss después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Función para mostrar errores de validación
function mostrarErroresValidacion(errors) {
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errors[field][0];
            
            input.parentNode.appendChild(feedback);
        }
    });
    
    // Mostrar mensaje general
    mostrarMensaje('danger', 'Por favor corrige los errores en el formulario');
}
</script>
@endsection

