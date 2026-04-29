<?php
session_start();

// 🔒 PERMITIR admin y empleado
if (!isset($_SESSION["rol"]) || 
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {
    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";

require_once "../../../config/db.php";
$conn = Database::Conectar();

$sql = "SELECT f.id_facturas, f.estado, f.fecha,
               c.nombre AS nombre_cliente,
               p.nombre AS nombre_producto,
               d.cantidad, d.precio, d.subtotal
        FROM facturas f
        JOIN clientes c ON f.id_clientes = c.id_clientes
        JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
        JOIN productos p ON d.id_productos = p.id_productos
        ORDER BY f.id_facturas DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Facturas</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI';
    background: #f4f6f9;
}

/* 🔝 TOPBAR */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn {
    background: #0f4c81;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
}

/* 📦 CONTENEDOR */
.container {
    width: 95%;
    margin: 30px auto;
}

/* 📊 TABLA */
.table-box {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #0f4c81;
    color: white;
    padding: 12px;
}

td {
    padding: 12px;
    text-align: center;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

/* 🎯 ESTADOS */
.estado {
    padding: 5px 10px;
    border-radius: 8px;
    color: white;
}

.pagada { background: #28a745; }
.pendiente { background: #ffc107; color: black; }
.anulada { background: #dc3545; }

/* ⚙️ ACCIONES */
.acciones a {
    margin: 0 5px;
    text-decoration: none;
    font-size: 18px;
}

.editar { color: #0f4c81; }
.eliminar { color: red; }
.pdf { color: green; }
</style>

</head>

<body>



<div class="topbar">
    <h1>Facturas</h1>
    <a class="btn" href="../dashboard_empleado.php">volver al inicio</a>
    <a class="btn" href="crear_factura.php">+ Nueva Factura</a>
</div>


<div class="container">

<div class="table-box">

<table>
<thead>
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Subtotal</th>
<th>Estado</th>
<th>Fecha</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php while($f = $result->fetch_assoc()){ ?>

<tr>
<td><?= $f['id_facturas'] ?></td>
<td><?= htmlspecialchars($f['nombre_cliente']) ?></td>
<td><?= htmlspecialchars($f['nombre_producto']) ?></td>
<td><?= $f['cantidad'] ?></td>
<td>$<?= number_format($f['precio'], 0, ',', '.') ?></td>
<td>$<?= number_format($f['subtotal'], 0, ',', '.') ?></td>

<td>
    <span class="estado <?= $f['estado'] ?>">
        <?= ucfirst($f['estado']) ?>
    </span>
</td>

<td><?= $f['fecha'] ?></td>

<td class="acciones">

    <!-- 🔥 SOLO ADMIN -->
    <?php if($esAdmin): ?>
        <a class="editar" href="editar_factura.php?id=<?= $f['id_facturas'] ?>">✏️</a>
        <a class="eliminar" href="eliminar_factura.php?id=<?= $f['id_facturas'] ?>"
           onclick="return confirm('¿Eliminar factura?')">🗑️</a>
    <?php endif; ?>

    <!-- TODOS -->
    <a class="pdf" href="pdf.php?id=<?= $f['id_facturas'] ?>">📄</a>

</td>

</tr>

<?php } ?>

</tbody>
</table>

</div>

</div>

</body>
</html>