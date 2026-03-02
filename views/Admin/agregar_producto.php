<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once __DIR__ . "/../../config/db.php";
$conn = Database::Conectar();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $stock  = $_POST["stock"];

    $sql = "INSERT INTO productos(id_categoria, nombre, stock, precio_compra, precio_venta, min_stock)
            VALUES (1, '$nombre', $stock, $precio, $precio, 5)";

    if($conn->query($sql)){
        header("Location: inventario.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Producto</title>
<link rel="stylesheet" href="../../public/css/agregar_producto.css">
</head>

<body>

<div class="form-box">

<h2>Agregar Producto</h2>

<form method="POST">

<input type="text" name="nombre" placeholder="Nombre del producto" required>

<input type="number" step="0.01" name="precio" placeholder="Precio" required>

<input type="number" name="stock" placeholder="Cantidad en stock" required>

<button type="submit">Guardar</button>

</form>

<a href="inventario.php">⬅ Volver al inventario</a>

</div>

</body>
</html>