

<link rel="stylesheet" href="assets/estilos/servicios.css">
<script src="assets/js/servicios.js" defer></script>

<div class="servicios-container">
    <div class="servicios-header">
        <h1><i class="fas fa-spa"></i> Nuestros Servicios</h1>
        <p>Descubre nuestra variedad de tratamientos especializados para tu bienestar</p>
        <div class="servicios-filtros">
            <input type="text" id="buscar-servicio" class="filtro-busqueda" 
                   placeholder="Buscar por nombre, descripción o categoría..." 
                   autocomplete="off"
                   aria-label="Buscar servicios">
            <select id="ordenar-precio" class="filtro-orden">
                <option value="">Ordenar por precio</option>
                <option value="asc">Menor a mayor</option>
                <option value="desc">Mayor a menor</option>
            </select>
        </div>
    </div>

    <div class="servicios-grid">

        <!-- Este foreach sirve para mostrar cada uno de los servicios disponibles en forma de tarjeta -->
        <?php foreach($servicios as $servicio): ?>
        <div class="servicio-card" data-id="<?php echo $servicio->id_servicio; ?>">
            <div class="servicio-imagen">
                <img src="assets/img/masajes/default-service.jpg" alt="<?php echo $servicio->nombre; ?>">
                <div class="servicio-duracion">
                    <i class="far fa-clock"></i> <?php echo $servicio->duracion; ?> minutos
                </div>

            </div>
            
            <div class="servicio-contenido">
                <div class="servicio-header">
                    <h2><?php echo $servicio->nombre; ?></h2>
                </div>

                <p class="servicio-descripcion"><?php echo $servicio->descripcion; ?></p>


                <div class="precio-container">
                    <span class="precio"><?php echo number_format($servicio->precio, 0, ',', '.'); ?></span>
                </div>
            </div>
            <div class="servicio-footer">
                <div class="acciones">
                    <div class="accion-grupo">
                        <button class="btn-detalles" onclick="mostrarDetalles(<?php echo $servicio->id_servicio; ?>)">
                            <span class="btn-icono"><i class="fas fa-info-circle"></i></span>
                        </button>
                        <button type="submit" class="btn-reservar" form="form-carrito-<?php echo $servicio->id_servicio; ?>">
                            <span class="btn-icono"><i class="fas fa-shopping-cart"></i></span>
                            <span class="btn-texto">Añadir al Carrito</span>
                        </button>
                        <form id="form-carrito-<?php echo $servicio->id_servicio; ?>" method="POST" action="?controlador=carrito&accion=añadir" class="form-carrito" style="display: none;">
                            <input type="hidden" name="id_servicio" value="<?= $servicio->id_servicio ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
