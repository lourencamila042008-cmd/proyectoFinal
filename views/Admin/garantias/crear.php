<?php
require_once "../../../config/db.php";

$conn = Database::Conectar();

$productos = $conn->query("SELECT * FROM productos");
$facturas = $conn->query("SELECT * FROM facturas");

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

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Nueva Garantía</title>

<link rel="stylesheet" href="../../../public/css/garantias.css">

</head>

<body>

<div class="container">

<h1>Registrar Garantía</h1>

<form method="POST" action="../../controllers/GarantiasController.php">

<label>Factura</label>

<select name="id_factura">

<?php foreach($facturas as $f): ?>

<option value="<?= $f['id_facturas'] ?>">

Factura #<?= $f['id_facturas'] ?>

</option>

<?php endforeach; ?>

</select>


<label>Producto</label>

<select name="id_producto">

<?php foreach($productos as $p): ?>

<option value="<?= $p['id_productos'] ?>">

<?= $p['nombre'] ?>

</option>

<?php endforeach; ?>

</select>


<label>Motivo</label>

<textarea name="motivo"></textarea>


<label>Solución</label>

<select name="solucion">

<option value="cambio">Cambio</option>
<option value="reparacion">Reparación</option>
<option value="devolucion">Devolución</option>

</select>


<label>Estado</label>

<select name="estado">

<option value="pendiente">Pendiente</option>
<option value="en_revision">En revisión</option>
<option value="resuelto">Resuelto</option>

</select>


<button type="submit">Guardar Garantía</button>

</form>

</div>

</body>
</html>