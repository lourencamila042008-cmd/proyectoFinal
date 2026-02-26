<?php
require_once __DIR__."/../models/Auth.php";  

class AuthController{
    public function login(){
        if($_POST){
            $model = new Auth();
            $login = $model->login($_POST['correo'],$_POST['contraseña']);
           
            if($login){
             header("Location: principal.php");
                exit;
            }
            else{
                echo "no se encontro el usuario";
            }
        }
        require_once __DIR__."/../views/Auth/login.php";
    }

    public function logout(){
        session_start();
        session_destroy();
        header("Location: login.php");
    }
}
?>