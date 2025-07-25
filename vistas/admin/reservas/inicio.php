<div class="admin-dashboard">
<div class="admin-container">
    <?php if (isset($_SESSION['mensaje_exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['mensaje_exito']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje_exito']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['mensaje_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['mensaje_error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje_error']); ?>
    <?php endif; ?>

    <div class="admin-header">
        <h2><i class="fas fa-calendar-alt"></i> Gestión de Reservas</h2>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="?controlador=admin&accion=reservas" class="row g-3">
                <input type="hidden" name="controlador" value="admin">
                <input type="hidden" name="accion" value="reservas">
                
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="confirmada" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                        <option value="completada" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'completada') ? 'selected' : ''; ?>>Completada</option>
                        <option value="cancelada" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $_GET['fecha_inicio'] ?? ''; ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?php echo $_GET['fecha_fin'] ?? ''; ?>">
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="?controlador=admin&accion=reservas" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Reservas</h6>
                            <h3><?php echo count($reservas); ?></h3>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pendientes</h6>
                            <h3><?php echo count(array_filter($reservas, fn($r) => $r->estado === 'pendiente')); ?></h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Confirmadas</h6>
                            <h3><?php echo count(array_filter($reservas, fn($r) => $r->estado === 'confirmada')); ?></h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Completadas</h6>
                            <h3><?php echo count(array_filter($reservas, fn($r) => $r->estado === 'completada')); ?></h3>
                        </div>
                        <i class="fas fa-check-double fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de reservas -->
    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Cliente</th>
                    <th style="width: 12%;">Spa</th>
                    <th style="width: 10%;">Fecha</th>
                    <th style="width: 8%;">Hora</th>
                    <th style="width: 25%;">Servicios</th>
                    <th style="width: 8%;">Total</th>
                    <th style="width: 10%;">Estado</th>
                    <th style="width: 12%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservas)): ?>
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay reservas que mostrar</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($reservas as $reserva): ?>
                    <tr>
                        <td class="text-center align-middle">
                            <span class="badge bg-secondary">#<?php echo $reserva->id_reserva; ?></span>
                        </td>
                        <td class="align-middle">
                            <div class="cliente-info">
                                <div class="fw-bold text-primary"><?php echo htmlspecialchars($reserva->nombre_cliente); ?></div>
                                <small class="text-muted d-block">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($reserva->email_cliente); ?>
                                </small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="fw-semibold"><?php echo htmlspecialchars($reserva->nombre_spa); ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <div class="fecha-info">
                                <div class="fw-bold"><?php echo date('d/m/Y', strtotime($reserva->fecha)); ?></div>
                                <small class="text-muted"><?php echo date('l', strtotime($reserva->fecha)); ?></small>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge bg-info text-white">
                                <i class="fas fa-clock me-1"></i><?php echo date('H:i', strtotime($reserva->hora)); ?>
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="servicios-lista">
                                <?php if (!empty($reserva->servicios)): ?>
                                    <?php foreach($reserva->servicios as $index => $servicio): ?>
                                        <div class="servicio-item mb-1">
                                            <span class="badge bg-light text-dark">
                                                <?php echo htmlspecialchars($servicio); ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Sin servicios</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <div class="total-info">
                                <span class="fw-bold text-success fs-5">€<?php echo number_format($reserva->total, 2); ?></span>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge <?php 
                                echo match($reserva->estado) {
                                    'pendiente' => 'bg-warning text-dark',
                                    'confirmada' => 'bg-success',
                                    'cancelada' => 'bg-danger',
                                    'completada' => 'bg-info',
                                    default => 'bg-secondary'
                                };
                            ?> px-3 py-2">
                                <i class="fas <?php 
                                    echo match($reserva->estado) {
                                        'pendiente' => 'fa-clock',
                                        'confirmada' => 'fa-check-circle',
                                        'cancelada' => 'fa-times-circle',
                                        'completada' => 'fa-check-double',
                                        default => 'fa-question-circle'
                                    };
                                ?> me-1"></i>
                                <?php echo ucfirst($reserva->estado); ?>
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Cambiar estado -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Cambiar estado">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if ($reserva->estado !== 'pendiente'): ?>
                                        <li><a class="dropdown-item" onclick="cambiarEstadoReserva(<?php echo $reserva->id_reserva; ?>, 'pendiente')" href="#">
                                            <i class="fas fa-clock text-warning"></i> Pendiente
                                        </a></li>
                                        <?php endif; ?>
                                        <?php if ($reserva->estado !== 'confirmada'): ?>
                                        <li><a class="dropdown-item" onclick="cambiarEstadoReserva(<?php echo $reserva->id_reserva; ?>, 'confirmada')" href="#">
                                            <i class="fas fa-check-circle text-success"></i> Confirmada
                                        </a></li>
                                        <?php endif; ?>
                                        <?php if ($reserva->estado !== 'completada'): ?>
                                        <li><a class="dropdown-item" onclick="cambiarEstadoReserva(<?php echo $reserva->id_reserva; ?>, 'completada')" href="#">
                                            <i class="fas fa-check-double text-info"></i> Completada
                                        </a></li>
                                        <?php endif; ?>
                                        <?php if ($reserva->estado !== 'cancelada'): ?>
                                        <li><a class="dropdown-item" onclick="cambiarEstadoReserva(<?php echo $reserva->id_reserva; ?>, 'cancelada')" href="#">
                                            <i class="fas fa-times-circle text-danger"></i> Cancelada
                                        </a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                
                                <!-- Ver detalles -->
                                <button class="btn btn-sm btn-info" onclick="verReserva(<?php echo $reserva->id_reserva; ?>)" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Eliminar -->
                                <button class="btn btn-sm btn-danger" onclick="eliminarReserva(<?php echo $reserva->id_reserva; ?>, '<?php echo htmlspecialchars($reserva->nombre_cliente); ?>')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para ver detalles de reserva -->
<div class="modal fade" id="detallesReservaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i>
                    Detalles de la Reserva
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="detallesReservaContent">
                <!-- El contenido se cargará dinámicamente -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando detalles de la reserva...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirReserva(document.getElementById('reservaIdDetalle').value)">
                    <i class="fas fa-print me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado de reserva -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i>
                    Cambiar Estado de Reserva
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarEstado">
                    <input type="hidden" id="reservaId" name="reserva_id">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-list me-2"></i>Nuevo Estado
                        </label>
                        <select class="form-select" id="nuevoEstado" name="estado" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="pendiente">⏳ Pendiente</option>
                            <option value="confirmada">✅ Confirmada</option>
                            <option value="completada">🎉 Completada</option>
                            <option value="cancelada">❌ Cancelada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-comment me-2"></i>Comentarios (Opcional)
                        </label>
                        <textarea class="form-control" name="comentarios" rows="3" 
                                  placeholder="Agregar comentarios sobre el cambio de estado..."></textarea>
                    </div>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Atención:</strong> El cliente será notificado automáticamente del cambio de estado.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" form="formCambiarEstado" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Cambiar Estado
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<script>
// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<!-- Incluir funciones de administración -->
<script src="assets/js/admin-funciones.js"></script>

<style>
.servicios-lista {
    max-width: 250px;
}

.actions-cell {
    white-space: nowrap;
}

.admin-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    text-align: center;
    vertical-align: middle;
}

.admin-table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.admin-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.servicios-detalle .badge {
    font-size: 0.9em;
    margin: 2px;
}

.cliente-info {
    min-width: 180px;
}

.fecha-info {
    min-width: 80px;
}

.total-info {
    min-width: 80px;
}

.servicio-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.25rem;
}

.servicio-item:last-child {
    margin-bottom: 0;
}

.badge {
    font-size: 0.85em;
}

/* Estilos simples para botones de acciones */
.btn-group {
    gap: 2px;
}

.btn-group .btn {
    margin: 0;
}

.dropdown-menu {
    min-width: 160px;
}

.dropdown-item {
    padding: 0.5rem 1rem;
}

/* Correcciones adicionales para visibilidad */
.table-responsive {
    overflow-x: auto;
    overflow-y: visible !important;
}

.dropdown {
    position: static;
}

.dropdown-menu {
    position: absolute !important;
    will-change: transform;
}

.btn-group {
    display: flex;
    flex-wrap: nowrap;
}

/* Asegurar que todos los elementos sean visibles */
* {
    box-sizing: border-box;
}

.admin-table td,
.admin-table th {
    position: relative;
    overflow: visible;
}
</style>