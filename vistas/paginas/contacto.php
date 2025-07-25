<?php
// Check if there's a status in the URL
$showSuccessAlert = isset($_GET['status']) && $_GET['status'] === 'success';
$showErrorAlert = isset($_GET['status']) && $_GET['status'] === 'error';
?>

<link rel="stylesheet" href="assets/estilos/contacto.css">

<?php if($showSuccessAlert): ?>
<div class="alert alert-success" role="alert">
    ¡Mensaje enviado exitosamente! Nos pondremos en contacto contigo pronto.
</div>
<?php endif; ?>

<?php if($showErrorAlert): ?>
<div class="alert alert-danger" role="alert">
    Hubo un problema al enviar el mensaje. Por favor, inténtalo de nuevo o contáctanos directamente por teléfono.
</div>
<?php endif; ?>

<div class="contacto-container">
    <div class="contacto-header">
        <h1>Contáctanos</h1>
        <p>Estamos aquí para responder tus preguntas y ayudarte a programar tu cita</p>
    </div>

    <div class="contacto-grid">
        <div class="contacto-info">
            <div class="info-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Ubicación</h3>
                <p>Calle Principal #123<br>Bogotá, Colombia</p>
            </div>
            <div class="info-card">
                <i class="fas fa-phone"></i>
                <h3>Teléfono</h3>
                <p>+57 (1) 234-5678<br>+57 300 123-4567</p>
            </div>
            <div class="info-card">
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <p>info@lapaso.com<br>citas@lapaso.com</p>
            </div>
            <div class="info-card">
                <i class="fas fa-clock"></i>
                <h3>Horario</h3>
                <p>Lunes a Sábado<br>8:00 AM - 7:00 PM</p>
            </div>
        </div>

        <div class="contacto-form">
            <form action="?controlador=paginas&accion=enviarContacto" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" required>
                </div>
                
                <div class="form-group">
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" required>
                </div>
                
                <div class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn-enviar">Enviar Mensaje</button>
            </form>
        </div>
    </div>

    <!-- Mapa con google maps -->
    <div class="mapa-container">
            <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d997.1171759396401!2d-75.59516813047415!3d6.290116899527166!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e4429c26cfed7ff%3A0x808a11e6b8a3eaf5!2sLas%20Margaritas%2C%20Robledo%2C%20Medell%C3%ADn%2C%20Antioquia!5e0!3m2!1ses!2sco!4v1717316141323!5m2!1ses!2sco" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>
