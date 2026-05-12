<?php
require_once "models/Usuario.php";

class UsuarioController{

    public function index(){
        echo "<h1>Bienvenida a InvoicePro 💙</h1>";
        echo "<a href='index.php?controller=auth&action=logout'>Cerrar sesión</a>";
    }

    public function guardar(){

        $model = new Usuario();

        $model->crear($_POST);

        header("Location: /MVC-PRU/index.php?controller=Usuario&action=listar&msg=creado");
        exit();
    }

    public function adminDashboard(){
       
        require_once "views/Admin/dashboard_admin.php";
    }
}