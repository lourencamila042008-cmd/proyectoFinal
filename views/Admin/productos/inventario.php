<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once __DIR__ . "/../../../config/db.php";
$conn = Database::Conectar();

// 🔎 OBTENER PRODUCTOS
$sql = "SELECT * FROM productos ORDER BY id_productos DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario - InvoicePro</title>
<link rel="stylesheet" href="../../../public/css/productos/inventario.css">
</head>

<body>

<div class="top-bar">
    <h1>Inventario</h1>

    <input type="text" id="buscar" placeholder="Buscar producto...">

    <button onclick="location.href='agregar_producto.php'">
        ➕ Agregar producto
    </button>
</div>
<div class="table-container">
<table id="tablaProductos">

<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Stock</th>
<th>Precio Venta</th>
<th>Precio Compra</th>
<th>Mínimo Stock</th>

</tr>
</thead>

<tbody>

<?php while($p = $result->fetch_assoc()): ?>

<tr>
<td><?= $p["id_productos"] ?></td>
<td><?= $p["nombre"] ?></td>
<td><?= $p["stock"] ?></td>
<td><?= $p["precio_venta"] ?></td>
<td><?= $p["precio_compra"] ?></td>
<td><?= $p["min_stock"] ?></td>
</tr>

<?php endwhile; ?>

</tbody>
</table>
</div>
<script>
// 🔎 BUSCADOR EN TIEMPO REAL
document.getElementById("buscar").addEventListener("keyup", function() {

    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaProductos tbody tr");

    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });

});
</script>

</body>
</html>