<?php
    // ob_start() sirve para limpiar el buffer de salida para que no se muestren los errores
    ob_start();
    $controlador = "paginas";
    $accion = "inicio";
    global $vis;
    $vis = "invisible";

    // validar si se ha enviado un controlador y accion por GET
    if (isset($_GET['controlador']) && isset($_GET['accion'])){
        // si se ha enviado un controlador y accion por GET

        if ($_GET['controlador'] != ""  &&  $_GET['accion'] != ""){  
            // Corregir el caso común de 'usuario' a 'usuarios'
            $controlador = $_GET['controlador'] === 'usuario' ? 'usuarios' : $_GET['controlador'];
            $accion = $_GET['accion'];   
        }
    }

    // carga la plantilla
    require_once("vistas/plantilla.php"); 
    
    // limpiar el buffer de salida para que no se muestren los errores
    ob_end_flush();
?>