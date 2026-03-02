<?php

require_once __DIR__."/../models/Auth.php";

class AuthController{

    // 🔐 LOGIN
    public function login(){

        if($_POST){

            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }

            $usuario = $_POST['usuario'];
            $password = $_POST['password'];

            $model = new Auth();
            $login = $model->login($usuario,$password);

            if($login){

                $_SESSION['usuario'] = $login['nombre_usuario'];
                $_SESSION['id_usuario'] = $login['id_usuario'];
                $_SESSION['rol'] = strtolower($login['tipo']);

                // 🔥 REDIRECCIÓN POR ROL
                if($_SESSION['rol'] == 'admin'){
                    header("Location: index.php?controller=admin&action=index");
                    exit;
                }

                header("Location: index.php?controller=usuario&action=index");
                exit;
            }

            echo "Credenciales incorrectas";
        }

        require_once __DIR__."/../views/Auth/login.php";
    }


    // 📝 REGISTER
    public function register(){

        if($_POST){

            $model = new Auth();

            if($model->register($_POST)){
                header("Location: index.php?controller=auth&action=login");
                exit;
            }else{
                echo "Error al registrar";
            }
        }

        require_once __DIR__."/../views/Auth/register.php";
    }


    // 🚪 LOGOUT
    public function logout(){

        session_start();
        session_destroy();

        header("Location: index.php");
    }
}
?>