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

            if (empty($usuario) || empty($password)) {
        die("Todos los campos son obligatorios");
    }

    
    if (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
        die("Correo inválido");
    }

                $_SESSION['usuario'] = $login['nombre_usuario'];
                $_SESSION['id_usuario'] = $login['id_usuario'];
                $_SESSION['rol'] = strtolower($login['tipo']);

                // 🔥 REDIRECCIÓN POR ROL
                if($_SESSION['rol'] == 'admin'){
                    header("Location: index.php?controller=admin&action=index");
                    exit;
                }
 if (password_verify($password, $usuario["password"])) {

            // ✅ Crear sesión
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
                

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

        // 🔹 Limpiar datos
        $nombre = trim($_POST["nombre"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");
        $confirmPassword = trim($_POST["confirm_password"] ?? "");

        // 🔴 Validaciones
        if(empty($nombre) || empty($email) || empty($password) || empty($confirmPassword)){
            echo "Todos los campos son obligatorios";
            return;
}

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo "Correo inválido";
            return;
        }
        // ❌ No permitir números en el nombre
if (preg_match('/[0-9]/', $nombre)) {
    echo "El nombre no puede contener números";
    return;
}

        if(strlen($password) < 6){
            echo "La contraseña debe tener mínimo 6 caracteres";
            return;
        }

        if($password !== $confirmPassword){
            echo "Las contraseñas no coinciden";
            return;
        }

        // 🔐 Encriptar contraseña
        $_POST["password"] = password_hash($password, PASSWORD_DEFAULT);

        $model = new Auth();

        if($model->register($_POST)){
            header("Location: index.php?controller=auth&action=login");
            exit;
        }else{
            echo "Error al registrar (posible correo duplicado)";
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