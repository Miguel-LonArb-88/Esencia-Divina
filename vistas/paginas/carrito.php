<div class="container py-4">
    <h2 class="mb-4" style="color: #D88084;"><i class="fas fa-shopping-cart me-2"></i>Tu Carrito</h2>

    <?php
if (!isset($_SESSION['nombre'])) {
    header('Location: ?controlador=usuarios&accion=login');
    exit;
}

// Mostrar mensaje de error del carrito si existe
if (isset($_SESSION['error_carrito'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo $_SESSION['error_carrito'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    unset($_SESSION['error_carrito']);
}

// Mostrar mensaje de error de pago si existe
if (isset($_SESSION['error_pago'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo $_SESSION['error_pago'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    unset($_SESSION['error_pago']);
}
?>

    <?php if (empty($serviciosCarrito)): ?>
        <div class="empty-cart text-center py-5">
            <i class="fas fa-cart-arrow-down display-4 text-primary mb-3"></i>
            <p class="lead text-muted">Tu carrito está vacío</p>
            <a href="?controlador=paginas&accion=servicios" class="btn btn-success mt-3">
                <i class="fas fa-spa me-2"></i>Ver Servicios
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Columna izquierda: Spa info y servicios -->
            <div class="col-md-8">


                <div class="row g-4">
                    <?php foreach ($serviciosCarrito as $index => $servicio): ?>
                        <?php if ($servicio): ?>
                        <div class="col-12">
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="row g-0">
                                    <div class="col-md-8">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0" style="color: #D88084;"><?= htmlspecialchars($servicio->nombre) ?></h5>
                                            </div>
                                            <p class="card-text small text-muted mb-0"><?= htmlspecialchars($servicio->descripcion) ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-body py-3 d-flex flex-column justify-content-between h-100">
                                            <div class="text-end mb-2">
                                                <span class="h5 fw-bold" style="color: #D88084;">$<?= number_format($servicio->precio, 2) ?></span>
                                            </div>
                                            <a href="?controlador=carrito&accion=eliminar&id=<?= $index ?>"
                                                class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash-alt me-1"></i>Eliminar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna derecha: Resumen de compra -->
            <div class="col-md-4">
                <div class="checkout-summary position-sticky top-0">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4 d-flex flex-column">
                            <h4 class="card-title border-bottom pb-3 mb-4 fw-bold" style="color: #D88084;">Resumen de Compra</h4>
                            <div class="d-flex justify-content-between align-items-center fw-bold mb-4 py-3 px-4" style="background-color: #FCF5F5; border-radius: 15px;">
                                <span class="h5 mb-0">Total:</span>
                                <span class="h3 mb-0 text-primary">
                                    $<?= number_format(array_sum(array_column(array_filter($serviciosCarrito), 'precio')), 2) ?>
                                </span>
                            </div>
                            <a href="?controlador=carrito&accion=procesar_pago"
                                class="btn btn-success btn-lg w-100 d-flex align-items-center justify-content-center gap-2 mb-4 py-3 rounded-3" style="background-color: #D88084 !important; border: none;">
                                <i class="fas fa-credit-card"></i>
                                <span class="fw-bold">Continuar Reservación</span>
                            </a>
                            <div class="text-center rounded-3 p-3" style="background-color: #FCF5F5;">
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-lock me-2"></i>Pago seguro mediante cifrado SSL
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>