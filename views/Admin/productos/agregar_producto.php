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
      $stock  = $_POST["stock"];
    $precio = $_POST["precio_venta"];
    $precio_compra = $_POST["precio_compra"];
    $min_stock = $_POST["min_stock"];
  

    $sql = "INSERT INTO productos(id_categoria, nombre, stock, precio_compra, precio_venta, min_stock)
            VALUES (1, '$nombre', $stock, $precio_compra, $precio, 5)";

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
<link rel="stylesheet" href="../../../public/css/productos/agregar_producto.css">
</head>

<body>

<div class="form-box">

<h2>Agregar Producto</h2>

<form method="POST">

<input type="text" name="nombre" placeholder="Nombre del producto" required>
<input type="number" name="stock" placeholder="Cantidad en stock" required>

<input type="number" step="0.01" name="precio_venta" placeholder="Precio de venta" required>

<input type="number" name="precio_compra" placeholder="Precio de compra" required>

<input type="number" name="min_stock" placeholder="Mínimo stock" required>

<button type="submit">Guardar</button>

</form>

<a href="inventario.php">⬅ Volver al inventario</a>

</div>

</body>
</html>