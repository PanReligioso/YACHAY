<?php
class Conectar {
    public static function conexion() {
        $conexion = new mysqli("localhost", "root", "", "plataforma_continental"); 
        
        if ($conexion->connect_error) {
            die("Error de conexión a la BD: " . $conexion->connect_error);
        }
        
        $conexion->set_charset("utf8mb4");
        return $conexion;
    }
}
?>