<?php
include_once(__DIR__ . '/../conexion.php');

class Reservas {
    public $id_reserva;
    public $id_cliente;
    public $fecha;
    public $hora;
    public $estado;
    public $notas;
    public $fecha_creacion;
    public $servicios;
    public $precios;
    public $nombre_spa;
    public $nombre_cliente;
    public $email_cliente;
    public $total;

    // Constructor
    public function __construct($id_reserva, $id_cliente, $fecha, $hora, $estado, $notas = null, $fecha_creacion = null, $nombre_spa = null) {
        $this->id_reserva = $id_reserva;
        $this->id_cliente = $id_cliente;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->estado = $estado;
        $this->notas = $notas;
        $this->fecha_creacion = $fecha_creacion;
        $this->servicios = [];
        $this->precios = [];
        $this->nombre_spa = $nombre_spa;
    }

    // Validar datos de la reserva
    private static function validarDatosReserva($datos) {
        if (!isset($datos['id_cliente']) || !is_numeric($datos['id_cliente'])) {
            throw new Exception('ID de cliente inválido');
        }
        if (!isset($datos['id_spas']) || !is_numeric($datos['id_spas'])) {
            throw new Exception('ID de spa inválido');
        }
        if (!isset($datos['fecha']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha'])) {
            throw new Exception('Formato de fecha inválido');
        }
        if (!isset($datos['hora']) || !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $datos['hora'])) {
            throw new Exception('Formato de hora inválido');
        }
        if (!isset($datos['estado']) || !in_array($datos['estado'], ['pendiente', 'confirmada', 'cancelada', 'completada'])) {
            throw new Exception('Estado de reserva inválido');
        }

        // Validar que la fecha y hora no sean anteriores a la actual
        $fechaHoraReserva = new DateTime($datos['fecha'] . ' ' . $datos['hora']);
        $ahora = new DateTime();
        if ($fechaHoraReserva < $ahora) {
            throw new Exception('La fecha y hora de reserva no pueden ser anteriores a la actual');
        }
    }

    // Crear una nueva reserva
    public static function crear($datos) {
        try {
            self::validarDatosReserva($datos);
            $conexion = DB::crearInstancia();
            $conexion->beginTransaction();

            $sql = $conexion->prepare('INSERT INTO reservas (id_cliente, id_spas, fecha, hora, estado, notas) VALUES (?, ?, ?, ?, ?, ?)');
            $resultado = $sql->execute([
                $datos['id_cliente'],
                $datos['id_spas'],
                $datos['fecha'],
                $datos['hora'],
                $datos['estado'],
                $datos['notas'] ?? null
            ]);

            if (!$resultado) {
                throw new Exception('Error al crear la reserva');
            }

            $idReserva = $conexion->lastInsertId();
            $conexion->commit();
            return $idReserva;

        } catch (Exception $e) {
            if (isset($conexion)) {
                $conexion->rollBack();
            }
            throw $e;
        }
    }

    // Agregar detalle a la reserva
    public static function agregarDetalle($id_reserva, $id_servicio) {
        try {
            if (!is_numeric($id_reserva) || !is_numeric($id_servicio)) {
                throw new Exception('IDs inválidos');
            }

            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare('INSERT INTO detalle_reserva (id_reserva, id_servicio) VALUES (?, ?)');
            if (!$sql->execute([$id_reserva, $id_servicio])) {
                throw new Exception('Error al agregar el detalle de la reserva');
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Verificar si existe una reservación
    public static function existeReservacion($fecha, $hora) {
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare('SELECT COUNT(*) FROM reservas WHERE fecha = ? AND hora = ? AND estado != "cancelada"');
        $sql->execute([$fecha, $hora]);
        return $sql->fetchColumn() > 0;
    }

    // Obtener reserva por ID
    public static function obtenerPorId($id_reserva) {
        if (!is_numeric($id_reserva)) {
            throw new Exception('ID de reserva inválido');
        }

        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare('SELECT * FROM reservas WHERE id_reserva = ?');
        $sql->execute([$id_reserva]);
        $reserva = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            return null;
        }

        return new Reservas(
            $reserva['id_reserva'],
            $reserva['id_cliente'],
            $reserva['fecha'],
            $reserva['hora'],
            $reserva['estado'],
            $reserva['notas'],
            null // fecha_creacion no existe en la tabla
        );
    }

    // Actualizar estado de la reserva
    public static function actualizarEstado($id_reserva, $nuevo_estado) {
        if (!in_array($nuevo_estado, ['pendiente', 'confirmada', 'cancelada', 'completada'])) {
            throw new Exception('Estado de reserva inválido');
        }

        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare('UPDATE reservas SET estado = ? WHERE id_reserva = ?');
        if (!$sql->execute([$nuevo_estado, $id_reserva])) {
            throw new Exception('Error al actualizar el estado de la reserva');
        }
        return true;
    }

    // Obtener servicios de una reserva
    public static function obtenerServicios($id_reserva) {
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare('SELECT s.* FROM servicios s 
            INNER JOIN detalle_reserva dr ON s.id_servicio = dr.id_servicio 
            WHERE dr.id_reserva = ?');
        $sql->execute([$id_reserva]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar una reserva
    public static function eliminar($id_reserva) {
        try {
            $conexion = DB::crearInstancia();
            $conexion->beginTransaction();

            // Eliminar detalles de la reserva
            $sql = $conexion->prepare('DELETE FROM detalle_reserva WHERE id_reserva = ?');
            $sql->execute([$id_reserva]);

            // Eliminar la reserva
            $sql = $conexion->prepare('DELETE FROM reservas WHERE id_reserva = ?');
            if (!$sql->execute([$id_reserva])) {
                throw new Exception('Error al eliminar la reserva');
            }

            $conexion->commit();
            return true;
        } catch (Exception $e) {
            if (isset($conexion)) {
                $conexion->rollBack();
            }
            throw $e;
        }
    }

    // Listar reservas con filtros
    public static function listarReservas($filtros = []) {
        $conexion = DB::crearInstancia();
        $where = "1=1";
        $params = [];

        if (isset($filtros['id_cliente'])) {
            $where .= " AND id_cliente = ?";
            $params[] = $filtros['id_cliente'];
        }

        if (isset($filtros['estado'])) {
            $where .= " AND estado = ?";
            $params[] = $filtros['estado'];
        }

        if (isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin'])) {
            $where .= " AND fecha BETWEEN ? AND ?";
            $params[] = $filtros['fecha_inicio'];
            $params[] = $filtros['fecha_fin'];
        }

        $sql = $conexion->prepare("SELECT * FROM reservas WHERE $where ORDER BY fecha DESC, hora DESC");
        $sql->execute($params);
        $reservas = [];

        while ($reserva = $sql->fetch(PDO::FETCH_ASSOC)) {
            $reservas[] = new Reservas(
                $reserva['id_reserva'],
                $reserva['id_cliente'],
                $reserva['fecha'],
                $reserva['hora'],
                $reserva['estado'],
                $reserva['notas'],
            );
        }

        return $reservas;
    }

    // Obtener reservas de un usuario específico
    public static function obtenerReservasUsuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            throw new Exception('ID de usuario inválido');
        }

        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare('SELECT r.*, sp.nombre as nombre_spa, GROUP_CONCAT(s.nombre) as servicios, 
                                  GROUP_CONCAT(s.precio) as precios
                                  FROM reservas r
                                  LEFT JOIN spas sp ON r.id_spas = sp.id_spa
                                  LEFT JOIN detalle_reserva dr ON r.id_reserva = dr.id_reserva
                                  LEFT JOIN servicios s ON dr.id_servicio = s.id_servicio
                                  WHERE r.id_cliente = ?
                                  GROUP BY r.id_reserva
                                  ORDER BY r.fecha DESC, r.hora DESC');
        $sql->execute([$id_usuario]);
        $reservas = [];

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $reserva = new Reservas(
                $row['id_reserva'],
                $row['id_cliente'],
                $row['fecha'],
                $row['hora'],
                $row['estado'],
                $row['notas'],
                null,
                $row['nombre_spa']
            );
            $reserva->servicios = $row['servicios'] ? explode(',', $row['servicios']) : [];
            $reserva->precios = $row['precios'] ? explode(',', $row['precios']) : [];
            $reservas[] = $reserva;
        }

        return $reservas;
    }

    // Listar reservas completas con información de cliente, servicios y spa
    public static function listarReservasCompletas($filtros = []) {
        $conexion = DB::crearInstancia();
        $where = "1=1";
        $params = [];

        if (isset($filtros['estado'])) {
            $where .= " AND r.estado = ?";
            $params[] = $filtros['estado'];
        }

        if (isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin'])) {
            $where .= " AND r.fecha BETWEEN ? AND ?";
            $params[] = $filtros['fecha_inicio'];
            $params[] = $filtros['fecha_fin'];
        }

        $sql = $conexion->prepare(
            "SELECT r.*, u.nombre as nombre_cliente, u.email as email_cliente, 
                    sp.nombre as nombre_spa, 
                    GROUP_CONCAT(s.nombre SEPARATOR ', ') as servicios,
                    GROUP_CONCAT(s.precio SEPARATOR ',') as precios,
                    SUM(s.precio) as total
             FROM reservas r
             LEFT JOIN usuarios u ON r.id_cliente = u.id_usuario
             LEFT JOIN spas sp ON r.id_spas = sp.id_spa
             LEFT JOIN detalle_reserva dr ON r.id_reserva = dr.id_reserva
             LEFT JOIN servicios s ON dr.id_servicio = s.id_servicio
             WHERE $where
             GROUP BY r.id_reserva
             ORDER BY r.fecha DESC, r.hora DESC"
        );
        $sql->execute($params);
        $reservas = [];

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $reserva = new Reservas(
                $row['id_reserva'],
                $row['id_cliente'],
                $row['fecha'],
                $row['hora'],
                $row['estado'],
                $row['notas'],
                null,
                $row['nombre_spa']
            );
            $reserva->nombre_cliente = $row['nombre_cliente'];
            $reserva->email_cliente = $row['email_cliente'];
            $reserva->servicios = $row['servicios'] ? explode(', ', $row['servicios']) : [];
            $reserva->precios = $row['precios'] ? explode(',', $row['precios']) : [];
            $reserva->total = $row['total'] ?? 0;
            $reservas[] = $reserva;
        }

        return $reservas;
    }

    // Función para obtener detalles completos de una reserva
    public static function obtenerDetallesCompletos($id_reserva) {
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("
            SELECT 
                r.id_reserva,
                r.fecha,
                r.hora,
                r.estado,
                r.notas,
                u.nombre as nombre_cliente,
                u.email as email_cliente,
                u.telefono as telefono_cliente,
                s.nombre as nombre_spa,
                s.direccion as direccion_spa,
                s.telefono as telefono_spa,
                GROUP_CONCAT(serv.nombre SEPARATOR ', ') as servicios,
                SUM(serv.precio) as total
            FROM reservas r
            JOIN usuarios u ON r.id_cliente = u.id_usuario
            JOIN spas s ON r.id_spas = s.id_spa
            LEFT JOIN detalle_reserva dr ON r.id_reserva = dr.id_reserva
            LEFT JOIN servicios serv ON dr.id_servicio = serv.id_servicio
            WHERE r.id_reserva = ?
            GROUP BY r.id_reserva
        ");
        $sql->execute(array($id_reserva));
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}
?>