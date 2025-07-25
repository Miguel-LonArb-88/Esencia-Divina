
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
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="login-button">Registrarse</button>
      <p class="forgot-password">¿Ya tienes cuenta? <a href="?controlador=usuarios&accion=login">Inicia sesión</a></p>
    </form>
    <div id="error-message" class="error-message"></div>
  </div>
</div>