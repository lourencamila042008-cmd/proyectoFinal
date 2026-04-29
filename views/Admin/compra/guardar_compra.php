<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$id_proveedor = $_POST['id_proveedor'];
$id_producto = $_POST['id_producto'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];

$errores = [];

if(empty($id_proveedor)) $errores[]="Proveedor obligatorio";
if(empty($id_producto)) $errores[]="Producto obligatorio";
if($cantidad <= 0) $errores[]="Cantidad inválida";
if($precio <= 0) $errores[]="Precio inválido";
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
    echo "<a href='crear_compra.php'>Volver</a>";
    exit;
}

$total = $cantidad * $precio;

$conn->query("INSERT INTO compras(id_proveedor,precio_total,fecha)
VALUES('$id_proveedor','$total',NOW())");

$id_compra = $conn->insert_id;

$conn->query("INSERT INTO detalle_compra(id_compra,id_producto,cantidad,precio_compra)
VALUES('$id_compra','$id_producto','$cantidad','$precio')");

$conn->query("UPDATE productos SET stock = stock + $cantidad WHERE id_producto = $id_producto");

echo "<p class='success'>Compra registrada correctamente</p>";
echo "<a href='crear_compra.php'>Volver</a>";