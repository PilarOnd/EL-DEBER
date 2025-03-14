<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Deber Reportes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>
<body>
    <div class="logo-container">
        <img src="{{ asset('img/logo.png') }}" alt="El Deber" class="logo">
        <h3>El Deber Reportes</h3>
    </div>

    <div class="kpi-section">
        <ul class="kpi-list">
            <li>CPC = Costo x Clic.</li>
            <li>CPE = Costo por Interacción única por usuario.</li>
            <li>CPV = Costo x View o Costo por reproducción de video.</li>
            <li>CPI = Costo por Instalación de una aplicación o software.</li>
            <li>CPA = Costo x Acción. Ejemplo: leads, descarga de app, compra online.</li>
            <li>CPM = Costo x mil impresiones.</li>
        </ul>
    </div>

    <div class="campaigns-section">
        <h5>CAMPAÑAS</h5>
        
        <div class="filter-section">
            <!-- Skeleton loading para los filtros -->
            <div id="loading-filters" class="loading">
                <div class="skeleton filter"></div>
                <div class="skeleton filter"></div>
                <div class="skeleton filter"></div>
            </div>

            <div id="filters-content" style="display: none;">
                <div class="year-filter">
                    <span class="filter-option active">2025</span>
                </div>
                
                <div class="month-filter">
                    <span class="filter-option">Todo</span>
                    <span class="filter-option active">FEBRERO</span>
                </div>
            </div>
        </div>

        <div class="campaigns-list" id="campaignsList">
            <!-- Skeleton loading para las campañas -->
            <div id="loading-campaigns" class="loading">
                <div class="skeleton title"></div>
                <div class="skeleton"></div>
                <div class="skeleton"></div>
            </div>
        </div>

        <div class="download-section">
            <button class="download-button">
                Descargas 2025
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearFilter = document.querySelector('.year-filter');
        const monthFilter = document.querySelector('.month-filter');
        const campaignsList = document.getElementById('campaignsList');

        // Función para actualizar las campañas
        async function actualizarCampañas() {
            const año = yearFilter.querySelector('.active').textContent;
            const mes = monthFilter.querySelector('.active').textContent;

            try {
                const response = await fetch(`/filtrar-campañas?año=${año}&mes=${mes}`);
                const campañas = await response.json();
                
                campaignsList.innerHTML = '';
                
                if (campañas.length === 0) {
                    campaignsList.innerHTML = '<div class="no-results">No se encontraron campañas para este período</div>';
                    return;
                }

                // Crear el dropdown
                const dropdownContainer = document.createElement('div');
                dropdownContainer.className = 'campaigns-dropdown';

                // Crear el header del dropdown
                const dropdownHeader = document.createElement('div');
                dropdownHeader.className = 'dropdown-header';
                dropdownHeader.innerHTML = `
                    <h6>${año}</h6>
                    <span class="dropdown-arrow">▼</span>
                `;

                // Crear el contenido del dropdown
                const dropdownContent = document.createElement('div');
                dropdownContent.className = 'dropdown-content';

                // Agregar las campañas como enlaces
                campañas.forEach(campaña => {
                    const campaignLink = document.createElement('a');
                    campaignLink.href = `/campañas/${campaña.id}/todas`; // Ajusta la ruta según tu estructura
                    campaignLink.className = 'campaign-link';
                    campaignLink.innerHTML = `
                        ${campaña.nombre}
                        <span class="external-link-icon">↗</span>
                    `;
                    dropdownContent.appendChild(campaignLink);
                });

                // Agregar todo al contenedor
                dropdownContainer.appendChild(dropdownHeader);
                dropdownContainer.appendChild(dropdownContent);
                campaignsList.appendChild(dropdownContainer);

                // Agregar funcionalidad de toggle al dropdown
                dropdownHeader.addEventListener('click', () => {
                    dropdownContent.classList.toggle('open');
                    dropdownHeader.querySelector('.dropdown-arrow').textContent = 
                        dropdownContent.classList.contains('open') ? '▼' : '▲';
                });

                // Abrir el dropdown por defecto
                dropdownContent.classList.add('open');

                // Actualizar el texto del botón de descargas
                const downloadButton = document.querySelector('.download-button');
                downloadButton.textContent = `Descargas ${año}`;
                
                // Agregar el ícono SVG de nuevo
                downloadButton.innerHTML += `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
                    </svg>
                `;

            } catch (error) {
                console.error('Error al filtrar campañas:', error);
                campaignsList.innerHTML = '<div class="error">Error al cargar las campañas</div>';
            }
        }

        // Eventos para los filtros
        yearFilter.querySelectorAll('.filter-option').forEach(option => {
            option.addEventListener('click', function() {
                console.log('Año seleccionado:', this.textContent);
                this.parentElement.querySelectorAll('.filter-option').forEach(sib => {
                    sib.classList.remove('active');
                });
                this.classList.add('active');
                actualizarCampañas();
            });
        });

        monthFilter.querySelectorAll('.filter-option').forEach(option => {
            option.addEventListener('click', function() {
                console.log('Mes seleccionado:', this.textContent);
                this.parentElement.querySelectorAll('.filter-option').forEach(sib => {
                    sib.classList.remove('active');
                });
                this.classList.add('active');
                actualizarCampañas();
            });
        });

        // Cargar campañas iniciales
        actualizarCampañas();
    });
    </script>
</body>
</html>