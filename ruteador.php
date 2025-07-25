<?php
    // Incluye el controlador solicitado y ejecuta la acción
    include_once("controladores/controlador_".$controlador.".php");
    $objControlador = "Controlador".ucfirst($controlador);
    $controlador = new $objControlador();
    $controlador->$accion();
?>
