<?php

    include_once(__DIR__ . '/../conexion.php');
    class Servicios{
        public $id_servicio;
        public $nombre;
        public $descripcion;
        public $precio;
        public $id_spa;
        public $duracion;
        public $categoria;
        public $destacado;
        public $beneficios;
        public $precio_anterior;

        // Constructor de la clase
        public function __construct($id_servicio, $nombre, $descripcion, $precio, $id_spa, $duracion = null, $categoria = null, $destacado = false, $beneficios = '', $precio_anterior = 0) {
            $this->id_servicio = $id_servicio;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->precio = floatval($precio);
            $this->id_spa = $id_spa;
            $this->duracion = $duracion;
            $this->categoria = $categoria;
            $this->destacado = $destacado;
            $this->beneficios = $beneficios;
            $this->precio_anterior = floatval($precio_anterior);
        }

        // Método para guardar un nuevo servicio en la base de datos
        public static function guardaServicio($nombre, $descripcion, $precio, $id_spa, $duracion = null, $categoria = null, $destacado = false, $beneficios = '', $precio_anterior = 0){
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("INSERT INTO servicios(nombre, descripcion, precio, id_spa, duracion, categoria, destacado, beneficios, precio_anterior) VALUES (?,?,?,?,?,?,?,?,?)");
            return $sql->execute(array($nombre, $descripcion, $precio, $id_spa, $duracion, $categoria, $destacado, $beneficios, $precio_anterior));
        }

        // Método para listar todos los servicios en la base de datos
        public static function listarServicios(){
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("SELECT * FROM servicios");
            $sql->execute();
            $servicios = array();

            // Recorremos los resultados de la consulta y creamos objetos Servicios
            foreach($sql->fetchAll() as $servicio){
                $servicios[] = new Servicios(
                    $servicio['id_servicio'],
                    $servicio['nombre'],
                    $servicio['descripcion'],
                    $servicio['precio'],
                    $servicio['id_spa'],
                    $servicio['duracion'] ?? null,
                    $servicio['categoria'] ?? null,
                    $servicio['destacado'] ?? false,
                    $servicio['beneficios'] ?? '',
                    $servicio['precio_anterior'] ?? 0
                );
            }
            return $servicios;
        }

        public static function obtenerMultiples($ids) {
            $conexion = DB::crearInstancia();
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = $conexion->prepare("SELECT * FROM servicios WHERE id_servicio IN ($placeholders)");
            $sql->execute($ids);
            $servicios = array();

            // Recorremos los resultados de la consulta y creamos objetos Servicios
            foreach($sql->fetchAll() as $servicio){
                $servicios[] = new Servicios(
                    $servicio['id_servicio'],
                    $servicio['nombre'],
                    $servicio['descripcion'],
                    $servicio['precio'],
                    $servicio['id_spa'],
                    $servicio['duracion'] ?? null,
                    $servicio['categoria'] ?? null,
                    $servicio['destacado'] ?? false,
                    $servicio['beneficios'] ?? '',
                    $servicio['precio_anterior'] ?? 0
                );
            }

            return $servicios;
        }

        // Método para validar que los servicios pertenezcan al mismo spa
        public static function validarMismoSpa($ids) {
            if (empty($ids)) return true;
            
            $conexion = DB::crearInstancia();
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = $conexion->prepare("SELECT COUNT(DISTINCT id_spa) as spa_count FROM servicios WHERE id_servicio IN ($placeholders)");
            $sql->execute($ids);
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            
            return $result['spa_count'] == 1;
        }

        // Método para verificar si un servicio tiene reservas asociadas
        public static function tieneReservas($id) {
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("SELECT COUNT(*) as total FROM detalle_reserva WHERE id_servicio = ?");
            $sql->execute(array($id));
            $resultado = $sql->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] > 0;
        }

        // Método para borrar un servicio de la base de datos
        public static function borrarServicio($id){
            $conexion = DB::crearInstancia();
            
            try {
                // Verificar si el servicio tiene reservas asociadas
                if (self::tieneReservas($id)) {
                    throw new Exception("No se puede eliminar el servicio porque tiene reservas asociadas.");
                }
                
                $sql = $conexion->prepare("DELETE FROM servicios WHERE id_servicio=?");
                $sql->execute(array($id));
                return true;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        // Método para editar un servicio en la base de datos
        public static function editarServicio($id, $nombre, $descripcion, $precio, $id_spa, $duracion = null, $categoria = null, $destacado = false, $beneficios = '', $precio_anterior = 0){
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("UPDATE servicios SET nombre=?, descripcion=?, precio=?, id_spa=?, duracion=?, categoria=?, destacado=?, beneficios=?, precio_anterior=? WHERE id_servicio=?");
            return $sql->execute(array($nombre, $descripcion, $precio, $id_spa, $duracion, $categoria, $destacado, $beneficios, $precio_anterior, $id));
        }

        // Método para buscar un servicio en la base de datos
        public static function buscarServicio($id){
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("SELECT * FROM servicios WHERE id_servicio = ?");
            $sql->execute(array($id));
            foreach($sql->fetchAll() as $servicio){
                $servi[] = new Servicios(
                    $servicio['id_servicio'],
                    $servicio['nombre'],
                    $servicio['descripcion'],
                    $servicio['precio'],
                    $servicio['id_spa'],
                    $servicio['duracion'] ?? null,
                    $servicio['categoria'] ?? null,
                    $servicio['destacado'] ?? false,
                    $servicio['beneficios'] ?? '',
                    $servicio['precio_anterior'] ?? 0
                );
            }
            return $servi;
        }



        // Método para obtener un servicio por su ID
        public static function obtenerPorId($id) {
            $conexion = DB::crearInstancia();
            $sql = $conexion->prepare("SELECT * FROM servicios WHERE id_servicio = ?");
            $sql->execute([$id]);
            $servicio = $sql->fetch(PDO::FETCH_ASSOC);
            if ($servicio) {
                return new Servicios(
                    $servicio['id_servicio'],
                    $servicio['nombre'],
                    $servicio['descripcion'],
                    $servicio['precio'],
                    $servicio['id_spa'],
                    $servicio['duracion'] ?? null,
                    $servicio['categoria'] ?? null,
                    $servicio['destacado'] ?? false,
                    $servicio['beneficios'] ?? '',
                    $servicio['precio_anterior'] ?? 0
                );
            }
            return null;
        }
    }
?>