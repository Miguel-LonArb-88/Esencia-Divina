<?php

    // Modelo Usuario
    // Clase que representa a un usuario en la base de datos
    class Usuario{

        // Atributos de la clase
        public $id;
        public $nombre;
        public $email;
        public $clave;
        public $tipo;

        // Constructor de la clase 
        public function __construct($id, $nombre, $email, $clave, $tipo){
            $this->id = $id;
            $this->nombre = $nombre;
            $this->email = $email;
            $this->clave = $clave;
            $this->tipo = $tipo;
        }


        // Funcion para iniciar sesion
        public static function login ($email){
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
            $sql->execute(array($email));
            $usuario = $sql->fetch(PDO::FETCH_ASSOC);
            if (!$usuario || !isset($usuario['id_usuario']) || !is_numeric($usuario['id_usuario'])) {
                return null;
            }
            return new Usuario(
                (int)$usuario['id_usuario'],
                $usuario['nombre'],
                $usuario['email'],
                $usuario['clave'],
                $usuario['tipo']
            );
        }

        // Funcion para registrar un nuevo usuario
        public static function register($usuario, $email, $clave, $tipo = 'cliente'){
            $conexion =DB::crearInstancia();
            $sql = $conexion->prepare("INSERT INTO usuarios(nombre, email, clave, tipo) VALUES (?,?,?,?)");
            $sql->execute(array($usuario, $email, $clave, $tipo));
        }

        // Funcion para listar todos los usuarios
        public static function listarUsuarios(){
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("SELECT * FROM usuarios");
            $sql->execute();
            $usuariosData = $sql->fetchAll();
            $listaUsuarios = [];

            foreach($usuariosData as $userData){
                $listaUsuarios[] = new Usuario(
                    $userData['id_usuario'],
                    $userData['nombre'],
                    $userData['email'],
                    $userData['clave'],
                    $userData['tipo']
                );
            }

            return $listaUsuarios;
        }

        // Funcion para obtener un usuario por su ID
        public static function obtenerPorId($id) {
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
            $sql->execute(array($id));
            $usuario = $sql->fetch(PDO::FETCH_ASSOC);
            if(!$usuario) return null;
            return new Usuario($usuario['id_usuario'], $usuario['nombre'], $usuario['email'], $usuario['clave'], $usuario['tipo']);
        }
    
        // Funcion para actualizar un usuario
        public static function actualizar($id, $nombre, $email, $tipo, $clave = null) {
            $conexion = DB::crearInstancia();
            if($clave) {
                $sql = $conexion->prepare("UPDATE usuarios SET nombre = ?, email = ?, clave = ?, tipo = ? WHERE id_usuario = ?");
                $sql->execute(array($nombre, $email, password_hash($clave, PASSWORD_DEFAULT), $tipo, $id));
            } else {
                $sql = $conexion->prepare("UPDATE usuarios SET nombre = ?, email = ?, tipo = ? WHERE id_usuario = ?");
                $sql->execute(array($nombre, $email, $tipo, $id));
            }
        }
    
        // Funcion para eliminar un usuario
        public static function eliminar($id) {
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $sql->execute(array($id));
        }

    }
?>