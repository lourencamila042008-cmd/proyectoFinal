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
<link rel="stylesheet" href="../../../public/css/facturas/facturas.css">
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