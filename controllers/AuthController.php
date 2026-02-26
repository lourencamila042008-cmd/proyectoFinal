<?php
/** llamando archivo AUTH */
require_once __DIR__."/../models/Auth.php";  

class AuthController{

/** se crea la funcion login */
public function login(){
        if($_POST){
            $model = new Auth();
            $login = $model->login($_POST['usuario'],$_POST['clave']);
           
            /** se crea un "si" para identificar el rol del usuario al iniciar sesion */
            if($login){
                $_SESSION['usuario']=$login['nombre_usuario'];
                $_SESSION['rol']=$login['tipo'];
                if($_SESSION['rol']=='admin'){
                    header("location:principal.php?controller=login&action=admin");
                }
             header("Location: principal.php?controller=usuario&action=index");
                
            }
            else{
                echo "no se encontro el usuario";
            }
        }
        require_once __DIR__."/../views/Auth/login.php";
    }

    /** se crea funcion para cerrar sesion */
    public function logout(){
        session_start();
        session_destroy();
        header("Location: login.php");
    }

    /** se crea funcion para crear el panel de administrador */
    public function admin(){
                require_once __DIR__."/../views/admin/admin.php";


    }
}
?>