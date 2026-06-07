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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Facturas - InvoicePro</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter', sans-serif;
}

body{
    background:#f4f6f9;
    padding:30px;
    color:#1e293b;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:30px;
}

.topbar h1{
    font-size:34px;
    color:#0f172a;
}

.actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.btn{
    border:none;
    background:#17345f;
    color:white;
    padding:14px 20px;
    border-radius:14px;
    cursor:pointer;
    text-decoration:none;
    font-weight:600;
    transition:.3s;
}

.btn:hover{
    background:#264c83;
}

.btn-secondary{
    background:#64748b;
}

.btn-secondary:hover{
    background:#475569;
}

/* TABLE */

.table-container{
    background:white;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    border:1px solid #e2e8f0;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

thead th{
    padding:20px;
    text-align:left;
    color:#0f172a;
    font-size:15px;
    font-weight:600;
}

tbody td{
    padding:18px 20px;
    border-top:1px solid #e2e8f0;
    color:#475569;
    font-size:14px;
}

tbody tr:hover{
    background:#f8fafc;
}

/* ESTADOS */

.estado{
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
}

.pagada{
    background:#dcfce7;
    color:#16a34a;
}

.pendiente{
    background:#fef3c7;
    color:#d97706;
}

.anulada{
    background:#fee2e2;
    color:#dc2626;
}

/* ACCIONES */

.acciones{
    display:flex;
    gap:12px;
}

.acciones a{
    text-decoration:none;
    font-size:18px;
    transition:.2s;
}

.acciones a:hover{
    transform:scale(1.15);
}

@media(max-width:900px){

    body{
        padding:20px;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
    }

    .table-container{
        overflow-x:auto;
    }
}

</style>
</head>

<body>

<div class="topbar">

    <h1>Facturas</h1>

    <div class="actions">

        <a class="btn btn-secondary"
        href="../dashboard_admin.php">
            ⬅ Volver
        </a>

        <a class="btn"
        href="crear_factura.php">
            ➕ Nueva Factura
        </a>

    </div>

</div>

<div class="table-container">

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

    <a href="editar_factura.php?id=<?= $f['id_facturas'] ?>">
        ✏️
    </a>

    <a href="eliminar_factura.php?id=<?= $f['id_facturas'] ?>"
    onclick="return confirm('¿Eliminar factura?')">
        🗑️
    </a>

    <a href="pdf_factura.php?id=<?= $f['id_facturas'] ?>">
        📄
    </a>
      <a href="enviar_correo.php?id=<?= $f['id_facturas'] ?>">
        📄
    </a>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>