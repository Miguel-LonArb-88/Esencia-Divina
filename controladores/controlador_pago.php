<?php
include_once("conexion.php");
DB::CrearInstancia();
include_once("modelos/pagos.php");
include_once("modelos/reservas.php");
include_once("modelos/servicios.php");
include_once("modelos/spas.php");

class ControladorPago
{
    // Método para mostrar la página de pago completado
    public function completado()
    {
        if (!isset($_SESSION['nombre'])) {
            header('Location: ?controlador=usuarios&accion=login');
            exit;
        }
        include_once("vistas/paginas/pago_completado.php");
    }

    // Method that processes the payment view
    private function verificarSesion() {
        if (!isset($_SESSION['nombre']) || !isset($_SESSION['id']) || !is_numeric($_SESSION['id'])) {
            $_SESSION['error_pago'] = 'Sesión inválida. Por favor, inicie sesión nuevamente.';
            header('Location: ?controlador=usuarios&accion=login');
            exit;
        }
        return true;
    }

    public function procesar()
    {
        $this->verificarSesion();

        if (empty($_SESSION['carrito'])) {
            header('Location: ?controlador=carrito&accion=ver');
            exit;
        }

        $servicios = Servicios::obtenerMultiples($_SESSION['carrito']);
        $total = array_sum(array_column($servicios, 'precio'));
        $spa = isset($_SESSION['spa_actual']) ? Spas::obtenerPorId($_SESSION['spa_actual']) : null;

        include_once("vistas/paginas/pago.php");
    }

    // Method that confirms the payment
    public function confirmar()
    {
        try {
            // Validación de sesión
            $this->verificarSesion();
            if (empty($_SESSION['carrito'])) {
                throw new Exception('El carrito está vacío');
            }

            // Validación de datos del formulario
            $camposRequeridos = ['total', 'fecha_reserva', 'hora_reserva', 'titular', 'numero_tarjeta', 'expiracion', 'cvv'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                    throw new Exception('Por favor complete todos los campos del formulario');
                }
            }

            // Validación de fecha y hora
            $fechaActual = new DateTime();
            $fechaReserva = new DateTime($_POST['fecha_reserva'] . ' ' . $_POST['hora_reserva']);
            if ($fechaReserva < $fechaActual) {
                throw new Exception('La fecha y hora de reserva no pueden ser anteriores a la actual');
            }

            // Validación de disponibilidad
            if (Reservas::existeReservacion($_POST['fecha_reserva'], $_POST['hora_reserva'])) {
                throw new Exception('Lo sentimos, ya existe una reservación para esta fecha y hora');
            }

            // Validar que el ID del cliente esté presente en la sesión
            if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id'])) {
                throw new Exception('ID de cliente inválido. Por favor, inicie sesión nuevamente.');
            }

            // Validar que exista un spa seleccionado
            if (!isset($_SESSION['spa_actual']) || !is_numeric($_SESSION['spa_actual'])) {
                throw new Exception('No se ha seleccionado un spa válido');
            }

            // Verificar que el spa existe
            $spa = Spas::obtenerPorId($_SESSION['spa_actual']);
            if (!$spa) {
                throw new Exception('El spa seleccionado no existe');
            }

            // Crear la reserva
            $datosReserva = [
                'id_cliente' => $_SESSION['id'],
                'fecha' => $_POST['fecha_reserva'],
                'hora' => $_POST['hora_reserva'],
                'estado' => 'pendiente',
                'id_spas' => $_SESSION['spa_actual']
            ];
            $idReserva = Reservas::crear($datosReserva);

            // Agregar los servicios del carrito a la reserva
            foreach ($_SESSION['carrito'] as $id_servicio) {
                Reservas::agregarDetalle($idReserva, $id_servicio);
            }

            // Validar datos de la tarjeta
            if (empty($_POST['titular']) || strlen($_POST['titular']) < 3) {
                throw new Exception('Nombre del titular inválido');
            }

            // Validar que el titular coincida con el nombre del usuario
            if (strtolower(trim($_POST['titular'])) !== strtolower(trim($_SESSION['nombre']))) {
                throw new Exception('El titular de la tarjeta debe coincidir con el nombre del usuario');
            }

            if (!preg_match('/^[0-9]{16}$/', str_replace(' ', '', $_POST['numero_tarjeta']))) {
                throw new Exception('Número de tarjeta inválido');
            }
            if (!preg_match('/^[0-9]{2}\/[0-9]{2}$/', $_POST['expiracion'])) {
                throw new Exception('Fecha de expiración inválida');
            }
            
            // Validar que la fecha de expiración no esté vencida
            list($mes, $anio) = explode('/', $_POST['expiracion']);
            $anio = '20' . $anio;
            $fechaExpiracion = DateTime::createFromFormat('Y-m', $anio . '-' . $mes);

            // Crear el registro de pago
            $datosPago = [
                'id_reserva' => $idReserva,
                'monto' => $_POST['total'],
                'estado' => 'pagado',
                'fecha_pago' => date('Y-m-d H:i:s'),
                'metodo_pago' => 'tarjeta',
                'referencia_pago' => substr($_POST['numero_tarjeta'], -4)
            ];
            
            $pago = new Pagos();
            $idFactura = $pago->registrarPago($datosPago);

            // Establecer variables de sesión para la página de pago completado
            $_SESSION['reserva_exitosa'] = true;
            $_SESSION['ultimo_id_reserva'] = $idReserva;
            $_SESSION['ultimo_id_factura'] = $idFactura;

            // Limpiar el carrito
            unset($_SESSION['carrito']);
            
            // Redirigir a la página de pago completado
            header('Location: ?controlador=pago&accion=completado');
            exit;
            $fechaActual = new DateTime();
            
            if ($mes < 1 || $mes > 12) {
                throw new Exception('Mes de expiración inválido');
            }
            
            if ($fechaExpiracion < $fechaActual) {
                throw new Exception('La tarjeta ha expirado');
            }
            if (!preg_match('/^[0-9]{3,4}$/', $_POST['cvv'])) {
                throw new Exception('CVV inválido');
            }

            // Procesar el pago
            try {
                // Aquí iría la integración con el gateway de pago
                // Por ahora simulamos un pago exitoso
                $idFactura = Pagos::registrarPago([
                    'id_reserva' => $idReserva,
                    'monto' => $_POST['total'],
                    'estado' => 'pagado'
                ]);

                // Registrar detalles de la reserva
                foreach ($_SESSION['carrito'] as $idServicio) {
                    Reservas::agregarDetalle($idReserva, $idServicio);
                }

                // Limpiar carrito y variables de sesión
                unset($_SESSION['carrito']);
                unset($_SESSION['spa_actual']);

                $_SESSION['reserva_exitosa'] = true;
                $_SESSION['ultimo_id_reserva'] = $idReserva;
                $_SESSION['ultimo_id_factura'] = $idFactura;

                header('Location: ?controlador=pago&accion=completado');
                exit;

            } catch (Exception $e) {
                // Si falla el pago, eliminar la reserva
                Reservas::eliminar($idReserva);
                throw new Exception('Error al procesar el pago: ' . $e->getMessage());
            }

        } catch (Exception $e) {
            $_SESSION['error_pago'] = $e->getMessage();
            header('Location: ?controlador=carrito&accion=ver');
            exit;
        }
    }
}

