<?php

/** llamando archivo de la base de datos */
require_once __DIR__."/../config/db.php";

/**se crea la clase usuario para manejas los datos del usuario */
class Usuario {
    private $db;
   public function crear($datos){

    $conexion = new mysqli("localhost", "root", "", "tu_base_datos");

    if($conexion->connect_error){
        die("Error conexión: " . $conexion->connect_error);
    }

    $sql = "INSERT INTO usuarios 
    (nombre_negocio, nombre_usuario, apellido_usuario, telefono, correo, id_rol, contraseña)
    VALUES (
        '{$datos["nombre_negocio"]}',
        '{$datos["nombre_usuario"]}',
        '{$datos["apellido_usuario"]}',
        '{$datos["telefono"]}',
        '{$datos["correo"]}',
        '{$datos["id_rol"]}',
        '{$datos["contraseña"]}'
    )";

    if(!$conexion->query($sql)){
        die("ERROR SQL: " . $conexion->error);
    }

    echo "GUARDADO OK";
    die();
}
}