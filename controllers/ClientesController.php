<?php
require_once "models/Cliente.php";

class ClientesController{

    public function index(){
        $model = new Cliente();
        $clientes = $model->obtenerTodos();

        require_once "views/Admin/clientes/index.php";
    }

    public function crear(){
        require_once "views/Admin/clientes/crear.php";
    }

    public function guardar(){
        $model = new Cliente();

        $model->crear(
            $_POST["nombre"],
            $_POST["cedula"],
            $_POST["telefono"]
        );

        header("Location: index.php?controller=clientes&action=index");
    }

    public function editar(){
        $model = new Cliente();
        $cliente = $model->obtenerPorId($_GET["id"]);

        require_once "views/Admin/clientes/editar.php";
    }

    public function actualizar(){
        $model = new Cliente();

        $model->actualizar(
            $_POST["id"],
            $_POST["nombre"],
            $_POST["cedula"],
            $_POST["telefono"]
        );

        header("Location: index.php?controller=clientes&action=index");
    }

    public function eliminar(){
        $model = new Cliente();
        $model->eliminar($_GET["id"]);

        header("Location: index.php?controller=clientes&action=index");
    }
}