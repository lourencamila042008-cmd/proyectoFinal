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