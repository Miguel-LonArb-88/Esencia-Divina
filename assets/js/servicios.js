document.addEventListener('DOMContentLoaded', function() {
    const buscarInput = document.getElementById('buscar-servicio');
    const ordenarSelect = document.getElementById('ordenar-precio');
    const serviciosGrid = document.querySelector('.servicios-grid');

    // Función para filtrar servicios
    function filtrarServicios() {
        const busqueda = buscarInput.value.toLowerCase();
        const servicios = document.querySelectorAll('.servicio-card');

        servicios.forEach(servicio => {
            const nombre = servicio.querySelector('h2').textContent.toLowerCase();
            const descripcion = servicio.querySelector('.servicio-descripcion').textContent.toLowerCase();
            const categoria = servicio.querySelector('.etiqueta.categoria').textContent.toLowerCase();

            if (nombre.includes(busqueda) || descripcion.includes(busqueda) || categoria.includes(busqueda)) {
                servicio.style.display = '';
            } else {
                servicio.style.display = 'none';
            }
        });
    }

    // Función para ordenar servicios por precio
    function ordenarServicios() {
        const servicios = Array.from(document.querySelectorAll('.servicio-card'));
        const orden = ordenarSelect.value;

        servicios.sort((a, b) => {
            const precioA = parseInt(a.querySelector('.precio').textContent.replace(/[^0-9]/g, ''));
            const precioB = parseInt(b.querySelector('.precio').textContent.replace(/[^0-9]/g, ''));

            return orden === 'asc' ? precioA - precioB : precioB - precioA;
        });

        servicios.forEach(servicio => serviciosGrid.appendChild(servicio));
    }

    // Función para mostrar detalles del servicio
    function mostrarDetalles(servicioId) {
        const servicio = document.querySelector(`[data-id="${servicioId}"]`);
        const modal = document.createElement('div');
        modal.className = 'modal-servicio';
        
        const contenido = servicio.cloneNode(true);
        contenido.className = 'modal-contenido';
        
        modal.innerHTML = `
            <div class="modal-overlay">
                <div class="modal-contenido">
                    ${contenido.innerHTML}
                    <button class="btn-cerrar" onclick="this.closest('.modal-servicio').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
    }

    // Event listeners
    buscarInput.addEventListener('input', filtrarServicios);
    ordenarSelect.addEventListener('change', ordenarServicios);

    // Exponer la función mostrarDetalles globalmente
    window.mostrarDetalles = mostrarDetalles;
});