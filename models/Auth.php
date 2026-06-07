<?php

// Cargo la conexión a la base de datos
require_once __DIR__."/../config/db.php";

// Clase para manejar el inicio de sesión y registro
class Auth {

    // Variable para guardar la conexión
    private $conexion;

    // Al iniciar la clase, conecta a la base de datos
    public function __construct(){
        $this->conexion = Database::Conectar();
    }

    // ➡️ FUNCIÓN PARA INICIAR SESIÓN
    public function login($usuario,$password){

        // Busco el usuario en la base:
        // Traigo sus datos y su rol (junto las tablas usuario, rol_user y rol)
        // Puede entrar con correo o con nombre de usuario
        $sql = $this->conexion->prepare("
            SELECT u.*, r.tipo, r.id_rol
            FROM usuario u
            INNER JOIN rol_user ru ON u.id_usuario = ru.id_usuario
            INNER JOIN rol r ON ru.id_rol = r.id_rol
            WHERE u.correo = ? OR u.nombre_usuario = ?
            LIMIT 1
        ");

        $sql->bind_param("ss",$usuario,$usuario);
        $sql->execute();

        $resultado = $sql->get_result();

        // Si encontró el usuario...
        if($resultado->num_rows > 0){

            $user = $resultado->fetch_assoc();

            // Verifico si la contraseña es correcta (está encriptada)
            if(password_verify($password,$user['contraseña'])){
                return $user; // Devuelvo todos los datos del usuario
            }
        }

        return false; // Si falla algo, devuelvo falso
    }


    // ➡️ FUNCIÓN PARA REGISTRAR NUEVO USUARIO
    public function register($datos){

        // Encripto la contraseña antes de guardarla
        $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);

        // Guardo los datos del usuario en la tabla usuario
        $sql = $this->conexion->prepare("
            INSERT INTO usuario
            (nombre_negocio,nombre_usuario,apellido_usuario,telefono,correo,contraseña)
            VALUES (?,?,?,?,?,?)
        ");

        $sql->bind_param("ssssss",
            $datos['nombre_negocio'],
            $datos['nombre_usuario'],
            $datos['apellido_usuario'],
            $datos['telefono'],
            $datos['correo'],
            $password_hash
        );

        // Si se guardó bien...
        if($sql->execute()){

            // Obtengo el ID del usuario que acabo de crear
            $id_usuario = $this->conexion->insert_id;

            // Por defecto, todos los nuevos son ROL EMPLEADO
            $rolEmpleado = "empleado";

            // Busco qué número de ID tiene el rol "empleado"
            $sqlRol = $this->conexion->prepare("
                SELECT id_rol FROM rol WHERE tipo = ?
            ");

            $sqlRol->bind_param("s",$rolEmpleado);
            $sqlRol->execute();

            $resRol = $sqlRol->get_result();

            if($fila = $resRol->fetch_assoc()){

                $id_rol = $fila['id_rol'];

                // Relaciono al usuario con su rol en la tabla rol_user
                $sqlUserRol = $this->conexion->prepare("
                    INSERT INTO rol_user (id_rol,id_usuario)
                    VALUES (?,?)
                ");
                
                $sqlUserRol->bind_param("ii",$id_rol,$id_usuario);

                return $sqlUserRol->execute();
            }
        }

        return false;
    }
}
?>