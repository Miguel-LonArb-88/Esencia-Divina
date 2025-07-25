<?php

include_once("conexion.php");
DB::CrearInstancia();

class ControladorAdmin{

    // Constructor de la clase
    public function __construct() {
        if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
            header("Location: ?controlador=usuarios&accion=login");
            exit();
        }
    }

    // Método para mostrar la página de inicio del administrador
    public function inicio(){
        // Incluir modelos necesarios
        include_once("modelos/servicios.php");
        include_once("modelos/usuario.php");
        include_once("modelos/reservas.php");
        
        
        // Obtener estadísticas
        $servicios = Servicios::listarServicios();
        $totalServicios = count($servicios);

        // Obtener datos para el gráfico
        $usuarios = Usuario::listarUsuarios();
        $totalUsuarios = count($usuarios);

        // Obtener datos para el gráfico de reservas
        $reservas = Reservas::listarReservas();
        $totalReservas = count($reservas);

        // Preparar datos para el gráfico
        $datosGraficos = [
            'usuarios' => [
                'total' => $totalUsuarios,
                'porTipo' => array_count_values(array_column($usuarios, 'tipo'))
            ],
            'servicios' => $totalServicios,
            'reservas' => $totalReservas
        ];

        include_once("vistas/admin/inicio.php");
    }

    // Método para mostrar la página de usuarios
    public function usuarios(){
        include_once("modelos/usuario.php");

        // Manejar solicitudes AJAX
        if (isset($_GET['action'])) {
            header('Content-Type: application/json');
            
            switch ($_GET['action']) {
                case 'obtener':
                    $id = $_GET['id'];
                    $usuario = Usuario::obtenerPorId($id);
                    if ($usuario) {
                        echo json_encode([
                            'success' => true,
                            'usuario' => [
                                'id_usuario' => $usuario->id,
                                'nombre' => $usuario->nombre,
                                'email' => $usuario->email,
                                'tipo' => $usuario->tipo
                            ]
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
                    }
                    exit;
                    
                case 'eliminar':
                    $id = $_GET['id'];
                    try {
                        Usuario::eliminar($id);
                        echo json_encode(['success' => true]);
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    }
                    exit;
            }
        }

        // Manejar eliminación de usuario
        if (isset($_GET['eliminar'])) {
            $id = $_GET['eliminar'];
            try {
                Usuario::eliminar($id);
                $_SESSION['mensaje_exito'] = "Usuario eliminado correctamente.";
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = $e->getMessage();
            }
            header("Location: ?controlador=admin&accion=usuarios");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            $action = $_POST['action'] ?? 'crear';
            
            try {
                if ($action === 'crear') {
                    $nombre = $_POST['nombre'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $tipo = $_POST['tipo'];
                    
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    Usuario::register($nombre, $email, $hash, $tipo);
                    echo json_encode(['success' => true]);
                } elseif ($action === 'actualizar') {
                    $id = $_POST['usuarioId'];
                    $nombre = $_POST['nombre'];
                    $email = $_POST['email'];
                    $tipo = $_POST['tipo'];
                    $password = !empty($_POST['password']) ? $_POST['password'] : null;
                    
                    Usuario::actualizar($id, $nombre, $email, $tipo, $password);
                    echo json_encode(['success' => true]);
                } elseif ($action === 'editar') {
                    // Mantener compatibilidad con el código existente
                    $id = $_POST['id'];
                    $nombre = $_POST['nombre'];
                    $email = $_POST['email'];
                    $tipo = $_POST['tipo'];
                    $password = !empty($_POST['password']) ? $_POST['password'] : null;
                    
                    Usuario::actualizar($id, $nombre, $email, $tipo, $password);
                    http_response_code(200);
                    exit();
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }

        // Obtener lista de usuarios
        $usuarios = Usuario::listarUsuarios();

        include_once("vistas/admin/usuarios/inicio.php");
    }

    public function servicios(){
        // Incluir modelos necesarios
        include_once("modelos/servicios.php");
        include_once("modelos/spas.php");

        // Manejar solicitudes AJAX
        if (isset($_GET['action'])) {
            header('Content-Type: application/json');
            
            switch ($_GET['action']) {
                case 'obtener':
                    $id = $_GET['id'];
                    $servicio = Servicios::obtenerPorId($id);
                    if ($servicio) {
                        echo json_encode([
                            'success' => true,
                            'servicio' => [
                                'id_servicio' => $servicio->id_servicio,
                                'nombre' => $servicio->nombre,
                                'descripcion' => $servicio->descripcion,
                                'precio' => $servicio->precio,
                                'duracion' => $servicio->duracion,
                                'id_spa' => $servicio->id_spa
                            ]
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Servicio no encontrado']);
                    }
                    exit;
                    
                case 'eliminar':
                    $id = $_GET['id'];
                    try {
                        Servicios::borrarServicio($id);
                        echo json_encode(['success' => true]);
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    }
                    exit;
            }
        }

        // Manejar eliminación de servicio
        if (isset($_GET['eliminar'])) {
            $id = $_GET['eliminar'];
            try {
                Servicios::borrarServicio($id);
                $_SESSION['mensaje_exito'] = "Servicio eliminado correctamente.";
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = $e->getMessage();
            }
            header("Location:?controlador=admin&accion=servicios");
            exit();
        }

        // Verificar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            $action = $_POST['action'] ?? 'crear';
            
            try {
                if ($action === 'crear') {
                    $nombre = $_POST['nombre'];
                    $descripcion = $_POST['descripcion'];
                    $precio = $_POST['precio'];
                    $duracion = $_POST['duracion'] ?? null;
                    $categoria = $_POST['categoria'] ?? null;
                    $id_spa = $_POST['id_spa'] ?? 1;
                    
                    Servicios::guardaServicio($nombre, $descripcion, $precio, $id_spa, $duracion, $categoria);
                    echo json_encode(['success' => true]);
                } elseif ($action === 'actualizar') {
                    $id = $_POST['servicioId'];
                    $nombre = $_POST['nombre'];
                    $descripcion = $_POST['descripcion'];
                    $precio = $_POST['precio'];
                    $duracion = $_POST['duracion'] ?? null;
                    $id_spa = $_POST['id_spa'] ?? 1;
                    
                    Servicios::editarServicio($id, $nombre, $descripcion, $precio, $id_spa, $duracion);
                    echo json_encode(['success' => true]);
                } elseif ($action === 'editar') {
                    // Mantener compatibilidad con el código existente
                    $id = $_POST['id'];
                    $nombre = $_POST['nombre'];
                    $descripcion = $_POST['descripcion'];
                    $precio = $_POST['precio'];
                    $duracion = $_POST['duracion'] ?? null;
                    
                    // Actualizar el servicio
                    Servicios::editarServicio($id, $nombre, $descripcion, $precio, 1, $duracion); // id_spa = 1 por defecto
                    
                    // Responder con éxito para AJAX
                    http_response_code(200);
                    exit();
                } else {
                    // Agregar nuevo servicio (compatibilidad)
                    $nombre = $_POST['nombre'];
                    $descripcion = $_POST['descripcion'];
                    $precio = $_POST['precio'];
                    $duracion = $_POST['duracion'] ?? null;
                    $categoria = $_POST['categoria'] ?? null;

                    // Registrar el servicio
                    Servicios::guardaServicio($nombre, $descripcion, $precio, 1, $duracion, $categoria); // id_spa = 1 por defecto

                    // Redirigir a la lista de servicios
                    header("Location:?controlador=admin&accion=servicios");
                    exit();
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }

        // Obtener lista de servicios
        $servicios = Servicios::listarServicios();
        $spas = Spas::listarSpas();

        include_once("vistas/admin/servicios/inicio.php");
    }

    // Método para mostrar la página de spas
    public function spas() {
        include_once("modelos/spas.php");
    
        // Manejar solicitudes AJAX
        if (isset($_GET['action'])) {
            header('Content-Type: application/json');
            
            switch ($_GET['action']) {
                case 'obtener':
                    $id = $_GET['id'];
                    $spa = Spas::obtenerPorId($id);
                    if ($spa) {
                        echo json_encode([
                            'success' => true,
                            'spa' => [
                                'id_spa' => $spa->id_spa,
                                'nombre' => $spa->nombre,
                                'direccion' => $spa->direccion,
                                'telefono' => $spa->telefono,
                                'email' => $spa->email
                            ]
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Spa no encontrado']);
                    }
                    exit;
                    
                case 'eliminar':
                    $id = $_GET['id'];
                    try {
                        Spas::eliminar($id);
                        echo json_encode(['success' => true]);
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    }
                    exit;
            }
        }
    
        // Manejar eliminación de spa
        if (isset($_GET['eliminar'])) {
            $id = $_GET['eliminar'];
            try {
                Spas::eliminar($id);
                $_SESSION['mensaje_exito'] = "Spa eliminado correctamente.";
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = $e->getMessage();
            }
            header("Location: ?controlador=admin&accion=spas");
            exit();
        }

        // Verificar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            $action = $_POST['action'] ?? 'crear';
            
            try {
                if ($action === 'crear') {
                    $nombre = $_POST['nombre'];
                    $direccion = $_POST['direccion'];
                    $telefono = $_POST['telefono'];
                    $email = $_POST['email'];
                    
                    Spas::guardarSpa($nombre, $direccion, $telefono, $email);
                    echo json_encode(['success' => true]);
                } elseif ($action === 'actualizar') {
                    $id = $_POST['spaId'];
                    $nombre = $_POST['nombre'];
                    $direccion = $_POST['direccion'];
                    $telefono = $_POST['telefono'];
                    $email = $_POST['email'];
                    
                    Spas::actualizar($id, $nombre, $direccion, $telefono, $email);
                    echo json_encode(['success' => true]);
                } elseif ($action === 'editar') {
                    // Mantener compatibilidad con el código existente
                    $id = $_POST['id'];
                    $nombre = $_POST['nombre'];
                    $direccion = $_POST['direccion'] ?? '';
                    $telefono = $_POST['telefono'] ?? '';
                    $email = $_POST['email'] ?? '';
                    
                    // Actualizar el spa
                    Spas::actualizar($id, $nombre, $direccion, $telefono, $email);
                    
                    // Responder con éxito para AJAX
                    http_response_code(200);
                    exit();
                } else {
                    // Agregar nuevo spa (compatibilidad)
                    $nombre = $_POST['nombre'] ?? '';
                    $direccion = $_POST['direccion'] ?? '';
                    $telefono = $_POST['telefono'] ?? '';
                    $email = $_POST['email'] ?? '';
            
                    Spas::guardarSpa($nombre, $direccion, $telefono, $email);
                    header("Location:?controlador=admin&accion=spas");
                    exit();
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }
    
        // Obtener lista de spas
        $spas = Spas::listarSpas();
        include_once("vistas/admin/spas/inicio.php");
    }

    // Método para mostrar la página de reservas
    public function reservas() {
        include_once("modelos/reservas.php");
        include_once("modelos/usuario.php");
        include_once("modelos/servicios.php");
        include_once("modelos/spas.php");

        // Manejar solicitudes AJAX
        if (isset($_GET['action'])) {
            header('Content-Type: application/json');
            
            switch ($_GET['action']) {
                case 'obtener_detalles':
                    $id = $_GET['id'];
                    $reserva = Reservas::obtenerDetallesCompletos($id);
                    if ($reserva) {
                        echo json_encode([
                            'success' => true,
                            'reserva' => $reserva
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Reserva no encontrada']);
                    }
                    exit;
                    
                case 'eliminar':
                    $id = $_GET['id'];
                    try {
                        Reservas::eliminar($id);
                        echo json_encode(['success' => true]);
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    }
                    exit;
            }
        }

        // Manejar peticiones POST para cambio de estado (AJAX)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'cambiar_estado') {
                header('Content-Type: application/json');
                
                try {
                    $id_reserva = $_POST['reservaIdEstado'];
                    $nuevo_estado = $_POST['nuevoEstado'];
                    
                    Reservas::actualizarEstado($id_reserva, $nuevo_estado);
                    echo json_encode(['success' => true]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                exit;
            } elseif (isset($_POST['cambiar_estado']) && isset($_POST['estado'])) {
                $id_reserva = $_POST['cambiar_estado'];
                $nuevo_estado = $_POST['estado'];
                
                try {
                    Reservas::actualizarEstado($id_reserva, $nuevo_estado);
                    http_response_code(200);
                    echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                exit();
            }
        }

        // Manejar actualización de estado de reserva (GET - para compatibilidad)
        if (isset($_GET['cambiar_estado'])) {
            $id_reserva = $_GET['cambiar_estado'];
            $nuevo_estado = $_GET['estado'];
            
            try {
                Reservas::actualizarEstado($id_reserva, $nuevo_estado);
                $_SESSION['mensaje_exito'] = "Estado de reserva actualizado correctamente.";
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = $e->getMessage();
            }
            header("Location: ?controlador=admin&accion=reservas");
            exit();
        }

        // Manejar eliminación de reserva
        if (isset($_GET['eliminar'])) {
            $id_reserva = $_GET['eliminar'];
            
            try {
                Reservas::eliminar($id_reserva);
                $_SESSION['mensaje_exito'] = "Reserva eliminada correctamente.";
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = $e->getMessage();
            }
            header("Location: ?controlador=admin&accion=reservas");
            exit();
        }

        // Obtener filtros
        $filtros = [];
        if (isset($_GET['estado']) && !empty($_GET['estado'])) {
            $filtros['estado'] = $_GET['estado'];
        }
        if (isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio'])) {
            $filtros['fecha_inicio'] = $_GET['fecha_inicio'];
        }
        if (isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin'])) {
            $filtros['fecha_fin'] = $_GET['fecha_fin'];
        }

        // Obtener lista de reservas con filtros
        $reservas = Reservas::listarReservasCompletas($filtros);
        
        include_once("vistas/admin/reservas/inicio.php");
    }

    // ============= MÉTODOS AJAX PARA OBTENER DATOS =============
    
    // Método para obtener datos de usuario por ID (AJAX)
    public function obtener_usuario() {
        if (isset($_GET['id'])) {
            include_once("modelos/usuario.php");
            $id = $_GET['id'];
            $usuario = Usuario::obtenerPorId($id);
            
            header('Content-Type: application/json');
            if ($usuario) {
                echo json_encode(['success' => true, 'usuario' => $usuario]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            }
            exit();
        }
    }

    // Método para obtener datos de servicio por ID (AJAX)
    public function obtener_servicio() {
        if (isset($_GET['id'])) {
            include_once("modelos/servicios.php");
            $id = $_GET['id'];
            $servicio = Servicios::obtenerPorId($id);
            
            header('Content-Type: application/json');
            if ($servicio) {
                echo json_encode(['success' => true, 'servicio' => $servicio]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Servicio no encontrado']);
            }
            exit();
        }
    }

    // Método para obtener datos de spa por ID (AJAX)
    public function obtener_spa() {
        if (isset($_GET['id'])) {
            include_once("modelos/spas.php");
            $id = $_GET['id'];
            $spa = Spas::obtenerPorId($id);
            
            header('Content-Type: application/json');
            if ($spa) {
                echo json_encode(['success' => true, 'spa' => $spa]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Spa no encontrado']);
            }
            exit();
        }
    }

    // Método para obtener datos de reserva por ID (AJAX)
    public function obtener_reserva() {
        if (isset($_GET['id'])) {
            include_once("modelos/reservas.php");
            $id = $_GET['id'];
            $reserva = Reservas::obtenerPorId($id);
            
            if ($reserva) {
                $servicios = Reservas::obtenerServicios($id);
                $reserva['servicios'] = array_column($servicios, 'nombre');
                $reserva['precios'] = array_column($servicios, 'precio');
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'reserva' => $reserva]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Reserva no encontrada']);
            }
            exit();
        }
    }
    
    // ============= MÉTODOS AJAX PARA ACTUALIZAR DATOS =============
    
    // Método para actualizar usuario (AJAX)
    public function actualizar_usuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include_once("modelos/usuario.php");
            
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $tipo = $_POST['tipo'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;
            
            try {
                Usuario::actualizar($id, $nombre, $email, $tipo, $password);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }
    }
    
    // Método para cambiar estado de reserva (AJAX)
    public function cambiar_estado_reserva() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && isset($_GET['estado'])) {
            include_once("modelos/reservas.php");
            
            $id_reserva = $_GET['id'];
            $nuevo_estado = $_GET['estado'];
            
            try {
                Reservas::actualizarEstado($id_reserva, $nuevo_estado);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }
    }
    
    // ============= MÉTODOS AJAX PARA ELIMINAR DATOS =============
    
    // Método para eliminar usuario (AJAX)
    public function eliminar_usuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            include_once("modelos/usuario.php");
            
            $id = $_GET['id'];
            
            try {
                Usuario::eliminar($id);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }
    }
    
    // Método para eliminar servicio (AJAX)
    public function eliminar_servicio() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            include_once("modelos/servicios.php");
            
            $id = $_GET['id'];
            
            try {
                Servicios::borrarServicio($id);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Servicio eliminado correctamente']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }
    }
    
    // Método para eliminar spa (AJAX)
    public function eliminar_spa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            include_once("modelos/spas.php");
            
            $id = $_GET['id'];
            
            try {
                // Verificar si el modelo Spas tiene método eliminar
                if (method_exists('Spas', 'eliminar')) {
                    Spas::eliminar($id);
                } else {
                    // Si no existe, crear una consulta directa
                    $db = DB::ObtenerInstancia();
                    $query = "DELETE FROM spas WHERE id_spa = ?";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$id]);
                }
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Spa eliminado correctamente']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }
    }
    
    // Método para eliminar reserva (AJAX)
    public function eliminar_reserva() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            include_once("modelos/reservas.php");
            
            $id = $_GET['id'];
            
            try {
                Reservas::eliminar($id);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Reserva eliminada correctamente']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }
    }
}

?>