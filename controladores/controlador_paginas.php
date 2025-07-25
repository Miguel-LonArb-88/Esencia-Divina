<?php


    class ControladorPaginas{

        // Método que muestra la página de inicio
        public function inicio(){
            include_once("modelos/servicios.php");
            $servicios = Servicios::listarServicios();

            include_once("modelos/comentarios.php");
            $comentarios = Comentarios::obtenerComentariosPositivos();
            $comentarios = array_slice($comentarios, 0, 3);


            include_once("vistas/paginas/inicio.php");
            
        }

        // Método que muestra la página de servicios
        public function servicios(){
            include_once("modelos/servicios.php");
            $servicios = Servicios::listarServicios();

            include_once("vistas/paginas/servicios.php");
        }

        // Método que muestra la página de contacto
        public function contacto(){
            include_once("vistas/paginas/contacto.php");
        }

        // Método que muestra la página de nosotros
        public function nosotros(){
            include_once("vistas/paginas/nosotros.php");
        }

        public function enviarContacto(){
            // Validar que sea una petición POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?controlador=paginas&accion=contacto');
                exit();
            }

            // Validar campos requeridos
            $campos_requeridos = ['nombre', 'email', 'telefono', 'asunto', 'mensaje'];
            foreach ($campos_requeridos as $campo) {
                if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                    header('Location: ?controlador=paginas&accion=contacto&status=error');
                    exit();
                }
            }

            // Sanitizar datos
            $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);
            $asunto = filter_var($_POST['asunto'], FILTER_SANITIZE_STRING);
            $mensaje = filter_var($_POST['mensaje'], FILTER_SANITIZE_STRING);

            // Validar email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: ?controlador=paginas&accion=contacto&status=error');
                exit();
            }

            // Set email recipient
            $destinatario = "jtoroblandon@gmail.com"; // Replace with actual email

            // Create HTML email content
            $contenido = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #DE968D; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
                    .content { background: #f8f9fa; padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; }
                    .footer { margin-top: 20px; color: #6c757d; font-size: 12px; text-align: center; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Nuevo Mensaje de Contacto</h2>
                    </div>
                    <div class='content'>
                        <p><strong>Nombre:</strong> $nombre</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Teléfono:</strong> $telefono</p>
                        <p><strong>Asunto:</strong> $asunto</p>
                        <p><strong>Mensaje:</strong></p>
                        <p>$mensaje</p>
                    </div>
                    <div class='footer'>
                        <p>Este mensaje fue enviado desde el formulario de contacto de LaPaSo.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            // Email headers
            $headers = array(
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: LaPaSo <' . $email . '>',
                'Reply-To: ' . $email,
                'X-Mailer: PHP/' . phpversion()
            );

            // Send email
            $enviado = mail($destinatario, "Contacto LaPaSo: " . $asunto, $contenido, implode("\r\n", $headers));

            // Redirect based on email sending status
            header('Location: ?controlador=paginas&accion=contacto&status=' . ($enviado ? 'success' : 'error'));
            exit();
        }
    }


?>