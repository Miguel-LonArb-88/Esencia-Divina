<?php
    if (!isset($_SESSION['id'])) {
        header('Location: ?controlador=usuarios&accion=login');
        exit;
    }
?>

<link rel="stylesheet" href="assets/estilos/perfil.css">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card profile-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">Mi Perfil</h4>
                            <small>Gestiona tu información personal</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(isset($mensaje)): ?>
                        <div class="alert alert-<?php echo $mensaje['tipo']; ?>">
                            <?php echo $mensaje['texto']; ?>
                        </div>
                    <?php endif; ?>

                    <form action="?controlador=usuarios&accion=actualizarPerfil" method="POST" class="needs-validation" novalidate>
                        <div class="form-section mb-4">
                            <div class="section-header">
                                <h5 class="section-title">
                                    <i class="fas fa-user-edit"></i>Información Personal
                                </h5>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">
                                            <i class="fas fa-user me-2"></i>Nombre
                                        </label>
                                        <input type="text" class="form-control custom-input" id="nombre" name="nombre" 
                                               value="<?php echo $_SESSION['nombre']; ?>" required 
                                               placeholder="Tu nombre">
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-2"></i>Por favor ingrese su nombre
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                        </label>
                                        <input type="email" class="form-control custom-input" id="email" name="email" 
                                               value="<?php echo $_SESSION['email']; ?>" required 
                                               placeholder="tu@email.com">
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-2"></i>Por favor ingrese un correo electrónico válido
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mt-4">
                            <div class="section-header">
                                <h5 class="section-title">
                                    <i class="fas fa-lock"></i>Cambiar Contraseña
                                </h5>
                                <small>Dejar en blanco para mantener la contraseña actual</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-key me-2"></i>Nueva Contraseña
                                        </label>
                                        <input type="password" class="form-control custom-input" id="password" name="password" 
                                               minlength="6" placeholder="Nueva contraseña">
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-2"></i>La contraseña debe tener al menos 6 caracteres
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="password_confirm" class="form-label">
                                            <i class="fas fa-check-double me-2"></i>Confirmar Nueva Contraseña
                                        </label>
                                        <input type="password" class="form-control custom-input" id="password_confirm" 
                                               name="password_confirm" placeholder="Confirmar contraseña">
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-2"></i>Las contraseñas no coinciden
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <small>Última actualización: <?php echo date('d/m/Y'); ?></small>
                            <button type="submit" class="btn btn-primary btn-update">
                                <i class="fas fa-save me-2"></i>Actualizar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    var inputs = document.querySelectorAll('.form-control')
    
    // Ocultar los mensajes de error inicialmente
    document.querySelectorAll('.invalid-feedback').forEach(function(feedback) {
        feedback.style.display = 'none';
    });
    
    // Mostrar mensajes de error solo cuando se interactúa con los campos
    inputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (!this.checkValidity()) {
                this.classList.add('is-invalid');
                var feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'flex';
                }
            } else {
                this.classList.remove('is-invalid');
                var feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            }
        });
        
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                var feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            }
        });
    });
    
    // Validación en el envío del formulario
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Mostrar mensajes de error para campos inválidos
                form.querySelectorAll(':invalid').forEach(function(field) {
                    field.classList.add('is-invalid');
                    var feedback = field.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'flex';
                    }
                });
            }
            
            // Verificar que las contraseñas coincidan si se está intentando cambiar
            var password = document.getElementById('password');
            var password_confirm = document.getElementById('password_confirm');
            if (password.value !== '' && password.value !== password_confirm.value) {
                event.preventDefault();
                password_confirm.classList.add('is-invalid');
                var feedback = password_confirm.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'flex';
                }
                password_confirm.setCustomValidity('Las contraseñas no coinciden');
            } else {
                password_confirm.setCustomValidity('');
            }
        }, false);
    });
})()
</script>