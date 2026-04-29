<?php
session_start();

// 🔒 PERMITIR admin y empleado
if (!isset($_SESSION["rol"]) || 
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {
    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";

require_once __DIR__ . "/../../../config/db.php";
$conn = Database::Conectar();

$sql = "SELECT * FROM productos ORDER BY id_productos DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario - InvoicePro</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #3a7bd5, #00c6ff);
}

/* 🔝 Barra superior */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    color: white;
}

.top-bar input {
    padding: 10px;
    border-radius: 8px;
    border: none;
    width: 250px;
}

/* Botón */
.top-bar button {
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    background: white;
    color: #0f4c81;
    font-weight: bold;
    cursor: pointer;
}

/* 📦 CONTENEDOR */
.table-container {
    width: 90%;
    margin: 20px auto;
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* 📊 TABLA */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #0f4c81;
    color: white;
}

th, td {
    padding: 12px;
    text-align: center;
}

tr:nth-child(even) {
    background: #f4f6f9;
}

tr:hover {
    background: #e6f0ff;
}

/* 🔴 SIN STOCK */
.agotado {
    background: #ff4d4d !important;
    color: white;
    font-weight: bold;
}

/* 🟡 STOCK BAJO */
.bajo {
    background: #fff3cd !important;
}

/* Hover */
.agotado:hover {
    background: #ff3333 !important;
}

.bajo:hover {
    background: #ffe69c !important;
}
.btn {
    background: white;
    color: navy;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
}
</style>

</head>

<body>

<div class="top-bar">
    <h1>Inventario</h1>
<a class="btn" href="../dashboard_empleado.php">volver al inicio</a>
    <input type="text" id="buscar" placeholder="Buscar producto...">

    <!-- SOLO ADMIN -->
    <?php if($esAdmin): ?>
        <button onclick="location.href='agregar_producto.php'">
            ➕ Agregar producto
        </button>
    <?php endif; ?>
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

<?php while($p = $result->fetch_assoc()): 

    $clase = "";

    if ($p["stock"] <= 0) {
        $clase = "agotado";
    } elseif ($p["stock"] <= $p["min_stock"]) {
        $clase = "bajo";
    }
?>

<tr class="<?= $clase ?>">

<td><?= $p["id_productos"] ?></td>

<td><?= htmlspecialchars($p["nombre"]) ?></td>

<td>
    <?= $p["stock"] ?>

    <?php if($p["stock"] <= 0): ?>
        <span>(AGOTADO)</span>
    <?php elseif($p["stock"] <= $p["min_stock"]): ?>
        <span style="color:#856404; font-weight:bold;">(BAJO)</span>
    <?php endif; ?>
</td>

<td><?= $p["precio_venta"] ?></td>
<td><?= $p["precio_compra"] ?></td>
<td><?= $p["min_stock"] ?></td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<script>
// 🔎 BUSCADOR
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