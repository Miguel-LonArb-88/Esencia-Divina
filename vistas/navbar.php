<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid px-4">
        <!-- Informacion de contacto -->
        <div class="navbar-text d-none d-lg-flex align-items-center">
            <span class="me-3"><i class="far fa-envelope"></i> info@spaproject.com</span>
            <span><i class="fas fa-phone"></i> +34 912 345 678</span>
        </div>

        <!-- Redes sociales -->
        <div class="d-none d-lg-flex align-items-center ms-auto" style="gap: 10px;">
            <a href="#" class="nav-link px-2"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="nav-link px-2"><i class="fab fa-instagram"></i></a>
            <a href="#" class="nav-link px-2"><i class="fab fa-twitter"></i></a>
            <a href="#" class="btn btn-primary">Reservar Ahora</a>
        </div>
    </div>
</nav>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container" style="margin: 0px; padding: 1rem 1rem;">
        <!-- Logo -->
        <a class="navbar-brand" href="?controlador=paginas&accion=inicio">
            <img src="assets/img/logo.png" alt="Esencia divina" height="60">
        </a>

        <!-- Barra para telefonos -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Items del menu -->
        <div class="collapse navbar-collapse" id="navbarMain" style="gap: 2rem;">
            <ul class="navbar-nav mx-auto gap-4"> 
                <li class="nav-item">
                    <a class="nav-link px-3" href="?controlador=paginas&accion=inicio">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="?controlador=paginas&accion=nosotros">Quiénes Somos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="?controlador=paginas&accion=servicios">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="?controlador=paginas&accion=contacto">Contacto</a>
                </li>
            </ul>

            <!-- Busqueda y icono de carrito -->
            <div class="d-flex align-items-center">
                <a href="#" class="nav-link px-2"><i class="fas fa-search"></i></a>
                <a href="?controlador=carrito&accion=ver" class="nav-link px-2">
                    <i class="fas fa-shopping-cart"></i>

                    <!-- Contador de productos en el carrito -->
                    <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                        <span class="badge bg-danger"><?php echo count($_SESSION['carrito']); ?></span>
                    <?php endif; ?>
                </a>
                <?php 
                if(isset($_SESSION['nombre'])) {
                    echo '<div class="dropdown">';
                    echo '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">';
                    echo '<i class="fas fa-user"></i>';
                    echo '</a>';
                    echo '<div class="dropdown-menu dropdown-menu-end">';
                    echo '<span class="dropdown-item-text">Usuario: ' . $_SESSION['nombre'] . '</span>';
                    echo '<div class="dropdown-divider"></div>';
                    echo '<a class="dropdown-item" href="?controlador=usuarios&accion=perfil">Perfil</a>';
                    echo '<a class="dropdown-item" href="?controlador=usuarios&accion=reservas">Mis Servicios</a>';
                    echo '<a class="dropdown-item" href="logout.php">Cerrar Sesión</a>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="dropdown">';
                    echo '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">';
                    echo '<i class="fas fa-user"></i>';
                    echo '</a>';
                    echo '<div class="dropdown-menu dropdown-menu-end">';
                    echo '<a class="dropdown-item" href="?controlador=usuarios&accion=login">Ingresar</a>';
                    echo '<a class="dropdown-item" href="?controlador=usuarios&accion=registro">Registro</a>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</nav>