<?php
require_once __DIR__. "/../Models/productos.php";

class ProductosController{

public function index(){
    $productos=new productos();
    $datos=$productos->mostrar();

    require_once __DIR__. "/../views/productos/listar.php";

}

public function crear(){
    if($_POST){
        $productos=new productos();
        $p=$productos->save(
            $_POST['id_categoria'],
            $_POST['nombre'],
            $_POST['stock'],
            $_POST['precio_compra'],
            $_POST['precio_venta'],
            $_POST['min_stock'],

        );
        header("location:principal.php");
    }
    require_once __DIR__. "/../Views/productos/crear.php";
}

public function editar(){
    $productos=new productos();
    if($_POST){
        $p=$productos->update(
            $_POST['id'],
            $_POST['id_categoria'],
            $_POST['nombre'],
            $_POST['stock'],
            $_POST['precio_compra'],
            $_POST['precio_venta'],
            $_POST['min_stock'],
        );
        header("location:principal.php");
        }
        $datos=$productos->GetById($_GET['id']);
        require_once __DIR__. "/../Views/productos/editar.php";

}

public function eliminar(){
    $productos=new productos();
    $p=$productos->DELETE($_GET['id']);
    header("location: principal.php");
}

}
?>