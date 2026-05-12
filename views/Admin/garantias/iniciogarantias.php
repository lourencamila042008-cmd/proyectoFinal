<?php
require_once "../../../config/db.php";

$conn = Database::Conectar();

$garantias = $conn->query("
    SELECT g.*, p.nombre AS nombre_producto
    FROM garantias g
    JOIN productos p ON g.id_producto = p.id_productos
    ORDER BY g.id_garantia DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Garantías</title>
<link rel="stylesheet" href="../../../public/css/garantias/garantias.css">

</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Inter',sans-serif;
    background:#f4f7fb;
    color:#1e293b;
    padding:30px;
}

.container{
    max-width:1400px;
    margin:auto;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:15px;
}

.topbar h1{
    font-size:32px;
    font-weight:700;
    color:#0f172a;
}

.btn{
    background:#1e3a5f;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    transition:.3s;
}

.btn:hover{
    background:#16304d;
    transform:translateY(-2px);
}

.alert{
    padding:14px 18px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:14px;
    font-weight:500;
}

.success{
    background:#dcfce7;
    color:#166534;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(15,23,42,.06);
}

thead{
    background:#f8fafc;
}

th{
    padding:18px;
    text-align:left;
    font-size:13px;
    color:#64748b;
    text-transform:uppercase;
    font-weight:600;
}

td{
    padding:18px;
    border-top:1px solid #e2e8f0;
    font-size:14px;
}

tr:hover{
    background:#f8fafc;
}

.estado{
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:600;
}

.estado-pendiente{
    background:#fef3c7;
    color:#92400e;
}

.estado-revision{
    background:#dbeafe;
    color:#1d4ed8;
}

.estado-resuelto{
    background:#dcfce7;
    color:#166534;
}

.btn-ver{
    text-decoration:none;
    background:#e0edff;
    color:#2563eb;
    padding:10px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    transition:.3s;
}

.btn-ver:hover{
    background:#2563eb;
    color:white;
}
</style>
<body>
<div class="container">

    <div class="topbar">
        <h1>Garantías</h1>
        <a class="btn" href="crear_garantia.php">+ Nueva garantía</a>
        <a class="btn" href="../dashboard_admin.php">volver al inicio</a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == "creado"){ ?>
        <div class="alert success">Garantía creada correctamente ✅</div>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Factura</th>
                <th>Producto</th>
                <th>Motivo</th>
                <th>Solución</th>
                <th>Estado</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php while($g = $garantias->fetch_assoc()){ ?>
            <tr>
                <td><?= $g['id_garantia'] ?></td>
                <td>#<?= $g['id_facturas'] ?></td>
                <td><?= htmlspecialchars($g['nombre_producto']) ?></td>
                <td><?= ucfirst($g['motivo']) ?></td>
                <td><?= ucfirst($g['solucion']) ?></td>
                <td>
                    <?php
                    if($g['estado'] == 'pendiente'){
                        echo "<span class='estado estado-pendiente'>Pendiente</span>";
                    } elseif($g['estado'] == 'en_revision'){
                        echo "<span class='estado estado-revision'>En revisión</span>";
                    } else {
                        echo "<span class='estado estado-resuelto'>Resuelto</span>";
                    }
                    ?>
                </td>
                <td><?= $g['fecha_inicio'] ?></td>
                <td><?= $g['fecha_fin'] ?></td>
                <td>
                    <a class="btn-ver" href="ver_garantia.php?id=<?= $g['id_garantia'] ?>">Ver</a>
                    
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</body>
</html>