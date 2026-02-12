<?php
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