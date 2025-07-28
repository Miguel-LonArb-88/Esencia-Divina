
<div class="login-container">
  <div class="login-box">
    <h2>Registro de Usuario</h2>
    <form id="registerForm" action="" method="POST">
      <div class="input-group">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="input-group">
        <label for="email">Correo:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="input-group">
        <label for="password">Contraseña:</label>
        <div class="password-container">
          <input type="password" id="password" name="password" required>
          <span class="toggle-password" onclick="togglePassword('password')">
            <svg id="eye-icon-password" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </span>
        </div>
      </div>
      <div class="input-group">
        <label class="checkbox-container">
          <input type="checkbox" id="terms" name="terms" required>
          <span class="checkmark"></span>
          Acepto los <a href="?controlador=paginas&accion=terminos" target="_blank">Términos y Condiciones</a>
        </label>
      </div>
      <button type="submit" class="login-button">Registrarse</button>
      <p class="forgot-password">¿Ya tienes cuenta? <a href="?controlador=usuarios&accion=login">Inicia sesión</a></p>
    </form>
    <div id="error-message" class="error-message"></div>
  </div>
</div>

<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById('eye-icon-' + inputId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        `;
    }
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const termsCheckbox = document.getElementById('terms');
    const errorMessage = document.getElementById('error-message');
    
    form.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            errorMessage.textContent = 'Debes aceptar los términos y condiciones para registrarte.';
            errorMessage.style.display = 'block';
            return false;
        }
        errorMessage.style.display = 'none';
    });
    
    // Limpiar mensaje de error cuando se marca el checkbox
    termsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            errorMessage.style.display = 'none';
        }
    });
});
</script>