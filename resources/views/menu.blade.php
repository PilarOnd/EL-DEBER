<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Deber Reportes</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>
<body>
    <div class="logo-container">
        <img src="{{ asset('img/logo.png') }}" alt="El Deber" class="logo">
        <h1>El Deber Reportes</h1>
    </div>

    <div class="kpi-section">
        <h2>KPI's BASE</h2>
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
        <h2>CAMPAÑAS</h2>
        
        <div class="filter-section">
            <div class="year-filter">
                <span class="filter-option">2019</span>
                <span class="filter-option">2020</span>
                <span class="filter-option active">2021</span>
            </div>
            
            <div class="month-filter">
                <span class="filter-option">Todo</span>
                <span class="filter-option">ENERO</span>
                <span class="filter-option">FEBRERO</span>
                <span class="filter-option">MARZO</span>
                <span class="filter-option">ABRIL</span>
                <span class="filter-option">MAYO</span>
                <span class="filter-option">JUNIO</span>
                <span class="filter-option active">JULIO</span>
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

    <script>
        document.querySelectorAll('.filter-option').forEach(option => {
            option.addEventListener('click', function() {
                this.parentElement.querySelectorAll('.filter-option').forEach(sib => {
                    sib.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>