<?php

// Cargo el modelo que tiene las funciones de autenticación
require_once __DIR__."/../models/Auth.php";

// Controlador para manejar el inicio de sesión, registro y salida
class AuthController{

    // 🔐 FUNCIÓN PARA INICIAR SESIÓN
    public function login(){

        // Si se envió el formulario
        if($_POST){

            // Inicio la sesión si no está iniciada
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }

            // Recibo los datos que escribió el usuario
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];

            // Llamo al modelo para verificar si existe
            $model = new Auth();
            $login = $model->login($usuario,$password);

            // Valido que no hayan dejado campos vacíos
            if (empty($usuario) || empty($password)) {
                die("Todos los campos son obligatorios");
            }

            // Valido que el correo tenga formato correcto
            if (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
                die("Correo inválido");
            }

            // Guardo los datos en la sesión para usarlos en todo el sistema
            $_SESSION['usuario'] = $login['nombre_usuario'];
            $_SESSION['id_usuario'] = $login['id_usuario'];
            $_SESSION['rol'] = strtolower($login['tipo']);

            // 🔥 REDIRIGIR SEGÚN EL ROL
            // Si es administrador, va a su panel
            if($_SESSION['rol'] == 'admin'){
                header("Location: index.php?controller=usuario&action=adminDashboard");
                exit;
            }

            // Verificación de contraseña (parte de seguridad)
            if (password_verify($password, $usuario["password"])) {

                // Datos adicionales para la sesión
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["nombre"] = $usuario["nombre"];

                // Si es empleado o otro rol, va al panel general
                header("Location: index.php?controller=usuario&action=index");
                exit;
            }

            // Si algo falla, muestro mensaje
            echo "Credenciales incorrectas";
        }

        // Si no hay envío, muestro el formulario de login
        require_once __DIR__."/../views/Auth/login.php";
    }


    // 📝 FUNCIÓN PARA REGISTRARSE
    public function register(){

        // Si se envió el formulario
        if($_POST){

            // 🔹 Limpio los espacios vacíos de los datos
            $nombre = trim($_POST["nombre"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $password = trim($_POST["password"] ?? "");
            $confirmPassword = trim($_POST["confirm_password"] ?? "");

            // 🔴 VALIDACIONES ANTES DE GUARDAR
            // Que no falte ningún campo
            if(empty($nombre) || empty($email) || empty($password) || empty($confirmPassword)){
                echo "Todos los campos son obligatorios";
                return;
            }

            // Que el correo sea válido
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "Correo inválido";
                return;
            }

            // Que el nombre no tenga números
            if (preg_match('/[0-9]/', $nombre)) {
                echo "El nombre no puede contener números";
                return;
            }

            // Que la contraseña tenga mínimo 6 caracteres
            if(strlen($password) < 6){
                echo "La contraseña debe tener mínimo 6 caracteres";
                return;
            }

            // Que las dos contraseñas escritas sean iguales
            if($password !== $confirmPassword){
                echo "Las contraseñas no coinciden";
                return;
            }

            // 🔐 Encripto la contraseña antes de guardarla
            $_POST["password"] = password_hash($password, PASSWORD_DEFAULT);

            // Llamo al modelo para guardar en la base
            $model = new Auth();

            // Si se guardó bien, voy al login, si no, error
            if($model->register($_POST)){
                header("Location: index.php?controller=auth&action=login");
                exit;
            }else{
                echo "Error al registrar (posible correo duplicado)";
            }
        }

        // Si no hay envío, muestro el formulario de registro
        require_once __DIR__."/../views/Auth/register.php";
    }


    // 🚪 FUNCIÓN PARA CERRAR SESIÓN
    public function logout(){

        // Inicio sesión y la destruyo para borrar todo
        session_start();
        session_destroy();

        // Regreso a la página principal
        header("Location: index.php");
    }
}
?>