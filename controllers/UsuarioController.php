<?php
require_once __DIR__."/../models/Usuario.php";

class UsuarioController{

public function index(){
 $usuario = new Usuario();
$datos= $usuario->mostrar();



require_once __DIR__."/../views/usuario/listar.php";
}

public function crear(){
    if($_POST){
        $usuario = new Usuario();
        $u=$usuario->save(
            $_POST['nombre_negocio'],
            $_POST['nombre_usuario'],
            $_POST['apellido_usuario'],
            $_POST['telefono'],
            $_POST['correo'],
            $_POST['id_rol']
        );
        header("Location: principal.php");
    }
    require_once __DIR__."/../views/usuario/crear.php";
}

}


?>