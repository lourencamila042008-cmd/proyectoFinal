<?php

// Cargo el modelo que tiene las funciones de clientes
require_once __DIR__."/../models/Clientes.php";

// Controlador para manejar todas las acciones de clientes
class ClientesController{

    // 🔹 MOSTRAR TODOS LOS CLIENTES
    // Pide todos los datos al modelo y muestra la lista
    public function index(){
        $model = new Cliente();
        $clientes = $model->obtenerTodos();

        require_once "views/Admin/clientes/index.php";
    }

    // 🔹 MOSTRAR FORMULARIO NUEVO CLIENTE
    public function crear(){
        require_once "views/Admin/clientes/crear.php";
    }

    // 🔹 GUARDAR CLIENTE NUEVO EN LA BASE
    // Recibe los datos del formulario y llama al modelo para guardar
    public function guardar(){
        $model = new Cliente();

        $model->crear(
            $_POST["nombre"],
            $_POST["cedula"],
            $_POST["telefono"]
        );

        // Vuelve a la lista principal
        header("Location: index.php?controller=clientes&action=index");
    }

    // 🔹 CARGAR DATOS PARA EDITAR
    // Busca un cliente por ID y muestra el formulario con sus datos
    public function editar(){
        $model = new Cliente();
        $cliente = $model->obtenerPorId($_GET["id"]);

        require_once "views/Admin/clientes/editar.php";
    }

    // 🔹 ACTUALIZAR DATOS DEL CLIENTE
    // Envía los cambios al modelo para guardarlos
    public function actualizar(){
        $model = new Cliente();

        $model->actualizar(
            $_POST["id"],
            $_POST["nombre"],
            $_POST["cedula"],
            $_POST["telefono"]
        );

        // Vuelve a la lista
        header("Location: index.php?controller=clientes&action=index");
    }

    // 🔹 ELIMINAR CLIENTE
    // Borra el registro según el ID que llega por la URL
    public function eliminar(){
        $model = new Cliente();
        $model->eliminar($_GET["id"]);

        header("Location: index.php?controller=clientes&action=index");
    }
}