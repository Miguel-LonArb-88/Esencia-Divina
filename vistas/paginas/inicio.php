<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Establece el conjunto de caracteres UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Establece la escala inicial de la página -->
    <title>La Paso - Centro de Masajes y Bienestar</title>
    <link rel="stylesheet" href="assets/estilos/carousel.css"> <!-- Enlaza el archivo CSS -->
    <link rel="stylesheet" href="assets/estilos/inicio.css">
</head>
<body>
    <?php include_once(__DIR__ . "/../includes/carrousel-home.php"); ?>

    <section class="servicios-carrusel">
        <h2>Nuestros Servicios Destacados</h2> 
        <div class="contenedor-carrusel">
            <div class="carrusel-interno">

                <!-- Verifica si $servicios esta no esta vacio -->
                <?php if(!empty($servicios)): ?>
                    <!-- Recorre el array de servicios y crea una tarjeta para cada uno -->
                    <?php foreach($servicios as $servicio): ?>
                    <div class="tarjeta-carrusel">
                        <img src="assets/img/masajes/<?= rand(1,3) ?>.jpg" alt="<?= $servicio->nombre ?>" class="imagen-servicio">
                        <h3><?= htmlspecialchars($servicio->nombre) ?></h3>
                        <p><?= htmlspecialchars(substr($servicio->descripcion, 0, 20)) . (strlen($servicio->descripcion) > 100 ? '...' : '') ?></p>
                        <div class="precio-duracion">
                            <span>$<?= number_format($servicio->precio, 2) ?></span>
                        </div>
                        <form method="POST" action="?controlador=carrito&accion=añadir">
                            <input type="hidden" name="id_servicio" value="<?= $servicio->id_servicio ?>">
                            <button type="submit" class="btn-reservar">Añadir al Carrito</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-resultados">No hay servicios disponibles</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="beneficios">
        <h2>¿Por qué elegirnos?</h2>
        <div class="beneficios-grid">
            <div class="beneficio-item">
                <i class="fas fa-certificate"></i>
                <h3>Profesionales Certificados</h3>
                <p>Equipo altamente cualificado y con años de experiencia.</p>
            </div>
            <div class="beneficio-item">
                <i class="fas fa-spa"></i>
                <h3>Ambiente Relajante</h3>
                <p>Instalaciones diseñadas para tu máxima comodidad.</p>
            </div>
            <div class="beneficio-item">
                <i class="fas fa-heart"></i>
                <h3>Atención Personalizada</h3>
                <p>Tratamientos adaptados a tus necesidades específicas.</p>
            </div>
        </div>
    </section>

    <section class="testimonios">
        <h2>Lo que dicen nuestros clientes</h2>
        <div class="testimonios-grid">
            <?php if(!empty($comentarios)): ?>
                <?php foreach($comentarios as $comentario): ?>
                    <div class="testimonio-card">
                        <div class="cliente-info">
                            <img src="assets/img/clientes/<?= htmlspecialchars($comentario['imagen'] ?? 'default.jpg') ?>" 
                                 alt="Foto de <?= htmlspecialchars($comentario['nombre'] ?? 'Cliente') ?>">
                            <h4><?= htmlspecialchars($comentario['nombre_usuario'] ?? 'Cliente') ?></h4>
                        </div>
                        <p>"<?= htmlspecialchars($comentario['texto'] ?? '') ?>"</p>
                        <div class="rating">
                            <?php
                            // Debug: mostrar el valor de calificación
                            echo "<!-- Debug: calificacion = " . ($comentario['calificacion'] ?? 'NULL') . " -->";
                            $rating = floatval($comentario['calificacion'] ?? 0); // Cambiar de intval a floatval
                            $fullStars = floor($rating); // Estrellas completas
                            $hasHalfStar = ($rating - $fullStars) >= 0.5; // ¿Tiene media estrella?
                            
                            // Mostrar estrellas completas
                            for($i = 1; $i <= $fullStars; $i++): ?>
                                <span class="star filled">★</span>
                            <?php endfor;
                            
                            // Mostrar media estrella si es necesario
                            if($hasHalfStar): ?>
                                <span class="star half">★</span>
                                <?php $fullStars++; // Incrementar para no duplicar en estrellas vacías
                            endif;
                            
                            // Mostrar estrellas vacías
                            for($i = $fullStars + 1; $i <= 5; $i++): ?>
                                <span class="star">★</span>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-testimonios">No hay testimonios disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
<script src="assets/js/carousel.js"></script> <!-- Enlaza el archivo JavaScript -->
</html>