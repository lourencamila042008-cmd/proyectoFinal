<?php

// Cargo el archivo de conexión a la base de datos
require_once __DIR__."/../config/db.php";

// Clase para manejar todo lo relacionado con los usuarios
class Usuario {

    // Variable para guardar la conexión
    private $db;

    // 🔹 CREAR NUEVO USUARIO
    // Recibe los datos y los guarda en la tabla usuarios
   public function crear($datos){

    // Me conecto a la base de datos directamente aquí
    $conexion = new mysqli("localhost", "root", "", "tu_base_datos");

    // Si hay error al conectar, lo muestro y detengo
    if($conexion->connect_error){
        die("Error conexión: " . $conexion->connect_error);
    }

    // Inserto todos los datos que llegaron en el arreglo $datos
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

    // Si falla la consulta, muestro el error
    if(!$conexion->query($sql)){
        die("ERROR SQL: " . $conexion->error);
    }

    // Si todo salió bien, muestro mensaje de confirmación
    echo "GUARDADO OK";
    die();
}

}