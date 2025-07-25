


<div class="login-container">
  <div class="login-box">
    <h2>Iniciar Sesión</h2>
    <form id="loginForm" action="" method="POST">
      <div class="input-group">
        <label for="email">Correo:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="input-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="login-button" name="submit">Entrar</button>
      <p class="forgot-password"><a href="#">¿Olvidaste tu contraseña?</a></p>
      <p class="forgot-password">¿Aun no tienes cuenta? <a href="?controlador=usuarios&accion=registro"><span> </span>Registrate aqui</a></p>
    </form>
    <div id="error-message" class="error-message"></div>
  </div>
</div>

