<?php
    include_once "modelos/usuario.php";
    $usuarios = Usuario::listarUsuarios();
?>

<div class="admin-dashboard">
    <div class="admin-container">
    <div class="admin-header">
        <h2><i class="fas fa-users"></i> Gestión de Usuarios</h2>
        <button class="btn btn-primary" onclick="abrirModalAgregarUsuario()">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Mejoras en la estructura de la tabla de usuarios -->
    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 25%;">Usuario</th>
                    <th style="width: 30%;">Email</th>
                    <th style="width: 15%;">Tipo</th>
                    <th style="width: 15%;">Estado</th>
                    <th style="width: 12%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay usuarios registrados</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($usuarios as $usuario): ?>
                    <tr>
                        <td class="text-center align-middle">
                            <span class="badge bg-secondary">#<?php echo $usuario->id; ?></span>
                        </td>
                        <td class="align-middle">
                            <div class="cliente-info">
                                <div class="fw-bold text-primary"><?php echo htmlspecialchars($usuario->nombre); ?></div>
                                <small class="text-muted d-block">
                                    <i class="fas fa-user me-1"></i>Registrado: <?php echo date('d/m/Y', strtotime($usuario->fecha_registro ?? 'now')); ?>
                                </small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="fw-semibold"><?php echo htmlspecialchars($usuario->email); ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge <?php echo $usuario->tipo === 'admin' ? 'bg-danger' : 'bg-info'; ?> px-3 py-2">
                                <i class="fas <?php echo $usuario->tipo === 'admin' ? 'fa-crown' : 'fa-user'; ?> me-1"></i>
                                <?php echo ucfirst($usuario->tipo); ?>
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Activo
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-info" onclick="verUsuario(<?php echo $usuario->id; ?>)" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" onclick="editarUsuario(<?php echo $usuario->id; ?>)" title="Editar usuario">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" onclick="eliminarUsuario(<?php echo $usuario->id; ?>, '<?php echo htmlspecialchars($usuario->nombre); ?>')" title="Eliminar usuario">
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

<!-- Modal Agregar Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioTitle">
                    <i class="fas fa-user-plus"></i>
                    Agregar Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formUsuario" method="POST">
                    <input type="hidden" id="usuarioId" name="usuarioId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-2"></i>Nombre Completo
                                </label>
                                <input type="text" class="form-control" id="nombreUsuario" name="nombre" required 
                                       placeholder="Ingrese el nombre completo">
                                <div class="invalid-feedback">Por favor ingrese un nombre válido.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control" id="emailUsuario" name="email" required 
                                       placeholder="usuario@ejemplo.com">
                                <div class="invalid-feedback">Por favor ingrese un email válido.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <input type="password" class="form-control" id="claveUsuario" name="clave" required 
                                       placeholder="Mínimo 6 caracteres">
                                <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user-tag me-2"></i>Tipo de Usuario
                                </label>
                                <select class="form-select" id="tipoUsuario" name="tipo" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="cliente">👤 Cliente</option>
                                    <option value="admin">👑 Administrador</option>
                                </select>
                                <div class="invalid-feedback">Por favor seleccione un tipo de usuario.</div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <strong>Nota:</strong> El usuario recibirá un email de bienvenida con sus credenciales de acceso.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" form="formUsuario" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Usuario
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles Usuario -->
<div class="modal fade" id="modalDetallesUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle"></i>
                    Detalles del Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-hashtag me-2"></i>ID de Usuario
                            </label>
                            <p class="form-control-plaintext" id="detalleIdUsuario"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user-tag me-2"></i>Tipo de Usuario
                            </label>
                            <p class="form-control-plaintext" id="detalleTipoUsuario"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Nombre Completo
                            </label>
                            <p class="form-control-plaintext" id="detalleNombreUsuario"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-envelope me-2"></i>Correo Electrónico
                            </label>
                            <p class="form-control-plaintext" id="detalleEmailUsuario"></p>
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

</div>

<script src="assets/js/admin-funciones.js"></script>