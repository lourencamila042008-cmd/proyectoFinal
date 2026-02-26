<?php
session_start();

require_once "controllers/UsuarioController.php";
require_once "controllers/AuthController.php";

$controller=$_GET['controller'] ?? null;
$action=$_GET['action'] ?? null;

if(!isset($_SESSION['usuario'])){
   $controller='login';
$action='login';
}
else{
  $controller=$controller ?? 'usuario';
  $action=$action ?? 'index';
}


switch($controller){
  case 'usuario':
    $controller=new UsuarioController();
   break;
   case 'login':
   $controller=new AuthController();
   break;
   default:
     $controller=new UsuarioController();
   break;

}

if(method_exists($controller,$action)){
    $controller->$action();
}else{
    echo "la action no esta permitida o no existe";
}

?>