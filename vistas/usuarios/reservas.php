<?php
if (!isset($_SESSION['id'])) {
    header('Location: ?controlador=usuarios&accion=login');
    exit;
}
?>

<link rel="stylesheet" href="assets/estilos/reservas.css">

<div class="reservas-container">
    <div class="container py-5">
        <div class="reservas-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h2 class="reservas-title"><i class="fas fa-calendar-alt me-2"></i>Mis Reservas</h2>
                <a href="?controlador=servicios" class="btn-nueva-reserva">
                    <i class="fas fa-plus-circle"></i>Nueva Reserva
                </a>
            </div>
        </div>

        <?php if (empty($reservas)): ?>
        <div class="no-reservas-alert">
            <i class="fas fa-spa fa-3x mb-3" style="color: #DE968D;"></i>
            <h5 class="alert-heading">No hay reservas</h5>
            <p class="mb-0">Aún no tienes reservas registradas. ¡Reserva tu primer servicio ahora!</p>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($reservas as $reserva): ?>
            <div class="col-md-6 mb-4">
                <div class="reserva-card">
                    <div class="reserva-card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="reserva-title">Reserva #<?php echo $reserva->id_reserva; ?></h5>
                                <small class="text-muted"><i class="fas fa-store me-1"></i><?php echo $reserva->nombre_spa; ?></small>
                            </div>
                            <span class="estado-badge <?php 
                                echo match($reserva->estado) {
                                    'pendiente' => 'bg-warning text-dark',
                                    'confirmada' => 'bg-success',
                                    'cancelada' => 'bg-danger',
                                    'completada' => 'bg-info',
                                    default => 'bg-secondary'
                                };
                            ?>">
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
                        </div>
                    </div>
                    <div class="reserva-card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fas fa-calendar me-2" style="color: #DE968D;"></i>
                                    <strong>Fecha:</strong>
                                    <span class="ms-2"><?php echo date('d/m/Y', strtotime($reserva->fecha)); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fas fa-clock me-2" style="color: #DE968D;"></i>
                                    <strong>Hora:</strong>
                                    <span class="ms-2"><?php echo date('H:i', strtotime($reserva->hora)); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="servicios-section">
                            <h6 class="servicios-title">
                                <i class="fas fa-spa me-2"></i>Servicios Reservados
                            </h6>
                            <div class="list-group list-group-flush">
                                <?php 
                                $total = 0;
                                for ($i = 0; $i < count($reserva->servicios); $i++): 
                                    $servicio = $reserva->servicios[$i];
                                    $precio = $reserva->precios[$i];
                                    $total += $precio;
                                ?>
                                <div class="servicio-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-check me-2 text-success"></i><?php echo $servicio; ?></span>
                                    <span class="precio-badge"><?php echo number_format($precio, 2); ?> €</span>
                                </div>
                                <?php endfor; ?>
                                <div class="total-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-calculator me-2"></i>Total</span>
                                    <span><?php echo number_format($total, 2); ?> €</span>
                                </div>
                            </div>
                        </div>

                        <?php if ($reserva->notas): ?>
                        <div class="notas-section">
                            <h6 class="notas-title">
                                <i class="fas fa-sticky-note me-2"></i>Notas Adicionales
                            </h6>
                            <div class="notas-content">
                                <i class="fas fa-quote-left text-muted me-2"></i>
                                <?php echo htmlspecialchars($reserva->notas); ?>
                                <i class="fas fa-quote-right text-muted ms-2"></i>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- NUEVOS BOTONES DE ACCIÓN -->
                        <div class="botones-accion">
                            <button class="btn-accion btn-ver-detalles" onclick="verDetalles(<?php echo $reserva->id_reserva; ?>)">
                                <i class="fas fa-eye"></i>Ver Detalles
                            </button>
                            
                            <?php if ($reserva->estado !== 'cancelada' && $reserva->estado !== 'completada'): ?>
                            <button class="btn-accion btn-cambiar-estado" onclick="abrirModalEstado(<?php echo $reserva->id_reserva; ?>, '<?php echo $reserva->estado; ?>')">
                                <i class="fas fa-edit"></i>Cambiar Estado
                            </button>
                            <?php endif; ?>
                            
                            <?php if ($reserva->estado === 'pendiente'): ?>
                            <button class="btn-accion btn-cancelar" onclick="cancelarReserva(<?php echo $reserva->id_reserva; ?>)">
                                <i class="fas fa-times"></i>Cancelar
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div id="modalEstado" class="modal-estado">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Cambiar Estado de Reserva</h3>
            <button class="close-modal" onclick="cerrarModal()">&times;</button>
        </div>
        <form id="formCambiarEstado">
            <input type="hidden" id="reservaId" name="reservaId">
            <div class="form-group">
                <label class="form-label" for="nuevoEstado">Nuevo Estado:</label>
                <select class="form-select" id="nuevoEstado" name="nuevoEstado">
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="completada">Completada</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <button type="submit" class="btn-guardar">
                <i class="fas fa-save me-2"></i>Guardar Cambios
            </button>
        </form>
    </div>
</div>

<script>
function verDetalles(reservaId) {
    // Aquí puedes implementar la lógica para mostrar detalles
    alert('Ver detalles de la reserva #' + reservaId);
    // Ejemplo: window.location.href = '?controlador=reservas&accion=detalle&id=' + reservaId;
}

function abrirModalEstado(reservaId, estadoActual) {
    document.getElementById('reservaId').value = reservaId;
    document.getElementById('nuevoEstado').value = estadoActual;
    document.getElementById('modalEstado').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalEstado').style.display = 'none';
}

function cancelarReserva(reservaId) {
    if (confirm('¿Estás seguro de que quieres cancelar esta reserva?')) {
        // Aquí implementar la lógica de cancelación
        alert('Reserva #' + reservaId + ' cancelada');
        // Ejemplo: window.location.href = '?controlador=reservas&accion=cancelar&id=' + reservaId;
    }
}

// Manejar envío del formulario
document.getElementById('formCambiarEstado').addEventListener('submit', function(e) {
    e.preventDefault();
    const reservaId = document.getElementById('reservaId').value;
    const nuevoEstado = document.getElementById('nuevoEstado').value;
    
    // Aquí implementar la lógica para cambiar el estado
    alert('Estado de reserva #' + reservaId + ' cambiado a: ' + nuevoEstado);
    cerrarModal();
    
    // Ejemplo de envío real:
    // fetch('?controlador=reservas&accion=cambiarEstado', {
    //     method: 'POST',
    //     body: new FormData(this)
    // }).then(() => location.reload());
});

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modalEstado');
    if (event.target === modal) {
        cerrarModal();
    }
}
</script>
