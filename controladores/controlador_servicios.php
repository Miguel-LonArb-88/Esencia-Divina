<?php
    include_once("conexion.php");
    DB::CrearInstancia();
    include_once("modelos/servicios.php");

    class ControladorServicios{
        
        // Método que muestra la vista de inicio
        public function inicio(){
            include_once("vistas/servicios/inicio.php");
        }

        // Método que muestra la vista de agregar
        public function guardarServicio(){    
            if ($_POST){
                $nombre=$_POST['nombre'];
                $descripcion=$_POST['descripcion'];
                $precio=$_POST['precio'];
                Servicios::guardaServicio($nombre,$descripcion,$precio);
                echo "<script>
                        window.location.href=('./?controlador=servicios&accion=inicio') </script>";
            }
        }
        public function listarServicios(){
            $servicios= Servicios::listarServicios() ;
            return  $servicios;
        }

        public function editar(){
            if (isset($_GET['id'])){
                $id = $_GET['id'];
                $servicio = Servicios::buscarServicio($id);
            include_once ("vistas/servicios/editar.php");
            return $servicio;
            }
        }

        public function guardarCambios(){
            if (isset($_POST)){
                $id = $_POST['id'];
                $nombre=$_POST['nombre'];
                $descripcion=$_POST['descripcion'];
                $precio=$_POST['precio'];
                Servicios::editarServicio($id,$nombre,$descripcion,$precio);
                echo "<script>
                    alert('Servicio Actualizado: ". $nombre . "');
                    window.location.href=('./?controlador=servicios&accion=inicio');
                </script>";
            }
        }


        public function borrar(){
            $id = $_GET['id'];
            Servicios::borrarServicio($id);
            echo "<script>
                    alert('Borrando Servicio No. ". $_GET['id'] . "');
                    window.location.href=('./?controlador=servicios&accion=inicio');
                </script>";
        }
    }
?>