<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

$errores = [];

if(empty($nombre)) $errores[]="Nombre obligatorio";
if(!preg_match("/^[0-9]+$/",$telefono)) $errores[]="Teléfono inválido";
if(!filter_var($correo,FILTER_VALIDATE_EMAIL)) $errores[]="Correo inválido";
?>

<style>
body{font-family:Arial;background:#f4f6f9;text-align:center;padding:50px;}
.error{color:red;}
.success{color:green;}
a{display:block;margin-top:15px;}
</style>

<?php

if($errores){
    foreach($errores as $e){
        echo "<p class='error'>$e</p>";
    }
    echo "<a href='crear_proveedor.php'>Volver</a>";
    exit;
}

$conn->query("INSERT INTO proveedores(nombre,telefono,correo)
VALUES('$nombre','$telefono','$correo')");

echo "<p class='success'>Proveedor guardado</p>";
echo "<a href='index_proveedores.php'>Volver</a>";