<?php

// Cargo el modelo que tiene las funciones de usuario
require_once "models/Usuario.php";

// Controlador para manejar las acciones de usuario
class UsuarioController{

    // 🔹 PÁGINA DE BIENVENIDA
    // Muestra el mensaje principal y el enlace para cerrar sesión
    public function index(){
        echo "<h1>Bienvenida a InvoicePro 💙</h1>";
        echo "<a href='index.php?controller=auth&action=logout'>Cerrar sesión</a>";
    }

    // 🔹 GUARDAR USUARIO NUEVO
    // Envía los datos del formulario al modelo para guardar en la base
    public function guardar(){

        $model = new Usuario();
        $model->crear($_POST);

        // Regresa a la lista con mensaje de confirmación
        header("Location: /MVC-PRU/index.php?controller=Usuario&action=listar&msg=creado");
        exit();
    }

    // 🔹 CARGAR PANEL DE ADMINISTRADOR
    // Muestra la vista del tablero principal para el admin
    public function adminDashboard(){
       
        require_once "views/Admin/dashboard_admin.php";
    }
}