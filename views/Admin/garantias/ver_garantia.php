<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: iniciogarantias.php");
    exit;
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT g.*, p.nombre AS nombre_producto, f.fecha AS fecha_factura,
           c.nombre AS nombre_cliente
    FROM garantias g
    JOIN productos p ON g.id_producto = p.id_productos
    JOIN facturas f ON g.id_facturas = f.id_facturas
    JOIN clientes c ON f.id_clientes = c.id_clientes
    WHERE g.id_garantia = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$g = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$g) {
    header("Location: iniciogarantias.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle Garantía</title>
<link rel="stylesheet" href="../../../public/css/garantias/ver_garantia.css">

</head>

<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f4f7fb;
    font-family:'Inter',sans-serif;
    display:flex;
    justify-content:center;
    padding:40px 20px;
}

.container{
    width:100%;
    max-width:850px;
    background:white;
    border-radius:24px;
    padding:40px;
    box-shadow:0 10px 30px rgba(15,23,42,.08);
}

h1{
    font-size:32px;
    margin-bottom:30px;
    color:#0f172a;
}

.detalle{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.fila{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px;
    border-radius:16px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    gap:15px;
}

.fila span:first-child{
    font-weight:600;
    color:#475569;
}

.fila span:last-child{
    color:#0f172a;
    font-weight:500;
    text-align:right;
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

.acciones{
    margin-top:35px;
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.btn{
    text-decoration:none;
    padding:14px 20px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    transition:.3s;
}

.btn-volver{
    background:#e2e8f0;
    color:#334155;
}

.btn-volver:hover{
    background:#cbd5e1;
}

.btn-eliminar{
    background:#fee2e2;
    color:#dc2626;
}

.btn-eliminar:hover{
    background:#dc2626;
    color:white;
}

@media(max-width:700px){

    .container{
        padding:25px;
    }

    .fila{
        flex-direction:column;
        align-items:flex-start;
    }

    .fila span:last-child{
        text-align:left;
    }
}
    </style>
<div class="container">

    <h1>Garantía #<?= $g['id_garantia'] ?></h1>

    <div class="detalle">

        <div class="fila">
            <span>Cliente</span>
            <span><?= htmlspecialchars($g['nombre_cliente']) ?></span>
        </div>

        <div class="fila">
            <span>Factura asociada</span>
            <span>#<?= $g['id_facturas'] ?> — <?= $g['fecha_factura'] ?></span>
        </div>

        <div class="fila">
            <span>Producto</span>
            <span><?= htmlspecialchars($g['nombre_producto']) ?></span>
        </div>

        <div class="fila">
            <span>Motivo</span>
            <span><?= ucfirst($g['motivo']) ?></span>
        </div>

        <div class="fila">
            <span>Solución</span>
            <span><?= ucfirst($g['solucion']) ?></span>
        </div>

        <div class="fila">
            <span>Estado</span>
            <span>
                <?php
                if($g['estado'] == 'pendiente'){
                    echo "<span class='estado estado-pendiente'>Pendiente</span>";
                } elseif($g['estado'] == 'en_revision'){
                    echo "<span class='estado estado-revision'>En revisión</span>";
                } else {
                    echo "<span class='estado estado-resuelto'>Resuelto</span>";
                }
                ?>
            </span>
        </div>

        <div class="fila">
            <span>Fecha inicio</span>
            <span><?= $g['fecha_inicio'] ?></span>
        </div>

        <div class="fila">
            <span>Fecha fin</span>
            <span><?= $g['fecha_fin'] ?></span>
        </div>

    </div>

    <div class="acciones">
        <a class="btn btn-volver" href="iniciogarantias.php">⬅ Volver</a>
        <a class="btn btn-eliminar"
           href="eliminar_garantia.php?id=<?= $g['id_garantia'] ?>"
           onclick="return confirm('¿Eliminar esta garantía?')">🗑️ Eliminar</a>
    </div>

</div>
</body>
</html>