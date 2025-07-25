<?php
    // Destruye la sesión y redirige al usuario a la página de inicio de sesión
    session_start();
    session_destroy();
    header('Location:index.php?controlador=paginas&accion=inicio');
    exit();
?>