<?php
include_once(__DIR__ . '/../conexion.php');

class Pagos {
    public $id_factura;
    public $id_reserva;
    public $monto;
    public $estado;
    public $fecha_pago;
    public $metodo_pago;
    public $referencia_pago;

    // Constructor
    public function __construct($datos = null) {
        if ($datos) {
            $this->id_factura = $datos['id_factura'] ?? null;
            $this->id_reserva = $datos['id_reserva'] ?? null;
            $this->monto = $datos['monto'] ?? null;
            $this->estado = $datos['estado'] ?? null;
            $this->fecha_pago = $datos['fecha_pago'] ?? null;
            $this->metodo_pago = $datos['metodo_pago'] ?? null;
            $this->referencia_pago = $datos['referencia_pago'] ?? null;
        }
    }

    // Validar datos del pago
    private static function validarDatosPago($datos) {
        if (!isset($datos['id_reserva']) || !is_numeric($datos['id_reserva'])) {
            throw new Exception('ID de reserva inválido');
        }
        if (!isset($datos['monto']) || !is_numeric($datos['monto']) || $datos['monto'] <= 0) {
            throw new Exception('Monto inválido');
        }
        if (!isset($datos['estado']) || !in_array($datos['estado'], ['pendiente', 'pagado'])) {
            throw new Exception('Estado de pago inválido');
        }
    }

    // Registrar un nuevo pago
    public function registrarPago($datos) {
        try {
            self::validarDatosPago($datos);
            $conexion = DB::crearInstancia();
            $conexion->beginTransaction();

            $sql = $conexion->prepare("INSERT INTO factura (id_reserva, monto, estado, fecha_pago, metodo_pago, referencia_pago) 
                                     VALUES (?, ?, ?, NOW(), ?, ?)");
            
            $resultado = $sql->execute([
                $datos['id_reserva'],
                $datos['monto'],
                $datos['estado'],
                $datos['metodo_pago'] ?? 'tarjeta',
                $datos['referencia_pago'] ?? null
            ]);

            if (!$resultado) {
                throw new Exception('Error al registrar el pago');
            }

            $idFactura = $conexion->lastInsertId();
            $this->id_factura = $idFactura;
            $this->id_reserva = $datos['id_reserva'];
            $this->monto = $datos['monto'];
            $this->estado = $datos['estado'];
            $this->fecha_pago = date('Y-m-d H:i:s');
            $this->metodo_pago = $datos['metodo_pago'] ?? 'tarjeta';
            $this->referencia_pago = $datos['referencia_pago'] ?? null;
            
            $conexion->commit();
            return $idFactura;

        } catch (Exception $e) {
            if (isset($conexion)) {
                $conexion->rollBack();
            }
            throw $e;
        }
    }

    // Obtener pago por ID
    public static function obtenerPorId($id) {
        if (!is_numeric($id)) {
            throw new Exception('ID de factura inválido');
        }

        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("SELECT * FROM factura WHERE id_factura = ?");
        $sql->execute([$id]);
        $pago = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$pago) {
            return null;
        }

        return new Pagos($pago);
    }

    // Listar todos los pagos
    public static function listarPagos($filtros = []) {
        $conexion = DB::crearInstancia();
        $where = "1=1";
        $params = [];

        if (isset($filtros['estado'])) {
            $where .= " AND estado = ?";
            $params[] = $filtros['estado'];
        }

        if (isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin'])) {
            $where .= " AND fecha_pago BETWEEN ? AND ?";
            $params[] = $filtros['fecha_inicio'];
            $params[] = $filtros['fecha_fin'];
        }

        $sql = $conexion->prepare("SELECT * FROM factura WHERE $where ORDER BY fecha_pago DESC");
        $sql->execute($params);
        $pagos = [];

        while ($pago = $sql->fetch(PDO::FETCH_ASSOC)) {
            $pagos[] = new Pagos($pago);
        }

        return $pagos;
    }

    // Actualizar estado del pago
    public static function actualizarEstado($id_factura, $nuevo_estado) {
        if (!in_array($nuevo_estado, ['pendiente', 'pagado', 'cancelado'])) {
            throw new Exception('Estado de pago inválido');
        }

        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("UPDATE factura SET estado = ? WHERE id_factura = ?");
        
        if (!$sql->execute([$nuevo_estado, $id_factura])) {
            throw new Exception('Error al actualizar el estado del pago');
        }

        return true;
    }

    // Obtener total de pagos por estado
    public static function obtenerTotalPorEstado($estado) {
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("SELECT COUNT(*) as total, SUM(monto) as suma 
                                 FROM factura WHERE estado = ?");
        $sql->execute([$estado]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}
?>
