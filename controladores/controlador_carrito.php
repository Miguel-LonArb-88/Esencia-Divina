<?php
ob_start();
require_once(__DIR__ . '/../modelos/servicios.php');
require_once(__DIR__ . '/../modelos/spas.php');

class ControladorCarrito
{

    // Método para mostrar la vista del carrito
    public function ver()
    {
        $serviciosCarrito = [];
        $spa = null;

        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $idServicio) {
                $serviciosCarrito[] = Servicios::obtenerPorId($idServicio);
            }
            
            if (isset($_SESSION['spa_actual'])) {
                $spa = Spas::obtenerPorId($_SESSION['spa_actual']);
            }
        }
        include_once(__DIR__ . '/../vistas/paginas/carrito.php');
    }

    // Constructor de la clase
    public function __construct()
    {
        ob_start(); // Iniciar el buffer de salida para evitar errores
    }

    // Método para añadir un servicio al carrito
    public function añadir()
    {
        if (!isset($_SESSION['nombre'])) {
            ob_end_clean();
            header('Location: ?controlador=usuarios&accion=login');
            exit;
        }

        if (isset($_POST['id_servicio'])) {
            $idServicio = $_POST['id_servicio'];
            $nuevoServicio = Servicios::obtenerPorId($idServicio);
            
            if (empty($_SESSION['carrito'])) {
                $_SESSION['carrito'][] = $idServicio;
                $_SESSION['spa_actual'] = $nuevoServicio->id_spa;
            } else {
                // Validar que el servicio sea del mismo spa
                if (!Servicios::validarMismoSpa(array_merge($_SESSION['carrito'], [$idServicio]))) {
                    $_SESSION['error_carrito'] = 'Solo puedes agregar servicios del mismo spa';
                    header('Location: ?controlador=carrito&accion=ver');
                    exit;
                }
                $_SESSION['carrito'][] = $idServicio;
            }
        }

        // Limpiar el buffer de salida después de la redirección
        if (ob_get_level() > 0) {
            ob_end_clean(); // Limpiar el buffer de salida
        }
        header('Location: ?controlador=carrito&accion=ver');
        exit;
    }

    public function eliminar()
    {
        if (isset($_GET['id']) && isset($_SESSION['carrito'])) {
            $index = $_GET['id'];
            if (isset($_SESSION['carrito'][$index])) {
                unset($_SESSION['carrito'][$index]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                
                // Si el carrito queda vacío, eliminar la referencia al spa
                if (empty($_SESSION['carrito'])) {
                    unset($_SESSION['spa_actual']);
                }

            }
        }
        
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Location: ?controlador=carrito&accion=ver');
        exit;
    }

    public function procesar_pago() {
        header('Location:?controlador=pago&accion=procesar');
        exit;
    }
}

