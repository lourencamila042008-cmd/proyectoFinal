<?php
/** llamando el archivo usuario */
require_once __DIR__."/../models/Usuario.php";

class UsuarioController{

/**se crea la funcion index para mostrar los datos */
public function index(){
 $usuario = new Usuario();
$datos= $usuario->mostrar();



require_once __DIR__."/../views/usuario/listar.php";
}

/**se crea la funcion para crear usuarios */
public function crear(){
    if($_POST){
        $usuario = new Usuario();
        $u=$usuario->save(
            $_POST['nombre_negocio'],
            $_POST['nombre_usuario'],
            $_POST['apellido_usuario'],
            $_POST['telefono'],
            $_POST['correo'],
                    $_POST['id_rol'],
                      $_POST['contraseña']
        );
        header("Location: principal.php");
    }
    require_once __DIR__."/../views/usuario/crear.php";
}

/** se crear la funcion para editar usuario */
public function editar(){
       $usuario = new Usuario();
      if($_POST){
        $u=$usuario->update(
            $_POST['id_usuario'],
            $_POST['nombre_negocio'],
            $_POST['nombre_usuario'],
            $_POST['apellido_usuario'],
            $_POST['telefono'],
            $_POST['correo'],
            $_POST['id_rol'],
            $_POST['contraseña']
        );
        header("Location: principal.php");
    }
    $datos = $usuario->GetById($_GET['id']);
    require_once __DIR__."/../views/usuario/editar.php";
}

/**se crea la funcion para eliminar usuarios */
public function eliminar(){
    $usuario = new Usuario();
    $u = $usuario->delete($_GET['id']);
    header("location: principal.php");
}

}


?>