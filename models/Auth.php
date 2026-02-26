<?php
/** llamando archivo de la base de datos */
require_once __DIR__. "/../config/db.php";

/** se crear la clase auth para la autenticacion de usuarios */
class Auth {
    private $db;
public function __construct(){
    $this->db = Database::Conectar();
}

/** se crea la funcion login con roles para iniciar sesion*/
public function login($user, $password){
    $sql = "SELECT * FROM rol as r INNER JOIN usuario as u ON r.id_rol=u.id_rol WHERE u.correo='$user' AND u.contraseña='$password'";
    $result = $this->db->query($sql);
    if($result->num_rows>0){
        $datos= $result->fetch_assoc();
        return $datos;

    }
    else{
        return false;
    }


}
}
?>