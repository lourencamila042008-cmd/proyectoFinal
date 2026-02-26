<?php
require_once __DIR__."/../models/Auth.php";  

class AuthController{

public function login(){

        if($_POST){
            session_start(); // 🔥 IMPORTANTE

            $model = new Auth();
            $login = $model->login($_POST['usuario'],$_POST['clave']);
           
            if($login){

                // 🔥 DEBE COINCIDIR CON principal.php
                $_SESSION['nombre_usuario'] = $login['nombre_usuario'];
                $_SESSION['rol'] = $login['tipo'];

                if($_SESSION['rol']=='admin'){
                    header("Location: principal.php?controller=login&action=admin");
                    exit();
                }

                header("Location: principal.php?controller=usuario&action=index");
                exit();
                
            } else {
                echo "No se encontró el usuario";
            }
        }

        require_once __DIR__."/../views/Auth/login.php";
    }

public function logout(){
        session_start();
        session_destroy();
        header("Location: principal.php");
    }

public function admin(){
        require_once __DIR__."/../views/admin/admin.php";
    }
}
?>