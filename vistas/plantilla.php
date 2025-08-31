<?php
// Inicia la sesion
session_start();

// Variable global
global $controlador, $accion;

// Quita el navbar y footer en el login y registro
$mostrarNavbarYFooter = !(
    $controlador === 'usuarios' &&
    ($accion === 'login' || $accion === 'registro')
);

// Verifica si el usuario es admin
$esAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
?>


<!doctype html>
<html lang="es">

<head>
    <title>Esencia Divina</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/logo1.jpg">
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />

    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Fuente de la pagina -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">


    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="./assets/estilos/login.css">
    <link rel="stylesheet" href="./assets/estilos/styles.css">

    <?php if ($esAdmin && $controlador === 'admin'): ?>
        <!-- Estilos específicos para el panel de administración -->
        <link rel="stylesheet" href="./assets/estilos/admin.css">
    <?php endif; ?>

    <!-- Para los graficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="contenido">

        <!-- Navbar y footer -->
        <?php if ($mostrarNavbarYFooter): ?>
            <header>
                <div>
                    <?php
                    if ($esAdmin && $controlador === 'admin') {
                        include_once("vistas/admin/navbar.php");
                    } else {
                        include_once("navbar.php");
                    }
                    ?>
                </div>
            </header>
        <?php endif; ?>
        <main>
            <div>
                <?php include_once("ruteador.php"); ?>
            </div>
        </main>
    </div>
    <?php if ($mostrarNavbarYFooter): ?>
        <footer>
            <div>
                <?php include_once("footer.php"); ?>
            </div>
        </footer>
    <?php endif; ?>

    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>