<?php
    include_once("conexion.php");
    DB::CrearInstancia();
    include_once("modelos/usuario.php");
    include_once("modelos/reservas.php");

    class ControladorUsuarios{

        // Método para mostrar la vista de inicio de sesión
        public function login(){
            // Si ya está logueado, redirigir según el tipo de usuario
            if(isset($_SESSION['tipo'])) {
                if($_SESSION['tipo'] === 'admin') {
                    header("Location: ?controlador=admin&accion=inicio");
                } else {
                    header("Location: ?controlador=paginas&accion=inicio");
                }
                exit();
            }

            if (!isset($_POST['submit'])) {
                include_once("vistas/usuarios/login.php");
                return;
            }

            if (!isset($_POST['email']) || !isset($_POST['password'])) {
                echo "<script>
                    alert('Por favor complete todos los campos');
                    window.location.href = '?controlador=usuarios&accion=login';
                </script>";
                return;
            }

            $email = $_POST['email'];
            $clave = $_POST['password'];
            $usuario = Usuario::login($email);
            
            if($usuario && password_verify($clave, $usuario->clave) && isset($usuario->id) && is_numeric($usuario->id)){
                $_SESSION['id'] = $usuario->id;
                $_SESSION['nombre'] = $usuario->nombre;
                $_SESSION['email'] = $usuario->email;
                $_SESSION['tipo'] = $usuario->tipo;

                if($usuario->tipo === 'admin') {
                    header("Location: ?controlador=admin&accion=inicio");
                } else {
                    header("Location: ?controlador=paginas&accion=inicio");
                }
                exit();
            } 
            
            $mensaje = isset($usuario) ? 'ID de usuario inválido' : 'Usuario y/o contraseña incorrectas';
                echo "<script>
                    alert('" . $mensaje . "');
                    window.location.href = '?controlador=usuarios&accion=login';
                </script>";
        }

        // Método para registrar un nuevo usuario
        public function registro(){
            if ($_POST){
                $nombre =$_POST['username'];
                $email = $_POST['email'];
                $clave = $_POST['password'];    
                $hash = password_hash($clave, PASSWORD_DEFAULT);
                Usuario::register($nombre,$email,$hash);
                echo "<script>
                            alert('Usuario registrado');
                            window.location.href = '?controlador=usuarios&accion=login';
                        </script>";
            }
            include_once("vistas/usuarios/registro.php");
        }
        public function reservas() {
            if (!isset($_SESSION['id'])) {
                header('Location: ?controlador=usuarios&accion=login');
                exit;
            }

            $id_usuario = $_SESSION['id'];
            $reservas = Reservas::obtenerReservasUsuario($id_usuario);
            include_once("vistas/usuarios/reservas.php");
        }
        // Método para mostrar el perfil del usuario
        public function perfil() {
            if (!isset($_SESSION['id'])) {
                header('Location: ?controlador=usuarios&accion=login');
                exit;
            }
            include_once("vistas/usuarios/perfil.php");
        }

        // Método para actualizar el perfil del usuario
        public function actualizarPerfil() {
            if (!isset($_SESSION['id'])) {
                header('Location: ?controlador=usuarios&accion=login');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_SESSION['id'];
                $nombre = $_POST['nombre'];
                $email = $_POST['email'];
                $password = !empty($_POST['password']) ? $_POST['password'] : null;

                try {
                    Usuario::actualizar($id, $nombre, $email, $_SESSION['tipo'], $password);
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['email'] = $email;
                    
                    echo "<script>
                        alert('Perfil actualizado correctamente');
                        window.location.href = '?controlador=usuarios&accion=perfil';
                    </script>";
                } catch (Exception $e) {
                    echo "<script>
                        alert('Error al actualizar el perfil: " . $e->getMessage() . "');
                        window.location.href = '?controlador=usuarios&accion=perfil';
                    </script>";
                }
            } else {
                header('Location: ?controlador=usuarios&accion=perfil');
            }
        }
    }

        
?>