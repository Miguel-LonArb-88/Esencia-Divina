<?php
if (!isset($_SESSION['reserva_exitosa']) || !isset($_SESSION['ultimo_id_reserva'])) {
    header('Location: ?controlador=inicio');
    exit;
}

$reserva = Reservas::obtenerPorId($_SESSION['ultimo_id_reserva']);
$servicios = Reservas::obtenerServicios($_SESSION['ultimo_id_reserva']);
$factura = Pagos::obtenerPorId($_SESSION['ultimo_id_factura']);

// Limpiar variables de sesión después de usarlas
unset($_SESSION['reserva_exitosa']);
unset($_SESSION['ultimo_id_reserva']);
unset($_SESSION['ultimo_id_factura']);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="mb-4">¡Pago Completado con Éxito!</h2>
                    <p class="lead mb-4">Tu reserva ha sido confirmada y registrada en nuestro sistema.</p>
                    
                    <div class="alert alert-info" role="alert">
                        <h5 class="alert-heading">Detalles de la Reserva</h5>
                        <hr>
                        <p class="mb-1"><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($reserva->fecha)); ?></p>
                        <p class="mb-1"><strong>Hora:</strong> <?php echo date('H:i', strtotime($reserva->hora)); ?></p>
                        <p class="mb-3"><strong>Estado:</strong> <span class="badge bg-success">Confirmada</span></p>
                        
                        <h6 class="mb-2">Servicios Reservados:</h6>
                        <ul class="list-unstyled">
                            <?php foreach($servicios as $servicio): ?>
                            <li><?php echo $servicio['nombre']; ?> - <?php echo number_format($servicio['precio'], 2); ?> €</li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <p class="mb-0"><strong>Total Pagado:</strong> <?php echo number_format($factura->monto, 2); ?> €</p>
                    </div>

                    <?php if($reserva->notas): ?>
                    <div class="alert alert-light text-start" role="alert">
                        <h6 class="alert-heading">Notas Adicionales:</h6>
                        <p class="mb-0"><?php echo htmlspecialchars($reserva->notas); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="?controlador=inicio" class="btn btn-primary me-2">
                            <i class="fas fa-home"></i> Volver al Inicio
                        </a>
                        <a href="?controlador=servicios" class="btn btn-outline-primary me-2">
                            <i class="fas fa-spa"></i> Ver Más Servicios
                        </a>
                        <a href="?controlador=usuarios&accion=reservas" class="btn btn-outline-info">
                            <i class="fas fa-calendar-check"></i> Ver Mis Reservas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <div class="text-center mt-4">
                <p class="text-muted">
                    Se ha enviado un correo electrónico con los detalles de tu reserva.<br>
                    Si tienes alguna pregunta, no dudes en contactarnos.
                </p>
            </div>
        </div>
    </div>
</div>