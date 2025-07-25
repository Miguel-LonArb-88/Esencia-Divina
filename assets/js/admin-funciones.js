// Funciones para el panel de administración

// ============= FUNCIONES GENERALES =============

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'success') {
    const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insertar al inicio del contenido principal
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alert);
    
    // Auto-dismiss después de 5 segundos
    setTimeout(() => {
        const alertElement = container.querySelector('.alert');
        if (alertElement) {
            alertElement.remove();
        }
    }, 5000);
}

// Función para limpiar formularios
function limpiarFormulario(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        // Limpiar campos ocultos de ID
        const idFields = form.querySelectorAll('input[type="hidden"][name*="id"]');
        idFields.forEach(field => field.value = '');
    }
}

// ============= FUNCIONES PARA USUARIOS =============

// Función para abrir modal de agregar usuario
function abrirModalAgregarUsuario() {
    limpiarFormulario('formUsuario');
    document.getElementById('modalUsuarioTitle').textContent = 'Agregar Usuario';
    document.getElementById('usuarioId').value = '';
    const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modal.show();
}

// Función para editar usuario
function editarUsuario(id) {
    fetch(`?controlador=admin&accion=usuarios&action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalUsuarioTitle').textContent = 'Editar Usuario';
                document.getElementById('usuarioId').value = data.usuario.id_usuario;
                document.getElementById('nombreUsuario').value = data.usuario.nombre;
                document.getElementById('emailUsuario').value = data.usuario.email;
                document.getElementById('tipoUsuario').value = data.usuario.tipo;
                
                const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
                modal.show();
            } else {
                mostrarNotificacion('Error al cargar los datos del usuario', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los datos del usuario', 'error');
        });
}

// Función para ver detalles de usuario
function verUsuario(id) {
    fetch(`?controlador=admin&accion=usuarios&action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const usuario = data.usuario;
                document.getElementById('detalleNombreUsuario').textContent = usuario.nombre;
                document.getElementById('detalleEmailUsuario').textContent = usuario.email;
                document.getElementById('detalleTipoUsuario').textContent = usuario.tipo === 'admin' ? 'Administrador' : 'Cliente';
                document.getElementById('detalleIdUsuario').textContent = `#${usuario.id_usuario}`;
                
                const modal = new bootstrap.Modal(document.getElementById('modalDetallesUsuario'));
                modal.show();
            } else {
                mostrarNotificacion('Error al cargar los detalles del usuario', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los detalles del usuario', 'error');
        });
}

// Función para eliminar usuario
function eliminarUsuario(id, nombre) {
    if (confirm(`¿Estás seguro de que deseas eliminar al usuario "${nombre}"?`)) {
        fetch(`?controlador=admin&accion=usuarios&action=eliminar&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion('Usuario eliminado correctamente');
                location.reload();
            } else {
                mostrarNotificacion(data.message || 'Error al eliminar el usuario', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al eliminar el usuario', 'error');
        });
    }
}

// ============= FUNCIONES PARA SERVICIOS =============

// Función para abrir modal de agregar servicio
function abrirModalAgregarServicio() {
    limpiarFormulario('formServicio');
    document.getElementById('modalServicioTitle').textContent = 'Agregar Servicio';
    document.getElementById('servicioId').value = '';
    const modal = new bootstrap.Modal(document.getElementById('modalServicio'));
    modal.show();
}

// Función para editar servicio
function editarServicio(id) {
    fetch(`?controlador=admin&accion=servicios&action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalServicioTitle').textContent = 'Editar Servicio';
                document.getElementById('servicioId').value = data.servicio.id_servicio;
                document.getElementById('nombreServicio').value = data.servicio.nombre;
                document.getElementById('descripcionServicio').value = data.servicio.descripcion;
                document.getElementById('precioServicio').value = data.servicio.precio;
                document.getElementById('duracionServicio').value = data.servicio.duracion;
                document.getElementById('spaServicio').value = data.servicio.id_spa;
                
                const modal = new bootstrap.Modal(document.getElementById('modalServicio'));
                modal.show();
            } else {
                mostrarNotificacion('Error al cargar los datos del servicio', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los datos del servicio', 'error');
        });
}

// Función para eliminar servicio
function eliminarServicio(id, nombre) {
    if (confirm(`¿Estás seguro de que deseas eliminar el servicio "${nombre}"?`)) {
        fetch(`?controlador=admin&accion=servicios&action=eliminar&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion('Servicio eliminado correctamente');
                location.reload();
            } else {
                mostrarNotificacion(data.message || 'Error al eliminar el servicio', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al eliminar el servicio', 'error');
        });
    }
}

// ============= FUNCIONES PARA SPAS =============

// Función para abrir modal de agregar spa
function abrirModalAgregarSpa() {
    limpiarFormulario('formSpa');
    document.getElementById('modalSpaTitle').textContent = 'Agregar Spa';
    document.getElementById('spaId').value = '';
    const modal = new bootstrap.Modal(document.getElementById('modalSpa'));
    modal.show();
}

// Función para editar spa
function editarSpa(id) {
    fetch(`?controlador=admin&accion=spas&action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalSpaTitle').textContent = 'Editar Spa';
                document.getElementById('spaId').value = data.spa.id_spa;
                document.getElementById('nombreSpa').value = data.spa.nombre;
                document.getElementById('direccionSpa').value = data.spa.direccion;
                document.getElementById('telefonoSpa').value = data.spa.telefono;
                document.getElementById('emailSpa').value = data.spa.email;
                
                const modal = new bootstrap.Modal(document.getElementById('modalSpa'));
                modal.show();
            } else {
                mostrarNotificacion('Error al cargar los datos del spa', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los datos del spa', 'error');
        });
}

// Función para ver detalles de spa
function verSpa(id) {
    fetch(`?controlador=admin&accion=spas&action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const spa = data.spa;
                document.getElementById('detalleNombreSpa').textContent = spa.nombre;
                document.getElementById('detalleDireccionSpa').textContent = spa.direccion;
                document.getElementById('detalleTelefonoSpa').textContent = spa.telefono;
                document.getElementById('detalleEmailSpa').textContent = spa.email;
                document.getElementById('detalleIdSpa').textContent = `#${spa.id_spa}`;
                
                const modal = new bootstrap.Modal(document.getElementById('modalDetallesSpa'));
                modal.show();
            } else {
                mostrarNotificacion('Error al cargar los detalles del spa', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los detalles del spa', 'error');
        });
}

// Función para ver detalles del servicio
function verServicio(id) {
    fetch(`?controlador=admin&accion=servicios&action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const servicio = data.servicio;
                document.getElementById('detalleIdServicio').textContent = `#${servicio.id_servicio}`;
                document.getElementById('detalleNombreServicio').textContent = servicio.nombre;
                document.getElementById('detalleDescripcionServicio').textContent = servicio.descripcion;
                document.getElementById('detalleDuracionServicio').textContent = servicio.duracion + ' min';
                document.getElementById('detallePrecioServicio').textContent = '€' + parseFloat(servicio.precio).toFixed(2);
                
                const modal = new bootstrap.Modal(document.getElementById('modalDetallesServicio'));
                modal.show();
            } else {
                mostrarNotificacion('Error al cargar los datos del servicio', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los datos del servicio', 'error');
        });
}

// Función para eliminar spa
function eliminarSpa(id, nombre) {
    if (confirm(`¿Estás seguro de que deseas eliminar el spa "${nombre}"?`)) {
        fetch(`?controlador=admin&accion=spas&action=eliminar&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion('Spa eliminado correctamente');
                location.reload();
            } else {
                mostrarNotificacion(data.message || 'Error al eliminar el spa', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al eliminar el spa', 'error');
        });
    }
}

// ============= FUNCIONES PARA RESERVAS =============

// Función para ver detalles de reserva
function verDetallesReserva(id) {
    fetch(`?controlador=admin&accion=reservas&action=obtener_detalles&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const reserva = data.reserva;
                document.getElementById('detalleCliente').textContent = reserva.nombre_cliente;
                document.getElementById('detalleEmail').textContent = reserva.email_cliente;
                document.getElementById('detalleTelefono').textContent = reserva.telefono_cliente || 'No especificado';
                document.getElementById('detalleFecha').textContent = reserva.fecha;
                document.getElementById('detalleHora').textContent = reserva.hora;
                document.getElementById('detalleEstado').textContent = reserva.estado;
                document.getElementById('detalleSpa').textContent = reserva.nombre_spa;
                document.getElementById('detalleServicios').textContent = reserva.servicios || 'No especificados';
                document.getElementById('detalleTotal').textContent = reserva.total ? `$${reserva.total}` : 'No calculado';
                document.getElementById('detalleNotas').textContent = reserva.notas || 'Sin notas';
                
                const modal = new bootstrap.Modal(document.getElementById('modalDetallesReserva'));
                modal.show();
            } else {
                mostrarNotificacion('Error al cargar los detalles de la reserva', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los detalles de la reserva', 'error');
        });
}

// Función para cambiar estado de reserva
function cambiarEstadoReserva(id, nuevoEstado) {
    if (nuevoEstado) {
        // Cambio directo de estado desde dropdown
        fetch(`?controlador=admin&accion=reservas&action=actualizarEstado&id=${id}&estado=${nuevoEstado}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion('Estado de reserva actualizado correctamente');
                location.reload();
            } else {
                mostrarNotificacion(data.message || 'Error al cambiar el estado', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cambiar el estado', 'error');
        });
    } else {
        // Abrir modal para cambio de estado
        document.getElementById('reservaId').value = id;
        const modal = new bootstrap.Modal(document.getElementById('cambiarEstadoModal'));
        modal.show();
    }
}

// Función para ver detalles de la reserva
function verReserva(id) {
    // Mostrar el modal con loading
    const modal = new bootstrap.Modal(document.getElementById('detallesReservaModal'));
    modal.show();
    
    // Guardar el ID para la función de imprimir
    let reservaIdDetalle = document.getElementById('reservaIdDetalle');
    if (!reservaIdDetalle) {
        reservaIdDetalle = document.createElement('input');
        reservaIdDetalle.type = 'hidden';
        reservaIdDetalle.id = 'reservaIdDetalle';
        document.body.appendChild(reservaIdDetalle);
    }
    reservaIdDetalle.value = id;
    
    fetch(`?controlador=admin&accion=reservas&action=obtenerDetallesCompletos&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const reserva = data.reserva;
                const contenido = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Información del Cliente</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nombre:</strong> ${reserva.nombre_cliente}</p>
                                    <p><strong>Email:</strong> ${reserva.email_cliente}</p>
                                    <p><strong>Teléfono:</strong> ${reserva.telefono_cliente || 'No especificado'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-calendar me-2"></i>Detalles de la Reserva</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>ID:</strong> #${reserva.id_reserva}</p>
                                    <p><strong>Fecha:</strong> ${new Date(reserva.fecha).toLocaleDateString('es-ES')}</p>
                                    <p><strong>Hora:</strong> ${reserva.hora}</p>
                                    <p><strong>Estado:</strong> <span class="badge bg-${getEstadoColor(reserva.estado)}">${reserva.estado}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-spa me-2"></i>Spa</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nombre:</strong> ${reserva.nombre_spa}</p>
                                    <p><strong>Dirección:</strong> ${reserva.direccion_spa}</p>
                                    <p><strong>Teléfono:</strong> ${reserva.telefono_spa}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Servicios</h6>
                                </div>
                                <div class="card-body">
                                    ${reserva.servicios && reserva.servicios.length > 0 ? 
                                        reserva.servicios.map(servicio => `
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>${servicio.nombre}</span>
                                                <span class="badge bg-primary">€${parseFloat(servicio.precio).toFixed(2)}</span>
                                            </div>
                                        `).join('') : 
                                        '<p class="text-muted">No hay servicios asociados</p>'
                                    }
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Total:</strong>
                                        <strong class="text-success fs-5">€${parseFloat(reserva.total).toFixed(2)}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('detallesReservaContent').innerHTML = contenido;
            } else {
                document.getElementById('detallesReservaContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar los detalles de la reserva: ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detallesReservaContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles de la reserva
                </div>
            `;
        });
}

// Función auxiliar para obtener el color del estado
function getEstadoColor(estado) {
    switch(estado) {
        case 'pendiente': return 'warning';
        case 'confirmada': return 'success';
        case 'completada': return 'info';
        case 'cancelada': return 'danger';
        default: return 'secondary';
    }
}

// Función para eliminar reserva
function eliminarReserva(id, cliente) {
    if (confirm(`¿Está seguro de que desea eliminar la reserva de "${cliente}"?`)) {
        fetch(`?controlador=admin&accion=reservas&action=eliminar&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion('Reserva eliminada correctamente');
                location.reload();
            } else {
                mostrarNotificacion(data.message || 'Error al eliminar la reserva', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al eliminar la reserva', 'error');
        });
    }
}

// ============= MANEJADORES DE FORMULARIOS =============

// Manejador para formulario de usuarios
document.addEventListener('DOMContentLoaded', function() {
    const formUsuario = document.getElementById('formUsuario');
    if (formUsuario) {
        formUsuario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const isEdit = document.getElementById('usuarioId').value !== '';
            formData.append('action', isEdit ? 'actualizar' : 'crear');
            
            fetch('?controlador=admin&accion=usuarios', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(isEdit ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalUsuario')).hide();
                    location.reload();
                } else {
                    mostrarNotificacion(data.message || 'Error al procesar la solicitud', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al procesar la solicitud', 'error');
            });
        });
    }
    
    // Manejador para formulario de servicios
    const formServicio = document.getElementById('formServicio');
    if (formServicio) {
        formServicio.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const isEdit = document.getElementById('servicioId').value !== '';
            formData.append('action', isEdit ? 'actualizar' : 'crear');
            
            fetch('?controlador=admin&accion=servicios', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(isEdit ? 'Servicio actualizado correctamente' : 'Servicio creado correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalServicio')).hide();
                    location.reload();
                } else {
                    mostrarNotificacion(data.message || 'Error al procesar la solicitud', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al procesar la solicitud', 'error');
            });
        });
    }
    
    // Manejador para formulario de spas
    const formSpa = document.getElementById('formSpa');
    if (formSpa) {
        formSpa.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const isEdit = document.getElementById('spaId').value !== '';
            formData.append('action', isEdit ? 'actualizar' : 'crear');
            
            fetch('?controlador=admin&accion=spas', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(isEdit ? 'Spa actualizado correctamente' : 'Spa creado correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalSpa')).hide();
                    location.reload();
                } else {
                    mostrarNotificacion(data.message || 'Error al procesar la solicitud', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al procesar la solicitud', 'error');
            });
        });
    }
    
    // Manejador para formulario de cambio de estado de reserva
    const formCambiarEstado = document.getElementById('formCambiarEstado');
    if (formCambiarEstado) {
        formCambiarEstado.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'cambiar_estado');
            
            fetch('?controlador=admin&accion=reservas', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion('Estado de reserva actualizado correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                    location.reload();
                } else {
                    mostrarNotificacion(data.message || 'Error al cambiar el estado', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al cambiar el estado', 'error');
            });
        });
    }
});

// Función para imprimir reserva
function imprimirReserva() {
    // Obtener el ID de la reserva del elemento oculto
    const reservaIdDetalle = document.getElementById('reservaIdDetalle');
    if (!reservaIdDetalle || !reservaIdDetalle.value) {
        mostrarNotificacion('No se puede imprimir: ID de reserva no encontrado', 'error');
        return;
    }
    
    const id = reservaIdDetalle.value;
    // Abrir ventana de impresión con los detalles de la reserva
    const ventanaImpresion = window.open('', '_blank', 'width=800,height=600');
    
    fetch(`?controlador=admin&accion=reservas&action=obtenerDetallesCompletos&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const reserva = data.reserva;
                const contenidoImpresion = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Reserva #${reserva.id_reserva}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            .header { text-align: center; margin-bottom: 30px; }
                            .info-section { margin-bottom: 20px; }
                            .info-section h3 { border-bottom: 2px solid #333; padding-bottom: 5px; }
                            .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
                            .servicios { margin-top: 20px; }
                            .servicio-item { display: flex; justify-content: space-between; padding: 5px 0; }
                            .total { font-weight: bold; font-size: 1.2em; margin-top: 20px; text-align: right; }
                            @media print { .no-print { display: none; } }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <h1>LaPaSo - Reserva de Spa</h1>
                            <h2>Reserva #${reserva.id_reserva}</h2>
                        </div>
                        
                        <div class="info-section">
                            <h3>Información del Cliente</h3>
                            <div class="row"><span>Nombre:</span> <span>${reserva.nombre_cliente}</span></div>
                            <div class="row"><span>Email:</span> <span>${reserva.email_cliente}</span></div>
                            <div class="row"><span>Teléfono:</span> <span>${reserva.telefono_cliente || 'No especificado'}</span></div>
                        </div>
                        
                        <div class="info-section">
                            <h3>Detalles de la Reserva</h3>
                            <div class="row"><span>Fecha:</span> <span>${new Date(reserva.fecha).toLocaleDateString('es-ES')}</span></div>
                            <div class="row"><span>Hora:</span> <span>${reserva.hora}</span></div>
                            <div class="row"><span>Estado:</span> <span>${reserva.estado}</span></div>
                        </div>
                        
                        <div class="info-section">
                            <h3>Spa</h3>
                            <div class="row"><span>Nombre:</span> <span>${reserva.nombre_spa}</span></div>
                            <div class="row"><span>Dirección:</span> <span>${reserva.direccion_spa}</span></div>
                            <div class="row"><span>Teléfono:</span> <span>${reserva.telefono_spa}</span></div>
                        </div>
                        
                        <div class="servicios">
                            <h3>Servicios</h3>
                            ${reserva.servicios && reserva.servicios.length > 0 ? 
                                reserva.servicios.map(servicio => `
                                    <div class="servicio-item">
                                        <span>${servicio.nombre}</span>
                                        <span>€${parseFloat(servicio.precio).toFixed(2)}</span>
                                    </div>
                                `).join('') : 
                                '<p>No hay servicios asociados</p>'
                            }
                            <div class="total">
                                Total: €${parseFloat(reserva.total).toFixed(2)}
                            </div>
                        </div>
                        
                        <div class="no-print" style="margin-top: 30px; text-align: center;">
                            <button onclick="window.print()">Imprimir</button>
                            <button onclick="window.close()">Cerrar</button>
                        </div>
                        
                        <script>
                            window.onload = function() {
                                window.print();
                            }
                        </script>
                    </body>
                    </html>
                `;
                
                ventanaImpresion.document.write(contenidoImpresion);
                ventanaImpresion.document.close();
            } else {
                ventanaImpresion.close();
                mostrarNotificacion('Error al cargar los datos para imprimir', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ventanaImpresion.close();
            mostrarNotificacion('Error al cargar los datos para imprimir', 'error');
        });
}