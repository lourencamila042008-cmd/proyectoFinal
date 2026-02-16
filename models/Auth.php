<?php
require_once __DIR__. "/../config/db.php";

class Auth {
    private $db;
public function __construct(){
    $this->db = Database::Conectar();
}

public function login($user, $password){
    $sql = "SELECT * FROM usuario WHERE correo = '$user'";
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