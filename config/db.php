<?php
/**se crea la clase Database para conectar con la base de datos */
class Database {
    public static function Conectar(){
        $conexion = new mysqli(
            "localhost",
            "root",
            "",
            "invoicepro"
            );
    
    if ($conexion->connect_errno){
        die("error".$conexion->connect_error);
    }
    return $conexion;
    }
} 
?>