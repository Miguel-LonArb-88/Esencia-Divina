<div class="login-container">
  <div class="login-box">
    <h2>Registro de Usuario</h2>
    <form id="registerForm" action="" method="POST" novalidate>
      <div class="input-group">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required minlength="3" maxlength="20" autocomplete="username">
        <span class="input-feedback" id="username-feedback"></span>
      </div>
      <div class="input-group">
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required autocomplete="email">
        <span class="input-feedback" id="email-feedback"></span>
      </div>
      <div class="input-group">
        <label for="phone">Número de teléfono:</label>
        <input type="tel" id="phone" name="phone" required>
        <span class="input-feedback" id="phone-feedback"></span>
      </div>
      <div class="input-group">
        <label for="password">Contraseña:</label>
        <div class="password-container">
          <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
          <span class="toggle-password" onclick="togglePassword('password')" aria-label="Mostrar/ocultar contraseña">
            <svg id="eye-icon-password" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </span>
        </div>
        <div class="password-strength" id="password-strength">
          <div class="strength-bar">
            <div class="strength-fill" id="strength-fill"></div>
          </div>
          <span class="strength-text" id="strength-text">Ingresa una contraseña</span>
        </div>
        <span class="input-feedback" id="password-feedback"></span>
      </div>
      <div class="input-group">
        <label for="confirm-password">Confirmar contraseña:</label>
        <div class="password-container">
          <input type="password" id="confirm-password" name="confirm-password" required autocomplete="new-password">
          <span class="toggle-password" onclick="togglePassword('confirm-password')" aria-label="Mostrar/ocultar confirmación de contraseña">
            <svg id="eye-icon-confirm-password" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </span>
        </div>
        <span class="input-feedback" id="confirm-password-feedback"></span>
      </div>
      <div class="input-group">
        <label class="checkbox-container">
          <input type="checkbox" id="terms" name="terms" required>
          <span class="checkmark"></span>
          Acepto los <a href="?controlador=paginas&accion=terminos" target="_blank" rel="noopener">Términos y Condiciones</a>
        </label>
        <span class="input-feedback" id="terms-feedback"></span>
      </div>
      <button type="submit" class="login-button" id="submit-btn">
        <span class="btn-text">Registrarse</span>
        <span class="btn-loading" style="display: none;">Registrando...</span>
      </button>
      <p class="forgot-password">¿Ya tienes cuenta? <a href="?controlador=usuarios&accion=login">Inicia sesión</a></p>
    </form>
    <div id="error-message" class="error-message"></div>
    <div id="success-message" class="success-message" style="display: none;"></div>
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

  // Validación de usuario
  function validateUsername(username) {
    if (username.length < 3) {
      return {
        valid: false,
        message: 'El usuario debe tener al menos 3 caracteres'
      };
    }
    if (username.length > 20) {
      return {
        valid: false,
        message: 'El usuario no puede tener más de 20 caracteres'
      };
    }
    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
      return {
        valid: false,
        message: 'Solo se permiten letras, números y guiones bajos'
      };
    }
    return {
      valid: true,
      message: 'Usuario válido'
    };
  }

  // Validación de email
  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return {
        valid: false,
        message: 'Ingresa un correo electrónico válido'
      };
    }
    return {
      valid: true,
      message: 'Correo válido'
    };
  }

  // Función para evaluar fortaleza de contraseña
  function checkPasswordStrength(password) {
    let score = 0;
    let feedback = [];

    if (password.length >= 8) score += 1;
    else feedback.push('al menos 8 caracteres');

    if (/[a-z]/.test(password)) score += 1;
    else feedback.push('una letra minúscula');

    if (/[A-Z]/.test(password)) score += 1;
    else feedback.push('una letra mayúscula');

    if (/[0-9]/.test(password)) score += 1;
    else feedback.push('un número');

    if (/[^a-zA-Z0-9]/.test(password)) score += 1;
    else feedback.push('un carácter especial');

    const strength = ['muy débil', 'débil', 'regular', 'buena', 'fuerte'][score];
    const color = ['#ff4757', '#ff6b7a', '#ffa502', '#2ed573', '#1dd1a1'][score];

    return {
      score,
      strength,
      color,
      feedback: feedback.length > 0 ? `Falta: ${feedback.join(', ')}` : 'Contraseña segura'
    };
  }

  // Actualizar indicador de fortaleza
  function updatePasswordStrength(password) {
    const strengthResult = checkPasswordStrength(password);
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    strengthFill.style.width = `${(strengthResult.score / 5) * 100}%`;
    strengthFill.style.backgroundColor = strengthResult.color;
    strengthText.textContent = password ? `${strengthResult.strength} - ${strengthResult.feedback}` : 'Ingresa una contraseña';
    strengthText.style.color = strengthResult.color;
  }

  // Event listeners para validación en tiempo real
  document.addEventListener('DOMContentLoaded', function() {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const termsCheckbox = document.getElementById('terms');
    const form = document.getElementById('registerForm');

    // Validación de usuario
    usernameInput.addEventListener('input', function() {
      const result = validateUsername(this.value);
      if (this.value) {
        showFeedback('username', result.message, result.valid ? 'success' : 'error');
      } else {
        clearFeedback('username');
      }
    });

    // Validación de email
    emailInput.addEventListener('input', function() {
      if (this.value) {
        const result = validateEmail(this.value);
        showFeedback('email', result.message, result.valid ? 'success' : 'error');
      } else {
        clearFeedback('email');
      }
    });

    // Validación de contraseña y fortaleza
    passwordInput.addEventListener('input', function() {
      updatePasswordStrength(this.value);

      if (this.value) {
        const strengthResult = checkPasswordStrength(this.value);
        if (strengthResult.score < 3) {
          showFeedback('password', 'Contraseña muy débil', 'error');
        } else {
          showFeedback('password', 'Contraseña aceptable', 'success');
        }
      } else {
        clearFeedback('password');
      }

      // Revalidar confirmación si ya tiene valor
      if (confirmPasswordInput.value) {
        validatePasswordConfirmation();
      }
    });

    // Validación de confirmación de contraseña
    function validatePasswordConfirmation() {
      const password = passwordInput.value;
      const confirmPassword = confirmPasswordInput.value;

      if (confirmPassword) {
        if (password === confirmPassword) {
          showFeedback('confirm-password', 'Las contraseñas coinciden', 'success');
        } else {
          showFeedback('confirm-password', 'Las contraseñas no coinciden', 'error');
        }
      } else {
        clearFeedback('confirm-password');
      }
    }

    confirmPasswordInput.addEventListener('input', validatePasswordConfirmation);

    // Validación de términos
    termsCheckbox.addEventListener('change', function() {
      if (this.checked) {
        showFeedback('terms', 'Términos aceptados', 'success');
      } else {
        showFeedback('terms', 'Debes aceptar los términos y condiciones', 'error');
      }
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      if (isSubmitting) return;

      // Validar todos los campos
      const usernameResult = validateUsername(usernameInput.value);
      const emailResult = validateEmail(emailInput.value);
      const passwordStrength = checkPasswordStrength(passwordInput.value);
      const passwordsMatch = passwordInput.value === confirmPasswordInput.value;
      const termsAccepted = termsCheckbox.checked;

      let isValid = true;

      // Mostrar errores
      if (!usernameResult.valid) {
        showFeedback('username', usernameResult.message, 'error');
        isValid = false;
      }

      if (!emailResult.valid) {
        showFeedback('email', emailResult.message, 'error');
        isValid = false;
      }

      if (passwordStrength.score < 3) {
        showFeedback('password', 'La contraseña es muy débil', 'error');
        isValid = false;
      }

      if (!passwordsMatch) {
        showFeedback('confirm-password', 'Las contraseñas no coinciden', 'error');
        isValid = false;
      }

      if (!termsAccepted) {
        showFeedback('terms', 'Debes aceptar los términos y condiciones', 'error');
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
          firstError.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
        }
      }
    });
  });
</script>