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
            <div class="year-filter">
                <span class="filter-option">2025</span>
            </div>
            
            <div class="month-filter">
                <span class="filter-option">Todo</span>
                <span class="filter-option">FEBRERO</span>
            </div>
        </div>

        <div class="download-section">
            <button class="download-button">
                Descargas 2021
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="campaigns-list" id="campaignsList">
        <!-- Aquí se mostrarán las campañas filtradas -->
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
                // Agregar console.log para debugging
                console.log('Filtrando por:', { año, mes });

                const response = await fetch(`/filtrar-campañas?año=${año}&mes=${mes}`);
                const campañas = await response.json();
                
                // Ver qué datos estamos recibiendo
                console.log('Campañas recibidas:', campañas);
                
                // Limpiar la lista actual
                campaignsList.innerHTML = '';
                
                if (campañas.length === 0) {
                    campaignsList.innerHTML = '<div class="no-results">No se encontraron campañas para este período</div>';
                    return;
                }
                
                // Mostrar las campañas filtradas
                campañas.forEach(campaña => {
                    const card = document.createElement('div');
                    card.className = 'campaign-card';
                    card.innerHTML = `
                        <h6>${campaña.nombre}</h6>
                        <p>Cliente: ${campaña.cliente_nombre}</p>
                        <p>Fecha: ${campaña.fecha_inicio} - ${campaña.fecha_fin}</p>
                        <p>Objetivo: ${new Intl.NumberFormat().format(campaña.objetivo)} impresiones</p>
                        <p>Presupuesto: ${campaña.presupuesto.monto} ${campaña.presupuesto.moneda}</p>
                        <p>Estado: ${campaña.estado}</p>
                        <p>Tipo: ${campaña.tipo}</p>
                    `;
                    campaignsList.appendChild(card);
                });

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