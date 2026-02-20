<?php
require_once __DIR__."/../Config/db.php";

class Auth{
      private $db;
    public function __construct(){
        $this->db=Database::Conectar();

    }
    public function login($user,$pass){
        $sql="SELECT* FROM usuarios WHERE usuario='$user' AND contraseña='$pass'";
        
        $resul=$this->db->query($sql);

        if($resul->num_rows>0){
            $datos=$resul->fetch_assoc();
            return $datos;
        }
        else{
            return false;
        }
}
}
?>