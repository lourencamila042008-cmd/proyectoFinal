<?php
require_once __DIR__."/../models/usuarios.php";

class UsuariosController{

public function index(){
    $usuarios=new usuarios();
    $datos=$usuarios->mostrar();

    require_once __DIR__."/../Views/usuario/listar.php";

}
public function crear(){
    if($_POST){
        $usuarios=new usuarios();
        $u=$usuarios->save(
            $_POST['usuario'],
            $_POST['contraseña'],
            $_POST['id_rol'],
            $_POST['estado'],
            $_POST['fecha_creacion'],
    );
    header("location: principal.php");

    }
     require_once __DIR__."/../Views/usuario/crear.php";
}


public function editar(){
    $usuarios=new usuarios();
    if($_POST){


    $u=$usuarios->update(
        $_POST['id'],
        $_POST['usuario'],
        $_POST['contraseña'],
        $_POST['id_rol'],
        $_POST['estado'],
        $_POST['fecha_creacion'],

    );

    header("location: principal<
    
    .php");

    }
    $datos=$usuarios->GetById($_GET['id']);
    require_once __DIR__. "/../views/usuario/editar.php";


}

public function eliminar(){
    $usuarios=new usuarios();
    $u=$usuarios->delete($_GET['id']);
    header("location: principal.php");
}
}
?>