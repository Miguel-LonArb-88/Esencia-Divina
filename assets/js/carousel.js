document.addEventListener('DOMContentLoaded', function() {
    // Obtener elementos del carrusel
    const carousel = document.querySelector('.carousel');
    if (!carousel) return; // Salir si el carrusel no existe en la página
    
    // Obtener elementos del carrusel
    const slides = carousel.querySelectorAll('.carousel-slide');
    const prevBtn = carousel.querySelector('.carousel-control.prev');
    const nextBtn = carousel.querySelector('.carousel-control.next');
    const indicators = carousel.querySelectorAll('.indicator');
    
    // Inicializar variables para el carrusel
    let currentSlide = 0;
    let slideInterval;
    const intervalTime = 5000; // Tiempo entre transiciones automáticas de diapositivas (5 segundos)
    
    // Inicializar el carrusel
    function initCarousel() {
        // Configurar event listeners
        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);
        
        // Configurar botones indicadores
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                goToSlide(index);
                resetInterval();
            });
        });
        
        // Iniciar presentación automática
        startSlideshow();
        
        // Pausar presentación al pasar el mouse
        carousel.addEventListener('mouseenter', pauseSlideshow);
        carousel.addEventListener('mouseleave', startSlideshow);
        
        // Manejar gestos de deslizamiento para móviles
        setupSwipeGestures();
    }
    
    // Ir a una diapositiva específica
    function goToSlide(slideIndex) {
        // Remover clase active de todas las diapositivas e indicadores
        slides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Agregar clase active a la diapositiva e indicador actual
        slides[slideIndex].classList.add('active');
        indicators[slideIndex].classList.add('active');
        
        // Actualizar índice de diapositiva actual
        currentSlide = slideIndex;
    }
    
    // Ir a la siguiente diapositiva
    function nextSlide() {
        let nextIndex = currentSlide + 1;
        if (nextIndex >= slides.length) {
            nextIndex = 0; // Volver a la primera diapositiva
        }
        goToSlide(nextIndex);
        resetInterval();
    }
    
    // Ir a la diapositiva anterior
    function prevSlide() {
        let prevIndex = currentSlide - 1;
        if (prevIndex < 0) {
            prevIndex = slides.length - 1; // Ir a la última diapositiva
        }
        goToSlide(prevIndex);
        resetInterval();
    }
    
    // Iniciar la presentación automática
    function startSlideshow() {
        // Limpiar cualquier intervalo existente
        if (slideInterval) {
            clearInterval(slideInterval);
        }
        
        // Establecer nuevo intervalo
        slideInterval = setInterval(nextSlide, intervalTime);
    }
    
    // Pausar la presentación automática
    function pauseSlideshow() {
        if (slideInterval) {
            clearInterval(slideInterval);
            slideInterval = null;
        }
    }
    
    // Reiniciar el temporizador de intervalo (llamado después de navegación manual)
    function resetInterval() {
        pauseSlideshow();
        startSlideshow();
    }
    
    // Configurar gestos táctiles de deslizamiento para dispositivos móviles
    function setupSwipeGestures() {
        let touchStartX = 0;
        let touchEndX = 0;
        
        carousel.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});
        
        carousel.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {passive: true});
        
        function handleSwipe() {
            const swipeThreshold = 50; // Distancia mínima para un deslizamiento
            
            // Deslizamiento a la izquierda (siguiente diapositiva)
            if (touchEndX < touchStartX - swipeThreshold) {
                nextSlide();
            }
            
            // Deslizamiento a la derecha (diapositiva anterior)
            if (touchEndX > touchStartX + swipeThreshold) {
                prevSlide();
            }
        }
    }
    
    // Inicializar el carrusel
    initCarousel();
});