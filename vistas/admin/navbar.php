<nav class="navbar navbar-expand-lg custom-admin-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="?controlador=admin&accion=inicio">
            <img src="./assets/img/logo.png" alt="LaPaSo" class="admin-logo me-2">
            <span class="brand-text">Panel Admin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="?controlador=admin&accion=servicios">
                        <i class="fas fa-spa me-1"></i>Servicios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?controlador=admin&accion=usuarios">
                        <i class="fas fa-users me-1"></i>Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?controlador=admin&accion=reservas">
                        <i class="fas fa-calendar-alt me-1"></i>Reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?controlador=admin&accion=spas">
                        <i class="fas fa-hot-tub me-1"></i>Spas
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3">
                    <i class="fas fa-user-circle me-1"></i>
                    Bienvenido, <?php echo $_SESSION['nombre']; ?>
                </span>
                <a href="logout.php" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</nav>