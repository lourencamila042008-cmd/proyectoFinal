<?php
require_once __DIR__. "/../Models/productos.php";

class ProductosController{

    /** LISTAR PRODUCTOS */
    public function index(){
        $productos = new Productos();
        $datos = $productos->mostrar();

        require_once __DIR__. "/../views/productos/listar.php";
    }

    /** CREAR PRODUCTO */
    public function crear(){

        if($_POST){
            $productos = new Productos();

            $p = $productos->save(
                $_POST['id_categoria'],
                $_POST['nombre'],
                $_POST['stock'],
                $_POST['precio_compra'],
                $_POST['precio_venta'],
                $_POST['min_stock']
            );

            // 🔥 PASA POR PRINCIPAL Y VA A LISTAR
            header("Location: principal.php?controller=productos&action=index");
            exit();
        }

        require_once __DIR__. "/../views/productos/crear.php";
    }

    /** EDITAR PRODUCTO */
    public function editar(){
        $productos = new Productos();

        if($_POST){
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

        $datos = $productos->GetById($_GET['id']);
        require_once __DIR__. "/../views/productos/editar.php";
    }

    /** ELIMINAR PRODUCTO */
    public function eliminar(){
        $productos = new Productos();
       $p = $productos->DELETE($_GET['id']);

        header("Location: principal.php?controller=productos&action=index");
        exit();
    }
}
?>