<?php

    // Verifica si el usuario está autenticado y es un administrador
    if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
        header("Location: ?controlador=usuarios&accion=login");
        exit();
    }

?>

<link rel="stylesheet" href="assets/estilos/admin.css">

<div class="admin-dashboard">
    <div class="container py-4">
        <div class="welcome-section mb-4">
            <h1>Panel de Administración</h1>
            <p class="text-muted">Bienvenido, <?php echo $_SESSION['nombre']; ?>. Aquí tienes un resumen de tu sistema.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stats-card bg-primary text-white">
                    <div class="stats-icon">
                        <i class="fas fa-spa fa-2x"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $totalServicios; ?></h3>
                        <p>Servicios Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card bg-success text-white">
                    <div class="stats-icon">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $totalUsuarios ?></h3>
                        <p>Usuarios Registrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card bg-info text-white">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $totalReservas ?></h3>
                        <p>Citas Pendientes</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mb-4">Gestión del Sistema</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-spa"></i>
                    </div>
                    <div class="card-content">
                        <h3>Servicios</h3>
                        <p>Gestionar servicios y tratamientos disponibles</p>
                        <div class="card-actions">
                            <a href="?controlador=admin&accion=servicios" class="btn btn-primary">Administrar</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon bg-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>Usuarios</h3>
                        <p>Gestionar usuarios y permisos del sistema</p>
                        <div class="card-actions">
                            <a href="?controlador=admin&accion=usuarios" class="btn btn-success">Administrar</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon bg-info">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-content">
                        <h3>Citas</h3>
                        <p>Ver y gestionar las citas programadas</p>
                        <div class="card-actions">
                            <a href="?controlador=admin&accion=citas" class="btn btn-info">Administrar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <h2 class="mb-4">Estadísticas</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="chartUsuarios"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="chartServicios"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para gráficos -->
<script>

// Datos para los gráficos
const chartData = <?= json_encode($datosGraficos) ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Usuarios
    new Chart(document.getElementById('chartUsuarios'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(chartData.usuarios.porTipo),
            datasets: [{
                data: Object.values(chartData.usuarios.porTipo),
                backgroundColor: ['#4e73df', '#36b9cc', '#1cc88a']
            }]
        }
    });

    // Gráfico de Servicios
    new Chart(document.getElementById('chartServicios'), {
        type: 'bar',
        data: {
            labels: ['Servicios Activos'],
            datasets: [{
                label: 'Servicios',
                data: [chartData.servicios],
                backgroundColor: '#4e73df'
            }]
        }
    });
});
</script>