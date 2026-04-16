<?php

require_once __DIR__."/../config/db.php";

class Auth {

    private $conexion;

    public function __construct(){
        $this->conexion = Database::Conectar();
    }

    // 🔐 LOGIN
    public function login($usuario,$password){

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

        if($resultado->num_rows > 0){

            $user = $resultado->fetch_assoc();

            // 🔥 VERIFICAR CONTRASEÑA SEGURA
            if(password_verify($password,$user['contraseña'])){
                return $user;
            }
        }

        return false;
    }


    // 📝 REGISTER → EMPLEADO POR DEFECTO
    public function register($datos){

        $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);

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

        if($sql->execute()){

            $id_usuario = $this->conexion->insert_id;

            // 🔥 BUSCAR ROL EMPLEADO
            $rolEmpleado = "empleado";

            $sqlRol = $this->conexion->prepare("
                SELECT id_rol FROM rol WHERE tipo = ?
            ");

            $sqlRol->bind_param("s",$rolEmpleado);
            $sqlRol->execute();

            $resRol = $sqlRol->get_result();

            if($fila = $resRol->fetch_assoc()){

                $id_rol = $fila['id_rol'];

                // 🔥 INSERTAR EN rol_user
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