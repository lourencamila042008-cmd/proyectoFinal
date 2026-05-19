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

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

:root{
    --bg:#f3f7fb;
    --surface:#ffffff;
    --surface2:#f8fafc;

    --border:#dbe7f3;

    --text:#0f172a;
    --muted:#64748b;

    --primary:#2563eb;
    --primary-light:#eff6ff;

    --success:#10b981;
    --success-bg:#ecfdf5;

    --warning:#f59e0b;
    --warning-bg:#fffbeb;

    --danger:#ef4444;
    --danger-bg:#fef2f2;

    --shadow:0 10px 30px rgba(15,23,42,.08);

    --font:'DM Sans', sans-serif;
    --mono:'DM Mono', monospace;
}

body{
    background:var(--bg);
    font-family:var(--font);
    color:var(--text);
    padding:32px 24px;
}

/* HEADER */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:28px;
    flex-wrap:wrap;
    gap:16px;
}

.topbar h1{
    font-size:30px;
    font-weight:700;
    letter-spacing:-1px;
}

.top-actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.btn{
    text-decoration:none;
    padding:12px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    transition:.2s;
    border:1px solid transparent;
}

.btn-primary{
    background:var(--primary);
    color:white;
    box-shadow:0 10px 20px rgba(37,99,235,.15);
}

.btn-primary:hover{
    transform:translateY(-2px);
}

.btn-light{
    background:white;
    color:var(--text);
    border-color:var(--border);
}

.btn-light:hover{
    border-color:var(--primary);
    color:var(--primary);
}

/* TABLE CARD */

.table-card{
    background:var(--surface);
    border-radius:24px;
    border:1px solid var(--border);
    overflow:hidden;
    box-shadow:var(--shadow);
}

/* TABLE */

.table-wrapper{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:1000px;
}

thead{
    background:var(--surface2);
}

th{
    text-align:left;
    padding:18px 20px;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:var(--muted);
    font-weight:700;
    border-bottom:1px solid var(--border);
}

td{
    padding:18px 20px;
    border-bottom:1px solid #edf2f7;
    font-size:14px;
}

tbody tr{
    transition:.2s;
}

tbody tr:hover{
    background:#fafcff;
}

/* BADGES */

.estado{
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.pagada{
    background:var(--success-bg);
    color:var(--success);
}

.pendiente{
    background:var(--warning-bg);
    color:var(--warning);
}

.anulada{
    background:var(--danger-bg);
    color:var(--danger);
}

/* MONEY */

.money{
    font-family:var(--mono);
    font-weight:500;
}

/* ACTIONS */

.acciones{
    display:flex;
    gap:10px;
}

.action-btn{
    width:38px;
    height:38px;
    border-radius:10px;
    display:flex;
    justify-content:center;
    align-items:center;
    text-decoration:none;
    font-size:16px;
    transition:.2s;
}

.action-btn:hover{
    transform:translateY(-2px);
}

.edit{
    background:#eff6ff;
    color:var(--primary);
}

.delete{
    background:#fef2f2;
    color:var(--danger);
}

.pdf{
    background:#ecfdf5;
    color:var(--success);
}

/* RESPONSIVE */

@media(max-width:768px){

    body{
        padding:20px 14px;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
    }

    .topbar h1{
        font-size:24px;
    }

    .top-actions{
        width:100%;
    }

    .btn{
        width:100%;
        text-align:center;
    }
}

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