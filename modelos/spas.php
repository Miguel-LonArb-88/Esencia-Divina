<?php

class Spas {
    public $id;
    public $nombre;
    public $direccion;
    public $telefono;
    public $email;

    // Constructor 
    public function __construct($id, $nombre, $direccion, $telefono, $email) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
    }

    // Método para guardar un nuevo spa en la base de datos
    public static function guardarSpa($nombre, $direccion, $telefono, $email) {
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("INSERT INTO spas (nombre, direccion, telefono, email) VALUES (?, ?, ?, ?)");
        $sql->execute(array($nombre, $direccion, $telefono, $email));
    }

    // Método para listar todos los spas
    public static function listarSpas() {
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("SELECT * FROM spas");
        $sql->execute();    
        $spas = [];

        foreach ($sql->fetchAll() as $spa) {
            $spas[] = new Spas($spa['id_spa'], $spa['nombre'], $spa['direccion'], $spa['telefono'], $spa['email']);
        }
        return $spas;
    }

    public static function obtenerPorId($id) {
        if ($id === null) return null;
        
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("SELECT * FROM spas WHERE id_spa = ?");
        $sql->execute([$id]);
        $spa = $sql->fetch(PDO::FETCH_ASSOC);
        
        if ($spa) {
            return new Spas($spa['id_spa'], $spa['nombre'], $spa['direccion'], $spa['telefono'], $spa['email']);
        }
        return null;
    }

    // Método para obtener un spa por su ID (alias de obtenerPorId)
    public static function obtenerSpaPorId($id) {
        return self::obtenerPorId($id);
    }

    // Función para actualizar un spa
    public static function actualizar($id, $nombre, $direccion, $telefono, $email) {
        $conexion = DB::crearInstancia();
        $sql = $conexion->prepare("UPDATE spas SET nombre = ?, direccion = ?, telefono = ?, email = ? WHERE id_spa = ?");
        return $sql->execute(array($nombre, $direccion, $telefono, $email, $id));
    }

    // Método para eliminar un spa
    public static function eliminar($id) {
        $conexion = DB::crearInstancia();
        
        // Verificar si el spa tiene servicios asociados
        $sql = $conexion->prepare("SELECT COUNT(*) as count FROM servicios WHERE id_spa = ?");
        $sql->execute(array($id));
        $resultado = $sql->fetch();
        
        if ($resultado['count'] > 0) {
            throw new Exception("No se puede eliminar el spa porque tiene servicios asociados.");
        }
        
        // Verificar si el spa tiene reservas asociadas
        $sql = $conexion->prepare("SELECT COUNT(*) as count FROM reservas WHERE id_spas = ?");
        $sql->execute(array($id));
        $resultado = $sql->fetch();
        
        if ($resultado['count'] > 0) {
            throw new Exception("No se puede eliminar el spa porque tiene reservas asociadas.");
        }
        
        // Eliminar el spa
        $sql = $conexion->prepare("DELETE FROM spas WHERE id_spa = ?");
        return $sql->execute(array($id));
    }
}

?>