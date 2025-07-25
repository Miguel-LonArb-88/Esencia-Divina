<?php
    class DB{
        private static $instancia=NULL;

        // Funcion que crea y devuelve la instancia de la conexion
        public static function crearInstancia(){
            if (!isset (self::$instancia)){
                $opcionesPDO[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                self::$instancia= new PDO
                ('mysql:host=localhost;dbname=diagrama','root','',$opcionesPDO);  
            }
            return self::$instancia;
        }
    }
?>