<?php
require_once __DIR__."/../models/Usuario.php";

class UsuarioController{

public function index(){
 $usuario = new Usuario();
$datos= $usuario->mostrar();

require_once __DIR__."/../views/usuario/listar.php";
}
}

?>