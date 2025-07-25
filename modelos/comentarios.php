<?php
include_once(__DIR__. '/../conexion.php');

class Comentarios {
    public $id_comentario;
    public $id_usuario;
    public $id_servicio;
    public $comentario;
    public $calificacion;

    public function __construct($datos = null) {
        if($datos) {
            $this->id_comentario = $datos['id_comentario']?? null;
            $this->id_usuario = $datos['id_usuario']?? null;
            $this->id_servicio = $datos['id_servicio']?? null;
            $this->comentario = $datos['comentario']?? null;
            $this->calificacion = $datos['calificacion']?? null;
        }
    }

    public function guardar($id_usuario, $id_servicio, $comentario, $calificacion) {
        $conexion = DB::crearInstancia();
        $conexion->beginTransaction();


        try {
            $sql = "INSERT INTO comentarios (id_usuario, id_servicio, texto, calificacion, fecha) VALUES (:id_usuario, :id_servicio, :comentario, :calificacion, NOW())";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_servicio', $id_servicio);
            $stmt->bindParam(':comentario', $comentario);
            $stmt->bindParam(':calificacion', $calificacion);
            $stmt->execute();

            $conexion->commit();
            return true;
        } catch(Exception $e) {
            $conexion->rollBack();
            return false;
        }
    }

    public static function obtenerComentariosPorServicio($id_servicio) {
        $conexion = DB::crearInstancia();
        $sql = "SELECT * FROM comentarios WHERE id_servicio = :id_servicio";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_servicio', $id_servicio);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function promedioCalificacion($id_servicio) {
        $conexion = DB::crearInstancia();
        $sql = "SELECT AVG(calificacion) AS promedio FROM comentarios WHERE id_servicio = :id_servicio";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_servicio', $id_servicio);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['promedio'];
    }

    public static function obtenerComentariosPorUsuario($id_usuario) {
        $conexion = DB::crearInstancia();
        $sql = "SELECT * FROM comentarios WHERE id_usuario = :id_usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarComentario($id_comentario) {
        $conexion = DB::crearInstancia();
        $sql = "DELETE FROM comentarios WHERE id_comentario = :id_comentario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_comentario', $id_comentario);
        $stmt->execute();
    }

    public static function obtenerComentariosPositivos() {
        $conexion = DB::crearInstancia();
        $sql = "SELECT c.*, u.nombre as nombre_usuario 
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id_usuario 
                WHERE c.calificacion >= 4";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerComentariosNegativos() {
        $conexion = DB::crearInstancia();
        $sql = "SELECT * FROM comentarios WHERE calificacion <= 2";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}



?>