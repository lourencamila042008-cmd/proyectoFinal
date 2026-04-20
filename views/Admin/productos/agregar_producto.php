<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once __DIR__ . "/../../../config/db.php";
$conn = Database::Conectar();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombre = $_POST["nombre"];
      $stock  = $_POST["stock"];
    $precio = $_POST["precio_venta"];
    $precio_compra = $_POST["precio_compra"];
    $min_stock = $_POST["min_stock"];

    // VALIDACIONES

    // Nombre
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    }

    // Stock
    if (!is_numeric($stock) || $stock < 0) {
        $errores[] = "El stock no puede ser negativo y debe ser numérico";
    }

    // Precio venta
    if (!is_numeric($precio) || $precio < 0) {
        $errores[] = "El precio de venta no puede ser negativo";
    }

    // Precio compra
    if (!is_numeric($precio_compra) || $precio_compra < 0) {
        $errores[] = "El precio de compra no puede ser negativo";
    }

    // Stock mínimo
    if (!is_numeric($min_stock) || $min_stock < 0) {
        $errores[] = "El stock mínimo no puede ser negativo";
    }

    // SI NO HAY ERRORES → GUARDAR
    if (empty($errores)) {

        $stmt = $conn->prepare("INSERT INTO productos 
        (id_categoria, nombre, stock, precio_compra, precio_venta, min_stock)
        VALUES (?, ?, ?, ?, ?, ?)");

        $id_categoria = 1;

        $stmt->bind_param(
            "isidii",
            $id_categoria,
            $nombre,
            $stock,
            $precio_compra,
            $precio,
            $min_stock
        );

        if($stmt->execute()){
            header("Location: inventario.php");
            exit();
        } else {
            $errores[] = "Error al guardar el producto";
        }
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

  <?php if (!empty($errores)): ?>
    <div style="color:red;">
        <?php foreach($errores as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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