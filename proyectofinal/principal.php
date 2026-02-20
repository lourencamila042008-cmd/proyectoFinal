<?php
require_once "controllers/UsuariosController.php";
require_once "controllers/Authcontroller.php";

$controller=$_GET['controller'] ?? null;
$action=$_GET['action'] ?? null;

$controller=$controller ?? 'login';
$action=$action ?? 'login';

switch($controller){
  case 'Usuarios':
    $controller=new UsuariosController();
   break;

    case 'login':
    $controller=new AuthController();
   break;
   
   break;
   default:
     $controller=new UsuariosController();
   break;

}

if(method_exists($controller,$action)){
    $controller->$action();
}else{
    echo "la action no esta permitida o no existe";
}

?>