


<div class="login-container">
  <div class="login-box">
    <h2>Iniciar Sesión</h2>
    <form id="loginForm" action="" method="POST" novalidate>
      <input type="hidden" name="login_submit" value="1">
      <div class="input-group">
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required autocomplete="email">
        <span class="input-feedback" id="email-feedback"></span>
      </div>
      <div class="input-group">
        <label for="password">Contraseña:</label>
        <div class="password-container">
          <input type="password" id="password" name="password" required autocomplete="current-password">
          <span class="toggle-password" onclick="togglePassword('password')" aria-label="Mostrar/ocultar contraseña">
            <svg id="eye-icon-password" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </span>
        </div>
        <span class="input-feedback" id="password-feedback"></span>
      </div>
      <button type="submit" class="login-button" id="submit-btn">
        <span class="btn-text">Iniciar Sesión</span>
        <span class="btn-loading" style="display: none;">Iniciando sesión...</span>
      </button>
      <p class="forgot-password"><a href="#" onclick="showForgotPassword()">¿Olvidaste tu contraseña?</a></p>
      <p class="forgot-password">¿No tienes cuenta? <a href="?controlador=usuarios&accion=registro">Regístrate</a></p>
    </form>
    <div id="error-message" class="error-message"></div>
    <div id="success-message" class="success-message" style="display: none;"></div>
    
    <!-- Modal para recuperar contraseña -->
    <div id="forgot-password-modal" class="modal" style="display: none;">
      <div class="modal-content">
        <span class="close" onclick="closeForgotPassword()">&times;</span>
        <h3>Recuperar Contraseña</h3>
        <p>Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña.</p>
        <form id="forgotPasswordForm">
          <div class="input-group">
            <label for="forgot-email">Correo electrónico:</label>
            <input type="email" id="forgot-email" name="forgot-email" required>
            <span class="input-feedback" id="forgot-email-feedback"></span>
          </div>
          <button type="submit" class="login-button">
            <span class="btn-text">Enviar instrucciones</span>
            <span class="btn-loading" style="display: none;">Enviando...</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
let isSubmitting = false;

// Función para mostrar/ocultar contraseña
function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById('eye-icon-' + inputId);
  
  if (input.type === 'password') {
    input.type = 'text';
    icon.innerHTML = `
      <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
      <line x1="1" y1="1" x2="23" y2="23"></line>
    `;
  } else {
    input.type = 'password';
    icon.innerHTML = `
      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
      <circle cx="12" cy="12" r="3"></circle>
    `;
  }
}

// Función para mostrar feedback
function showFeedback(inputId, message, type = 'error') {
  const feedback = document.getElementById(inputId + '-feedback');
  const input = document.getElementById(inputId);
  
  feedback.textContent = message;
  feedback.className = `input-feedback ${type}`;
  
  if (type === 'error') {
    input.classList.add('error');
    input.classList.remove('success');
  } else if (type === 'success') {
    input.classList.add('success');
    input.classList.remove('error');
  } else {
    input.classList.remove('error', 'success');
  }
}

// Función para limpiar feedback
function clearFeedback(inputId) {
  const feedback = document.getElementById(inputId + '-feedback');
  const input = document.getElementById(inputId);
  
  feedback.textContent = '';
  feedback.className = 'input-feedback';
  input.classList.remove('error', 'success');
}

// Validación de email
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    return { valid: false, message: 'Ingresa un correo electrónico válido' };
  }
  return { valid: true, message: 'Correo válido' };
}

// Funciones para modal de recuperación de contraseña
function showForgotPassword() {
  document.getElementById('forgot-password-modal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeForgotPassword() {
  document.getElementById('forgot-password-modal').style.display = 'none';
  document.body.style.overflow = 'auto';
  // Limpiar formulario
  document.getElementById('forgotPasswordForm').reset();
  clearFeedback('forgot-email');
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
  const modal = document.getElementById('forgot-password-modal');
  if (event.target === modal) {
    closeForgotPassword();
  }
}

// Event listeners para validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const loginForm = document.getElementById('loginForm');
  const forgotPasswordForm = document.getElementById('forgotPasswordForm');
  const forgotEmailInput = document.getElementById('forgot-email');
  
  // Validación de email en tiempo real
  emailInput.addEventListener('input', function() {
    if (this.value) {
      const result = validateEmail(this.value);
      showFeedback('email', result.message, result.valid ? 'success' : 'error');
    } else {
      clearFeedback('email');
    }
  });
  
  // Validación de contraseña
  passwordInput.addEventListener('input', function() {
    if (this.value) {
      if (this.value.length < 6) {
        showFeedback('password', 'La contraseña debe tener al menos 6 caracteres', 'error');
      } else {
        showFeedback('password', 'Contraseña válida', 'success');
      }
    } else {
      clearFeedback('password');
    }
  });
  
  // Validación del formulario de login
  loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (isSubmitting) return;
    
    const emailResult = validateEmail(emailInput.value);
    const passwordValid = passwordInput.value.length >= 6;
    
    let isValid = true;
    
    // Validar email
    if (!emailResult.valid) {
      showFeedback('email', emailResult.message, 'error');
      isValid = false;
    }
    
    // Validar contraseña
    if (!passwordValid) {
      showFeedback('password', 'La contraseña debe tener al menos 6 caracteres', 'error');
      isValid = false;
    }
    
    if (isValid) {
      // Mostrar estado de carga
      isSubmitting = true;
      const submitBtn = document.getElementById('submit-btn');
      const btnText = submitBtn.querySelector('.btn-text');
      const btnLoading = submitBtn.querySelector('.btn-loading');
      
      submitBtn.disabled = true;
      btnText.style.display = 'none';
      btnLoading.style.display = 'inline';
      
      // Enviar formulario
      this.submit();
    } else {
      // Scroll al primer error
      const firstError = document.querySelector('.input-feedback.error');
      if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  });
  
  // Validación del email en modal de recuperación
  forgotEmailInput.addEventListener('input', function() {
    if (this.value) {
      const result = validateEmail(this.value);
      showFeedback('forgot-email', result.message, result.valid ? 'success' : 'error');
    } else {
      clearFeedback('forgot-email');
    }
  });
  
  // Manejo del formulario de recuperación de contraseña
  forgotPasswordForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const emailResult = validateEmail(forgotEmailInput.value);
    
    if (emailResult.valid) {
      const submitBtn = this.querySelector('button[type="submit"]');
      const btnText = submitBtn.querySelector('.btn-text');
      const btnLoading = submitBtn.querySelector('.btn-loading');
      
      submitBtn.disabled = true;
      btnText.style.display = 'none';
      btnLoading.style.display = 'inline';
      
      // Simular envío (aquí iría la lógica real)
      setTimeout(() => {
        alert('Se han enviado las instrucciones a tu correo electrónico.');
        closeForgotPassword();
        
        // Restaurar botón
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
      }, 2000);
    } else {
      showFeedback('forgot-email', emailResult.message, 'error');
    }
  });
  
  // Manejar tecla Escape para cerrar modal
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeForgotPassword();
    }
  });
});
</script>

