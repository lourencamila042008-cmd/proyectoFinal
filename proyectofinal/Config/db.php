<?php

class Database{
    
    public static function Conectar(){
        $conexion=new mysqli(
            "localhost",
            "root",
            "",
            "maracumango"

        );
        if($conexion->connect_errno){
            die($conexion->connect_error);

        }
        return $conexion;
    }
}


?>