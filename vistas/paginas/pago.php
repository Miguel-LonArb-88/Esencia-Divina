<?php
if (!isset($_SESSION['nombre']) || empty($_SESSION['carrito'])) {
    header('Location: ?controlador=carrito&accion=ver');
    exit;
}

// Mostrar mensaje de error si existe
if (isset($_SESSION['error_pago'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo $_SESSION['error_pago'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    unset($_SESSION['error_pago']);
}
?>


<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">Detalles de la Reservación</h3>
                    <form action="?controlador=pago&accion=confirmar" method="POST" id="reservation-form">
                        <div class="mb-3">
                            <label class="form-label">Fecha de Reservación</label>
                            <input type="date" class="form-control" name="fecha_reserva" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora de Reservación</label>
                            <select class="form-control" name="hora_reserva" required>
                                <?php
                                    $inicio = strtotime('09:00');
                                    $fin = strtotime('20:00');
                                    for($i = $inicio; $i <= $fin; $i += 3600) {
                                        echo '<option value="'.date('H:i', $i).'">'.date('H:i', $i).'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notas Adicionales</label>
                            <textarea class="form-control" name="notas" rows="3" placeholder="Especificaciones especiales o requerimientos"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-4">Continuar al Pago</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mb-4" id="payment-section" style="display: none;">
                <div class="card-body">
                    <h3 class="card-title mb-4">Detalles del Pago</h3>
                    <form method="POST" id="payment-form" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Titular de la Tarjeta</label>
                            <input type="text" class="form-control" name="titular" required value="<?php echo htmlspecialchars($_SESSION['nombre']); ?>" readonly>
                            <small class="text-muted">El titular debe coincidir con el nombre de usuario registrado</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Número de Tarjeta</label>
                            <input type="text" class="form-control" name="numero_tarjeta" required pattern="[0-9]{16}" maxlength="16" placeholder="1234567890123456" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Expiración</label>
                                <input type="text" class="form-control" name="expiracion" placeholder="MM/YY" required pattern="[0-9]{2}/[0-9]{2}" maxlength="5" oninput="this.value = this.value.replace(/[^0-9/]/g, '').replace(/(\d{2})(?=[^/])/,'$1/');">
                                <small class="text-muted">Formato: MM/YY (ejemplo: 12/25)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control" name="cvv" required pattern="[0-9]{3,4}" maxlength="4" placeholder="123" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <small class="text-muted">3 o 4 dígitos en el reverso de la tarjeta</small>
                            </div>
                        </div>
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <button type="submit" class="btn btn-primary w-100">Pagar <?php echo number_format($total, 2); ?> €</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Resumen de la Reserva</h4>
                    <?php if ($spa): ?>
                    <div class="mb-3">
                        <h5>Spa Seleccionado</h5>
                        <p class="text-muted"><?php echo $spa->nombre; ?></p>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <h5>Servicios</h5>
                        <ul class="list-unstyled">
                            <?php foreach($servicios as $servicio): ?>
                            <li class="mb-2">
                                <?php echo $servicio->nombre; ?>
                                <span class="float-end"><?php echo number_format($servicio->precio, 2); ?> €</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total</h5>
                        <h5 class="mb-0"><?php echo number_format($total, 2); ?> €</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para validar fecha y hora
function validarFechaHora(fecha, hora) {
    const fechaHoraReserva = new Date(fecha + ' ' + hora);
    const ahora = new Date();
    return fechaHoraReserva > ahora;
}

// Función para validar tarjeta de crédito
function validarTarjeta(numero) {
    return /^[0-9]{16}$/.test(numero);
}

// Función para validar fecha de expiración
function validarExpiracion(exp) {
    if (!/^[0-9]{2}\/[0-9]{2}$/.test(exp)) return false;
    const [mes, anio] = exp.split('/');
    const fechaExp = new Date(20 + anio, mes - 1);
    return fechaExp > new Date();
}

// Función para validar CVV
function validarCVV(cvv) {
    return /^[0-9]{3,4}$/.test(cvv);
}

// Función para mostrar errores
function mostrarError(mensaje) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
}

// Configurar el formulario de reserva
document.getElementById('reservation-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const fecha = this.querySelector('[name="fecha_reserva"]').value;
    const hora = this.querySelector('[name="hora_reserva"]').value;
    
    if (!validarFechaHora(fecha, hora)) {
        mostrarError('La fecha y hora de reserva deben ser posteriores a la actual');
        return;
    }

    const notas = this.querySelector('[name="notas"]').value;

    // Almacenar los datos de reserva
    sessionStorage.setItem('fecha_reserva', fecha);
    sessionStorage.setItem('hora_reserva', hora);
    sessionStorage.setItem('notas', notas);

    // Mostrar el formulario de pago
    this.style.display = 'none';
    document.getElementById('payment-form').style.display = 'block';
    document.getElementById('payment-section').style.display = 'block';
});

// Formatear automáticamente el número de tarjeta
document.querySelector('[name="numero_tarjeta"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 16);
});

// Formatear automáticamente la fecha de expiración
document.querySelector('[name="expiracion"]').addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    this.value = value;
});

// Formatear automáticamente el CVV
document.querySelector('[name="cvv"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 4);
});

// Configurar el formulario de pago
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const titular = document.querySelector('#payment-form [name="titular"]').value;
    const numeroTarjeta = document.querySelector('#payment-form [name="numero_tarjeta"]').value;
    const expiracion = document.querySelector('#payment-form [name="expiracion"]').value;
    const cvv = document.querySelector('#payment-form [name="cvv"]').value;

    // Validaciones
    if (!titular.trim()) {
        mostrarError('Por favor ingrese el nombre del titular de la tarjeta');
        return;
    }
    if (!validarTarjeta(numeroTarjeta)) {
        mostrarError('El número de tarjeta debe tener 16 dígitos');
        return;
    }
    if (!validarExpiracion(expiracion)) {
        mostrarError('La fecha de expiración no es válida');
        return;
    }
    if (!validarCVV(cvv)) {
        mostrarError('El CVV debe tener entre 3 y 4 dígitos');
        return;
    }

    // Crear y enviar el formulario final
    const formFinal = document.createElement('form');
    formFinal.method = 'POST';
    formFinal.action = '?controlador=pago&accion=confirmar';
    
    const campos = {
        fecha_reserva: sessionStorage.getItem('fecha_reserva'),
        hora_reserva: sessionStorage.getItem('hora_reserva'),
        notas: sessionStorage.getItem('notas'),
        titular: titular,
        numero_tarjeta: numeroTarjeta,
        expiracion: expiracion,
        cvv: cvv,
        total: document.querySelector('#payment-form [name="total"]').value
    };

    // Crear y agregar los campos al formulario
    Object.entries(campos).forEach(([nombre, valor]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = nombre;
        input.value = valor;
        formFinal.appendChild(input);
    });

    // Mostrar indicador de carga
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
    
    document.body.appendChild(formFinal);
    formFinal.submit();
});
</script>