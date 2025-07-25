

<div class="admin-dashboard">
    <div class="admin-container">
    <div class="admin-header">
        <h2><i class="fas fa-hot-tub"></i> Gestión de Spas</h2>
        <button class="btn btn-primary" onclick="abrirModalAgregarSpa()">
            <i class="fas fa-plus"></i> Nuevo Spa
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 25%;">Spa</th>
                    <th style="width: 30%;">Dirección</th>
                    <th style="width: 15%;">Teléfono</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 12%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($spas)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-hot-tub fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay spas registrados</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($spas as $spa): ?>
                    <tr>
                        <td class="text-center align-middle">
                            <span class="badge bg-secondary">#<?php echo $spa->id; ?></span>
                        </td>
                        <td class="align-middle">
                            <div class="cliente-info">
                                <div class="fw-bold text-primary"><?php echo htmlspecialchars($spa->nombre); ?></div>
                                <small class="text-muted d-block">
                                    <i class="fas fa-building me-1"></i>Centro de bienestar
                                </small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="fw-semibold"><?php echo htmlspecialchars($spa->direccion); ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge bg-info text-white px-3 py-2">
                                <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($spa->telefono); ?>
                            </span>
                        </td>
                        <td class="align-middle">
                            <span class="text-muted"><?php echo htmlspecialchars($spa->email); ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-info btn-sm" onclick="verSpa(<?php echo $spa->id; ?>)" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="editarSpa(<?php echo $spa->id; ?>)" title="Editar spa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarSpa(<?php echo $spa->id; ?>, '<?php echo htmlspecialchars($spa->nombre); ?>')" title="Eliminar spa">
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

<!-- Modal Spa -->
<div class="modal fade" id="modalSpa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSpaTitle">
                    <i class="fas fa-hot-tub"></i>
                    Agregar Nuevo Spa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formSpa" method="POST">
                    <input type="hidden" id="spaId" name="spaId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-building me-2"></i>Nombre del Spa
                                </label>
                                <input type="text" class="form-control" id="nombreSpa" name="nombre" required 
                                       placeholder="Ej: Spa Relajación Total">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone me-2"></i>Teléfono
                                </label>
                                <input type="tel" class="form-control" id="telefonoSpa" name="telefono" required 
                                       placeholder="Ej: +34 123 456 789">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Dirección
                        </label>
                        <input type="text" class="form-control" id="direccionSpa" name="direccion" required 
                               placeholder="Ej: Calle Principal 123, Ciudad">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control" id="emailSpa" name="email" required 
                               placeholder="Ej: contacto@spa.com">
                    </div>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <strong>Nota:</strong> Asegúrate de que todos los datos sean correctos antes de guardar.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" form="formSpa" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Spa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles Spa -->
<div class="modal fade" id="modalDetallesSpa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-hot-tub"></i>
                    Detalles del Spa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-hashtag me-2"></i>ID del Spa
                            </label>
                            <p class="form-control-plaintext" id="detalleIdSpa"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-building me-2"></i>Nombre del Spa
                            </label>
                            <p class="form-control-plaintext" id="detalleNombreSpa"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-phone me-2"></i>Teléfono
                            </label>
                            <p class="form-control-plaintext" id="detalleTelefonoSpa"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <p class="form-control-plaintext" id="detalleEmailSpa"></p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-map-marker-alt me-2"></i>Dirección
                    </label>
                    <p class="form-control-plaintext" id="detalleDireccionSpa"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
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