<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

// JOIN para traer nombre del cliente y productos del detalle
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
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #2f7bbd, #3fa9f5);
    min-height: 100vh;
    padding: 40px;
}

.container {
    background: white;
    padding: 30px;
    border-radius: 15px;
    max-width: 1200px;
    margin: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    overflow-x: auto; /* ← agrega esto */
}
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

h1 { color: #0f4c81; }

.btn {
    background: #0f4c81;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px; /* ← agrega esto */
}

th {
    background: #0f4c81;
    color: white;
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

tr:hover { background: #f2f9ff; }

.estado {
    padding: 5px 10px;
    border-radius: 6px;
    color: white;
    font-size: 12px;
}

.pagada   { background: #2ecc71; }
.pendiente{ background: #f39c12; }
.anulada  { background: #e74c3c; }

.acciones a {
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    margin: 2px;
    font-size: 13px;
}

.editar  { background: #3498db; }
.eliminar{ background: #e74c3c; }
.pdf     { background: #2ecc71; }
</style>
</head>

<body>
<div class="container">

    <div class="topbar">
        <h1>Facturas</h1>
        <a class="btn" href="crear_factura.php">+ Nueva Factura</a>
    </div>

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
                    <a class="editar"   href="editar_factura.php?id=<?= $f['id_facturas'] ?>">✏️</a>
                    <a class="eliminar" href="eliminar_factura.php?id=<?= $f['id_facturas'] ?>"
                       onclick="return confirm('¿Eliminar factura?')">🗑️</a>
                    <a class="pdf"      href="pdf_factura.php?id=<?= $f['id_facturas'] ?>">📄</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</body>
</html>