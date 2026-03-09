<?php
session_start();

require_once "controllers/UsuarioController.php";
require_once "controllers/AuthController.php";
require_once "controllers/ProductosController.php";
require_once "controllers/FacturasController.php";
require_once "controllers/GarantiasController.php";

$controller = $_GET['controller'] ?? 'auth';
$action     = $_GET['action'] ?? 'login';

// 🔐 SI NO ESTÁ LOGUEADO → SOLO AUTH
if(!isset($_SESSION['usuario']) && $controller != 'auth'){
    $controller = 'auth';
    $action = 'login';
}

// 🔒 SI YA ESTÁ LOGUEADO Y QUIERE LOGIN → REDIRIGIR
if(isset($_SESSION['usuario']) && $controller == 'auth'){
    $controller = 'usuario';
    $action = 'index';
}

switch($controller){

  case 'usuario':
    $controller = new UsuarioController();
    break;

  case 'auth':
    $controller = new AuthController();
    break;

  case 'productos':
    $controller = new ProductosController();
    break;

    case 'facturas':
      $controller = new FacturasController();
      break;


  default:
    $controller = new AuthController();
    break;
}

if(method_exists($controller,$action)){
    $controller->$action();
}else{
    echo "La acción no existe";
}