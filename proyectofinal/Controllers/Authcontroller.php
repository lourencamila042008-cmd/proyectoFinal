<?php
require_once __DIR__."/../models/Auth.php";

class Authcontroller {

public function login(){

if($_POST){
     $model=new Auth();
     $login=$model->login($_POST['usuario'],$_POST['contraseña']);

     if($login){
        header("location: principal.php?controller=usuarios&action=principal");
        exit;
       }else{

        echo "no se encontro el usuario";
     }


}
  require_once __DIR__."/../views/Auth//login.php"; 
}
}
?>