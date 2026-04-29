<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

$conn->query("UPDATE proveedores 
SET nombre='$nombre', telefono='$telefono', correo='$correo'
WHERE id_proveedores=$id");

header("Location: index_proveedores.php");