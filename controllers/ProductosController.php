<?php

// Cargo el modelo que tiene las funciones de productos
require_once __DIR__. "/../Models/productos.php";

// Controlador para manejar todas las acciones de productos
class ProductosController{

    // 🔹 LISTAR TODOS LOS PRODUCTOS
    // Pide todos los datos al modelo y carga la vista para mostrarlos
    public function index(){
        $productos = new Productos();
        $datos = $productos->mostrar();

        require_once __DIR__. "/../views/productos/listar.php";
    }

    // 🔹 CREAR NUEVO PRODUCTO
    // Si se envió el formulario, guarda los datos. Si no, muestra el formulario
    public function crear(){

        if($_POST){
            $productos = new Productos();

            // Envía todos los datos al modelo para guardar
            $p = $productos->save(
                $_POST['id_categoria'],
                $_POST['nombre'],
                $_POST['stock'],
                $_POST['precio_compra'],
                $_POST['precio_venta'],
                $_POST['min_stock']
            );

            // Guarda y regresa a la lista
            header("Location: principal.php?controller=productos&action=index");
            exit();
        }

        // Muestra el formulario de creación
        require_once __DIR__. "/../views/productos/crear.php";
    }

    // 🔹 EDITAR PRODUCTO EXISTENTE
    // Si envió cambios, los actualiza. Si no, carga los datos para mostrarlos
    public function editar(){
        $productos = new Productos();

        if($_POST){
            // Envía los nuevos datos al modelo para actualizar
            $p = $productos->update(
                $_POST['id'],
                $_POST['id_categoria'],
                $_POST['nombre'],
                $_POST['stock'],
                $_POST['precio_compra'],
                $_POST['precio_venta'],
                $_POST['min_stock']
            );

            header("Location: principal.php?controller=productos&action=index");
            exit();
        }

        // Busca el producto por ID y carga el formulario con sus datos
        $datos = $productos->GetById($_GET['id']);
        require_once __DIR__. "/../views/productos/editar.php";
    }

    // 🔹 ELIMINAR PRODUCTO
    // Llama al modelo para borrar el registro y vuelve a la lista
    public function eliminar(){
        $productos = new Productos();
       $p = $productos->DELETE($_GET['id']);

        header("Location: principal.php?controller=productos&action=index");
        exit();
    }
}
?>