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
        <h2><i class="fas fa-spa"></i> Gestión de Servicios</h2>
        <button class="btn btn-primary" onclick="abrirModalAgregarServicio()">
            <i class="fas fa-plus"></i> Nuevo Servicio
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 25%;">Servicio</th>
                    <th style="width: 35%;">Descripción</th>
                    <th style="width: 12%;">Duración</th>
                    <th style="width: 12%;">Precio</th>
                    <th style="width: 12%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($servicios)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-spa fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay servicios disponibles</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($servicios as $servicio): ?>
                    <tr>
                        <td class="text-center align-middle">
                            <span class="badge bg-secondary">#<?php echo $servicio->id_servicio; ?></span>
                        </td>
                        <td class="align-middle">
                            <div class="cliente-info">
                                <div class="fw-bold text-primary"><?php echo htmlspecialchars($servicio->nombre); ?></div>
                                <small class="text-muted d-block">
                                    <i class="fas fa-tag me-1"></i>Servicio de spa
                                </small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="text-muted"><?php echo htmlspecialchars(substr($servicio->descripcion, 0, 50)) . (strlen($servicio->descripcion) > 50 ? '...' : ''); ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge bg-info text-white px-3 py-2">
                                <i class="fas fa-clock me-1"></i><?php echo $servicio->duracion ?? '60'; ?> min
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <div class="total-info">
                                <span class="fw-bold text-success fs-5">€<?php echo number_format($servicio->precio, 0, ',', '.'); ?></span>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-info" onclick="verServicio(<?php echo $servicio->id_servicio; ?>)" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" onclick="editarServicio(<?php echo $servicio->id_servicio; ?>)" title="Editar servicio">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" onclick="eliminarServicio(<?php echo $servicio->id_servicio; ?>, '<?php echo htmlspecialchars($servicio->nombre); ?>')" title="Eliminar servicio">
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

<!-- Modal Agregar Servicio -->
<div class="modal fade" id="modalServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalServicioTitle">
                    <i class="fas fa-spa"></i>
                    Agregar Nuevo Servicio
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formServicio" method="POST" action="?controlador=admin&accion=servicios">
                    <input type="hidden" id="servicioId" name="id">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-tag me-2"></i>Nombre del Servicio
                                </label>
                                <input type="text" class="form-control" id="nombreServicio" name="nombre" required 
                                       placeholder="Ej: Masaje Relajante">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-clock me-2"></i>Duración (min)
                                </label>
                                <input type="number" class="form-control" id="duracionServicio" name="duracion" required 
                                       placeholder="60" min="15" max="300">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-align-left me-2"></i>Descripción
                        </label>
                        <textarea class="form-control" id="descripcionServicio" name="descripcion" rows="3" required 
                                  placeholder="Describe los beneficios y características del servicio..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-euro-sign me-2"></i>Precio (€)
                                </label>
                                <input type="number" class="form-control" id="precioServicio" name="precio" required 
                                       placeholder="50.00" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-list me-2"></i>Categoría
                                </label>
                                <select class="form-select" name="categoria">
                                    <option value="">Seleccionar categoría...</option>
                                    <option value="masaje">🤲 Masajes</option>
                                    <option value="facial">✨ Tratamientos Faciales</option>
                                    <option value="corporal">💆 Tratamientos Corporales</option>
                                    <option value="relajacion">🧘 Relajación</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-building me-2"></i>Spa
                                </label>
                                <select class="form-select" id="spaServicio" name="id_spa" required>
                                    <?php if (isset($spas) && !empty($spas)): ?>
                                        <?php foreach($spas as $spa): ?>
                                            <option value="<?php echo $spa->id_spa; ?>"><?php echo htmlspecialchars($spa->nombre); ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="1">Spa Principal</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-lightbulb me-2"></i>
                        <div>
                            <strong>Consejo:</strong> Una buena descripción ayuda a los clientes a entender mejor el servicio.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" form="formServicio" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Servicio
                </button>
            </div>
        </div>
    </div>
</div>


</div>

<!-- Modal Ver Detalles Servicio -->
<div class="modal fade" id="modalDetallesServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-spa"></i>
                    Detalles del Servicio
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-hashtag me-2"></i>ID del Servicio
                            </label>
                            <p class="form-control-plaintext" id="detalleIdServicio"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-2"></i>Nombre del Servicio
                            </label>
                            <p class="form-control-plaintext" id="detalleNombreServicio"></p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-align-left me-2"></i>Descripción
                    </label>
                    <p class="form-control-plaintext" id="detalleDescripcionServicio"></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-clock me-2"></i>Duración
                            </label>
                            <p class="form-control-plaintext" id="detalleDuracionServicio"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-euro-sign me-2"></i>Precio
                            </label>
                            <p class="form-control-plaintext" id="detallePrecioServicio"></p>
                        </div>
                    </div>
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

<script src="assets/js/admin-funciones.js"></script>
<script>
// Función para validar el formulario de servicio
function validarFormularioServicio() {
    const nombre = document.getElementById('nombreServicio').value.trim();
    const descripcion = document.getElementById('descripcionServicio').value.trim();
    const precio = parseFloat(document.getElementById('precioServicio').value);
    const duracion = parseInt(document.getElementById('duracionServicio').value);
    
    // Validar nombre
    if (nombre.length < 3) {
        mostrarNotificacion('El nombre del servicio debe tener al menos 3 caracteres', 'error');
        return false;
    }
    
    // Validar descripción
    if (descripcion.length < 10) {
        mostrarNotificacion('La descripción debe tener al menos 10 caracteres', 'error');
        return false;
    }
    
    // Validar precio
    if (isNaN(precio) || precio <= 0) {
        mostrarNotificacion('El precio debe ser un número mayor a 0', 'error');
        return false;
    }
    
    if (precio > 1000) {
        mostrarNotificacion('El precio no puede ser mayor a €1000', 'error');
        return false;
    }
    
    // Validar duración
    if (isNaN(duracion) || duracion < 15 || duracion > 300) {
        mostrarNotificacion('La duración debe estar entre 15 y 300 minutos', 'error');
        return false;
    }
    
    return true;
}

// Event listener para el formulario de servicio
document.addEventListener('DOMContentLoaded', function() {
    const formServicio = document.getElementById('formServicio');
    if (formServicio) {
        formServicio.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar formulario antes de enviar
            if (!validarFormularioServicio()) {
                return;
            }
            
            const formData = new FormData(this);
            const servicioId = document.getElementById('servicioId').value;
            
            if (servicioId) {
                formData.append('accion', 'actualizar');
                formData.append('id', servicioId);
            } else {
                formData.append('accion', 'crear');
            }
            
            // Mostrar indicador de carga
            const submitBtn = document.querySelector('#formServicio button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
            submitBtn.disabled = true;
            
            fetch('?controlador=admin&accion=servicios', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    mostrarNotificacion('Servicio guardado correctamente', 'success');
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalServicio'));
                    if (modal) {
                        modal.hide();
                    }
                    // Recargar página después de un breve delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarNotificacion(data.message || 'Error al guardar el servicio', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión. Por favor, inténtalo de nuevo.', 'error');
            })
            .finally(() => {
                // Restaurar botón
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>
