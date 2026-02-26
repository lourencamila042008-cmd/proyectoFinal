<?php
session_start();

require_once "controllers/UsuarioController.php";
require_once "controllers/AuthController.php";
require_once "controllers/ProductosController.php";

$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;

/** 🔥 ARREGLO AQUÍ */
if(!isset($_SESSION['usuario'])){
    $controller = 'login';
    $action = 'login';
}
else{
    $controller = $controller ?? 'usuario';
    $action = $action ?? 'index';
}

switch($controller){
  case 'usuario':
    $controller = new UsuarioController();
    break;

  case 'login':
    $controller = new AuthController();
    break;

  case 'productos':
    $controller = new ProductosController();
    break;

  default:
    $controller = new UsuarioController();
    break;
}

if(method_exists($controller,$action)){
    $controller->$action();
}else{
    echo "la action no esta permitida o no existe";
}
?>